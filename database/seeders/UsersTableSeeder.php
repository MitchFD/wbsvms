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
                'email'             => 'mfodesierto2@gmail.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('admin123'),
                'user_role'         => 'administrator',
                'user_status'       => 'active',
                'user_role_status'  => 'active',
                'user_type'         => 'employee',
                'user_sdca_id'      => '201839485',
                'user_image'        => 'employee_user_image.jpg',
                'user_lname'        => 'Bravo',
                'user_fname'        => 'Johny',
                'user_gender'       => 'male',
                'registered_by'     => '1',
                'created_at'        => now()
            ],
            [
                'email'             => 'security@gmail.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('security123'),
                'user_role'         => 'security guard',
                'user_status'       => 'active',
                'user_role_status'  => 'active',
                'user_type'         => 'employee',
                'user_sdca_id'      => '201983746',
                'user_image'        => 'employee_user_image.jpg',
                'user_lname'        => 'Wick',
                'user_fname'        => 'John',
                'user_gender'       => 'male',
                'registered_by'     => '1',
                'created_at'        => now()
            ],
            [
                'email'             => 'student@gmail.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('student123'),
                'user_role'         => 'student',
                'user_status'       => 'active',
                'user_role_status'  => 'active',
                'user_type'         => 'student',
                'user_sdca_id'      => '20159846',
                'user_image'        => 'student_user_image.jpg',
                'user_lname'        => 'Doe',
                'user_fname'        => 'John',
                'user_gender'       => 'female',
                'registered_by'     => '1',
                'created_at'        => now()
            ]
        ]);
    }
}
