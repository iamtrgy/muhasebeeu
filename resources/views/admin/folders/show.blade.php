@php
    // Build folder breadcrumbs for internal navigation (separate from header breadcrumbs)
    $folderBreadcrumbs = collect([]);
    $current = $folder;
    
    // Build the breadcrumb path
    while ($current) {
        $folderBreadcrumbs->push($current);
        $current = $current->parent;
    }
    
    // Reverse to get the correct order
    $folderBreadcrumbs = $folderBreadcrumbs->reverse();
@endphp

<x-admin.layout 
    title="{{ $folder->name }}" 
    :breadcrumbs="[
        ['title' => __('Dashboard'), 'href' => route('admin.dashboard'), 'first' => true],
        ['title' => __('File Manager'), 'href' => route('admin.folders.index')],
        ['title' => $folder->name]
    ]"
>
    <div class="space-y-6">
        <!-- Folder Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Folder Info Card -->
            <x-ui.card.base class="lg:col-span-2">
                <x-ui.card.body class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-500 rounded-lg p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $folder->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Admin File Manager') }}</p>
                            <div class="mt-2 flex items-center gap-4">
                                @if($folder->is_public)
                                    <x-ui.badge variant="success">{{ __('Public') }}</x-ui.badge>
                                @else
                                    <x-ui.badge variant="secondary">{{ __('Private') }}</x-ui.badge>
                                @endif
                                @if($folder->allow_uploads)
                                    <x-ui.badge variant="primary">{{ __('Uploads Allowed') }}</x-ui.badge>
                                @endif
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $folder->totalSize() ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                    @if($folder->description)
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $folder->description }}</p>
                        </div>
                    @endif
                </x-ui.card.body>
            </x-ui.card.base>

            <!-- Sub-folders Stat -->
            <x-ui.card.base class="hover:shadow-lg transition-shadow">
                <x-ui.card.body class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-lg p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Sub-folders') }}</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $folder->children->count() }}</div>
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            <!-- Files Stat -->
            <x-ui.card.base class="hover:shadow-lg transition-shadow">
                <x-ui.card.body class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-emerald-500 rounded-lg p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Files') }}</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $folder->files->count() }}</div>
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>
        </div>

        <!-- Child Folders Section -->
        @if($folder->children->count() > 0)
            <x-ui.card.base>
                <x-ui.card.header>
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Sub-folders') }}</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Folders inside this directory') }}</p>
                        </div>
                        <x-ui.badge variant="secondary">{{ $folder->children->count() }} {{ __('folders') }}</x-ui.badge>
                    </div>
                </x-ui.card.header>
                <x-ui.card.body>
                    <!-- Folder Path Breadcrumb -->
                    <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="flex items-center space-x-2 text-sm">
                            <a href="{{ route('admin.folders.index') }}" class="flex items-center text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
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
                                    <a href="{{ route('admin.folders.show', $breadcrumb) }}" 
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
                            @foreach($folder->children as $childFolder)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <x-ui.table.cell>
                                        <a href="{{ route('admin.folders.show', $childFolder) }}" class="flex items-center hover:text-blue-600 dark:hover:text-blue-400 group">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-yellow-500 group-hover:text-yellow-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                    <x-ui.table.cell>
                                        <div class="flex items-center">
                                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $childFolder->files->count() }}</span>
                                            <svg class="h-4 w-4 ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>{{ $childFolder->totalSize() ?? 'N/A' }}</x-ui.table.cell>
                                    <x-ui.table.cell>
                                        @if($childFolder->lastModified())
                                            <span class="text-sm text-gray-500 dark:text-gray-400" title="{{ $childFolder->lastModified()->format('F j, Y H:i') }}">{{ $childFolder->lastModified()->diffForHumans() }}</span>
                                        @else
                                            <span class="text-sm text-gray-400">-</span>
                                        @endif
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        @if($childFolder->is_public)
                                            <x-ui.badge variant="success">{{ __('Public') }}</x-ui.badge>
                                        @else
                                            <x-ui.badge variant="secondary">{{ __('Private') }}</x-ui.badge>
                                        @endif
                                    </x-ui.table.cell>
                                    <x-ui.table.action-cell>
                                        <a href="{{ route('admin.folders.show', $childFolder) }}" 
                                           class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                           title="{{ __('View folder') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.folders.edit', $childFolder) }}" 
                                           class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                           title="{{ __('Edit folder') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form method="POST" action="{{ route('admin.folders.destroy', $childFolder) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-1 rounded-lg text-gray-400 hover:text-red-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                                    title="{{ __('Delete folder') }}"
                                                    onclick="return confirm('{{ __('Are you sure you want to delete this folder?') }}')">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                        @if($childFolder->files->count() > 0)
                                            <span class="text-xs px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full ml-2">{{ $childFolder->files->count() }} {{ __('files') }}</span>
                                        @endif
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
                    @if($folder->files->count() > 0)
                        <div class="flex items-center gap-3">
                            <x-ui.badge variant="secondary">{{ $folder->files->count() }} {{ __('files') }}</x-ui.badge>
                            <x-ui.button.secondary size="sm" href="{{ route('admin.folders.index') }}">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                {{ __('Back to Files') }}
                            </x-ui.button.secondary>
                        </div>
                    @endif
                </div>
            </x-ui.card.header>
            <x-ui.card.body>
                <!-- Folder Path Breadcrumb -->
                @if($folderBreadcrumbs->count() >= 1)
                    <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="flex items-center space-x-2 text-sm">
                            <a href="{{ route('admin.folders.index') }}" class="flex items-center text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
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
                                    <a href="{{ route('admin.folders.show', $breadcrumb) }}" 
                                       class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                        {{ $breadcrumb->name }}
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
                
                @if($folder->files->count() > 0)
                    <x-ui.table.base>
                        <x-slot name="head">
                            <x-ui.table.head-cell width="30%">{{ __('File Name') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell width="10%">{{ __('Size') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell width="10%">{{ __('Type') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell width="25%">{{ __('Notes') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell width="15%">{{ __('Uploaded') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell width="10%" align="right">{{ __('Actions') }}</x-ui.table.head-cell>
                        </x-slot>
                        <x-slot name="body">
                            @foreach($folder->files as $file)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" data-file-id="{{ $file->id }}">
                                    <x-ui.table.cell>
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                @if(in_array($file->mime_type ?? 'unknown', ['image/jpeg', 'image/png', 'image/gif']))
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
                                                @if(in_array($file->mime_type ?? 'unknown', ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'text/plain']))
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400 cursor-pointer group" 
                                                         x-on:click="
                                                             $dispatch('file-preview-data', {
                                                                 id: {{ $file->id }},
                                                                 name: '{{ $file->filename ?? $file->original_name }}',
                                                                 type: '{{ $file->mime_type ?? 'unknown' }}',
                                                                 previewUrl: '{{ route('admin.files.preview', $file) }}',
                                                                 downloadUrl: '{{ route('admin.files.download', $file) }}',
                                                                 updateNotesUrl: '{{ route('admin.files.update-notes', $file->id) }}',
                                                                 notes: @js($file->notes ?? '')
                                                             });
                                                             $dispatch('open-modal', 'file-preview')
                                                         ">
                                                        {{ $file->filename ?? $file->original_name }}
                                                        <svg class="inline h-4 w-4 ml-1 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </div>
                                                @else
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $file->filename ?? $file->original_name }}
                                                    </div>
                                                @endif
                                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $file->uploader ? $file->uploader->name : 'System' }}</div>
                                            </div>
                                        </div>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $file->human_readable_size ?? ($file->size ? number_format($file->size / 1024, 1) . ' KB' : 'N/A') }}</span>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        @if($file->mime_type === 'application/pdf')
                                            <x-ui.badge variant="danger">PDF</x-ui.badge>
                                        @elseif(str_starts_with($file->mime_type ?? '', 'image/'))
                                            <x-ui.badge variant="success">Image</x-ui.badge>
                                        @elseif($file->mime_type === 'text/plain')
                                            <x-ui.badge variant="secondary">Text</x-ui.badge>
                                        @else
                                            <x-ui.badge variant="secondary">{{ strtoupper(pathinfo($file->filename ?? $file->original_name, PATHINFO_EXTENSION)) }}</x-ui.badge>
                                        @endif
                                    </x-ui.table.cell>
                                    <x-ui.table.cell :nowrap="false" style="max-width: 300px; width: 25%;">
                                        @if(method_exists('App\View\Components\Ui\Table\EditableCell', '__construct'))
                                            <x-ui.table.editable-cell 
                                                :value="$file->notes ?? ''"
                                                placeholder="Add notes..."
                                                :route="route('admin.files.update-notes', $file->id)"
                                                field="notes"
                                                type="textarea"
                                                :maxLength="1000"
                                                class="editable-note-cell"
                                                :file="[
                                                    'id' => $file->id,
                                                    'original_name' => $file->filename ?? $file->original_name,
                                                    'mime_type' => $file->mime_type ?? 'unknown',
                                                    'preview_url' => route('admin.files.preview', $file),
                                                    'download_url' => route('admin.files.download', $file)
                                                ]"
                                            />
                                        @else
                                            <div class="text-sm text-gray-700 dark:text-gray-300">
                                                {{ $file->notes ? Str::limit($file->notes, 50) : __('No notes') }}
                                            </div>
                                        @endif
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $file->created_at->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $file->created_at->diffForHumans() }}</div>
                                    </x-ui.table.cell>
                                    <x-ui.table.action-cell>
                                        @if(in_array($file->mime_type ?? 'unknown', ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'text/plain']))
                                            <button type="button" 
                                                 class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                                 title="{{ __('Preview') }}"
                                                 x-on:click="
                                                     $dispatch('file-preview-data', {
                                                         id: {{ $file->id }},
                                                         name: '{{ $file->filename ?? $file->original_name }}',
                                                         type: '{{ $file->mime_type ?? 'unknown' }}',
                                                         previewUrl: '{{ route('admin.files.preview', $file) }}',
                                                         downloadUrl: '{{ route('admin.files.download', $file) }}',
                                                         updateNotesUrl: '{{ route('admin.files.update-notes', $file->id) }}',
                                                         notes: @js($file->notes ?? '')
                                                     });
                                                     $dispatch('open-modal', 'file-preview')
                                                 ">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 616 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                        @endif
                                        <a href="{{ route('admin.files.download', $file) }}" 
                                           class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                           title="{{ __('Download') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </a>
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
                        <x-slot name="action">
                            <x-ui.button.secondary href="{{ route('admin.folders.index') }}">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                {{ __('Back to Files') }}
                            </x-ui.button.secondary>
                        </x-slot>
                    </x-ui.table.empty-state>
                @endif
            </x-ui.card.body>
        </x-ui.card.base>
    </div>
    
    <!-- File Preview Modal -->
    <x-ui.modal.base name="file-preview" maxWidth="4xl">
        <div class="text-center" 
             x-data="{ 
                 currentFile: null,
                 editingNote: false, 
                 noteValue: '', 
                 originalNote: '',
                 saving: false,
                 error: null,
                 
                 startEdit() {
                     this.editingNote = true;
                     this.noteValue = this.currentFile?.notes || '';
                     this.originalNote = this.noteValue;
                     this.$nextTick(() => {
                         if (this.$refs.noteTextarea) {
                             this.$refs.noteTextarea.focus();
                         }
                     });
                 },
                 
                 cancelEdit() {
                     this.editingNote = false;
                     this.noteValue = this.originalNote;
                     this.error = null;
                 },
                 
                 async saveNote() {
                     if (this.noteValue === this.originalNote) {
                         this.editingNote = false;
                         return;
                     }
                     
                     this.saving = true;
                     this.error = null;
                     
                     try {
                         if (!this.currentFile || !this.currentFile.id) {
                             throw new Error('No file selected');
                         }
                         
                         const response = await fetch(this.currentFile.updateNotesUrl, {
                             method: 'PATCH',
                             headers: {
                                 'Content-Type': 'application/json',
                                 'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').content,
                                 'Accept': 'application/json',
                                 'X-Requested-With': 'XMLHttpRequest'
                             },
                             body: JSON.stringify({
                                 notes: this.noteValue
                             })
                         });
                         
                         const data = await response.json();
                         
                         if (response.ok && data.success) {
                             // Update the current file data
                             this.currentFile.notes = this.noteValue;
                             this.originalNote = this.noteValue;
                             this.editingNote = false;
                             
                             // Show success feedback
                             if (typeof toastr !== 'undefined') {
                                 toastr.success(data.message || 'Notes updated successfully');
                             }
                         } else {
                             throw new Error(data.message || 'Update failed');
                         }
                     } catch (err) {
                         this.error = err.message;
                         if (typeof toastr !== 'undefined') {
                             toastr.error(this.error);
                         }
                     } finally {
                         this.saving = false;
                     }
                 }
             }" 
             x-on:file-preview-data.window="currentFile = $event.detail; console.log('File preview data received:', $event.detail)">
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
                    <iframe x-bind:src="currentFile?.previewUrl" class="w-full h-64 border border-gray-200 rounded-lg"></iframe>
                </div>
            </div>
            
            {{-- Notes Section --}}
            <div x-show="currentFile" class="mt-1 border-t border-gray-200 dark:border-gray-700 pt-1">
                <div class="text-left">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                            <div class="flex items-center justify-center w-6 h-6 bg-amber-100 dark:bg-amber-900/30 rounded-full mr-2">
                                <svg class="w-3 h-3 text-amber-600 dark:text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            Important Notes
                        </h4>
                        <div class="flex items-center gap-2">
                            <div x-show="currentFile?.notes && !editingNote" class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-xs text-green-600 dark:text-green-400 font-medium">Has Notes</span>
                            </div>
                            <button x-show="currentFile && !editingNote" 
                                    @click="startEdit()"
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium text-amber-700 dark:text-amber-300 bg-amber-100 dark:bg-amber-900/50 hover:bg-amber-200 dark:hover:bg-amber-900/70 rounded-md transition-colors">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                <span x-text="currentFile?.notes ? 'Edit Note' : 'Add Note'"></span>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Note Editing Mode -->
                    <div x-show="editingNote" class="space-y-3">
                        <div>
                            <textarea x-ref="noteTextarea"
                                      x-model="noteValue"
                                      @keydown.escape="cancelEdit()"
                                      @keydown.ctrl.enter="saveNote()"
                                      placeholder="Add notes for this file..."
                                      rows="4"
                                      maxlength="1000"
                                      :disabled="saving"
                                      class="w-full px-3 py-2 text-sm border border-amber-300 dark:border-amber-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 dark:bg-gray-700 dark:text-gray-100 resize-none"></textarea>
                            <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                <span x-text="noteValue.length"></span>/1000 characters • Ctrl+Enter to save
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div x-show="error" class="text-xs text-red-500" x-text="error"></div>
                            <div class="flex items-center gap-2 ml-auto">
                                <button @click="cancelEdit()" 
                                        :disabled="saving"
                                        class="inline-flex items-center px-3 py-1 text-xs font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-md transition-colors disabled:opacity-50">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Cancel
                                </button>
                                <button @click="saveNote()" 
                                        :disabled="saving"
                                        class="inline-flex items-center px-3 py-1 text-xs font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-md transition-colors disabled:opacity-50">
                                    <svg x-show="!saving" class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <svg x-show="saving" class="w-3 h-3 mr-1 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span x-text="saving ? 'Saving...' : 'Save'"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Note Display Mode -->
                    <div x-show="!editingNote">
                        <!-- Notes exist -->
                        <div x-show="currentFile?.notes" 
                             x-data="{ expanded: false }"
                             class="bg-gradient-to-r from-amber-50 to-yellow-50 dark:from-amber-900/20 dark:to-yellow-900/20 border border-amber-200 dark:border-amber-700 rounded-lg px-3 py-2 text-sm text-gray-800 dark:text-gray-200 break-words">
                            
                            <!-- Truncated view -->
                            <div x-show="!expanded && currentFile?.notes && currentFile.notes.length > 150">
                                <p x-text="currentFile.notes.substring(0, 150) + '...'" class="leading-normal"></p>
                                <button @click="expanded = true" 
                                        class="inline-flex items-center mt-2 text-xs text-amber-700 dark:text-amber-300 hover:text-amber-800 dark:hover:text-amber-200 font-medium">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                    View More
                                </button>
                            </div>
                            
                            <!-- Full view -->
                            <div x-show="expanded || (currentFile?.notes && currentFile.notes.length <= 150)">
                                <p x-text="currentFile?.notes" class="leading-normal"></p>
                                <button x-show="expanded" 
                                        @click="expanded = false" 
                                        class="inline-flex items-center mt-2 text-xs text-amber-700 dark:text-amber-300 hover:text-amber-800 dark:hover:text-amber-200 font-medium">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                    </svg>
                                    View Less
                                </button>
                            </div>
                        </div>
                        
                        <!-- No notes -->
                        <div x-show="!currentFile?.notes" 
                             class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-500 dark:text-gray-400 italic text-center flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.314 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                            No notes available for this file
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-center gap-3">
                <x-ui.button.primary x-bind:href="currentFile?.downloadUrl" 
                                    x-show="currentFile?.downloadUrl"
                                    download>
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
</x-admin.layout>