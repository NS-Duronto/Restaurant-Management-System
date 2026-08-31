<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Support\Facades\Artisan;
use Dipokhalder\Settings\Facades\Settings;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Settings::group('company')->set([
            'company_name'         => 'সহজ রেস্টুরেন্ট - Sohoj RMS',
            'company_email'        => 'info@sohojrms.com',
            'company_phone'        => '+8801700000000',
            'company_website'      => 'https://sohojrms.com',
            'company_city'         => 'Mirpur 1',
            'company_state'        => 'Dhaka',
            'company_country_code' => 'BGD',
            'company_zip_code'     => '1216',
            'company_address'      => 'House : 25, Road No: 2, Block A, Mirpur-1, Dhaka 1216'
        ]);

        $envService = new EnvEditor();
        $envService->addData([
            'APP_NAME' => "Sohoj RMS - Restaurant Management System"
        ]);
        Artisan::call('optimize:clear');
    }
}
