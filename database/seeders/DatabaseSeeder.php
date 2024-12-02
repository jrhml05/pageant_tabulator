<?php

namespace Database\Seeders;

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
        $this->call(JudgeSeeder::class);
        $this->call(StageSeeder::class);
        $this->call(MrCandidateSeeder::class);
        $this->call(MsCandidateSeeder::class);
        $this->call(MrRankingSeeder::class);
        $this->call(MsRankingSeeder::class);
        $this->call(MrFinalRankingSeeder::class);
        $this->call(MsFinalRankingSeeder::class);
        $this->call(MrTalentScoreSeeder::class);
        $this->call(MsTalentScoreSeeder::class);
        $this->call(MrRavewearScoreSeeder::class);
        $this->call(MsRavewearScoreSeeder::class);
        $this->call(MrPrepageantScoreSeeder::class);
        $this->call(MsPrepageantScoreSeeder::class);
    }
}
