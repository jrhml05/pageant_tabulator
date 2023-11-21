<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Score;
use App\Models\Stage;
use App\Models\Barangay;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InitialZeroScoreSeeder extends Seeder
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
        $barangays = Barangay::all();

        foreach ($judges as $judge) {
            foreach ($barangays as $barangay) {
                //initialize zero score to scores table based on stage, judges and barangays
                Score::create([
                    'stage_id' => $stage,
                    'judge_id' => $judge->id,
                    'barangay_id' => $barangay->id,
                    'candidate_id' => $barangay->id,
                ]);
            }
        }
    }
}
