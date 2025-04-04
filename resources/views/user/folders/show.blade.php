<x-app-layout>
    <x-folder.file-preview-modal />

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Add Accountant Style Breadcrumb Navigation -->
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="px-4 py-4 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center text-sm">
                         <x-folder.breadcrumb :folder="$folder" />
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center gap-4 mb-6">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                            </div>
                            <input type="text" id="tableSearch" class="block w-full ps-10 pe-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 focus:border-blue-500 dark:focus:border-blue-500 focus:ring-blue-500 dark:focus:ring-blue-500" placeholder="{{ __('Search files and folders...') }}" onkeyup="searchTable()">
                        </div>
                        <x-folder.upload-button :folder="$folder" />
                        @if(!$folder->canUpload(auth()->user()))
                        <div class="text-red-500 text-sm">Debug: Uploads not allowed for this folder</div>
                        @endif
                    </div>

                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($files->isEmpty() && $subfolders->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No content</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">This folder is empty.</p>
                        </div>
                    @else
                        <!-- Mobile View -->
                        <div class="block sm:hidden">
                            <div class="space-y-4">
                                @foreach($subfolders as $subfolder)
                                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                                        <div class="p-4">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-3">
                                                    <svg class="h-5 w-5 text-yellow-500" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                                    </svg>
                                                    <a href="{{ route('user.folders.show', $subfolder) }}" class="text-sm font-medium text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400">
                                                        {{ $subfolder->name }}
                                                    </a>
                                                </div>
                                                <a href="{{ route('user.folders.show', $subfolder) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                                    </svg>
                                                </a>
                                            </div>
                                            
                                            <div class="mt-3 grid grid-cols-2 gap-2 text-sm text-gray-500 dark:text-gray-400">
                                                <div>
                                                    <span class="font-medium">Files:</span> {{ $subfolder->files_count }}
                                                </div>
                                                @if($subfolder->activeChildrenCount() > 0)
                                                    <div>
                                                        <span class="font-medium">Subfolders:</span> {{ $subfolder->activeChildrenCount() }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <span class="font-medium">Created by:</span> {{ optional($subfolder->creator)->name ?? 'System' }}
                                                </div>
                                                <div>
                                                    <span class="font-medium">Date:</span> {{ $subfolder->created_at->format('M d, Y') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                @foreach($files as $file)
                                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                                        <div class="p-4">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-3">
                                                    <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    <button onclick="previewFile('{{ $file->name }}', '{{ $file->mime_type }}', '{{ $file->url }}')" 
                                                            class="text-sm font-medium text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400 text-left">
                                                        {{ $file->name }}
                                                    </button>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <a href="{{ route('user.files.download', $file) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                        </svg>
                                                    </a>
                                                    <form action="{{ route('user.files.destroy', $file) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" onclick="return confirm('Are you sure you want to delete this file?')">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                            
                                            <div class="mt-3 grid grid-cols-2 gap-2 text-sm text-gray-500 dark:text-gray-400">
                                                <div>
                                                    <span class="font-medium">Size:</span> {{ number_format($file->size / 1024, 2) }} KB
                                                </div>
                                                <div>
                                                    <span class="font-medium">Type:</span> File
                                                </div>
                                                <div>
                                                    <span class="font-medium">Uploaded by:</span> {{ $file->uploader->name }}
                                                </div>
                                                <div>
                                                    <span class="font-medium">Date:</span> {{ $file->created_at->format('M d, Y') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Desktop View -->
                        <div class="hidden sm:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" id="contentTable">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <x-folder.table-header />
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    <tr class="breadcrumb-row">
                                        <td colspan="6">
                                            <div class="bg-gray-50 dark:bg-gray-700">
                                                <div class="px-6 py-3 text-sm text-gray-500">
                                                    <x-folder.breadcrumb :folder="$folder" />
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @foreach($subfolders as $subfolder)
                                        <tr class="content-row">
                                            <td class="px-6 py-4 whitespace-nowrap w-2/5">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-5 w-5">
                                                        <svg class="h-5 w-5 text-yellow-500" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                                        </svg>
                                                    </div>
                                                    <div class="ml-4 truncate">
                                                        <a href="{{ route('user.folders.show', $subfolder) }}" class="text-sm font-medium text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400">
                                                            {{ $subfolder->name }}
                                                        </a>
                                                        @if($subfolder->activeChildrenCount() > 0)
                                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                                {{ $subfolder->activeChildrenCount() }} {{ Str::plural('subfolder', $subfolder->activeChildrenCount()) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap w-1/12">
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    Folder
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap w-1/6">
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $subfolder->files_count }} {{ Str::plural('file', $subfolder->files_count) }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap w-1/6">
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ optional($subfolder->creator)->name ?? 'System' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 w-1/6">
                                                {{ $subfolder->created_at->format('M d, Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium w-1/12">
                                                <div class="flex items-center space-x-3 justify-end">
                                                    <a href="{{ route('user.folders.show', $subfolder) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @foreach($files as $file)
                                        <tr class="content-row">
                                            <td class="px-6 py-4 whitespace-nowrap w-2/5">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-5 w-5">
                                                        <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                    </div>
                                                    <div class="ml-4 truncate">
                                                        <button onclick="previewFile('{{ $file->name }}', '{{ $file->mime_type }}', '{{ $file->url }}')" 
                                                                class="text-sm font-medium text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400 text-left">
                                                            {{ $file->name }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap w-1/12">
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    File
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap w-1/6">
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ number_format($file->size / 1024, 2) }} KB
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap w-1/6">
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $file->uploader->name }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 w-1/6">
                                                {{ $file->created_at->format('M d, Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium w-1/12">
                                                <div class="flex items-center space-x-3 justify-end">
                                                    <a href="{{ route('user.files.download', $file) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                        </svg>
                                                    </a>
                                                    <form action="{{ route('user.files.destroy', $file) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" onclick="return confirm('Are you sure you want to delete this file?')">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            
                            <!-- Add pagination links -->
                            <div class="mt-4">
                                {{ $files->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Container -->
    <div id="preview-container" class="fixed bottom-0 right-0 p-4 max-w-md z-[65]"></div>

    <!-- Upload Modal -->
    <div id="uploadModal" class="hidden fixed inset-0 z-[60] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div id="modalBackdrop" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <!-- Modal content -->
                <div class="bg-white dark:bg-gray-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="w-full">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-white mb-4" id="modal-title">
                                {{ __('Upload Files') }}
                            </h3>
                            <form id="dropzone-upload" action="{{ route('user.files.upload', $folder) }}" class="dropzone">
                                @csrf
                                <div class="dz-default dz-message">
                                    <div class="flex flex-col items-center justify-center py-7 gap-4">
                                        <svg class="w-12 h-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242"></path><path d="M12 12v9"></path><path d="m16 16-4-4-4 4"></path></svg>
                                        <div class="text-gray-500 dark:text-gray-400">
                                            {{ __('Drag and drop files here or click to select files') }}
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button" onclick="submitForm()" class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto">
                        {{ __('Upload') }}
                    </button>
                    <button type="button" onclick="closeModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-gray-800 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 sm:mt-0 sm:w-auto">
                        {{ __('Cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function searchTable() {
            var input = document.getElementById("tableSearch");
            var filter = input.value.toLowerCase();
            var table = document.querySelector("table");
            var rows = table.getElementsByTagName("tr");

            for (var i = 0; i < rows.length; i++) {
                var row = rows[i];
                if (!row.classList.contains('breadcrumb-row')) { // Skip breadcrumb row
                    var cells = row.getElementsByTagName("td");
                    var found = false;
                    
                    for (var j = 0; j < cells.length; j++) {
                        var cell = cells[j];
                        if (cell) {
                            var text = cell.textContent || cell.innerText;
                            if (text.toLowerCase().indexOf(filter) > -1) {
                                found = true;
                                break;
                            }
                        }
                    }
                    
                    row.style.display = found ? "" : "none";
                }
            }
        }

        // Make sure dropzone is properly initialized
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Dropzone !== 'undefined') {
                Dropzone.autoDiscover = false;
            }
        });
    </script>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Dropzone
        if (typeof Dropzone !== 'undefined') {
            var dropzone = new Dropzone("#dropzone-upload", {
                url: "{{ route('user.files.upload', $folder) }}",
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                paramName: "files",
                maxFilesize: 10,
                maxFiles: 10,
                parallelUploads: 5,
                uploadMultiple: true,
                addRemoveLinks: true,
                dictRemoveFile: "Remove",
                previewsContainer: "#preview-container",
                autoProcessQueue: false,
                previewTemplate: `
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-2 mb-2 flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-600 dark:text-gray-300" data-dz-name></span>
                            <span class="text-xs text-gray-500 dark:text-gray-400" data-dz-size></span>
                        </div>
                        <button data-dz-remove class="text-red-500 hover:text-red-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                `,
                init: function() {
                    this.on("success", function(file, response) {
                        console.log("Upload successful");
                        window.location.reload();
                    });

                    this.on("error", function(file, errorMessage) {
                        console.error("Upload error:", errorMessage);
                        alert("Error uploading file: " + errorMessage);
                    });

                    this.on("addedfile", function(file) {
                        console.log("File added:", file.name);
                    });
                }
            });

            window.dropzone = dropzone;
        }
    });
    </script>
    @endpush
</x-app-layout> 