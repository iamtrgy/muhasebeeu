@props(['folder'])

@if($folder->canUpload(auth()->user()))
    <div x-data="{
        showModal: false
    }">
        <div class="flex items-center space-x-4">
            <!-- Upload Button -->
            <button @click="showModal = true" class="cursor-pointer bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Upload Files</span>
            </button>
            
            <!-- Allowed File Types -->
            <div class="text-sm text-gray-500">
                Allowed: PDF, Images, Word, Excel, Text (Max 10MB)
            </div>
        </div>

        <!-- Upload Modal -->
        <div x-show="showModal" class="fixed inset-0 overflow-y-auto z-50" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showModal = false">
                    <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
                </div>

                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 mb-4">
                                    Upload Files
                                </h3>
                                
                                <!-- Dropzone Area -->
                                <div id="custom-dropzone" class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                        <span class="font-medium text-blue-600 dark:text-blue-400 hover:underline">Click to upload</span> or drag and drop
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        PDF, Images, Word, Excel, Text (Max 10MB)
                                    </p>
                                </div>
                                
                                <!-- Preview Container -->
                                <div id="custom-preview-container" class="mt-4 space-y-2"></div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button id="custom-upload-submit" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Upload
                        </button>
                        <button @click="showModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Dropzone for the custom button
        if (typeof Dropzone !== 'undefined') {
            var customDropzone = new Dropzone("#custom-dropzone", {
                url: "/user/folders/{{ $folder->id }}/upload",
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
                previewsContainer: "#custom-preview-container",
                autoProcessQueue: false,
                clickable: "#custom-dropzone",
                createImageThumbnails: true,
                thumbnailWidth: 120,
                thumbnailHeight: 120,
                previewTemplate: `
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 mb-2 flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0 w-10 h-10">
                                <img data-dz-thumbnail class="w-full h-full object-cover rounded" />
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white" data-dz-name></p>
                                <p class="text-xs text-gray-500 dark:text-gray-400" data-dz-size></p>
                                <div class="mt-1 w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 hidden">
                                    <div class="bg-blue-600 h-2.5 rounded-full" data-dz-uploadprogress style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500 dark:text-gray-400" data-dz-status></span>
                            <button type="button" data-dz-remove class="text-red-500 hover:text-red-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                `,
                init: function() {
                    var myDropzone = this;
                    
                    // Handle the upload button click
                    document.getElementById('custom-upload-submit').addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        if (myDropzone.getQueuedFiles().length > 0) {
                            myDropzone.processQueue();
                        } else {
                            toastr.error('Please add files to upload.');
                        }
                    });
                    
                    this.on("addedfile", function(file) {
                        console.log("File added:", file.name);
                        // Show progress bar when file is added
                        file.previewElement.querySelector("[data-dz-uploadprogress]").parentElement.classList.remove("hidden");
                        file.previewElement.querySelector("[data-dz-status]").textContent = "Queued";
                    });
                    
                    this.on("uploadprogress", function(file, progress) {
                        console.log("Upload progress:", progress);
                        file.previewElement.querySelector("[data-dz-uploadprogress]").style.width = progress + "%";
                        file.previewElement.querySelector("[data-dz-status]").textContent = Math.round(progress) + "%";
                    });
                    
                    this.on("success", function(files, response) {
                        if (response.success) {
                            console.log("Upload successful:", response.data);
                            
                            // Update status for all files
                            this.files.forEach(file => {
                                if (file.previewElement) {
                                    // Hide progress bar
                                    file.previewElement.querySelector("[data-dz-uploadprogress]").parentElement.classList.add("hidden");
                                    // Update status
                                    file.previewElement.querySelector("[data-dz-status]").textContent = "Uploaded";
                                    // Add success indicator
                                    file.previewElement.classList.add("border-green-500");
                                }
                            });
                            
                            // Show success notification
                            toastr.success('Files uploaded successfully');
                            
                            // Close modal and reload after delay
                            setTimeout(() => {
                                document.querySelector('[x-data]').__x.$data.showModal = false;
                                window.location.reload();
                            }, 1500);
                        } else {
                            console.error("Upload failed:", response.message);
                            toastr.error(response.message || 'Error uploading files');
                            
                            // Update status for all files
                            this.files.forEach(file => {
                                if (file.previewElement) {
                                    // Hide progress bar
                                    file.previewElement.querySelector("[data-dz-uploadprogress]").parentElement.classList.add("hidden");
                                    // Update status
                                    file.previewElement.querySelector("[data-dz-status]").textContent = "Failed";
                                    // Add error indicator
                                    file.previewElement.classList.add("border-red-500");
                                }
                            });
                        }
                    });

                    this.on("error", function(file, errorMessage, xhr) {
                        let message = errorMessage;
                        if (typeof errorMessage === 'object' && errorMessage.message) {
                            message = errorMessage.message;
                        }
                        console.error("Upload error:", message);
                        
                        // Update file status
                        if (file.previewElement) {
                            // Hide progress bar
                            file.previewElement.querySelector("[data-dz-uploadprogress]").parentElement.classList.add("hidden");
                            // Update status
                            file.previewElement.querySelector("[data-dz-status]").textContent = "Error";
                            // Add error indicator
                            file.previewElement.classList.add("border-red-500");
                        }
                        
                        toastr.error(message || 'Error uploading files');
                    });
                    
                    this.on("removedfile", function(file) {
                        console.log("File removed:", file.name);
                    });
                }
            });
        }
    });
    </script>
    @endpush
@endif