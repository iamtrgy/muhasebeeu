<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use App\Models\User;
use App\Services\BunnyAdapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\Flysystem\Config;
use Illuminate\Support\Facades\Log;
use App\Services\FolderStructureService;

class FolderController extends Controller
{
    public function index()
    {
        $folders = Folder::with(['creator', 'users', 'children' => function($query) {
                $query->whereNull('deleted_at');
            }])
            ->whereNull('parent_id')
            ->withCount(['children' => function($query) {
                $query->whereNull('deleted_at');
            }, 'files'])
            ->latest()
            ->get();
        return view('admin.folders.index', compact('folders'));
    }

    public function create()
    {
        $folders = Folder::with(['parent', 'children' => function($query) {
                $query->whereNull('deleted_at');
            }])
            ->whereNull('deleted_at')
            ->get();
        $users = User::where('is_admin', false)->get();
        return view('admin.folders.create', compact('folders', 'users'));
    }

    public function createIn(Folder $parent)
    {
        $folders = Folder::with(['parent', 'children' => function($query) {
                $query->whereNull('deleted_at');
            }])
            ->whereNull('deleted_at')
            ->get();
        $users = User::where('is_admin', false)->get();
        return view('admin.folders.create', compact('folders', 'users', 'parent'));
    }

    protected function validationRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_public' => ['boolean'],
            'allow_uploads' => ['boolean'],
            'create_for_everyone' => ['boolean'],
            'access_type' => ['required', 'in:personal_copies,shared_access'],
            'parent_id' => ['nullable', 'exists:folders,id'],
            'user_ids' => ['nullable', 'array'],
            'user_ids.*' => ['exists:users,id']
        ];
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:folders,id',
            'created_by' => 'required|exists:users,id',
            'company_id' => [
                'nullable',
                'exists:companies,id',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value && $request->created_by) {
                        $company = \App\Models\Company::find($value);
                        if (!$company || $company->user_id != $request->created_by) {
                            $fail('The selected company does not belong to the selected owner.');
                        }
                    }
                }
            ],
            'is_public' => 'boolean',
            'allow_uploads' => 'boolean',
        ]);

        $folder = Folder::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'parent_id' => $validated['parent_id'],
            'created_by' => $validated['created_by'],
            'company_id' => $validated['company_id'] ?? null,
            'is_public' => $request->boolean('is_public', false),
            'allow_uploads' => $request->boolean('allow_uploads', false),
        ]);

        $folder->users()->syncWithoutDetaching([$validated['created_by']]);

        $folderPath = $this->getFolderPath($folder);
        $config = config('filesystems.disks.bunny');
        $adapter = new BunnyAdapter(
            $config['storage_zone_name'],
            $config['api_key'],
            $config['region'],
            $config['hostname']
        );
        
        $parentPath = dirname($folderPath);
        if ($parentPath !== '.' && $parentPath !== '') {
             try {
                 if (!$adapter->directoryExists($parentPath)) {
                     $adapter->write($parentPath . '/.keep', 'Placeholder file to ensure parent folder exists', new Config());
                     \Log::info("Created parent directory placeholder: {$parentPath}");
                 }
             } catch (\Exception $e) {
                 \Log::warning("Failed to create parent directory: " . $e->getMessage());
             }
         }

        try {
            $adapter->write($folderPath . '/.keep', 'Placeholder file to ensure folder exists in storage', new Config());
            if ($adapter->fileExists($folderPath . '/.keep')) {
                \Log::info("Successfully created placeholder for folder ID {$folder->id}");
            } else {
                usleep(500000);
                $adapter->write($folderPath . '/.keep', 'Placeholder file to ensure folder exists in storage', new Config());
                \Log::info("Retried creating placeholder for folder ID {$folder->id}");
            }
        } catch (\Exception $e) {
            \Log::error("Failed to create placeholder for folder ID {$folder->id}: " . $e->getMessage());
            session()->flash('warning', 'Folder created in database, but the placeholder file in storage could not be created. Some features may be limited.');
        }

        return redirect()->route('admin.folders.index')->with('success', 'Folder created successfully for user.');
    }

    public function edit(Folder $folder)
    {
        // Check if user can edit this folder
        if (!auth()->user()->is_admin && $folder->created_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $users = User::where('is_admin', false)->get();

        // Get all descendant folder IDs recursively
        $descendantIds = $folder->allChildren()->pluck('id')->toArray();

        // Get available parent folders, excluding self and all descendants
        $folders = Folder::with(['parent', 'children' => function($query) {
                $query->whereNull('deleted_at');
            }])
            ->whereNull('deleted_at')
            ->where('id', '!=', $folder->id)
            ->whereNotIn('id', $descendantIds)
            ->get();

        $folder->load(['users', 'derivedFolders', 'templateFolder']);

        return view('admin.folders.edit', compact('folder', 'users', 'folders'));
    }

    public function update(Request $request, Folder $folder)
    {
        // Check if user can edit this folder
        if (!auth()->user()->is_admin && $folder->created_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Validate basic fields
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($folder) {
                    // Check if name is unique within the same parent folder
                    $exists = Folder::where('name', $value)
                        ->where('parent_id', $folder->parent_id)
                        ->where('id', '!=', $folder->id)
                        ->exists();
                    if ($exists) {
                        $fail('A folder with this name already exists in the selected location.');
                    }
                },
            ],
            'description' => 'nullable|string',
            'is_public' => 'boolean',
            'allow_uploads' => 'boolean',
            'parent_id' => [
                'nullable',
                'exists:folders,id',
                function ($attribute, $value, $fail) use ($folder) {
                    if ($value === $folder->id) {
                        $fail('A folder cannot be its own parent.');
                    }
                    if ($folder->wouldCreateCycle($value)) {
                        $fail('Cannot move folder under one of its descendants.');
                    }
                },
            ],
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        try {
            DB::transaction(function () use ($folder, $validated, $request) {
                // Common attributes for both main folder and derived folders
                $folderAttributes = [
                    'name' => $validated['name'],
                    'description' => $validated['description'] ?? null,
                    'is_public' => $request->boolean('is_public', false),
                    'allow_uploads' => $request->boolean('allow_uploads', false),
                    'parent_id' => $validated['parent_id']
                ];

                // Check if this is moving to a new parent
                $isMoving = $folder->parent_id !== $validated['parent_id'];

                // Update the main folder
                $folder->update($folderAttributes);

                // If this is a template folder, update all derived folders
                if ($folder->derivedFolders()->exists()) {
                    $folder->derivedFolders()->update($folderAttributes);
                    
                    // Show warning about user assignments being ignored for template folders
                    if ($request->has('user_ids')) {
                        session()->flash('warning', 'User assignments cannot be modified for template folders as each derived folder maintains its own user assignments.');
                    }

                    // Show info about derived folders being updated
                    $derivedCount = $folder->derivedFolders()->count();
                    session()->flash('info', "Updated {$derivedCount} derived folder(s) with the new settings.");
                } else {
                    // Regular folder, sync users
                    $previousUsers = $folder->users->pluck('id')->toArray();
                    $newUsers = $validated['user_ids'] ?? [];
                    $folder->users()->sync($newUsers);

                    // Show message about user changes if any
                    $addedCount = count(array_diff($newUsers, $previousUsers));
                    $removedCount = count(array_diff($previousUsers, $newUsers));
                    if ($addedCount || $removedCount) {
                        $changes = [];
                        if ($addedCount) $changes[] = "{$addedCount} user(s) added";
                        if ($removedCount) $changes[] = "{$removedCount} user(s) removed";
                        session()->flash('info', 'User assignments updated: ' . implode(', ', $changes) . '.');
                    }
                }

                // Show message about folder move if applicable
                if ($isMoving) {
                    $newLocation = $validated['parent_id'] 
                        ? Folder::find($validated['parent_id'])->name 
                        : 'root folder';
                    session()->flash('info', "Folder moved to {$newLocation}.");
                }
            });

            return redirect()->route('admin.folders.index')
                ->with('success', 'Folder updated successfully.');

        } catch (\Exception $e) {
            report($e); // Log the error
            
            $errorMessage = app()->environment('local') 
                ? $e->getMessage() 
                : 'Failed to update folder. Please try again.';
            
            return redirect()->back()
                ->withInput()
                ->with('error', $errorMessage);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Folder $folder)
    {
        // Soft delete the folder and its descendants
        DB::transaction(function () use ($folder) {
            $folder->delete(); // This triggers the deleting event in the Folder model
        });

        return redirect()->route('admin.folders.index')->with('success', 'Folder and its contents moved to trash.');
    }

    public function show(Folder $folder)
    {
        $folder->load([
            'files' => function ($query) {
                $query->with('uploader:id,name')->latest();
            },
            'children' => function($query) {
                $query->whereNull('deleted_at')
                    ->withCount(['files', 'children' => function($q) {
                        $q->whereNull('deleted_at');
                    }]);
            }
        ]);

        return view('admin.folders.show', compact('folder'));
    }

    /**
     * Bulk delete the specified folders.
     */
    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return response()->json(['message' => 'No folders selected.'], 400);
        }

        // Soft delete selected folders and their descendants
        DB::transaction(function () use ($ids) {
            $folders = Folder::whereIn('id', $ids)->get();
            foreach ($folders as $folder) {
                $folder->delete(); // Triggers deleting event
            }
        });

        return response()->json(['message' => count($ids) . ' folders and their contents moved to trash.']);
    }

    /**
     * Create default folder structures for all companies 
     * belonging to regular users that don't have one yet.
     */
    public function createMissingStructures(Request $request, FolderStructureService $folderService)
    {
        $users = User::where('is_admin', false)
                    ->where('is_accountant', false)
                    ->with('companies') // Eager load companies
                    ->get();

        $createdCount = 0;
        $skippedCount = 0;

        foreach ($users as $user) {
            foreach ($user->companies as $company) {
                // Check if a root folder already exists for this company
                $existingFolder = Folder::where('company_id', $company->id)
                                        ->whereNull('parent_id')
                                        ->exists();

                if (!$existingFolder) {
                    try {
                        $folderService->createCompanyFolders($user, $company);
                        $createdCount++;
                    } catch (\Exception $e) {
                        Log::error("Error creating folder structure for company {$company->id} (User {$user->id}): " . $e->getMessage());
                        // Optionally flash an error message, but continue processing other users/companies
                    }
                } else {
                    $skippedCount++;
                }
            }
        }

        return redirect()->route('admin.folders.index')->with('success', "Checked companies. Created {$createdCount} new folder structures. Skipped {$skippedCount} existing structures.");
    }

    /**
     * !!! DANGER ZONE !!!
     * Delete ALL folders and ALL files from the database and storage.
     * Requires careful implementation and confirmation.
     */
    public function deleteAll(Request $request)
    {
        // --- IMPLEMENTATION NOTES ---
        // 1. **EXTREME CAUTION:** This is highly destructive.
        // 2. Add multiple confirmations (e.g., type "DELETE EVERYTHING" to confirm).
        // 3. Database Operations:
        //    - `DB::table('files')->delete();` (or truncate)
        //    - `DB::table('folder_user')->delete();` (or truncate)
        //    - `DB::table('folders')->delete();` (or truncate) - Order matters due to FKs if not truncating.
        // 4. Storage Operations:
        //    - Get BunnyAdapter instance.
        //    - List all objects/directories in the storage zone root (or specific base paths like 'folders/', 'files/').
        //    - Iterate and delete each object/directory.
        // 5. Logging: Log the start and end of this operation.
        // 6. Authorization: Ensure only the super admin can do this.
        // 7. Consider a maintenance mode toggle.

        Log::warning("ADMIN ACTION: deleteAll method called but not yet implemented.");
        // Placeholder - DO NOT IMPLEMENT FULLY WITHOUT CAREFUL REVIEW
        // DB::table('files')->delete();
        // DB::table('folder_user')->delete();
        // DB::table('folders')->delete();
        // Clear storage...

        // return redirect()->route('admin.folders.index')->with('warning', 'DELETE ALL action initiated (placeholder - nothing deleted yet).');
        return redirect()->route('admin.folders.index')->with('error', 'DELETE ALL functionality is not yet safely implemented.');
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
            
            // Try to delete the folder itself (may or may not work depending on if it's empty)
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