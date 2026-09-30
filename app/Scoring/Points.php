<?php

namespace App\Scoring;

final class Points
{
    /**
     * A judge's entry as points: null when blank, false when it isn't a score from 0 to $max
     * with at most two decimals. "7." counts as 7 so typing "7.5" never flashes an error.
     */
    public static function parse(mixed $value, float $max): float|false|null
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        if (! preg_match('/^(\d{1,3}(\.\d{0,2})?|\.\d{1,2})$/', $value)) {
            return false;
        }

        $points = (float) $value;

        return $points <= $max ? $points : false;
    }

    /** Totals and averages are kept in hundredths so sums and tie checks are exact. */
    public static function hundredths(float|string $points): int
    {
        return (int) round(((float) $points) * 100);
    }

    public static function format(?int $hundredths): string
    {
        return $hundredths === null ? '' : number_format($hundredths / 100, 2);
    }

    /** 7.5 → "7.5", 10.00 → "10": for maximums and for refilling a judge's inputs. */
    public static function plain(float|string $points): string
    {
        return rtrim(rtrim(number_format((float) $points, 2, '.', ''), '0'), '.');
    }
}
