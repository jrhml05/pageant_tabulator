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

    public function test_segment_average_is_the_mean_of_judge_totals_but_the_rank_sum_places(): void
    {
        [$a, $b, $c] = $this->only('ms', 3);
        $judges = User::onPanel('prepageant')->get();

        // Totals 15, 14.5, 13 → 42.5 / 3 = 14.1666… → 14.17
        $this->score('talent', $a, $judges[0], ['skill' => 7.5, 'performance' => 7.5]);
        $this->score('talent', $a, $judges[1], ['skill' => 7.25, 'performance' => 7.25]);
        $this->score('talent', $a, $judges[2], ['skill' => 6.5, 'performance' => 6.5]);
        // Totals 14, 14, 14.5 → 14.1666… → 14.17: the same average as $a
        $this->score('talent', $b, $judges[0], ['skill' => 7, 'performance' => 7]);
        $this->score('talent', $b, $judges[1], ['skill' => 7, 'performance' => 7]);
        $this->score('talent', $b, $judges[2], ['skill' => 7.5, 'performance' => 7]);
        // Only one criterion from judge 1: that total doesn't count, so the average is judge 2's alone.
        $this->score('talent', $c, $judges[0], ['skill' => 7.5]);
        $this->score('talent', $c, $judges[1], ['skill' => 5, 'performance' => 5]);

        $result = (new Tabulator)->segment(Segment::find('talent'), 'ms');
        $rows = collect($result['rows'])->keyBy(fn ($r) => $r['candidate']->id);

        $this->assertSame(1417, $rows[$a->id]['average']);
        $this->assertSame([1500, 1450, 1300], array_values($rows[$a->id]['totals']));
        $this->assertSame(1417, $rows[$b->id]['average']);
        $this->assertNull($rows[$c->id]['totals'][$judges[0]->id]);
        $this->assertSame(1, $rows[$c->id]['counted']);
        $this->assertSame(1000, $rows[$c->id]['average']);
        // Only judge 2 has scored every candidate, so the rank sums are judge 2's ranks alone and
        // break the tie in averages: judge 2 gave $a 14.5 and $b 14.
        $this->assertSame([$judges[1]->id], $result['rank_judges']->all());
        $this->assertSame([1, 2, 3], [$rows[$a->id]['rank_sum'], $rows[$b->id]['rank_sum'], $rows[$c->id]['rank_sum']]);
        // Averaged over the 1 judge who counts.
        $this->assertSame([100, 200, 300], [$rows[$a->id]['average_rank'], $rows[$b->id]['average_rank'], $rows[$c->id]['average_rank']]);
        $this->assertSame([1, 2, 3], [$rows[$a->id]['rank'], $rows[$b->id]['rank'], $rows[$c->id]['rank']]);
        $this->assertFalse($rows[$a->id]['tied']);
        // Judge 3 ranked $a below $b.
        $this->assertSame(2, $rows[$a->id]['judge_ranks'][$judges[2]->id]);
        $this->assertSame(1, $rows[$b->id]['judge_ranks'][$judges[2]->id]);
    }

    public function test_ranks_are_competition_ranks(): void
    {
        $ranks = Tabulator::rank([1 => 90, 2 => 85, 3 => 85, 4 => 80, 5 => 75, 6 => 70, 7 => 70, 8 => 65]);
        $this->assertSame([1, 2, 2, 4, 5, 6, 6, 8], array_values(array_column($ranks, 'rank')));
        $this->assertSame([false, true, true, false, false, true, true, false], array_values(array_column($ranks, 'tied')));

        $lowest = Tabulator::rank([1 => 6, 2 => 9, 3 => 9, 4 => 6, 5 => 12], lowestFirst: true);
        $this->assertSame([1, 3, 3, 1, 5], array_values(array_column($lowest, 'rank')));
    }

    public function test_round_one_matches_the_boards_worked_example(): void
    {
        $candidates = $this->only('ms', 5);

        // Each judge's combined pre-pageant and pageant night score, per candidate (C1 to C5),
        // spread over the panel's segments.
        $this->scoreCombined('prepageant', $candidates, [
            [29, 28.4, 29.2], [28, 29.2, 28], [28.4, 28, 29], [29.2, 29, 28.4], [28, 28, 28],
        ]);
        $this->scoreCombined('pageant', $candidates, [
            [56.5, 67, 58, 51, 54], [65, 51, 52, 61, 68], [63, 69, 61, 59, 67], [58, 65, 58, 65, 55], [55, 50, 51, 62, 67],
        ]);

        $result = (new Tabulator)->roundOne('ms');
        $rows = collect($result['rows'])->keyBy(fn ($r) => $r['candidate']->id);
        $field = fn (string $panel, string $field) => array_map(fn ($c) => $rows[$c->id]['panels'][$panel][$field], $candidates);

        // Each judge's talent + thematic wear total, and that judge's rank of it.
        $this->assertSame([2900, 2840, 2920], array_values($rows[$candidates[0]->id]['panels']['prepageant']['totals']));
        $this->assertSame([2, 3, 1], array_values($rows[$candidates[0]->id]['panels']['prepageant']['judge_ranks']));
        $this->assertSame([5650, 6700, 5800, 5100, 5400], array_values($rows[$candidates[0]->id]['panels']['pageant']['totals']));
        $this->assertSame([4, 2, 2, 5, 5], array_values($rows[$candidates[0]->id]['panels']['pageant']['judge_ranks']));

        // Rank sums over 3 judges and over 5 (ties ranked 1, 2, 2, 4), then averaged.
        $this->assertSame([6, 9, 9, 6, 12], $field('prepageant', 'rank_sum'));
        $this->assertSame([200, 300, 300, 200, 400], $field('prepageant', 'average_rank'));
        $this->assertSame([60, 90, 90, 60, 120], $field('prepageant', 'weighted'));
        $this->assertSame([18, 13, 10, 13, 19], $field('pageant', 'rank_sum'));
        $this->assertSame([360, 260, 200, 260, 380], $field('pageant', 'average_rank'));
        $this->assertSame([252, 182, 140, 182, 266], $field('pageant', 'weighted'));

        // C1: 2.00 × 30% + 3.60 × 70% = 3.12.
        $this->assertSame([312, 272, 230, 242, 386], array_map(fn ($c) => $rows[$c->id]['total'], $candidates));
        $this->assertSame([4, 3, 1, 2, 5], array_map(fn ($c) => $rows[$c->id]['rank'], $candidates));
        $this->assertFalse(collect($rows)->contains('tied', true));
        $this->assertTrue(collect($rows)->every('complete'));
        $this->assertSame(30, (int) $result['panels']['prepageant']['weight']);
        $this->assertSame(70, (int) $result['panels']['pageant']['weight']);
    }

    public function test_round_one_averages_ranks_over_each_panels_judges_instead_of_adding_them(): void
    {
        [$a, $b, $c] = $this->only('ms', 3);

        // A: first with all 3 pre-pageant judges, ranks 2, 2, 2, 2, 1 on pageant night.
        // B: third with all 3 pre-pageant judges, first with all 5 on pageant night.
        $this->scoreCombined('prepageant', [$a, $b, $c], [[30, 30, 30], [28, 28, 28], [29, 29, 29]]);
        $this->scoreCombined('pageant', [$a, $b, $c], [[60, 60, 60, 60, 70], [70, 70, 70, 70, 70], [50, 50, 50, 50, 50]]);

        $rows = collect((new Tabulator)->roundOne('ms')['rows'])->keyBy(fn ($r) => $r['candidate']->id);

        // Divided by 3 and by 5: A 3 / 3 = 1.00 and 9 / 5 = 1.80.
        $this->assertSame([3, 100], [$rows[$a->id]['panels']['prepageant']['rank_sum'], $rows[$a->id]['panels']['prepageant']['average_rank']]);
        $this->assertSame([9, 180], [$rows[$a->id]['panels']['pageant']['rank_sum'], $rows[$a->id]['panels']['pageant']['average_rank']]);
        // A: 1.00 × 30% + 1.80 × 70% = 1.56. B: 3.00 × 30% + 1.00 × 70% = 1.60.
        // Adding the ranks instead (A 3 × 30 + 9 × 70, B 9 × 30 + 5 × 70) would put B first.
        $this->assertSame([156, 160, 270], [$rows[$a->id]['total'], $rows[$b->id]['total'], $rows[$c->id]['total']]);
        $this->assertSame([1, 2, 3], [$rows[$a->id]['rank'], $rows[$b->id]['rank'], $rows[$c->id]['rank']]);
    }

    public function test_round_one_ranks_each_judges_panel_total_while_segments_keep_their_own_winner(): void
    {
        [$a, $b] = $this->only('ms', 2);
        $judges = User::onPanel('prepageant')->get();

        // A wins talent and B wins thematic wear with every judge, but B's talent + thematic
        // wear total is higher with every judge (26 to 25).
        foreach ($judges as $judge) {
            $this->score('talent', $a, $judge, ['skill' => 7.5, 'performance' => 7.5]);
            $this->score('thematic_wear', $a, $judge, ['stage_presence' => 7, 'relevance' => 3]);
            $this->score('talent', $b, $judge, ['skill' => 6, 'performance' => 6]);
            $this->score('thematic_wear', $b, $judge, ['stage_presence' => 10, 'relevance' => 4]);
        }
        // Level on pageant night.
        $this->scorePanel('pageant', $a, 0.8);
        $this->scorePanel('pageant', $b, 0.8);

        $rows = collect((new Tabulator)->roundOne('ms')['rows'])->keyBy(fn ($r) => $r['candidate']->id);

        $this->assertSame([2500, 2500, 2500], array_values($rows[$a->id]['panels']['prepageant']['totals']));
        $this->assertSame([2, 2, 2], array_values($rows[$a->id]['panels']['prepageant']['judge_ranks']));
        // Pre-pageant: A 2.00 × 30% = 0.60, B 1.00 × 30% = 0.30. Pageant night: both 0.70.
        $this->assertSame([130, 100], [$rows[$a->id]['total'], $rows[$b->id]['total']]);
        $this->assertSame([2, 1], [$rows[$a->id]['rank'], $rows[$b->id]['rank']]);

        // Talent's own winner is still A.
        $talent = collect((new Tabulator)->segment(Segment::find('talent'), 'ms')['rows'])->keyBy(fn ($r) => $r['candidate']->id);
        $this->assertSame([1, 2], [$talent[$a->id]['rank'], $talent[$b->id]['rank']]);
    }

    public function test_a_round_one_tie_goes_to_the_better_beauty_of_face_average_rank(): void
    {
        [$a, $b] = $this->only('ms', 2);

        $this->scorePanel('prepageant', $a, 0.8);
        $this->scorePanel('prepageant', $b, 0.8);
        foreach (['swim_wear', 'formal_wear'] as $segment) {
            $this->scoreSegment($segment, [$a, $b], [[10, 10, 10, 10, 10], [10, 10, 10, 10, 10]]);
        }
        // A wins beauty of face, B wins Q&A: both 20%, so the totals are level.
        $this->scoreSegment('beauty_of_face', [$a, $b], [[18, 18, 18, 18, 18], [15, 15, 15, 15, 15]]);
        $this->scoreSegment('wit', [$a, $b], [[15, 15, 15, 15, 15], [18, 18, 18, 18, 18]]);

        $rows = collect((new Tabulator)->roundOne('ms')['rows'])->keyBy(fn ($r) => $r['candidate']->id);

        $this->assertSame($rows[$a->id]['total'], $rows[$b->id]['total']);
        $this->assertSame([1, 2], [$rows[$a->id]['rank'], $rows[$b->id]['rank']]);
        $this->assertFalse($rows[$a->id]['tied']);
        $this->assertSame(['beauty_of_face'], $rows[$a->id]['tie_break']);
        $this->assertSame(['beauty_of_face'], $rows[$b->id]['tie_break']);
    }

    public function test_a_round_one_tie_level_on_beauty_of_face_goes_to_q_and_a(): void
    {
        [$a, $b] = $this->only('ms', 2);

        $this->scorePanel('prepageant', $a, 0.8);
        $this->scorePanel('prepageant', $b, 0.8);
        // Pageant night totals are level with every judge (51), and so is beauty of face.
        $this->scoreSegment('swim_wear', [$a, $b], [[9, 9, 9, 9, 9], [12, 12, 12, 12, 12]]);
        $this->scoreSegment('formal_wear', [$a, $b], [[12, 12, 12, 12, 12], [12, 12, 12, 12, 12]]);
        $this->scoreSegment('beauty_of_face', [$a, $b], [[12, 12, 12, 12, 12], [12, 12, 12, 12, 12]]);
        // Q&A: A first.
        $this->scoreSegment('wit', [$a, $b], [[18, 18, 18, 18, 18], [15, 15, 15, 15, 15]]);

        $rows = collect((new Tabulator)->roundOne('ms')['rows'])->keyBy(fn ($r) => $r['candidate']->id);

        $this->assertSame([100, 100], [$rows[$a->id]['total'], $rows[$b->id]['total']]);
        $this->assertSame([1, 2], [$rows[$a->id]['rank'], $rows[$b->id]['rank']]);
        $this->assertSame(['beauty_of_face', 'wit'], $rows[$a->id]['tie_break']);
    }

    public function test_a_round_one_tie_left_after_the_tie_break_is_flagged(): void
    {
        [$a, $b] = $this->only('ms', 2);

        foreach ([$a, $b] as $candidate) {
            $this->scorePanel('prepageant', $candidate, 0.8);
            $this->scorePanel('pageant', $candidate, 0.8);
        }

        $result = (new Tabulator)->roundOne('ms');
        $rows = collect($result['rows'])->keyBy(fn ($r) => $r['candidate']->id);

        $this->assertSame([1, 1], [$rows[$a->id]['rank'], $rows[$b->id]['rank']]);
        $this->assertTrue($rows[$a->id]['tied']);
        $this->assertSame([], $rows[$a->id]['tie_break']);
        $this->assertSame(['beauty_of_face', 'wit'], $result['tie_break']->all());
    }

    public function test_round_one_flags_a_tie_left_at_the_top_five_cut(): void
    {
        [$a, $b, $c, $d, $e, $f] = $this->only('mr', 6);

        // Every judge on a panel scores alike, so each judge ranks the same way.
        // Pre-pageant: D, C, B, A, then E and F tied. Pageant night: A, B, C, D, then E and F tied.
        foreach ([[$a, 0.6, 0.9], [$b, 0.7, 0.8], [$c, 0.8, 0.7], [$d, 0.9, 0.6], [$e, 0.5, 0.5], [$f, 0.5, 0.5]] as [$candidate, $pre, $night]) {
            $this->scorePanel('prepageant', $candidate, $pre);
            $this->scorePanel('pageant', $candidate, $night);
        }

        $result = (new Tabulator)->roundOne('mr');
        $rows = collect($result['rows'])->keyBy(fn ($r) => $r['candidate']->id);

        // A: average rank 4 × 30% = 1.20, plus 1 × 70% = 0.70: 1.90.
        $this->assertSame(120, $rows[$a->id]['panels']['prepageant']['weighted']);
        $this->assertSame([190, 230, 270, 310, 500, 500], array_map(fn ($c) => $rows[$c->id]['total'], [$a, $b, $c, $d, $e, $f]));
        $this->assertSame([1, 2, 3, 4, 5, 5], array_map(fn ($c) => $rows[$c->id]['rank'], [$a, $b, $c, $d, $e, $f]));
        $this->assertTrue($rows[$a->id]['complete']);
        $this->assertTrue($rows[$e->id]['tied']);
        $this->assertTrue($result['cutoff_tie']);
        $this->assertCount(6, $result['suggested']);
    }

    public function test_round_one_before_pageant_night_counts_only_pre_pageant(): void
    {
        [$a, $b] = $this->only('ms', 2);
        $this->scorePanel('prepageant', $a, 0.9);
        $this->scorePanel('prepageant', $b, 0.8);

        $rows = collect((new Tabulator)->roundOne('ms')['rows'])->keyBy(fn ($r) => $r['candidate']->id);

        $this->assertSame([30, 60], [$rows[$a->id]['total'], $rows[$b->id]['total']]);
        $this->assertSame([1, 2], [$rows[$a->id]['rank'], $rows[$b->id]['rank']]);
        $this->assertNull($rows[$a->id]['panels']['pageant']['weighted']);
        $this->assertFalse($rows[$a->id]['complete']);
    }

    public function test_a_judge_missing_one_segment_is_left_out_of_the_panel_average(): void
    {
        [$a, $b] = $this->only('ms', 2);
        $judges = User::onPanel('prepageant')->get();

        $this->scorePanel('prepageant', $a, 0.9);
        $this->scorePanel('prepageant', $b, 0.8);
        // Judge 3 has no thematic wear score for B yet.
        Score::where('segment', 'thematic_wear')->where('candidate_id', $b->id)->where('judge_id', $judges[2]->id)->delete();

        $result = (new Tabulator)->roundOne('ms');
        $rows = collect($result['rows'])->keyBy(fn ($r) => $r['candidate']->id);
        $pre = fn (Candidate $c) => $rows[$c->id]['panels']['prepageant'];

        $this->assertSame($judges->take(2)->pluck('id')->all(), $result['panels']['prepageant']['rank_judges']->all());
        $this->assertNull($pre($b)['totals'][$judges[2]->id]);
        // Averaged over the 2 judges who count: B 4 / 2 = 2.00 → 0.60.
        $this->assertSame([2, 4], [$pre($a)['rank_sum'], $pre($b)['rank_sum']]);
        $this->assertSame([100, 200], [$pre($a)['average_rank'], $pre($b)['average_rank']]);
        $this->assertSame([30, 60], [$pre($a)['weighted'], $pre($b)['weighted']]);
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

    /** The first $count candidates of a division, with the rest removed so every candidate is scored. */
    private function only(string $division, int $count): array
    {
        $keep = $this->candidates($division, $count);
        Candidate::division($division)->whereNotIn('id', array_map(fn ($c) => $c->id, $keep))->delete();

        return $keep;
    }

    /** Every judge on the panel gives the candidate $share of the points on each Round 1 criterion. */
    private function scorePanel(string $panel, Candidate $candidate, float $share): void
    {
        foreach (Segment::inRound(1)->where('panel', $panel) as $segment) {
            foreach (User::onPanel($panel)->get() as $judge) {
                $this->score($segment->key, $candidate, $judge, array_map(fn ($c) => round($c['max'] * $share, 2), $segment->criteria));
            }
        }
    }

    /** Each judge's total for a segment, per candidate (one list of judge totals each), spread over its criteria in order. */
    private function scoreSegment(string $segment, array $candidates, array $sheets): void
    {
        $judges = User::onPanel(Segment::find($segment)->panel)->get();
        foreach ($candidates as $i => $candidate) {
            foreach ($judges as $j => $judge) {
                $left = $sheets[$i][$j];
                $points = [];
                foreach (Segment::find($segment)->criteria as $criterion => $c) {
                    $points[$criterion] = min($c['max'], $left);
                    $left = round($left - $points[$criterion], 2);
                }
                $this->score($segment, $candidate, $judge, $points);
            }
        }
    }

    /** Each judge's combined score for a panel, split over the panel's Round 1 segments by weight. */
    private function scoreCombined(string $panel, array $candidates, array $sheets): void
    {
        $segments = Segment::inRound(1)->where('panel', $panel);
        $weight = $segments->sum(fn (Segment $s) => $s->maxPoints());
        foreach ($segments as $segment) {
            $share = $segment->maxPoints() / $weight;
            $this->scoreSegment($segment->key, $candidates, array_map(
                fn ($totals) => array_map(fn ($total) => round($total * $share, 2), $totals), $sheets,
            ));
        }
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
