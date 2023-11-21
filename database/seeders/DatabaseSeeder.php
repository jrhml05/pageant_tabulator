<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminUserSeeder::class);
        $this->call(BarangaySeeder::class);
        $this->call(StageSeeder::class);
        $this->call(JudgeSeeder::class);
        $this->call(CandidateSeeder::class);
        // $this->call(InitialCategorySeeder::class);
        $this->call(InitialZeroScoreSeeder::class);
        // $this->call(SubCategoryForTalentSeeder::class);
        $this->call(InitialZeroSubScoreSeeder::class);
    }
}
