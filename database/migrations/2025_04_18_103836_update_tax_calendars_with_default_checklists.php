<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\TaxCalendar;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $taxCalendars = TaxCalendar::all();

        foreach ($taxCalendars as $calendar) {
            if (empty($calendar->default_checklist)) {
                $baseChecklist = $this->getDefaultChecklist($calendar);
                $calendar->update(['default_checklist' => $baseChecklist]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to revert as we're just adding default checklists
    }

    protected function getDefaultChecklist($calendar)
    {
        $baseChecklist = [
            [
                'title' => 'Review previous period\'s documentation',
                'completed' => false,
                'notes' => 'Check last period\'s submissions and any notes'
            ],
            [
                'title' => 'Gather all required documents',
                'completed' => false,
                'notes' => 'Collect invoices, receipts, and relevant financial records'
            ],
            [
                'title' => 'Verify account balances',
                'completed' => false,
                'notes' => 'Ensure all accounts are reconciled and up to date'
            ]
        ];

        if ($calendar->requires_payment) {
            $baseChecklist[] = [
                'title' => 'Calculate payment amount',
                'completed' => false,
                'notes' => 'Double-check all calculations'
            ];
            $baseChecklist[] = [
                'title' => 'Prepare payment details',
                'completed' => false,
                'notes' => 'Get approval for payment if required'
            ];
        }

        // Add frequency-specific items
        switch ($calendar->frequency) {
            case 'monthly':
                $baseChecklist[] = [
                    'title' => 'Review monthly transactions',
                    'completed' => false,
                    'notes' => 'Check all transactions for the month'
                ];
                break;
            case 'quarterly':
                $baseChecklist[] = [
                    'title' => 'Review quarterly summaries',
                    'completed' => false,
                    'notes' => 'Check quarterly totals and compare with previous quarters'
                ];
                break;
            case 'annual':
                $baseChecklist[] = [
                    'title' => 'Review annual reports',
                    'completed' => false,
                    'notes' => 'Check annual totals and prepare year-end reconciliation'
                ];
                $baseChecklist[] = [
                    'title' => 'Verify compliance with annual requirements',
                    'completed' => false,
                    'notes' => 'Ensure all annual obligations are met'
                ];
                break;
        }

        $baseChecklist[] = [
            'title' => 'Submit declaration in e-Tax/e-Customs',
            'completed' => false,
            'notes' => 'Use the correct form code and period'
        ];

        $baseChecklist[] = [
            'title' => 'Save confirmation of submission',
            'completed' => false,
            'notes' => 'Store confirmation number and screenshot if needed'
        ];

        return $baseChecklist;
    }
};
