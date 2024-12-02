<?php

namespace Database\Seeders;

use App\Models\Mr_candidate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MrCandidateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $i = 1;

        while ($i < 13) {
            $exists = Mr_candidate::where('name', $i)->count();
            if (!$exists)
                Mr_candidate::insert([
                    'name' => $i,
                    'department' => 'C',
                ]);
            $i++;
        }
    }
}
