<?php

namespace Tests\Feature;

use App\Models\Candidate;
use App\Models\Score;
use App\Models\ScoreLock;
use App\Models\TieDecision;
use App\Models\User;
use App\Scoring\ScoreReset;
use App\Scoring\Segment;
use App\Scoring\Tabulator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class TieBreakTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Collection $ms;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->admin = User::where('username', 'admin1')->first();
        $this->ms = Candidate::division('ms')->get()->keyBy('number');
    }

    public function test_the_board_orders_a_round_two_tie_once_every_judge_has_locked_in(): void
    {
        // No. 2 and No. 4 both average 80 for 1st runner-up.
        $this->scoreFinal([1 => 90, 2 => 80, 3 => 70, 4 => 80, 5 => 60]);

        $this->actingAs($this->admin)->get('/results/ms/final')->assertOk()
            ->assertSee('Tied for 1st runner-up: No. 2 and No. 4.')
            ->assertSee("Record the board of judges' order under Settle the tie, below the table.")
            ->assertSee('Settle the tie')
            ->assertSee('The other takes 2nd runner-up.');

        $this->put('/results/ms/final/ties', ['ties' => [2 => [$this->ms[4]->id]]])
            ->assertRedirect('/results/ms/final')
            ->assertSessionHas('success', 'Saved. No. 2 and No. 4 tied at 80.00; the board of judges placed No. 4 higher.');

        $rows = $this->rows('final');
        $this->assertSame([1, 3, 4, 2, 5], array_map(fn ($n) => $rows[$n]['rank'], [1, 2, 3, 4, 5]));
        $this->assertFalse($rows[2]['tied'] || $rows[4]['tied']);
        $this->assertTrue($rows[2]['decided'] && $rows[4]['decided']);
        $this->assertFalse($rows[1]['decided']);
        $this->assertSame([5, 3, 2, 4, 1], Tabulator::winners((new Tabulator)->segment(Segment::find('final'), 'ms'))->pluck('candidate.number')->all());

        $this->get('/results/ms/final')->assertOk()
            ->assertSee('No. 2 and No. 4 tied at 80.00; the board of judges placed No. 4 higher.')
            ->assertSee('Saved: No. 4, then No. 2.')
            ->assertSee('<span class="decided">Board of judges</span>', false)
            ->assertDontSee("Record the board of judges' order");
        $this->get('/results/ms/final/winners')->assertOk();
        $this->get('/results/ms/final/print')->assertOk();
    }

    public function test_a_tie_cant_be_settled_while_judges_are_still_scoring(): void
    {
        $this->scoreFinal([1 => 90, 2 => 80, 3 => 70, 4 => 80, 5 => 60]);
        ScoreLock::where('segment', 'final')->first()->delete();

        $this->actingAs($this->admin)->get('/results/ms/final')->assertOk()
            ->assertSee('Tied for now:')
            ->assertSee("If this tie still stands once every judge has locked in, a form below the table records the board of judges' order.")
            ->assertDontSee('Settle the tie');

        ScoreLock::where('segment', 'swim_wear')->delete();
        $this->scoreSwimWear([1 => 12, 2 => 12, 3 => 11, 4 => 10, 5 => 9, 6 => 8, 7 => 7, 8 => 6, 9 => 5]);
        ScoreLock::where('segment', 'swim_wear')->first()->delete();
        $this->get('/results/ms/swim_wear')->assertOk()
            ->assertSee("a form below the table records the board of judges' pick for the winner.")
            ->assertDontSee('Save winner');

        $this->put('/results/ms/final/ties', ['ties' => [2 => [$this->ms[4]->id]]])
            ->assertSessionHas('error', 'A tie can be settled once every judge has locked in and scored every finalist.');
        $this->assertSame(0, TieDecision::count());
    }

    public function test_a_three_way_tie_needs_a_different_candidate_for_each_place(): void
    {
        $this->scoreFinal([1 => 90, 2 => 80, 3 => 80, 4 => 80, 5 => 60]);
        $id = fn (int $number) => $this->ms[$number]->id;

        $this->actingAs($this->admin)->put('/results/ms/final/ties', ['ties' => [2 => [$id(3), $id(3)]]])
            ->assertSessionHas('error', 'In the tie for 1st runner-up, choose a different candidate for each place.');
        $this->put('/results/ms/final/ties', ['ties' => [2 => [$id(3)]]])->assertSessionHas('error');
        $this->assertSame(0, TieDecision::count());

        $this->put('/results/ms/final/ties', ['ties' => [2 => [$id(4), $id(2)]]])
            ->assertSessionHas('success', 'Saved. No. 2, No. 3 and No. 4 tied at 80.00; the board of judges placed them in this order: No. 4, No. 2, No. 3.');

        $rows = $this->rows('final');
        $this->assertSame([2 => 3, 3 => 4, 4 => 2], [2 => $rows[2]['rank'], 3 => $rows[3]['rank'], 4 => $rows[4]['rank']]);
        $this->assertSame('3rd runner-up', Tabulator::placement($rows[3]['rank'], 'ms'));
    }

    public function test_a_decision_is_dropped_when_a_score_changes_the_tie(): void
    {
        $this->scoreFinal([1 => 90, 2 => 80, 3 => 70, 4 => 80, 5 => 60]);
        $this->actingAs($this->admin)->put('/results/ms/final/ties', ['ties' => [2 => [$this->ms[4]->id]]]);
        $this->assertSame(1, TieDecision::count());

        // Both still tie, but on a new average: the board's order was for the old one.
        Score::where('segment', 'final')->whereIn('candidate_id', [$this->ms[2]->id, $this->ms[4]->id])->where('criterion', 'impression')->decrement('points');

        $rows = $this->rows('final');
        $this->assertSame(0, TieDecision::count());
        $this->assertSame([2, 2], [$rows[2]['rank'], $rows[4]['rank']]);
        $this->assertTrue($rows[2]['tied']);
    }

    public function test_a_decision_can_be_cleared(): void
    {
        $this->scoreFinal([1 => 90, 2 => 80, 3 => 70, 4 => 80, 5 => 60]);
        $this->actingAs($this->admin)->put('/results/ms/final/ties', ['ties' => [2 => [$this->ms[4]->id]]]);

        $this->put('/results/ms/final/ties', ['clear' => 2])
            ->assertSessionHas('success', 'Cleared. No. 2 and No. 4 are tied for 1st runner-up again.');
        $this->assertSame(0, TieDecision::count());
        $this->assertTrue($this->rows('final')[2]['tied']);
    }

    public function test_a_round_one_segment_settles_its_winner_only(): void
    {
        // Swim wear: No. 1, 2 and 3 tie for first at 12; No. 4 and 5 tie for 4th at 10.
        $this->scoreSwimWear([1 => 12, 2 => 12, 3 => 12, 4 => 10, 5 => 10, 6 => 9, 7 => 8, 8 => 7, 9 => 6]);

        $this->actingAs($this->admin)->get('/results/ms/swim_wear')->assertOk()
            ->assertSee('Tied for first place: No. 1, No. 2 and No. 3.', false)
            ->assertSee('The others stay tied for 2nd.');
        $this->assertSame([1], array_column((new Tabulator)->segment(Segment::find('swim_wear'), 'ms')['ties'], 'rank'));

        $this->put('/results/ms/swim_wear/ties', ['ties' => [1 => [$this->ms[3]->id]]])
            ->assertSessionHas('success', 'Saved. No. 1, No. 2 and No. 3 tied for first at 12.00; the board of judges named No. 3 the winner.');

        $rows = $this->rows('swim_wear');
        $this->assertSame([1, 2, 2], [$rows[3]['rank'], $rows[1]['rank'], $rows[2]['rank']]);
        $this->assertTrue($rows[1]['tied'] && $rows[2]['tied']);
        $this->assertTrue($rows[3]['decided']);
        $this->assertSame([3], Tabulator::winners((new Tabulator)->segment(Segment::find('swim_wear'), 'ms'))->pluck('candidate.number')->all());
        $this->get('/results/ms/swim_wear/winners')->assertOk();

        // A tie below first isn't the board's to settle here.
        $this->put('/results/ms/swim_wear/ties', ['ties' => [4 => [$this->ms[4]->id]]])
            ->assertSessionHas('error', 'The ties changed while this page was open. Check the results and choose again.');
    }

    public function test_casual_wear_has_no_tie_section(): void
    {
        $this->assertSame([], (new Tabulator)->segment(Segment::find('casual_wear'), 'ms')['ties']);
    }

    public function test_a_reset_deletes_the_segments_decisions(): void
    {
        $this->scoreFinal([1 => 90, 2 => 80, 3 => 70, 4 => 80, 5 => 60]);
        $this->actingAs($this->admin)->put('/results/ms/final/ties', ['ties' => [2 => [$this->ms[4]->id]]]);

        ScoreReset::segment(Segment::find('swim_wear'));
        $this->assertSame(1, TieDecision::count());
        ScoreReset::segment(Segment::find('final'));
        $this->assertSame(0, TieDecision::count());
    }

    /** Ms. No. 1 to 5 are finalists; every pageant night judge gives each that total in Round 2, and locks in. */
    private function scoreFinal(array $totals): void
    {
        $judges = User::onPanel('pageant')->get();
        foreach ($totals as $number => $total) {
            $this->ms[$number]->update(['is_finalist' => true]);
            foreach ($judges as $judge) {
                foreach (['impression' => $total / 2, 'intelligence' => $total / 2] as $criterion => $points) {
                    Score::create(['segment' => 'final', 'candidate_id' => $this->ms[$number]->id, 'judge_id' => $judge->id, 'criterion' => $criterion, 'points' => $points]);
                }
            }
        }
        foreach ($judges as $judge) {
            ScoreLock::create(['segment' => 'final', 'judge_id' => $judge->id]);
        }
    }

    /** Every pageant night judge gives each Ms. candidate that Swim wear total, spread over the criteria, and locks in. */
    private function scoreSwimWear(array $totals): void
    {
        $segment = Segment::find('swim_wear');
        $judges = User::onPanel($segment->panel)->get();
        foreach ($totals as $number => $total) {
            foreach ($judges as $judge) {
                $left = $total;
                foreach ($segment->criteria as $criterion => $c) {
                    $points = min($c['max'], $left);
                    $left -= $points;
                    Score::create(['segment' => 'swim_wear', 'candidate_id' => $this->ms[$number]->id, 'judge_id' => $judge->id, 'criterion' => $criterion, 'points' => $points]);
                }
            }
        }
        foreach ($judges as $judge) {
            ScoreLock::create(['segment' => 'swim_wear', 'judge_id' => $judge->id]);
        }
    }

    /** A segment's Ms. rows, keyed by candidate number. */
    private function rows(string $segment): Collection
    {
        return collect((new Tabulator)->segment(Segment::find($segment), 'ms')['rows'])->keyBy(fn ($row) => $row['candidate']->number);
    }
}
