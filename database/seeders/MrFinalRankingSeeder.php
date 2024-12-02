<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Mr_candidate;
use App\Models\Mr_final_rank;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MrFinalRankingSeeder extends Seeder
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

        foreach ($candidates as $candidate) {
            //initialize data to sub scores table
            Mr_final_rank::create([
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