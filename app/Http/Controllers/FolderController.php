<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Services\BunnyAdapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Config;

class FolderController extends Controller
{
    public function index()
    {
        // Get only root folders (parent_id is null) that the user has access to
        $folders = Folder::where(function($query) {
            // Get folders where user is explicitly assigned
            $query->whereHas('users', function($q) {
                $q->where('user_id', auth()->id());
            });

            // Or folders created by the user
            if (!auth()->user()->is_admin) {
                $query->orWhere('created_by', auth()->id());
            }
        })
        ->whereNull('parent_id') // Only get root folders
        ->withCount(['files', 'children' => function($query) {
            $query->whereNull('deleted_at');
        }])
        ->latest()
        ->paginate(10);

        return view('user.folders.index', compact('folders'));
    }

    public function show(Folder $folder)
    {
        // Check if user has access to this folder
        if (!$folder->isAccessibleBy(auth()->user())) {
            abort(403, 'Unauthorized action.');
        }

        // Get subfolders that are accessible to the user
        $subfolders = $folder->children()
            ->where(function ($query) {
                $query->where(function($q) {
                    $q->where('is_public', true)
                        ->whereNull('deleted_at');
                })
                ->orWhere('created_by', auth()->id())
                ->orWhereHas('users', function ($q) {
                    $q->where('users.id', auth()->id());
                });
            })
            ->withCount(['files', 'children' => function($query) {
                $query->whereNull('deleted_at');
            }])
            ->latest()
            ->get();

        // Get files with pagination
        $files = $folder->files()
            ->with('uploader')
            ->latest()
            ->paginate(15);

        return view('user.folders.show', compact('folder', 'files', 'subfolders'));
    }

    public function create()
    {
        $user = auth()->user();
        $folders = Folder::where('is_public', true)
            ->orWhere('created_by', $user->id)
            ->orWhereHas('users', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->get();
            
        // Get user's companies for the dropdown
        $companies = $user->companies;
            
        return view('user.folders.create', compact('folders', 'companies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_public' => 'boolean',
            'parent_id' => 'nullable|exists:folders,id',
            'company_id' => 'nullable|exists:companies,id,user_id,' . auth()->id(),
        ]);

        // If parent_id is provided, check if user has access to parent folder
        if (!empty($validated['parent_id'])) {
            $parentFolder = Folder::findOrFail($validated['parent_id']);
            if (!$parentFolder->isAccessibleBy(auth()->user())) {
                abort(403, 'You do not have access to the parent folder.');
            }
        }

        $folder = Folder::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'is_public' => $validated['is_public'] ?? false,
            'parent_id' => $validated['parent_id'],
            'created_by' => auth()->id(),
            'company_id' => $validated['company_id'] ?? null,
        ]);

        // Create placeholder file to ensure folder exists in Bunny storage
        $folderPath = $this->getFolderPath($folder);
        
        // Get configuration and create adapter directly
        $config = config('filesystems.disks.bunny');
        $adapter = new BunnyAdapter(
            $config['storage_zone_name'],
            $config['api_key'],
            $config['region'] ?? 'de',
            $config['hostname'] ?? null
        );
        
        // Ensure the parent directory exists first
        $parentPath = dirname($folderPath);
        if ($parentPath !== '.' && $parentPath !== '') {
            try {
                // Check if parent directory exists, create it if not
                if (!$adapter->directoryExists($parentPath)) {
                    // Create placeholder in parent path
                    $adapter->write($parentPath . '/.keep', 'Placeholder file to ensure parent folder exists', new Config());
                    \Log::info("Created parent directory placeholder: {$parentPath}");
                }
            } catch (\Exception $e) {
                \Log::warning("Failed to create parent directory: " . $e->getMessage());
            }
        }

        // Now create the placeholder file for this folder
        try {
            $adapter->write($folderPath . '/.keep', 'Placeholder file to ensure folder exists in storage', new Config());
            
            // Double-check the file exists
            if ($adapter->fileExists($folderPath . '/.keep')) {
                \Log::info("Successfully created placeholder for folder ID {$folder->id}");
            } else {
                // Try one more time with a small delay
                usleep(500000); // 500ms delay
                $adapter->write($folderPath . '/.keep', 'Placeholder file to ensure folder exists in storage', new Config());
                \Log::info("Retried creating placeholder for folder ID {$folder->id}");
            }
        } catch (\Exception $e) {
            // Log the error but don't interrupt the folder creation process
            \Log::error("Failed to create placeholder for folder ID {$folder->id}: " . $e->getMessage());
        }

