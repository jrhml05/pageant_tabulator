<?php

namespace Database\Seeders;

use App\Models\Mr_candidate;
use App\Models\Mr_natlcost_score;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MrNatlCostScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $candidates = Mr_candidate::all();

        $judges = User::where('role', 'judge')->whereIn('id', range(2, 4))->get();

        foreach ($candidates as $candidate) {
            //initialize data to sub scores table

            foreach ($judges as $judge) {
                Mr_natlcost_score::create([
                    'judge_id' => $judge->id,
                    'candidate_id' => $candidate->id,
                    'is_lock' => 0
                ]);
            }
        }
    }
}
