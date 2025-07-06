<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\UserClient;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'company_id',
        'client_id',
        'client_name',
        'client_vat_number',
        'client_address',
        'invoice_date',
        'due_date',
        'subtotal',
        'tax_amount',
        'total',
        'currency',
        'language_code',
        'notes',
        'payment_url',
        'status',
        'pdf_path',
        'folder_id',
        'created_by'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Faturayı oluşturan şirket
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Müşteri şirket
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(UserClient::class, 'client_id');
    }

    /**
     * Fatura kalemleri
     */
    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * Faturayı oluşturan kullanıcı
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Fatura PDF'inin kaydedildiği klasör
     */
    public function folder(): BelongsTo
    {
        return $this->belongsTo(Folder::class);
    }

    /**
     * PDF dosyasının tam URL'ini döndürür
     */
    public function getPdfUrlAttribute(): ?string
    {
        if (!$this->pdf_path) {
            return null;
        }
        
        // PDF'nin görüntülenmesi için gereken URL'i oluştur
        $config = config('filesystems.disks.bunny');
        $cdnUrl = $config['cdn_url'] ?? 'https://all-files-1.b-cdn.net';
        return rtrim($cdnUrl, '/') . '/' . ltrim($this->pdf_path, '/');
    }

    /**
     * PDF dosyasının indirilmesi için URL döndürür
     */
    public function getDownloadUrlAttribute(): ?string
    {
        if (!$this->pdf_path) {
            return null;
        }
        
        $fileName = 'invoice_' . $this->invoice_number . '.pdf';
        return $this->pdf_url . '?download=1&filename=' . urlencode($fileName) . '&force=true';
    }

    /**
     * Fatura tarihine göre klasör yolu oluşturur
     * Örn: invoices/2023/04
     */
    public function getInvoiceFolderPathAttribute(): string
    {
        $date = $this->invoice_date instanceof Carbon 
            ? $this->invoice_date 
            : Carbon::parse($this->invoice_date);
            
        return 'invoices/' . $date->format('Y/m');
    }
}
