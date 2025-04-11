<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tax_calendars', function (Blueprint $table) {
            $table->id();
            $table->string('name');                    // e.g., "VAT Return", "Social Tax Declaration"
            $table->string('country_code');            // e.g., "EE" for Estonia
            $table->enum('frequency', ['monthly', 'quarterly', 'annual']);
            $table->unsignedTinyInteger('due_day');    // Day of month/quarter/year
            $table->string('form_code')->nullable();   // e.g., "KMD", "TSD", "INF14"
            $table->text('description')->nullable();
            $table->string('emta_link')->nullable();   // Link to relevant EMTA page
            $table->boolean('requires_payment');       // Whether this deadline involves a payment
            $table->unsignedTinyInteger('payment_due_day')->nullable(); // If payment due date is different
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Index for quick filtering
            $table->index(['country_code', 'frequency', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_calendars');
    }
};
