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
                'crOffense_category' => 'major offenses',
                'crOffense_type'     => 'default',
                'crOffense_details'  => 'Bullying',
                'respo_user_id'      => '1',
                'created_at'         => now()
            ],
            [
                'crOffense_category' => 'major offenses',
                'crOffense_type'     => 'default',
                'crOffense_details'  => 'Falsification of official documents.',
                'respo_user_id'      => '1',
                'created_at'         => now()
            ],
            [
                'crOffense_category' => 'major offenses',
                'crOffense_type'     => 'default',
                'crOffense_details'  => 'Gross misconduct',
                'respo_user_id'      => '1',
                'created_at'         => now()
            ],
            [
                'crOffense_category' => 'major offenses',
                'crOffense_type'     => 'default',
                'crOffense_details'  => 'Lying to school authority',
                'respo_user_id'      => '1',
                'created_at'         => now()
            ],
            // [
            //     'crOffense_category' => 'major offenses',
            //     'crOffense_type'     => 'default',
            //     'crOffense_details'  => 'Offensive behavior',
            //     'respo_user_id'      => '1',
            //     'created_at'         => now()
            // ],
            // [
            //     'crOffense_category' => 'major offenses',
            //     'crOffense_type'     => 'default',
            //     'crOffense_details'  => 'Offensive language',
            //     'respo_user_id'      => '1',
            //     'created_at'         => now()
            // ],
            [
                'crOffense_category' => 'major offenses',
                'crOffense_type'     => 'default',
                'crOffense_details'  => 'Threatening behavior',
                'respo_user_id'      => '1',
                'created_at'         => now()
            ],
            [
                'crOffense_category' => 'major offenses',
                'crOffense_type'     => 'default',
                'crOffense_details'  => 'Defiance to authority',
                'respo_user_id'      => '1',
                'created_at'         => now()
            ],
            [
                'crOffense_category' => 'minor offenses',
                'crOffense_type'     => 'default',
                'crOffense_details'  => 'Violation of Dress Code',
                'respo_user_id'      => '1',
                'created_at'         => now()
            ],
            // [
            //     'crOffense_category' => 'minor offenses',
            //     'crOffense_type'     => 'default',
            //     'crOffense_details'  => 'Disrespect to Authority',
            //     'respo_user_id'      => '1',
            //     'created_at'         => now()
            // ],
            // [
            //     'crOffense_category' => 'minor offenses',
            //     'crOffense_type'     => 'default',
            //     'crOffense_details'  => 'Indecent Public Display of Affection (PDA)',
            //     'respo_user_id'      => '1',
            //     'created_at'         => now()
            // ],
            // [
            //     'crOffense_category' => 'minor offenses',
            //     'crOffense_type'     => 'default',
            //     'crOffense_details'  => 'Pranking/ Trolling during online class',
            //     'respo_user_id'      => '1',
            //     'created_at'         => now()
            // ],
            [
                'crOffense_category' => 'minor offenses',
                'crOffense_type'     => 'default',
                'crOffense_details'  => 'Unauthorized sharing of official online classes links',
                'respo_user_id'      => '1',
                'created_at'         => now()
            ],
            [
                'crOffense_category' => 'minor offenses',
                'crOffense_type'     => 'default',
                'crOffense_details'  => 'Unauthorized sharing of learning materials',
                'respo_user_id'      => '1',
                'created_at'         => now()
            ],
            [
                'crOffense_category' => 'minor offenses',
                'crOffense_type'     => 'default',
                'crOffense_details'  => 'Online scheming',
                'respo_user_id'      => '1',
                'created_at'         => now()
            ],
            [
                'crOffense_category' => 'minor offenses',
                'crOffense_type'     => 'default',
                'crOffense_details'  => 'Defiance to authority',
                'respo_user_id'      => '1',
                'created_at'         => now()
            ],
            // [
            //     'crOffense_category' => 'minor offenses',
            //     'crOffense_type'     => 'default',
            //     'crOffense_details'  => 'Cheating',
            //     'respo_user_id'      => '1',
            //     'created_at'         => now()
            // ],
            // [
            //     'crOffense_category' => 'minor offenses',
            //     'crOffense_type'     => 'default',
            //     'crOffense_details'  => 'Plagiarism',
            //     'respo_user_id'      => '1',
            //     'created_at'         => now()
            // ],
            [
                'crOffense_category' => 'less serious offenses',
                'crOffense_type'     => 'default',
                'crOffense_details'  => 'Defaming or tarnishing the name/reputation of another student',
                'respo_user_id'      => '1',
                'created_at'         => now()
            ],
            // [
            //     'crOffense_category' => 'less serious offenses',
            //     'crOffense_type'     => 'default',
            //     'crOffense_details'  => 'Bad manners.',
            //     'respo_user_id'      => '1',
            //     'created_at'         => now()
            // ],
            // [
            //     'crOffense_category' => 'less serious offenses',
            //     'crOffense_type'     => 'default',
            //     'crOffense_details'  => 'Taking pictures without consent of the subject',
            //     'respo_user_id'      => '1',
            //     'created_at'         => now()
            // ],
            // [
            //     'crOffense_category' => 'less serious offenses',
            //     'crOffense_type'     => 'default',
            //     'crOffense_details'  => 'Unauthorized solicitation of donations in cash or in kind',
            //     'respo_user_id'      => '1',
            //     'created_at'         => now()
            // ],
            // [
            //     'crOffense_category' => 'less serious offenses',
            //     'crOffense_type'     => 'default',
            //     'crOffense_details'  => 'Unauthorized alteration or erasure of official announcements',
            //     'respo_user_id'      => '1',
            //     'created_at'         => now()
            // ],
            // [
            //     'crOffense_category' => 'less serious offenses',
            //     'crOffense_type'     => 'default',
            //     'crOffense_details'  => 'Scandalous acts either verbal or non-verbal whether in writing or thru electronic',
            //     'respo_user_id'      => '1',
            //     'created_at'         => now()
            // ],
            [
                'crOffense_category' => 'less serious offenses',
                'crOffense_type'     => 'default',
                'crOffense_details'  => 'Malicious/ Scandalous posting thru social networking sites',
                'respo_user_id'      => '1',
                'created_at'         => now()
            ],
            [
                'crOffense_category' => 'less serious offenses',
                'crOffense_type'     => 'default',
                'crOffense_details'  => 'Unauthorized access to somebody’s account',
                'respo_user_id'      => '1',
                'created_at'         => now()
            ],
            [
                'crOffense_category' => 'less serious offenses',
                'crOffense_type'     => 'default',
                'crOffense_details'  => 'Intentionally entering an online class without permission',
                'respo_user_id'      => '1',
                'created_at'         => now()
            ],
            [
                'crOffense_category' => 'less serious offenses',
                'crOffense_type'     => 'default',
                'crOffense_details'  => 'Using the school’s online platform for personal gain',
                'respo_user_id'      => '1',
                'created_at'         => now()
            ]
        ]);
    }
}
