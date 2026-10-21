<?php

namespace Database\Seeders;

use App\Models\Candidate;
use Illuminate\Database\Seeder;

class CandidateSeeder extends Seeder
{
    public function run(): void
    {
        foreach (array_keys(config('pageant.divisions')) as $division) {
            foreach (range(1, 12) as $number) {
                Candidate::firstOrCreate(['division' => $division, 'number' => $number]);
            }
        }
    }
}
