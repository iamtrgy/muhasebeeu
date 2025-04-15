<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Company;
use App\Models\UserClient;
use App\Services\Invoice\InvoicePdfGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\File;

class InvoiceController extends Controller
{
    protected $pdfGenerator;
    
    public function __construct(InvoicePdfGenerator $pdfGenerator)
    {
        $this->pdfGenerator = $pdfGenerator;
    }
    
    /**
     * Fatura listesini gösterir
     */
    public function index()
    {
        $invoices = Invoice::where('created_by', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('user.invoices.index', compact('invoices'));
    }
    
    /**
     * Yeni fatura oluşturma formunu gösterir
     */
    public function create()
    {
        $companies = Company::where('user_id', Auth::id())->get();
        $clients = Company::where('user_id', Auth::id())
            ->where('is_own_company', false)
            ->get();
            
        // Müşterilerimizi de alalım
        $userclients = UserClient::where('user_id', Auth::id())->get();
            
        // Desteklenen diller
        $languages = [
            'tr' => 'Türkçe',
            'en' => 'İngilizce',
            'de' => 'Almanca'
        ];
        
        // Son fatura numarasını bul ve bir sonraki numarayı öner
        $lastInvoice = Invoice::where('created_by', Auth::id())
            ->orderBy('invoice_number', 'desc')
            ->first();
            
        $nextInvoiceNumber = $this->generateNextInvoiceNumber($lastInvoice);
        
        return view('user.invoices.create', compact('companies', 'clients', 'languages', 'nextInvoiceNumber', 'userclients'));
    }
    
    /**
     * Yeni faturayı kaydeder
     */
    public function store(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'invoice_number' => 'required|string|max:50|unique:invoices,invoice_number',
            'invoice_date' => 'required|date',
            'language_code' => 'required|string|in:tr,en,de',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_rate' => 'required|numeric|min:0'
        ]);
        
        // Eğer client_id gönderildiyse, UserClient tablosunda varlığını kontrol et
        if ($request->client_id) {
            $request->validate([
                'client_id' => 'exists:user_clients,id'
            ]);
            
            // Ayrıca bu client'ın gerçekten kullanıcıya ait olduğunu kontrol et
            $clientExists = UserClient::where('id', $request->client_id)
                ->where('user_id', Auth::id())
                ->exists();
                
            if (!$clientExists) {
                return back()->withInput()->withErrors(['error' => 'Selected client is not valid or does not belong to you.']);
            }
        }
        
        // First handle new client saving OUTSIDE of the main transaction
        $newClientId = null;
        
        if (!$request->client_id && $request->save_client && $request->client_name) {
            try {
                // Create new client in its own transaction
                DB::beginTransaction();
                $newClient = UserClient::create([
                    'user_id' => Auth::id(),
                    'name' => $request->client_name,
                    'email' => $request->client_email,
                    'phone' => $request->client_phone,
                    'vat_number' => $request->vat_number,
                    'company_reg_number' => $request->client_company_reg_number,
                    'country' => $request->client_country,
                    'address' => $request->client_address,
                ]);
                DB::commit();
                
                if ($newClient && $newClient->id) {
                    $newClientId = $newClient->id;
                }
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Client save error during invoice creation: ' . $e->getMessage());
                // Continue with the invoice creation even if client save fails
            }
        }
        
