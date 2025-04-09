@props(['folder'])

@if($folder->canUpload(auth()->user()))
    <div x-data="{
        showModal: false,
        useAI: true
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
                                
                                <!-- AI Classification Toggle in Modal -->
                                <div class="mb-4">
                                    <label class="flex items-center cursor-pointer">
                                        <div class="relative inline-flex items-center">
                                            <input type="checkbox" 
                                                x-model="useAI"
                                                class="sr-only">
                                            <div class="w-14 h-7 transition-colors duration-200 ease-in-out rounded-full"
                                                :class="useAI ? 'bg-green-500' : 'bg-gray-300'">
                                            </div>
                                            <div class="absolute left-1 top-1 w-5 h-5 transition-transform duration-200 ease-in-out transform bg-white rounded-full shadow-md"
                                                :class="useAI ? 'translate-x-7' : 'translate-x-0'">
                                            </div>
                                            <!-- ON/OFF Labels -->
                                            <div class="absolute inset-0 flex items-center justify-between text-xs font-bold px-1">
                                                <span class="text-white ml-1" :class="useAI ? 'opacity-100' : 'opacity-0'">ON</span>
                                                <span class="text-gray-700 mr-1" :class="useAI ? 'opacity-0' : 'opacity-100'">OFF</span>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <span class="text-gray-700 dark:text-gray-300 font-medium">AI Auto-Classify</span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400 block" x-show="useAI">AI will suggest the best folder</span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400 block" x-show="!useAI">Files will stay in selected folder</span>
                                        </div>
                                    </label>
                                </div>
                                
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
                previewsContainer: "#custom-preview-container",
                autoProcessQueue: false,
                clickable: "#custom-dropzone",
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
                    var myDropzone = this;
                    
                    // Handle the upload button click
                    document.getElementById('custom-upload-submit').addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        if (myDropzone.getQueuedFiles().length > 0) {
                            // Get AI classification option
                            const useAI = document.querySelector('[x-model="useAI"]').checked;
                            
                            // Add AI classify parameter to all files
                            myDropzone.on("sending", function(file, xhr, formData) {
                                formData.append("ai_classify", useAI);
                            });
                            
                            myDropzone.processQueue();
                        } else {
                            alert('Please add files to upload.');
                        }
                    });
                    
                    this.on("success", function(files, response) {
                        console.log("Upload successful:", response);
                        
                        // Show notification if there are pending classifications
                        if (response.pending_classification) {
                            // Create a modal for classification review
                            const classificationModal = document.createElement('div');
                            classificationModal.id = 'classification-modal';
                            classificationModal.className = 'fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center z-50';
                            classificationModal.innerHTML = `
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4 overflow-hidden">
                                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                            <svg class="inline-block h-5 w-5 text-yellow-500 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                            Document Classification
                                        </h3>
                                        <button id="close-classification-modal" class="text-gray-400 hover:text-gray-500">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="px-6 py-4">
                                        <div class="mb-4">
                                            <p class="text-gray-700 dark:text-gray-300">
                                                AI has suggested classifications for your uploaded documents. Would you like to review them now?
                                            </p>
                                        </div>
                                        <div class="flex flex-col space-y-2">
                                            <a href="{{ route('user.files.classification') }}" class="w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-sm text-center">
                                                Review Classifications Now
                                            </a>
                                            <button id="review-later" class="w-full px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 text-sm">
                                                Review Later
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;
                            document.body.appendChild(classificationModal);
                            
                            // Handle close button
                            document.getElementById('close-classification-modal').addEventListener('click', function() {
                                classificationModal.remove();
                            });
                            
                            // Handle "Review Later" button
                            document.getElementById('review-later').addEventListener('click', function() {
                                classificationModal.remove();
                                
                                // Show a small notification instead
                                const notification = document.createElement('div');
                                notification.className = 'fixed bottom-20 right-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded shadow-lg z-50 max-w-sm';
                                notification.innerHTML = `
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm">${response.message}</p>
                                        </div>
                                    </div>
                                `;
                                document.body.appendChild(notification);
                                
                                // Remove notification after 10 seconds
                                setTimeout(() => {
                                    notification.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                                    setTimeout(() => {
                                        notification.remove();
                                    }, 500);
                                }, 10000);
                            });
                        }
                        
                        // Don't automatically reload the page - let user interact with the classification options
                        // Instead, reset the dropzone and close the modal
                        myDropzone.removeAllFiles(true);
                        
                        // Close the modal safely by checking if the element exists first
                        const modalElement = document.querySelector('[x-data]');
                        if (modalElement && modalElement.__x) {
                            modalElement.__x.$data.showModal = false;
                        }
                    });

                    this.on("error", function(file, errorMessage, xhr) {
                        console.error("Upload error:", errorMessage);
                        
                        let errorMsg = "An error occurred during file upload.";
                        
                        // If we have an xhr response with error details
                        if (xhr && xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        } else if (typeof errorMessage === 'string') {
                            errorMsg = errorMessage;
                        } else if (typeof errorMessage === 'object' && errorMessage.message) {
                            errorMsg = errorMessage.message;
                        }
                        
                        // Create an error notification
                        const errorNotification = document.createElement('div');
                        errorNotification.className = 'fixed bottom-20 right-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-lg z-50 max-w-sm';
                        errorNotification.innerHTML = `
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm">Upload Error: ${errorMsg}</p>
                                </div>
                            </div>
                        `;
                        document.body.appendChild(errorNotification);
                        
                        // Remove notification after 10 seconds
                        setTimeout(() => {
                            errorNotification.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                            setTimeout(() => {
                                errorNotification.remove();
                            }, 500);
                        }, 10000);
                    });
                }
            });
        }
    });
    </script>
    @endpush
@endif