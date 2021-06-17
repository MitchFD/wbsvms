<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\OffensesCategories;

class DefaultOffensesCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OffensesCategories::insert([
            [
                'offCategory' => 'major offenses',
                'created_by'  => '1',
                'created_at'  => now()
            ],
            [
                'offCategory' => 'minor offenses',
                'created_by'  => '1',
                'created_at'  => now()
            ],
            [
                'offCategory' => 'less serious offenses',
                'created_by'  => '1',
                'created_at'  => now()
            ],
        ]);
    }
}
