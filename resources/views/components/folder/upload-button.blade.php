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
                <div class="relative w-full max-w-md rounded-lg bg-white p-6 shadow-xl dark:bg-gray-800 border border-gray-200 dark:border-gray-700 ring-1 ring-black/5 dark:ring-white/5">
                    <!-- Header -->
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Upload Files</h3>
                        <button @click="closeModal()" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Dropzone -->
                    <div id="custom-dropzone" class="mb-4 cursor-pointer rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 p-6 text-center hover:bg-gray-100 dark:border-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600">
                        <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Drag files here or <span class="font-medium text-blue-600 dark:text-blue-400">browse</span></p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">PDF, Images, Word, Excel, Text (Max 10MB)</p>
                    </div>
                    
                    <!-- Preview Container -->
                    <div id="custom-preview-container" class="max-h-60 space-y-2 overflow-y-auto"></div>
                    
                    <!-- Actions -->
                    <div class="mt-4 flex justify-end space-x-3">
                        <button @click="closeModal()" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                            Cancel
                        </button>
                        <button id="custom-upload-submit" class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Upload
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
            
            var customDropzone = new Dropzone("#custom-dropzone", {
                url: "/user/folders/{{ $folder->id }}/upload",
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                paramName: "files",
                maxFilesize: 10, // 10MB max file size
                maxFiles: 10,
                parallelUploads: 5, // Increased parallel uploads for better performance
                uploadMultiple: true,
                addRemoveLinks: true,
                dictRemoveFile: "Remove",
                previewsContainer: "#custom-preview-container",
                autoProcessQueue: false,
                clickable: true,
                createImageThumbnails: true,
                thumbnailWidth: 80, // Reduced thumbnail size
                thumbnailHeight: 80, // Reduced thumbnail size
                acceptedFiles: ".pdf,.doc,.docx,.xls,.xlsx,.txt,.jpg,.jpeg,.png,.gif", // Explicitly define accepted files
                timeout: 180000, // 3 minutes timeout
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
                    
                    // Custom file type icons for different document types
                    this.on("addedfile", function(file) {
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
                    
                    // Handle the upload button click
                    uploadButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        if (myDropzone.getQueuedFiles().length > 0) {
                            uploadButton.disabled = true;
                            uploadButton.classList.add('opacity-50', 'cursor-not-allowed');
                            myDropzone.processQueue();
                        } else {
                            toastr.warning('Please add files to upload');
                        }
                    });

                    this.on("sending", function(file) {
                        // Show loading state
                        file.previewElement.classList.add('opacity-50');
                    });

                    this.on("success", function(file, response) {
                        file.previewElement.classList.remove('opacity-50');
                        toastr.success('File uploaded successfully');
                    });

                    this.on("error", function(file, errorMessage, xhr) {
                        file.previewElement.classList.remove('opacity-50');
                        let message = errorMessage;
                        if (typeof errorMessage === 'object' && errorMessage.message) {
                            message = errorMessage.message;
                        }
                        toastr.error(message);
                    });

                    this.on("queuecomplete", function() {
                        uploadButton.disabled = false;
                        uploadButton.classList.remove('opacity-50', 'cursor-not-allowed');
                        // Reload the file list after all uploads are complete
                        window.location.reload();
                    });
                }
            });

            // Make the dropzone more responsive
            const dropzoneElement = document.getElementById('custom-dropzone');
            
            // Add visual feedback on click
            dropzoneElement.addEventListener('click', function() {
                // Add active state
                this.classList.add('bg-gray-100', 'dark:bg-gray-600');
                
                // Remove active state after animation
                setTimeout(() => {
                    this.classList.remove('bg-gray-100', 'dark:bg-gray-600');
                }, 200);
                
                // Trigger the file browser
                customDropzone.hiddenFileInput.click();
            });
            
            // Add visual feedback on hover
            dropzoneElement.addEventListener('dragover', function() {
                this.classList.add('border-blue-500', 'dark:border-blue-400');
            });
            
            dropzoneElement.addEventListener('dragleave', function() {
                this.classList.remove('border-blue-500', 'dark:border-blue-400');
            });
            
            dropzoneElement.addEventListener('drop', function() {
                this.classList.remove('border-blue-500', 'dark:border-blue-400');
            });
        }
    });
    </script>
    @endpush
@endif