@php
    // Build folder breadcrumbs
    $folderBreadcrumbs = collect([]);
    $current = $folder;
    
    // Build the breadcrumb path
    while ($current) {
        $folderBreadcrumbs->push($current);
        $current = $current->parent;
    }
    
    // Reverse to get the correct order
    $folderBreadcrumbs = $folderBreadcrumbs->reverse();
    
    // Create breadcrumb array for layout
    $breadcrumbsArray = [
        ['title' => __('Dashboard'), 'href' => route('accountant.dashboard'), 'first' => true],
        ['title' => __('Users'), 'href' => route('accountant.users.index')],
        ['title' => $user->name, 'href' => route('accountant.users.show', $user->id)],
    ];
    
    // Add folder breadcrumbs
    foreach ($folderBreadcrumbs as $folderBreadcrumb) {
        if ($folderBreadcrumb->id === $folder->id) {
            $breadcrumbsArray[] = ['title' => $folderBreadcrumb->name];
        } else {
            $breadcrumbsArray[] = [
                'title' => $folderBreadcrumb->name,
                'href' => route('accountant.users.viewFolder', ['userId' => $user->id, 'folderId' => $folderBreadcrumb->id])
            ];
        }
    }
@endphp

<x-accountant.layout 
    title="{{ $folder->name }}" 
    :breadcrumbs="$breadcrumbsArray"
>
    <div class="space-y-6">
            
        <!-- User & Folder Info Card -->
        <x-ui.card.base>
            <x-ui.card.body class="p-6">
                <div class="sm:flex sm:items-center sm:justify-between">
                    <div class="sm:flex sm:items-center">
                        <x-ui.avatar name="{{ $user->name }}" size="xl" class="flex-shrink-0" />
                        <div class="mt-4 sm:mt-0 sm:ml-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                            <div class="mt-2 flex items-center">
                                <svg class="h-4 w-4 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $folder->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Folder Type') }}</dt>
                            <dd class="mt-1">
                                @if($folder->is_public)
                                    <x-ui.badge variant="success">{{ __('Public') }}</x-ui.badge>
                                @else
                                    <x-ui.badge variant="secondary">{{ __('Private') }}</x-ui.badge>
                                @endif
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Sub-folders') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $childFolders->count() }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Files') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $files->count() }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Size') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $folder->totalSize() }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Last Modified') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                @if($folder->lastModified())
                                    {{ $folder->lastModified()->diffForHumans() }}
                                @else
                                    -
                                @endif
                            </dd>
                        </div>

                        @if($folder->description)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Description') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $folder->description }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </x-ui.card.body>
        </x-ui.card.base>

        <!-- Child Folders Section -->
        @if($childFolders->count() > 0)
            <x-ui.card.base>
                <x-ui.card.header>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Sub-folders') }}</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Folders inside this directory') }}</p>
                </x-ui.card.header>
                <x-ui.card.body>
                    <x-ui.table.base>
                        <x-slot name="head">
                            <x-ui.table.head-cell>{{ __('Folder Name') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Files') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Size') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Last Modified') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Status') }}</x-ui.table.head-cell>
                        </x-slot>
                        <x-slot name="body">
                            @foreach($childFolders as $childFolder)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <x-ui.table.cell>
                                        <a href="{{ route('accountant.users.viewFolder', ['userId' => $user->id, 'folderId' => $childFolder->id]) }}" class="flex items-center hover:text-blue-600 dark:hover:text-blue-400">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $childFolder->name }}</div>
                                                @if($childFolder->description)
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $childFolder->description }}</div>
                                                @endif
                                            </div>
                                        </a>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>{{ $childFolder->files->count() }}</x-ui.table.cell>
                                    <x-ui.table.cell>{{ $childFolder->totalSize() }}</x-ui.table.cell>
                                    <x-ui.table.cell>
                                        @if($childFolder->lastModified())
                                            <span title="{{ $childFolder->lastModified()->format('F j, Y H:i') }}">{{ $childFolder->lastModified()->diffForHumans() }}</span>
                                        @else
                                            -
                                        @endif
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        @if($childFolder->is_public)
                                            <x-ui.badge variant="success">{{ __('Public') }}</x-ui.badge>
                                        @else
                                            <x-ui.badge variant="secondary">{{ __('Private') }}</x-ui.badge>
                                        @endif
                                    </x-ui.table.cell>
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
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Files') }}</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Documents stored in this folder') }}</p>
            </x-ui.card.header>
            <x-ui.card.body>
                @if($files->count() > 0)
                    <x-ui.table.base>
                        <x-slot name="head">
                            <x-ui.table.head-cell>{{ __('File Name') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Size') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Type') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Uploaded') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell class="text-right">{{ __('Actions') }}</x-ui.table.head-cell>
                        </x-slot>
                        <x-slot name="body">
                            @foreach($files as $file)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <x-ui.table.cell>
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                @if(in_array($file->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'text/plain']))
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400 cursor-pointer" 
                                                         x-on:click="
                                                             $dispatch('file-preview-data', {
                                                                 name: '{{ $file->original_name }}',
                                                                 type: '{{ $file->mime_type }}',
                                                                 previewUrl: '{{ route('accountant.files.preview', $file) }}',
                                                                 downloadUrl: '{{ route('accountant.files.download', $file) }}'
                                                             });
                                                             $dispatch('open-modal', 'file-preview')
                                                         ">
                                                        {{ $file->original_name }}
                                                    </div>
                                                @else
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $file->original_name }}
                                                    </div>
                                                @endif
                                                <div class="text-xs text-gray-500 dark:text-gray-400">ID: {{ $file->id }}</div>
                                            </div>
                                        </div>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>{{ $file->human_readable_size }}</x-ui.table.cell>
                                    <x-ui.table.cell>
                                        <x-ui.badge variant="secondary">{{ $file->mime_type }}</x-ui.badge>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $file->created_at->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $file->created_at->diffForHumans() }}</div>
                                    </x-ui.table.cell>
                                    <x-ui.table.action-cell>
                                        <x-ui.button.secondary size="sm" href="{{ route('accountant.files.download', $file) }}">
                                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            {{ __('Download') }}
                                        </x-ui.button.secondary>
                                    </x-ui.table.action-cell>
                                </tr>
                            @endforeach
                        </x-slot>
                    </x-ui.table.base>
                @else
                    <x-ui.table.empty-state>
                        <x-slot name="icon">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </x-slot>
                        <x-slot name="title">{{ __('No Files') }}</x-slot>
                        <x-slot name="description">{{ __('This folder does not contain any files yet.') }}</x-slot>
                    </x-ui.table.empty-state>
                @endif
            </x-ui.card.body>
        </x-ui.card.base>
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
</x-accountant.layout> 