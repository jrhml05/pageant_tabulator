<?php

namespace App\Scoring;

use App\Models\Candidate;
use App\Models\Score;
use App\Models\ScoreLock;
use App\Models\User;
use Illuminate\Support\Collection;

/**
 * Turns judges' points into averages and ranks.
 *
 * Every figure is in hundredths (int). Equal figures share a rank and are flagged as tied for
 * the board to settle.
 *
 * A segment is placed by rank sum: each judge ranks the candidates by total (ties share a
 * rank), the ranks are added up, and the lowest sum places first. A segment set to "average"
 * ranks by the mean of the judges' totals, rounded to 2 decimals, instead.
 *
 * Round 1 is placed by ranks per panel: each judge ranks the candidates by their total across
 * the panel's segments, the ranks are averaged over the panel's judges (3 pre-pageant, 5 pageant
 * night) and weighted (30%, 70%), and the lowest total wins. Averaging, not summing, keeps the
 * 5-judge panel from outweighing its 70%.
 */
final class Tabulator
{
    /**
     * One segment for one division: each judge's points and total, the average, and ranks.
     *
     * @return array{
     *     segment: Segment,
     *     judges: Collection,
     *     locked: Collection,
     *     rank_judges: Collection,
     *     rows: list<array{
     *         candidate: Candidate,
     *         points: array<int, array<string, ?int>>,
     *         totals: array<int, ?int>,
     *         judge_ranks: array<int, ?int>,
     *         counted: int,
     *         average: ?int,
     *         rank_sum: ?int,
     *         average_rank: ?int,
     *         rank: ?int,
     *         tied: bool,
     *     }>,
     * }
     */
    public function segment(Segment $segment, string $division): array
    {
        $judges = $segment->judges();
        $candidates = $segment->candidates($division);

        $scores = Score::where('segment', $segment->key)
            ->whereIn('candidate_id', $candidates->pluck('id'))
            ->whereIn('judge_id', $judges->pluck('id'))
            ->get()
            ->groupBy(['candidate_id', 'judge_id']);

        $rows = [];
        foreach ($candidates as $candidate) {
            $points = [];
            $totals = [];
            foreach ($judges as $judge) {
                $entered = $scores->get($candidate->id)?->get($judge->id)?->pluck('points', 'criterion') ?? collect();
                foreach (array_keys($segment->criteria) as $criterion) {
                    $value = $entered->get($criterion);
                    $points[$judge->id][$criterion] = $value === null ? null : Points::hundredths($value);
                }
                // A judge's total counts only once every criterion has points.
                $totals[$judge->id] = in_array(null, $points[$judge->id], true) ? null : array_sum($points[$judge->id]);
            }

            $counted = array_filter($totals, fn ($total) => $total !== null);

            $rows[$candidate->id] = [
                'candidate' => $candidate,
                'points' => $points,
                'totals' => $totals,
                'counted' => count($counted),
                'average' => $counted ? (int) round(array_sum($counted) / count($counted)) : null,
            ];
        }

        $ranked = self::rankSums(array_map(fn ($row) => $row['totals'], $rows), $judges);
        foreach ($rows as $id => $row) {
            $rows[$id]['judge_ranks'] = $ranked['judge_ranks'][$id];
            $rows[$id]['rank_sum'] = $ranked['rank_sums'][$id];
            $rows[$id]['average_rank'] = self::averageRank($ranked['rank_sums'][$id], $ranked['rank_judges']->count());
        }

        $rows = $segment->ranksBySum()
            ? $this->withRanks($rows, 'rank_sum', lowestFirst: true)
            : $this->withRanks($rows, 'average');

        return [
            'segment' => $segment,
            'judges' => $judges,
            'locked' => ScoreLock::where('segment', $segment->key)->whereIn('judge_id', $judges->pluck('id'))->pluck('judge_id'),
            'rank_judges' => $ranked['rank_judges'],
            'rows' => array_values($rows),
        ];
    }

