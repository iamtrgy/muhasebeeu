<?php

namespace App\Services\Invoice;

use App\Models\Invoice;
use App\Models\File;
use App\Models\Folder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class InvoicePdfGenerator
{
    /**
     * Desteklenen diller
     */
    protected array $supportedLanguages = ['tr', 'en', 'de', 'et'];
    
    /**
     * Dil çevirileri
     */
    protected array $translations = [
        'tr' => [
            'invoice' => 'FATURA',
            'invoice_number' => 'Fatura No',
            'invoice_date' => 'Fatura Tarihi',
            'due_date' => 'Son Ödeme Tarihi',
            'company_info' => 'Satıcı Bilgileri',
            'client_info' => 'Alıcı Bilgileri',
            'tax_number' => 'Kayıt No',
            'address' => 'Adres',
            'description' => 'Açıklama',
            'quantity' => 'Miktar',
            'unit_price' => 'Birim Fiyat',
            'tax_rate' => 'KDV Oranı',
            'tax_amount' => 'KDV Tutarı',
            'subtotal' => 'Ara Toplam',
            'total' => 'Toplam',
            'notes' => 'Notlar',
            'page' => 'Sayfa',
            'of' => '/',
        ],
        'en' => [
            'invoice' => 'INVOICE',
            'invoice_number' => 'Invoice No',
            'invoice_date' => 'Invoice Date',
            'due_date' => 'Due Date',
            'company_info' => 'Seller Information',
            'client_info' => 'Customer Information',
            'tax_number' => 'Registry Number',
            'address' => 'Address',
            'description' => 'Description',
            'quantity' => 'Quantity',
            'unit_price' => 'Unit Price',
            'tax_rate' => 'Tax Rate',
            'tax_amount' => 'Tax Amount',
            'subtotal' => 'Subtotal',
            'total' => 'Total',
            'notes' => 'Notes',
            'page' => 'Page',
            'of' => 'of',
        ],
        'de' => [
            'invoice' => 'RECHNUNG',
            'invoice_number' => 'Rechnungsnummer',
            'invoice_date' => 'Rechnungsdatum',
            'due_date' => 'Fälligkeitsdatum',
            'company_info' => 'Verkäuferinformationen',
            'client_info' => 'Kundeninformationen',
            'tax_number' => 'Registernummer',
            'address' => 'Adresse',
            'description' => 'Beschreibung',
            'quantity' => 'Menge',
            'unit_price' => 'Einzelpreis',
            'tax_rate' => 'MwSt-Satz',
            'tax_amount' => 'MwSt-Betrag',
            'subtotal' => 'Zwischensumme',
            'total' => 'Gesamtbetrag',
            'notes' => 'Anmerkungen',
            'page' => 'Seite',
            'of' => 'von',
        ],
        'et' => [
            'invoice' => 'ARVE',
            'invoice_number' => 'Arve number',
            'invoice_date' => 'Arve kuupäev',
            'due_date' => 'Maksetähtaeg',
            'company_info' => 'Müüja info',
            'client_info' => 'Kliendi info',
            'tax_number' => 'Registrikood',
            'address' => 'Aadress',
            'description' => 'Kirjeldus',
            'quantity' => 'Kogus',
            'unit_price' => 'Ühiku hind',
            'tax_rate' => 'KM määr',
            'tax_amount' => 'KM summa',
            'subtotal' => 'Vahesumma',
            'total' => 'Kokku',
            'notes' => 'Märkused',
            'page' => 'Leht',
            'of' => '/',
        ]
    ];
    
    /**
     * Fatura için PDF oluşturur ve dosya sistemine kaydeder
     */
    public function generatePdf(Invoice $invoice): File
    {
        // Delete existing PDF file if it exists
        if ($invoice->pdf_path) {
            try {
                Storage::disk('bunny')->delete($invoice->pdf_path);
            } catch (\Exception $e) {
                \Log::error('Error deleting old PDF: ' . $e->getMessage());
            }
        }

        // Dil kontrolü
        $language = $this->validateLanguage($invoice->language_code);
        
        // PDF içeriğini hazırla
        $html = $this->prepareHtml($invoice, $language);
        
        // PDF oluştur
        $pdf = PDF::loadHTML($html);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'sans-serif'
        ]);
        
        // Fatura için klasör oluştur veya var olanı kullan
        $folder = $this->getOrCreateFolder($invoice);
        
        // Dosya adını hazırla - add timestamp to prevent caching
        $fileName = $this->generateFileName($invoice);
        
        // Dosya yolunu tanımla
        $filePath = $invoice->invoice_folder_path . '/' . $fileName;
        
        // Try to delete any existing file at this path
        try {
            Storage::disk('bunny')->delete($filePath);
        } catch (\Exception $e) {
            \Log::error('Error deleting existing file: ' . $e->getMessage());
        }
        
        // PDF'i saklama alanına kaydet
        Storage::disk('bunny')->put($filePath, $pdf->output());
        
        // Force a cache clear on the Bunny CDN for this file
        try {
            if (config('filesystems.disks.bunny.purge_cache_url')) {
                $purgeUrl = config('filesystems.disks.bunny.purge_cache_url') . '/' . $filePath;
                \Http::delete($purgeUrl);
            }
        } catch (\Exception $e) {
            \Log::error('Error purging CDN cache: ' . $e->getMessage());
        }
        
        // Delete old file record if exists
        if ($invoice->pdf_path) {
            File::where('path', $invoice->pdf_path)->delete();
        }
        
        // Dosya kaydını oluştur
        $file = new File([
            'name' => $fileName,
            'original_name' => $fileName,
            'mime_type' => 'application/pdf',
            'size' => Storage::disk('bunny')->size($filePath),
            'path' => $filePath,
            'folder_id' => $folder->id,
            'uploaded_by' => $invoice->created_by,
        ]);
        
        $file->save();
        
        // Faturayı güncelle
        $invoice->update([
            'pdf_path' => $filePath,
            'folder_id' => $folder->id
        ]);
        
        return $file;
    }
    
    /**
     * Fatura için HTML şablonunu hazırlar
     */
    protected function prepareHtml(Invoice $invoice, string $language): string
    {
        $trans = $this->translations[$language];
        
        // View dosyası varsa kullan, yoksa string olarak HTML oluştur
        if (view()->exists('invoices.template_' . $language)) {
            return view('invoices.template_' . $language, [
                'invoice' => $invoice,
                'trans' => $trans
            ])->render();
        }
        
        // Basit bir HTML şablonu oluştur
        $date_format = $language == 'en' ? 'Y-m-d' : 'd.m.Y';
        
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>' . $trans['invoice'] . ' #' . $invoice->invoice_number . '</title>
            <style>
                body { font-family: sans-serif; font-size: 14px; line-height: 1.5; color: #333; }
                .invoice-header { text-align: center; margin-bottom: 30px; }
                .invoice-title { font-size: 24px; font-weight: bold; margin-bottom: 10px; }
                .invoice-details { margin-bottom: 20px; width: 100%; }
                .invoice-details table { width: 100%; }
                .invoice-details td { padding: 5px; }
                .info-section { margin-bottom: 20px; }
                .info-title { font-weight: bold; border-bottom: 1px solid #ddd; padding-bottom: 5px; margin-bottom: 10px; }
                .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                .items-table th { background-color: #f2f2f2; padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
                .items-table td { padding: 10px; border-bottom: 1px solid #ddd; }
                .items-table .text-right { text-align: right; }
                .total-section { width: 100%; }
                .total-table { width: 40%; float: right; }
                .total-table td { padding: 5px; }
                .total-table .total-row { font-weight: bold; }
                .notes-section { margin-top: 30px; border-top: 1px solid #ddd; padding-top: 10px; }
                .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #777; }
            </style>
        </head>
        <body>
            <div class="invoice-header">
                <div class="invoice-title">' . $trans['invoice'] . '</div>
            </div>
            
            <table class="invoice-details">
                <tr>
                    <td width="50%">
                        <strong>' . $trans['invoice_number'] . ':</strong> ' . $invoice->invoice_number . '
                    </td>
                    <td width="50%" align="right">
                        <strong>' . $trans['invoice_date'] . ':</strong> ' . $invoice->invoice_date->format($date_format) . '
                    </td>
                </tr>';
                
        if ($invoice->due_date) {
            $html .= '
                <tr>
                    <td width="50%">&nbsp;</td>
                    <td width="50%" align="right">
                        <strong>' . $trans['due_date'] . ':</strong> ' . $invoice->due_date->format($date_format) . '
                    </td>
                </tr>';
        }
                
        $html .= '
            </table>
            
            <div class="info-section">
                <div class="info-title">' . $trans['company_info'] . '</div>
                <div>
                    <strong>' . $invoice->company->name . '</strong><br>
                    ' . $trans['tax_number'] . ': ' . $invoice->company->tax_number . '<br>
                    ' . $trans['address'] . ': ' . $invoice->company->address . '
                </div>
            </div>
            
            <div class="info-section">
                <div class="info-title">' . $trans['client_info'] . '</div>
                <div>';
        
        if ($invoice->client_id && $invoice->client) {
            $html .= '
                    <strong>' . $invoice->client->name . '</strong><br>';
            if ($invoice->client->vat_number) {
                $html .= '
                    ' . $trans['tax_number'] . ': ' . $invoice->client->vat_number . '<br>';
            }
            if ($invoice->client->company_reg_number) {
                $html .= '
                    ' . __('Company Reg. Number') . ': ' . $invoice->client->company_reg_number . '<br>';
            }
            if ($invoice->client->email) {
                $html .= '
                    ' . __('Email') . ': ' . $invoice->client->email . '<br>';
            }
            if ($invoice->client->phone) {
                $html .= '
                    ' . __('Phone') . ': ' . $invoice->client->phone . '<br>';
            }
            if ($invoice->client->country) {
                $html .= '
                    ' . __('Country') . ': ' . $invoice->client->country . '<br>';
            }
            if ($invoice->client->address) {
                $html .= '
                    ' . $trans['address'] . ': ' . $invoice->client->address;
            }
            $html .= '
                </div>';
        } else {
            $html .= '
                    <strong>' . $invoice->client_name . '</strong><br>';
            if ($invoice->client_vat_number) {
                $html .= '
                    ' . $trans['tax_number'] . ': ' . $invoice->client_vat_number . '<br>';
            }
            if ($invoice->client_company_reg_number) {
                $html .= '
                    ' . __('Company Reg. Number') . ': ' . $invoice->client_company_reg_number . '<br>';
            }
            if ($invoice->client_email) {
                $html .= '
                    ' . __('Email') . ': ' . $invoice->client_email . '<br>';
            }
            if ($invoice->client_phone) {
                $html .= '
                    ' . __('Phone') . ': ' . $invoice->client_phone . '<br>';
            }
            if ($invoice->client_country) {
                $html .= '
                    ' . __('Country') . ': ' . $invoice->client_country . '<br>';
            }
            if ($invoice->client_address) {
                $html .= '
                    ' . $trans['address'] . ': ' . $invoice->client_address;
            }
            $html .= '
                </div>';
        }
            
        $html .= '
            </div>
            
            <table class="items-table">
                <thead>
                    <tr>
                        <th>' . $trans['description'] . '</th>
                        <th>' . $trans['quantity'] . '</th>
                        <th>' . $trans['unit_price'] . '</th>
                        <th>' . $trans['tax_rate'] . '</th>
                        <th>' . $trans['tax_amount'] . '</th>
                        <th>' . $trans['total'] . '</th>
                    </tr>
                </thead>
                <tbody>';
                
        foreach ($invoice->items as $item) {
            $html .= '
                    <tr>
                        <td>' . $item->description . '</td>
                        <td>' . number_format($item->quantity, 2, ',', '.') . '</td>
                        <td>' . number_format($item->unit_price, 2, ',', '.') . ' ' . $invoice->currency . '</td>
                        <td>%' . number_format($item->tax_rate, 0) . '</td>
                        <td>' . number_format($item->tax_amount, 2, ',', '.') . ' ' . $invoice->currency . '</td>
                        <td>' . number_format($item->total, 2, ',', '.') . ' ' . $invoice->currency . '</td>
                    </tr>';
        }
                
        $html .= '
                </tbody>
            </table>
            
            <div class="total-section">
                <table class="total-table">
                    <tr>
                        <td align="right"><strong>' . $trans['subtotal'] . ':</strong></td>
                        <td align="right">' . number_format($invoice->subtotal, 2, ',', '.') . ' ' . $invoice->currency . '</td>
                    </tr>
                    <tr>
                        <td align="right"><strong>' . $trans['tax_amount'] . ':</strong></td>
                        <td align="right">' . number_format($invoice->tax_amount, 2, ',', '.') . ' ' . $invoice->currency . '</td>
                    </tr>
                    <tr class="total-row">
                        <td align="right"><strong>' . $trans['total'] . ':</strong></td>
                        <td align="right">' . number_format($invoice->total, 2, ',', '.') . ' ' . $invoice->currency . '</td>
                    </tr>
                </table>
            </div>
            
            <div style="clear: both;"></div>';
            
        if ($invoice->notes) {
            $html .= '
            <div class="notes-section">
                <strong>' . $trans['notes'] . ':</strong>
                <p>' . nl2br(e($invoice->notes)) . '</p>
            </div>';
        }
            
        $html .= '
            <div class="footer">
                ' . $trans['page'] . ' 1 ' . $trans['of'] . ' 1
            </div>
        </body>
        </html>';
        
        return $html;
    }
    
    /**
     * Fatura dosyası için dosya adı oluşturur
     */
    protected function generateFileName(Invoice $invoice): string
    {
        $companyName = Str::slug($invoice->company->name);
        $timestamp = time(); // Add timestamp to prevent caching
        return 'invoice_' . $invoice->invoice_number . '_' . $companyName . '_' . $timestamp . '.pdf';
    }
    
    /**
     * Dil kodunu kontrol eder, geçerli değilse varsayılanı döndürür
     */
    protected function validateLanguage(string $language): string
    {
        return in_array($language, array_keys($this->translations)) ? $language : 'tr';
    }
    
    /**
     * Fatura için klasör bulur veya oluşturur
     */
    protected function getOrCreateFolder(Invoice $invoice): Folder
    {
        $user = $invoice->creator;
        $company = $invoice->company;
        
        // Get the invoice date components
        $invoiceDate = Carbon::parse($invoice->invoice_date);
        $year = $invoiceDate->format('Y');
        $monthNumber = $invoiceDate->format('m');
        $monthName = $invoiceDate->format('F'); // Full month name like "April"
        
        // Find the year folder
        $yearFolder = Folder::where('company_id', $company->id)
            ->where('name', $year)
            ->whereHas('parent', function($query) {
                $query->whereNull('parent_id');
            })
            ->first();
            
        if (!$yearFolder) {
            throw new \Exception("Year folder {$year} not found for company {$company->name}");
        }
        
        // Find the month folder - try both formats: just month name or "mm - Month"
        $monthFolder = Folder::where('company_id', $company->id)
            ->where(function($query) use ($monthName, $monthNumber) {
                $query->where('name', $monthName)
                      ->orWhere('name', $monthNumber . ' - ' . $monthName);
            })
            ->where('parent_id', $yearFolder->id)
            ->first();
            
        if (!$monthFolder) {
            throw new \Exception("Month folder {$monthName} not found in year {$year} for company {$company->name}");
        }
        
        // Find the Income folder
        $incomeFolder = Folder::where('company_id', $company->id)
            ->where(function($query) {
                $query->where('name', 'Income')
                      ->orWhere('name', 'income');
            })
            ->where('parent_id', $monthFolder->id)
            ->first();
            
        if (!$incomeFolder) {
            throw new \Exception("Income folder not found in {$monthName} {$year} for company {$company->name}. Please make sure the folder structure is properly set up with an Income folder in each month.");
        }
        
        return $incomeFolder;
    }
} 