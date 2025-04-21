<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tax_calendars', function (Blueprint $table) {
            if (!Schema::hasColumn('tax_calendars', 'auto_create_tasks')) {
                $table->boolean('auto_create_tasks')->default(true)->after('is_active');
            }
        });
    }

    public function down()
    {
        Schema::table('tax_calendars', function (Blueprint $table) {
            $table->dropColumn('auto_create_tasks');
        });
    }
}; 