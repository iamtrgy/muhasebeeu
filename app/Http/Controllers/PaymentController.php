<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Show the public payment page for guests
     */
    public function show(Invoice $invoice)
    {
        // Load the invoice with its company and client relationships
        $invoice->load(['company', 'client', 'items']);
        
        return view('payment.show', compact('invoice'));
    }
}
