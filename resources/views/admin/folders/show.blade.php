<x-admin-layout>
    <x-slot name="header">
        <x-admin.page-title title="{{ $folder->name }}"></x-admin.page-title>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <!-- Folder Quick Info -->
            <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <x-icons name="folder" class="h-8 w-8 text-yellow-500" />
                            </div>
                            <div>
                                <div class="text-xl font-semibold text-gray-900">{{ $folder->name }}</div>
                                <div class="mt-1 text-sm text-gray-600">
                                    Created by <span class="font-medium">{{ $folder->creator->name }}</span> on {{ $folder->created_at->format('M d, Y') }}
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            @if($folder->allow_uploads)
                                <form action="{{ route('admin.folders.files.store', $folder) }}" method="POST" enctype="multipart/form-data" class="inline" id="uploadForm">
                                    @csrf
                                    <input type="file" name="files[]" id="files" multiple class="hidden" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                                    <button type="button" onclick="document.getElementById('files').click()" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <x-icons name="upload" class="w-4 h-4 mr-2" />
                                        UPLOAD FILES
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('admin.folders.create.in', $folder) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <x-icons name="add" class="w-4 h-4 mr-2" />
                                CREATE SUBFOLDER
                            </a>

                        </div>
                    </div>
                    <div class="mt-4 flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <span class="px-3 py-1 text-sm rounded-full {{ $folder->is_public ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $folder->is_public ? 'Public' : 'Private' }}
                            </span>
                            <span class="px-3 py-1 text-sm rounded-full {{ $folder->allow_uploads ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $folder->allow_uploads ? 'Uploads Allowed' : 'Uploads Disabled' }}
                            </span>
                            @if($folder->templateFolder)
                                <span class="px-3 py-1 text-sm rounded-full bg-purple-100 text-purple-800">Template Copy</span>
                            @endif
                            @if($folder->derivedFolders()->exists())
                                <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-800">Template</span>
                            @endif
                        </div>
                        <div class="flex items-center">
                            <span class="text-sm text-gray-600 mr-2">Assigned to:</span>
                            @if($folder->users->isNotEmpty())
                                <div class="flex flex-wrap gap-2">
                                    @foreach($folder->users->take(3) as $user)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $user->name }}
                                        </span>
                                    @endforeach
                                    @if($folder->users->count() > 3)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                            +{{ $folder->users->count() - 3 }} more
                                        </span>
                                    @endif
                                </div>
                            @else
                                <span class="text-sm text-gray-500">No users assigned</span>
                            @endif
                        </div>
                    </div>
                    @if($folder->description)
                        <div class="mt-4 text-sm text-gray-600">
                            <span class="font-medium">Description:</span> {{ $folder->description }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Rest of the content -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Subfolders -->
                    <div class="mb-6">
                        <div class="mb-4 flex flex-col sm:flex-row gap-4 justify-between items-center">
                            <h3 class="text-lg font-medium">Subfolders</h3>
                            <div class="flex-1 relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <x-icons name="search" class="w-4 h-4 text-gray-500 dark:text-gray-400" />
                                </div>
                                <input type="search" id="subfolderSearch" placeholder="{{ __('Search subfolders...') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            </div>
                        </div>
                        @if($folder->children->isEmpty())
                            <p class="text-gray-600 text-sm">No subfolders found.</p>
                        @else
                            <div class="overflow-x-auto">
                                <p id="noSubfoldersMessage" class="text-gray-600 text-sm hidden">No matching subfolders found.</p>
                                <x-admin.table id="subfolderTableBody">
                                    <x-slot name="header">
                                        <x-admin.table.tr>
                                            <x-admin.table.th>{{ __('Name') }}</x-admin.table.th>
                                            <x-admin.table.th>{{ __('Status') }}</x-admin.table.th>
                                            <x-admin.table.th>{{ __('Files') }}</x-admin.table.th>
                                            <x-admin.table.th>{{ __('Created') }}</x-admin.table.th>
                                            <x-admin.table.th class="relative">
                                                <span class="sr-only">{{ __('Actions') }}</span>
                                            </x-admin.table.th>
                                        </x-admin.table.tr>
                                    </x-slot>
                                        @foreach($folder->children as $subfolder)
                                            <x-admin.table.tr class="subfolder-row" data-name="{{ strtolower($subfolder->name) }}">
                                                <x-admin.table.td>
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center">
                                                            <x-icons name="folder" class="h-8 w-8 text-yellow-500" />
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                                <a href="{{ route('admin.folders.show', $subfolder) }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                                                                    {{ $subfolder->name }}
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </x-admin.table.td>
                                                <x-admin.table.td>
                                                    <div class="flex flex-wrap gap-1.5">
                                                        <span class="px-2 py-1 inline-flex items-center text-xs font-medium rounded-md {{ $subfolder->is_public ? 'bg-green-50 text-green-700 dark:bg-green-900/40 dark:text-green-400 ring-1 ring-green-600/20 dark:ring-green-500/40' : 'bg-gray-50 text-gray-700 dark:bg-gray-800 dark:text-gray-300 ring-1 ring-gray-500/20 dark:ring-gray-600/40' }}">
                                                            <span class="mr-1 w-1.5 h-1.5 rounded-full {{ $subfolder->is_public ? 'bg-green-500' : 'bg-gray-500' }}"></span>
                                                            {{ $subfolder->is_public ? __('Public') : __('Private') }}
                                                        </span>
                                                    </div>
                                                </x-admin.table.td>
                                                <x-admin.table.td>
                                                    <div class="text-sm text-gray-700 dark:text-gray-300">{{ $subfolder->files_count }} {{ Str::plural('file', $subfolder->files_count) }}</div>
                                                </x-admin.table.td>
                                                <x-admin.table.td>
                                                    <div class="text-sm text-gray-700 dark:text-gray-300">{{ $subfolder->created_at->format('M d, Y H:i') }}</div>
                                                </x-admin.table.td>
                                                <x-admin.table.td>
                                                    <div class="flex justify-end space-x-2">
                                                        <a href="{{ route('admin.folders.show', $subfolder) }}" class="inline-flex items-center justify-center w-9 h-9 bg-gray-50 dark:bg-gray-800 rounded-md text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150" title="{{ __('View') }}">
                                                            <x-icons name="view" class="w-5 h-5" />
                                                        </a>
                                                        <a href="{{ route('admin.folders.edit', $subfolder) }}" class="inline-flex items-center justify-center w-9 h-9 bg-blue-50 dark:bg-blue-900/30 rounded-md text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-800 hover:bg-blue-100 dark:hover:bg-blue-800/50 transition-colors duration-150" title="{{ __('Edit') }}">
                                                            <x-icons name="edit" class="w-5 h-5" />
                                                        </a>
                                                        <form method="POST" action="{{ route('admin.folders.destroy', $subfolder) }}" class="inline-block">
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
                            </div>
                        @endif
                    </div>

                    <!-- Files -->
                    <div>
                        <div class="mb-4 flex flex-col sm:flex-row gap-4 justify-between items-center">
                            <h3 class="text-lg font-medium">Files</h3>
                            <div class="flex-1 relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <x-icons name="search" class="w-4 h-4 text-gray-500 dark:text-gray-400" />
                                </div>
                                <input type="search" id="fileSearch" placeholder="{{ __('Search files...') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            </div>
                        </div>
                        @if($folder->files->isEmpty())
                            <p class="text-gray-600 text-sm">No files found.</p>
                        @else
                            <div class="overflow-x-auto">
                                <p id="noFilesMessage" class="text-gray-600 text-sm hidden">No matching files found.</p>
                                <x-admin.table id="fileTableBody">
                                    <x-slot name="header">
                                        <x-admin.table.tr>
                                            <x-admin.table.th>{{ __('Name') }}</x-admin.table.th>
                                            <x-admin.table.th>{{ __('Size') }}</x-admin.table.th>
                                            <x-admin.table.th>{{ __('Uploaded By') }}</x-admin.table.th>
                                            <x-admin.table.th>{{ __('Uploaded At') }}</x-admin.table.th>
                                            <x-admin.table.th class="relative">
                                                <span class="sr-only">{{ __('Actions') }}</span>
                                            </x-admin.table.th>
                                        </x-admin.table.tr>
                                    </x-slot>
                                        @foreach($folder->files as $file)
                                            <x-admin.table.tr class="file-row" data-name="{{ strtolower($file->name) }}">
                                                <x-admin.table.td>
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center">
                                                            <svg class="h-8 w-8 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                                            </svg>
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $file->name }}</div>
                                                        </div>
                                                    </div>
                                                </x-admin.table.td>
                                                <x-admin.table.td>
                                                    <div class="text-sm text-gray-700 dark:text-gray-300">{{ number_format($file->size / 1024, 2) }} KB</div>
                                                </x-admin.table.td>
                                                <x-admin.table.td>
                                                    <div class="text-sm text-gray-700 dark:text-gray-300">{{ $file->uploader->name }}</div>
                                                </x-admin.table.td>
                                                <x-admin.table.td>
                                                    <div class="text-sm text-gray-700 dark:text-gray-300">{{ $file->created_at->format('M d, Y H:i') }}</div>
                                                </x-admin.table.td>
                                                <x-admin.table.td>
                                                    <div class="flex justify-end space-x-2">
                                                        <a href="{{ route('admin.files.download', $file) }}" class="inline-flex items-center justify-center w-9 h-9 bg-blue-50 dark:bg-blue-900/30 rounded-md text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-800 hover:bg-blue-100 dark:hover:bg-blue-800/50 transition-colors duration-150" title="{{ __('Download') }}">
                                                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                                            </svg>
                                                        </a>
                                                        <form method="POST" action="{{ route('admin.files.destroy', $file) }}" class="inline-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="inline-flex items-center justify-center w-9 h-9 bg-red-50 dark:bg-red-900/30 rounded-md text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800 hover:bg-red-100 dark:hover:bg-red-800/50 transition-colors duration-150" title="{{ __('Delete') }}" onclick="return confirm('{{ __('Are you sure you want to delete this file?') }}')">
                                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </x-admin.table.td>
                                            </x-admin.table.tr>
                                        @endforeach
                                </x-admin.table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // File search functionality
            const fileSearch = document.getElementById('fileSearch');
            const fileRows = document.querySelectorAll('#fileTableBody tbody tr');
            const noFilesMessage = document.querySelector('#noFilesMessage');

            if (fileSearch) {
                fileSearch.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    let visibleCount = 0;
                    
                    fileRows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        const isVisible = text.includes(searchTerm);
                        
                        row.style.display = isVisible ? '' : 'none';
                        if (isVisible) visibleCount++;
                    });
                    
                    // Toggle no files message if it exists
                    if (noFilesMessage) {
                        noFilesMessage.style.display = visibleCount > 0 ? 'none' : '';
                    }
                });
            }

            // Subfolder search functionality
            const subfolderSearch = document.getElementById('subfolderSearch');
            const subfolderRows = document.querySelectorAll('#subfolderTableBody tbody tr');
            const noSubfoldersMessage = document.querySelector('#noSubfoldersMessage');

            if (subfolderSearch) {
                subfolderSearch.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    let visibleCount = 0;
                    
                    subfolderRows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        const isVisible = text.includes(searchTerm);
                        
                        row.style.display = isVisible ? '' : 'none';
                        if (isVisible) visibleCount++;
                    });
                    
                    // Toggle no subfolders message if it exists
                    if (noSubfoldersMessage) {
                        noSubfoldersMessage.style.display = visibleCount > 0 ? 'none' : '';
                    }
                });
            }

            // File upload functionality
            const fileInput = document.getElementById('files');
            const uploadForm = document.getElementById('uploadForm');

            if (fileInput && uploadForm) {
                fileInput.addEventListener('change', function() {
                    if (this.files.length > 0) {
                        uploadForm.submit();
                    }
                });
            }
        });
    </script>
    @endpush
</x-admin-layout> 