<?php

namespace App\Scoring;

use App\Models\Candidate;
use App\Models\OpenSegment;
use App\Models\Score;
use App\Models\ScoreLock;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Wipes scores after a rehearsal, by criterion, segment or round. A reset also deletes the
 * segment's lock-ins, since a lock-in vouches for a full sheet.
 *
 * Each reset leaves a mark per segment. A judge's open sheet still holds the old entries, and
 * the next edit would save all of them again, so the sheet reloads when the mark has changed.
 */
final class ScoreReset
{
    /** @return int score rows deleted */
    public static function criterion(Segment $segment, string $criterion): int
    {
        return DB::transaction(function () use ($segment, $criterion) {
            $deleted = Score::where('segment', $segment->key)->where('criterion', $criterion)->delete();
            ScoreLock::where('segment', $segment->key)->delete();
            self::stamp($segment->key);

            return $deleted;
        });
    }

    /** @return int score rows deleted */
    public static function segment(Segment $segment): int
    {
        return self::segments([$segment->key]);
    }

    /** @return int score rows deleted */
    public static function round(int $round): int
    {
        return self::segments(Segment::inRound($round)->keys()->all());
    }

    /** Every score, lock-in, open segment and finalist mark, as after a rehearsal. */
    public static function everything(): void
    {
        DB::transaction(function () {
            Score::query()->delete();
            ScoreLock::query()->delete();
            OpenSegment::query()->delete();
            Candidate::query()->update(['is_finalist' => false, 'announce_order' => null]);
        });

        Segment::all()->keys()->each(fn (string $key) => self::stamp($key));
    }

    /** Changes with every reset of the segment; null if it was never reset. */
    public static function mark(string $segment): ?string
    {
        return Cache::get("scores.reset.{$segment}");
    }

    /** @param  list<string>  $keys */
    private static function segments(array $keys): int
    {
        return DB::transaction(function () use ($keys) {
            $deleted = Score::whereIn('segment', $keys)->delete();
            ScoreLock::whereIn('segment', $keys)->delete();
            foreach ($keys as $key) {
                self::stamp($key);
            }

            return $deleted;
        });
    }

    private static function stamp(string $segment): void
    {
        Cache::forever("scores.reset.{$segment}", (string) Str::uuid());
    }
}
