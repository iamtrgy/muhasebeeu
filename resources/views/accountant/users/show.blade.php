<x-accountant.layout 
    title="{{ $user->name }}" 
    :breadcrumbs="[
        ['title' => __('Dashboard'), 'href' => route('accountant.dashboard'), 'first' => true],
        ['title' => __('Users'), 'href' => route('accountant.users.index')],
        ['title' => $user->name]
    ]"
>
    <div class="space-y-6">
        <!-- Overview Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- User Info Card -->
            <x-ui.card.base class="md:col-span-2">
                <x-ui.card.body class="p-6">
                    <div class="flex items-center">
                        <x-ui.avatar name="{{ $user->name }}" size="lg" class="flex-shrink-0" />
                        <div class="ml-4 flex-1">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                            <div class="mt-2 flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                                @if($user->phone)
                                    <span>📞 {{ $user->phone }}</span>
                                @endif
                                <span>🌍 {{ $user->country->name ?? 'N/A' }}</span>
                                <span>📅 {{ $user->created_at->format('M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            <!-- Folders Stat -->
            <x-ui.card.base class="hover:shadow-lg transition-shadow">
                <x-ui.card.body class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-lg p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Folders') }}</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $folders->count() }}</div>
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
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $folders->sum(function($folder) { return $folder->files->count(); }) }}</div>
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>
        </div>

        <!-- Document Management Section -->
        <x-ui.card.base>
            <x-ui.card.header>
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Document Folders') }}</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Browse and manage user documents') }}</p>
                    </div>
                    @if($folders->count() > 0)
                        <div class="flex items-center gap-3">
                            <!-- Search -->
                            <div class="relative">
                                <input 
                                    type="text" 
                                    x-model="searchTerm"
                                    placeholder="{{ __('Search folders...') }}"
                                    class="w-64 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                    x-on:input="filterFolders"
                                >
                                <svg class="absolute right-3 top-2.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            
                            <!-- Filter Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <x-ui.button.secondary size="sm" x-on:click="open = !open">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z" />
                                    </svg>
                                    {{ __('Filter') }}
                                </x-ui.button.secondary>
                                
                                <div x-show="open" x-transition @click.outside="open = false" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg z-10 border border-gray-200 dark:border-gray-700">
                                    <div class="py-1">
                                        <button class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700" x-on:click="filterByStatus('all'); open = false">
                                            {{ __('All Folders') }}
                                        </button>
                                        <button class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700" x-on:click="filterByStatus('public'); open = false">
                                            {{ __('Public Only') }}
                                        </button>
                                        <button class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700" x-on:click="filterByStatus('private'); open = false">
                                            {{ __('Private Only') }}
                                        </button>
                                        <button class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700" x-on:click="sortBy('name'); open = false">
                                            {{ __('Sort by Name') }}
                                        </button>
                                        <button class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700" x-on:click="sortBy('files'); open = false">
                                            {{ __('Sort by Files') }}
                                        </button>
                                        <button class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700" x-on:click="sortBy('modified'); open = false">
                                            {{ __('Sort by Modified') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </x-ui.card.header>
            <x-ui.card.body>
                @if($folders->count() > 0)
                    <div x-data="folderManager()" x-init="initFolders(@js($folders->toArray()))">
                        <x-ui.table.base>
                            <x-slot name="head">
                                <x-ui.table.head-cell>{{ __('Folder Name') }}</x-ui.table.head-cell>
                                <x-ui.table.head-cell>{{ __('Files') }}</x-ui.table.head-cell>
                                <x-ui.table.head-cell>{{ __('Size') }}</x-ui.table.head-cell>
                                <x-ui.table.head-cell>{{ __('Last Modified') }}</x-ui.table.head-cell>
                                <x-ui.table.head-cell>{{ __('Status') }}</x-ui.table.head-cell>
                                <x-ui.table.head-cell class="text-right">{{ __('Actions') }}</x-ui.table.head-cell>
                            </x-slot>
                            <x-slot name="body">
                                <template x-for="folder in filteredFolders" :key="folder.id">
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        <x-ui.table.cell>
                                            <a x-bind:href="window.location.origin + '/accountant/users/{{ $user->id }}/folders/' + folder.id" 
                                               class="flex items-center hover:text-blue-600 dark:hover:text-blue-400 group">
                                                <div class="flex-shrink-0">
                                                    <svg class="h-5 w-5 text-yellow-500 group-hover:text-yellow-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                                    </svg>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100" x-text="folder.name"></div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400" x-text="folder.description" x-show="folder.description"></div>
                                                </div>
                                            </a>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell>
                                            <div class="flex items-center">
                                                <span x-text="folder.files_count" class="text-sm font-medium text-gray-900 dark:text-gray-100"></span>
                                                <svg class="h-4 w-4 ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell>
                                            <span x-text="folder.total_size" class="text-sm text-gray-900 dark:text-gray-100"></span>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell>
                                            <span x-text="folder.last_modified_human" class="text-sm text-gray-500 dark:text-gray-400" x-show="folder.last_modified_human"></span>
                                            <span x-show="!folder.last_modified_human" class="text-sm text-gray-400">-</span>
                                        </x-ui.table.cell>
                                        <x-ui.table.cell>
                                            <x-ui.badge x-bind:variant="folder.is_public ? 'success' : 'secondary'" x-text="folder.is_public ? '{{ __('Public') }}' : '{{ __('Private') }}'"></x-ui.badge>
                                        </x-ui.table.cell>
                                        <x-ui.table.action-cell>
                                            <div class="flex items-center gap-2">
                                                <x-ui.button.secondary size="sm" x-bind:href="window.location.origin + '/accountant/users/{{ $user->id }}/folders/' + folder.id">
                                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    {{ __('View') }}
                                                </x-ui.button.secondary>
                                                <span x-show="folder.files_count > 0" class="text-xs px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full" x-text="`${folder.files_count} files`"></span>
                                            </div>
                                        </x-ui.table.action-cell>
                                    </tr>
                                </template>
                            </x-slot>
                        </x-ui.table.base>
                        
                        <!-- No results after filtering -->
                        <div x-show="filteredFolders.length === 0" class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('No folders found') }}</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Try adjusting your search or filter criteria') }}</p>
                        </div>
                    </div>
                @else
                    <x-ui.table.empty-state>
                        <x-slot name="icon">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                            </svg>
                        </x-slot>
                        <x-slot name="title">{{ __('No Folders') }}</x-slot>
                        <x-slot name="description">{{ __('This user has not created any folders yet.') }}</x-slot>
                    </x-ui.table.empty-state>
                @endif
            </x-ui.card.body>
        </x-ui.card.base>

        <!-- Companies Section -->
        <x-ui.card.base>
            <x-ui.card.header>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('User Companies') }}</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Companies owned by this user') }}</p>
            </x-ui.card.header>
            <x-ui.card.body>
                @if($companies->count() > 0)
                    <x-ui.table.base>
                        <x-slot name="head">
                            <x-ui.table.head-cell>{{ __('Name') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Country') }}</x-ui.table.head-cell>
                            <x-ui.table.head-cell>{{ __('Tax Number') }}</x-ui.table.head-cell>
                        </x-slot>
                        <x-slot name="body">
                            @foreach($companies as $company)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <x-ui.table.cell>
                                        <a href="{{ route('accountant.companies.show', $company) }}" class="flex items-center hover:text-blue-600 dark:hover:text-blue-400">
                                            <x-ui.avatar name="{{ $company->name }}" size="md" class="flex-shrink-0" />
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $company->name }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $company->email }}</div>
                                            </div>
                                        </a>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $company->country->name ?? 'N/A' }}</div>
                                    </x-ui.table.cell>
                                    <x-ui.table.cell>
                                        <div class="text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $company->tax_number ?? 'N/A' }}</div>
                                    </x-ui.table.cell>
                                </tr>
                            @endforeach
                        </x-slot>
                    </x-ui.table.base>
                @else
                    <x-ui.table.empty-state>
                        <x-slot name="icon">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </x-slot>
                        <x-slot name="title">{{ __('No Companies') }}</x-slot>
                        <x-slot name="description">{{ __('This user has not created any companies yet.') }}</x-slot>
                    </x-ui.table.empty-state>
                @endif
            </x-ui.card.body>
        </x-ui.card.base>
    </div>
    <x-folder.file-preview-modal />
    
    <script>
        function folderManager() {
            return {
                folders: [],
                filteredFolders: [],
                searchTerm: '',
                filterStatus: 'all',
                sortField: 'name',
                
                initFolders(foldersData) {
                    this.folders = foldersData;
                    this.filteredFolders = [...this.folders];
                },
                
                filterFolders() {
                    let filtered = [...this.folders];
                    
                    // Search filter
                    if (this.searchTerm) {
                        filtered = filtered.filter(folder => 
                            folder.name.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                            (folder.description && folder.description.toLowerCase().includes(this.searchTerm.toLowerCase()))
                        );
                    }
                    
                    // Status filter
                    if (this.filterStatus !== 'all') {
                        filtered = filtered.filter(folder => {
                            if (this.filterStatus === 'public') return folder.is_public;
                            if (this.filterStatus === 'private') return !folder.is_public;
                            return true;
                        });
                    }
                    
                    this.filteredFolders = filtered;
                },
                
                filterByStatus(status) {
                    this.filterStatus = status;
                    this.filterFolders();
                },
                
                sortBy(field) {
                    this.sortField = field;
                    this.filteredFolders.sort((a, b) => {
                        if (field === 'name') {
                            return a.name.localeCompare(b.name);
                        } else if (field === 'files') {
                            return b.files_count - a.files_count;
                        } else if (field === 'modified') {
                            return new Date(b.updated_at) - new Date(a.updated_at);
                        }
                        return 0;
                    });
                }
            }
        }
    </script>
</x-accountant.layout> 