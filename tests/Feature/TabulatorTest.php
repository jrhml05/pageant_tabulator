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

    public function test_a_segment_places_by_the_average_of_the_judges_totals(): void
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
        // Equal averages share first place; judges' own orderings don't count.
        $this->assertSame([1, 1, 3], [$rows[$a->id]['rank'], $rows[$b->id]['rank'], $rows[$c->id]['rank']]);
        $this->assertSame([true, true, false], [$rows[$a->id]['tied'], $rows[$b->id]['tied'], $rows[$c->id]['tied']]);
        $this->assertArrayNotHasKey('judge_ranks', $rows[$a->id]);
        $this->assertArrayNotHasKey('rank_judges', $result);
    }

    public function test_ranks_are_competition_ranks(): void
    {
        $ranks = Tabulator::rank([1 => 90, 2 => 85, 3 => 85, 4 => 80, 5 => 75, 6 => 70, 7 => 70, 8 => 65]);
        $this->assertSame([1, 2, 2, 4, 5, 6, 6, 8], array_values(array_column($ranks, 'rank')));
        $this->assertSame([false, true, true, false, false, true, true, false], array_values(array_column($ranks, 'tied')));
    }

    public function test_round_one_adds_each_segments_average_at_its_weight(): void
    {
        [$a, $b, $c] = $this->only('ms', 3);

        // Talent is out of 100 at 15%; the rest are scored out of their weight.
        $this->scoreSegment('talent', [$a, $b, $c], [[90, 85, 80], [95, 95, 95], [70, 71, 73]]);
        $this->scoreSegment('thematic_wear', [$a, $b, $c], [[12, 12, 12], [10, 10, 10], [14, 14, 14]]);
        foreach (['swim_wear' => [12, 11, 13], 'formal_wear' => [12, 12, 12], 'beauty_of_face' => [16, 18, 15], 'wit' => [17, 16, 15]] as $segment => $points) {
            $this->scoreSegment($segment, [$a, $b, $c], array_map(fn ($p) => array_fill(0, 5, $p), $points));
        }

        $result = (new Tabulator)->roundOne('ms');
        $rows = collect($result['rows'])->keyBy(fn ($r) => $r['candidate']->id);
        $talent = fn ($c) => $rows[$c->id]['segments']['talent'];

        // A: talent averages 85.00, which counts 12.75 of its 15.
        $this->assertSame([8500, 1275], [$talent($a)['average'], $talent($a)['weighted']]);
        $this->assertSame([1200, 1200], [$rows[$a->id]['segments']['thematic_wear']['average'], $rows[$a->id]['segments']['thematic_wear']['weighted']]);
        // C: talent averages 71.33 (71.333...), which counts 10.6995, shown as 10.70.
        $this->assertSame([7133, 1070], [$talent($c)['average'], $talent($c)['weighted']]);

        // A: 12.75 + 12 + 12 + 12 + 16 + 17 = 81.75. C: 10.6995 + 14 + 13 + 12 + 15 + 15 = 79.6995.
        $this->assertSame([8175, 8125, 7970], array_map(fn ($c) => $rows[$c->id]['total'], [$a, $b, $c]));
        $this->assertSame([1, 2, 3], array_map(fn ($c) => $rows[$c->id]['rank'], [$a, $b, $c]));
        $this->assertFalse(collect($rows)->contains('tied', true));
        $this->assertTrue(collect($rows)->every('complete'));
        $this->assertSame(['talent' => 3, 'thematic_wear' => 3, 'swim_wear' => 5, 'formal_wear' => 5, 'beauty_of_face' => 5, 'wit' => 5], $result['judges']->all());
    }

    public function test_round_one_places_by_weighted_score_while_segments_keep_their_own_winner(): void
    {
        [$a, $b] = $this->only('ms', 2);

        // A wins talent and B wins thematic wear with every judge, but B's weighted total is
        // higher: talent's 100 and 80 count 15 and 12, so A has 15 + 10 and B 12 + 14.
        $this->scoreSegment('talent', [$a, $b], [[100, 100, 100], [80, 80, 80]]);
        $this->scoreSegment('thematic_wear', [$a, $b], [[10, 10, 10], [14, 14, 14]]);
        // Level on pageant night.
        $this->scorePanel('pageant', $a, 0.8);
        $this->scorePanel('pageant', $b, 0.8);

        $rows = collect((new Tabulator)->roundOne('ms')['rows'])->keyBy(fn ($r) => $r['candidate']->id);

        $this->assertSame([8100, 8200], [$rows[$a->id]['total'], $rows[$b->id]['total']]);
        $this->assertSame([2, 1], [$rows[$a->id]['rank'], $rows[$b->id]['rank']]);

        // Talent's own winner is still A.
        $talent = collect((new Tabulator)->segment(Segment::find('talent'), 'ms')['rows'])->keyBy(fn ($r) => $r['candidate']->id);
        $this->assertSame([1, 2], [$talent[$a->id]['rank'], $talent[$b->id]['rank']]);
    }

    public function test_round_one_compares_totals_before_rounding(): void
    {
        [$a, $b] = $this->only('ms', 2);

        // Talent 87.33 counts 13.0995 and 87.34 counts 13.101: both show as 13.10, but B is ahead.
        $this->scoreSegment('talent', [$a, $b], [[87.33, 87.33, 87.33], [87.34, 87.34, 87.34]]);
        $this->scoreSegment('thematic_wear', [$a, $b], [[12, 12, 12], [12, 12, 12]]);
        $this->scorePanel('pageant', $a, 0.8);
        $this->scorePanel('pageant', $b, 0.8);

        $rows = collect((new Tabulator)->roundOne('ms')['rows'])->keyBy(fn ($r) => $r['candidate']->id);

        $this->assertSame([1310, 1310], [$rows[$a->id]['segments']['talent']['weighted'], $rows[$b->id]['segments']['talent']['weighted']]);
        $this->assertSame([8110, 8110], [$rows[$a->id]['total'], $rows[$b->id]['total']]);
        $this->assertSame([2, 1], [$rows[$a->id]['rank'], $rows[$b->id]['rank']]);
        $this->assertFalse($rows[$a->id]['tied']);
    }

    public function test_a_round_one_tie_goes_to_the_higher_beauty_of_face_average(): void
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
        // Pageant night totals are level (51), and so is beauty of face.
        $this->scoreSegment('swim_wear', [$a, $b], [[9, 9, 9, 9, 9], [12, 12, 12, 12, 12]]);
        $this->scoreSegment('formal_wear', [$a, $b], [[12, 12, 12, 12, 12], [12, 12, 12, 12, 12]]);
        $this->scoreSegment('beauty_of_face', [$a, $b], [[12, 12, 12, 12, 12], [12, 12, 12, 12, 12]]);
        // Q&A: A first.
        $this->scoreSegment('wit', [$a, $b], [[18, 18, 18, 18, 18], [15, 15, 15, 15, 15]]);

        $rows = collect((new Tabulator)->roundOne('ms')['rows'])->keyBy(fn ($r) => $r['candidate']->id);

        // 24 pre-pageant and 51 on pageant night each.
        $this->assertSame([7500, 7500], [$rows[$a->id]['total'], $rows[$b->id]['total']]);
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

        // A 60% pre-pageant and 90% pageant night: 18 + 63 = 81. E and F: 15 + 35 = 50.
        foreach ([[$a, 0.6, 0.9], [$b, 0.7, 0.8], [$c, 0.8, 0.7], [$d, 0.9, 0.6], [$e, 0.5, 0.5], [$f, 0.5, 0.5]] as [$candidate, $pre, $night]) {
            $this->scorePanel('prepageant', $candidate, $pre);
            $this->scorePanel('pageant', $candidate, $night);
        }

        $result = (new Tabulator)->roundOne('mr');
        $rows = collect($result['rows'])->keyBy(fn ($r) => $r['candidate']->id);

        $this->assertSame([8100, 7700, 7300, 6900, 5000, 5000], array_map(fn ($c) => $rows[$c->id]['total'], [$a, $b, $c, $d, $e, $f]));
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

        $this->assertSame([2700, 2400], [$rows[$a->id]['total'], $rows[$b->id]['total']]);
        $this->assertSame([1, 2], [$rows[$a->id]['rank'], $rows[$b->id]['rank']]);
        $this->assertNull($rows[$a->id]['segments']['swim_wear']['weighted']);
        $this->assertFalse($rows[$a->id]['complete']);
    }

    public function test_casual_wear_places_on_its_own_and_leaves_both_rounds_alone(): void
    {
        [$a, $b] = $this->only('ms', 2);
        $this->scorePanel('prepageant', $a, 0.9);
        $this->scorePanel('prepageant', $b, 0.8);
        $finalists = [$a->id, $b->id];
        Candidate::whereIn('id', $finalists)->update(['is_finalist' => true]);
        $this->scoreSegment('final', [$a, $b], [[90, 90, 90, 90, 90], [80, 80, 80, 80, 80]]);
        $roundOne = (new Tabulator)->roundOne('ms');
        $roundTwo = (new Tabulator)->segment(Segment::find('final'), 'ms');

        // $b is ranked first on every judge's sheet.
        $this->rankSegment([$a, $b], [[2, 2, 2, 2, 2], [1, 1, 1, 1, 1]]);
        $rows = $this->casualRows();

        $this->assertSame([1000, 500], [$rows[$a->id]['rank_total'], $rows[$b->id]['rank_total']]);
        $this->assertSame([2, 1], [$rows[$a->id]['rank'], $rows[$b->id]['rank']]);
        $this->assertEquals($roundOne, (new Tabulator)->roundOne('ms'));
        $this->assertEquals($roundTwo['rows'], (new Tabulator)->segment(Segment::find('final'), 'ms')['rows']);
    }

    public function test_casual_wear_places_by_the_lowest_rank_total_and_flags_ties(): void
    {
        [$a, $b, $c] = $this->only('ms', 3);
        // Totals: $a 1+1+3+2+3 = 10, $b 2+3+1+1+2 = 9, $c 3+2+2+3+1 = 11.
        $this->rankSegment([$a, $b, $c], [[1, 1, 3, 2, 3], [2, 3, 1, 1, 2], [3, 2, 2, 3, 1]]);
        $rows = $this->casualRows();

        $this->assertSame([1000, 900, 1100], [$rows[$a->id]['rank_total'], $rows[$b->id]['rank_total'], $rows[$c->id]['rank_total']]);
        $this->assertSame([2, 1, 3], [$rows[$a->id]['rank'], $rows[$b->id]['rank'], $rows[$c->id]['rank']]);
        $this->assertSame([100, 100, 300, 200, 300], array_values($rows[$a->id]['totals']));
        $this->assertFalse($rows[$b->id]['tied']);

        // Equal totals share the rank and are flagged for the board; there is no tie-break.
        Score::where(['segment' => 'casual_wear', 'candidate_id' => $c->id])->delete();
        $this->rankSegment([$c], [[3, 2, 2, 1, 1]]);
        $rows = $this->casualRows();

        $this->assertSame([900, 900], [$rows[$b->id]['rank_total'], $rows[$c->id]['rank_total']]);
        $this->assertSame([1, 1, 3], [$rows[$b->id]['rank'], $rows[$c->id]['rank'], $rows[$a->id]['rank']]);
        $this->assertTrue($rows[$b->id]['tied'] && $rows[$c->id]['tied']);
    }

    public function test_casual_wear_compares_average_ranks_until_every_judge_has_ranked(): void
    {
        [$a, $b] = $this->only('ms', 2);
        // $a: 2 from every judge, total 10. $b: 1 from three judges so far, total 3 (but an average of 1).
        $this->rankSegment([$a], [[2, 2, 2, 2, 2]]);
        $judges = User::onPanel('pageant')->get();
        foreach ($judges->take(3) as $judge) {
            $this->score('casual_wear', $b, $judge, ['rank' => 1]);
        }
        $rows = $this->casualRows();

        $this->assertSame(300, $rows[$b->id]['rank_total']);
        $this->assertSame(3, $rows[$b->id]['counted']);
        $this->assertSame([1, 2], [$rows[$b->id]['rank'], $rows[$a->id]['rank']]);

        // A judge ranking $b 5th puts the averages at 2 and 2: level, even though the totals differ.
        $this->score('casual_wear', $b, $judges[3], ['rank' => 5]);
        $rows = $this->casualRows();
        $this->assertSame([800, 1000], [$rows[$b->id]['rank_total'], $rows[$a->id]['rank_total']]);
        $this->assertSame([1, 1], [$rows[$b->id]['rank'], $rows[$a->id]['rank']]);
    }

    public function test_casual_wear_scores_every_candidate_not_only_finalists(): void
    {
        $candidates = $this->candidates('ms', 3);
        $candidates[1]->update(['is_finalist' => true]);

        $this->assertSame(Candidate::division('ms')->count(), Segment::find('casual_wear')->candidates('ms')->count());
    }

    public function test_a_segment_average_counts_the_judges_who_have_scored_so_far(): void
    {
        [$a, $b] = $this->only('ms', 2);
        $judges = User::onPanel('prepageant')->get();

        foreach ([[$a, 0.9], [$b, 0.8]] as [$candidate, $share]) {
            $this->scorePanel('prepageant', $candidate, $share);
            $this->scorePanel('pageant', $candidate, $share);
        }
        // Judge 3 has no thematic wear score for B yet.
        Score::where('segment', 'thematic_wear')->where('candidate_id', $b->id)->where('judge_id', $judges[2]->id)->delete();

        $rows = collect((new Tabulator)->roundOne('ms')['rows'])->keyBy(fn ($r) => $r['candidate']->id);
        $thematic = $rows[$b->id]['segments']['thematic_wear'];

        $this->assertSame([2, 1200, 1200], [$thematic['counted'], $thematic['average'], $thematic['weighted']]);
        $this->assertSame(8000, $rows[$b->id]['total']);
        $this->assertTrue($rows[$a->id]['complete']);
        $this->assertFalse($rows[$b->id]['complete']);
    }

    public function test_round_two_places_by_the_average_of_the_judges_totals(): void
    {
        [$a, $b, $c] = $this->finalists(3);
        $judges = User::onPanel('pageant')->get();

        // Totals per judge for A, B, C. Averages: A 78.6, B 77, C 75. Each judge's own order
        // differs (judge 2 puts B first, judge 4 puts C first), but only the averages count.
        $sheets = [
            [90, 80, 70],
            [70, 90, 80],
            [85, 85, 60],
            [60, 70, 90],
            [88, 60, 75],
        ];
        foreach ($sheets as $j => $totals) {
            foreach ([$a, $b, $c] as $i => $candidate) {
                $this->score('final', $candidate, $judges[$j], ['impression' => $totals[$i] / 2, 'intelligence' => $totals[$i] / 2]);
            }
        }

        $rows = collect((new Tabulator)->segment(Segment::find('final'), 'ms')['rows'])->keyBy(fn ($r) => $r['candidate']->id);

        $this->assertSame([7860, 7700, 7500], [$rows[$a->id]['average'], $rows[$b->id]['average'], $rows[$c->id]['average']]);
        $this->assertSame([1, 2, 3], [$rows[$a->id]['rank'], $rows[$b->id]['rank'], $rows[$c->id]['rank']]);
        $this->assertFalse(collect($rows)->contains('tied', true));
        $this->assertSame('Ms. LCUAA 2026', Tabulator::placement($rows[$a->id]['rank'], 'ms'));
        $this->assertSame('2nd runner-up', Tabulator::placement($rows[$c->id]['rank'], 'ms'));
    }

    public function test_an_average_counts_the_judges_who_have_scored_so_far(): void
    {
        [$a, $b, $c] = $this->finalists(3);
        $judges = User::onPanel('pageant')->get();

        // Judge 5 has not scored C yet: C's 70 + 80 + 60 + 90 is divided by 4, not 5.
        $sheets = [[90, 80, 70], [70, 90, 80], [85, 85, 60], [60, 70, 90], [88, 60, null]];
        foreach ($sheets as $j => $totals) {
            foreach ([$a, $b, $c] as $i => $candidate) {
                if ($totals[$i] !== null) {
                    $this->score('final', $candidate, $judges[$j], ['impression' => $totals[$i] / 2, 'intelligence' => $totals[$i] / 2]);
                }
            }
        }

        $rows = collect((new Tabulator)->segment(Segment::find('final'), 'ms')['rows'])->keyBy(fn ($r) => $r['candidate']->id);

        $this->assertSame([5, 5, 4], [$rows[$a->id]['counted'], $rows[$b->id]['counted'], $rows[$c->id]['counted']]);
        $this->assertSame([7860, 7700, 7500], [$rows[$a->id]['average'], $rows[$b->id]['average'], $rows[$c->id]['average']]);
        $this->assertSame([1, 2, 3], [$rows[$a->id]['rank'], $rows[$b->id]['rank'], $rows[$c->id]['rank']]);
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

    /** Each judge's Casual wear rank, per candidate (one list of ranks each, in judge order). */
    private function rankSegment(array $candidates, array $ranks): void
    {
        $judges = User::onPanel('pageant')->get();
        foreach ($candidates as $i => $candidate) {
            foreach ($judges as $j => $judge) {
                $this->score('casual_wear', $candidate, $judge, ['rank' => $ranks[$i][$j]]);
            }
        }
    }

    private function casualRows()
    {
        return collect((new Tabulator)->segment(Segment::find('casual_wear'), 'ms')['rows'])->keyBy(fn ($r) => $r['candidate']->id);
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
