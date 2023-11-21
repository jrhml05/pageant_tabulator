<?php

namespace Database\Seeders;

use App\Models\Score;
use App\Models\Stage;
use App\Models\Category;
use App\Models\SubScore;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InitialZeroSubScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //get active stage
        $stage = Stage::where('is_active', 1)->first()->id;

        //get all scores
        $scores = Score::where('stage_id',$stage)->get();

        foreach($scores as $score){
            //initialize data to sub scores table
            SubScore::create([
                'stage_id' => $stage,
                'score_id' => $score->id,
                'judge_id' => $score->judge_id,
                'barangay_id' => $score->barangay_id,
                'candidate_id' => $score->candidate_id,
            ]);
        }

    }
}
