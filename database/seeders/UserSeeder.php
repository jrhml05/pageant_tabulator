<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Rehearsal accounts. Each password is the username: change them on the Judges page before the event.
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            ['admin1', 'Admin 1', 'admin', null],
            ['admin2', 'Admin 2', 'admin', null],
        ];

        foreach (range(1, 3) as $n) {
            $accounts[] = ["prejudge{$n}", "Pre-pageant judge {$n}", 'judge', 'prepageant'];
        }

        foreach (range(1, 5) as $n) {
            $accounts[] = ["judge{$n}", "Judge {$n}", 'judge', 'pageant'];
        }

        foreach ($accounts as [$username, $name, $role, $panel]) {
            User::updateOrCreate(
                ['username' => $username],
                ['name' => $name, 'role' => $role, 'panel' => $panel, 'password' => Hash::make($username)],
            );
        }
    }
}
