<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BankTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_id',
        'company_id',
        'transaction_date',
        'description',
        'amount',
        'currency',
        'type',
        'balance',
        'reference_number',
        'category',
        'matched_invoice_id',
        'match_confidence',
        'match_status',
        'notes',
        'status',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
        'balance' => 'decimal:2',
        'match_confidence' => 'integer',
    ];

    /**
     * Get the file (bank statement) this transaction belongs to
     */
    public function file()
    {
        return $this->belongsTo(File::class);
    }

    /**
     * Get the company this transaction belongs to
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the matched invoice (if any)
     */
    public function matchedInvoice()
    {
        return $this->belongsTo(Invoice::class, 'matched_invoice_id');
    }

    /**
     * Scope for unmatched transactions
     */
    public function scopeUnmatched($query)
    {
        return $query->where('match_status', 'unmatched');
    }

    /**
     * Scope for matched transactions
     */
    public function scopeMatched($query)
    {
        return $query->whereIn('match_status', ['auto_matched', 'manual_matched']);
    }

    /**
     * Scope for debit transactions
     */
    public function scopeDebits($query)
    {
        return $query->where('type', 'debit');
    }

    /**
     * Scope for credit transactions
     */
    public function scopeCredits($query)
    {
        return $query->where('type', 'credit');
    }

    /**
     * Get formatted amount with currency
     */
    public function getFormattedAmountAttribute()
    {
        $symbol = $this->type === 'debit' ? '-' : '+';
        return $symbol . ' ' . number_format($this->amount, 2) . ' ' . $this->currency;
    }

    /**
     * Check if transaction can be matched
     */
    public function canBeMatched()
    {
        return in_array($this->match_status, ['unmatched', 'auto_matched']);
    }
}
