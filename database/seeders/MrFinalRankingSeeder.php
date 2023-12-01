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
