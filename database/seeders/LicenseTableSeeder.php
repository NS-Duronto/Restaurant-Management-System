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
        $envService = new EnvEditor;
        $apiKey = $envService->getValue('VITE_API_KEY') ?: 'b6d68vy2-m7g5-20r0-5275-h103w73453q120';

        Settings::group('license')->set([
            'license_key' => $apiKey,
        ]);

        if (blank($envService->getValue('VITE_API_KEY'))) {
            $envService->addData(['VITE_API_KEY' => $apiKey]);
            Artisan::call('optimize:clear');
        }

        if ($envService->getValue('DEMO')) {
            Settings::group('license')->set([
                'license_key' => 'b6d68vy2-m7g5-20r0-5275-h103w73453q120',
            ]);
            $envService->addData(['VITE_API_KEY' => 'b6d68vy2-m7g5-20r0-5275-h103w73453q120']);
            Artisan::call('optimize:clear');
        }
    }
}
