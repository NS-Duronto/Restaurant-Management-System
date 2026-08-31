<?php

namespace Database\Seeders;


use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Dipokhalder\Settings\Facades\Settings;

class MailTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $envService = new EnvEditor();

        Settings::group('mail')->set([
            'mail_mailer'     => 'smtp',
            'mail_host'       => $envService->getValue('DEMO') ? 'mail.sohojrms.com' : '',
            'mail_port'       => $envService->getValue('DEMO') ? '465' : '0',
            'mail_username'   => $envService->getValue('DEMO') ? 'info@sohojrms.com' : '',
            'mail_password'   => $envService->getValue('DEMO') ? 'password123' : '',
            'mail_encryption' => $envService->getValue('DEMO') ? 'ssl' : '',
            'mail_from_name'  => $envService->getValue('DEMO') ? 'Sohoj RMS - Restaurant Management System' : '',
            'mail_from_email' => $envService->getValue('DEMO') ? 'info@sohojrms.com' : ''
        ]);

        $envService->addData([
            'MAIL_MAILER'       => 'smtp',
            'MAIL_HOST'         => $envService->getValue('DEMO') ? 'mail.sohojrms.com' : '',
            'MAIL_PORT'         => $envService->getValue('DEMO') ? '465' : '0',
            'MAIL_USERNAME'     => $envService->getValue('DEMO') ? 'info@sohojrms.com' : '',
            'MAIL_PASSWORD'     => $envService->getValue('DEMO') ? 'password123' : '',
            'MAIL_ENCRYPTION'   => $envService->getValue('DEMO') ? 'ssl' : '',
            'MAIL_FROM_NAME'    => $envService->getValue('DEMO') ? 'Sohoj RMS - Restaurant Management System' : '',
            'MAIL_FROM_ADDRESS' => $envService->getValue('DEMO') ? 'info@sohojrms.com' : ''
        ]);
        Artisan::call('optimize:clear');
    }
}
