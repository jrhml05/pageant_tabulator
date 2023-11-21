<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BarangaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('barangays')->truncate();
        DB::insert("INSERT INTO `barangays` (`name`, `is_active`) VALUES
			('Balangobong',1),
			('Bued',1),
			('Bugayong',1),
			('Camangaan',1),
			('Canarvacanan',1),
			('Capas',1),
			('Cili',1),
			('Dumayat',1),
			('Linmansangan',1),
			('Mangcasuy',1),
			('Moreno',1),
			('Pasileng Norte',1),
			('Pasileng Sur',1),
			('Poblacion',1),
			('Santiago',1),
			('San Felipe Central',1),
			('San Felipe Sur',1),
			('San Pablo',1),
			('Sta. Catalina',1),
			('Sta. Maria Norte',1),
			('Sto. Niño',1),
			('Sumabnit',1),
			('Tabuyoc',1),
			('Vacante',1);
		");
    }
}
