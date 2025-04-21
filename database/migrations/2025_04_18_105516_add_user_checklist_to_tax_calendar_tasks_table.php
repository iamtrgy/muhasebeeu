<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\TaxCalendarTask;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tax_calendar_tasks', function (Blueprint $table) {
    if (!Schema::hasColumn('tax_calendar_tasks', 'user_checklist')) {
        $table->json('user_checklist')->nullable()->after('checklist');
    }
});

        // Update existing tasks with user checklists
        $tasks = TaxCalendarTask::all();
        foreach ($tasks as $task) {
            if ($task->checklist && empty($task->user_checklist)) {
                $userChecklist = collect($task->checklist)->map(function ($item) {
                    return [
                        'title' => $item['title'],
                        'completed' => false,
                        'notes' => $item['notes'] ?? null
                    ];
                })->toArray();
                
                $task->update(['user_checklist' => $userChecklist]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tax_calendar_tasks', function (Blueprint $table) {
            $table->dropColumn('user_checklist');
        });
    }
};
