<?php

namespace App\Providers;

use Dipokhalder\Settings\Facades\Settings;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (! file_exists(base_path('.env')) && file_exists(base_path('.env.example'))) {
            @copy(base_path('.env.example'), base_path('.env'));
        }

        if (blank(config('app.key'))) {
            $cipher = config('app.cipher') ?: 'AES-256-CBC';
            $key = 'base64:'.base64_encode(
                Encrypter::generateKey($cipher)
            );
            config(['app.key' => $key]);

            if (file_exists(base_path('.env')) && is_writable(base_path('.env'))) {
                try {
                    $envContent = file_get_contents(base_path('.env'));
                    if (preg_match('/^APP_KEY=.*$/m', $envContent)) {
                        $envContent = preg_replace('/^APP_KEY=.*$/m', 'APP_KEY='.$key, $envContent);
                    } else {
                        $envContent = 'APP_KEY='.$key."\n".$envContent;
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
        Sanctum::authenticateAccessTokensUsing(function ($accessToken, $isValid) {
            if (! $isValid) {
                return false;
            }

            try {
                $lifetime = (int) Settings::group('site')->get('site_session_lifetime');
                if ($lifetime > 0) {
                    $lastActivity = $accessToken->last_used_at ?? $accessToken->created_at;
                    if ($lastActivity && $lastActivity->lt(now()->subMinutes($lifetime))) {
                        return false;
                    }
                }
            } catch (\Throwable $e) {
                // Ignore fallback during setup or unmigrated state
            }

            return true;
        });
    }
}
