<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Cashier\Billable;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'is_accountant',
        'onboarding_completed',
        'country_id',
        'onboarding_step',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
        'is_accountant' => 'boolean',
        'onboarding_completed' => 'boolean',
    ];

    public function folders(): BelongsToMany
    {
        return $this->belongsToMany(Folder::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'uploaded_by');
    }

    public function accessibleFolders(): Collection
    {
        return Folder::where('is_public', true)
            ->orWhereHas('users', function ($query) {
                $query->where('user_id', $this->id);
            })
            ->whereNull('parent_id') // Only return root folders
            ->get();
    }

    /**
     * Get the next billing date for the user's subscription.
     * 
     * @param string $subscription The subscription name
     * @return \Carbon\Carbon|null
     */
    public function nextBillingDate($subscription = 'default')
    {
        $subscription = $this->subscription($subscription);
        
        if (!$subscription) {
            return null;
        }
        
        // If on trial, return trial end date
        if ($subscription->onTrial()) {
            return $subscription->trial_ends_at;
        }
        
        // If canceled, return the end date
        if ($subscription->canceled()) {
            return $subscription->ends_at;
        }
        
        // Otherwise calculate next billing date based on creation date
        $createdAt = $subscription->created_at;
        $today = now();
        
        // Find the next billing date by adding months until we find a future date
        $nextBillingDate = $createdAt->copy();
        while ($nextBillingDate->lte($today)) {
            $nextBillingDate->addMonth();
        }
        
        return $nextBillingDate;
    }

    /**
     * Get all activity logs for the user
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Log a login event
     */
    public function logLogin()
    {
        return ActivityLogService::log(
            'user_login',
            'User logged into the system',
            $this
        );
    }

    /**
     * Log a logout event
     */
    public function logLogout()
    {
        return ActivityLogService::log(
            'user_logout',
            'User logged out of the system',
            $this
        );
    }

    /**
     * Log a profile update event
     * 
     * @param array $changes The fields that were changed
     */
    public function logProfileUpdate(array $changes)
    {
        $fieldsChanged = array_keys($changes);
        
        return ActivityLogService::log(
            'profile_update',
            'User updated their profile information: ' . implode(', ', $fieldsChanged),
            $this,
            ['changes' => $changes]
        );
    }

    /**
     * Log an email verification event
     * 
     * @param bool $manual Whether the verification was done manually by an admin
     */
    public function logEmailVerification($manual = false)
    {
        $description = $manual 
            ? 'User email was manually verified by an administrator'
            : 'User verified their email address';
            
        return ActivityLogService::log(
            'email_verified',
            $description,
            $this,
            ['manual' => $manual]
        );
    }

    /**
     * Check if the user has an active subscription by directly querying the database
     * Useful for debugging subscription issues
     * 
     * The method extends subscription access until the end of the month in which
     * the subscription expires, providing users with access for the entire month
     * even if their subscription technically ended mid-month.
     * 
     * @param string $name The subscription name
     * @return bool
     */
    public function hasActiveSubscription($name = 'default'): bool
    {
        // Check if the subscriptions table exists
        if (!Schema::hasTable('subscriptions')) {
            return true; // If no subscription table, assume active for development
        }
        
        try {
            // Check the database directly to avoid any Cashier caching issues
            $subscription = $this->subscriptions()
                ->where('name', $name)
                ->first();
                
            // If no subscription found, return false
            if (is_null($subscription)) {
                return false;
            }
            
            // If subscription doesn't have an end date, it's active
            if (is_null($subscription->ends_at)) {
                return true;
            }
            
            // If subscription ends_at is in the future, it's active
            if ($subscription->ends_at > now()) {
                return true;
            }
            
            // Extension feature: If subscription ended this month, extend until end of month
            if ($subscription->ends_at->isSameMonth(now())) {
                return true;
            }
                
            // For debugging, log when subscription status is unexpected
            $cashierCheck = $this->subscribed($name);
            $manualCheck = false; // We already determined it's false at this point
            
            if ($cashierCheck != $manualCheck) {
                \Log::warning("Subscription check mismatch for user {$this->id}", [
                    'manual_check' => $manualCheck,
                    'cashier_check' => $cashierCheck,
                    'subscription' => $subscription,
                    'ends_at' => $subscription->ends_at,
                    'now' => now(),
                    'same_month' => $subscription->ends_at->isSameMonth(now())
                ]);
            }
            
            return false;
        } catch (\Exception $e) {
            \Log::error("Error checking subscription: " . $e->getMessage());
            return true; // Default to true on error to prevent blocking access
        }
    }

    /**
     * Get the country that the user is in.
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get the companies associated with the user.
     */
    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Get the user's current company.
     */
    public function currentCompany()
    {
        return $this->belongsTo(Company::class, 'current_company_id');
    }

    /**
     * Get the users assigned to this accountant.
     */
    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'accountant_user', 'accountant_id', 'user_id');
    }

    /**
     * Get the accountants this user is assigned to.
     */
    public function accountants()
    {
        return $this->belongsToMany(User::class, 'accountant_user', 'user_id', 'accountant_id');
    }

    /**
     * Get the accountants this user is assigned to (alias for accountants).
     */
    public function assignedAccountants()
    {
        return $this->belongsToMany(User::class, 'accountant_user', 'user_id', 'accountant_id');
    }

    /**
     * Get the companies assigned to this accountant.
     */
    public function assignedCompanies()
    {
        return $this->belongsToMany(Company::class, 'accountant_company', 'accountant_id', 'company_id');
    }

    /**
     * Get the clients that belong to the user.
     */
    public function userClients()
    {
        return $this->hasMany(UserClient::class);
    }
}
