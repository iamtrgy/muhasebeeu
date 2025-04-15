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
                'description' => 'Monthly VAT return (KMD) submission and payment',
                'frequency' => 'monthly',
                'due_day' => 20,
                'form_code' => 'KMD',
                'emta_link' => 'https://www.emta.ee/en/business-client/taxes-paying/value-added-tax/vat-returns',
                'requires_payment' => true,
                'payment_due_day' => 20,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'country_code' => 'EE',
                'name' => 'Social Tax and Income Tax Return',
                'description' => 'Monthly TSD declaration for payroll taxes',
                'frequency' => 'monthly',
                'due_day' => 10,
                'form_code' => 'TSD',
                'emta_link' => 'https://www.emta.ee/en/business-client/taxes-paying/tax-and-customs-board-declaration-forms',
                'requires_payment' => true,
                'payment_due_day' => 10,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'country_code' => 'EE',
                'name' => 'Annual Report',
                'description' => 'Annual report submission to Business Register',
                'frequency' => 'annual',
                'due_day' => 30,
                'form_code' => null,
                'emta_link' => null,
                'requires_payment' => false,
                'payment_due_day' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'country_code' => 'EE',
                'name' => 'Intrastat Report',
                'description' => 'Monthly statistical report for EU trade',
                'frequency' => 'monthly',
                'due_day' => 14,
                'form_code' => 'INTRASTAT',
                'emta_link' => 'https://www.emta.ee/en/business-client/customs-trade-goods/intrastat',
                'requires_payment' => false,
                'payment_due_day' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($taxDates as $tax) {
            DB::table('tax_calendars')->insertOrIgnore($tax);
        }
    }
} 