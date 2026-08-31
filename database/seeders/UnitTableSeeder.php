<?php

namespace Database\Seeders;

use App\Enums\Status;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitTableSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            ['name' => 'Kilogram', 'code' => 'kg', 'status' => Status::ACTIVE],
            ['name' => 'Gram', 'code' => 'gm', 'status' => Status::ACTIVE],
            ['name' => 'Litre', 'code' => 'ltr', 'status' => Status::ACTIVE],
            ['name' => 'Millilitre', 'code' => 'ml', 'status' => Status::ACTIVE],
            ['name' => 'Piece', 'code' => 'pcs', 'status' => Status::ACTIVE],
            ['name' => 'Packet', 'code' => 'pkt', 'status' => Status::ACTIVE],
            ['name' => 'Box', 'code' => 'box', 'status' => Status::ACTIVE],
        ];

        foreach ($units as $unit) {
            Unit::firstOrCreate(['name' => $unit['name']], $unit);
        }
    }
}
