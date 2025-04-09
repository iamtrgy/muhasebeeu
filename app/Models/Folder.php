<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Folder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'is_public',
        'allow_uploads',
        'parent_id',
        'created_by',
        'company_id',
        'create_for_everyone',
        'template_folder_id',
        'access_type',
        'path'
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'allow_uploads' => 'boolean',
        'create_for_everyone' => 'boolean'
    ];



    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the parent folder
     */
    public function parent()
    {
        return $this->belongsTo(Folder::class, 'parent_id')
            ->withTrashed();  // Include soft-deleted parents to maintain hierarchy
    }

    /**
     * Get the company that owns the folder
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the children folders
     */
    public function children()
    {
        return $this->hasMany(Folder::class, 'parent_id');
    }

    /**
     * Get count of active children folders
     */
    public function activeChildrenCount(): int
    {
        return $this->activeChildren()->count();
    }

    /**
     * Get all active children folders
     */
    public function activeChildren()
    {
        return $this->hasMany(Folder::class, 'parent_id')
            ->where('active', true)
            ->whereNull('deleted_at');
    }

    /**
     * Get all children folders including soft-deleted ones
     */
    public function allChildren()
    {
        return $this->hasMany(Folder::class, 'parent_id')
            ->withTrashed()
            ->with('children');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    /**
     * Get recent files in the folder
     */
    public function recentFiles()
    {
        return $this->files()->orderBy('created_at', 'desc');
    }

    /**
     * Get the total size of all files in the folder
     */
    public function totalSize(): string
    {
        $bytes = $this->files->sum('size');
        
        if ($bytes < 1024) {
            return $bytes . ' B';
        } elseif ($bytes < 1048576) {
            return round($bytes / 1024, 1) . ' KB';
        } elseif ($bytes < 1073741824) {
            return round($bytes / 1048576, 1) . ' MB';
        } else {
            return round($bytes / 1073741824, 1) . ' GB';
        }
    }

    /**
     * Get the last modified date of any file in the folder
     */
    public function lastModified()
    {
        return $this->files()->latest('updated_at')->first()?->updated_at;
    }

    /**
     * Get the full path of the folder
     */
    public function getFullPathAttribute()
    {
        $path = collect([$this->name]);
        $parent = $this->parent;
        
        while ($parent) {
            $path->prepend($parent->name);
            $parent = $parent->parent;
        }
        
        return $path->implode(' > ');
    }

    protected static function boot()
    {
        parent::boot();

        // When creating a folder, set the creator
        static::creating(function ($folder) {
            if (!$folder->created_by && auth()->check()) {
                $folder->created_by = auth()->id();
            }
        });

        // When deleting a folder, handle cleanup
        static::deleting(function ($folder) {
            // Detach all users
            $folder->users()->detach();

            // If this is a template folder, update derived folders
            if ($folder->derivedFolders()->exists()) {
                $folder->derivedFolders()->update(['template_folder_id' => null]);
            }

            // Delete all files in this folder
            $folder->files()->delete();

            // Recursively delete child folders
            $folder->children()->each(function ($child) {
                $child->delete();
            });
        });

        // When updating a folder, validate and handle changes
        static::updating(function ($folder) {
            // Prevent moving folder under itself or its descendants
            if ($folder->isDirty('parent_id') && $folder->wouldCreateCycle($folder->parent_id)) {
                throw new \InvalidArgumentException('Cannot move folder under one of its descendants.');
            }

            // If visibility is changing and this is a template folder, sync to derived folders
            if ($folder->isDirty('is_public') && $folder->derivedFolders()->exists()) {
                $folder->derivedFolders()->update(['is_public' => $folder->is_public]);
            }
        });

        // When restoring a folder
        static::restoring(function ($folder) {
            // Restore all soft deleted children
            $folder->children()->withTrashed()->get()->each(function ($child) {
                $child->restore();
            });
        });
    }

    protected static function booted()
    {
        static::saving(function ($folder) {
            // Generate path when saving
            if ($folder->parent_id) {
                $parent = static::find($folder->parent_id);
                $folder->path = $parent->path . '/' . $folder->id;
            } else {
                $folder->path = (string) $folder->id;
            }
        });
    }

    public function isAccessibleBy(?User $user): bool
    {
        if ($this->trashed()) {
            return false;
        }

        if ($this->is_public) {
            return true;
        }

        if (!$user) {
            return false;
        }

        return $user->is_admin || 
               $this->created_by === $user->id || 
               $this->users()->where('user_id', $user->id)->exists();
    }

    public function canUpload(?User $user): bool
    {
        if (!$this->allow_uploads || $this->trashed()) {
            return false;
        }

        return $this->isAccessibleBy($user);
    }

    public function wouldCreateCycle($newParentId): bool
    {
        if (!$newParentId) {
            return false;
        }

        if ($newParentId === $this->id) {
            return true;
        }

        $parent = Folder::find($newParentId);
        while ($parent) {
            if ($parent->id === $this->id) {
                return true;
            }
            $parent = $parent->parent;
        }
        return false;
    }

    public function templateFolder(): BelongsTo
    {
        return $this->belongsTo(Folder::class, 'template_folder_id');
    }

    public function derivedFolders(): HasMany
    {
        return $this->hasMany(Folder::class, 'template_folder_id');
    }

    /**
     * Get all descendants of the folder
     */
    public function descendants()
    {
        return $this->hasMany(static::class, 'parent_id')
            ->with('descendants');
    }

    /**
     * Get all ancestors of the folder
     */
    public function ancestors()
    {
        return $this->belongsTo(static::class, 'parent_id')
            ->with('ancestors');
    }
} 