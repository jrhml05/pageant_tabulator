<?php

namespace Tests\Feature;

use App\Http\Livewire\Judge\ScoreSheet;
use App\Models\Candidate;
use App\Models\OpenSegment;
use App\Models\Score;
use App\Models\ScoreLock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ScoreSheetTest extends TestCase
{
    use RefreshDatabase;

    private User $judge;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->judge = User::where('username', 'prejudge1')->first();
        OpenSegment::create(['segment' => 'talent']);
    }

    public function test_entries_save_as_the_judge_types(): void
    {
        $candidate = Candidate::division('ms')->first();

        $this->sheet()->set("points.c{$candidate->id}.skill", '7.25');

        $this->assertSame('7.25', $this->points($candidate, 'skill'));
    }

    public function test_entries_save_when_the_browser_sends_a_whole_candidate(): void
    {
        // Livewire 4 in the browser batches a candidate's fields into one "points.c{id}" update.
        [$ms, $mr] = [Candidate::division('ms')->first(), Candidate::division('mr')->first()];

        $this->sheet()->set([
            "points.c{$ms->id}" => ['skill' => '7.5', 'performance' => '6.25'],
            "points.c{$mr->id}" => ['skill' => '5', 'performance' => '51'],
        ]);

        $this->assertSame('7.5', $this->points($ms, 'skill'));
        $this->assertSame('6.25', $this->points($ms, 'performance'));
        $this->assertSame('5', $this->points($mr, 'skill'));
        $this->assertNull($this->points($mr, 'performance'));
    }

    public function test_an_out_of_range_entry_is_saved_blank_and_flagged(): void
    {
        $candidate = Candidate::division('mr')->first();

        $this->sheet()
            ->set("points.c{$candidate->id}.skill", '7')
            ->set("points.c{$candidate->id}.skill", '50.5')
            ->assertSee('Enter 0 to 50, up to 2 decimals.');

        $this->assertNull($this->points($candidate, 'skill'));
    }

    public function test_a_closed_segment_refuses_changes(): void
    {
        $candidate = Candidate::division('ms')->first();
        $sheet = $this->sheet();
        OpenSegment::whereKey('talent')->delete();

        $sheet->set("points.c{$candidate->id}.skill", '5')
            ->assertSet("points.c{$candidate->id}.skill", '')
            ->assertSee('The tabulator has closed Talent');

        $this->assertNull($this->points($candidate, 'skill'));
    }

    public function test_a_judge_cannot_score_another_panels_segment(): void
    {
        OpenSegment::create(['segment' => 'swim_wear']);
        $candidate = Candidate::division('ms')->first();

        Livewire::actingAs($this->judge)->test(ScoreSheet::class, ['segment' => 'swim_wear'])
            ->set("points.c{$candidate->id}.beauty", '9');

        $this->assertSame(0, Score::count());
    }

    public function test_lock_in_needs_every_score_then_blocks_changes(): void
    {
        $sheet = $this->sheet();
        $candidates = Candidate::all();

        $sheet->call('lock')->assertSee('Some scores are missing');
        $this->assertSame(0, ScoreLock::count());

        foreach ($candidates as $candidate) {
            $sheet->set("points.c{$candidate->id}.skill", '7')->set("points.c{$candidate->id}.performance", '6.5');
        }
        $sheet->assertSee('18 of 18 scored')->call('lock');

        $this->assertTrue(ScoreLock::where('segment', 'talent')->where('judge_id', $this->judge->id)->exists());

        $first = $candidates->first();
        $sheet->set("points.c{$first->id}.skill", '1')->assertSee('locked in');
        $this->assertSame('7', $this->points($first, 'skill'));
    }

    public function test_round_one_pairs_ms_and_mr_by_number(): void
    {
        $this->sheet()->assertSeeInOrder(['No. 1', 'Ms. LCUAA', 'Mr. LCUAA', 'No. 2']);
    }

    public function test_the_judge_can_show_one_division_and_the_choice_is_remembered(): void
    {
        $this->sheet()
            ->assertSet('viewMode', 'pairs')
            ->call('showView', 'mr')
            ->assertSet('viewMode', 'mr')
            ->assertSee('Showing Mr. LCUAA candidates only.')
            ->assertSee('Mr. LCUAA')
            ->assertDontSee('Ms. LCUAA')
            ->assertSee('aria-pressed="true"', false);

        // A new sheet (the next segment, or a reload) opens in the same view.
        $this->sheet()->assertSet('viewMode', 'mr')->assertDontSee('Ms. LCUAA');

        $this->sheet()->call('showView', 'pairs')->assertSeeInOrder(['No. 1', 'Ms. LCUAA', 'Mr. LCUAA']);
    }

    public function test_an_unknown_view_is_ignored(): void
    {
        $this->sheet()->call('showView', 'everyone')->assertSet('viewMode', 'pairs');
    }

    public function test_judges_see_candidate_numbers_without_schools(): void
    {
        $this->sheet()->assertSee('No. 1')->assertDontSee('Pateros Technological College');
    }

    public function test_one_division_view_still_saves_and_points_to_the_other_division(): void
    {
        $sheet = $this->sheet()->call('showView', 'mr');

        foreach (Candidate::division('mr')->get() as $candidate) {
            $sheet->set("points.c{$candidate->id}", ['skill' => '6', 'performance' => '6']);
        }

        $sheet->assertSee('9 of 18 scored')
            ->assertSee('Go to Ms. candidates (9 left)')
            ->assertDontSee('Next to score');
        $this->assertSame(18, Score::whereNotNull('points')->count());

        $sheet->call('showView', 'ms')->assertSee('Next to score: Ms. No. 1');
    }

    public function test_round_two_lists_only_finalists(): void
    {
        OpenSegment::create(['segment' => 'final']);
        Candidate::where('division', 'ms')->where('number', 4)->update(['is_finalist' => true]);
        $judge = User::where('username', 'judge1')->first();

        Livewire::actingAs($judge)->test(ScoreSheet::class, ['segment' => 'final'])
            ->assertSee('0 of 1 scored')
            ->assertSee('Intelligence');
    }

    public function test_casual_wear_ranks_every_candidate_for_the_pageant_night_panel(): void
    {
        Candidate::where('division', 'ms')->where('number', 4)->update(['is_finalist' => true]);
        $candidate = Candidate::division('mr')->first();

        $this->rankSheet()
            ->assertSee('Special award')
            ->assertSee('0 of 18 ranked')
            ->assertSee('Lock in ranking')
            ->assertSee('Your ranking')
            ->assertSeeInOrder(['No. 1', 'Ms. LCUAA', 'Mr. LCUAA', 'No. 2'])
            ->call('rank', $candidate->id, 1)
            ->assertSee('1 of 18 ranked');

        $this->assertSame([$candidate->id => 1], $this->ranks());
    }

    public function test_giving_a_taken_rank_moves_it_and_says_so(): void
    {
        [$one, $two] = Candidate::division('ms')->take(2)->get();

        $this->rankSheet()
            ->call('rank', $one->id, 1)
            ->call('rank', $two->id, 3)
            ->call('rank', $two->id, 1)
            ->assertSet('moved', 'Rank 1 moved from Ms. No. 1 to Ms. No. 2. Give Ms. No. 1 a rank again.')
            ->assertSet("points.c{$one->id}.rank", '')
            ->assertSet("points.c{$two->id}.rank", '1');

        $this->assertSame([$two->id => 1], $this->ranks());
    }

    public function test_tapping_a_candidates_own_rank_again_keeps_it(): void
    {
        $candidate = Candidate::division('ms')->first();

        $this->rankSheet()->call('rank', $candidate->id, 2)->call('rank', $candidate->id, 2);

        $this->assertSame([$candidate->id => 2], $this->ranks());
    }

    public function test_each_division_is_ranked_on_its_own(): void
    {
        $ms = Candidate::division('ms')->first();
        $mr = Candidate::division('mr')->first();

        $this->rankSheet()->call('rank', $ms->id, 1)->call('rank', $mr->id, 1)->assertSet('moved', null);

        $this->assertSame([$ms->id => 1, $mr->id => 1], $this->ranks());
    }

    public function test_a_rank_outside_the_division_or_typed_in_is_refused(): void
    {
        $candidate = Candidate::division('ms')->first();

        $this->rankSheet()
            ->call('rank', $candidate->id, 10)
            ->call('rank', $candidate->id, 0)
            ->set("points.c{$candidate->id}.rank", '4')
            ->set("points.c{$candidate->id}", ['rank' => '5'])
            ->assertSet("points.c{$candidate->id}.rank", '');

        $this->assertSame([], $this->ranks());
    }

    public function test_a_ranking_locks_in_once_every_candidate_has_a_rank(): void
    {
        $sheet = $this->rankSheet();
        $candidates = Candidate::orderBy('division')->orderBy('number')->get();
        foreach ($candidates->slice(1) as $candidate) {
            $sheet->call('rank', $candidate->id, $candidate->number);
        }

        $sheet->assertSee('17 of 18 ranked')->assertSee('Next to rank: Mr. No. 1')->call('lock');
        $this->assertFalse(ScoreLock::where('segment', 'casual_wear')->exists());

        $first = $candidates->first();
        $sheet->call('rank', $first->id, $first->number)->call('lock')->assertSee('Your ranking is locked in');
        $this->assertTrue(ScoreLock::where(['segment' => 'casual_wear', 'judge_id' => $this->casualJudge()->id])->exists());

        $sheet->call('rank', $first->id, 2)->assertSet('notice', 'Your scores for this segment are locked in, so that change was not saved.');
        $this->assertSame($first->number, $this->ranks()[$first->id]);
    }

    public function test_a_closed_ranked_segment_refuses_ranks(): void
    {
        $candidate = Candidate::division('ms')->first();
        $sheet = $this->rankSheet();
        OpenSegment::whereKey('casual_wear')->delete();

        $sheet->call('rank', $candidate->id, 1)->assertSet('notice', 'The tabulator has closed Casual wear, so that change was not saved.');
        $this->assertSame([], $this->ranks());
    }

    public function test_a_judge_can_clear_their_own_ranking(): void
    {
        $ms = Candidate::division('ms')->first();
        $mr = Candidate::division('mr')->first();
        $other = User::where('username', 'judge2')->first();
        Score::create(['segment' => 'casual_wear', 'candidate_id' => $ms->id, 'judge_id' => $other->id, 'criterion' => 'rank', 'points' => 4]);

        $this->rankSheet()->assertDontSee('Clear ranking')
            ->call('rank', $ms->id, 1)->call('rank', $mr->id, 2)
            ->assertSee('Clear ranking')->assertSee('Clear your Ms. and Mr. ranking?')
            ->call('clearRanking')
            ->assertSet("points.c{$ms->id}.rank", '')
            ->assertSee('0 of 18 ranked');

        $this->assertSame([], $this->ranks());
        $this->assertTrue(Score::where(['segment' => 'casual_wear', 'judge_id' => $other->id])->exists());
    }

    public function test_clearing_in_one_division_view_leaves_the_other_division(): void
    {
        $ms = Candidate::division('ms')->first();
        $mr = Candidate::division('mr')->first();

        $this->rankSheet()->call('rank', $ms->id, 1)->call('rank', $mr->id, 1)
            ->call('showView', 'mr')->assertSee('Clear Mr. ranking')
            ->call('clearRanking');

        $this->assertSame([$ms->id => 1], $this->ranks());
    }

    public function test_a_locked_ranking_cannot_be_cleared(): void
    {
        $candidate = Candidate::division('ms')->first();
        $sheet = $this->rankSheet()->call('rank', $candidate->id, 1);
        ScoreLock::create(['segment' => 'casual_wear', 'judge_id' => $this->casualJudge()->id]);

        $sheet->call('clearRanking')->assertSet('notice', 'Your scores for this segment are locked in, so that change was not saved.');
        $this->assertSame([$candidate->id => 1], $this->ranks());
    }

    private function rankSheet()
    {
        OpenSegment::firstOrCreate(['segment' => 'casual_wear']);

        return Livewire::actingAs($this->casualJudge())->test(ScoreSheet::class, ['segment' => 'casual_wear']);
    }

    private function casualJudge(): User
    {
        return User::where('username', 'judge1')->first();
    }

    /** @return array<int, int> this judge's saved Casual wear ranks by candidate id */
    private function ranks(): array
    {
        return Score::where(['segment' => 'casual_wear', 'judge_id' => $this->casualJudge()->id, 'criterion' => 'rank'])
            ->whereNotNull('points')->orderBy('candidate_id')->pluck('points', 'candidate_id')->map(fn ($p) => (int) $p)->all();
    }

    private function sheet()
    {
        return Livewire::actingAs($this->judge)->test(ScoreSheet::class, ['segment' => 'talent']);
    }

    private function points(Candidate $candidate, string $criterion): ?string
    {
        $points = Score::where(['segment' => 'talent', 'candidate_id' => $candidate->id, 'judge_id' => $this->judge->id, 'criterion' => $criterion])->value('points');

        return $points === null ? null : rtrim(rtrim($points, '0'), '.');
    }
}
