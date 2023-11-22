<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Mr_candidate;
use App\Models\Mr_talent_score;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MrTalentScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        //get all scores
        $candidates = Mr_candidate::all();

        $judges = User::where('role', 'judge')->get();

        foreach ($candidates as $candidate) {
            //initialize data to sub scores table

            foreach ($judges as $judge) {
                Mr_talent_score::create([
                    'judge_id' => $judge->id,
                    'candidate_id' => $candidate->id,
                    'is_lock' => 0
                ]);
            }
        }
    }
}