    /**
     * Round 1 for one division, placed by weighted average ranks per panel (lowest wins).
     *
     * Per panel, a judge's total for a candidate adds up the judge's totals for the panel's
     * Round 1 segments (pre-pageant: talent + thematic wear), and counts only when every one of
     * them is complete. Each judge ranks the candidates by that total; the ranks are added up
     * (`rank_sum`), averaged over the judges who count, and multiplied by the panel's weight:
     * an average rank of 2 at 30% is 60 (0.60). The total adds the panels up. It is compared
     * exactly, over a common denominator, so a tie is a true tie and not two figures that round
     * alike. Each segment still ranks on its own for its own winner (`segment()`).
     *
     * A tie on the total goes to the lower average rank in the `tie_break` segments, in order
     * (`tie_break` on the row lists the ones it took). Candidates level on all of them share a
     * rank and are flagged.
     *
     * @return array{
     *     panels: Collection<string, array{label: string, weight: float, segments: Collection, judges: Collection, rank_judges: Collection}>,
     *     criteria: Collection<string, array{segment: Segment, rank_judges: Collection}>,
     *     tie_break: Collection<int, string>,
     *     rows: list<array{
     *         candidate: Candidate,
     *         panels: array<string, array{totals: array<int, ?int>, judge_ranks: array<int, ?int>, rank_sum: ?int, average_rank: ?int, weighted: ?int}>,
     *         total: ?int,
     *         complete: bool,
     *         rank: ?int,
     *         tied: bool,
     *         tie_break: list<string>,
     *     }>,
     *     cutoff_tie: bool,
     *     suggested: list<int>,
     * }
     */
    public function roundOne(string $division): array
    {
        $candidates = Candidate::division($division)->get();

        $sheets = Segment::inRound(1)->map(function (Segment $segment) use ($division) {
            $sheet = $this->segment($segment, $division);
            $sheet['rows'] = collect($sheet['rows'])->keyBy(fn ($row) => $row['candidate']->id);

            return $sheet;
        });

        $panels = $sheets->groupBy(fn ($sheet) => $sheet['segment']->panel, preserveKeys: true)
            ->map(function (Collection $segments, string $panel) use ($candidates) {
                $judges = User::onPanel($panel)->get();

                $totals = [];
                foreach ($candidates as $candidate) {
                    foreach ($judges as $judge) {
                        $parts = $segments->map(fn ($sheet) => $sheet['rows'][$candidate->id]['totals'][$judge->id]);
                        $totals[$candidate->id][$judge->id] = $parts->containsStrict(null) ? null : $parts->sum();
                    }
                }

                return [
                    'label' => config("pageant.panels.{$panel}"),
                    'weight' => $segments->sum(fn ($sheet) => $sheet['segment']->maxPoints()),
                    'segments' => $segments->map(fn ($sheet) => $sheet['segment']),
                    'judges' => $judges,
                    'totals' => $totals,
                    'ranked' => self::rankSums($totals, $judges),
                ];
            });

        $denominator = $panels->map(fn ($panel) => $panel['ranked']['rank_judges']->count())->filter()
            ->reduce(fn (int $lcm, int $judges) => self::lcm($lcm, $judges), 1);
        // A tie-break segment no judge has finished can't separate anyone yet.
        $tieBreak = collect(config('pageant.tie_break'))
            ->filter(fn (string $key) => $sheets->has($key) && $sheets[$key]['rank_judges']->isNotEmpty())->values();

        $rows = [];
        $keys = [];
        foreach ($candidates as $candidate) {
            $scores = [];
            $exact = null;
            foreach ($panels as $key => $panel) {
                $rankSum = $panel['ranked']['rank_sums'][$candidate->id];
                $judges = $panel['ranked']['rank_judges']->count();
                // The weight in hundredths of a percent: 30% is 3000.
                $basis = (int) round($panel['weight'] * 100);
                $scores[$key] = [
                    'totals' => $panel['totals'][$candidate->id],
                    'judge_ranks' => $panel['ranked']['judge_ranks'][$candidate->id],
                    'rank_sum' => $rankSum,
                    'average_rank' => self::averageRank($rankSum, $judges),
                    'weighted' => $rankSum === null ? null : (int) round($rankSum * $basis / $judges / 100),
                ];
                if ($rankSum !== null) {
                    $exact = ($exact ?? 0) + $rankSum * $basis * intdiv($denominator, $judges);
                }
            }

            $rows[$candidate->id] = [
                'candidate' => $candidate,
                'panels' => $scores,
                'total' => $exact === null ? null : (int) round($exact / ($denominator * 100)),
                'complete' => ! in_array(null, array_column($scores, 'rank_sum'), true),
            ];
            // Every judge who counts in a segment ranked every candidate, so within a segment the
            // rank sums compare the same way the average ranks do.
            $keys[$candidate->id] = $exact === null ? null
                : [$exact, ...$tieBreak->map(fn ($key) => $sheets[$key]['rows'][$candidate->id]['rank_sum'])];
        }

        foreach (self::rankLowestFirst($keys) as $id => $rank) {
            $rows[$id] += ['rank' => $rank['rank'], 'tied' => $rank['tied'], 'tie_break' => $tieBreak->take($rank['depth'])->all()];
        }

        $finalists = (int) config('pageant.finalists');
        $ordered = collect($rows)->whereNotNull('rank')->sortBy('rank')->values();
        $atCutoff = $ordered->get($finalists - 1);
        $afterCutoff = $ordered->get($finalists);

        return [
            'panels' => $panels->map(fn ($panel) => [
                'label' => $panel['label'],
                'weight' => $panel['weight'],
                'segments' => $panel['segments'],
                'judges' => $panel['judges'],
                'rank_judges' => $panel['ranked']['rank_judges'],
            ]),
            'criteria' => $sheets->map(fn ($sheet) => ['segment' => $sheet['segment'], 'rank_judges' => $sheet['rank_judges']]),
            'tie_break' => collect(config('pageant.tie_break')),
            'rows' => array_values($rows),
            // Candidates still tied across the last finalist spot: the board picks who goes through.
            'cutoff_tie' => $atCutoff && $afterCutoff && $atCutoff['rank'] === $afterCutoff['rank'],
            'suggested' => $ordered->where('rank', '<=', $finalists)->pluck('candidate.id')->all(),
        ];
    }

