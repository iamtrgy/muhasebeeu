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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('company_id')->constrained(); // Faturayı oluşturan şirket
            $table->foreignId('client_id')->nullable()->constrained('user_clients'); // Müşteri şirket
            $table->string('client_name')->nullable(); // Alternatif olarak ad-soyad girebilmek için
            $table->string('client_vat_number')->nullable(); // VAT numarası (vergi numarası)
            $table->string('client_company_reg_number')->nullable(); // Şirket sicil numarası
            $table->string('client_country', 2)->nullable(); // ISO ülke kodu
            $table->string('client_address')->nullable();
            $table->string('client_email')->nullable();
            $table->string('client_phone')->nullable();
            $table->date('invoice_date');
            $table->date('due_date')->nullable();
            $table->decimal('subtotal', 12, 2);
            $table->decimal('tax_amount', 12, 2);
            $table->decimal('total', 12, 2);
            $table->string('currency', 3)->default('TRY');
            $table->string('language_code', 5)->default('tr'); // Fatura dili (tr, en, de vb.)
            $table->string('notes')->nullable();
            $table->string('status')->default('draft'); // draft, sent, paid, cancelled
            $table->string('pdf_path')->nullable(); // Oluşturulan PDF'in yolu
            $table->foreignId('folder_id')->nullable()->constrained(); // Kaydedilen klasör
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->string('payment_method')->nullable(); // Ödeme şekli: bank_transfer, credit_card, cash, paypal
            $table->string('payment_terms')->nullable(); // Ödeme şartları: due_receipt, net_15, net_30, net_60
            $table->string('reference')->nullable(); // Referans/PO numarası
            $table->boolean('reverse_charge')->default(false); // Ters vergilendirme uygulanıyor mu
            $table->boolean('vat_exempt')->default(false); // KDV muaf mı
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
