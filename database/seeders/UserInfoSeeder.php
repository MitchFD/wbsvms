<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Useremployees;
use App\Models\Userstudents;

class UserInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Useremployees::insert([
            [
                'uEmp_id'       => '201839485',
                'uEmp_job_desc' => 'Student Discipline Director',
                'uEmp_dept'     => 'Student Discipline Office',
                'uEmp_phnum'    => '09266993636',
                'created_at'    => now()
            ]
        ]);
    }
}
