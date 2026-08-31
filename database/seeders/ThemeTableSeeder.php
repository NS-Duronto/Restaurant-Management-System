<?php

namespace Database\Seeders;

use Dipokhalder\Settings\Facades\Settings;
use Illuminate\Database\Seeder;

class ThemeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Settings::group('theme')->set([
            'theme_logo' => '',
            'theme_favicon_logo' => '',
            'theme_footer_logo' => '',
            'theme_primary_color' => '#f97316',
        ]);
    }
}
