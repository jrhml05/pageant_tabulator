<?php

namespace App\Scoring;

use App\Models\Candidate;
use App\Models\Score;
use App\Models\ScoreLock;
use Illuminate\Support\Collection;

/**
 * Turns judges' points into averages and ranks.
 *
 * Every figure is in hundredths (int). A segment average is the mean of the judges' totals,
 * rounded to 2 decimals; the Round 1 total is the sum of those rounded averages, so the
 * numbers on a printed sheet always add up. Ranks follow the rounded figures, and equal
 * figures share a rank and are flagged as tied for the board to settle.
 *
 * A "rank_sum" segment (Round 2) is placed differently: each judge ranks the candidates by
 * total (ties share a rank), the ranks are added up, and the lowest sum places first.
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
                'judge_ranks' => [],
                'counted' => count($counted),
                'average' => $counted ? (int) round(array_sum($counted) / count($counted)) : null,
            ];
        }

        foreach ($judges as $judge) {
            $ranks = self::rank(array_map(fn ($row) => $row['totals'][$judge->id], $rows));
            foreach ($ranks as $id => $rank) {
                $rows[$id]['judge_ranks'][$judge->id] = $rank['rank'];
            }
        }

        // Rank sums use only judges who have scored every candidate, so each sum covers the same judges.
        $rankJudges = $judges->filter(fn ($judge) => $rows && collect($rows)->every(fn ($row) => $row['totals'][$judge->id] !== null))
            ->pluck('id');
        foreach ($rows as $id => $row) {
            $rows[$id]['rank_sum'] = $rankJudges->isEmpty() ? null : $rankJudges->sum(fn ($judgeId) => $row['judge_ranks'][$judgeId]);
        }

        $rows = $segment->ranksBySum()
            ? $this->withRanks($rows, 'rank_sum', lowestFirst: true)
            : $this->withRanks($rows, 'average');

        return [
            'segment' => $segment,
            'judges' => $judges,
            'locked' => ScoreLock::where('segment', $segment->key)->whereIn('judge_id', $judges->pluck('id'))->pluck('judge_id'),
            'rank_judges' => $rankJudges->values(),
            'rows' => array_values($rows),
        ];
    }

    /**
     * Round 1 for one division: every segment average, the total out of 100, and ranks.
     *
     * @return array{
     *     segments: Collection,
     *     rows: list<array{candidate: Candidate, averages: array<string, ?int>, total: ?int, complete: bool, rank: ?int, tied: bool}>,
     *     cutoff_tie: bool,
     *     suggested: list<int>,
     * }
     */
    public function roundOne(string $division): array
    {
        $segments = Segment::inRound(1);

        $averages = $segments->map(fn (Segment $segment) => collect($this->segment($segment, $division)['rows'])
            ->mapWithKeys(fn ($row) => [$row['candidate']->id => $row['average']]));

        $rows = [];
        foreach (Candidate::division($division)->get() as $candidate) {
            $byKey = $segments->map(fn (Segment $segment) => $averages[$segment->key]->get($candidate->id))->all();
            $scored = array_filter($byKey, fn ($average) => $average !== null);

            $rows[$candidate->id] = [
                'candidate' => $candidate,
                'averages' => $byKey,
                'total' => $scored ? array_sum($scored) : null,
                'complete' => count($scored) === count($byKey),
            ];
        }

        $rows = $this->withRanks($rows, 'total');

        $finalists = (int) config('pageant.finalists');
        $ordered = collect($rows)->whereNotNull('rank')->sortBy('rank')->values();
        $atCutoff = $ordered->get($finalists - 1);
        $afterCutoff = $ordered->get($finalists);

        return [
            'segments' => $segments,
            'rows' => array_values($rows),
            // Candidates tied across the last finalist spot: the board picks who goes through.
            'cutoff_tie' => $atCutoff && $afterCutoff && $atCutoff['total'] === $afterCutoff['total'],
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

    private function withRanks(array $rows, string $field, bool $lowestFirst = false): array
    {
        foreach (self::rank(array_map(fn ($row) => $row[$field], $rows), $lowestFirst) as $id => $rank) {
            $rows[$id] += $rank;
        }

        return $rows;
    }
}
