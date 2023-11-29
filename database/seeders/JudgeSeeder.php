<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class JudgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $i = 1;

        while ($i < 6) {
            $exists = User::where('email', 'judge' . $i . '@mail.com')->count();
            if (!$exists)
                User::insert([
                    'name' => 'Judge-' . $i,
                    'email' => 'judge' . $i . '@mail.com',
                    'role' => 'judge',
                    'password' => Hash::make('mrmsuepjudge' . $i),
                ]);
            $i++;
        }
    }
}
