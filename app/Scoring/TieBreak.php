<?php

namespace App\Scoring;

use App\Models\Candidate;
use App\Models\TieDecision;
use Illuminate\Support\Collection;

/**
 * The board's order for candidates tied on a segment's average, saved by the tabulator once
 * every judge has locked in: every Round 2 placement, and first place in a Round 1 segment (its
 * winner). Other ties stay flagged for the board.
 *
 * A saved order holds only while the same candidates are tied on the same average. When a score
 * changes that, the order is deleted and the tie shows again.
 */
final class TieBreak
{
    /** Whether the board's order for a tie at this rank is saved here. */
    public static function settles(Segment $segment, int $rank): bool
    {
        if ($segment->isAward() || $segment->isRanked()) {
            return false;
        }

        return $segment->forFinalistsOnly() ? $rank <= count(config('pageant.placements')) : $rank === 1;
    }

    /** How many places the board picks in a tie of $size: all but the last in Round 2, the winner in Round 1. */
    public static function picks(Segment $segment, int $size): int
    {
        return $segment->forFinalistsOnly() ? $size - 1 : 1;
    }

    /** The place a tie at $rank is for: "1st runner-up" in Round 2, "first place" in a Round 1 segment. */
    public static function place(Segment $segment, int $rank, string $division): string
    {
        return $segment->forFinalistsOnly() ? Tabulator::placement($rank, $division) ?? "rank {$rank}" : 'first place';
    }

    /**
     * Applies the saved orders to a segment's ranked rows. The candidates in an order take its
     * ranks one by one and are marked `decided`; in Round 1 the ones left after the winner stay
     * tied with each other on the next rank.
     *
     * @param  array<int, array{candidate: Candidate, average: ?int, rank: ?int, tied: bool}>  $rows  keyed by candidate id
     * @return array{0: array<int, array>, 1: list<array{rank: int, average: int, candidates: Collection<int, Candidate>, order: ?list<int>}>}
     */
    public static function apply(Segment $segment, string $division, array $rows): array
    {
        $rows = array_map(fn ($row) => $row + ['decided' => false], $rows);
        if ($segment->isAward() || $segment->isRanked()) {
            return [$rows, []];
        }

        $saved = TieDecision::where('segment', $segment->key)->where('division', $division)->get()->keyBy('rank');
        $groups = collect($rows)->where('tied', true)->groupBy('rank')->sortKeys()
            ->filter(fn ($group, $rank) => self::settles($segment, $rank));

        $ties = [];
        foreach ($groups as $rank => $group) {
            $ids = $group->pluck('candidate.id')->sort()->values()->all();
            $average = $group->first()['average'];
            $decision = $saved->pull($rank);
            if ($decision && ($decision->candidates !== $ids || $decision->average !== $average)) {
                $decision->delete();
                $decision = null;
            }

            if ($decision) {
                foreach ($decision->order as $i => $id) {
                    $rows[$id] = ['rank' => $rank + $i, 'tied' => false, 'decided' => true] + $rows[$id];
                }
                $rest = array_diff($ids, $decision->order);
                foreach ($rest as $id) {
                    $rows[$id] = ['rank' => $rank + count($decision->order), 'tied' => count($rest) > 1, 'decided' => count($rest) === 1] + $rows[$id];
                }
            }

            $ties[] = [
                'rank' => $rank,
                'average' => $average,
                'candidates' => $group->pluck('candidate')->sortBy('number')->values(),
                'order' => $decision?->order,
            ];
        }

        // Orders for ties that are gone.
        $saved->each->delete();

        return [$rows, $ties];
    }

    /** @param  list<int>  $order  candidate ids, first place first; Round 2 orders every tied candidate */
    public static function save(Segment $segment, string $division, array $tie, array $order): void
    {
        TieDecision::updateOrCreate(
            ['segment' => $segment->key, 'division' => $division, 'rank' => $tie['rank']],
            [
                'average' => $tie['average'],
                'candidates' => $tie['candidates']->pluck('id')->sort()->values()->all(),
                'order' => array_values($order),
            ],
        );
    }

    public static function clear(Segment $segment, string $division, int $rank): void
    {
        TieDecision::where('segment', $segment->key)->where('division', $division)->where('rank', $rank)->delete();
    }

    /** "No. 3 and No. 5 tied at 88.40; the board of judges placed No. 3 higher." For a tie with a saved order. */
    public static function note(Segment $segment, array $tie): string
    {
        $number = fn (int $id) => 'No. '.$tie['candidates']->firstWhere('id', $id)->number;
        $tied = $tie['candidates']->map(fn (Candidate $c) => "No. {$c->number}")->join(', ', ' and ');
        $at = Points::format($tie['average']);

        if (! $segment->forFinalistsOnly()) {
            return "{$tied} tied for first at {$at}; the board of judges named {$number($tie['order'][0])} the winner.";
        }

        return count($tie['order']) === 2
            ? "{$tied} tied at {$at}; the board of judges placed {$number($tie['order'][0])} higher."
            : "{$tied} tied at {$at}; the board of judges placed them in this order: ".collect($tie['order'])->map($number)->join(', ').'.';
    }
}
