<?php

namespace Tests\Feature;

use App\Models\Candidate;
use App\Models\OpenSegment;
use App\Models\Score;
use App\Models\ScoreLock;
use App\Models\User;
use App\Scoring\Segment;
use App\Scoring\Tabulator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
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
                $this->get("/results/{$division}/{$segment}/winner")->assertOk()->assertHeader('content-type', 'application/pdf');
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

    public function test_round_one_flags_saved_finalists_that_differ_from_the_top_five(): void
    {
        // Mr. No. 1 to 7, ranked in number order by both panels.
        Candidate::division('mr')->where('number', '>', 7)->delete();
        $mr = Candidate::division('mr')->orderBy('number')->get()->keyBy('number');
        $this->scoreRoundOne($mr, [1 => 0.9, 2 => 0.85, 3 => 0.8, 4 => 0.75, 5 => 0.7, 6 => 0.65, 7 => 0.6]);
        $this->actingAs($this->admin);
        $save = fn (array $numbers) => $this->put('/results/mr/finalists', ['finalists' => $mr->whereIn('number', $numbers)->pluck('id')->all()]);

        // Nothing saved yet: no notice.
        $this->assertNull($this->finalistsNotice());

        $save([1, 2, 3, 4, 6]);
        $this->assertSame("Saved finalists don't match the current top 5: No. 5 is now in, No. 6 is out. Update the ticks under Top 5 finalists and save.", $this->finalistsNotice());

        $save([1, 2, 3, 4, 5]);
        $this->assertNull($this->finalistsNotice());

        // No. 5 and No. 6 tie at the cut: either one is the board's call, not a mismatch.
        Score::query()->delete();
        $this->scoreRoundOne($mr, [1 => 0.9, 2 => 0.85, 3 => 0.8, 4 => 0.75, 5 => 0.7, 6 => 0.7, 7 => 0.6]);
        $this->assertNull($this->finalistsNotice());
        $save([1, 2, 3, 4, 6]);
        $this->assertNull($this->finalistsNotice());

        $save([1, 2, 3, 4, 7]);
        $this->assertSame("Saved finalists don't match the current top 5: No. 7 is out. No. 5 and No. 6 are tied at the cut, so the board decides between them. Update the ticks under Top 5 finalists and save.", $this->finalistsNotice());
    }

    public function test_changing_finalists_after_round_two_unlocks_it_for_the_new_finalist(): void
    {
        $ms = Candidate::division('ms')->orderBy('number')->get()->keyBy('number');
        $this->roundTwoScoredAndLocked($ms, [1, 2, 3, 4, 5]);
        $this->actingAs($this->admin);

        // Confirm first: Round 2 is under way.
        $this->get('/results/ms/round-1')->assertSee('data-confirm="Round 2 is already scored.', false);

        // No. 6 replaces No. 5.
        $this->put('/results/ms/finalists', ['finalists' => $ms->only([1, 2, 3, 4, 6])->pluck('id')->all()])
            ->assertSessionHas('success', '5 Ms. LCUAA finalists saved. Round 2 sheets are unlocked so the judges can score No. 6 once you open Round 2. '
                .'They lock in again when done. No. 5 has Round 2 scores that no longer count. They are kept and count again if you tick that candidate again.');

        $this->assertSame(0, ScoreLock::where('segment', 'final')->count());
        // Round 1 locks are untouched, and No. 5's Round 2 scores are kept.
        $this->assertSame(1, ScoreLock::where('segment', 'wit')->count());
        $this->assertSame(10, Score::where('segment', 'final')->where('candidate_id', $ms[5]->id)->count());
    }

    public function test_saving_finalists_who_are_all_scored_keeps_round_two_locks(): void
    {
        $ms = Candidate::division('ms')->orderBy('number')->get()->keyBy('number');
        $this->roundTwoScoredAndLocked($ms, [1, 2, 3, 4, 5]);

        // The same set again, then one fewer: every finalist left is already scored.
        $this->actingAs($this->admin)->put('/results/ms/finalists', ['finalists' => $ms->only([1, 2, 3, 4, 5])->pluck('id')->all()])
            ->assertSessionHas('success', '5 Ms. LCUAA finalists saved.');
        $this->put('/results/ms/finalists', ['finalists' => $ms->only([1, 2, 3, 4])->pluck('id')->all()]);

        $this->assertSame(5, ScoreLock::where('segment', 'final')->count());
    }

    public function test_round_one_saves_finalists_without_a_confirm_before_round_two(): void
    {
        $this->actingAs($this->admin)->get('/results/ms/round-1')->assertDontSee('data-confirm="Round 2', false);
    }

    public function test_a_locked_sheet_missing_a_finalist_is_not_reported_as_final(): void
    {
        $ms = Candidate::division('ms')->orderBy('number')->get()->keyBy('number');
        $this->roundTwoScoredAndLocked($ms, [1, 2, 3, 4, 5]);
        // No. 6 became a finalist after the judges locked in.
        $ms[6]->update(['is_finalist' => true]);

        $this->assertSame([6], Segment::find('final')->unscored()->get('ms')->pluck('number')->all());
        $this->assertFalse(Segment::find('final')->unscored()->has('mr'));

        $this->actingAs($this->admin);
        $this->get('/results/ms/final')
            ->assertSeeInOrder(['All 5 judges locked in, but No. 6', "isn't scored by every judge, so ranks wait for them."])
            ->assertDontSee('These results are final');
        $this->get('/home')->assertSee("All 5 judges locked in, but Ms. No. 6 isn't scored by every judge");
        $this->get('/results/ms/final/winner')->assertOk();
    }

    /** The pageant night judges score these finalists (by number) in Round 2 and lock in. */
    private function roundTwoScoredAndLocked(Collection $candidates, array $numbers): void
    {
        $judges = User::onPanel('pageant')->get();
        foreach ($numbers as $i => $number) {
            $candidates[$number]->update(['is_finalist' => true]);
            foreach ($judges as $j => $judge) {
                foreach (['impression' => 40 - $i, 'intelligence' => 40 - $j] as $criterion => $points) {
                    Score::create(['segment' => 'final', 'candidate_id' => $candidates[$number]->id, 'judge_id' => $judge->id, 'criterion' => $criterion, 'points' => $points]);
                }
            }
        }
        foreach ($judges as $judge) {
            ScoreLock::create(['segment' => 'final', 'judge_id' => $judge->id]);
        }
        ScoreLock::create(['segment' => 'wit', 'judge_id' => $judges[0]->id]);
    }

    /** The finalists notice in the live-refreshed region of Mr. Round 1, as plain text, or null. */
    private function finalistsNotice(): ?string
    {
        $html = $this->get('/results/mr/round-1')->assertOk()->getContent();
        $start = strpos($html, 'data-refresh-region');
        $region = substr($html, $start, strpos($html, '</div>', $start) - $start);
        $text = trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags('<'.$region), ENT_QUOTES)));

        return preg_match('/Saved finalists .*? and save\./', $text, $match) ? $match[0] : null;
    }

    /** Every Round 1 judge gives each candidate (by number) that share of the points on every criterion. */
    private function scoreRoundOne(Collection $candidates, array $shares): void
    {
        foreach (Segment::inRound(1) as $segment) {
            foreach (User::onPanel($segment->panel)->get() as $judge) {
                foreach ($shares as $number => $share) {
                    foreach ($segment->criteria as $criterion => $c) {
                        Score::create(['segment' => $segment->key, 'candidate_id' => $candidates[$number]->id, 'judge_id' => $judge->id, 'criterion' => $criterion, 'points' => round($c['max'] * $share, 2)]);
                    }
                }
            }
        }
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

    public function test_segment_sheets_show_each_judges_rank_under_their_total(): void
    {
        $judges = User::onPanel('prepageant')->get();
        // Judge 1 scores every Ms. candidate, rising with the number; judge 2 scores only No. 1.
        foreach (Candidate::division('ms')->get() as $candidate) {
            foreach (['skill' => $candidate->number / 2, 'performance' => 0] as $criterion => $points) {
                Score::create(['segment' => 'talent', 'candidate_id' => $candidate->id, 'judge_id' => $judges[0]->id, 'criterion' => $criterion, 'points' => $points]);
            }
        }
        $first = Candidate::division('ms')->where('number', 1)->first();
        Score::create(['segment' => 'talent', 'candidate_id' => $first->id, 'judge_id' => $judges[1]->id, 'criterion' => 'skill', 'points' => 7]);
        Score::create(['segment' => 'talent', 'candidate_id' => $first->id, 'judge_id' => $judges[1]->id, 'criterion' => 'performance', 'points' => 7]);
        $last = Candidate::division('ms')->count();

        $page = $this->actingAs($this->admin)->get('/results/ms/talent')->assertOk()->assertSee('rank 1')->assertSee("rank {$last}");
        // Judge 2 hasn't scored everyone, so only judge 1's ranks appear: one per candidate.
        $this->assertSame($last, substr_count($page->getContent(), '<span class="sub">rank '));
    }

    public function test_segment_sheets_list_candidates_by_number_and_print_the_winner(): void
    {
        $candidates = Candidate::division('ms')->orderBy('number')->take(3)->get();
        Candidate::division('ms')->whereNotIn('id', $candidates->pluck('id'))->delete();
        // Every pageant-night judge scores No. 3 highest and No. 1 lowest.
        foreach (User::onPanel('pageant')->get() as $judge) {
            foreach ($candidates as $candidate) {
                foreach (['substance' => 4 * $candidate->number, 'delivery' => 2 * $candidate->number] as $criterion => $points) {
                    Score::create(['segment' => 'wit', 'candidate_id' => $candidate->id, 'judge_id' => $judge->id, 'criterion' => $criterion, 'points' => $points]);
                }
            }
        }
        $this->actingAs($this->admin);

        $this->get('/results/ms/wit')->assertOk()
            ->assertSeeInOrder(['No. 1', 'No. 2', 'No. 3'])
            ->assertSee('href="'.route('results.segment.winner', ['ms', 'wit']).'"', false);
        $this->get('/results/ms/wit?judge=1')->assertOk()->assertDontSee(route('results.segment.winner', ['ms', 'wit']));

        $result = (new Tabulator)->segment(Segment::find('wit'), 'ms');
        $html = view('admin.results.pdf.winner', [
            'label' => 'Ms. LCUAA',
            'result' => $result,
            'winners' => collect($result['rows'])->where('rank', 1)->values(),
        ])->render();
        $this->assertStringContainsString('No. 3', $html);
        $this->assertStringNotContainsString('No. 1<', $html);
        $this->assertStringContainsString('0 of 5 judges have locked in', $html);
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
