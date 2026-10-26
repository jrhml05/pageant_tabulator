<?php

namespace Database\Seeders;

use App\Models\Candidate;
use Illuminate\Database\Seeder;

class CandidateSeeder extends Seeder
{
    /** Each school sends one Mr. and one Ms. candidate, numbered alike in both divisions. */
    private const SCHOOLS = [
        1 => 'Pateros Technological College',
        2 => 'Dalubhasaan ng Lungsod ng San Pablo',
        3 => 'Gordon College',
        4 => 'Pamantasan ng Lungsod ng Muntinlupa',
        5 => 'Taguig City University',
        6 => 'Kolehiyo ng Lungsod ng Lipa',
        7 => 'Calaca City Global College',
        8 => 'University of Eastern Pangasinan',
        9 => 'City College of Angeles',
    ];

    public function run(): void
    {
        foreach (array_keys(config('pageant.divisions')) as $division) {
            foreach (self::SCHOOLS as $number => $school) {
                Candidate::updateOrCreate(['division' => $division, 'number' => $number], ['school' => $school]);
            }
        }
    }
}
