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
                'crSanct_details' => '3 hours duty in a department',
                'respo_user_id'   => 1,
                'created_at'      => now()
            ],
            [
                'crSanct_details' => '6 hours duty in a department',
                'respo_user_id'   => 1,
                'created_at'      => now()
            ],
            [
                'crSanct_details' => '9 hours duty or Community service',
                'respo_user_id'   => 1,
                'created_at'      => now()
            ],
            [
                'crSanct_details' => 'Community Service',
                'respo_user_id'   => 1,
                'created_at'      => now()
            ],
            [
                'crSanct_details' => '1-day suspension',
                'respo_user_id'   => 1,
                'created_at'      => now()
            ],
            [
                'crSanct_details' => '2-day suspension',
                'respo_user_id'   => 1,
                'created_at'      => now()
            ],
            [
                'crSanct_details' => '7-day suspension',
                'respo_user_id'   => 1,
                'created_at'      => now()
            ],
            [
                'crSanct_details' => '10-day suspension and no re-admission the following semester',
                'respo_user_id'   => 1,
                'created_at'      => now()
            ]
        ]);
    }
}
