<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (!file_exists(base_path('.env')) && file_exists(base_path('.env.example'))) {
            @copy(base_path('.env.example'), base_path('.env'));
        }

        if (blank(config('app.key'))) {
            $cipher = config('app.cipher') ?: 'AES-256-CBC';
            $key = 'base64:' . base64_encode(
                \Illuminate\Encryption\Encrypter::generateKey($cipher)
            );
            config(['app.key' => $key]);

            if (file_exists(base_path('.env')) && is_writable(base_path('.env'))) {
                try {
                    $envContent = file_get_contents(base_path('.env'));
                    if (preg_match('/^APP_KEY=.*$/m', $envContent)) {
                        $envContent = preg_replace('/^APP_KEY=.*$/m', 'APP_KEY=' . $key, $envContent);
                    } else {
                        $envContent = "APP_KEY=" . $key . "\n" . $envContent;
                    }
                    file_put_contents(base_path('.env'), $envContent);
                } catch (\Throwable $e) {
                }
            }
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
