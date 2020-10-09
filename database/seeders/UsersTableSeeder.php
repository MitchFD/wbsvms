<?php
namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'id'                => '201839485',
                'email'             => 'mfodesierto2@gmail.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('admin123'),
                'user_role'         => 'administrator',
                'user_status'       => 'active',
                'user_role_status'  => 'active',
                'user_type'         => 'employee',
                'user_image'        => 'employee.jpg',
                'user_lname'        => 'Desierto',
                'user_fname'        => 'Mitch Frankein',
                'registered_by'     => '201839485',
                'created_at'        => now()
            ],
            [
                'id'                => '201983746',
                'email'             => 'security@gmail.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('security123'),
                'user_role'         => 'security guard',
                'user_status'       => 'active',
                'user_role_status'  => 'active',
                'user_type'         => 'employee',
                'user_image'        => 'employee.jpg',
                'user_lname'        => 'Guard',
                'user_fname'        => 'Kuya',
                'registered_by'     => '201839485',
                'created_at'        => now()
                ]
        ]);
    }
}
