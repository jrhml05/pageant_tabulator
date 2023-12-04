<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ms_candidate;
use App\Models\Ms_ranking;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MsRankingSeeder extends Seeder
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
                Ms_ranking::create([
                    'judge_id' => $judge->id,
                    'candidate_id' => $candidate->id,
                    'prod_num' => 0,
                    'sports_wear' => 0,
                    'talent' => 0,
                    'prepageant' => 0,
                    'casual_wear' => 0,
                    'formal_wear' => 0,
                    'prelim' => 0,
                    'to_final' => 0,
                    'final' => 0
                ]);
            }
        }
    }
}
