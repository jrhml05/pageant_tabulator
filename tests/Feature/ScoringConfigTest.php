<?php

namespace Tests\Feature;

use App\Scoring\Segment;
use Tests\TestCase;

class ScoringConfigTest extends TestCase
{
    public function test_each_round_is_worth_100_points(): void
    {
        $this->assertEquals(100, Segment::inRound(1)->sum(fn (Segment $s) => $s->weight()));
        $this->assertEquals(100, Segment::inRound(2)->sum(fn (Segment $s) => $s->weight()));
    }

    public function test_talent_is_judged_out_of_100_and_counts_15_percent(): void
    {
        $talent = Segment::find('talent');

        $this->assertSame(['skill' => 50.0, 'performance' => 50.0], array_map(fn ($c) => $c['max'], $talent->criteria));
        $this->assertSame(['Skills', 'Performance'], array_column($talent->criteria, 'label'));
        $this->assertEquals(100, $talent->maxPoints());
        $this->assertEquals(15, $talent->weight());
        $this->assertTrue($talent->isScaled());
        $this->assertSame(15, $talent->scale());
        $this->assertSame(100, Segment::find('thematic_wear')->scale());
    }

    public function test_scaled_segments_stay_exact(): void
    {
        foreach (Segment::all()->reject(fn (Segment $s) => $s->isRanked()) as $segment) {
            $this->assertSame(0, ($segment->weight() * 10000) % ($segment->maxPoints() * 100), $segment->key);
        }
    }

    public function test_casual_wear_is_a_separate_award_for_every_candidate(): void
    {
        $casual = Segment::find('casual_wear');

        $this->assertSame(['casual_wear'], Segment::awards()->keys()->all());
        $this->assertTrue($casual->isAward());
        $this->assertNull($casual->round);
        $this->assertFalse($casual->forFinalistsOnly());
        $this->assertSame('Special award', $casual->roundLabel());
        $this->assertTrue($casual->isRanked());
        $this->assertSame([Segment::RANK], array_keys($casual->criteria));
        $this->assertSame([], Segment::all()->except('casual_wear')->filter(fn (Segment $s) => $s->isRanked())->keys()->all());
        $this->assertNotContains('casual_wear', Segment::inRound(1)->keys()->merge(Segment::inRound(2)->keys()));
    }

    public function test_segment_weights_match_the_scoring_rules(): void
    {
        // Casual wear is ranked, so it has no points or weight.
        $weights = Segment::all()->reject(fn (Segment $s) => $s->isRanked())->map(fn (Segment $s) => $s->weight())->all();

        $this->assertEquals([
            'talent' => 15, 'thematic_wear' => 15, 'swim_wear' => 15, 'formal_wear' => 15,
            'beauty_of_face' => 20, 'wit' => 20, 'final' => 100,
        ], $weights);

        // Round 1 ties go to the highest-weighted segments: Beauty of face, then Q&A.
        $this->assertSame(['beauty_of_face', 'wit'], config('pageant.tie_break'));
        $this->assertSame(['talent', 'thematic_wear'], Segment::all()->where('panel', 'prepageant')->keys()->all());
        $this->assertSame(
            ['swim_wear', 'formal_wear', 'beauty_of_face', 'wit', 'final', 'casual_wear'],
            Segment::all()->where('panel', 'pageant')->keys()->all(),
        );
    }
}
