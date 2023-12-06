<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Mr_candidate;
use App\Models\Mr_final_score;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MrFinalScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //get all judge
        $judges = User::where('role', 'judge')->get();

        //get all barangays
        $candidates = Mr_candidate::where('is_active', 1)->get();

        foreach ($judges as $judge) {
            foreach ($candidates as $candidate) {
                //initialize zero score to scores table based on stage, judges and candidates
                Mr_final_score::create([
                    'judge_id' => $judge->id,
                    'candidate_id' => $candidate->id,
                    'is_lock' => 0,
                ]);
            }
        }
    }
}
