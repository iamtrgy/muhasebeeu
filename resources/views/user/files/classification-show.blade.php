<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb Navigation -->
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="px-4 py-4 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center text-sm">
                        <a href="{{ route('user.dashboard') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                            Dashboard
                        </a>
                        <svg class="flex-shrink-0 mx-2 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                        <a href="{{ route('user.files.classification') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                            Document Classification
                        </a>
                        <svg class="flex-shrink-0 mx-2 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-gray-700 dark:text-gray-300 font-medium truncate">{{ $file->name }}</span>
                    </div>
                </div>
            </div>

            <div class="mb-6 grid md:grid-cols-2 gap-6">
                <!-- File Preview -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold mb-4">File Preview</h2>
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 h-[400px] overflow-auto">
                            @if(in_array($file->file_type, ['pdf', 'doc', 'docx', 'txt']))
                                <div class="file-preview h-full flex flex-col justify-center items-center">
                                    <iframe src="{{ route('user.files.preview', $file) }}" class="w-full h-full rounded border-0"></iframe>
                                </div>
                            @elseif(in_array($file->file_type, ['jpg', 'jpeg', 'png', 'gif']))
                                <div class="file-preview h-full flex flex-col justify-center items-center">
                                    <img src="{{ route('user.files.preview', $file) }}" alt="{{ $file->name }}" class="max-w-full max-h-full object-contain">
                                </div>
                            @else
                                <div class="file-preview h-full flex flex-col justify-center items-center">
                                    <svg class="h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="mt-2 text-gray-500 dark:text-gray-400">Preview not available</p>
                                    <a href="{{ route('user.files.download', $file) }}" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-sm">
                                        Download File
                                    </a>
                                </div>
                            @endif
                        </div>
                        
                        <div class="mt-4">
                            <h3 class="font-medium text-gray-900 dark:text-gray-100 mb-2">File Information</h3>
                            <div class="grid grid-cols-2 gap-2 text-sm">
                                <div class="text-gray-500 dark:text-gray-400">Name:</div>
                                <div class="text-gray-900 dark:text-gray-100">{{ $file->name }}</div>
                                
                                <div class="text-gray-500 dark:text-gray-400">Type:</div>
                                <div class="text-gray-900 dark:text-gray-100">{{ strtoupper($file->file_type) }}</div>
                                
                                <div class="text-gray-500 dark:text-gray-400">Size:</div>
                                <div class="text-gray-900 dark:text-gray-100">{{ $file->size_formatted }}</div>
                                
                                <div class="text-gray-500 dark:text-gray-400">Uploaded:</div>
                                <div class="text-gray-900 dark:text-gray-100">{{ $file->created_at->format('M d, Y H:i') }}</div>
                                
                                <div class="text-gray-500 dark:text-gray-400">Uploaded by:</div>
                                <div class="text-gray-900 dark:text-gray-100">{{ $file->uploader->name ?? 'Unknown' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Classification Options -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold mb-4">Classification Options</h2>
                        
                        <div class="mb-6">
                            <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg mb-4">
                                <h3 class="font-medium text-gray-900 dark:text-gray-100 mb-2">Current Location</h3>
                                <div class="flex items-center text-gray-700 dark:text-gray-300">
                                    <svg class="h-5 w-5 text-yellow-500 mr-2" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 7v10a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1H2V6zm0 3h16v5a2 2 0 01-2 2H4a2 2 0 01-2-2V9z" />
                                    </svg>
                                    <span>{{ $file->folder->path }}</span>
                                </div>
                            </div>
                            
                            <div class="p-4 border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                <h3 class="font-medium text-gray-900 dark:text-gray-100 mb-2">AI Suggested Location</h3>
                                <div class="flex items-center text-gray-700 dark:text-gray-300">
                                    <svg class="h-5 w-5 text-yellow-500 mr-2" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 7v10a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1H2V6zm0 3h16v5a2 2 0 01-2 2H4a2 2 0 01-2-2V9z" />
                                    </svg>
                                    <span>{{ $file->suggestedFolder->path }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <h3 class="font-medium text-gray-900 dark:text-gray-100 mb-2">Make a Decision</h3>
                            
                            <form action="{{ route('user.files.classification.handle', $file) }}" method="POST" class="space-y-4">
                                @csrf
                                
                                <div class="flex flex-col space-y-2">
                                    <button type="submit" name="action" value="accept" class="w-full px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 text-sm flex items-center justify-center">
                                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Accept AI Suggestion
                                    </button>
                                    
                                    <button type="submit" name="action" value="keep" class="w-full px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 text-sm flex items-center justify-center">
                                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        Keep Current Location
                                    </button>
                                </div>
                                
                                <div class="relative">
                                    <div class="absolute inset-0 flex items-center">
                                        <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                                    </div>
                                    <div class="relative flex justify-center text-sm">
                                        <span class="px-2 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400">or</span>
                                    </div>
                                </div>
                                
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                    <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-2">Select Another Folder</h4>
                                    
                                    <x-folder.folder-browser inputName="selected_folder_id" containerClass="max-h-[200px] overflow-y-auto border border-gray-200 dark:border-gray-700 rounded p-2 mb-3" />
                                    
                                    <button type="submit" name="action" value="custom" id="custom-folder-btn" class="w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-sm disabled:opacity-50" disabled>
                                        Select Custom Folder
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Enable the custom folder button when a folder is selected
            document.getElementById('folder-browser-container').addEventListener('folderSelected', function(event) {
                document.getElementById('custom-folder-btn').disabled = false;
            });
        });
    </script>
</x-app-layout>
