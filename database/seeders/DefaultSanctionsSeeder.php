<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultSanctionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('created_sanctions_tbl')->insert([
            [
                'crSanct_details' => 'Written Reprimand',
                'respo_user_id'   => 1,
                'created_at'      => now()
            ],
            [
                'crSanct_details' => 'Conference with the Student Discipline Officer',
                'respo_user_id'   => 1,
                'created_at'      => now()
            ],
            [
                'crSanct_details' => 'Conference with the Discipline Committee',
                'respo_user_id'   => 1,
                'created_at'      => now()
            ],
            [
                'crSanct_details' => 'Non-Readmission',
                'respo_user_id'   => 1,
                'created_at'      => now()
            ],
            [
                'crSanct_details' => 'Dismissal',
                'respo_user_id'   => 1,
                'created_at'      => now()
            ]
        ]);
    }
}
