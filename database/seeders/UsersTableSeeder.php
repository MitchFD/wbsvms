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
                'email'             => 'svms_admin@gmail.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('admin123'),
                'user_role'         => 'Administrator',
                'user_status'       => 'active',
                'user_role_status'  => 'active',
                'user_type'         => 'employee',
                'user_sdca_id'      => '201839485',
                // 'user_image'        => 'employee_user_image.jpg',
                'user_image'        => null,
                'user_lname'        => 'Bravo',
                'user_fname'        => 'Johny',
                'user_gender'       => 'male',
                'registered_by'     => '1',
                'created_at'        => now()
            ]
        ]);
    }
}
