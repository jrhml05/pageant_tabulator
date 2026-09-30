<?php

namespace Tests\Feature;

use App\Scoring\Segment;
use Tests\TestCase;

class ScoringConfigTest extends TestCase
{
    public function test_each_round_is_worth_100_points(): void
    {
        $this->assertEquals(100, Segment::inRound(1)->sum(fn (Segment $s) => $s->maxPoints()));
        $this->assertEquals(100, Segment::inRound(2)->sum(fn (Segment $s) => $s->maxPoints()));
    }

    public function test_segment_weights_match_the_scoring_rules(): void
    {
        $weights = Segment::all()->map(fn (Segment $s) => $s->maxPoints())->all();

        $this->assertEquals([
            'talent' => 15, 'thematic_wear' => 15, 'swim_wear' => 15, 'formal_wear' => 15,
            'beauty_of_face' => 20, 'wit' => 20, 'final' => 100,
        ], $weights);

        $this->assertSame(['final'], Segment::all()->filter->ranksBySum()->keys()->all());
        $this->assertSame(['talent', 'thematic_wear'], Segment::all()->where('panel', 'prepageant')->keys()->all());
        $this->assertSame(
            ['swim_wear', 'formal_wear', 'beauty_of_face', 'wit', 'final'],
            Segment::all()->where('panel', 'pageant')->keys()->all(),
        );
    }
}
