<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Barangay;
use App\Models\Ranking;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RankingSeeder extends Seeder
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
        $barangays = Barangay::all();

        foreach ($judges as $judge) {
            foreach ($barangays as $barangay) {
                //initialize zero score to scores table based on stage, judges and barangays
                Ranking::create([
                    'judge_id' => $judge->id,
                    'barangay_id' => $barangay->id,
                ]);
            }
        }
    }
}