        // Attach the current user to the folder
        $folder->users()->attach(auth()->id());

        return redirect()->route('folders.show', $folder)
            ->with('success', 'Folder created successfully.');
    }
    
    /**
     * Get the full path for a folder, including parent folders
     */
    private function getFolderPath(Folder $folder): string
    {
        $path = 'folders/' . $folder->id;
        
        // Add parent folder paths if necessary
        if ($folder->parent_id) {
            $parentPath = $this->getParentPath($folder->parent);
            if (!empty($parentPath)) {
                $path = $parentPath . '/' . $path;
            }
        }
        
        return $path;
    }
    
    /**
     * Get the path for parent folders
     */
    private function getParentPath(?Folder $folder): string
    {
        if (!$folder) {
            return '';
        }
        
        $path = 'folders/' . $folder->id;
        
        if ($folder->parent_id) {
            $parentPath = $this->getParentPath($folder->parent);
            if (!empty($parentPath)) {
                $path = $parentPath . '/' . $path;
            }
        }
        
        return $path;
    }

    /**
     * Remove the specified folder resource from storage.
     * This only deletes personal access for regular users.
     */
    public function destroy(Folder $folder)
    {
        // Check if user has access to this folder
        if (!$folder->isAccessibleBy(auth()->user())) {
            abort(403, 'Unauthorized action.');
        }

        // For regular users, we only detach them from the folder
        // We don't delete the actual folder unless they are the creator
        // and it's not a public folder
        if ($folder->is_public || $folder->created_by !== auth()->id()) {
            // Just detach the current user
            $folder->users()->detach(auth()->id());
            return redirect()->route('user.folders.index')
                ->with('success', 'Folder removed from your list.');
        }

        // Get Bunny adapter configuration
        $bunnyConfig = config('filesystems.disks.bunny');
        $adapter = new BunnyAdapter(
            $bunnyConfig['storage_zone_name'],
            $bunnyConfig['api_key'],
            $bunnyConfig['region'] ?? 'de',
            $bunnyConfig['hostname'] ?? null
        );

        // Only delete the folder if the user is the creator and it's not public
        try {
            // Delete from Bunny CDN
            $this->deleteFromBunny($folder, $adapter);
            
            // Detach all users
            $folder->users()->detach();
            
            // Delete the folder from the database
            $folder->delete();
            
            return redirect()->route('user.folders.index')
                ->with('success', 'Folder deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting folder: ' . $e->getMessage());
            return redirect()->route('user.folders.index')
                ->with('error', 'Error deleting folder: ' . $e->getMessage());
        }
    }
    
    /**
     * Delete folder and its files from Bunny CDN storage
     */
    private function deleteFromBunny(Folder $folder, BunnyAdapter $adapter): void
    {
        try {
            // Get folder path
            $folderPath = $this->getFolderPath($folder);
            
            // Delete placeholder file
            try {
                if ($adapter->fileExists($folderPath . '/.keep')) {
                    $adapter->delete($folderPath . '/.keep');
                    \Log::info("Deleted placeholder file for folder ID {$folder->id}");
                }
            } catch (\Exception $e) {
                \Log::warning("Failed to delete placeholder file for folder ID {$folder->id}: " . $e->getMessage());
            }
            
            // Delete the folder's files from storage
            $folder->files->each(function ($file) use ($adapter, $folderPath) {
                try {
                    $filePath = $folderPath . '/' . $file->filename;
                    if ($adapter->fileExists($filePath)) {
                        $adapter->delete($filePath);
                        \Log::info("Deleted file {$file->filename} from Bunny CDN");
                    }
                } catch (\Exception $e) {
                    \Log::warning("Failed to delete file {$file->filename} from Bunny CDN: " . $e->getMessage());
                }
            });
            
            // Try to delete the folder itself
            try {
                $adapter->deleteDirectory($folderPath);
                \Log::info("Deleted folder path {$folderPath} from Bunny CDN");
            } catch (\Exception $e) {
                \Log::warning("Note: Could not delete folder directory {$folderPath} from Bunny CDN: " . $e->getMessage());
            }
        } catch (\Exception $e) {
            \Log::error("Error deleting folder ID {$folder->id} from Bunny CDN: " . $e->getMessage());
        }
    }
} 