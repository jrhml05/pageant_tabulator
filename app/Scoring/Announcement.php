<?php

namespace App\Scoring;

use App\Models\Candidate;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * The order the emcee calls the finalists. It is random and deliberately never matches the
 * Round 1 ranking or the candidate numbers, so the call order gives nothing away.
 */
final class Announcement
{
    private const ATTEMPTS = 100;

    public function __construct(private Tabulator $tabulator) {}

    /** Finalists of a division in call order. */
    public static function order(string $division): Collection
    {
        return Candidate::where('division', $division)->where('is_finalist', true)
            ->orderBy('announce_order')->orderBy('number')->get();
    }

    /**
     * Draws a new call order for a division's finalists. Unless $force, an existing order for
     * the same finalists is kept, so saving the same five again doesn't change a printed sheet.
     */
    public function shuffle(string $division, bool $force = false): void
    {
        $finalists = Candidate::where('division', $division)->where('is_finalist', true)->get();

        $hasOrder = $finalists->isNotEmpty() && $finalists->every(fn ($c) => $c->announce_order !== null)
            && $finalists->pluck('announce_order')->sort()->values()->all() === range(1, $finalists->count());
        if ($hasOrder && ! $force) {
            return;
        }

        $ids = $finalists->pluck('id');
        $byNumber = $finalists->sortBy('number')->pluck('id')->values()->all();
        $ranks = collect($this->tabulator->roundOne($division)['rows'])->mapWithKeys(fn ($r) => [$r['candidate']->id => $r['rank']]);
        $byRank = $finalists->sortBy(fn ($c) => [$ranks[$c->id] ?? PHP_INT_MAX, $c->number])->pluck('id')->values()->all();
        $current = $hasOrder ? $finalists->sortBy('announce_order')->pluck('id')->values()->all() : null;

        // With 3 or more finalists there is always an order that is none of these; with fewer, take any.
        $avoid = array_filter([$byNumber, $byRank, $current]);
        $order = $ids->shuffle()->values()->all();
        for ($i = 0; $i < self::ATTEMPTS && $ids->count() >= 3 && in_array($order, $avoid, true); $i++) {
            $order = $ids->shuffle()->values()->all();
        }

        DB::transaction(function () use ($division, $order) {
            Candidate::where('division', $division)->update(['announce_order' => null]);
            foreach ($order as $position => $id) {
                Candidate::whereKey($id)->update(['announce_order' => $position + 1]);
            }
        });
    }
}
