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
        'completed_at',
        'checklist',
        'user_checklist',
        'notes',
        'status',
        'reminder_sent_at'
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'completed_at' => 'datetime',
        'reminder_sent_at' => 'datetime',
        'checklist' => 'array',
        'user_checklist' => 'array'
    ];

    protected static function booted()
    {
        static::creating(function ($task) {
            // Load the tax calendar relationship if it's not already loaded
            if (!$task->relationLoaded('taxCalendar')) {
                $task->load('taxCalendar');
            }

            // Set the accountant checklist
            if (empty($task->checklist) && $task->taxCalendar && $task->taxCalendar->default_checklist) {
                $task->checklist = $task->taxCalendar->default_checklist;
            }

            // Set the user checklist with user-specific items
            if (empty($task->user_checklist) && $task->taxCalendar) {
                $task->user_checklist = $task->taxCalendar->getDefaultUserChecklist();
            }
        });
    }

    // Get the appropriate checklist based on user type
    public function getActiveChecklist()
    {
        if (auth()->user()->is_admin || auth()->user()->is_accountant) {
            return $this->checklist;
        }
        return $this->user_checklist;
    }

    // Update the appropriate checklist based on user type
    public function updateActiveChecklist(array $checklist)
    {
        if (auth()->user()->is_admin || auth()->user()->is_accountant) {
            $this->update(['checklist' => $checklist]);
        } else {
            $this->update(['user_checklist' => $checklist]);
        }
    }

    // Calculate overall progress considering both checklists
    public function getProgressAttribute()
    {
        $accountantProgress = $this->getChecklistProgress($this->checklist);
        $userProgress = $this->getChecklistProgress($this->user_checklist);
        
        // If both checklists exist, return average progress
        if ($this->checklist && $this->user_checklist) {
            return ($accountantProgress + $userProgress) / 2;
        }
        
        // If only one checklist exists, return its progress
        return $this->checklist ? $accountantProgress : $userProgress;
    }

    private function getChecklistProgress($checklist)
    {
        if (!$checklist) return 0;
        
        $total = count($checklist);
        if ($total === 0) return 0;
        
        $completed = collect($checklist)->filter(fn($item) => $item['completed'])->count();
        return ($completed / $total) * 100;
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

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'pending')
            ->where('due_date', '<', now());
    }

    public function scopeUpcoming($query, $days = 30)
    {
        return $query->where('status', 'pending')
            ->whereBetween('due_date', [now(), now()->addDays($days)]);
    }

    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeNeedsReminder($query)
    {
        return $query->where('status', 'pending')
            ->whereNull('reminder_sent_at')
            ->whereHas('taxCalendar', function ($q) {
                $q->where('auto_create_tasks', true);
            });
    }

    // Attributes
    public function getIsOverdueAttribute()
    {
        return !$this->completed_at && $this->due_date->isPast();
    }

    public function getIsCompletedAttribute()
    {
        return $this->status === 'completed';
    }

    // Methods
    public function complete()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);
    }

    public function reopen()
    {
        $this->update([
            'status' => 'pending',
            'completed_at' => null
        ]);
    }

    public function markReminderSent()
    {
        $this->update([
            'reminder_sent_at' => now()
        ]);
    }

    public function getDaysUntilDueAttribute()
    {
        return Carbon::now()->startOfDay()->diffInDays($this->due_date, false);
    }

    public function getUrgencyLevelAttribute()
    {
        if ($this->status === 'completed') return 'completed';
        if ($this->is_overdue) return 'overdue';
        if ($this->days_until_due <= 3) return 'urgent';
        if ($this->days_until_due <= 7) return 'warning';
        return 'normal';
    }
} 