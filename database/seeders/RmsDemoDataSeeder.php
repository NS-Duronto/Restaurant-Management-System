<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\KitchenGoods;
use App\Models\KitchenGoodsCategory;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\SendToKitchen;
use App\Models\SendToKitchenItem;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RmsDemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();
        $adminId = $admin ? $admin->id : 1;

        // 1. Units
        $unitsData = [
            ['name' => 'Kilogram', 'code' => 'kg', 'status' => Status::ACTIVE],
            ['name' => 'Liter', 'code' => 'ltr', 'status' => Status::ACTIVE],
            ['name' => 'Gram', 'code' => 'gm', 'status' => Status::ACTIVE],
            ['name' => 'Piece', 'code' => 'pcs', 'status' => Status::ACTIVE],
            ['name' => 'Bag / বস্তা', 'code' => 'bag', 'status' => Status::ACTIVE],
            ['name' => 'Box / কার্টুন', 'code' => 'box', 'status' => Status::ACTIVE],
        ];

        $units = [];
        foreach ($unitsData as $u) {
            $units[$u['code']] = Unit::firstOrCreate(['code' => $u['code']], $u);
        }

        // 2. Kitchen Goods Categories
        $categoriesData = [
            'চাল ও শস্য' => 'রাইস, পোলাও ও বিরিয়ানির চাল',
            'তেল ও ঘি' => 'সয়াবিন তেল, সরিষার তেল ও খাঁটি গাওয়া ঘি',
            'মাংস ও ডিম' => 'মুরগি, গরু, খাসির মাংস ও ডিম',
            'শাকসবজি ও সালাদ' => 'পেঁয়াজ, আলু, টমেটো, শসা ও কাঁচামরিচ',
            'মশলা ও উপাদান' => 'এলাচ, দারুচিনি, জিরা, আদা ও রসুন',
            'ডেইরি ও দুগ্ধজাত' => 'গরুর দুধ, পনির, মাখন ও টক দই',
        ];

        $categories = [];
        foreach ($categoriesData as $name => $desc) {
            $categories[$name] = KitchenGoodsCategory::firstOrCreate(
                ['name' => $name],
                ['slug' => Str::slug($name), 'description' => $desc, 'status' => Status::ACTIVE]
            );
        }

        // 3. Suppliers
        $suppliersData = [
            [
                'name' => 'মেসার্স হক ট্রেডার্স',
                'company_name' => 'হক রাইস অ্যান্ড অয়েল মিলস',
                'phone' => '01711223344',
                'email' => 'haquetraders@gmail.com',
                'address' => 'বাবুবাজার চালের আড়ত, ঢাকা',
                'status' => Status::ACTIVE,
            ],
            [
                'name' => 'গ্রিন ভ্যালি পোল্ট্রি ফার্ম',
                'company_name' => 'গ্রিন ভ্যালি এগ্রো ইন্ডাস্ট্রিজ',
                'phone' => '01822334455',
                'email' => 'greenvalley.agro@yahoo.com',
                'address' => 'গাজীপুর সদর, ঢাকা',
                'status' => Status::ACTIVE,
            ],
            [
                'name' => 'ফ্রেশ অ্যাগ্রো ভেজিটেবল',
                'company_name' => 'ফ্রেশ ফ্রুটস অ্যান্ড ভেজিটেবল সাপ্লাই',
                'phone' => '01933445566',
                'email' => 'freshagro.bd@gmail.com',
                'address' => 'কাওরান বাজার পাইকারি মার্কেট, ঢাকা',
                'status' => Status::ACTIVE,
            ],
            [
                'name' => 'সুরমা ডেইরি মিল্ক',
                'company_name' => 'সুরমা অর্গানিক ডেইরি ফার্ম',
                'phone' => '01755667788',
                'email' => 'surmadairy@gmail.com',
                'address' => 'সাভার, ঢাকা',
                'status' => Status::ACTIVE,
            ],
        ];

        $suppliers = [];
        foreach ($suppliersData as $s) {
            $suppliers[$s['name']] = Supplier::firstOrCreate(['name' => $s['name']], $s);
        }

        // 4. Kitchen Goods Items
        $goodsData = [
            [
                'name' => 'বাসমতী চাল (কাচ্চি প্রিমিয়াম)',
                'kitchen_goods_category_id' => $categories['চাল ও শস্য']->id,
                'unit_id' => $units['kg']->id,
                'cost_per_unit' => 280,
                'current_stock' => 150,
                'status' => Status::ACTIVE,
            ],
            [
                'name' => 'চিনিগুঁড়া পোলাও চাল',
                'kitchen_goods_category_id' => $categories['চাল ও শস্য']->id,
                'unit_id' => $units['kg']->id,
                'cost_per_unit' => 140,
                'current_stock' => 120,
                'status' => Status::ACTIVE,
            ],
            [
                'name' => 'সয়াবিন তেল (রূপচাঁদা ড্রাম)',
                'kitchen_goods_category_id' => $categories['তেল ও ঘি']->id,
                'unit_id' => $units['ltr']->id,
                'cost_per_unit' => 170,
                'current_stock' => 80,
                'status' => Status::ACTIVE,
            ],
            [
                'name' => 'গাওয়া ঘি (খাঁটি বাঘাবাড়ী)',
                'kitchen_goods_category_id' => $categories['তেল ও ঘি']->id,
                'unit_id' => $units['kg']->id,
                'cost_per_unit' => 1400,
                'current_stock' => 15,
                'status' => Status::ACTIVE,
            ],
            [
                'name' => 'ব্রয়লার মুরগির মাংস (ড্রেসড)',
                'kitchen_goods_category_id' => $categories['মাংস ও ডিম']->id,
                'unit_id' => $units['kg']->id,
                'cost_per_unit' => 195,
                'current_stock' => 60,
                'status' => Status::ACTIVE,
            ],
            [
                'name' => 'ফার্মের লাল ডিম',
                'kitchen_goods_category_id' => $categories['মাংস ও ডিম']->id,
                'unit_id' => $units['pcs']->id,
                'cost_per_unit' => 12.5,
                'current_stock' => 300,
                'status' => Status::ACTIVE,
            ],
            [
                'name' => 'দেশি পেঁয়াজ',
                'kitchen_goods_category_id' => $categories['শাকসবজি ও সালাদ']->id,
                'unit_id' => $units['kg']->id,
                'cost_per_unit' => 85,
                'current_stock' => 70,
                'status' => Status::ACTIVE,
            ],
            [
                'name' => 'আদা ও রসুন পেস্ট',
                'kitchen_goods_category_id' => $categories['মশলা ও উপাদান']->id,
                'unit_id' => $units['kg']->id,
                'cost_per_unit' => 240,
                'current_stock' => 25,
                'status' => Status::ACTIVE,
            ],
            [
                'name' => 'খাঁটি তরল দুধ',
                'kitchen_goods_category_id' => $categories['ডেইরি ও দুগ্ধজাত']->id,
                'unit_id' => $units['ltr']->id,
                'cost_per_unit' => 90,
                'current_stock' => 40,
                'status' => Status::ACTIVE,
            ],
        ];

        $goods = [];
        foreach ($goodsData as $g) {
            $goods[$g['name']] = KitchenGoods::firstOrCreate(['name' => $g['name']], $g);
        }

        // 5. Expense Categories
        $expCategoriesData = [
            'বিদ্যুৎ ও গ্যাস বিল' => 'মাসিক গ্যাস সিলিন্ডার ও ডেসকো বিদ্যুৎ বিল',
            'রেস্টুরেন্ট দোকান ভাড়া' => 'মূল শপ স্পেস ও কিচেন স্পেস ভাড়া',
            'স্টাফ বেতন ও ভাতা' => 'শেফ, ওয়েটার ও কিচেন হেল্পারদের স্যালারি',
            'কিচেন সরঞ্জাম ও মেরামত' => 'চুলা, ফ্রিজ ও ওভেন সার্ভিসিং খরচ',
            'পরিবহন ও বাজার খরচ' => 'ভ্যানভাড়া, সিএনজি ও ডেলিভারি যাতায়াত',
            'আপ্যায়ন ও চা-নাস্তা' => 'গেস্ট ও অফিসিয়াল রিফ্রেশমেন্ট',
        ];

        $expCategories = [];
        foreach ($expCategoriesData as $name => $desc) {
            $expCategories[$name] = ExpenseCategory::firstOrCreate(
                ['name' => $name],
                ['description' => $desc, 'status' => \App\Enums\Status::ACTIVE]
            );
        }

        // 6. Sample Purchases
        if (Purchase::count() === 0) {
            // Purchase 1: Haq Traders
            $pur1 = Purchase::create([
                'purchase_no' => 'PUR-' . date('ymd') . '-1001',
                'supplier_id' => $suppliers['মেসার্স হক ট্রেডার্স']->id,
                'date' => date('Y-m-d', strtotime('-2 days')),
                'total_amount' => 31000,
                'paid_amount' => 31000,
                'payment_method' => 1,
                'note' => '৫০ কেজি বাসমতী চাল ও ১০০ লিটার সয়াবিন তেল ক্রয়',
                'user_id' => $adminId,
            ]);
            PurchaseItem::create([
                'purchase_id' => $pur1->id,
                'kitchen_goods_id' => $goods['বাসমতী চাল (কাচ্চি প্রিমিয়াম)']->id,
                'quantity' => 50,
                'unit_cost' => 280,
                'total_cost' => 14000,
            ]);
            PurchaseItem::create([
                'purchase_id' => $pur1->id,
                'kitchen_goods_id' => $goods['সয়াবিন তেল (রূপচাঁদা ড্রাম)']->id,
                'quantity' => 100,
                'unit_cost' => 170,
                'total_cost' => 17000,
            ]);

            // Purchase 2: Green Valley Poultry
            $pur2 = Purchase::create([
                'purchase_no' => 'PUR-' . date('ymd') . '-1002',
                'supplier_id' => $suppliers['গ্রিন ভ্যালি পোল্ট্রি ফার্ম']->id,
                'date' => date('Y-m-d', strtotime('-1 days')),
                'total_amount' => 15450,
                'paid_amount' => 15450,
                'payment_method' => 1,
                'note' => '৫০ কেজি ব্রয়লার মুরগি ও ৫০০ পিস লাল ডিম ডেলিভারি',
                'user_id' => $adminId,
            ]);
            PurchaseItem::create([
                'purchase_id' => $pur2->id,
                'kitchen_goods_id' => $goods['ব্রয়লার মুরগির মাংস (ড্রেসড)']->id,
                'quantity' => 50,
                'unit_cost' => 195,
                'total_cost' => 9750,
            ]);
            PurchaseItem::create([
                'purchase_id' => $pur2->id,
                'kitchen_goods_id' => $goods['ফার্মের লাল ডিম']->id,
                'quantity' => 500,
                'unit_cost' => 11.4,
                'total_cost' => 5700,
            ]);
        }

        // 7. Sample Send To Kitchen
        if (SendToKitchen::count() === 0) {
            $stk1 = SendToKitchen::create([
                'send_no' => 'STK-' . date('ymd') . '-2001',
                'date' => date('Y-m-d', strtotime('-1 days')),
                'user_id' => $adminId,
                'note' => 'শুক্রবার দুপুরের কাচ্চি বিরিয়ানি ব্যাচের মালামাল ইস্যু',
                'total_items' => 3,
            ]);
            SendToKitchenItem::create([
                'send_to_kitchen_id' => $stk1->id,
                'kitchen_goods_id' => $goods['বাসমতী চাল (কাচ্চি প্রিমিয়াম)']->id,
                'quantity' => 25,
            ]);
            SendToKitchenItem::create([
                'send_to_kitchen_id' => $stk1->id,
                'kitchen_goods_id' => $goods['সয়াবিন তেল (রূপচাঁদা ড্রাম)']->id,
                'quantity' => 15,
            ]);
            SendToKitchenItem::create([
                'send_to_kitchen_id' => $stk1->id,
                'kitchen_goods_id' => $goods['গাওয়া ঘি (খাঁটি বাঘাবাড়ী)']->id,
                'quantity' => 3,
            ]);
        }

        // 8. Sample Expenses
        if (Expense::count() === 0) {
            Expense::create([
                'expense_category_id' => $expCategories['বিদ্যুৎ ও গ্যাস বিল']->id,
                'title' => 'কিচেন এলপি গ্যাস সিলিন্ডার রিফিল (২টি)',
                'date' => date('Y-m-d', strtotime('-3 days')),
                'amount' => 2850,
                'payment_method' => 1,
                'note' => 'যমুনা গ্যাস ৪৫ কেজি রিফিল ক্যাশ পেমেন্ট',
                'user_id' => $adminId,
            ]);
            Expense::create([
                'expense_category_id' => $expCategories['কিচেন সরঞ্জাম ও মেরামত']->id,
                'title' => 'ডিপ ফ্রিজ কম্প্রেসার গ্যাস চার্জ ও সার্ভিসিং',
                'date' => date('Y-m-d', strtotime('-2 days')),
                'amount' => 3500,
                'payment_method' => 3,
                'note' => 'টেকনিশিয়ান শফিকুল ইসলাম (বিকাশ পেমেন্ট)',
                'user_id' => $adminId,
            ]);
            Expense::create([
                'expense_category_id' => $expCategories['পরিবহন ও বাজার খরচ']->id,
                'title' => 'কাওরান বাজার থেকে সবজি ভ্যান ভাড়া',
                'date' => date('Y-m-d', strtotime('-1 days')),
                'amount' => 650,
                'payment_method' => 1,
                'note' => 'ভ্যান চালক রফিক মিয়া ক্যাশ রসিদ',
                'user_id' => $adminId,
            ]);
            Expense::create([
                'expense_category_id' => $expCategories['আপ্যায়ন ও চা-নাস্তা']->id,
                'title' => 'স্টাফ বিকালের চা-বিস্কুট ও নাশতা',
                'date' => date('Y-m-d'),
                'amount' => 420,
                'payment_method' => 1,
                'note' => 'সাপ্তাহিক চা-নাস্তা ভাউচার',
                'user_id' => $adminId,
            ]);
        }
    }
}
