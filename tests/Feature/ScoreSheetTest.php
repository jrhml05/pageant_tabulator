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
            "points.c{$mr->id}" => ['skill' => '5', 'performance' => '9'],
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
            ->set("points.c{$candidate->id}.skill", '8')
            ->assertSee('Enter 0 to 7.5, up to 2 decimals.');

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
        $sheet->assertSee('24 of 24 scored')->call('lock');

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

    public function test_one_division_view_still_saves_and_points_to_the_other_division(): void
    {
        $sheet = $this->sheet()->call('showView', 'mr');

        foreach (Candidate::division('mr')->get() as $candidate) {
            $sheet->set("points.c{$candidate->id}", ['skill' => '6', 'performance' => '6']);
        }

        $sheet->assertSee('12 of 24 scored')
            ->assertSee('Go to Ms. candidates (12 left)')
            ->assertDontSee('Next to score');
        $this->assertSame(24, Score::whereNotNull('points')->count());

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
