<?php

namespace Database\Seeders;

use Dipokhalder\EnvEditor\EnvEditor;
use Dipokhalder\Settings\Facades\Settings;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class LicenseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Settings::group('license')->set([
            'license_key' => env('VITE_API_KEY') ?: 'ACTIVE',
        ]);
    }
}
