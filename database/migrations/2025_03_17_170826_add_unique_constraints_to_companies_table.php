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
        // First, let's remove duplicates
        // Find all duplicate names
        $duplicateNames = DB::table('companies')
            ->select('name')
            ->groupBy('name')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        // For each duplicate name, keep the first one and update the others
        foreach ($duplicateNames as $duplicate) {
            $companies = DB::table('companies')
                ->where('name', $duplicate->name)
                ->orderBy('id')
                ->get();
            
            $first = true;
            foreach ($companies as $company) {
                if ($first) {
                    // Keep the first one
                    $first = false;
                    continue;
                }
                
                // Update the duplicates with a unique name
                DB::table('companies')
                    ->where('id', $company->id)
                    ->update(['name' => $company->name . ' (ID: ' . $company->id . ')']);
            }
        }

        // Find all duplicate tax numbers
        $duplicateTaxNumbers = DB::table('companies')
            ->select('tax_number')
            ->whereNotNull('tax_number')
            ->groupBy('tax_number')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        // For each duplicate tax number, keep the first one and update the others
        foreach ($duplicateTaxNumbers as $duplicate) {
            $companies = DB::table('companies')
                ->where('tax_number', $duplicate->tax_number)
                ->orderBy('id')
                ->get();
            
            $first = true;
            foreach ($companies as $company) {
                if ($first) {
                    // Keep the first one
                    $first = false;
                    continue;
                }
                
                // Clear the tax number for duplicates
                DB::table('companies')
                    ->where('id', $company->id)
                    ->update(['tax_number' => null]);
            }
        }

        // Now add unique constraints
        Schema::table('companies', function (Blueprint $table) {
            $table->unique('name');
            $table->unique('tax_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropUnique('companies_name_unique');
            $table->dropUnique('companies_tax_number_unique');
        });
    }
};
