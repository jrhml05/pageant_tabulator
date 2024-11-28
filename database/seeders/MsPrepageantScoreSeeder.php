<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ms_candidate;
use App\Models\User;
use App\Models\Ms_prepageant_score;

class MsPrepageantScoreSeeder extends Seeder
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

       $judges = User::where('role', 'judge')->whereIn('id', range(2, 4))->get();

       foreach ($candidates as $candidate) {
           //initialize data to sub scores table

           foreach ($judges as $judge) {
               Ms_prepageant_score::create([
                   'judge_id' => $judge->id,
                   'candidate_id' => $candidate->id,
               ]);
           }
       }
    }
}
