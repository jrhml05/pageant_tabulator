<?php

namespace App\Scoring;

use App\Models\Candidate;
use App\Models\Score;
use App\Models\ScoreLock;
use Illuminate\Support\Collection;

/**
 * Turns judges' points into averages and ranks.
 *
 * Every figure is in hundredths (int). Equal figures share a rank and are flagged as tied for
 * the board to settle; the board's order for a Round 2 placement or a segment's first place is
 * saved and applied by TieBreak. Outside a ranked segment judges don't rank anyone: a segment
 * adds up the judges' totals, divides by the number of judges, and ranks that average, highest
 * first.
 *
 * Round 1 is placed by weighted scores: each segment's average, scaled to its weight in the
 * round, added up out of 100. The highest total wins.
 *
 * A ranked segment (Casual wear) is the exception: each judge ranks the candidates, and the
 * lowest total of their ranks wins.
 */
final class Tabulator
{
    /**
     * One segment for one division: each judge's points and total, the average, and its rank.
     *
     * In a ranked segment a judge's total is the rank they gave, `rank_total` adds the judges'
     * ranks up, and the lowest places first. Until every judge has ranked a candidate, candidates
     * are compared by their average rank, so a missing judge doesn't lower anyone's total.
     *
     * @return array{
     *     segment: Segment,
     *     judges: Collection,
     *     locked: Collection,
     *     rows: list<array{
     *         candidate: Candidate,
     *         points: array<int, array<string, ?int>>,
     *         totals: array<int, ?int>,
     *         counted: int,
     *         average: ?int,
     *         rank_total: ?int,
     *         rank: ?int,
     *         tied: bool,
     *         decided: bool,
     *     }>,
     *     ties: list<array{rank: int, average: int, candidates: Collection, order: ?list<int>}>,
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
                'rank_total' => $segment->isRanked() && $counted ? array_sum($counted) : null,
            ];
        }

        // rank() places the highest first, so a ranked segment's averages are negated.
        $sign = $segment->isRanked() ? -1 : 1;
        foreach (self::rank(array_map(fn ($row) => $row['average'] === null ? null : $sign * $row['average'], $rows)) as $id => $rank) {
            $rows[$id] += $rank;
        }

        // The board's saved orders for ties it settles here; `ties` lists those ties, settled or not.
        [$rows, $ties] = TieBreak::apply($segment, $division, $rows);

        return [
            'segment' => $segment,
            'judges' => $judges,
            'locked' => ScoreLock::where('segment', $segment->key)->whereIn('judge_id', $judges->pluck('id'))->pluck('judge_id'),
            'rows' => array_values($rows),
            'ties' => $ties,
        ];
    }

    /** Every judge has locked in and scored every candidate, so a segment's results won't move. */
    public static function isFinal(array $result): bool
    {
        $judges = $result['judges'];

        return $judges->isNotEmpty()
            && $judges->pluck('id')->diff($result['locked'])->isEmpty()
            && collect($result['rows'])->every(fn ($row) => $row['counted'] === $judges->count());
    }

    /**
     * Round 1 for one division, placed by weighted scores (highest wins).
     *
     * Each segment's score is its average (the mean of the judges' totals, to 2 decimals), scaled
     * to the segment's weight: talent is judged out of 100 and counts 15, so an average of 87.33
     * adds 13.0995. The total adds the segments up, out of 100, and counts whatever is scored so
     * far. It is compared before rounding, so a tie is a true tie and not two figures that round
     * alike. `weighted` and `total` are shown in hundredths.
     *
     * A tie on the total goes to the higher average in the `tie_break` segments, in order
     * (`tie_break` on the row lists the ones it took). Candidates level on all of them share a
     * rank and are flagged.
     *
     * @return array{
     *     segments: Collection<string, Segment>,
     *     judges: Collection<string, int>,
     *     tie_break: Collection<int, string>,
     *     rows: list<array{
     *         candidate: Candidate,
     *         segments: array<string, array{average: ?int, counted: int, weighted: ?int}>,
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
        $segments = Segment::inRound(1);

        $sheets = $segments->map(function (Segment $segment) use ($division) {
            $sheet = $this->segment($segment, $division);
            $sheet['rows'] = collect($sheet['rows'])->keyBy(fn ($row) => $row['candidate']->id);

            return $sheet;
        });
        $judges = $sheets->map(fn ($sheet) => $sheet['judges']->count());
        $tieBreak = collect(config('pageant.tie_break'))->filter(fn (string $key) => $sheets->has($key))->values();

        $rows = [];
        $keys = [];
        foreach ($candidates as $candidate) {
            $scores = [];
            $exact = null;
            foreach ($segments as $key => $segment) {
                $row = $sheets[$key]['rows'][$candidate->id];
                // In ten-thousandths: 8733 (87.33) × 15 is 130995 (13.0995).
                $weighted = $row['average'] === null ? null : $row['average'] * $segment->scale();
                $scores[$key] = [
                    'average' => $row['average'],
                    'counted' => $row['counted'],
                    'weighted' => $segment->weighted($row['average']),
                ];
                if ($weighted !== null) {
                    $exact = ($exact ?? 0) + $weighted;
                }
            }

            $rows[$candidate->id] = [
                'candidate' => $candidate,
                'segments' => $scores,
                'total' => $exact === null ? null : (int) round($exact / 100),
                'complete' => $judges->every(fn (int $count, string $key) => $count > 0 && $scores[$key]['counted'] === $count),
            ];
            // Ranked lowest first, so the figures are negated; an unscored tie-break segment counts as below 0.
            $keys[$candidate->id] = $exact === null ? null
                : [-$exact, ...$tieBreak->map(fn ($key) => -($scores[$key]['average'] ?? -1))];
        }

        foreach (self::rankLowestFirst($keys) as $id => $rank) {
            $rows[$id] += ['rank' => $rank['rank'], 'tied' => $rank['tied'], 'tie_break' => $tieBreak->take($rank['depth'])->all()];
        }

        $finalists = (int) config('pageant.finalists');
        $ordered = collect($rows)->whereNotNull('rank')->sortBy('rank')->values();
        $atCutoff = $ordered->get($finalists - 1);
        $afterCutoff = $ordered->get($finalists);

        return [
            'segments' => $segments,
            'judges' => $judges,
            'tie_break' => $tieBreak,
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
     * Who a segment's winners sheet names. Round 2 announces everyone within the placement titles
     * (top 5), lowest placement first so the winner is read out last; any other segment names its
     * winner only. Tied candidates share a placement, in number order.
     *
     * @param  array{segment: Segment, rows: list<array{candidate: Candidate, rank: ?int}>}  $result  from segment()
     */
    public static function winners(array $result): Collection
    {
        $last = $result['segment']->forFinalistsOnly() ? count(config('pageant.placements')) : 1;

        return collect($result['rows'])
            ->filter(fn ($row) => $row['rank'] !== null && $row['rank'] <= $last)
            ->sortBy([['rank', 'desc'], ['candidate.number', 'asc']])
            ->values();
    }

    /**
     * Competition ranking, highest first: 90, 85, 85, 80 rank 1, 2, 2, 4. Nulls stay unranked.
     *
     * @param  array<int, ?int>  $values
     * @return array<int, array{rank: ?int, tied: bool}>
     */
    public static function rank(array $values): array
    {
        $scored = array_filter($values, fn ($value) => $value !== null);
        $counts = array_count_values($scored);
        arsort($scored);

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
}