        try {
            DB::beginTransaction();
            
            // Faturanın toplam tutarlarını hesapla
            $subtotal = 0;
            $taxAmount = 0;
            
            // İtem bilgilerini hazırla
            $items = [];
            foreach ($request->items as $key => $itemData) {
                $quantity = floatval($itemData['quantity']);
                $unitPrice = floatval($itemData['unit_price']);
                $taxRate = floatval($itemData['tax_rate']);
                
                $itemSubtotal = $quantity * $unitPrice;
                $itemTaxAmount = $itemSubtotal * ($taxRate / 100);
                $itemTotal = $itemSubtotal + $itemTaxAmount;
                
                $subtotal += $itemSubtotal;
                $taxAmount += $itemTaxAmount;
                
                $items[] = [
                    'description' => $itemData['description'],
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'tax_rate' => $taxRate,
                    'tax_amount' => $itemTaxAmount,
                    'total' => $itemTotal,
                    'sort_order' => $key + 1,
                ];
            }
            
            // Faturayı oluştur
            $invoice = new Invoice([
                'invoice_number' => $request->invoice_number,
                'company_id' => $request->company_id,
                'invoice_date' => Carbon::parse($request->invoice_date),
                'due_date' => $request->due_date ? Carbon::parse($request->due_date) : null,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total' => $subtotal + $taxAmount,
                'currency' => $request->currency ?? 'TRY',
                'language_code' => $request->language_code,
                'notes' => $request->notes,
                'status' => 'draft',
                'created_by' => Auth::id(),
            ]);
            
            // Set client information based on the case
            if ($request->client_id) {
                // Get client from database to ensure it exists
                $client = UserClient::where('id', $request->client_id)
                    ->where('user_id', Auth::id())
                    ->first();
                    
                if ($client) {
                    $invoice->client_id = $client->id;
                    // Make sure all manual fields are null when using client_id
                    $invoice->client_name = null;
                    $invoice->client_vat_number = null;
                    $invoice->client_address = null;
                    $invoice->client_email = null;
                    $invoice->client_phone = null;
                    $invoice->client_company_reg_number = null;
                    $invoice->client_country = null;
                }
            } else if ($newClientId) {
                // Get new client from database
                $client = UserClient::find($newClientId);
                if ($client) {
                    $invoice->client_id = $client->id;
                    // Make sure all manual fields are null when using client_id
                    $invoice->client_name = null;
                    $invoice->client_vat_number = null;
                    $invoice->client_address = null;
                    $invoice->client_email = null;
                    $invoice->client_phone = null;
                    $invoice->client_company_reg_number = null;
                    $invoice->client_country = null;
                }
            } else if ($request->client_name) {
                // Use manual client information
                $invoice->client_id = null;
                $invoice->client_name = $request->client_name;
                $invoice->client_vat_number = $request->vat_number;
                $invoice->client_address = $request->client_address;
                $invoice->client_email = $request->client_email;
                $invoice->client_phone = $request->client_phone;
                $invoice->client_company_reg_number = $request->client_company_reg_number;
                $invoice->client_country = $request->client_country;
            }
            
            // Save the invoice with all information including client details
            $invoice->save();
            
            // Fatura kalemlerini ekle
            foreach ($items as $item) {
                $invoice->items()->create($item);
            }
            
            // PDF oluştur ve kaydet
            try {
                $this->pdfGenerator->generatePdf($invoice);
            } catch (\Exception $e) {
                \Log::error('Error generating PDF: ' . $e->getMessage());
                // PDF oluşturulamazsa da devam et
            }
            
            DB::commit();
            
            return redirect()->route('user.invoices.show', $invoice)
                ->with('success', 'Invoice successfully created.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Error while creating invoice: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Fatura detaylarını gösterir
     */
    public function show(Invoice $invoice)
    {
        // Faturayı sadece oluşturan kişi görebilir
        if ($invoice->created_by != Auth::id()) {
            abort(403, 'Bu faturayı görüntüleme yetkiniz yok.');
        }
        
        return view('user.invoices.show', compact('invoice'));
    }
    
    /**
     * Fatura düzenleme formunu gösterir
     */
    public function edit(Invoice $invoice)
    {
        // Faturayı sadece oluşturan kişi düzenleyebilir
        if ($invoice->created_by != Auth::id()) {
            abort(403, 'Bu faturayı düzenleme yetkiniz yok.');
        }
        
        $companies = Company::where('user_id', Auth::id())->get();
        $clients = Company::where('user_id', Auth::id())
            ->where('is_own_company', false)
            ->get();
            
        // Müşterilerimizi de alalım
        $userclients = UserClient::where('user_id', Auth::id())->get();
            
        // Desteklenen diller
        $languages = [
            'tr' => 'Türkçe',
            'en' => 'İngilizce',
            'de' => 'Almanca'
        ];
        
        return view('user.invoices.edit', compact('invoice', 'companies', 'clients', 'languages', 'userclients'));
    }
    
    /**
     * Fatura güncellemesini kaydeder
     */
    public function update(Request $request, Invoice $invoice)
    {
        // Faturayı sadece oluşturan kişi güncelleyebilir
        if ($invoice->created_by != Auth::id()) {
            abort(403, 'Bu faturayı güncelleme yetkiniz yok.');
        }
        
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'invoice_number' => 'required|string|max:50|unique:invoices,invoice_number,' . $invoice->id,
            'invoice_date' => 'required|date',
            'language_code' => 'required|string|in:tr,en,de',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_rate' => 'required|numeric|min:0'
        ]);
        
        try {
            DB::beginTransaction();
            
            // Faturanın toplam tutarlarını hesapla
            $subtotal = 0;
            $taxAmount = 0;
            
            // Mevcut fatura kalemlerini sil
            $invoice->items()->delete();
            
            // İtem bilgilerini hazırla
            foreach ($request->items as $key => $itemData) {
                $quantity = floatval($itemData['quantity']);
                $unitPrice = floatval($itemData['unit_price']);
                $taxRate = floatval($itemData['tax_rate']);
                
                $itemSubtotal = $quantity * $unitPrice;
                $itemTaxAmount = $itemSubtotal * ($taxRate / 100);
                $itemTotal = $itemSubtotal + $itemTaxAmount;
                
                $subtotal += $itemSubtotal;
                $taxAmount += $itemTaxAmount;
                
                $invoice->items()->create([
                    'description' => $itemData['description'],
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'tax_rate' => $taxRate,
                    'tax_amount' => $itemTaxAmount,
                    'total' => $itemTotal,
                    'sort_order' => $key + 1,
                ]);
            }
            
            // Güvenli güncelleme - SADECE değiştirilebilir alanları güncelle, client bilgilerini değiştirme
            $invoice->update([
                'invoice_number' => $request->invoice_number,
                'company_id' => $request->company_id,
                'invoice_date' => Carbon::parse($request->invoice_date),
                'due_date' => $request->due_date ? Carbon::parse($request->due_date) : null,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total' => $subtotal + $taxAmount,
                'currency' => $request->currency ?? 'TRY',
                'language_code' => $request->language_code,
                'notes' => $request->notes
                // Client bilgileri burada güncellenmiyor
            ]);
            
            // Yeni PDF oluştur ve kaydet
            $this->pdfGenerator->generatePdf($invoice);
            
            DB::commit();
            
            return redirect()->route('user.invoices.show', $invoice)
                ->with('success', 'Invoice successfully updated.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Error while updating invoice: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Faturayı siler
     */
    public function destroy(Invoice $invoice)
    {
        // Faturayı sadece oluşturan kişi silebilir
        if ($invoice->created_by != Auth::id()) {
            abort(403, 'Bu faturayı silme yetkiniz yok.');
        }
        
        try {
            // Faturayı ve ilişkili dosyaları sil
            DB::beginTransaction();
            
            // Delete the file record and its storage
            if ($invoice->pdf_path) {
                // Find and delete the file record
                $file = File::where('path', $invoice->pdf_path)
                    ->where('folder_id', $invoice->folder_id)
                    ->first();
                    
                if ($file) {
                    // File::delete() method will handle both storage and database deletion
                    $file->delete();
                } else {
                    // If no file record found, just try to delete from storage
                    Storage::disk('bunny')->delete($invoice->pdf_path);
                }
                
                // Force a cache clear on the Bunny CDN for this file
                try {
                    if (config('filesystems.disks.bunny.purge_cache_url')) {
                        $purgeUrl = config('filesystems.disks.bunny.purge_cache_url') . '/' . $invoice->pdf_path;
                        \Http::delete($purgeUrl);
                    }
                } catch (\Exception $e) {
                    \Log::error('Error purging CDN cache during invoice deletion: ' . $e->getMessage());
                    // Continue with deletion even if cache purge fails
                }
            }
            
            // Fatura kalemlerini sil
            $invoice->items()->delete();
            
            // Faturayı sil
            $invoice->delete();
            
            DB::commit();
            
            return redirect()->route('user.invoices.index')
                ->with('success', 'Invoice successfully deleted.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error while deleting invoice: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Fatura PDF'ini indirir
     */
    public function downloadPdf(Invoice $invoice)
    {
        // Faturayı sadece oluşturan kişi indirebilir
        if ($invoice->created_by != Auth::id()) {
            abort(403, 'Bu faturayı indirme yetkiniz yok.');
        }
        
        if (!$invoice->pdf_path) {
            // PDF yoksa yeniden oluştur
            try {
                $file = $this->pdfGenerator->generatePdf($invoice);
                // Refresh the invoice to get the latest pdf_path
                $invoice->refresh();
            } catch (\Exception $e) {
                return back()->withErrors(['error' => 'Error generating PDF: ' . $e->getMessage()]);
            }
        }
        
        // Add cache-busting query parameter to the URL
        $downloadUrl = $invoice->download_url;
        $timestamp = time();
        $separator = parse_url($downloadUrl, PHP_URL_QUERY) ? '&' : '?';
        $downloadUrl .= $separator . 't=' . $timestamp;
        
        return redirect($downloadUrl);
    }
    
    /**
     * Fatura PDF'ini yeniden oluşturur
     */
    public function regeneratePdf(Invoice $invoice)
    {
        // Faturayı sadece oluşturan kişi yeniden oluşturabilir
        if ($invoice->created_by != Auth::id()) {
            abort(403, 'Bu faturayı yeniden oluşturma yetkiniz yok.');
        }
        
        try {
            $file = $this->pdfGenerator->generatePdf($invoice);
            
            return redirect()->route('user.invoices.show', $invoice)
                ->with('success', 'Invoice PDF has been regenerated.');
                
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error while generating PDF: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Bir sonraki fatura numarasını oluşturur
     */
    protected function generateNextInvoiceNumber($lastInvoice = null)
    {
        $currentYear = Carbon::now()->format('Y');
        $userPrefix = 'U' . str_pad(Auth::id(), 3, '0', STR_PAD_LEFT); // U001, U002, etc.
        
        if (!$lastInvoice) {
            // İlk fatura
            return $userPrefix . '-' . $currentYear . '-0001';
        }
        
        // Son fatura numarasından yeni bir numara oluştur
        $parts = explode('-', $lastInvoice->invoice_number);
        
        // Eski format kontrolü - Prefix'siz fatura numaraları
        if (count($parts) == 2) {
            // Eski format (YYYY-NNNN), yeni formata çevir
            return $userPrefix . '-' . $currentYear . '-0001';
        }
        
        // Yeni format (UXXX-YYYY-NNNN)
        if (count($parts) != 3) {
            // Geçersiz format, varsayılan formatı kullan
            return $userPrefix . '-' . $currentYear . '-0001';
        }
        
        $prefix = $parts[0];
        $year = $parts[1];
        $number = (int) $parts[2];
        
        // Farklı kullanıcı ise veya prefix değiştiyse, 0001'den başla
        if ($prefix != $userPrefix) {
            return $userPrefix . '-' . $currentYear . '-0001';
        }
        
        if ($year != $currentYear) {
            // Yeni yıl, numarayı sıfırla
            return $userPrefix . '-' . $currentYear . '-0001';
        }
        
        // Mevcut numarayı bir artır
        $nextNumber = $number + 1;
        return $userPrefix . '-' . $currentYear . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
