<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class TaxCalendarTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'tax_calendar_id',
        'company_id',
        'user_id',
        'due_date',
        'checklist',
        'notes',
        'user_notes',
        'is_completed'
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'checklist' => 'array',
        'is_completed' => 'boolean'
    ];

    protected static function booted()
    {
        static::creating(function ($task) {
            // Set default checklist if empty
            if (empty($task->checklist) && $task->taxCalendar && $task->taxCalendar->default_checklist) {
                $task->checklist = $task->taxCalendar->default_checklist;
            }
            
            // If no checklist at all, create a simple default
            if (empty($task->checklist)) {
                $task->checklist = [
                    ['title' => 'Complete this task', 'completed' => false, 'notes' => null]
                ];
            }
        });
    }

    // Simple progress calculation
    public function getProgressAttribute()
    {
        if (!$this->checklist) return 0;
        
        $total = count($this->checklist);
        if ($total === 0) return 0;
        
        $completed = collect($this->checklist)->filter(fn($item) => $item['completed'])->count();
        return ($completed / $total) * 100;
    }

    // Get completed checklist items count
    public function getCompletedItemsAttribute()
    {
        if (!$this->checklist) return 0;
        return collect($this->checklist)->filter(fn($item) => $item['completed'])->count();
    }

    // Get total checklist items count
    public function getTotalItemsAttribute()
    {
        if (!$this->checklist) return 0;
        return count($this->checklist);
    }

    // Update checklist
    public function updateChecklist(array $checklist)
    {
        $this->update(['checklist' => $checklist]);
        
        // Auto-complete task if all items are completed
        $allCompleted = collect($checklist)->every(fn($item) => $item['completed'] === true);
        if ($allCompleted && !$this->is_completed) {
            $this->update(['is_completed' => true]);
        } elseif (!$allCompleted && $this->is_completed) {
            $this->update(['is_completed' => false]);
        }
    }

    // Relationships
    public function taxCalendar(): BelongsTo
    {
        return $this->belongsTo(TaxCalendar::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(TaskMessage::class, 'tax_calendar_task_id')->orderBy('created_at', 'asc');
    }

    // Simple scopes
    public function scopePending($query)
    {
        return $query->where('is_completed', false);
    }

    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    public function scopeOverdue($query)
    {
        return $query->where('is_completed', false)
            ->where('due_date', '<', now());
    }

    public function scopeUpcoming($query, $days = 30)
    {
        return $query->where('is_completed', false)
            ->whereBetween('due_date', [now(), now()->addDays($days)]);
    }

    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    // Simple attributes
    public function getIsOverdueAttribute()
    {
        return !$this->is_completed && $this->due_date->isPast();
    }

    public function getDaysUntilDueAttribute()
    {
        return Carbon::now()->startOfDay()->diffInDays($this->due_date, false);
    }

    public function getUrgencyLevelAttribute()
    {
        if ($this->is_completed) return 'completed';
        if ($this->is_overdue) return 'overdue';
        if ($this->days_until_due <= 3) return 'urgent';
        if ($this->days_until_due <= 7) return 'warning';
        return 'normal';
    }

    // Simple methods
    public function complete()
    {
        $this->update(['is_completed' => true]);
    }

    public function reopen()
    {
        $this->update(['is_completed' => false]);
    }
} 