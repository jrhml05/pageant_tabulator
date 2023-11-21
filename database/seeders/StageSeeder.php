<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('stages')->truncate();
        DB::insert("INSERT INTO `stages` (`stage_name`, `is_active`) VALUES
			('Pre-Pageant',1),
			('Preliminaries',0),
			('Semifinal',0),
			('Final',0)");
    }
}
