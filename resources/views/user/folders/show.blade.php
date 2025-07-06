@php
    // Build folder breadcrumbs for navigation
    $folderBreadcrumbs = collect([]);
    $current = $folder;
    
    // Build the breadcrumb path
    while ($current) {
        $folderBreadcrumbs->push($current);
        $current = $current->parent;
    }
    
    // Reverse to get the correct order
    $folderBreadcrumbs = $folderBreadcrumbs->reverse();
    
    // Page breadcrumbs
    $pageBreadcrumbs = [
        ['title' => __('Home'), 'href' => route('user.dashboard'), 'first' => true],
        ['title' => __('My Folders'), 'href' => route('user.folders.index')],
    ];
    
    // Add folder hierarchy to breadcrumbs
    foreach($folderBreadcrumbs as $breadcrumbFolder) {
        if ($breadcrumbFolder->id === $folder->id) {
            $pageBreadcrumbs[] = ['title' => $breadcrumbFolder->name];
        } else {
            $pageBreadcrumbs[] = ['title' => $breadcrumbFolder->name, 'href' => route('user.folders.show', $breadcrumbFolder)];
        }
    }
@endphp

<x-user.layout 
    title="{{ $folder->name }}" 
    :breadcrumbs="$pageBreadcrumbs"
>
<div x-data="{ fileData: null }">
    <div class="space-y-6">

        @if(session('success'))
            <x-ui.alert variant="success">{{ session('success') }}</x-ui.alert>
        @endif

        @if(session('error'))
            <x-ui.alert variant="danger">{{ session('error') }}</x-ui.alert>
        @endif

        <!-- Sub-folders Section -->
        @if($subfolders->count() > 0)
            <x-ui.card.base>
                <x-ui.card.header>
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Sub-folders') }}</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Folders inside this directory') }}</p>
                        </div>
                        <x-ui.badge variant="secondary">{{ $subfolders->count() }} {{ __('folders') }}</x-ui.badge>
                    </div>
                </x-ui.card.header>
                <x-ui.card.body>
                    <!-- Folder Path Breadcrumb -->
                    <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="flex items-center space-x-2 text-sm">
                            <a href="{{ route('user.folders.index') }}" class="flex items-center text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                {{ __('Root') }}
                            </a>
                            @foreach($folderBreadcrumbs as $index => $breadcrumb)
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                                @if($breadcrumb->id === $folder->id)
                                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $breadcrumb->name }}</span>
                                @else
                                    <a href="{{ route('user.folders.show', $breadcrumb) }}" 
                                       class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                        {{ $breadcrumb->name }}
                                    </a>
                                @endif
                            @endforeach
                            <span class="text-gray-400 ml-2">{{ __('(Sub-folders)') }}</span>
                        </div>
                    </div>
                    <x-ui.table.base>
                        <x-slot name="head">
                            <x-ui.table.head-cell>{{ __('Folder Name') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Files') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Size') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Last Modified') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Status') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell align="right">{{ __('Actions') }}</x-ui.table.head-cell>
                        </x-slot>
                        <x-slot name="body">
                            @foreach($subfolders as $subfolder)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <x-ui.table.cell>
                                        <a href="{{ route('user.folders.show', $subfolder) }}" class="flex items-center hover:text-blue-600 dark:hover:text-blue-400 group">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-yellow-500 group-hover:text-yellow-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $subfolder->name }}</div>
                                                @if($subfolder->description)
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $subfolder->description }}</div>
                                                @endif
                                            </div>
                                        </a>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        <div class="flex items-center">
                                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $subfolder->files_count }}</span>
                                            <svg class="h-4 w-4 ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>{{ $subfolder->totalSize() ?? '0 B' }}</x-ui.table.cell>
                                    <x-ui.table.cell>
                                        @if($subfolder->lastModified())
                                            <span class="text-sm text-gray-500 dark:text-gray-400" title="{{ $subfolder->lastModified()->format('F j, Y H:i') }}">{{ $subfolder->lastModified()->diffForHumans() }}</span>
                                        @else
                                            <span class="text-sm text-gray-400">-</span>
                                        @endif
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        @if($subfolder->is_public)
                                            <x-ui.badge variant="success">{{ __('Public') }}</x-ui.badge>
                                        @else
                                            <x-ui.badge variant="secondary">{{ __('Private') }}</x-ui.badge>
                                        @endif
                                    </x-ui.table.cell>
                                    <x-ui.table.action-cell>
                                        <a href="{{ route('user.folders.show', $subfolder) }}" 
                                           class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                           title="{{ __('View folder') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 616 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                    </x-ui.table.action-cell>
                                </tr>
                            @endforeach
                        </x-slot>
                    </x-ui.table.base>
                </x-ui.card.body>
            </x-ui.card.base>
        @endif

        <!-- Files Section -->
        <x-ui.card.base>
            <x-ui.card.header>
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Files') }}</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Documents stored in this folder') }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        @if($files->count() > 0)
                            <x-ui.badge variant="secondary">{{ $files->count() }} {{ __('files') }}</x-ui.badge>
                        @endif
                        @if($folder->canUpload(auth()->user()))
                            <x-folder.upload-button :folder="$folder" />
                        @endif
                    </div>
                </div>
            </x-ui.card.header>
            <x-ui.card.body>
                <!-- Folder Path Breadcrumb -->
                @if($folderBreadcrumbs->count() > 1)
                    <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="flex items-center space-x-2 text-sm">
                            <a href="{{ route('user.folders.index') }}" class="flex items-center text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                {{ __('Root') }}
                            </a>
                            @foreach($folderBreadcrumbs as $index => $breadcrumb)
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                                @if($breadcrumb->id === $folder->id)
                                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $breadcrumb->name }}</span>
                                @else
                                    <a href="{{ route('user.folders.show', $breadcrumb) }}" 
                                       class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                        {{ $breadcrumb->name }}
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
                
                @if($files->count() > 0)
                    <x-ui.table.base>
                        <x-slot name="head">
                            <x-ui.table.head-cell>{{ __('File Name') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Size') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Type') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Uploaded') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell align="right">{{ __('Actions') }}</x-ui.table.head-cell>
                        </x-slot>
                        <x-slot name="body">
                            @foreach($files as $file)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <x-ui.table.cell>
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                @if(in_array($file->mime_type, ['image/jpeg', 'image/png', 'image/gif']))
                                                    <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                @elseif($file->mime_type === 'application/pdf')
                                                    <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                    </svg>
                                                @else
                                                    <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                @if(in_array($file->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'text/plain']))
                                                    <button type="button" 
                                                            class="text-sm font-medium text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400 text-left group" 
                                                            x-on:click="
                                                                $dispatch('file-preview-data', {
                                                                    name: '{{ $file->name }}',
                                                                    type: '{{ $file->mime_type }}',
                                                                    previewUrl: '{{ $file->url }}',
                                                                    downloadUrl: '{{ route('user.files.download', $file) }}'
                                                                });
                                                                $dispatch('open-modal', 'file-preview')
                                                            ">
                                                        {{ $file->name }}
                                                        <svg class="inline h-4 w-4 ml-1 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 616 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </button>
                                                @else
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $file->name }}
                                                    </div>
                                                @endif
                                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $file->uploader ? $file->uploader->name : 'System' }}</div>
                                            </div>
                                        </div>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ number_format($file->size / 1024, 2) }} KB</span>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        @if($file->mime_type === 'application/pdf')
                                            <x-ui.badge variant="danger">PDF</x-ui.badge>
                                        @elseif(str_starts_with($file->mime_type, 'image/'))
                                            <x-ui.badge variant="success">Image</x-ui.badge>
                                        @elseif($file->mime_type === 'text/plain')
                                            <x-ui.badge variant="secondary">Text</x-ui.badge>
                                        @else
                                            <x-ui.badge variant="secondary">{{ strtoupper(pathinfo($file->name, PATHINFO_EXTENSION)) }}</x-ui.badge>
                                        @endif
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $file->created_at->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $file->created_at->diffForHumans() }}</div>
                                    </x-ui.table.cell>
                                    <x-ui.table.action-cell>
                                        @if(in_array($file->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'text/plain']))
                                            <button type="button" 
                                                 class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                                 title="{{ __('Preview') }}"
                                                 x-on:click="
                                                     $dispatch('file-preview-data', {
                                                         name: '{{ $file->name }}',
                                                         type: '{{ $file->mime_type }}',
                                                         previewUrl: '{{ $file->url }}',
                                                         downloadUrl: '{{ route('user.files.download', $file) }}'
                                                     });
                                                     $dispatch('open-modal', 'file-preview')
                                                 ">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 616 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                        @endif
                                        <a href="{{ route('user.files.download', $file) }}" 
                                           class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                           title="{{ __('Download') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('user.files.destroy', $file) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-1 rounded-lg text-red-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900 transition-colors" 
                                                    onclick="return confirm('Are you sure you want to delete this file?')"
                                                    title="{{ __('Delete') }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </x-ui.table.action-cell>
                                </tr>
                            @endforeach
                        </x-slot>
                    </x-ui.table.base>
                    
                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $files->links() }}
                    </div>
                @else
                    <x-ui.table.empty-state>
                        <x-slot name="icon">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </x-slot>
                        <x-slot name="title">{{ __('No Files') }}</x-slot>
                        <x-slot name="description">
                            @if($subfolders->isEmpty())
                                {{ __('This folder is empty.') }}
                            @else
                                {{ __('This folder does not contain any files yet.') }}
                            @endif
                        </x-slot>
                        @if($folder->canUpload(auth()->user()))
                            <x-slot name="action">
                                <x-folder.upload-button :folder="$folder" />
                            </x-slot>
                        @endif
                    </x-ui.table.empty-state>
                @endif
            </x-ui.card.body>
        </x-ui.card.base>
    </div>
