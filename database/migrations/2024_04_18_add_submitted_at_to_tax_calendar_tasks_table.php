<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tax_calendar_tasks', function (Blueprint $table) {
            $table->timestamp('submitted_at')->nullable()->after('status');
            $table->text('review_comments')->nullable()->after('submitted_at');
            $table->timestamp('reviewed_at')->nullable()->after('review_comments');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->after('reviewed_at');
        });
    }

    public function down()
    {
        Schema::table('tax_calendar_tasks', function (Blueprint $table) {
            $table->dropColumn(['submitted_at', 'review_comments', 'reviewed_at', 'reviewed_by']);
        });
    }
}; 