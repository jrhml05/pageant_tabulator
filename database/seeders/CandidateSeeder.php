<?php

namespace Database\Seeders;

use App\Models\Barangay;
use App\Models\Candidate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CandidateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('candidates')->truncate();
        $barangays = Barangay::all();

        foreach($barangays as $b) {
            Candidate::insert([
                'first_name' => 'Candidate '.$b->id,
                'last_name' => '#'.$b->id,
                'barangay_id' => $b->id,
                'age' => 18,
            ]);
        }

    }
}
