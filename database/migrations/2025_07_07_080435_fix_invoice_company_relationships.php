<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, let's check if there are invoices without company_id but with created_by
        $invoicesWithoutCompany = DB::table('invoices')
            ->whereNull('company_id')
            ->whereNotNull('created_by')
            ->get();

        foreach ($invoicesWithoutCompany as $invoice) {
            // Get the first company of the user who created the invoice
            $userCompany = DB::table('company_user')
                ->where('user_id', $invoice->created_by)
                ->first();
                
            if ($userCompany) {
                // Update the invoice with the company_id
                DB::table('invoices')
                    ->where('id', $invoice->id)
                    ->update(['company_id' => $userCompany->company_id]);
            }
        }
        
        // Also handle cases where company_id exists but created_by is null
        // This ensures old invoices have proper created_by field
        $invoicesWithoutCreator = DB::table('invoices')
            ->whereNotNull('company_id')
            ->whereNull('created_by')
            ->get();
            
        foreach ($invoicesWithoutCreator as $invoice) {
            // Get the first user of the company
            $companyUser = DB::table('company_user')
                ->where('company_id', $invoice->company_id)
                ->first();
                
            if ($companyUser) {
                DB::table('invoices')
                    ->where('id', $invoice->id)
                    ->update(['created_by' => $companyUser->user_id]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration only fixes data, no schema changes to reverse
    }
};