<?php

namespace Database\Seeders;

use App\Enums\Status;
use App\Models\ExpenseCategory;
use Illuminate\Database\Seeder;

class ExpenseCategoryTableSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Staff Salary', 'description' => 'Employee and staff monthly salary expenses', 'status' => Status::ACTIVE],
            ['name' => 'Electric Bill', 'description' => 'Electricity and utility bill expenses', 'status' => Status::ACTIVE],
            ['name' => 'Space Rent', 'description' => 'Restaurant space or shop monthly rent', 'status' => Status::ACTIVE],
            ['name' => 'Service Charge', 'description' => 'Maintenance, repair, and cleaning service charges', 'status' => Status::ACTIVE],
            ['name' => 'Other Expenses', 'description' => 'Miscellaneous and general operational expenses', 'status' => Status::ACTIVE],
        ];

        foreach ($categories as $category) {
            ExpenseCategory::firstOrCreate(['name' => $category['name']], $category);
        }
    }
}
