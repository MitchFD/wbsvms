<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\CreatedOffenses;

class DefaultOffensesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sq = "'";
        CreatedOffenses::insert([
            [
                'crOffense_category' => 'minor offenses',
                'crOffense_type'     => 'default',
                'crOffense_details'  => 'Violation of Dress Code',
                'respo_user_id'      => '1',
                'created_at'         => now()
            ],
            [
                'crOffense_category' => 'minor offenses',
                'crOffense_type'     => 'default',
                'crOffense_details'  => 'Not wearing the prescribed uniform',
                'respo_user_id'      => '1',
                'created_at'         => now()
            ],
            [
                'crOffense_category' => 'minor offenses',
                'crOffense_type'     => 'default',
                'crOffense_details'  => 'Not wearing ID',
                'respo_user_id'      => '1',
                'created_at'         => now()
            ],
            [
                'crOffense_category' => 'minor offenses',
                'crOffense_type'     => 'default',
                'crOffense_details'  => 'Littering',
                'respo_user_id'      => '1',
                'created_at'         => now()
            ],
            [
                'crOffense_category' => 'minor offenses',
                'crOffense_type'     => 'default',
                'crOffense_details'  => 'Using cellular phones and other E-gadgets while having a class',
                'respo_user_id'      => '1',
                'created_at'         => now()
            ],
            [
                'crOffense_category' => 'minor offenses',
                'crOffense_type'     => 'default',
                'crOffense_details'  => 'Body Piercing',
                'respo_user_id'      => '1',
                'created_at'         => now()
            ],
            [
                'crOffense_category' => 'minor offenses',
                'crOffense_type'     => 'default',
                'crOffense_details'  => 'Indecent Public Display of Affection',
                'respo_user_id'      => '1',
                'created_at'         => now()
            ],
            [
                'crOffense_category' => 'less serious offenses',
                'crOffense_type'     => 'default',
                'crOffense_details'  => 'Wearing somebody else'.$sq.'s ID',
                'respo_user_id'      => '1',
                'created_at'         => now()
            ],
            [
                'crOffense_category' => 'less serious offenses',
                'crOffense_type'     => 'default',
                'crOffense_details'  => '"Wearing Tampered/Unauthorized ID',
                'respo_user_id'      => '1',
                'created_at'         => now()
            ],
            [
                'crOffense_category' => 'less serious offenses',
                'crOffense_type'     => 'default',
                'crOffense_details'  => 'Lending His/Her ID',
                'respo_user_id'      => '1',
                'created_at'         => now()
            ],
            [
                'crOffense_category' => 'less serious offenses',
                'crOffense_type'     => 'default',
                'crOffense_details'  => 'Smoking or Possession of Smoking Paraphernalia',
                'respo_user_id'      => '1',
                'created_at'         => now()
            ]
        ]);
    }
}
