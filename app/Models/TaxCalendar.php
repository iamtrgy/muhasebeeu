<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class TaxCalendar extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'country_code',
        'frequency',
        'due_day',
        'due_month',
        'form_code',
        'description',
        'emta_link',
        'requires_payment',
        'payment_due_day',
        'is_active'
    ];

    protected $casts = [
        'due_day' => 'integer',
        'payment_due_day' => 'integer',
        'requires_payment' => 'boolean',
        'is_active' => 'boolean'
    ];

    // Scopes for filtering
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForCountry($query, $countryCode)
    {
        return $query->where('country_code', $countryCode);
    }

    public function scopeByFrequency($query, $frequency)
    {
        return $query->where('frequency', $frequency);
    }

    // Get upcoming deadlines for the next N days
    public function scopeUpcoming($query, $days = 30)
    {
        return $query->where('is_active', true);
    }

    // Get the next occurrence of this deadline
    public function getNextDeadlineAttribute()
    {
        $today = now()->startOfDay();
        $currentMonth = $today->month;
        $currentYear = $today->year;
        
        try {
            // For monthly deadlines
            if ($this->frequency === 'monthly') {
                $deadline = Carbon::create($currentYear, $currentMonth, $this->due_day)->startOfDay();
                
                // If today is before or equal to the deadline, use this month
                if ($today->lte($deadline)) {
                    return $deadline;
                }
                
                // Otherwise, use next month
                return $deadline->addMonth();
            }
            
            // For quarterly deadlines
            if ($this->frequency === 'quarterly') {
                $currentQuarter = ceil($currentMonth / 3);
                $quarterMonth = ($currentQuarter * 3) - 2; // First month of current quarter
                
                $deadline = Carbon::create($currentYear, $quarterMonth, 1)->lastOfMonth();
                if ($this->due_day <= $deadline->day) {
                    $deadline = Carbon::create($currentYear, $quarterMonth, $this->due_day);
                }
                
                while ($deadline->isPast()) {
                    $quarterMonth += 3;
                    if ($quarterMonth > 12) {
                        $quarterMonth = 1;
                        $currentYear++;
                    }
                    $deadline = Carbon::create($currentYear, $quarterMonth, 1)->lastOfMonth();
                    if ($this->due_day <= $deadline->day) {
                        $deadline = Carbon::create($currentYear, $quarterMonth, $this->due_day);
                    }
                }
                return $deadline;
            }
            
            // For annual deadlines
            if ($this->frequency === 'annual') {
                $month = $this->due_month ?? 1; // Default to January if not specified
                $deadline = Carbon::create($currentYear, $month, $this->due_day)->startOfDay();
                
                // If today is before or equal to the deadline, use this year
                if ($today->lte($deadline)) {
                    return $deadline;
                }
                
                // Otherwise, use next year
                return $deadline->addYear();
            }
        } catch (\Exception $e) {
            \Log::error('Error calculating next deadline: ' . $e->getMessage());
            return null;
        }
        
        return null;
    }

    // Get the next payment date if different from deadline
    public function getNextPaymentDateAttribute()
    {
        if (!$this->requires_payment || !$this->payment_due_day) {
            return null;
        }

        try {
            $deadline = $this->next_deadline;
            if (!$deadline) {
                return null;
            }

            $lastDay = $deadline->copy()->lastOfMonth();
            if ($this->payment_due_day <= $lastDay->day) {
                return $deadline->copy()->day($this->payment_due_day)->startOfDay();
            }
            return $lastDay->startOfDay();
        } catch (\Exception $e) {
            \Log::error('Error calculating next payment date: ' . $e->getMessage());
            return null;
        }
    }
}
