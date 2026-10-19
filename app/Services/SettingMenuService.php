<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use App\Models\SettingMenu;
use App\Enums\Status;
use App\Libraries\QueryExceptionLibrary;

class SettingMenuService
{
    /**
     * @throws Exception
     */
    public function list()
    {
        try {
            $menus = SettingMenu::where('status', Status::ACTIVE)->orderBy('priority', 'desc')->get();
            if (!$menus->contains('url', 'backup')) {
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
                $menus = SettingMenu::where('status', Status::ACTIVE)->orderBy('priority', 'desc')->get();
            }
            return $menus->unique('url')->values();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }
}
