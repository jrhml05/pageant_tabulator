<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


use App\Models\Barangay;
use App\Models\FinalRanking;

class FinalRankingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //get all barangays
        $barangays = Barangay::all();
            foreach ($barangays as $barangay) {
                //initialize zero score to scores table based on stage, judges and barangays
                FinalRanking::create([
                    'barangay_id' => $barangay->id,
                ]);
            }    //
    }
}
