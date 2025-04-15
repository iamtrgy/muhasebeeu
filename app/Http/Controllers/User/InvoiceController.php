<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function create()
    {
        // Get logged in user ID for clarity
        $userId = auth()->id();
        \Log::info('InvoiceController@create BAŞLADI', ['user_id' => $userId]);
        
        try {
            // User's companies (kendi şirketlerim - faturanın "kimden" kısmı)
            $companies = auth()->user()->companies;
            
            // User's customers (müşterilerim - faturanın "kime" kısmı)
            // Değişken adını değiştirdik: $customers -> $userclients
            $userclients = Customer::where('user_id', $userId)->orderBy('name')->get();
            
            \Log::info('InvoiceController@create - Değişkenler oluşturuldu', [
                'user_id' => $userId,
                'companies_count' => $companies->count(),
                'userclients_count' => $userclients->count(),
                'userclients_items' => $userclients->map(function($customer) {
                    return [
                        'id' => $customer->id,
                        'name' => $customer->name
                    ];
                })->toArray(),
            ]);
            
            // Get languages from config
            $languages = config('app.available_languages', [
                'en' => 'English',
                'tr' => 'Turkish',
                'de' => 'German'
            ]);
            
            // Generate next invoice number
            $nextInvoiceNumber = $this->generateNextInvoiceNumber();
            
            \Log::info('InvoiceController@create - View oluşturuluyor');
            
            return view('user.invoices.create', 
                // Değişken adını değiştirip view'a aktarıyoruz
                compact('companies', 'userclients', 'languages', 'nextInvoiceNumber')
            );
        } catch (\Exception $e) {
            \Log::error('InvoiceController@create - HATA', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            // Existing validation rules
            'company_id' => 'required|exists:companies,id',
            'invoice_number' => 'required|string|max:50',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date',
            'currency' => 'required|string|max:3',
            'language_code' => 'required|string|max:5',
            'notes' => 'nullable|string',
            'payment_method' => 'nullable|string|max:20',
            'payment_terms' => 'nullable|string|max:20',
            'reference' => 'nullable|string|max:100',
            'reverse_charge' => 'nullable|boolean',
            'vat_exempt' => 'nullable|boolean',
            
            // Client validation conditionally required
            'client_type' => 'required|in:existing,new',
            'client_id' => 'required_if:client_type,existing|nullable|exists:customers,id',
            'client_name' => 'required_if:client_type,new|nullable|string|max:255',
            'client_email' => 'nullable|email|max:255',
            'client_vat_number' => 'nullable|string|max:50',
            'client_company_reg_number' => 'nullable|string|max:50',
            'client_country' => 'nullable|string|max:2',
            'client_address' => 'nullable|string',
            'client_phone' => 'nullable|string|max:30',
            'save_client' => 'nullable|boolean',
            
            // Items validation
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_rate' => 'required|numeric|min:0',
        ]);

        // Create new invoice
        $invoice = new Invoice();
        $invoice->user_id = auth()->id();
        $invoice->company_id = $request->company_id;
        $invoice->invoice_number = $request->invoice_number;
        $invoice->invoice_date = $request->invoice_date;
        $invoice->due_date = $request->due_date;
        $invoice->currency = $request->currency;
        $invoice->language_code = $request->language_code;
        $invoice->notes = $request->notes;
        $invoice->status = 'draft';
        $invoice->payment_method = $request->payment_method;
        $invoice->payment_terms = $request->payment_terms;
        $invoice->reference = $request->reference;
        $invoice->reverse_charge = $request->has('reverse_charge');
        $invoice->vat_exempt = $request->has('vat_exempt');
        
        // Handle client data based on client_type
        if ($request->client_type === 'existing') {
            // Use existing customer
            $invoice->client_id = $request->client_id;
        } else {
            // Handle new customer data
            if ($request->save_client) {
                // Save new customer to database
                $customer = Customer::create([
                    'user_id' => auth()->id(),
                    'name' => $request->client_name,
                    'email' => $request->client_email,
                    'phone' => $request->client_phone,
                    'vat_number' => $request->client_vat_number,
                    'company_reg_number' => $request->client_company_reg_number,
                    'country' => $request->client_country,
                    'address' => $request->client_address,
                ]);
                
                $invoice->client_id = $customer->id;
            } else {
                // Just save client info in invoice
                $invoice->client_name = $request->client_name;
                $invoice->client_email = $request->client_email;
                $invoice->client_phone = $request->client_phone;
                $invoice->client_vat_number = $request->client_vat_number;
                $invoice->client_company_reg_number = $request->client_company_reg_number;
                $invoice->client_country = $request->client_country;
                $invoice->client_address = $request->client_address;
            }
        }
        
        // Calculate totals
        $subtotal = 0;
        $tax_total = 0;
        
        foreach ($request->items as $item) {
            $item_subtotal = $item['quantity'] * $item['unit_price'];
            $item_tax = $item_subtotal * ($item['tax_rate'] / 100);
            
            $subtotal += $item_subtotal;
            $tax_total += $item_tax;
        }
        
        $invoice->subtotal = $subtotal;
        $invoice->tax_amount = $tax_total;
        $invoice->total = $subtotal + $tax_total;
        
        $invoice->save();
        
        // Save invoice items
        foreach ($request->items as $item) {
            $invoiceItem = new InvoiceItem();
            $invoiceItem->invoice_id = $invoice->id;
            $invoiceItem->description = $item['description'];
            $invoiceItem->quantity = $item['quantity'];
            $invoiceItem->unit_price = $item['unit_price'];
            $invoiceItem->tax_rate = $item['tax_rate'];
            
            $item_subtotal = $item['quantity'] * $item['unit_price'];
            $item_tax = $item_subtotal * ($item['tax_rate'] / 100);
            
            $invoiceItem->subtotal = $item_subtotal;
            $invoiceItem->tax_amount = $item_tax;
            $invoiceItem->total = $item_subtotal + $item_tax;
            
            $invoiceItem->save();
        }
        
        // Generate PDF and save to storage
        // $this->generatePdf($invoice);
        
        return redirect()->route('user.invoices.show', $invoice)
            ->with('success', 'Invoice created successfully.');
    }

    public function edit(Invoice $invoice)
    {
        // Implementation...
    }
    
    public function show(Invoice $invoice)
    {
        // Implementation...
    }
    
    public function downloadPdf(Invoice $invoice)
    {
        // Implementation...
    }
    
    /**
     * Generate a unique invoice number
     */
    private function generateNextInvoiceNumber()
    {
        $year = date('Y');
        $month = date('m');
        
        // Get the latest invoice this month
        $latestInvoice = Invoice::where('user_id', auth()->id())
            ->whereYear('invoice_date', $year)
            ->whereMonth('invoice_date', $month)
            ->orderBy('id', 'desc')
            ->first();
        
        if ($latestInvoice) {
            // Extract the numeric part
            $lastNumber = 0;
            if (preg_match('/(\d+)$/', $latestInvoice->invoice_number, $matches)) {
                $lastNumber = intval($matches[1]);
            }
            
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }
        
        // Format: INV-YYYY-MM-XXXX
        return 'INV-' . $year . '-' . $month . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
} 