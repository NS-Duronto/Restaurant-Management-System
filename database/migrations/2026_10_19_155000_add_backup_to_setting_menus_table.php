<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\SettingMenu;
use App\Enums\Status;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        SettingMenu::firstOrCreate(
            ['url' => 'backup'],
            [
                'name'       => 'Backup',
                'language'   => 'backup',
                'icon'       => 'fa-solid fa-database',
                'priority'   => 908,
                'status'     => Status::ACTIVE,
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        SettingMenu::where('url', 'backup')->delete();
    }
};
