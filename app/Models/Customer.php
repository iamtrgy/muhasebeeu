<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'vat_number',
        'company_reg_number',
        'country',
        'address',
        'user_id',
    ];

    /**
     * Get the user that owns the customer.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the invoices for the customer.
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'client_id');
    }
    
    /**
     * Get formatted VAT number with country.
     */
    public function getFormattedVatNumberAttribute()
    {
        if (empty($this->vat_number)) {
            return null;
        }
        
        // If VAT number already has country prefix, return as is
        if (preg_match('/^[A-Z]{2}/', $this->vat_number)) {
            return $this->vat_number;
        }
        
        // Otherwise, add country prefix if country is available
        if (!empty($this->country)) {
            return strtoupper($this->country) . $this->vat_number;
        }
        
        return $this->vat_number;
    }
}
