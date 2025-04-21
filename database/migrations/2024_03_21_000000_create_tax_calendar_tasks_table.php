<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tax_calendar_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tax_calendar_id')->constrained()->onDelete('cascade');
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('due_date');
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('reminder_sent_at')->nullable();
            $table->json('checklist')->nullable()->comment('Accountant checklist');
            $table->json('user_checklist')->nullable()->comment('User checklist');
            $table->text('notes')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->index(['company_id', 'status', 'due_date']);
            $table->index(['user_id', 'status', 'due_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('tax_calendar_tasks');
    }
}; 