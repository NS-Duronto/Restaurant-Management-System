<?php

namespace Database\Seeders;

use App\Libraries\AppLibrary;
use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Menu::truncate();

        $menus = [
            [
                'name'       => 'Dashboard',
                'language'   => 'dashboard',
                'url'        => 'dashboard',
                'icon'       => 'lab lab-dashboard',
                'priority'   => 10,
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name'       => 'POS',
                'language'   => 'pos',
                'url'        => 'pos',
                'icon'       => 'lab lab-pos',
                'priority'   => 20,
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name'       => 'Order Management',
                'language'   => 'order_management',
                'url'        => '#',
                'icon'       => 'lab lab-pos-orders',
                'priority'   => 30,
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'children'   => [
                    [
                        'name'       => 'POS Orders',
                        'language'   => 'pos_orders',
                        'url'        => 'pos-orders',
                        'icon'       => 'lab lab-pos-orders',
                        'priority'   => 1,
                        'status'     => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'name'       => 'Table Orders',
                        'language'   => 'table_orders',
                        'url'        => 'table-orders',
                        'icon'       => 'lab lab-reserve-line',
                        'priority'   => 2,
                        'status'     => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'name'       => 'K.D.S',
                        'language'   => 'k_d_s',
                        'url'        => 'kitchen-display-system',
                        'icon'       => 'lab lab-kds',
                        'priority'   => 3,
                        'status'     => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'name'       => 'O.S.S',
                        'language'   => 'o_s_s',
                        'url'        => 'order-status-screen',
                        'icon'       => 'lab lab-cds',
                        'priority'   => 4,
                        'status'     => 0,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                ]
            ],
            [
                'name'       => 'Food Menu & Category',
                'language'   => 'items',
                'url'        => 'items',
                'icon'       => 'lab lab-items',
                'priority'   => 40,
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name'       => 'Table Management',
                'language'   => 'dining_tables',
                'url'        => 'dining-tables',
                'icon'       => 'lab lab-dining-table',
                'priority'   => 50,
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name'       => 'Expense Management',
                'language'   => 'expense_management',
                'url'        => '#',
                'icon'       => 'fa-solid fa-wallet',
                'priority'   => 60,
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'children'   => [
                    [
                        'name'       => 'Expenses',
                        'language'   => 'expenses',
                        'url'        => 'expenses',
                        'icon'       => 'fa-solid fa-receipt',
                        'priority'   => 1,
                        'status'     => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'name'       => 'Expense Categories',
                        'language'   => 'expense_categories',
                        'url'        => 'expense-categories',
                        'icon'       => 'fa-solid fa-tags',
                        'priority'   => 2,
                        'status'     => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                ]
            ],
            [
                'name'       => 'Suppliers & Purchases',
                'language'   => 'suppliers_and_purchases',
                'url'        => '#',
                'icon'       => 'fa-solid fa-truck-field',
                'priority'   => 70,
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'children'   => [
                    [
                        'name'       => 'Suppliers',
                        'language'   => 'suppliers',
                        'url'        => 'suppliers',
                        'icon'       => 'fa-solid fa-users-gear',
                        'priority'   => 1,
                        'status'     => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'name'       => 'Purchases',
                        'language'   => 'purchases',
                        'url'        => 'purchases',
                        'icon'       => 'fa-solid fa-cart-flatbed',
                        'priority'   => 2,
                        'status'     => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                ]
            ],
            [
                'name'       => 'Inventory & Kitchen',
                'language'   => 'inventory_and_kitchen',
                'url'        => '#',
                'icon'       => 'fa-solid fa-boxes-stacked',
                'priority'   => 80,
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'children'   => [
                    [
                        'name'       => 'Kitchen Goods (Stock)',
                        'language'   => 'kitchen_goods',
                        'url'        => 'kitchen-goods',
                        'icon'       => 'fa-solid fa-layer-group',
                        'priority'   => 1,
                        'status'     => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'name'       => 'Send To Kitchen',
                        'language'   => 'send_to_kitchen',
                        'url'        => 'send-to-kitchen',
                        'icon'       => 'fa-solid fa-dolly',
                        'priority'   => 2,
                        'status'     => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                ]
            ],
            [
                'name'       => 'Sales & Profit Reports',
                'language'   => 'sales_and_profit_reports',
                'url'        => '#',
                'icon'       => 'fa-solid fa-chart-line',
                'priority'   => 90,
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'children'   => [
                    [
                        'name'       => 'Sales Report',
                        'language'   => 'sales_report',
                        'url'        => 'sales-report',
                        'icon'       => 'lab lab-sales-report',
                        'priority'   => 1,
                        'status'     => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'name'       => 'Items Report',
                        'language'   => 'items_report',
                        'url'        => 'items-report',
                        'icon'       => 'lab lab-items-report',
                        'priority'   => 2,
                        'status'     => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'name'       => 'Credit Balance Report',
                        'language'   => 'credit_balance_report',
                        'url'        => 'credit-balance-report',
                        'icon'       => 'lab lab-credit-balance-report',
                        'priority'   => 3,
                        'status'     => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'name'       => 'Profit & Loss Report',
                        'language'   => 'profit_loss_report',
                        'url'        => 'profit-loss-report',
                        'icon'       => 'fa-solid fa-chart-pie',
                        'priority'   => 4,
                        'status'     => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                ]
            ],
            [
                'name'       => 'Users & Staff',
                'language'   => 'users',
                'url'        => '#',
                'icon'       => 'lab lab-administrators',
                'priority'   => 100,
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'children'   => [
                    [
                        'name'       => 'Administrators',
                        'language'   => 'administrators',
                        'url'        => 'administrators',
                        'icon'       => 'lab lab-administrators',
                        'priority'   => 1,
                        'status'     => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'name'       => 'Customers',
                        'language'   => 'customers',
                        'url'        => 'customers',
                        'icon'       => 'lab lab-customers',
                        'priority'   => 2,
                        'status'     => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'name'       => 'Employees',
                        'language'   => 'employees',
                        'url'        => 'employees',
                        'icon'       => 'lab lab-employee',
                        'priority'   => 3,
                        'status'     => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'name'       => 'Waiters',
                        'language'   => 'waiters',
                        'url'        => 'waiters',
                        'icon'       => 'lab lab-waiter',
                        'priority'   => 4,
                        'status'     => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                    [
                        'name'       => 'Chef',
                        'language'   => 'chefs',
                        'url'        => 'chefs',
                        'icon'       => 'lab lab-chef',
                        'priority'   => 5,
                        'status'     => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                ]
            ],
            [
                'name'       => 'Offers & Promo',
                'language'   => 'promo',
                'url'        => '#',
                'icon'       => 'lab lab-offers',
                'priority'   => 110,
                'status'     => 0,
                'created_at' => now(),
                'updated_at' => now(),
                'children'   => [
                    [
                        'name'       => 'Offers',
                        'language'   => 'offers',
                        'url'        => 'offers',
                        'icon'       => 'lab lab-offers',
                        'priority'   => 1,
                        'status'     => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                ]
            ],
            [
                'name'       => 'Accounts',
                'language'   => 'accounts',
                'url'        => '#',
                'icon'       => 'lab lab-transactions',
                'priority'   => 120,
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'children'   => [
                    [
                        'name'       => 'Transactions',
                        'language'   => 'transactions',
                        'url'        => 'transactions',
                        'icon'       => 'lab lab-transactions',
                        'priority'   => 1,
                        'status'     => 1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                ]
            ],
            [
                'name'       => 'Settings',
                'language'   => 'settings',
                'url'        => 'settings',
                'icon'       => 'lab lab-settings',
                'priority'   => 130,
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        Menu::insert(AppLibrary::associativeToNumericArrayBuilder($menus));
    }
}
