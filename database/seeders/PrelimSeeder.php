<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrelimSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate tables
        DB::table('mr_prelim_scores')->truncate();
        DB::table('mr_natlcost_scores')->truncate();
        DB::table('mr_deptuni_scores')->truncate();
        DB::table('mr_swimwear_scores')->truncate();
        DB::table('mr_formalwear_scores')->truncate();
        DB::table('mr_qna_scores')->truncate();

        DB::table('ms_prelim_scores')->truncate();
        DB::table('ms_natlcost_scores')->truncate();
        DB::table('ms_deptuni_scores')->truncate();
        DB::table('ms_swimwear_scores')->truncate();
        DB::table('ms_formalwear_scores')->truncate();
        DB::table('ms_qna_scores')->truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Call seeders
        $this->call(MrPrelimScoreSeeder::class);
        $this->call(MrNatlCostScoreSeeder::class);
        $this->call(MrDeptUniScoreSeeder::class);
        $this->call(MrSwimWearScoreSeeder::class);
        $this->call(MrFormalWearScoreSeeder::class);
        $this->call(MrQnaScoreSeeder::class);

        $this->call(MsPrelimScoreSeeder::class);
        $this->call(MsNatlCostScoreSeeder::class);
        $this->call(MsDeptUniScoreSeeder::class);
        $this->call(MsSwimWearScoreSeeder::class);
        $this->call(MsFormalWearScoreSeeder::class);
        $this->call(MsQnaScoreSeeder::class);
    }
}
