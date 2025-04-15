<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaxCalendarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $taxDates = [
            [
                'country_code' => 'EE',
                'name' => 'VAT Return',
                'description' => 'Monthly VAT return due',
                'due_day' => 20,
                'due_month' => null, // Monthly
                'category' => 'vat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'country_code' => 'EE',
                'name' => 'Annual Report',
                'description' => 'Annual report submission deadline',
                'due_day' => 30,
                'due_month' => 6, // June
                'category' => 'annual',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'country_code' => 'EE',
                'name' => 'Income Tax Return',
                'description' => 'Annual income tax return due',
                'due_day' => 31,
                'due_month' => 3, // March
                'category' => 'income_tax',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'country_code' => 'EE',
                'name' => 'Social Tax Return',
                'description' => 'Monthly social tax return due',
                'due_day' => 10,
                'due_month' => null, // Monthly
                'category' => 'social_tax',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($taxDates as $tax) {
            DB::table('tax_calendars')->insertOrIgnore($tax);
        }
    }
} 