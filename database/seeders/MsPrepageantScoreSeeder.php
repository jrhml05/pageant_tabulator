<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ms_candidate;
use App\Models\Ms_prepageant_score;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MsPrepageantScoreSeeder extends Seeder
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
        $candidates = Ms_candidate::all();

        foreach ($judges as $judge) {
            foreach ($candidates as $candidate) {
                //initialize zero score to scores table based on stage, judges and barangays
                Ms_prepageant_score::create([
                    'judge_id' => $judge->id,
                    'candidate_id' => $candidate->id,
                    'is_lock' => 0,
                ]);
            }
        }
    }
}
