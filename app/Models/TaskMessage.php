<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskMessage extends Model
{
    protected $fillable = [
        'tax_calendar_task_id',
        'user_id',
        'content',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(TaxCalendarTask::class, 'tax_calendar_task_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
