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
            'requires_payment' => true,
            'default_checklist' => [
                [
                    'title' => 'Review sales invoices',
                    'completed' => false
                ],
                [
                    'title' => 'Review purchase invoices',
                    'completed' => false
                ],
                [
                    'title' => 'Check VAT rates',
                    'completed' => false
                ],
                [
                    'title' => 'Verify EU transactions',
                    'completed' => false
                ],
                [
                    'title' => 'Complete KMD form',
                    'completed' => false
                ],
                [
                    'title' => 'Submit KMD INF if required',
                    'completed' => false
                ],
                [
                    'title' => 'Verify payment amount',
                    'completed' => false
                ]
            ],
            'task_instructions' => "1. Log in to e-MTA\n2. Go to VAT Returns\n3. Fill in form KMD\n4. Check if KMD INF is required\n5. Submit the return\n6. Make payment if required",
            'reminder_days_before' => 5
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
            'requires_payment' => true,
            'default_checklist' => [
                [
                    'title' => 'Review salary payments',
                    'completed' => false
                ],
                [
                    'title' => 'Check benefits and fringe benefits',
                    'completed' => false
                ],
                [
                    'title' => 'Verify social tax calculations',
                    'completed' => false
                ],
                [
                    'title' => 'Check unemployment insurance premiums',
                    'completed' => false
                ],
                [
                    'title' => 'Verify pension contributions',
                    'completed' => false
                ],
                [
                    'title' => 'Complete TSD annexes',
                    'completed' => false
                ],
                [
                    'title' => 'Verify payment amount',
                    'completed' => false
                ]
            ],
            'task_instructions' => "1. Log in to e-MTA\n2. Go to TSD Returns\n3. Fill in TSD main form\n4. Complete required annexes\n5. Submit the return\n6. Make payment",
            'reminder_days_before' => 5
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
            'requires_payment' => false,
            'default_checklist' => [
                [
                    'title' => 'Prepare balance sheet',
                    'completed' => false
                ],
                [
                    'title' => 'Prepare income statement',
                    'completed' => false
                ],
                [
                    'title' => 'Prepare cash flow statement',
                    'completed' => false
                ],
                [
                    'title' => 'Write notes to financial statements',
                    'completed' => false
                ],
                [
                    'title' => 'Management report',
                    'completed' => false
                ],
                [
                    'title' => 'Auditor review (if required)',
                    'completed' => false
                ],
                [
                    'title' => 'Board approval',
                    'completed' => false
                ],
                [
                    'title' => 'Shareholder approval',
                    'completed' => false
                ]
            ],
            'task_instructions' => "1. Prepare financial statements\n2. Get necessary approvals\n3. Log in to Business Register Portal\n4. Fill in the required forms\n5. Upload financial statements\n6. Submit the report",
            'reminder_days_before' => 14
        ]);
    }
}
