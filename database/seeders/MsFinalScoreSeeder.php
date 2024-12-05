<?php

namespace Database\Seeders;

use App\Models\Ms_candidate;
use App\Models\Ms_final_score;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MsFinalScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Ms_final_score::truncate();
        
        $candidates = Ms_candidate::where('is_active', '=', 1)->get();

        $judges = User::where('role', 'judge')->whereIn('id', range(2, 4))->get();

        foreach ($candidates as $candidate) {
            //initialize data to sub scores table

            foreach ($judges as $judge) {
                Ms_final_score::create([
                    'judge_id' => $judge->id,
                    'candidate_id' => $candidate->id,
                    'is_lock' => 0
                ]);
            }
        }
    }
}
