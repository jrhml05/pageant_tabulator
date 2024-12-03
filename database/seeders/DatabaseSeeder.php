<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Disable foreign key checks for truncation
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate tables
        DB::table('users')->truncate();
        DB::table('stages')->truncate();
        DB::table('mr_candidates')->truncate();
        DB::table('ms_candidates')->truncate();
        DB::table('mr_rankings')->truncate();
        DB::table('ms_rankings')->truncate();
        DB::table('mr_final_ranks')->truncate();
        DB::table('ms_final_ranks')->truncate();
        DB::table('mr_talent_scores')->truncate();
        DB::table('ms_talent_scores')->truncate();
        DB::table('mr_ravewear_scores')->truncate();
        DB::table('ms_ravewear_scores')->truncate();
        DB::table('mr_prepageant_scores')->truncate();
        DB::table('ms_prepageant_scores')->truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Call seeders
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
