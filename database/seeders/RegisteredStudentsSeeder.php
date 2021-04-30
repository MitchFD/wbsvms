<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisteredStudentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('students_tbl')->insert([
            [
                'Student_Number' => '20150348',
                'First_Name'     => 'Mitch Frankein',
                'Middle_Name'    => 'Ovalo',
                'Last_Name'      => 'Desierto',
                'Gender'         => 'Male',
                'Age'            => 23,
                'Email'          => 'mfodesierto2@gmail.com',
                'School_Name'    => 'SBCS',
                'Course'         => 'BSIT',
                'YearLevel'      => '4',
                // 'Student_Image'  => 'default_student_img.jpg',
                'Status'         => 1,
                'created_at'     => now()
            ],
            [
                'Student_Number' => '20186475',
                'First_Name'     => 'Frankein',
                'Middle_Name'    => 'Drake',
                'Last_Name'      => 'Jeremiah',
                'Gender'         => 'Male',
                'Age'            => 22,
                'Email'          => 'mfodesierto2@gmail.com',
                'School_Name'    => 'SBCS',
                'Course'         => 'BSIT',
                'YearLevel'      => '3',
                // 'Student_Image'  => 'default_student_img.jpg',
                'Status'         => 1,
                'created_at'     => now()
            ],
            [
                'Student_Number' => '20198923',
                'First_Name'     => 'Jaysef',
                'Middle_Name'    => 'Dork',
                'Last_Name'      => 'Dreamon',
                'Gender'         => 'Male',
                'Age'            => 25,
                'Email'          => 'mfodesierto2@gmail.com',
                'School_Name'    => 'SBCS',
                'Course'         => 'BSIT',
                'YearLevel'      => '2',
                // 'Student_Image'  => 'default_student_img.jpg',
                'Status'         => 1,
                'created_at'     => now()
            ],
            [
                'Student_Number' => '20169857',
                'First_Name'     => 'John',
                'Middle_Name'    => 'Maribel',
                'Last_Name'      => 'Doe',
                'Gender'         => 'Male',
                'Age'            => 21,
                'Email'          => 'mfodesierto2@gmail.com',
                'School_Name'    => 'SBCS',
                'Course'         => 'BSBA',
                'YearLevel'      => '2',
                // 'Student_Image'  => 'default_student_img.jpg',
                'Status'         => 1,
                'created_at'     => now()
            ],
            [
                'Student_Number' => '20160978',
                'First_Name'     => 'Jenny',
                'Middle_Name'    => 'Caiden',
                'Last_Name'      => 'Dualan',
                'Gender'         => 'Female',
                'Age'            => 20,
                'Email'          => 'mfodesierto2@gmail.com',
                'School_Name'    => 'SBCS',
                'Course'         => 'BSA',
                'YearLevel'      => '3',
                // 'Student_Image'  => 'default_student_img.jpg',
                'Status'         => 1,
                'created_at'     => now()
            ],
            [
                'Student_Number' => '20149869',
                'First_Name'     => 'Toph',
                'Middle_Name'    => 'Tucker',
                'Last_Name'      => 'Beifong',
                'Gender'         => 'Female',
                'Age'            => 25,
                'Email'          => 'mfodesierto2@gmail.com',
                'School_Name'    => 'SBCS',
                'Course'         => 'BMA',
                'YearLevel'      => '4',
                // 'Student_Image'  => 'default_student_img.jpg',
                'Status'         => 1,
                'created_at'     => now()
            ],
            [
                'Student_Number' => '20197485',
                'First_Name'     => 'Ashley',
                'Middle_Name'    => 'Gianna',
                'Last_Name'      => 'Isabela',
                'Gender'         => 'Female',
                'Age'            => 19,
                'Email'          => 'mfodesierto2@gmail.com',
                'School_Name'    => 'SBCS',
                'Course'         => 'BSA',
                'YearLevel'      => '1',
                // 'Student_Image'  => 'default_student_img.jpg',
                'Status'         => 1,
                'created_at'     => now()
            ],
            [
                'Student_Number' => '20176464',
                'First_Name'     => 'John Mark',
                'Middle_Name'    => 'Gonzales',
                'Last_Name'      => 'Urbiztondo',
                'Gender'         => 'Male',
                'Age'            => 20,
                'Email'          => 'mfodesierto2@gmail.com',
                'School_Name'    => 'SIHTM',
                'Course'         => 'BSHM',
                'YearLevel'      => '3',
                // 'Student_Image'  => 'default_student_img.jpg',
                'Status'         => 1,
                'created_at'     => now()
            ],
            [
                'Student_Number' => '20215243',
                'First_Name'     => 'Aeron',
                'Middle_Name'    => 'Ver',
                'Last_Name'      => 'Guinto',
                'Gender'         => 'Male',
                'Age'            => 23,
                'Email'          => 'mfodesierto2@gmail.com',
                'School_Name'    => 'SBCS',
                'Course'         => 'BSTM',
                'YearLevel'      => '4',
                // 'Student_Image'  => 'default_student_img.jpg',
                'Status'         => 1,
                'created_at'     => now()
            ],
            [
                'Student_Number' => '20200997',
                'First_Name'     => 'Joana',
                'Middle_Name'    => 'Adrian',
                'Last_Name'      => 'Gonzales',
                'Gender'         => 'Female',
                'Age'            => 24,
                'Email'          => 'mfodesierto2@gmail.com',
                'School_Name'    => 'SASE',
                'Course'         => 'BS Psychology',
                'YearLevel'      => '1',
                // 'Student_Image'  => 'default_student_img.jpg',
                'Status'         => 1,
                'created_at'     => now()
            ],
            [
                'Student_Number' => '20189674',
                'First_Name'     => 'Kevin',
                'Middle_Name'    => 'Dukinea',
                'Last_Name'      => 'Kylan',
                'Gender'         => 'Male',
                'Age'            => 21,
                'Email'          => 'mfodesierto2@gmail.com',
                'School_Name'    => 'SASE',
                'Course'         => 'BS Education',
                'YearLevel'      => '2',
                // 'Student_Image'  => 'default_student_img.jpg',
                'Status'         => 1,
                'created_at'     => now()
            ],
            [
                'Student_Number' => '20169586',
                'First_Name'     => 'Remuel',
                'Middle_Name'    => 'Zeinab',
                'Last_Name'      => 'Kailan',
                'Gender'         => 'Male',
                'Age'            => 28,
                'Email'          => 'mfodesierto2@gmail.com',
                'School_Name'    => 'SASE',
                'Course'         => 'BA Communication',
                'YearLevel'      => '4',
                // 'Student_Image'  => 'default_student_img.jpg',
                'Status'         => 1,
                'created_at'     => now()
            ],
            [
                'Student_Number' => '20156536',
                'First_Name'     => 'Reny',
                'Middle_Name'    => 'Jay',
                'Last_Name'      => 'Joshua',
                'Gender'         => 'Male',
                'Age'            => 24,
                'Email'          => 'mfodesierto2@gmail.com',
                'School_Name'    => 'SASE',
                'Course'         => 'BA Communication',
                'YearLevel'      => '2',
                // 'Student_Image'  => 'default_student_img.jpg',
                'Status'         => 1,
                'created_at'     => now()
            ],
            [
                'Student_Number' => '20150896',
                'First_Name'     => 'Seseria',
                'Middle_Name'    => 'Cristy',
                'Last_Name'      => 'Herera',
                'Gender'         => 'Female',
                'Age'            => 23,
                'Email'          => 'mfodesierto2@gmail.com',
                'School_Name'    => 'SHSP',
                'Course'         => 'BS Pharmacy',
                'YearLevel'      => '1',
                // 'Student_Image'  => 'default_student_img.jpg',
                'Status'         => 1,
                'created_at'     => now()
            ],
            [
                'Student_Number' => '20210546',
                'First_Name'     => 'Adrian',
                'Middle_Name'    => 'Geray',
                'Last_Name'      => 'Garcia',
                'Gender'         => 'Male',
                'Age'            => 24,
                'Email'          => 'mfodesierto2@gmail.com',
                'School_Name'    => 'SHSP',
                'Course'         => 'BS Radiologic Technology',
                'YearLevel'      => '1',
                // 'Student_Image'  => 'default_student_img.jpg',
                'Status'         => 1,
                'created_at'     => now()
            ],
            [
                'Student_Number' => '20166253',
                'First_Name'     => 'Aldrin',
                'Middle_Name'    => 'John',
                'Last_Name'      => 'Verdy',
                'Gender'         => 'Male',
                'Age'            => 25,
                'Email'          => 'mfodesierto2@gmail.com',
                'School_Name'    => 'SHSP',
                'Course'         => 'BS Physical Therapy',
                'YearLevel'      => '4',
                // 'Student_Image'  => 'default_student_img.jpg',
                'Status'         => 1,
                'created_at'     => now()
            ],
            [
                'Student_Number' => '20167485',
                'First_Name'     => 'Stephen',
                'Middle_Name'    => 'John',
                'Last_Name'      => 'Vergara',
                'Gender'         => 'Male',
                'Age'            => 25,
                'Email'          => 'mfodesierto2@gmail.com',
                'School_Name'    => 'SHSP',
                'Course'         => 'BS Medical Technology',
                'YearLevel'      => '4',
                // 'Student_Image'  => 'default_student_img.jpg',
                'Status'         => 1,
                'created_at'     => now()
            ],
            [
                'Student_Number' => '20171212',
                'First_Name'     => 'Verlen',
                'Middle_Name'    => 'Mae',
                'Last_Name'      => 'Abrosol',
                'Gender'         => 'Female',
                'Age'            => 28,
                'Email'          => 'mfodesierto2@gmail.com',
                'School_Name'    => 'SHSP',
                'Course'         => 'BS Nursing',
                'YearLevel'      => '4',
                // 'Student_Image'  => 'default_student_img.jpg',
                'Status'         => 1,
                'created_at'     => now()
            ]
            ,
            [
                'Student_Number' => '20187677',
                'First_Name'     => 'Altea',
                'Middle_Name'    => 'Bagab',
                'Last_Name'      => 'Grace',
                'Gender'         => 'Female',
                'Age'            => 23,
                'Email'          => 'mfodesierto2@gmail.com',
                'School_Name'    => 'SHSP',
                'Course'         => 'BS Nursing',
                'YearLevel'      => '2',
                // 'Student_Image'  => 'default_student_img.jpg',
                'Status'         => 1,
                'created_at'     => now()
            ]
        ]);
    }
}
