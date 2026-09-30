<?php

namespace Tests\Feature;

use App\Models\Candidate;
use App\Models\OpenSegment;
use App\Models\Score;
use App\Models\ScoreLock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PagesTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->admin = User::where('username', 'admin1')->first();
    }

    public function test_sign_in_uses_the_username(): void
    {
        $this->post('/login', ['username' => 'judge3', 'password' => 'judge3'])->assertRedirect(route('judge.app'));
        $this->assertAuthenticatedAs(User::where('username', 'judge3')->first());
    }

    public function test_admins_sign_in_to_scoring_control(): void
    {
        $this->post('/login', ['username' => 'admin2', 'password' => 'admin2'])->assertRedirect(route('home'));
    }

    public function test_a_wrong_password_is_refused(): void
    {
        $this->post('/login', ['username' => 'judge1', 'password' => 'nope'])->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_every_admin_page_renders(): void
    {
        $this->seedSomeScores();
        $this->actingAs($this->admin);

        $this->get('/home')->assertOk()->assertSee('Talent')->assertSee('Round 2 (top 5)');
        $this->get('/candidates')->assertOk();
        $this->get('/candidates/ms/create')->assertOk();
        $this->get('/candidates/mr/3/edit')->assertOk()->assertSee('No. 3');
        $this->get('/judges')->assertOk()->assertSee('prejudge1')->assertSee('judge5');
        $this->get('/judges/create')->assertOk();

        foreach (['ms', 'mr'] as $division) {
            $this->get("/results/{$division}/round-1")->assertOk()->assertSee('Top 5 finalists');
            $this->get("/results/{$division}/round-1/print")->assertOk()->assertHeader('content-type', 'application/pdf');
            foreach (array_keys(config('pageant.segments')) as $segment) {
                $this->get("/results/{$division}/{$segment}")->assertOk();
                $this->get("/results/{$division}/{$segment}?judge=1")->assertOk();
                $this->get("/results/{$division}/{$segment}/print")->assertOk()->assertHeader('content-type', 'application/pdf');
                $this->get("/results/{$division}/{$segment}/print?judge=1")->assertOk();
            }
        }

        $this->get('/results/ms/talent?judge=4')->assertNotFound();
    }

    public function test_results_pages_switch_division_in_place_and_the_sidebar_follows(): void
    {
        $this->actingAs($this->admin);

        // One results list in the sidebar, not one per division.
        $sidebar = $this->sidebar('/home');
        $this->assertSame(1, substr_count($sidebar, 'Overall and finalists'));
        $this->assertStringNotContainsString('Ms. LCUAA results', $sidebar);
        $this->assertStringContainsString('href="'.route('results.segment', ['ms', 'talent']).'"', $sidebar);

        // The switch keeps the page and the judge tab, and marks the division shown.
        $this->get('/results/ms/talent?judge=2')->assertOk()
            ->assertSee('href="'.route('results.segment', ['mr', 'talent']).'?judge=2"', false)
            ->assertSeeInOrder(['aria-label="Division"', 'aria-current="page"', 'Ms. LCUAA'], false);

        $this->get('/results/mr/round-1')->assertOk()
            ->assertSee('href="'.route('results.round1', 'ms').'"', false);

        // Having looked at Mr., the sidebar now opens Mr. results.
        $sidebar = $this->sidebar('/home');
        $this->assertStringContainsString('href="'.route('results.segment', ['mr', 'talent']).'"', $sidebar);
        $this->assertStringNotContainsString(route('results.segment', ['ms', 'talent']), $sidebar);
    }

    private function sidebar(string $url): string
    {
        $html = $this->get($url)->assertOk()->getContent();
        $start = strpos($html, '<nav aria-label="Admin"');

        return substr($html, $start, strpos($html, '</nav>', $start) - $start);
    }

    public function test_judges_cannot_reach_admin_pages(): void
    {
        $this->actingAs(User::where('username', 'judge1')->first())->get('/home')->assertRedirect(route('judge.app'));
    }

    public function test_opening_a_segment_sends_its_panel_straight_to_the_sheet(): void
    {
        $this->actingAs($this->admin)->post('/segments/swim_wear/open')->assertRedirect(route('home'));

        $judge = User::where('username', 'judge2')->first();
        $this->actingAs($judge)->get('/judge-app')->assertRedirect(route('judge.sheet', 'swim_wear'));
        $this->actingAs($judge)->get('/judge-app/swim_wear')->assertOk()->assertSee('Beauty &amp; complexion', false);
        $this->actingAs($judge)->getJson('/judge-app/status')->assertExactJson(['open' => ['Swim wear']]);

        // The pre-pageant panel keeps waiting.
        $pre = User::where('username', 'prejudge1')->first();
        $this->actingAs($pre)->get('/judge-app')->assertOk()->assertSee('Nothing to score right now');
        $this->actingAs($pre)->get('/judge-app/swim_wear')->assertRedirect(route('judge.app'));

        $this->actingAs($this->admin)->post('/segments/swim_wear/close');
        $this->assertSame(0, OpenSegment::count());
    }

    public function test_unlock_removes_a_judges_lock(): void
    {
        $judge = User::where('username', 'judge1')->first();
        ScoreLock::create(['segment' => 'wit', 'judge_id' => $judge->id]);

        $this->actingAs($this->admin)->delete("/segments/wit/locks/{$judge->id}")->assertRedirect(route('home'));

        $this->assertSame(0, ScoreLock::count());
    }

    public function test_saving_finalists_replaces_the_previous_set(): void
    {
        $ms = Candidate::division('ms')->get();
        $ms[0]->update(['is_finalist' => true]);
        $mr = Candidate::division('mr')->first();

        $this->actingAs($this->admin)
            ->put('/results/ms/finalists', ['finalists' => $ms->slice(1, 5)->pluck('id')->all()])
            ->assertRedirect(route('results.round1', 'ms'));

        $this->assertSame($ms->slice(1, 5)->pluck('id')->values()->all(), Candidate::where('is_finalist', true)->orderBy('number')->pluck('id')->all());

        // A candidate from the other division is refused.
        $this->actingAs($this->admin)->put('/results/ms/finalists', ['finalists' => [$mr->id]])->assertSessionHasErrors('finalists.0');
    }

    public function test_judge_accounts_use_a_username_and_a_panel(): void
    {
        $this->actingAs($this->admin)->post('/judges', [
            'name' => 'Guest judge', 'username' => 'judge6', 'panel' => 'pageant', 'password' => 'secret1',
        ])->assertRedirect(route('judges.index'));

        $this->assertSame('pageant', User::where('username', 'judge6')->value('panel'));

        $this->actingAs($this->admin)->post('/judges', [
            'name' => 'Duplicate', 'username' => 'judge6', 'panel' => '', 'password' => 'secret1',
        ])->assertSessionHasErrors(['username', 'panel']);
    }

    public function test_a_candidates_college_or_university_is_saved_and_shown(): void
    {
        $this->actingAs($this->admin)->get('/candidates/ms/create')->assertSee('College/University');

        $this->actingAs($this->admin)
            ->put('/candidates/ms/2', ['school' => 'Laguna College'])
            ->assertRedirect(route('candidates.index'));
        $this->assertSame('Laguna College', Candidate::division('ms')->where('number', 2)->value('school'));
        $this->actingAs($this->admin)->get('/candidates')->assertSee('Laguna College');

        $this->actingAs($this->admin)
            ->put('/candidates/ms/2', ['school' => str_repeat('x', 256)])
            ->assertSessionHasErrors(['school' => 'The college/university must not be greater than 255 characters.']);
    }

    public function test_deleting_a_candidate_deletes_their_scores(): void
    {
        $candidate = Candidate::division('mr')->first();
        Score::create(['segment' => 'talent', 'candidate_id' => $candidate->id, 'judge_id' => User::where('username', 'prejudge1')->value('id'), 'criterion' => 'skill', 'points' => 5]);

        $this->actingAs($this->admin)->delete("/candidates/mr/{$candidate->number}")->assertRedirect(route('candidates.index'));

        $this->assertSame(0, Score::count());
    }

    private function seedSomeScores(): void
    {
        $judge = User::where('username', 'judge1')->first();
        foreach (Candidate::all() as $candidate) {
            Score::create(['segment' => 'wit', 'candidate_id' => $candidate->id, 'judge_id' => $judge->id, 'criterion' => 'substance', 'points' => 10]);
            Score::create(['segment' => 'wit', 'candidate_id' => $candidate->id, 'judge_id' => $judge->id, 'criterion' => 'delivery', 'points' => 6]);
        }
        Candidate::whereIn('number', [1, 2])->update(['is_finalist' => true]);
    }
}
