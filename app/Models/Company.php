<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'country_id',
        'user_id',
        'tax_number',
        'vat_number',
        'address',
        'phone',
        'email',
        'is_own_company',
        'foundation_date'
    ];

    protected $casts = [
        'is_own_company' => 'boolean',
        'foundation_date' => 'date',
    ];

    /**
     * Get the country that the company is in.
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get the user that owns the company.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the accountants assigned to this company.
     */
    public function accountants()
    {
        return $this->belongsToMany(User::class, 'accountant_company', 'company_id', 'accountant_id');
    }

    /**
     * Get the folders associated with the company.
     */
    public function folders(): HasMany
    {
        return $this->hasMany(Folder::class);
    }

    /**
     * Get the users associated with the company.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'company_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Get the users who have this as their current company.
     */
    public function currentUsers()
    {
        return $this->hasMany(User::class, 'current_company_id');
    }
}
