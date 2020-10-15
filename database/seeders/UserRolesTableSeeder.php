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
                'uRole_status' => 'active',
                'uRole_type'   => 'employee',
                'uRole'        => 'administrator',
                'uRole_access' => json_encode(["profile", "dashboard", "user management", "violation entry", "violation records", "student handbook"]),
                'created_by'   => '201839485',
                'created_at'   => now()
            ],
            [
                'uRole_status' => 'active',
                'uRole_type'   => 'employee',
                'uRole'        => 'security guard',
                'uRole_access' => json_encode(["profile", "violation entry", "student handbook"]),
                'created_by'   => '201839485',
                'created_at'   => now()
            ],
            [
                'uRole_status' => 'active',
                'uRole_type'   => 'student',
                'uRole'        => 'student',
                'uRole_access' => json_encode(["profile", "violation entry", "violation records", "student handbook"]),
                'created_by'   => '201839485',
                'created_at'   => now()
            ]
        ]);
    }
}
