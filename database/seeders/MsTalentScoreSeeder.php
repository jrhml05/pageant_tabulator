<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ms_candidate;
use App\Models\Ms_talent_score;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InitialZeroMsTalentScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        //get all scores
        $candidates = Ms_candidate::all();

        $judges = User::where('role', 'judge')->get();

        foreach ($candidates as $candidate) {
            //initialize data to sub scores table

            foreach ($judges as $judge) {
                Ms_talent_score::create([
                    'judge_id' => $judge->id,
                    'candidate_id' => $candidate->id,
                    'is_lock' => 0
                ]);
            }
        }
    }
}