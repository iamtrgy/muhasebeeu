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
        Schema::create('bank_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')->constrained()->onDelete('cascade');
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->date('transaction_date');
            $table->text('description');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('EUR');
            $table->enum('type', ['debit', 'credit']);
            $table->decimal('balance', 10, 2)->nullable();
            $table->string('reference_number')->nullable();
            $table->string('category')->nullable();
            $table->foreignId('matched_invoice_id')->nullable()->constrained('invoices')->onDelete('set null');
            $table->integer('match_confidence')->nullable();
            $table->enum('match_status', ['unmatched', 'auto_matched', 'manual_matched', 'ignored'])->default('unmatched');
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'reviewed', 'reconciled'])->default('pending');
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['company_id', 'transaction_date']);
            $table->index(['file_id', 'transaction_date']);
            $table->index('match_status');
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_transactions');
    }
};
