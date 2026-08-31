<?php

namespace Database\Seeders;

use App\Enums\Role as EnumRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::find(EnumRole::ADMIN);
        $adminRole?->givePermissionTo(Permission::all());

        $branchManager = Role::find(EnumRole::BRANCH_MANAGER);
        if ($branchManager) {
            $branchManagerPermissions = [
                'dashboard',
                'dining-tables',
                'pos',
                'pos-orders',
                'online-orders',
                'table-orders',
                'kitchen-display-system',
                'order-status-screen',
                'push-notifications',
                'messages',
                'delivery-boys',
                'customers',
                'employees',
                'waiters',
                'chefs',
                'transactions',
                'sales-report',
                'items-report',
                'credit-balance-report',
                'units',
                'kitchen-goods',
                'suppliers',
                'purchases',
                'send-to-kitchen',
                'expenses',
                'profit-loss-report',
            ];
            $branchManagerPerms = Permission::whereIn('name', $branchManagerPermissions)->get();
            $branchManager->givePermissionTo($branchManagerPerms);
        }

        $posOperatorManager = Role::find(EnumRole::POS_OPERATOR);
        if ($posOperatorManager) {
            $posOperatorPermissions = [
                'dashboard',
                'pos',
                'pos-orders',
                'dining-tables',
                'table-orders',
                'expenses',
            ];
            $posOperatorPerms = Permission::whereIn('name', $posOperatorPermissions)->get();
            $posOperatorManager->givePermissionTo($posOperatorPerms);
        }

        $chef = Role::find(EnumRole::CHEF);
        if ($chef) {
            $chefPermissions = [
                'dashboard',
                'kitchen-display-system',
                'order-status-screen',
                'send-to-kitchen',
            ];
            $chefPerms = Permission::whereIn('name', $chefPermissions)->get();
            $chef->givePermissionTo($chefPerms);
        }
    }
}
