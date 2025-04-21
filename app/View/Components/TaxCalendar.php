<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\TaxCalendar as TaxCalendarModel;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class TaxCalendar extends Component
{
    public Collection $deadlines;
    public Collection $hiddenDeadlines;
    public $daysToShow;
    public $showAll = false;
    public $initialCount = 3;

    public function __construct($daysToShow = 30, $showAll = false)
    {
        $this->daysToShow = $daysToShow;
        $this->showAll = $showAll;
        
        // Get upcoming deadlines for user's companies
        $allDeadlines = TaxCalendarModel::query()
            ->active()
            ->whereIn('country_code', auth()->user()->companies->pluck('country_code')->unique())
            ->upcoming($daysToShow)
            ->get()
            ->map(function($deadline) {
                $nextDeadline = $deadline->next_deadline;
                $daysUntil = $nextDeadline ? now()->startOfDay()->diffInDays($nextDeadline->startOfDay(), false) : null;
                
                // Get tasks for the current user's companies for this deadline
                $tasks = $deadline->tasks()
                    ->whereIn('company_id', auth()->user()->companies->pluck('id'))
                    ->where('due_date', $nextDeadline)
                    ->get();
                
                $totalTasks = $tasks->count();
                $completedTasks = $tasks->where('status', 'completed')->count();
                $progress = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
                
                return [
                    'name' => $deadline->name,
                    'description' => $deadline->description,
                    'form_code' => $deadline->form_code,
                    'emta_link' => $deadline->emta_link,
                    'next_deadline' => $nextDeadline,
                    'next_payment' => $deadline->next_payment_date,
                    'days_until' => $daysUntil,
                    'urgency' => $this->getUrgencyLevel($daysUntil),
                    'status' => $this->getStatus($daysUntil),
                    'progress' => $progress,
                    'total_tasks' => $totalTasks,
                    'completed_tasks' => $completedTasks
                ];
            })
            ->unique('name')
            ->sortBy('next_deadline')
            ->values();
            
        if ($this->showAll) {
            $this->deadlines = $allDeadlines;
            $this->hiddenDeadlines = collect();
        } else {
            $this->deadlines = $allDeadlines->take($this->initialCount);
            $this->hiddenDeadlines = $allDeadlines->slice($this->initialCount);
        }
    }

    private function getUrgencyLevel($daysUntil)
    {
        if ($daysUntil === null) return 'normal';
        if ($daysUntil < 0) return 'past';
        if ($daysUntil <= 3) return 'urgent';     // Red if 3 days or less
        if ($daysUntil <= 7) return 'warning';    // Yellow if 4-7 days
        return 'normal';                          // Green if more than 7 days
    }

    private function getStatus($daysUntil)
    {
        if ($daysUntil === null) return 'Unknown';
        if ($daysUntil < 0) return 'Past Due';
        if ($daysUntil === 0) return 'Due Today';
        if ($daysUntil === 1) return 'Due Tomorrow';
        if ($daysUntil <= 3) return 'Due in ' . $daysUntil . ' days (Urgent!)';
        if ($daysUntil <= 7) return 'Due in ' . $daysUntil . ' days (Prepare)';
        return 'Due in ' . $daysUntil . ' days';
    }

    public function render()
    {
        return view('components.tax-calendar');
    }
}
