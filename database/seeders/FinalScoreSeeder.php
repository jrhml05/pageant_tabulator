<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Stage;
use App\Models\FinalScore;
use App\Models\Barangay;

class FinalScoreSeeder extends Seeder
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

        //get all judge
        $judges = User::where('role', 'judge')->get();

        //get all barangays
        $barangays = Barangay::where('is_active', 1)->get();

        foreach ($judges as $judge) {
            foreach ($barangays as $barangay) {
                //initialize zero score to scores table based on stage, judges and barangays
                FinalScore::create([
                    'stage_id' => $stage,
                    'judge_id' => $judge->id,
                    'barangay_id' => $barangay->id,
                    'candidate_id' => $barangay->id,
                ]);
            }
        }
    }
}
