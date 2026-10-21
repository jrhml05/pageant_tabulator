<?php

namespace Tests\Feature;

use App\Http\Livewire\Judge\ScoreSheet;
use App\Models\Candidate;
use App\Models\OpenSegment;
use App\Models\Score;
use App\Models\ScoreLock;
use App\Models\User;
use App\Scoring\ScoreReset;
use App\Scoring\Segment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ScoreResetTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->admin = User::where('username', 'admin1')->first();

        // Every judge scores every candidate on every criterion, and locks in.
        foreach (Segment::all() as $segment) {
            foreach ($segment->judges() as $judge) {
                foreach (Candidate::all() as $candidate) {
                    foreach (array_keys($segment->criteria) as $criterion) {
                        Score::create(['segment' => $segment->key, 'candidate_id' => $candidate->id, 'judge_id' => $judge->id, 'criterion' => $criterion, 'points' => 5]);
                    }
                }
                ScoreLock::create(['segment' => $segment->key, 'judge_id' => $judge->id]);
            }
        }
    }

    public function test_the_page_counts_what_each_reset_deletes(): void
    {
        $perCriterion = Candidate::count() * 3;

        $this->actingAs($this->admin)->get(route('reset.index'))->assertOk()
            ->assertSee('Thematic wear')
            ->assertSee('Relevance of the costume')
            ->assertSee("{$perCriterion} scores")
            ->assertSee('Clear everything');
    }

    public function test_a_criterion_reset_deletes_only_that_criterion_and_the_segments_locks(): void
    {
        $this->actingAs($this->admin)->delete(route('reset.criterion', ['talent', 'skill']))
            ->assertRedirect(route('reset.index'));

        $this->assertSame(0, Score::where('segment', 'talent')->where('criterion', 'skill')->count());
        $this->assertSame(Candidate::count() * 3, Score::where('segment', 'talent')->where('criterion', 'performance')->count());
        $this->assertFalse(ScoreLock::where('segment', 'talent')->exists());
        $this->assertTrue(ScoreLock::where('segment', 'thematic_wear')->exists());
    }

    public function test_an_unknown_criterion_is_not_found(): void
    {
        $this->actingAs($this->admin)->delete(route('reset.criterion', ['talent', 'nope']))->assertNotFound();
        $this->assertSame(Candidate::count() * 3, Score::where('segment', 'talent')->where('criterion', 'skill')->count());
    }

    public function test_a_segment_reset_leaves_the_other_segments(): void
    {
        $this->actingAs($this->admin)->delete(route('reset.segment', 'swim_wear'));

        $this->assertFalse(Score::where('segment', 'swim_wear')->exists());
        $this->assertFalse(ScoreLock::where('segment', 'swim_wear')->exists());
        $this->assertTrue(Score::where('segment', 'formal_wear')->exists());
        $this->assertTrue(ScoreLock::where('segment', 'formal_wear')->exists());
    }

    public function test_a_round_reset_covers_its_segments_and_keeps_finalists(): void
    {
        Candidate::where('number', 1)->update(['is_finalist' => true]);
        OpenSegment::create(['segment' => 'wit']);

        $this->actingAs($this->admin)->delete(route('reset.round', 1))
            ->assertSessionHas('success', fn ($message) => str_contains($message, 'Saved finalists stay'));

        $this->assertFalse(Score::where('segment', '!=', 'final')->exists());
        $this->assertFalse(ScoreLock::where('segment', '!=', 'final')->exists());
        $this->assertTrue(Score::where('segment', 'final')->exists());
        $this->assertTrue(ScoreLock::where('segment', 'final')->exists());
        $this->assertSame(2, Candidate::where('is_finalist', true)->count());
        $this->assertTrue(OpenSegment::whereKey('wit')->exists());
    }

    public function test_clearing_everything_matches_scores_clear(): void
    {
        Candidate::where('number', 1)->update(['is_finalist' => true, 'announce_order' => 1]);
        OpenSegment::create(['segment' => 'final']);

        $this->actingAs($this->admin)->delete(route('reset.everything'));

        $this->assertSame(0, Score::count());
        $this->assertSame(0, ScoreLock::count());
        $this->assertSame(0, OpenSegment::count());
        $this->assertFalse(Candidate::where('is_finalist', true)->orWhereNotNull('announce_order')->exists());
    }

    public function test_judges_cannot_reset(): void
    {
        $this->actingAs(User::where('username', 'judge1')->first())->delete(route('reset.segment', 'wit'))->assertRedirect();

        $this->assertTrue(Score::where('segment', 'wit')->exists());
    }

    public function test_an_open_sheet_does_not_put_reset_scores_back(): void
    {
        $judge = User::where('username', 'prejudge1')->first();
        $candidate = Candidate::division('ms')->first();
        OpenSegment::create(['segment' => 'talent']);
        ScoreLock::where('segment', 'talent')->delete();

        // The sheet loads with every entry filled in, then the tabulator resets the segment.
        $sheet = Livewire::actingAs($judge)->test(ScoreSheet::class, ['segment' => 'talent']);
        ScoreReset::segment(Segment::find('talent'));

        // The browser sends the whole candidate, stale entries included: nothing is saved, and the sheet reloads empty.
        $sheet->set("points.c{$candidate->id}", ['skill' => '7', 'performance' => '5'])
            ->assertSee('The tabulator reset scores in Talent')
            ->assertSet("points.c{$candidate->id}.performance", '');
        $this->assertFalse(Score::where('segment', 'talent')->exists());

        // The next entry saves as usual.
        $sheet->set("points.c{$candidate->id}.skill", '7');
        $this->assertSame(1, Score::where('segment', 'talent')->whereNotNull('points')->count());
    }
}
