<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tax_calendars', function (Blueprint $table) {
            $table->json('default_checklist')->nullable()->after('auto_create_tasks');
            $table->text('task_instructions')->nullable()->after('default_checklist');
            $table->integer('reminder_days_before')->default(7)->after('task_instructions');
        });
    }

    public function down()
    {
        Schema::table('tax_calendars', function (Blueprint $table) {
            $table->dropColumn(['default_checklist', 'task_instructions', 'reminder_days_before']);
        });
    }
}; 