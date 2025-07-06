<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceEmailLog extends Model
{
    protected $fillable = [
        'invoice_id',
        'user_id',
        'recipient_email',
        'cc_email',
        'subject',
        'message',
        'status',
        'resend_id',
        'error_message',
        'sent_at'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
