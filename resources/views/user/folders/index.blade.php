<x-user.layout 
    title="My Folders"
    :breadcrumbs="[
        ['title' => __('Home'), 'href' => route('user.dashboard'), 'first' => true],
        ['title' => __('My Folders')]
    ]"
>
    <div class="space-y-6">

        {{-- Folders Table --}}
        <x-ui.card.base>
            <x-ui.card.header>
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('My Folders') }}</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                        {{ __('Access your document folders and files') }} ({{ $folders->count() }} {{ $folders->count() === 1 ? 'folder' : 'folders' }})
                    </p>
                </div>
            </x-ui.card.header>
            <x-ui.card.body>
                @if($folders->isEmpty())
                    <x-ui.table.empty-state>
                        <x-slot name="icon">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                            </svg>
                        </x-slot>
                        <x-slot name="title">{{ __('No Folders Found') }}</x-slot>
                        <x-slot name="description">
                            {{ __('No folders have been created for you yet. Contact your administrator.') }}
                        </x-slot>
                    </x-ui.table.empty-state>
                @else
                    <x-ui.table.base>
                        <x-slot name="head">
                            <x-ui.table.head-cell>{{ __('Folder Name') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Files') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Size') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Last Modified') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell align="right">{{ __('Actions') }}</x-ui.table.head-cell>
                        </x-slot>
                        <x-slot name="body">
                            @foreach($folders as $folder)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <x-ui.table.cell>
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <a href="{{ route('user.folders.show', $folder) }}" class="text-sm font-medium text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400">
                                                    {{ $folder->name }}
                                                </a>
                                                @if($folder->description)
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($folder->description, 50) }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $folder->files->count() }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $folder->files->count() === 1 ? 'file' : 'files' }}
                                        </div>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ number_format($folder->files->sum('size') / 1024, 2) }} KB</div>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $folder->updated_at->format('M d, Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $folder->updated_at->diffForHumans() }}
                                        </div>
                                    </x-ui.table.cell>
                                    <x-ui.table.action-cell>
                                        <a href="{{ route('user.folders.show', $folder) }}" 
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
                @endif
            </x-ui.card.body>
        </x-ui.card.base>
        
        {{-- Recent Uploads Section --}}
        @php
            // Get recent files UPLOADED BY THE USER ONLY
            $recentFiles = \App\Models\File::where('uploaded_by', auth()->id())
            ->whereHas('folder', function($query) {
                $query->where(function($q) {
                    // Get folders where user is explicitly assigned
                    $q->whereHas('users', function($userQuery) {
                        $userQuery->where('user_id', auth()->id());
                    });
                    
                    // Or folders created by the user (if not admin)
                    if (!auth()->user()->is_admin) {
                        $q->orWhere('created_by', auth()->id());
                    }
                });
            })
            ->with('folder')
            ->latest()
            ->take(5)
            ->get();
        @endphp
        
        @if($recentFiles->count() > 0)
            <x-ui.card.base>
                <x-ui.card.header>
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Recent Uploads') }}</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Files you have uploaded recently') }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <x-ui.badge variant="secondary">{{ $recentFiles->count() }} {{ __('recent files') }}</x-ui.badge>
                        </div>
                    </div>
                </x-ui.card.header>
                <x-ui.card.body>
                    <div class="space-y-3" x-data="{ fileData: null }">
                        @foreach($recentFiles as $file)
                            @if($file->uploaded_by == auth()->id())
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/30 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors">
                                <div class="flex items-center space-x-3">
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
                                    <div class="min-w-0 flex-1">
                                        @if(in_array($file->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'text/plain']))
                                            <button type="button" 
                                                    class="text-sm font-medium text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400 text-left group truncate" 
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
                                                <svg class="inline h-3 w-3 ml-1 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 616 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                        @else
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                                {{ $file->name }}
                                            </div>
                                        @endif
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ __('in') }} 
                                            <a href="{{ route('user.folders.show', $file->folder) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                {{ $file->folder->full_path }}
                                            </a>
                                            • {{ number_format($file->size / 1024, 2) }} KB
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $file->created_at->format('M d') }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $file->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                                <div class="ml-4 flex items-center space-x-2">
                                    @if(in_array($file->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'text/plain']))
                                        <button type="button" 
                                             class="p-1 rounded text-gray-400 hover:text-gray-500 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
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
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 616 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                    @endif
                                    <a href="{{ route('user.files.download', $file) }}" 
                                       class="p-1 rounded text-gray-400 hover:text-gray-500 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
                                       title="{{ __('Download') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>
        @endif
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
                <a x-bind:href="currentFile?.downloadUrl" 
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                   x-show="currentFile">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    {{ __('Download') }}
                </a>
                <x-ui.button.secondary x-on:click="$dispatch('close-modal', 'file-preview')">
                    {{ __('Close') }}
                </x-ui.button.secondary>
            </div>
        </div>
    </x-ui.modal.base>
</x-user.layout>
