<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TaxCalendar;

class EstonianTaxCalendarSeeder extends Seeder
{
    public function run()
    {
        // Clear existing entries
        TaxCalendar::truncate();

        // VAT Return (KMD)
        TaxCalendar::create([
            'name' => 'VAT Return',
            'form_code' => 'KMD',
            'description' => 'Submit VAT return (KMD) and its annex (KMD INF) for the previous month',
            'frequency' => 'monthly',
            'due_day' => 20,
            'payment_due_day' => 20,
            'emta_link' => 'https://www.emta.ee/en/business-client/taxes-and-payment/value-added-tax-vat/vat-return',
            'country_code' => 'EE',
            'is_active' => true,
            'requires_payment' => true
        ]);

        // Income and Social Tax Return (TSD)
        TaxCalendar::create([
            'name' => 'Income and Social Tax Return',
            'form_code' => 'TSD',
            'description' => 'Submit tax return on income and social tax, unemployment insurance premiums and contributions to mandatory funded pension (TSD)',
            'frequency' => 'monthly',
            'due_day' => 10,
            'payment_due_day' => 10,
            'emta_link' => 'https://www.emta.ee/en/business-client/taxes-and-payment/income-and-social-tax/tsd-return',
            'country_code' => 'EE',
            'is_active' => true,
            'requires_payment' => true
        ]);

        // Annual Report Submission
        TaxCalendar::create([
            'name' => 'Annual Report Submission',
            'description' => 'Submit annual report to the Business Register',
            'frequency' => 'annual',
            'due_month' => 6, // June
            'due_day' => 30,  // 30th
            'emta_link' => 'https://www.rik.ee/en/e-business-register',
            'country_code' => 'EE',
            'is_active' => true,
            'requires_payment' => false
        ]);
    }
}
