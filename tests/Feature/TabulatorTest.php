<?php

namespace Tests\Feature;

use App\Models\Candidate;
use App\Models\Score;
use App\Models\User;
use App\Scoring\Segment;
use App\Scoring\Tabulator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TabulatorTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_segment_average_is_the_mean_of_judge_totals_rounded_to_two_decimals(): void
    {
        [$a, $b, $c] = $this->candidates('ms', 3);
        $judges = User::onPanel('prepageant')->get();

        // Totals 15, 14.5, 13 → 42.5 / 3 = 14.1666… → 14.17
        $this->score('talent', $a, $judges[0], ['skill' => 7.5, 'performance' => 7.5]);
        $this->score('talent', $a, $judges[1], ['skill' => 7.25, 'performance' => 7.25]);
        $this->score('talent', $a, $judges[2], ['skill' => 6.5, 'performance' => 6.5]);
        // Totals 14, 14, 14.5 → 14.1666… → 14.17: a tie with $a
        $this->score('talent', $b, $judges[0], ['skill' => 7, 'performance' => 7]);
        $this->score('talent', $b, $judges[1], ['skill' => 7, 'performance' => 7]);
        $this->score('talent', $b, $judges[2], ['skill' => 7.5, 'performance' => 7]);
        // Only one criterion from judge 1: that total doesn't count, so the average is judge 2's alone.
        $this->score('talent', $c, $judges[0], ['skill' => 7.5]);
        $this->score('talent', $c, $judges[1], ['skill' => 5, 'performance' => 5]);

        $rows = collect((new Tabulator)->segment(Segment::find('talent'), 'ms')['rows'])->keyBy(fn ($r) => $r['candidate']->id);

        $this->assertSame(1417, $rows[$a->id]['average']);
        $this->assertSame([1500, 1450, 1300], array_values($rows[$a->id]['totals']));
        $this->assertSame(1417, $rows[$b->id]['average']);
        $this->assertSame(1, $rows[$a->id]['rank']);
        $this->assertSame(1, $rows[$b->id]['rank']);
        $this->assertTrue($rows[$a->id]['tied']);
        $this->assertNull($rows[$c->id]['totals'][$judges[0]->id]);
        $this->assertSame(1, $rows[$c->id]['counted']);
        $this->assertSame(1000, $rows[$c->id]['average']);
        $this->assertSame(3, $rows[$c->id]['rank']);
        // Judge 3 ranked $a below $b.
        $this->assertSame(2, $rows[$a->id]['judge_ranks'][$judges[2]->id]);
        $this->assertSame(1, $rows[$b->id]['judge_ranks'][$judges[2]->id]);
    }

    public function test_round_one_adds_segment_averages_and_flags_a_tie_at_the_top_five_cut(): void
    {
        $candidates = $this->candidates('mr', 7);
        $judges = User::onPanel('pageant')->get();

        // Q&A totals 20, 19, 18, 17, 16, 16; the seventh candidate is not scored.
        foreach ([[12, 8], [12, 7], [11, 7], [10, 7], [9, 7], [10, 6]] as $i => [$substance, $delivery]) {
            foreach ($judges as $judge) {
                $this->score('wit', $candidates[$i], $judge, ['substance' => $substance, 'delivery' => $delivery]);
            }
        }
        // Talent adds 14.17 to the leader (same sheets as above).
        $pre = User::onPanel('prepageant')->get();
        $this->score('talent', $candidates[0], $pre[0], ['skill' => 7.5, 'performance' => 7.5]);
        $this->score('talent', $candidates[0], $pre[1], ['skill' => 7.25, 'performance' => 7.25]);
        $this->score('talent', $candidates[0], $pre[2], ['skill' => 6.5, 'performance' => 6.5]);

        $result = (new Tabulator)->roundOne('mr');
        $rows = collect($result['rows'])->keyBy(fn ($r) => $r['candidate']->number);

        $this->assertSame(1417, $rows[1]['averages']['talent']);
        $this->assertSame(3417, $rows[1]['total']);
        $this->assertFalse($rows[1]['complete']);
        $this->assertSame([1, 2, 3, 4, 5, 5], $rows->take(6)->pluck('rank')->all());
        $this->assertNull($rows[7]['rank']);
        $this->assertTrue($result['cutoff_tie']);
        $this->assertCount(6, $result['suggested']);
    }

    public function test_round_two_places_by_the_lowest_sum_of_judge_ranks_not_the_average(): void
    {
        [$a, $b, $c] = $this->finalists(3);
        $judges = User::onPanel('pageant')->get();

        // Totals per judge for A, B, C. By average A leads (78.6 vs 77 vs 75),
        // but by ranks A and B both sum to 9 and C to 11.
        $sheets = [
            [90, 80, 70],   // ranks 1, 2, 3
            [70, 90, 80],   // 3, 1, 2
            [85, 85, 60],   // 1, 1, 3 (equal totals share a rank)
            [60, 70, 90],   // 3, 2, 1
            [88, 60, 75],   // 1, 3, 2
        ];
        foreach ($sheets as $j => $totals) {
            foreach ([$a, $b, $c] as $i => $candidate) {
                $this->score('final', $candidate, $judges[$j], ['impression' => $totals[$i] / 2, 'intelligence' => $totals[$i] / 2]);
            }
        }

        $result = (new Tabulator)->segment(Segment::find('final'), 'ms');
        $rows = collect($result['rows'])->keyBy(fn ($r) => $r['candidate']->id);

        $this->assertSame([1, 3, 1, 3, 1], array_values($rows[$a->id]['judge_ranks']));
        $this->assertSame([2, 1, 1, 2, 3], array_values($rows[$b->id]['judge_ranks']));
        $this->assertSame(9, $rows[$a->id]['rank_sum']);
        $this->assertSame(9, $rows[$b->id]['rank_sum']);
        $this->assertSame(11, $rows[$c->id]['rank_sum']);
        $this->assertSame([1, 1, 3], [$rows[$a->id]['rank'], $rows[$b->id]['rank'], $rows[$c->id]['rank']]);
        $this->assertTrue($rows[$a->id]['tied']);
        $this->assertFalse($rows[$c->id]['tied']);
        $this->assertSame('2nd runner-up', Tabulator::placement($rows[$c->id]['rank'], 'ms'));
        $this->assertCount(5, $result['rank_judges']);
    }

    public function test_rank_sums_leave_out_a_judge_who_has_not_scored_every_finalist(): void
    {
        [$a, $b, $c] = $this->finalists(3);
        $judges = User::onPanel('pageant')->get();

        // Same sheets as above, but judge 5 has not scored C yet.
        $sheets = [[90, 80, 70], [70, 90, 80], [85, 85, 60], [60, 70, 90], [88, 60, null]];
        foreach ($sheets as $j => $totals) {
            foreach ([$a, $b, $c] as $i => $candidate) {
                if ($totals[$i] !== null) {
                    $this->score('final', $candidate, $judges[$j], ['impression' => $totals[$i] / 2, 'intelligence' => $totals[$i] / 2]);
                }
            }
        }

        $result = (new Tabulator)->segment(Segment::find('final'), 'ms');
        $rows = collect($result['rows'])->keyBy(fn ($r) => $r['candidate']->id);

        $this->assertSame($judges->take(4)->pluck('id')->all(), $result['rank_judges']->all());
        // Over judges 1 to 4: A 8, B 6, C 9.
        $this->assertSame([8, 6, 9], [$rows[$a->id]['rank_sum'], $rows[$b->id]['rank_sum'], $rows[$c->id]['rank_sum']]);
        $this->assertSame([2, 1, 3], [$rows[$a->id]['rank'], $rows[$b->id]['rank'], $rows[$c->id]['rank']]);
    }

    public function test_round_two_scores_only_finalists_and_names_placements(): void
    {
        $candidates = $this->candidates('ms', 3);
        $candidates[1]->update(['is_finalist' => true]);

        $this->assertSame([$candidates[1]->id], Segment::find('final')->candidates('ms')->pluck('id')->all());
        $this->assertSame('Ms. LCUAA 2026', Tabulator::placement(1, 'ms'));
        $this->assertSame('4th runner-up', Tabulator::placement(5, 'mr'));
        $this->assertNull(Tabulator::placement(6, 'mr'));
    }

    private function finalists(int $count): array
    {
        $finalists = $this->candidates('ms', $count);
        foreach ($finalists as $candidate) {
            $candidate->update(['is_finalist' => true]);
        }

        return $finalists;
    }

    private function candidates(string $division, int $count): array
    {
        return Candidate::division($division)->take($count)->get()->all();
    }

    private function score(string $segment, Candidate $candidate, User $judge, array $points): void
    {
        foreach ($points as $criterion => $value) {
            Score::create(['segment' => $segment, 'candidate_id' => $candidate->id, 'judge_id' => $judge->id, 'criterion' => $criterion, 'points' => $value]);
        }
    }
}
