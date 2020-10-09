<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_employees_tbl')->insert([
            [
                'uEmp_id'       => '201839485',
                'uEmp_job_desc' => 'Student Discipline Director',
                'uEmp_dept'     => 'Student Discipline Office',
                'uEmp_phnum'    => '09266993636',
                'created_at'    => now()
            ],
            [
                'uEmp_id'       => '201983746',
                'uEmp_job_desc' => 'Chief Security',
                'uEmp_dept'     => 'Security Department',
                'uEmp_phnum'    => '09266993636',
                'created_at'    => now()
            ]
        ]);

        DB::table('user_students_tbl')->insert([
            [
                'uStud_num'     => '20159846',
                'uStud_school'  => 'SBCS',
                'uStud_program' => 'BSIT',
                'uStud_yearlvl' => 'FOURTH YEAR',
                'uStud_section' => '4A',
                'uStud_phnum'   => '09266993636',
                'created_at'    => now()
            ]
        ]);
    }
}
