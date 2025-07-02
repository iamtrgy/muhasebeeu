@props(['folder'])

@if($folder->canUpload(auth()->user()))
    <div x-data="{
        showModal: false,
        closeModal() {
            this.showModal = false;
        }
    }">
        <!-- Simple Upload Button -->
        <button @click="showModal = true" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Upload Files</span>
        </button>

        <!-- Simplified Upload Modal -->
        <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
            <div class="flex min-h-screen items-center justify-center p-4">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-black/70" @click="closeModal()"></div>
                
                <!-- Modal Content -->
                <div class="relative w-full max-w-md rounded-lg bg-white p-6 shadow-xl dark:bg-gray-800 border border-gray-200 dark:border-gray-700 ring-1 ring-black/5 dark:ring-white/5" @click.stop>
                    <!-- Header -->
                    <div class="mb-4 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Upload Files</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                <span id="file-count">0</span> / 20 files selected
                            </p>
                        </div>
                        <button @click="closeModal()" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Dropzone -->
                    <div id="custom-dropzone" class="mb-4 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 p-6 text-center dark:border-gray-600 dark:bg-gray-700">
                        <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Drag files here or click the button below</p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">PDF, Images, Word, Excel, Text (Max 10MB per file)</p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">You can select up to 20 files at once</p>
                        
                        <!-- Dedicated Browse Button -->
                        <button type="button" id="browse-files-btn" class="mt-3 inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                            </svg>
                            Browse Files
                        </button>
                    </div>
                    
                    <!-- Preview Container -->
                    <div id="custom-preview-container" class="max-h-60 space-y-2 overflow-y-auto"></div>
                    
                    <!-- Actions -->
                    <div class="mt-4 flex justify-end space-x-3">
                        <button @click="closeModal()" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                            Cancel
                        </button>
                        <button id="custom-upload-submit" class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 min-w-[100px] relative">
                            <span class="upload-text">Upload</span>
                            <span class="upload-progress hidden">
                                <svg class="animate-spin h-4 w-4 text-white inline-block mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="progress-text">Uploading...</span>
                            </span>
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
            Dropzone.autoDiscover = false;
            
            // Small delay to ensure DOM is ready
            setTimeout(function() {
                var customDropzone = new Dropzone("#custom-dropzone", {
                url: "/user/folders/{{ $folder->id }}/upload",
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                paramName: "files",  // Parameter name without brackets
                maxFilesize: 10, // 10MB max file size per file
                maxFiles: 20,  // Increased from 10 to 20 files
                parallelUploads: 2, // Upload 2 files at a time for better stability
                uploadMultiple: false, // Upload files individually for better progress tracking
                addRemoveLinks: true,
                dictRemoveFile: "Remove",
                dictMaxFilesExceeded: "You can only upload a maximum of 20 files",
                previewsContainer: "#custom-preview-container",
                autoProcessQueue: false, // Manual processing for better control
                clickable: false, // Disable dropzone clicking, we'll use button instead
                createImageThumbnails: true,
                thumbnailWidth: 80, // Reduced thumbnail size
                thumbnailHeight: 80, // Reduced thumbnail size
                acceptedFiles: ".pdf,.doc,.docx,.xls,.xlsx,.txt,.jpg,.jpeg,.png,.gif,.bmp,.svg,.csv", // Added more file types
                timeout: 300000, // 5 minutes timeout for multiple files
                chunking: false, // Disable chunking for small files
                previewTemplate: `
                    <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-700/30 rounded p-2 mb-2">
                        <div class="flex items-center space-x-2 overflow-hidden">
                            <div class="flex-shrink-0 w-8 h-8 bg-gray-100 dark:bg-gray-600 rounded flex items-center justify-center">
                                <img data-dz-thumbnail class="w-full h-full object-cover rounded" />
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate" data-dz-name></p>
                                <p class="text-xs text-gray-500 dark:text-gray-400" data-dz-size></p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 ml-2">
                            <div class="w-16">
                                <div class="h-1.5 w-full bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
                                    <div data-dz-uploadprogress class="h-full bg-blue-600 rounded-full transition-all duration-300"></div>
                                </div>
                            </div>
                            <button data-dz-remove class="text-gray-400 hover:text-red-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                `,
                init: function() {
                    var myDropzone = this;
                    var uploadButton = document.getElementById('custom-upload-submit');
                    var fileCountElement = document.getElementById('file-count');
                    
                    // Update file count
                    function updateFileCount() {
                        if (fileCountElement) {
                            fileCountElement.textContent = myDropzone.files.length;
                        }
                    }
                    
                    // Handle max files exceeded
                    this.on("maxfilesexceeded", function(file) {
                        this.removeFile(file);
                        if (typeof toastr !== 'undefined') {
                            toastr.warning('Maximum 20 files allowed. File "' + file.name + '" was not added.');
                        } else {
                            alert('Maximum 20 files allowed. File "' + file.name + '" was not added.');
                        }
                    });
                    
                    // Custom file type icons for different document types
                    this.on("addedfile", function(file) {
                        updateFileCount();
                        if (!file.type.match(/image.*/)) {
                            // Remove default thumbnail
                            file.previewElement.querySelector('[data-dz-thumbnail]').remove();
                            
                            // Add appropriate file type icon based on mime type
                            var fileIcon = '';
                            var iconColor = 'text-gray-400 dark:text-gray-300';
                            
                            if (file.type.match(/pdf/)) {
                                fileIcon = '<svg class="w-5 h-5 ' + iconColor + '" fill="currentColor" viewBox="0 0 24 24"><path d="M7 2C5.9 2 5 2.9 5 4V20C5 21.1 5.9 22 7 22H17C18.1 22 19 21.1 19 20V8L13 2H7ZM13 3.5L17.5 8H13V3.5ZM9.3 10.5H10.5V13.5H9.3V10.5ZM11.3 10.5H14.7V11.7H13.5V15.3H11.3V10.5ZM15.5 13.5H16.7V15.3H15.5V13.5Z"></path></svg>';
                            } else if (file.type.match(/word|doc/)) {
                                fileIcon = '<svg class="w-5 h-5 ' + iconColor + '" fill="currentColor" viewBox="0 0 24 24"><path d="M19.437,4.065H11.37L9.494,2H4.562C3.699,2,3,2.699,3,3.562v16.875C3,21.301,3.699,22,4.562,22h14.875C20.301,22,21,21.301,21,20.438V5.628C21,4.765,20.301,4.065,19.437,4.065z M14.422,7.299c0.238,0,0.432,0.193,0.432,0.432s-0.193,0.432-0.432,0.432h-4.844c-0.238,0-0.432-0.193-0.432-0.432s0.193-0.432,0.432-0.432H14.422z M16.647,10.205c0.238,0,0.432,0.193,0.432,0.432s-0.193,0.432-0.432,0.432H7.353c-0.238,0-0.432-0.193-0.432-0.432s0.193-0.432,0.432-0.432H16.647z M16.647,13.111c0.238,0,0.432,0.193,0.432,0.432s-0.193,0.432-0.432,0.432H7.353c-0.238,0-0.432-0.193-0.432-0.432s0.193-0.432,0.432-0.432H16.647z M16.647,16.017c0.238,0,0.432,0.193,0.432,0.432s-0.193,0.432-0.432,0.432H7.353c-0.238,0-0.432-0.193-0.432-0.432s0.193-0.432,0.432-0.432H16.647z"></path></svg>';
                            } else if (file.type.match(/excel|spreadsheet|xls/)) {
                                fileIcon = '<svg class="w-5 h-5 ' + iconColor + '" fill="currentColor" viewBox="0 0 24 24"><path d="M19.437,4.065H11.37L9.494,2H4.562C3.699,2,3,2.699,3,3.562v16.875C3,21.301,3.699,22,4.562,22h14.875C20.301,22,21,21.301,21,20.438V5.628C21,4.765,20.301,4.065,19.437,4.065z M15.315,16.584l-1.844-3.166l1.659-3.166h1.225l-1.317,2.646l1.5,2.686H15.315z M10.864,16.584L9.019,13.418l1.659-3.166h1.226L10.587,12.9l1.5,2.686H10.864z M7.221,16.584L5.376,13.418l1.66-3.166h1.225L6.944,12.9l1.5,2.686H7.221z"></path></svg>';
                            } else if (file.type.match(/zip|rar|archive/)) {
                                fileIcon = '<svg class="w-5 h-5 ' + iconColor + '" fill="currentColor" viewBox="0 0 24 24"><path d="M14,2H6C4.9,2,4,2.9,4,4v16c0,1.1,0.9,2,2,2h12c1.1,0,2-0.9,2-2V8L14,2z M16,18H8v-2h8V18z M16,14H8v-2h8V14z M13,9V3.5L18.5,9H13z"></path><path d="M10 2H12V4H10zM10 4H12V6H10zM10 6H12V8H10z"></path></svg>';
                            } else {
                                fileIcon = '<svg class="w-5 h-5 ' + iconColor + '" fill="currentColor" viewBox="0 0 24 24"><path d="M14,2H6C4.9,2,4,2.9,4,4v16c0,1.1,0.9,2,2,2h12c1.1,0,2-0.9,2-2V8L14,2z M16,18H8v-2h8V18z M16,14H8v-2h8V14z M13,9V3.5L18.5,9H13z"></path></svg>';
                            }
                            
                            // Insert the file icon
                            file.previewElement.querySelector('.flex-shrink-0').innerHTML = fileIcon;
                        }                        
                    });
                    
                    // Update file count when file is removed
                    this.on("removedfile", function(file) {
                        updateFileCount();
                    });
                    
                    // Handle the upload button click
                    uploadButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        if (myDropzone.getQueuedFiles().length > 0) {
                            // Disable button and show uploading state
                            uploadButton.disabled = true;
                            uploadButton.classList.add('cursor-not-allowed');
                            uploadButton.querySelector('.upload-text').classList.add('hidden');
                            uploadButton.querySelector('.upload-progress').classList.remove('hidden');
                            
                            // Start processing files
                            myDropzone.processQueue();
                        } else {
                            // Show warning if no files selected
                            if (typeof toastr !== 'undefined') {
                                toastr.warning('Please add files to upload');
                            } else {
                                alert('Please add files to upload');
                            }
                        }
                    });

                    // Track overall upload progress
                    let totalFiles = 0;
                    let completedFiles = 0;
                    let uploadingFiles = 0;
                    
                    // Initialize total files when processing starts
                    this.on("processingmultiple", function() {
                        totalFiles = myDropzone.files.length;
                        completedFiles = 0;
                        uploadingFiles = 0;
                    });
                    
                    this.on("processing", function() {
                        if (totalFiles === 0) {
                            totalFiles = myDropzone.files.length;
                        }
                    });
                    
                    // Handle individual file completion
                    this.on("complete", function(file) {
                        // Check if there are more files to process
                        if (myDropzone.getQueuedFiles().length > 0 && myDropzone.getUploadingFiles().length < myDropzone.options.parallelUploads) {
                            myDropzone.processQueue();
                        }
                    });
                    
                    this.on("sending", function(file) {
                        // Show loading state
                        file.previewElement.classList.add('opacity-50');
                        uploadingFiles++;
                        
                        // Update button text to show current progress
                        const progressText = uploadButton.querySelector('.progress-text');
                        if (progressText) {
                            progressText.textContent = `Uploading ${uploadingFiles + completedFiles}/${totalFiles}...`;
                        }
                    });
                    
                    // Update progress for each file
                    this.on("uploadprogress", function(file, progress) {
                        // Update individual file progress bar
                        const progressBar = file.previewElement.querySelector('[data-dz-uploadprogress]');
                        if (progressBar) {
                            progressBar.style.width = progress + '%';
                        }
                        
                        // Calculate and update overall progress
                        let totalProgress = 0;
                        myDropzone.files.forEach(function(f) {
                            totalProgress += f.upload ? f.upload.progress : 0;
                        });
                        
                        const overallProgress = Math.round(totalProgress / myDropzone.files.length);
                        const progressText = uploadButton.querySelector('.progress-text');
                        if (progressText) {
                            progressText.textContent = `Uploading ${overallProgress}%`;
                        }
                    });

                    this.on("success", function(file, response) {
                        file.previewElement.classList.remove('opacity-50');
                        completedFiles++;
                        uploadingFiles--;
                        
                        // Add success indication to file preview
                        file.previewElement.classList.add('border-green-500', 'bg-green-50', 'dark:bg-green-900/20');
                        
                        // Update button text
                        const progressText = uploadButton.querySelector('.progress-text');
                        if (progressText) {
                            if (completedFiles === totalFiles) {
                                progressText.textContent = `All ${totalFiles} files uploaded!`;
                            } else {
                                progressText.textContent = `Uploaded ${completedFiles}/${totalFiles} files...`;
                            }
                        }
                        
                        // The 'complete' event will handle processing next files
                        
                        // Only show toast for the last file or if there's only one file
                        if (completedFiles === totalFiles && typeof toastr !== 'undefined') {
                            toastr.success(`Successfully uploaded ${totalFiles} file${totalFiles > 1 ? 's' : ''}`);
                        }
                    });

                    this.on("error", function(file, errorMessage, xhr) {
                        file.previewElement.classList.remove('opacity-50');
                        uploadingFiles--;
                        
                        let message = errorMessage;
                        
                        // Parse error message from response
                        if (typeof errorMessage === 'object') {
                            if (errorMessage.message) {
                                message = errorMessage.message;
                            } else if (errorMessage.error) {
                                message = errorMessage.error;
                            } else if (errorMessage.errors) {
                                // Laravel validation errors
                                const firstError = Object.values(errorMessage.errors)[0];
                                message = Array.isArray(firstError) ? firstError[0] : firstError;
                            }
                        } else if (xhr && xhr.status === 403) {
                            message = 'You do not have permission to upload files to this folder.';
                        } else if (xhr && xhr.status === 401) {
                            message = 'Your session has expired. Please refresh the page and try again.';
                        }
                        
                        if (typeof toastr !== 'undefined') {
                            toastr.error(message);
                        } else {
                            alert('Upload error: ' + message);
                        }
                        
                        // Add error indication to file preview
                        file.previewElement.classList.add('border-red-500', 'bg-red-50', 'dark:bg-red-900/20');
                        
                        // The 'complete' event will handle processing next files
                    });

                    this.on("queuecomplete", function() {
                        // Show completion message
                        const progressText = uploadButton.querySelector('.progress-text');
                        if (progressText) {
                            progressText.textContent = 'Upload complete!';
                        }
                        
                        // Wait a moment before resetting
                        setTimeout(function() {
                            uploadButton.disabled = false;
                            uploadButton.classList.remove('cursor-not-allowed');
                            uploadButton.querySelector('.upload-text').classList.remove('hidden');
                            uploadButton.querySelector('.upload-progress').classList.add('hidden');
                            
                            // Reset counters
                            totalFiles = 0;
                            completedFiles = 0;
                            
                            // Reload the file list after all uploads are complete
                            window.location.reload();
                        }, 1500);
                    });
                }
            });

            // Make the dropzone more responsive
            const dropzoneElement = document.getElementById('custom-dropzone');
            const browseButton = document.getElementById('browse-files-btn');
            
            // Create a hidden file input
            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.multiple = true;
            fileInput.accept = '.pdf,.doc,.docx,.xls,.xlsx,.txt,.jpg,.jpeg,.png,.gif,.bmp,.svg,.csv';
            fileInput.style.display = 'none';
            document.body.appendChild(fileInput);
            
            // Handle browse button click
            browseButton.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                fileInput.click();
            });
            
            // Handle file selection
            fileInput.addEventListener('change', function(e) {
                const files = e.target.files;
                if (files && files.length > 0) {
                    // Add files to Dropzone
                    for (let i = 0; i < files.length; i++) {
                        customDropzone.addFile(files[i]);
                    }
                }
                // Clear the input for next selection
                fileInput.value = '';
            });
            
            // Reset file count when modal opens
            updateFileCount();
            
            // Add visual feedback on drag
            dropzoneElement.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('border-blue-500', 'dark:border-blue-400', 'bg-blue-50', 'dark:bg-blue-900/20');
            });
            
            dropzoneElement.addEventListener('dragleave', function(e) {
                e.preventDefault();
                this.classList.remove('border-blue-500', 'dark:border-blue-400', 'bg-blue-50', 'dark:bg-blue-900/20');
            });
            
            dropzoneElement.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('border-blue-500', 'dark:border-blue-400', 'bg-blue-50', 'dark:bg-blue-900/20');
            });
            
            // Clean up on modal close
            document.addEventListener('alpine:init', () => {
                Alpine.data('uploadModal', () => ({
                    showModal: false,
                    closeModal() {
                        this.showModal = false;
                        // Clean up file input when modal closes
                        if (fileInput && fileInput.parentNode) {
                            fileInput.parentNode.removeChild(fileInput);
                        }
                    }
                }));
            });
            }, 100); // End of setTimeout
        }
    });
    </script>
    @endpush
@endif