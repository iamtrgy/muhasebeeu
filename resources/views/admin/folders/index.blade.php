<x-admin-layout>
    <x-slot name="header">
        <x-admin.page-title title="{{ __('Folders') }}"></x-admin.page-title>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100">
                <!-- Search and Filter Controls -->
                <div class="mb-6 flex flex-col sm:flex-row gap-4 justify-between items-center">
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <x-icons name="search" class="w-4 h-4 text-gray-500 dark:text-gray-400" />
                        </div>
                        <input type="search" id="folderSearch" placeholder="{{ __('Search folders...') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.folders.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <x-icons name="add" class="w-4 h-4 mr-2" />
                            {{ __('CREATE FOLDER') }}
                        </a>

                    </div>
                </div>

                <!-- Folders Table -->
                <x-admin.table id="foldersTable">
                    <x-slot name="header">
                        <x-admin.table.tr>
                            <x-admin.table.th>{{ __('Name') }}</x-admin.table.th>
                            <x-admin.table.th>{{ __('Description') }}</x-admin.table.th>
                            <x-admin.table.th>{{ __('Status') }}</x-admin.table.th>
                            <x-admin.table.th>{{ __('Created By') }}</x-admin.table.th>
                            <x-admin.table.th>{{ __('Assigned Users') }}</x-admin.table.th>
                            <x-admin.table.th class="relative">
                                <span class="sr-only">{{ __('Actions') }}</span>
                            </x-admin.table.th>
                        </x-admin.table.tr>
                    </x-slot>
                    @foreach($folders as $folder)
                        <x-admin.table.tr
                            class="folder-row"
                            data-name="{{ strtolower($folder->name) }}"
                            data-description="{{ strtolower($folder->description) }}">

                            <x-admin.table.td>
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center">
                                        <x-icons name="folder" class="h-8 w-8 text-yellow-500" />
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100 folder-name">
                                            <a href="{{ route('admin.folders.show', $folder) }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                                                {{ $folder->name }}
                                            </a>
                                        </div>
                                        @if($folder->description)
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-1 max-w-xs">
                                                {{ $folder->description }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </x-admin.table.td>
                            <x-admin.table.td>
                                <div class="text-sm text-gray-700 dark:text-gray-300">{{ $folder->description ? Str::limit($folder->description, 100) : '—' }}</div>
                            </x-admin.table.td>
                            <x-admin.table.td>
                                <div class="flex flex-wrap gap-1.5">
                                    <span class="px-2 py-1 inline-flex items-center text-xs font-medium rounded-md {{ $folder->is_public ? 'bg-green-50 text-green-700 dark:bg-green-900/40 dark:text-green-400 ring-1 ring-green-600/20 dark:ring-green-500/40' : 'bg-gray-50 text-gray-700 dark:bg-gray-800 dark:text-gray-300 ring-1 ring-gray-500/20 dark:ring-gray-600/40' }}">
                                        <span class="mr-1 w-1.5 h-1.5 rounded-full {{ $folder->is_public ? 'bg-green-500' : 'bg-gray-500' }}"></span>
                                        {{ $folder->is_public ? __('Public') : __('Private') }}
                                    </span>
                                    @if($folder->templateFolder)
                                        <span class="px-2 py-1 inline-flex items-center text-xs font-medium rounded-md bg-blue-50 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400 ring-1 ring-blue-600/20 dark:ring-blue-500/40">
                                            <x-icons name="file" class="w-3 h-3 mr-1" />
                                            {{ __('Template Copy') }}
                                        </span>
                                    @endif
                                    @if($folder->derivedFolders()->exists())
                                        <span class="px-2 py-1 inline-flex items-center text-xs font-medium rounded-md bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 ring-1 ring-amber-600/20 dark:ring-amber-500/40">
                                            <x-icons name="file" class="w-3 h-3 mr-1" />
                                            {{ __('Template') }}
                                        </span>
                                    @endif
                                </div>
                            </x-admin.table.td>
                            <x-admin.table.td>
                                <div class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ $folder->creator ? $folder->creator->name : __('System') }}
                                </div>
                            </x-admin.table.td>
                            <x-admin.table.td>
                                <div class="flex -space-x-2 overflow-hidden">
                                    @forelse($folder->users as $user)
                                        <div class="h-7 w-7 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 border border-white dark:border-gray-600 shadow-sm flex items-center justify-center text-xs font-medium text-gray-700 dark:text-gray-200" title="{{ $user->name }}">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                    @empty
                                        <span class="text-xs text-gray-500 dark:text-gray-400 italic">{{ __('None') }}</span>
                                    @endforelse
                                    @if(count($folder->users) > 5)
                                        <div class="h-7 w-7 rounded-full bg-blue-100 dark:bg-blue-900 border border-white dark:border-gray-600 shadow-sm flex items-center justify-center text-xs font-medium text-blue-700 dark:text-blue-300">
                                            +{{ count($folder->users) - 5 }}
                                        </div>
                                    @endif
                                </div>
                            </x-admin.table.td>
                            <x-admin.table.td>
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('admin.folders.show', $folder) }}" class="inline-flex items-center justify-center w-9 h-9 bg-gray-50 dark:bg-gray-800 rounded-md text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150" title="{{ __('View') }}">
                                        <x-icons name="view" class="w-5 h-5" />
                                    </a>
                                    <a href="{{ route('admin.folders.edit', $folder) }}" class="inline-flex items-center justify-center w-9 h-9 bg-blue-50 dark:bg-blue-900/30 rounded-md text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-800 hover:bg-blue-100 dark:hover:bg-blue-800/50 transition-colors duration-150" title="{{ __('Edit') }}">
                                        <x-icons name="edit" class="w-5 h-5" />
                                    </a>
                                    <form method="POST" action="{{ route('admin.folders.destroy', $folder) }}" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center w-9 h-9 bg-red-50 dark:bg-red-900/30 rounded-md text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800 hover:bg-red-100 dark:hover:bg-red-800/50 transition-colors duration-150" title="{{ __('Delete') }}" onclick="return confirm('{{ __('Are you sure you want to delete this folder?') }}')">
                                            <x-icons name="delete" class="w-5 h-5" />
                                        </button>
                                    </form>
                                </div>
                            </x-admin.table.td>
                        </x-admin.table.tr>
                    @endforeach
                </x-admin.table>

                <!-- Empty State -->
                <div id="noFoldersFound" class="hidden py-8 text-center">
                    <x-icons name="folder" class="mx-auto h-12 w-12 text-gray-400" />
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('No folders found') }}</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Try adjusting your search terms.') }}</p>
                </div>

                <div class="flex justify-between items-center mt-6">
                    <h3 class="text-lg font-medium">{{ __('Root Folders') }}</h3>
                    <div class="space-x-2">
                        <a href="{{ route('admin.folders.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Create New Folder') }}
                        </a>
                        <!-- Button to create missing structures -->
                        <form action="{{ route('admin.folders.create-missing-structures') }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to create missing folder structures for all relevant users and companies?');">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Create Missing Structures') }}
                            </button>
                        </form>
                        <!-- Button to delete all folders and files -->
                        <form action="{{ route('admin.folders.delete-all') }}" method="POST" class="inline" onsubmit="return confirm('!!! DANGER ZONE !!!\n\nAre you absolutely sure you want to delete ALL folders and ALL files?\n\nThis action is irreversible and will permanently remove all data.\n\nType \'DELETE EVERYTHING\' below to confirm:\n\n') && prompt('Confirm by typing: DELETE EVERYTHING') === 'DELETE EVERYTHING';">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('DELETE ALL FOLDERS & FILES') }}
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('folderSearch');
            const folderRows = document.querySelectorAll('.folder-row');
            const noFoldersFound = document.getElementById('noFoldersFound');

            // Search functionality
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                let visibleCount = 0;

                folderRows.forEach(row => {
                    const name = row.dataset.name;
                    const description = row.dataset.description;
                    const isVisible = name.includes(searchTerm) || description.includes(searchTerm);
                    
                    row.classList.toggle('hidden', !isVisible);
                    if (isVisible) visibleCount++;
                });

                noFoldersFound.classList.toggle('hidden', visibleCount > 0);
            });
        });
    </script>
    @endpush
</x-admin-layout>