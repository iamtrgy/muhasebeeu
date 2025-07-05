<x-admin.layout 
    title="{{ __('File Manager') }}"
    :breadcrumbs="[
        ['title' => __('Dashboard'), 'href' => route('admin.dashboard'), 'first' => true],
        ['title' => __('File Manager')]
    ]"
>
    <div class="space-y-6">
        <!-- Page Header with Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Manage folders and files across the system') }}
                </p>
            </div>
            <div class="flex gap-3">
                <form action="{{ route('admin.folders.create-missing-structures') }}" method="POST" class="inline" 
                      onsubmit="return confirm('{{ __('Are you sure you want to create missing folder structures for all relevant users and companies?') }}');">
                    @csrf
                    <x-ui.button.secondary type="submit">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                        </svg>
                        {{ __('Create Missing Structures') }}
                    </x-ui.button.secondary>
                </form>
                <x-ui.button.primary href="{{ route('admin.folders.create') }}">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    {{ __('Create Folder') }}
                </x-ui.button.primary>
            </div>
        </div>

        <!-- Search Card -->
        <x-ui.card.base>
            <x-ui.card.body>
                <div class="relative">
                    <x-ui.form.input
                        type="search"
                        id="folderSearch"
                        name="search"
                        placeholder="{{ __('Search folders...') }}"
                        leadingIcon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>'
                    />
                </div>
            </x-ui.card.body>
        </x-ui.card.base>

        <!-- Folders Table -->
        <x-ui.card.base>
            <x-ui.card.header>
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('System Folders') }}</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('All folders across the system') }}</p>
                    </div>
                    <x-ui.badge variant="secondary">{{ $folders instanceof \Illuminate\Pagination\LengthAwarePaginator ? $folders->total() : $folders->count() }} {{ __('folders') }}</x-ui.badge>
                </div>
            </x-ui.card.header>
            <x-ui.card.body>
                <x-ui.table.base id="foldersTable">
                    <x-slot name="head">
                        <x-ui.table.head-cell width="25%">{{ __('Folder Name') }}</x-ui.table.head-cell>
                        <x-ui.table.head-cell width="15%">{{ __('Files') }}</x-ui.table.head-cell>
                        <x-ui.table.head-cell width="10%">{{ __('Size') }}</x-ui.table.head-cell>
                        <x-ui.table.head-cell width="15%">{{ __('Last Modified') }}</x-ui.table.head-cell>
                        <x-ui.table.head-cell width="15%">{{ __('Status') }}</x-ui.table.head-cell>
                        <x-ui.table.head-cell width="10%">{{ __('Owner') }}</x-ui.table.head-cell>
                        <x-ui.table.head-cell width="10%" align="right">{{ __('Actions') }}</x-ui.table.head-cell>
                    </x-slot>
                    <x-slot name="body">
                        @forelse($folders as $folder)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors folder-row"
                                data-name="{{ strtolower($folder->name) }}"
                                data-description="{{ strtolower($folder->description) }}">
                                
                                <x-ui.table.cell>
                                    <a href="{{ route('admin.folders.show', $folder) }}" class="flex items-center hover:text-blue-600 dark:hover:text-blue-400 group">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-500 group-hover:text-yellow-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $folder->name }}</div>
                                            @if($folder->description)
                                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ Str::limit($folder->description, 60) }}</div>
                                            @endif
                                        </div>
                                    </a>
                                </x-ui.table.cell>
                                
                                <x-ui.table.cell>
                                    <div class="flex items-center">
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $folder->files->count() }}</span>
                                        <svg class="h-4 w-4 ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        @if($folder->children->count() > 0)
                                            <span class="text-xs ml-2 px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full">
                                                {{ $folder->children->count() }} {{ __('sub') }}
                                            </span>
                                        @endif
                                    </div>
                                </x-ui.table.cell>
                                
                                <x-ui.table.cell>{{ $folder->totalSize() ?? 'N/A' }}</x-ui.table.cell>
                                
                                <x-ui.table.cell>
                                    @if($folder->lastModified())
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $folder->lastModified()->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $folder->lastModified()->diffForHumans() }}</div>
                                    @else
                                        <span class="text-sm text-gray-400">—</span>
                                    @endif
                                </x-ui.table.cell>
                                
                                <x-ui.table.cell>
                                    <div class="flex flex-wrap gap-1">
                                        @if($folder->is_public)
                                            <x-ui.badge variant="success" size="sm">{{ __('Public') }}</x-ui.badge>
                                        @else
                                            <x-ui.badge variant="secondary" size="sm">{{ __('Private') }}</x-ui.badge>
                                        @endif
                                        
                                        @if($folder->templateFolder)
                                            <x-ui.badge variant="primary" size="sm">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                                                </svg>
                                                {{ __('Copy') }}
                                            </x-ui.badge>
                                        @endif
                                        
                                        @if($folder->derivedFolders()->exists())
                                            <x-ui.badge variant="warning" size="sm">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                {{ __('Template') }}
                                            </x-ui.badge>
                                        @endif
                                    </div>
                                </x-ui.table.cell>
                                
                                <x-ui.table.cell>
                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ $folder->creator ? Str::limit($folder->creator->name, 15) : __('System') }}
                                    </div>
                                    @if($folder->users->count() > 0)
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            +{{ $folder->users->count() }} {{ __('users') }}
                                        </div>
                                    @endif
                                </x-ui.table.cell>
                                
                                <x-ui.table.action-cell>
                                    <a href="{{ route('admin.folders.show', $folder) }}" 
                                       class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                       title="{{ __('View folder') }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.folders.edit', $folder) }}" 
                                       class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                       title="{{ __('Edit folder') }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.folders.destroy', $folder) }}" class="inline">
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
                                </x-ui.table.action-cell>
                            </tr>
                        @empty
                            <x-ui.table.empty-state 
                                colspan="7"
                                message="{{ __('No folders found. Create your first folder to get started.') }}"
                            />
                        @endforelse
                    </x-slot>
                </x-ui.table.base>

                <!-- Pagination -->
                @if($folders instanceof \Illuminate\Pagination\LengthAwarePaginator && $folders->hasPages())
                    <div class="mt-4">
                        {{ $folders->links() }}
                    </div>
                @endif
            </x-ui.card.body>
        </x-ui.card.base>

        <!-- Quick Actions Card -->
        @if(auth()->user()->is_admin)
        <x-ui.card.base>
            <x-ui.card.header>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Quick Actions') }}
                </h3>
            </x-ui.card.header>
            <x-ui.card.body>
                <div class="flex flex-wrap gap-3">
                    <!-- Danger Zone - Delete All -->
                    <form action="{{ route('admin.folders.delete-all') }}" method="POST" class="inline" 
                          onsubmit="return confirm('{{ __('WARNING: This will delete ALL folders and files. This action cannot be undone. Are you absolutely sure?') }}') && confirm('{{ __('This is your FINAL WARNING. All data will be permanently deleted. Continue?') }}');">
                        @csrf
                        <x-ui.button.danger type="submit" size="sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            {{ __('Delete All Folders') }}
                        </x-ui.button.danger>
                    </form>
                </div>
            </x-ui.card.body>
        </x-ui.card.base>
        @endif
    </div>

    @push('scripts')
    <script>
        // Folder search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('folderSearch');
            const folderRows = document.querySelectorAll('.folder-row');
            const noFoldersMessage = document.getElementById('noFoldersFound');
            const table = document.getElementById('foldersTable');

            if (!searchInput) return;

            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                let visibleCount = 0;

                folderRows.forEach(row => {
                    const name = row.getAttribute('data-name') || '';
                    const description = row.getAttribute('data-description') || '';
                    
                    if (name.includes(searchTerm) || description.includes(searchTerm)) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Show/hide empty state
                if (table) {
                    table.style.display = visibleCount === 0 ? 'none' : '';
                }
                if (noFoldersMessage) {
                    noFoldersMessage.classList.toggle('hidden', visibleCount > 0);
                }
            });
        });
    </script>
    @endpush
</x-admin.layout>