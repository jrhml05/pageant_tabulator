<?php

namespace Database\Seeders;

use App\Models\Ms_candidate;
use App\Models\Ms_prelim_score;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MsPrelimScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $candidates = Ms_candidate::all();

       $judges = User::where('role', 'judge')->whereIn('id', range(2, 4))->get();

       foreach ($candidates as $candidate) {
           //initialize data to sub scores table

           foreach ($judges as $judge) {
               Ms_prelim_score::create([
                   'judge_id' => $judge->id,
                   'candidate_id' => $candidate->id,
               ]);
           }
       }
    }
}
