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
        'is_active',
        'auto_create_tasks',
        'default_checklist',
        'task_instructions',
        'user_instructions',
        'reminder_days_before'
    ];

    protected $casts = [
        'due_day' => 'integer',
        'payment_due_day' => 'integer',
        'requires_payment' => 'boolean',
        'is_active' => 'boolean',
        'auto_create_tasks' => 'boolean',
        'default_checklist' => 'array'
    ];

    protected static function booted()
    {
        static::creating(function ($taxCalendar) {
            if (empty($taxCalendar->default_checklist)) {
                $taxCalendar->default_checklist = $taxCalendar->getDefaultAccountantChecklist();
            }
            if (empty($taxCalendar->task_instructions)) {
                $taxCalendar->task_instructions = $taxCalendar->getDefaultAccountantInstructions();
            }
            if (empty($taxCalendar->user_instructions)) {
                $taxCalendar->user_instructions = $taxCalendar->getDefaultUserInstructions();
            }
        });
    }

    protected function getDefaultAccountantChecklist()
    {
        $baseChecklist = [
            [
                'title' => 'Review sales invoices',
                'completed' => false,
                'notes' => 'Check all sales invoices for the period'
            ],
            [
                'title' => 'Review purchase invoices',
                'completed' => false,
                'notes' => 'Verify all purchase invoices and expenses'
            ],
            [
                'title' => 'Check VAT rates',
                'completed' => false,
                'notes' => 'Ensure correct VAT rates are applied'
            ],
            [
                'title' => 'Verify EU transactions',
                'completed' => false,
                'notes' => 'Check all EU sales and purchases'
            ]
        ];

        if ($this->requires_payment) {
            $baseChecklist[] = [
                'title' => 'Calculate final payment amount',
                'completed' => false,
                'notes' => 'Double-check all calculations and verify the final amount'
            ];
            $baseChecklist[] = [
                'title' => 'Prepare payment details',
                'completed' => false,
                'notes' => 'Set up payment information and get approval'
            ];
        }

        $baseChecklist[] = [
            'title' => 'Submit declaration in e-Tax/e-Customs',
            'completed' => false,
            'notes' => 'Submit using the correct form code'
        ];

        $baseChecklist[] = [
            'title' => 'Save confirmation and documentation',
            'completed' => false,
            'notes' => 'Store all relevant documentation and confirmation numbers'
        ];

        return $baseChecklist;
    }

    protected function getDefaultAccountantInstructions()
    {
        $instructions = [
            "Tax Calendar: " . $this->name,
            "Form Code: " . $this->form_code,
            "Frequency: " . ucfirst($this->frequency),
            "",
            "Context:",
        ];

        // Add form-specific context
        switch ($this->form_code) {
            case 'KMD':
                $instructions[] = "This is a VAT Return declaration that must be submitted monthly. It includes:";
                $instructions[] = "- Regular VAT transactions (20%, 9%, 0%)";
                $instructions[] = "- EU sales and purchases";
                $instructions[] = "- Reverse charge VAT";
                $instructions[] = "- Special scheme transactions";
                break;
            case 'TSD':
                $instructions[] = "This is a Tax and Social Tax Return that includes:";
                $instructions[] = "- Salary payments and taxes";
                $instructions[] = "- Benefits and fringe benefits";
                $instructions[] = "- Withholding tax obligations";
                break;
            // Add more form types as needed
        }

        $instructions[] = "";
        $instructions[] = "Submission Process:";
        $instructions[] = "1. Log in to e-MTA";
        $instructions[] = "2. Navigate to: Returns and Obligations → " . $this->form_code;
        $instructions[] = "3. Select period: " . $this->getFormattedPeriod();
        
        if ($this->form_code === 'KMD') {
            $instructions[] = "4. Fill in form KMD sections:";
            $instructions[] = "   - Part I: Regular VAT transactions";
            $instructions[] = "   - Part II: EU transactions";
            $instructions[] = "5. Check if KMD INF is required";
            $instructions[] = "   - Required if B2B transactions exceed €1000";
            $instructions[] = "   - Include all EU transactions";
        }

        if ($this->requires_payment) {
            $instructions[] = "";
            $instructions[] = "Payment Details:";
            $instructions[] = "- Due by: " . $this->payment_due_day . "th of the month";
            $instructions[] = "- Reference number format: Company reg. number + period";
            $instructions[] = "- Bank account: Tax authority account";
        }

        return implode("\n", $instructions);
    }

    protected function getDefaultUserInstructions()
    {
        $instructions = [
            "Tax Calendar: " . $this->name,
            "Form: " . $this->form_code,
            "Frequency: " . ucfirst($this->frequency),
            "",
            "What is this?",
        ];

        // Add form-specific explanation for users
        switch ($this->form_code) {
            case 'KMD':
                $instructions[] = "This is your monthly VAT (käibemaks) declaration. It includes:";
                $instructions[] = "- All your sales with VAT";
                $instructions[] = "- All your purchases with VAT";
                $instructions[] = "- Any EU transactions";
                $instructions[] = "- Special transactions (if applicable)";
                break;
            case 'TSD':
                $instructions[] = "This is your monthly tax return for:";
                $instructions[] = "- Employee salaries and taxes";
                $instructions[] = "- Benefits provided to employees";
                $instructions[] = "- Other payments subject to tax";
                break;
            // Add more form types as needed
        }

        $instructions[] = "";
        $instructions[] = "Required Documents:";
        
        // Add form-specific document requirements
        switch ($this->form_code) {
            case 'KMD':
                $instructions[] = "1. Sales Documents:";
                $instructions[] = "   - All sales invoices for " . $this->getFormattedPeriod();
                $instructions[] = "   - EU sales invoices (if any)";
                $instructions[] = "   - Credit notes issued";
                $instructions[] = "";
                $instructions[] = "2. Purchase Documents:";
                $instructions[] = "   - All purchase invoices";
                $instructions[] = "   - EU purchase invoices (if any)";
                $instructions[] = "   - Credit notes received";
                break;
            case 'TSD':
                $instructions[] = "1. Payroll Documents:";
                $instructions[] = "   - Salary calculations";
                $instructions[] = "   - Benefit statements";
                $instructions[] = "   - Contracts for new employees";
                break;
        }

        $instructions[] = "";
        $instructions[] = "Deadlines:";
        $instructions[] = "- Submit documents by: 3 days before " . $this->due_day . "th";
        if ($this->requires_payment) {
            $instructions[] = "- Payment due by: " . $this->payment_due_day . "th";
        }

        $instructions[] = "";
        $instructions[] = "How to Submit:";
        $instructions[] = "1. Organize documents by type";
        $instructions[] = "2. Upload to portal or email to accountant";
        $instructions[] = "3. Keep originals for your records";
        $instructions[] = "4. Note any special cases or changes";

        return implode("\n", $instructions);
    }

    protected function getFormattedPeriod()
    {
        $month = now()->format('F');
        switch ($this->frequency) {
            case 'monthly':
                return $month;
            case 'quarterly':
                return "Q" . ceil(now()->month / 3) . " " . now()->year;
            case 'annual':
                return now()->year;
            default:
                return $month;
        }
    }

    public function getDefaultUserChecklist()
    {
        $baseChecklist = [
            [
                'title' => 'Provide all sales documents',
                'completed' => false,
                'notes' => 'Upload or send all sales invoices for the period'
            ],
            [
                'title' => 'Provide all purchase documents',
                'completed' => false,
                'notes' => 'Upload or send all purchase invoices and receipts'
            ],
            [
                'title' => 'Confirm bank statements',
                'completed' => false,
                'notes' => 'Ensure all bank statements are available'
            ]
        ];

        // Add frequency-specific items
        switch ($this->frequency) {
            case 'monthly':
                $baseChecklist[] = [
                    'title' => 'Review monthly summary',
                    'completed' => false,
                    'notes' => 'Check if all transactions are included'
                ];
                break;
            case 'quarterly':
                $baseChecklist[] = [
                    'title' => 'Review quarterly overview',
                    'completed' => false,
                    'notes' => 'Verify all major transactions are recorded'
                ];
                break;
            case 'annual':
                $baseChecklist[] = [
                    'title' => 'Review annual summary',
                    'completed' => false,
                    'notes' => 'Check annual totals and major transactions'
                ];
                break;
        }

        if ($this->requires_payment) {
            $baseChecklist[] = [
                'title' => 'Verify payment amount',
                'completed' => false,
                'notes' => 'Check the calculated payment amount'
            ];
            $baseChecklist[] = [
                'title' => 'Ensure funds are available',
                'completed' => false,
                'notes' => 'Make sure sufficient funds are in the account'
            ];
        }

        return $baseChecklist;
    }

    // Relationships
    public function tasks()
    {
        return $this->hasMany(TaxCalendarTask::class);
    }

    // Get tasks for a specific company
    public function tasksForCompany($companyId)
    {
        return $this->tasks()->where('company_id', $companyId);
    }

    // Create a new task for a company
    public function createTaskForCompany($companyId, $userId, $dueDate = null)
    {
        $dueDate = $dueDate ?? $this->next_deadline;
        
        if (!$dueDate) {
            return null;
        }

        return $this->tasks()->create([
            'company_id' => $companyId,
            'user_id' => $userId,
            'due_date' => $dueDate,
            'checklist' => $this->default_checklist ?? [],
            'user_checklist' => collect($this->default_checklist ?? [])->map(function ($item) {
                return [
                    'title' => $item['title'],
                    'completed' => false,
                    'notes' => $item['notes'] ?? null
                ];
            })->toArray(),
            'status' => 'pending'
        ]);
    }

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

    public function scopeAutoCreateEnabled($query)
    {
        return $query->where('auto_create_tasks', true);
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

    // Get reminder date based on reminder_days_before
    public function getReminderDateAttribute()
    {
        $deadline = $this->next_deadline;
        if (!$deadline) {
            return null;
        }

        return $deadline->copy()->subDays($this->reminder_days_before)->startOfDay();
    }
}
