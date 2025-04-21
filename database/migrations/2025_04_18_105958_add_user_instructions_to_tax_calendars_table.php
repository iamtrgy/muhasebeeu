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
        Schema::table('tax_calendars', function (Blueprint $table) {
            $table->text('user_instructions')->nullable()->after('task_instructions');
        });

        // Update existing tax calendars with user instructions
        $taxCalendars = TaxCalendar::all();
        foreach ($taxCalendars as $calendar) {
            if (empty($calendar->user_instructions)) {
                $instructions = [
                    "Important Deadlines:",
                    "- Document submission: 3 days before due date",
                    "- Payment (if required): By " . $calendar->payment_due_day . "th of the month",
                    "",
                    "Required Actions:",
                    "1. Collect and organize all documents:",
                    "   - Sales invoices for the period",
                    "   - Purchase invoices and receipts",
                    "   - Bank statements",
                ];

                if ($calendar->form_code === 'KMD') {
                    $instructions[] = "   - EU sales and purchase documents";
                }

                $instructions[] = "";
                $instructions[] = "2. Document Submission:";
                $instructions[] = "   - Upload documents to the portal";
                $instructions[] = "   - Or email them to your accountant";
                $instructions[] = "   - Keep original documents for your records";

                if ($calendar->requires_payment) {
                    $instructions[] = "";
                    $instructions[] = "3. Payment Preparation:";
                    $instructions[] = "   - Review the payment amount when provided";
                    $instructions[] = "   - Ensure sufficient funds are available";
                    $instructions[] = "   - Payment deadline: " . $calendar->payment_due_day . "th of the month";
                }

                $instructions[] = "";
                $instructions[] = "Need Help?";
                $instructions[] = "- Contact your accountant for questions";
                if ($calendar->emta_link) {
                    $instructions[] = "- EMTA Guide: " . $calendar->emta_link;
                }

                $calendar->user_instructions = implode("\n", $instructions);
                $calendar->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tax_calendars', function (Blueprint $table) {
            $table->dropColumn('user_instructions');
        });
    }
};
