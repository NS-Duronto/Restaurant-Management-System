<?php

namespace Database\Seeders;

use App\Enums\Ask;
use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Currency::insert([
            [
                'name' => 'Taka',
                'symbol' => '৳',
                'code' => 'BDT',
                'is_cryptocurrency' => Ask::NO,
                'exchange_rate' => 1
            ],
            [
                'name' => 'Dollars',
                'symbol' => '$',
                'code' => 'USD',
                'is_cryptocurrency' => Ask::NO,
                'exchange_rate' => 1
            ],
        ]);
    }
}
