<?php

/*
 * Scoring rules for Mr. & Ms. LCUAA 2026.
 *
 * Criteria are scored in points, and each segment's points add up to its weight in the round,
 * so each round totals 100.
 *
 * Round 1 is placed by ranks, per panel: each judge's totals across the panel's Round 1 segments
 * are added up and ranked, the ranks are averaged over the panel's judges (3 pre-pageant,
 * 5 pageant night), and the average is weighted by the panel's points (pre-pageant 30, so 30%;
 * pageant night 70, so 70%). The lowest weighted total wins. Each segment also ranks on its own,
 * for that segment's winner.
 *
 * A segment's `ranking` is "rank_sum" (default: each judge ranks the candidates by total, the
 * ranks are added up, lowest sum wins) or "average" (mean of the judges' totals, highest wins).
 */

return [

    'event' => 'Mr. & Ms. LCUAA 2026',

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

    // A Round 1 tie on the total goes to the lower average rank in these segments, in order.
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
            'criteria' => [
                'skill' => ['Skill', 7.5],
                'performance' => ['Performance', 7.5],
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
    ],

];
