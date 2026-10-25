<?php

use Dipokhalder\Settings\Facades\Settings;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            if (Settings::group('site')->get('site_session_lifetime') === null) {
                Settings::group('site')->set([
                    'site_session_lifetime' => 120,
                ]);
            }
        } catch (Throwable $e) {
            // Ignore if settings table does not exist yet during fresh install
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
