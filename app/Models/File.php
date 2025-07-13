<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'original_name',
        'mime_type',
        'size',
        'path',
        'folder_id',
        'uploaded_by',
        'notes',
        'ai_analysis',
        'ai_analyzed_at',
        'ai_suggested_folder_id',
        'ai_suggestion_accepted',
        'statement_analyzed',
        'statement_analysis_date',
        'transaction_count',
    ];

    protected $casts = [
        'size' => 'integer',
        'ai_analysis' => 'array',
        'ai_analyzed_at' => 'datetime',
        'ai_suggestion_accepted' => 'boolean',
        'statement_analyzed' => 'boolean',
        'statement_analysis_date' => 'datetime',
        'transaction_count' => 'integer',
    ];

    public function folder(): BelongsTo
    {
        return $this->belongsTo(Folder::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function suggestedFolder(): BelongsTo
    {
        return $this->belongsTo(Folder::class, 'ai_suggested_folder_id');
    }

    /**
     * Get the bank transactions extracted from this statement file
     */
    public function bankTransactions()
    {
        return $this->hasMany(BankTransaction::class);
    }

    public function getUrlAttribute(): string
    {
        // Generate URL from Bunny storage
        $config = config('filesystems.disks.bunny');
        $cdnUrl = $config['cdn_url'] ?? 'https://all-files-1.b-cdn.net';
        return rtrim($cdnUrl, '/') . '/' . ltrim($this->path, '/');
    }

    public function getDownloadUrlAttribute(): string
    {
        return $this->url . '?download=1&filename=' . urlencode($this->original_name) . '&force=true';
    }

    /**
     * Get the formatted file size
     * 
     * @return string
     */
    public function getSizeFormattedAttribute(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= (1 << (10 * $pow));
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }

    public function delete(): bool
    {
        Storage::disk('bunny')->delete($this->path);
        return parent::delete();
    }

    /**
     * Get the company associated with the file's folder
     * 
     * @return \App\Models\Company|null
     */
    public function getCompanyAttribute()
    {
        if ($this->folder && $this->folder->creator && $this->folder->creator->companies) {
            $companies = $this->folder->creator->companies;
            if ($companies->count() > 0) {
                return $companies->first();
            }
        }
        
        return null;
    }
}