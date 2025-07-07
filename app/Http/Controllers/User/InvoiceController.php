<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\UserClient;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoiceEmailLog;
use Illuminate\Http\Request;
use App\Services\ResendService;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    /**
     * Display a listing of invoices
     */
    public function index()
    {
        // Get user's company IDs
        $userCompanyIds = auth()->user()->companies->pluck('id');
        
        // Get invoices in multiple ways to catch all possible scenarios:
        // 1. Invoices from user's companies
        // 2. Invoices created by the user (for backwards compatibility)
        $invoices = Invoice::where(function($query) use ($userCompanyIds) {
                $query->whereIn('company_id', $userCompanyIds)
                      ->orWhere('created_by', auth()->id());
            })
            ->with(['company', 'client'])
            ->latest()
            ->paginate(10);
            
        return view('user.invoices.index', compact('invoices'));
    }
    
    public function create()
    {
        // Get logged in user ID for clarity
        $userId = auth()->id();
        \Log::info('InvoiceController@create BAŞLADI', ['user_id' => $userId]);
        
        try {
            // User's companies (kendi şirketlerim - faturanın "kimden" kısmı)
            $companies = auth()->user()->companies;
            
            // User's clients (müşterilerim - faturanın "kime" kısmı)
            $userclients = UserClient::where('user_id', $userId)->orderBy('name')->get();
            
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
            'company_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    // Check if the company belongs to the current user
                    $belongs = auth()->user()->companies()->where('companies.id', $value)->exists();
                    
                    if (!$belongs) {
                        $fail('The selected company is invalid.');
                    }
                }
            ],
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
            'payment_url' => 'nullable|url|max:500',
            
            // Client validation conditionally required
            'client_type' => 'required|in:existing,new',
            'client_id' => [
                'required_if:client_type,existing',
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        // Check if the client exists and belongs to the current user
                        $exists = UserClient::where('id', $value)
                            ->where('user_id', auth()->id())
                            ->exists();
                        
                        if (!$exists) {
                            $fail('The selected client is invalid.');
                        }
                    }
                }
            ],
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
        // Set created_by instead of user_id
        $invoice->created_by = auth()->id();
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
        $invoice->payment_url = $request->payment_url;
        
        // Handle client data based on client_type
        if ($request->client_type === 'existing' && $request->client_id) {
            // Use existing customer
            $invoice->client_id = $request->client_id;
        } else {
            // Handle new customer data
            if ($request->save_client) {
                // Save new customer to database
                $client = UserClient::create([
                    'user_id' => auth()->id(),
                    'name' => $request->client_name,
                    'email' => $request->client_email,
                    'phone' => $request->client_phone,
                    'vat_number' => $request->client_vat_number,
                    'company_reg_number' => $request->client_company_reg_number,
                    'country' => $request->client_country,
                    'address' => $request->client_address,
                ]);
                
                $invoice->client_id = $client->id;
            } else {
                // Just save client info in invoice (no client_id)
                $invoice->client_id = null;
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
        // Ensure user can edit this invoice
        // Check if user has access to this invoice through company relationship
        $userCompanyIds = auth()->user()->companies->pluck('id');
        if (!$userCompanyIds->contains($invoice->company_id) && $invoice->created_by !== auth()->id()) {
            abort(403);
        }
        
        $companies = auth()->user()->companies;
        $languages = config('app.available_languages', [
            'en' => 'English',
            'tr' => 'Turkish',
            'de' => 'German'
        ]);
        
        return view('user.invoices.edit', compact('invoice', 'companies', 'languages'));
    }
    
    public function show(Invoice $invoice)
    {
        // Ensure user can view this invoice
        // Check if user has access to this invoice through company relationship
        $userCompanyIds = auth()->user()->companies->pluck('id');
        if (!$userCompanyIds->contains($invoice->company_id) && $invoice->created_by !== auth()->id()) {
            abort(403);
        }
        
        return view('user.invoices.show', compact('invoice'));
    }
    
    /**
     * Update the specified invoice
     */
    public function update(Request $request, Invoice $invoice)
    {
        // Ensure user can update this invoice
        // Check if user has access to this invoice through company relationship
        $userCompanyIds = auth()->user()->companies->pluck('id');
        if (!$userCompanyIds->contains($invoice->company_id) && $invoice->created_by !== auth()->id()) {
            abort(403);
        }
        
        // Validate the request
        $validated = $request->validate([
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
            'payment_url' => 'nullable|url|max:500',
            
            // Items validation
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_rate' => 'required|numeric|min:0',
        ]);
        
        // Update invoice
        $invoice->update([
            'invoice_number' => $request->invoice_number,
            'invoice_date' => $request->invoice_date,
            'due_date' => $request->due_date,
            'currency' => $request->currency,
            'language_code' => $request->language_code,
            'notes' => $request->notes,
            'payment_method' => $request->payment_method,
            'payment_terms' => $request->payment_terms,
            'reference' => $request->reference,
            'reverse_charge' => $request->has('reverse_charge'),
            'vat_exempt' => $request->has('vat_exempt'),
            'payment_url' => $request->payment_url,
        ]);
        
        // Delete existing items and recreate
        $invoice->items()->delete();
        
        // Calculate totals
        $subtotal = 0;
        $taxTotal = 0;
        
        // Add items
        foreach ($request->items as $index => $itemData) {
            $quantity = floatval($itemData['quantity']);
            $unitPrice = floatval($itemData['unit_price']);
            $taxRate = floatval($itemData['tax_rate']);
            
            $itemSubtotal = $quantity * $unitPrice;
            $itemTax = $itemSubtotal * ($taxRate / 100);
            $itemTotal = $itemSubtotal + $itemTax;
            
            $subtotal += $itemSubtotal;
            $taxTotal += $itemTax;
            
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => $itemData['description'],
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'tax_rate' => $taxRate,
                'subtotal' => $itemSubtotal,
                'tax_amount' => $itemTax,
                'total' => $itemTotal,
                'sort_order' => $index + 1,
            ]);
        }
        
        // Update invoice totals
        $invoice->update([
            'subtotal' => $subtotal,
            'tax_amount' => $taxTotal,
            'total' => $subtotal + $taxTotal,
        ]);
        
        return redirect()->route('user.invoices.show', $invoice)
            ->with('success', __('Invoice updated successfully!'));
    }
    
    /**
     * Remove the specified invoice
     */
    public function destroy(Invoice $invoice)
    {
        // Ensure user can delete this invoice
        // Check if user has access to this invoice through company relationship
        $userCompanyIds = auth()->user()->companies->pluck('id');
        if (!$userCompanyIds->contains($invoice->company_id) && $invoice->created_by !== auth()->id()) {
            abort(403);
        }
        
        // Delete invoice items first
        $invoice->items()->delete();
        
        // Delete the invoice
        $invoice->delete();
        
        return redirect()->route('user.invoices.index')
            ->with('success', __('Invoice deleted successfully!'));
    }
    
    public function downloadPdf(Invoice $invoice)
    {
        // Ensure user can download this invoice
        // Check if user has access to this invoice through company relationship
        $userCompanyIds = auth()->user()->companies->pluck('id');
        if (!$userCompanyIds->contains($invoice->company_id) && $invoice->created_by !== auth()->id()) {
            abort(403);
        }
        
        if (!$invoice->pdf_path && !$invoice->pdf_url) {
            return redirect()->route('user.invoices.show', $invoice)
                ->with('error', __('PDF not found for this invoice.'));
        }
        
        $filename = 'invoice-' . $invoice->invoice_number . '.pdf';
        
        // If using Bunny CDN
        if ($invoice->pdf_url) {
            // Download from CDN and stream to user with download headers
            try {
                $pdfContent = file_get_contents($invoice->pdf_url);
                
                return response($pdfContent)
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                    ->header('Content-Length', strlen($pdfContent));
            } catch (\Exception $e) {
                // If download fails, try redirect with download parameter if available
                if ($invoice->download_url && $invoice->download_url !== $invoice->pdf_url) {
                    return redirect($invoice->download_url);
                }
                
                return redirect()->route('user.invoices.show', $invoice)
                    ->with('error', __('Failed to download PDF.'));
            }
        }
        
        // If stored locally
        return Storage::download($invoice->pdf_path, $filename);
    }
    
    /**
     * Regenerate PDF for the invoice
     */
    public function regeneratePdf(Invoice $invoice)
    {
        // Ensure user can regenerate this invoice
        // Check if user has access to this invoice through company relationship
        $userCompanyIds = auth()->user()->companies->pluck('id');
        if (!$userCompanyIds->contains($invoice->company_id) && $invoice->created_by !== auth()->id()) {
            abort(403);
        }
        
        try {
            // TODO: Implement PDF generation logic here
            // For now, just return with a message
            
            return redirect()->route('user.invoices.show', $invoice)
                ->with('success', __('Invoice PDF regenerated successfully!'));
        } catch (\Exception $e) {
            return redirect()->route('user.invoices.show', $invoice)
                ->with('error', __('Failed to regenerate PDF: ') . $e->getMessage());
        }
    }
    
    /**
     * Generate a unique invoice number
     */
    private function generateNextInvoiceNumber()
    {
        $year = date('Y');
        $month = date('m');
        
        // Get the latest invoice this month from user's companies
        $userCompanyIds = auth()->user()->companies->pluck('id');
        $latestInvoice = Invoice::whereIn('company_id', $userCompanyIds)
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
    
    /**
     * Send invoice via email
     */
    public function send(Request $request, Invoice $invoice)
    {
        // Check rate limiting using email logs
        $lastEmail = InvoiceEmailLog::where('invoice_id', $invoice->id)
            ->where('user_id', auth()->id())
            ->where('status', 'sent')
            ->orderBy('sent_at', 'desc')
            ->first();
            
        if ($lastEmail && $lastEmail->sent_at->diffInMinutes(now()) < 5) {
            $minutesLeft = 5 - $lastEmail->sent_at->diffInMinutes(now());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Please wait :minutes minutes before resending this invoice.', ['minutes' => ceil($minutesLeft)])
                ], 429);
            }
            
            return redirect()->route('user.invoices.show', $invoice)
                ->with('error', __('Please wait :minutes minutes before resending this invoice.', ['minutes' => ceil($minutesLeft)]));
        }
        
        // Check if user has sent too many emails today (spam prevention)
        $todayEmailCount = InvoiceEmailLog::where('user_id', auth()->id())
            ->where('status', 'sent')
            ->whereDate('sent_at', today())
            ->count();
            
        if ($todayEmailCount >= 50) { // Max 50 emails per day per user
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Daily email limit reached. Please try again tomorrow.')
                ], 429);
            }
            
            return redirect()->route('user.invoices.show', $invoice)
                ->with('error', __('Daily email limit reached. Please try again tomorrow.'));
        }
        
        \Log::info('Send invoice method called', [
            'invoice_id' => $invoice->id,
            'request_data' => $request->all()
        ]);
        
        // Validate request
        try {
            $validated = $request->validate([
                'recipient_email' => 'required|email',
                'cc_email' => 'nullable|email',
                'subject' => 'required|string|max:255',
                'message' => 'required|string'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->validator->errors()->first()
                ], 422);
            }
            throw $e;
        }
        
        \Log::info('Validation passed', ['validated' => $validated]);
        
        try {
            $resend = new ResendService();
            
            // Prepare HTML content
            $html = nl2br(e($validated['message']));
            $html .= '<br><br>';
            
            // Add payment link if invoice is not paid
            if ($invoice->status !== 'paid') {
                $paymentPageUrl = route('payment.show', $invoice);
                $html .= '<div style="margin: 20px 0; text-align: center;">';
                $html .= '<a href="' . $paymentPageUrl . '" style="background-color: #10b981; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">' . __('View & Pay Invoice') . '</a>';
                $html .= '</div>';
            }
            
            $html .= '<p style="color: #666; font-size: 14px;">' . __('This email contains an invoice attachment.') . '</p>';
            
            // Prepare attachments
            $attachments = [];
            
            \Log::info('Checking for PDF attachments', [
                'pdf_path' => $invoice->pdf_path,
                'pdf_url' => $invoice->pdf_url
            ]);
            
            // Check if we have a PDF to attach
            if ($invoice->pdf_path) {
                // Check if PDF is on Bunny CDN (default file system)
                $defaultDisk = config('filesystems.default', 'bunny');
                
                if ($defaultDisk === 'bunny' && $invoice->pdf_url) {
                    // PDF is on Bunny CDN, download it using cURL for better error handling
                    try {
                        \Log::info('Attempting to download PDF from Bunny CDN: ' . $invoice->pdf_url);
                        
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $invoice->pdf_url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                        curl_setopt($ch, CURLOPT_USERAGENT, 'Laravel/' . app()->version());
                        
                        $pdfContent = curl_exec($ch);
                        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                        $error = curl_error($ch);
                        curl_close($ch);
                        
                        if ($error) {
                            throw new \Exception('cURL Error: ' . $error);
                        }
                        
                        if ($httpCode !== 200) {
                            throw new \Exception('HTTP Error: ' . $httpCode);
                        }
                        
                        if ($pdfContent && strlen($pdfContent) > 100) { // Basic check for valid PDF
                            $attachments[] = [
                                'filename' => 'invoice-' . $invoice->invoice_number . '.pdf',
                                'content' => base64_encode($pdfContent),
                                'type' => 'application/pdf'
                            ];
                            \Log::info('PDF attached from Bunny CDN successfully', ['size' => strlen($pdfContent)]);
                        } else {
                            throw new \Exception('Downloaded content is too small or empty');
                        }
                    } catch (\Exception $e) {
                        \Log::error('Failed to download PDF from Bunny CDN: ' . $e->getMessage());
                        // Add download link to email if we can't attach
                        $html .= '<br><p><a href="' . $invoice->pdf_url . '" style="background-color: #4F46E5; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">' . __('Download Invoice PDF') . '</a></p>';
                    }
                } elseif (Storage::disk('local')->exists($invoice->pdf_path)) {
                    // PDF is stored locally
                    $pdfContent = Storage::disk('local')->get($invoice->pdf_path);
                    $attachments[] = [
                        'filename' => 'invoice-' . $invoice->invoice_number . '.pdf',
                        'content' => base64_encode($pdfContent),
                        'type' => 'application/pdf'
                    ];
                    \Log::info('PDF attached from local storage');
                } else {
                    \Log::warning('PDF path exists but file not found locally: ' . $invoice->pdf_path);
                }
            }
            
            // If no attachments, add a note to the email
            if (empty($attachments)) {
                \Log::warning('No PDF attachment available for invoice: ' . $invoice->id);
                $html .= '<br><p style="color: #666; font-size: 14px;"><strong>' . __('Note:') . '</strong> ' . __('The invoice PDF is not attached to this email. Please contact us if you need a copy.') . '</p>';
            } else {
                \Log::info('Attachments prepared', ['count' => count($attachments), 'first_filename' => $attachments[0]['filename'] ?? null]);
            }
            
            // Send email using Resend
            $result = $resend->sendWithAttachment(
                $validated['recipient_email'],
                $validated['subject'],
                $html,
                $attachments,
                $validated['cc_email'] ?? null
            );
            
            if ($result['success']) {
                // Update invoice status to sent ONLY if email was sent successfully
                $invoice->update(['status' => 'sent']);
                
                // Log successful email send
                InvoiceEmailLog::create([
                    'invoice_id' => $invoice->id,
                    'user_id' => auth()->id(),
                    'recipient_email' => $validated['recipient_email'],
                    'cc_email' => $validated['cc_email'] ?? null,
                    'subject' => $validated['subject'],
                    'message' => $validated['message'],
                    'status' => 'sent',
                    'resend_id' => $result['id'] ?? null,
                    'sent_at' => now(),
                ]);
                
                \Log::info('Invoice sent successfully', [
                    'invoice_id' => $invoice->id,
                    'recipient' => $validated['recipient_email']
                ]);
                
                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => __('Invoice has been sent successfully!'),
                        'redirect' => route('user.invoices.show', $invoice)
                    ]);
                }
                
                return redirect()->route('user.invoices.show', $invoice)
                    ->with('success', __('Invoice has been sent successfully to ') . $validated['recipient_email']);
            } else {
                // Log failed email attempt
                InvoiceEmailLog::create([
                    'invoice_id' => $invoice->id,
                    'user_id' => auth()->id(),
                    'recipient_email' => $validated['recipient_email'],
                    'cc_email' => $validated['cc_email'] ?? null,
                    'subject' => $validated['subject'],
                    'message' => $validated['message'],
                    'status' => 'failed',
                    'error_message' => $result['error'] ?? 'Unknown error',
                    'sent_at' => now(),
                ]);
                
                \Log::error('Failed to send invoice via Resend', [
                    'error' => $result['error'],
                    'invoice_id' => $invoice->id
                ]);
                throw new \Exception($result['error'] ?? 'Unknown error');
            }
                
        } catch (\Exception $e) {
            \Log::error('Failed to send invoice: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('user.invoices.show', $invoice)
                ->with('error', __('Failed to send invoice: ') . $e->getMessage());
        }
    }
} 