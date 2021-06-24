<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_roles_tbl')->insert([
            [
                'uRole_status'   => 'active',
                'uRole_type'     => 'employee',
                'uRole'          => 'Administrator',
                'uRole_access'   => json_encode(["profile", "dashboard", "users management", "violation entry", "violation records", "offenses", "sanctions", "disciplinary policies"]),
                'assUsers_count' => 1,
                'created_by'     => 1,
                'created_at'     => now()
            ]
        ]);
    }
}
