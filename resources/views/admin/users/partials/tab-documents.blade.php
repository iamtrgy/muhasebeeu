<!-- Documents Tab -->
<div x-show="activeTab === 'documents'" x-data="{ loading: false, currentPage: 1, totalPages: 1 }" x-init="$watch('activeTab', value => { if(value === 'documents') { loading = false; } })" class="space-y-6">
    <!-- Search Bar -->
    <div class="relative flex-1">
        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
        </div>
        <input 
            type="search" 
            id="documentSearch" 
            class="form-input ps-10" 
            placeholder="{{ __('Search documents...') }}"
            onkeyup="filterDocuments()"
        >
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th scope="col" class="w-2/5 px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Name
                    </th>
                    <th scope="col" class="w-1/5 px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Type
                    </th>
                    <th scope="col" class="w-1/5 px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Created
                    </th>
                    <th scope="col" class="w-1/5 px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody id="documentTableBody" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @if(isset($currentFolder))
                    <!-- Parent folder navigation (back button) -->
                    @if($currentFolder->parent_id)
                        <tr class="document-row hover:bg-gray-50 dark:hover:bg-gray-700" data-name="parent" data-type="folder">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <svg class="flex-shrink-0 h-5 w-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                                    </svg>
                                    <a href="{{ route('admin.users.show', ['user' => $user->id, 'folder_id' => $currentFolder->parent_id]) }}" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ __('Parent Folder') }}
                                    </a>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Folder') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                -
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                -
                            </td>
                        </tr>
                    @else
                        <!-- Return to root if we're in a main folder -->
                        <tr class="document-row hover:bg-gray-50 dark:hover:bg-gray-700" data-name="root" data-type="folder">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <svg class="flex-shrink-0 h-5 w-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                                    </svg>
                                    <a href="{{ route('admin.users.show', ['user' => $user->id]) }}" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ __('Back to Root') }}
                                    </a>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Folder') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                -
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                -
                            </td>
                        </tr>
                    @endif
                    
                    @foreach($subFolders as $folder)
                        <tr class="document-row hover:bg-gray-50 dark:hover:bg-gray-700" data-name="{{ strtolower($folder->name) }}" data-type="folder">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <svg class="flex-shrink-0 h-5 w-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
                                    </svg>
                                    <a href="{{ route('admin.users.show', ['user' => $user->id, 'folder_id' => $folder->id]) }}" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $folder->name }}
                                    </a>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Folder') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $folder->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.folders.show', $folder) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                    {{ __('View') }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    
                    @foreach($folderFiles as $file)
                        <tr class="document-row hover:bg-gray-50 dark:hover:bg-gray-700" data-name="{{ strtolower($file->name) }}" data-type="file">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <svg class="flex-shrink-0 h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                    </svg>
                                    <a href="javascript:void(0)" onclick="previewFile('{{ $file->name }}', '{{ $file->mime_type ?? 'application/octet-stream' }}', '{{ route('files.preview', $file) }}')" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $file->name }}
                                    </a>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $file->type }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $file->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('files.download', $file) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                    {{ __('Download') }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <!-- Main Folders -->
                    @foreach($mainFolders as $folder)
                        <tr class="document-row hover:bg-gray-50 dark:hover:bg-gray-700" data-name="{{ strtolower($folder->name) }}" data-type="folder">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <svg class="flex-shrink-0 h-5 w-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
                                    </svg>
                                    <a href="{{ route('admin.users.show', ['user' => $user->id, 'folder_id' => $folder->id]) }}" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $folder->name }}
                                    </a>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Folder') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $folder->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.folders.show', $folder) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                    {{ __('View') }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    
                    @foreach($folderFiles as $file)
                        <tr class="document-row hover:bg-gray-50 dark:hover:bg-gray-700" data-name="{{ strtolower($file->name) }}" data-type="file">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <svg class="flex-shrink-0 h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                    </svg>
                                    <a href="javascript:void(0)" onclick="previewFile('{{ $file->name }}', '{{ $file->mime_type ?? 'application/octet-stream' }}', '{{ route('files.preview', $file) }}')" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $file->name }}
                                    </a>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $file->type }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $file->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('files.download', $file) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                    {{ __('Download') }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @endif

                @if((isset($currentFolder) && $subFolders->isEmpty() && $folderFiles->isEmpty()) || 
                   (!isset($currentFolder) && $mainFolders->isEmpty() && $folderFiles->isEmpty()))
                    <tr>
                        <td colspan="4" class="px-6 py-10">
                            <x-ui.empty-state 
                                icon="folder" 
                                title="{{ __('No Documents') }}" 
                                message="{{ __('No folders or files found in this location.') }}">
                            </x-ui.empty-state>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<script>
    function filterDocuments() {
        const input = document.getElementById('documentSearch');
        const filter = input.value.toLowerCase();
        const rows = document.querySelectorAll('.document-row');
        
        rows.forEach(row => {
            const name = row.getAttribute('data-name');
            const type = row.getAttribute('data-type');
            
            if (name.includes(filter) || (type && type.includes(filter))) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    function previewFile(name, mimeType, url) {
        // Set the file details in the modal
        document.getElementById('filePreviewTitle').textContent = name;
        
        const previewContainer = document.getElementById('filePreviewContent');
        const modal = document.getElementById('filePreviewModal');        
        
        // Show loading indicator
        previewContainer.innerHTML = '<div class="flex justify-center items-center h-64"><div class="spinner"></div></div>';
        
        // Display the modal
        modal.__x.$data.open = true;
        
        // Fetch and display the file content
        if (mimeType.startsWith('image/')) {
            previewContainer.innerHTML = `<img src="${url}" alt="${name}" class="max-w-full h-auto">`;
        } else if (mimeType === 'application/pdf') {
            previewContainer.innerHTML = `<iframe src="${url}" width="100%" height="500" frameborder="0"></iframe>`;
        } else {
            // For non-previewable files, show a download button
            previewContainer.innerHTML = `
                <div class="flex flex-col items-center justify-center h-64">
                    <p class="mb-4 text-gray-600 dark:text-gray-400">This file type cannot be previewed</p>
                    <a href="${url}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700" download>
                        Download File
                    </a>
                </div>
            `;
        }
    }
</script>

<!-- Include File Preview Modal Component -->
<x-folder.file-preview-modal />
