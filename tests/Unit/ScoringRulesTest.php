<?php

namespace Tests\Unit;

use App\Scoring\Points;
use App\Scoring\Tabulator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ScoringRulesTest extends TestCase
{
    public function test_ties_share_a_rank_and_the_next_rank_is_skipped(): void
    {
        $ranks = Tabulator::rank([10 => 9000, 11 => 8500, 12 => 8500, 13 => 8000, 14 => null]);

        $this->assertSame(['rank' => 1, 'tied' => false], $ranks[10]);
        $this->assertSame(['rank' => 2, 'tied' => true], $ranks[11]);
        $this->assertSame(['rank' => 2, 'tied' => true], $ranks[12]);
        $this->assertSame(['rank' => 4, 'tied' => false], $ranks[13]);
        $this->assertSame(['rank' => null, 'tied' => false], $ranks[14]);
    }

    #[DataProvider('entries')]
    public function test_parses_judge_entries(string $entry, float $max, float|false|null $expected): void
    {
        $this->assertSame($expected, Points::parse($entry, $max));
    }

    public static function entries(): array
    {
        return [
            'blank' => ['', 10, null],
            'spaces' => ['  ', 10, null],
            'whole' => ['8', 10, 8.0],
            'decimal' => ['7.25', 7.5, 7.25],
            'trailing dot while typing' => ['7.', 7.5, 7.0],
            'leading dot' => ['.5', 5, 0.5],
            'at max' => ['7.5', 7.5, 7.5],
            'over max' => ['7.51', 7.5, false],
            'three decimals' => ['7.125', 10, false],
            'negative' => ['-1', 10, false],
            'text' => ['abc', 10, false],
            'comma decimal' => ['7,5', 10, false],
        ];
    }

    public function test_hundredths_are_exact(): void
    {
        $this->assertSame(750, Points::hundredths('7.50'));
        $this->assertSame(1, Points::hundredths(0.01));
        $this->assertSame('86.42', Points::format(8642));
        $this->assertSame('7.5', Points::plain(7.5));
        $this->assertSame('10', Points::plain('10.00'));
    }
}
