<?php

namespace Database\Seeders;

use App\Models\Mr_candidate;
use App\Models\Mr_final_score;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MrFinalScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Mr_final_score::truncate();
        
        $candidates = Mr_candidate::where('is_active', '=', 1)->get();

        $judges = User::where('role', 'judge')->whereIn('id', range(2, 4))->get();

        foreach ($candidates as $candidate) {
            //initialize data to sub scores table

            foreach ($judges as $judge) {
                Mr_final_score::create([
                    'judge_id' => $judge->id,
                    'candidate_id' => $candidate->id,
                    'is_lock' => 0
                ]);
            }
        }
    }
}