    /** "Ms. LCUAA 2026", "1st runner-up"... for a Round 2 rank. */
    public static function placement(?int $rank, string $division): ?string
    {
        $title = $rank === null ? null : config("pageant.placements.{$rank}");

        return $title === null ? null : str_replace('{division}', config("pageant.divisions.{$division}"), $title);
    }

    /**
     * Competition ranking, highest first: 90, 85, 85, 80 rank 1, 2, 2, 4. Nulls stay unranked.
     * With $lowestFirst (rank sums), the smallest value ranks 1.
     *
     * @param  array<int, ?int>  $values
     * @return array<int, array{rank: ?int, tied: bool}>
     */
    public static function rank(array $values, bool $lowestFirst = false): array
    {
        $scored = array_filter($values, fn ($value) => $value !== null);
        $counts = array_count_values($scored);
        if ($lowestFirst) {
            asort($scored);
        } else {
            arsort($scored);
        }

        $ranks = array_map(fn () => ['rank' => null, 'tied' => false], $values);
        $position = 0;
        $previous = null;
        $rank = 0;
        foreach ($scored as $id => $value) {
            $position++;
            if ($value !== $previous) {
                $rank = $position;
                $previous = $value;
            }
            $ranks[$id] = ['rank' => $rank, 'tied' => $counts[$value] > 1];
        }

        return $ranks;
    }

    /**
     * Each judge ranks the candidates by total, and a candidate's rank sum adds up those ranks.
     * Only judges who have a total for every candidate count, so every sum covers the same judges.
     *
     * @param  array<int, array<int, ?int>>  $totals  candidate id => judge id => total
     * @return array{judge_ranks: array<int, array<int, ?int>>, rank_sums: array<int, ?int>, rank_judges: Collection}
     */
    private static function rankSums(array $totals, Collection $judges): array
    {
        $judgeRanks = array_map(fn () => [], $totals);
        foreach ($judges as $judge) {
            foreach (self::rank(array_map(fn ($byJudge) => $byJudge[$judge->id], $totals)) as $id => $rank) {
                $judgeRanks[$id][$judge->id] = $rank['rank'];
            }
        }

        $rankJudges = $judges->filter(fn ($judge) => $totals && collect($totals)->every(fn ($byJudge) => $byJudge[$judge->id] !== null))
            ->pluck('id')->values();

        return [
            'judge_ranks' => $judgeRanks,
            'rank_sums' => array_map(fn ($ranks) => $rankJudges->isEmpty() ? null : $rankJudges->sum(fn ($id) => $ranks[$id]), $judgeRanks),
            'rank_judges' => $rankJudges,
        ];
    }

    /**
     * Competition ranking on lists of values, lowest first: candidates level on the first value
     * are separated by the next one, and so on. Candidates level on every value share a rank.
     * `depth` is how many values after the first it took to separate a candidate from the others
     * level with it on the first: 0 when no one was level, or when nothing separated them.
     *
     * @param  array<int, ?list<int>>  $keys
     * @return array<int, array{rank: ?int, tied: bool, depth: int}>
     */
    private static function rankLowestFirst(array $keys): array
    {
        $scored = array_filter($keys, fn ($key) => $key !== null);
        uasort($scored, fn (array $a, array $b) => $a <=> $b);

        $ranks = array_map(fn () => ['rank' => null, 'tied' => false, 'depth' => 0], $keys);
        $position = 0;
        $previous = null;
        $rank = 0;
        foreach ($scored as $id => $key) {
            $position++;
            if ($key !== $previous) {
                $rank = $position;
                $previous = $key;
            }

            $level = array_filter($scored, fn ($other) => $other[0] === $key[0]);
            $same = array_filter($level, fn ($other) => $other === $key);
            $depth = 0;
            if (count($level) > 1 && count($same) === 1) {
                do {
                    $depth++;
                    $prefix = array_slice($key, 0, $depth + 1);
                } while (count(array_filter($level, fn ($other) => array_slice($other, 0, $depth + 1) === $prefix)) > 1);
            }

            $ranks[$id] = ['rank' => $rank, 'tied' => count($same) > 1, 'depth' => $depth];
        }

        return $ranks;
    }

    /** A rank sum over its judges, in hundredths: 13 over 5 judges is 260 (2.60). */
    private static function averageRank(?int $rankSum, int $judges): ?int
    {
        return $rankSum === null || $judges === 0 ? null : (int) round($rankSum * 100 / $judges);
    }

    private static function lcm(int $a, int $b): int
    {
        [$x, $y] = [$a, $b];
        while ($y !== 0) {
            [$x, $y] = [$y, $x % $y];
        }

        return intdiv($a * $b, $x);
    }

    private function withRanks(array $rows, string $field, bool $lowestFirst = false): array
    {
        foreach (self::rank(array_map(fn ($row) => $row[$field], $rows), $lowestFirst) as $id => $rank) {
            $rows[$id] += $rank;
        }

        return $rows;
    }
}
