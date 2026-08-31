<?php

namespace Tests\Feature;

use App\Enums\Ask;
use App\Enums\Status;
use App\Models\SettingMenu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class SettingMenuTest extends TestCase
{
    use RefreshDatabase;

    protected function makeUser(string $email): User
    {
        return User::create([
            'name'              => 'Test User',
            'email'             => $email,
            'phone'             => '1234567890',
            'username'          => str()->slug($email),
            'email_verified_at' => now(),
            'password'          => bcrypt('123456'),
            'branch_id'         => 0,
            'status'            => Status::ACTIVE,
            'country_code'      => '+880',
            'is_guest'          => Ask::NO,
        ]);
    }

    protected function authenticatedAdmin(): User
    {
        $user = $this->makeUser('admin@example.test');

        $permission = Permission::create([
            'name'       => 'settings',
            'guard_name' => 'sanctum',
        ]);

        $user->givePermissionTo($permission);

        return $user;
    }

    public function test_it_returns_only_active_setting_menus_ordered_by_priority(): void
    {
        $user = $this->authenticatedAdmin();

        SettingMenu::insert([
            [
                'name'       => 'Company',
                'language'   => 'company',
                'url'        => 'company',
                'icon'       => 'lab lab-company',
                'priority'   => 1000,
                'status'     => Status::ACTIVE,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'License',
                'language'   => 'license',
                'url'        => 'license',
                'icon'       => 'lab lab-license',
                'priority'   => 910,
                'status'     => Status::ACTIVE,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Hidden',
                'language'   => 'hidden',
                'url'        => 'hidden',
                'icon'       => 'lab lab-hidden',
                'priority'   => 5000,
                'status'     => Status::INACTIVE,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->withHeaders(['x-api-key' => env('VITE_API_KEY')])
            ->getJson('/api/admin/setting/setting-menu');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        $this->assertSame('company', $response->json('data.0.language'));
        $this->assertSame('license', $response->json('data.1.language'));
    }

    public function test_it_requires_the_settings_permission(): void
    {
        $user = $this->makeUser('nopermission@example.test');

        $response = $this->actingAs($user, 'sanctum')
            ->withHeaders(['x-api-key' => env('VITE_API_KEY')])
            ->getJson('/api/admin/setting/setting-menu');

        $response->assertStatus(403);
    }
}
