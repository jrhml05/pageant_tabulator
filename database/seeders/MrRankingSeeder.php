<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Mr_candidate;
use App\Models\Mr_ranking;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MrRankingSeeder extends Seeder
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
                Mr_ranking::create([
                    'judge_id' => $judge->id,
                    'candidate_id' => $candidate->id,
                    'rave_wear' => 0,
                    'talent' => 0,
                    'prepageant' => 0,
                    'national_costume' => 0,
                    'dept_uniform' => 0,
                    'swim_wear' => 0,
                    'formal_wear' => 0,
                    'qna' => 0,
                    'pageant' => 0,
                    'to_top_5' => 0,
                    'final' => 0
                ]);
            }
        }
    }
}