</div>
    
    <!-- File Preview Modal -->
    <x-ui.modal.base name="file-preview" maxWidth="lg">
        <div class="text-center" x-data="{ currentFile: null }" 
             x-on:file-preview-data.window="currentFile = $event.detail">
            <h3 class="text-lg font-medium mb-4 text-gray-900 dark:text-gray-100" x-text="currentFile?.name || 'File Preview'"></h3>
            
            {{-- Image Preview --}}
            <div x-show="currentFile && ['image/jpeg', 'image/png', 'image/gif'].includes(currentFile.type)">
                <div class="max-w-full max-h-96 overflow-auto">
                    <img x-bind:src="currentFile?.previewUrl" x-bind:alt="currentFile?.name" class="max-w-full h-auto rounded-lg">
                </div>
            </div>

            {{-- PDF Preview --}}
            <div x-show="currentFile && currentFile.type === 'application/pdf'">
                <div class="w-full h-96">
                    <iframe x-bind:src="currentFile?.previewUrl" class="w-full h-full border-0 rounded-lg"></iframe>
                </div>
            </div>

            {{-- Text Preview --}}
            <div x-show="currentFile && currentFile.type === 'text/plain'">
                <div class="max-w-full max-h-96 overflow-auto bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ __('Text file preview') }}</p>
                        <iframe x-bind:src="currentFile?.previewUrl" class="w-full h-64 border border-gray-200 rounded-lg"></iframe>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-center gap-3">
                <x-ui.button.primary x-bind:href="currentFile?.downloadUrl">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    {{ __('Download') }}
                </x-ui.button.primary>
                <x-ui.button.secondary x-on:click="$dispatch('close-modal', 'file-preview')">
                    {{ __('Close') }}
                </x-ui.button.secondary>
            </div>
        </div>
    </x-ui.modal.base>
</x-user.layout>
