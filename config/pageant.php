<?php

/*
 * Scoring rules for Mr. & Ms. LCUAA 2026.
 *
 * Criteria are scored in points, and each segment's points add up to its weight in the round,
 * so each round totals 100. A segment with a `weight` is scored out of its own points instead
 * (Talent: 100) and counts toward the round at that weight (15%), so a talent average of 90
 * adds 13.5 to the Round 1 total. Its weight × 10,000 must divide by its points, so the scaled
 * figures stay exact.
 *
 * Apart from a ranked segment (below), judges don't rank candidates. Every segment adds up the
 * judges' totals for a candidate, divides by the number of judges (an average, to 2 decimals),
 * and ranks the averages, highest first. Round 1 scales each segment's average to its weight
 * and adds them up out of 100; Round 2 is the `final` segment's average.
 *
 * A segment with no `round` is a separate award (Casual wear): placed on its own, and never
 * counted in Round 1 or Round 2.
 *
 * A `ranked` segment (Casual wear) isn't scored in points: each judge ranks the candidates of a
 * division 1, 2, 3... with each rank used once. The judges' ranks are added up and the lowest
 * total wins. It has no criteria; each rank is saved as one `rank` entry.
 */

return [

    'event' => 'Mr. & Ms. LCUAA 2026',

    // Signs every printed sheet, after the judges and the tabulator.
    'board' => 'LCUAA National Board',

    'divisions' => [
        'ms' => 'Ms. LCUAA',
        'mr' => 'Mr. LCUAA',
    ],

    'panels' => [
        'prepageant' => 'Pre-pageant',
        'pageant' => 'Pageant night',
    ],

    'rounds' => [
        1 => 'Round 1',
        2 => 'Round 2',
    ],

    // Round 2 is scored by the top candidates of Round 1, per division.
    'finalists' => 5,

    // A Round 1 tie on the total goes to the higher average in these segments, in order.
    // Candidates still level after the last one stay tied for the board to decide.
    'tie_break' => ['beauty_of_face', 'wit'],

    // Placement titles for Round 2, by rank. `{division}` becomes "Mr. LCUAA" or "Ms. LCUAA".
    'placements' => [
        1 => '{division} 2026',
        2 => '1st runner-up',
        3 => '2nd runner-up',
        4 => '3rd runner-up',
        5 => '4th runner-up',
    ],

    // In the order the tabulator opens them. Criteria are [label, maximum points].
    'segments' => [
        'talent' => [
            'label' => 'Talent',
            'round' => 1,
            'panel' => 'prepageant',
            'weight' => 15,
            'criteria' => [
                'skill' => ['Skills', 50],
                'performance' => ['Performance', 50],
            ],
        ],
        'thematic_wear' => [
            'label' => 'Thematic wear',
            'round' => 1,
            'panel' => 'prepageant',
            'criteria' => [
                'stage_presence' => ['Stage presence, poise & bearing', 10],
                'relevance' => ['Relevance of the costume', 5],
            ],
        ],
        'swim_wear' => [
            'label' => 'Swim wear',
            'round' => 1,
            'panel' => 'pageant',
            'criteria' => [
                'beauty' => ['Beauty & complexion', 10],
                'figure' => ['Body figure / masculinity', 5],
            ],
        ],
        'formal_wear' => [
            'label' => 'Formal / evening wear',
            'round' => 1,
            'panel' => 'pageant',
            'criteria' => [
                'elegance' => ['Beauty & elegance', 10],
                'stage_presence' => ['Stage presence', 5],
            ],
        ],
        'beauty_of_face' => [
            'label' => 'Beauty of face, poise, bearing & personality',
            'short' => 'Beauty of face',
            'round' => 1,
            'panel' => 'pageant',
            'criteria' => [
                'face' => ['Beauty of face', 10],
                'poise' => ['Poise, bearing & personality', 10],
            ],
        ],
        'wit' => [
            'label' => 'Wit & verbal ability (Q&A)',
            'short' => 'Q&A',
            'round' => 1,
            'panel' => 'pageant',
            'criteria' => [
                'substance' => ['Substance of answer', 12],
                'delivery' => ['Manner of delivery', 8],
            ],
        ],
        'final' => [
            'label' => 'Round 2 (top 5)',
            'short' => 'Round 2',
            'round' => 2,
            'panel' => 'pageant',
            'criteria' => [
                'impression' => ['Beauty of face, poise, grace & overall impression', 50],
                'intelligence' => ['Intelligence', 50],
            ],
        ],
        'casual_wear' => [
            'label' => 'Casual wear',
            'panel' => 'pageant',
            'ranked' => true,
        ],
    ],

];
