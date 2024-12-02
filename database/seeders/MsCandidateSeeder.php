<?php

namespace Database\Seeders;

use App\Models\Ms_candidate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MsCandidateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $i = 1;

        while ($i < 15) {
            $exists = Ms_candidate::where('name', $i)->count();
            if (!$exists)
                Ms_candidate::insert([
                    'name' => $i,
                    'department' => 'C',
                ]);
            $i++;
        }
    }
}
