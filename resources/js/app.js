import './bootstrap';
import Alpine from 'alpinejs';
import modalManager from './modal-manager';

// Initialize Alpine
window.Alpine = Alpine;
Alpine.start();

// Import Dropzone CSS
import 'dropzone/dist/dropzone.css';

// Make Dropzone available globally
import Dropzone from 'dropzone';
window.Dropzone = Dropzone;

// Prevent Dropzone auto-discover
Dropzone.autoDiscover = false;

// Configure Dropzone defaults
Dropzone.options.dropzoneUpload = {
    clickable: '.dz-default.dz-message',
    previewsContainer: '#preview-container',
    paramName: 'files',
    maxFilesize: 10,
    maxFiles: 10,
    parallelUploads: 5,
    uploadMultiple: true,
    addRemoveLinks: true,
    autoProcessQueue: false,
    dictDefaultMessage: '',
    createImageThumbnails: true,
    init: function() {
        this.on('success', function(file, response) {
            console.log('File uploaded successfully');
            window.location.reload();
        });
        
        this.on('error', function(file, errorMessage) {
            console.error('Upload error:', errorMessage);
            alert('Error uploading file: ' + errorMessage);
        });

        this.on('addedfile', function(file) {
            console.log('File added:', file);
        });
    }
};

// Initialize Dropzone when the modal is opened
window.openUploadModal = function() {
    const modal = document.getElementById('uploadModal');
    modal.classList.remove('hidden');
    
    // Use modal manager if available
    if (window.modalManager) {
        window.modalManager.open('uploadModal', modal);
    } else {
        document.body.style.overflow = 'hidden';
    }

    // Initialize Dropzone only if not already initialized
    if (!window.myDropzone) {
        const dropzoneElement = document.getElementById('dropzone-upload');
        if (dropzoneElement) {
            window.myDropzone = new Dropzone(dropzoneElement, {
                url: dropzoneElement.closest('form').action,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                paramName: 'files[]',
                maxFilesize: 10,
                addRemoveLinks: true,
                dictRemoveFile: 'Remove',
                autoProcessQueue: false,
                uploadMultiple: true,
                parallelUploads: 5,
                maxFiles: 10,
                createImageThumbnails: true,
                previewTemplate: `
                    <div class="dz-preview dz-file-preview">
                        <div class="flex items-center gap-x-3 w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-sm text-gray-800 dark:text-gray-200 rounded-lg p-2">
                            <div class="flex-1 truncate">
                                <span data-dz-name></span>
                                <span class="text-gray-500 dark:text-gray-400" data-dz-size></span>
                            </div>
                            <div class="flex items-center gap-x-2">
                                <button type="button" class="flex items-center gap-x-1 text-gray-500 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300" data-dz-remove>
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M18 6L6 18"></path>
                                        <path d="M6 6l12 12"></path>
                                    </svg>
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                `,
                init: function() {
                    this.on('success', function(file, response) {
                        console.log('File uploaded successfully');
                        window.location.reload();
                    });
                    
                    this.on('error', function(file, errorMessage) {
                        console.error('Upload error:', errorMessage);
                        alert('Error uploading file: ' + errorMessage);
                    });
                }
            });
        }
    }
};

window.closeUploadModal = function() {
    const modal = document.getElementById('uploadModal');
    modal.classList.add('hidden');
    
    // Use modal manager if available
    if (window.modalManager) {
        window.modalManager.close('uploadModal');
    } else {
        document.body.style.overflow = 'auto';
    }
    
    // Clear dropzone
    if (window.myDropzone) {
        window.myDropzone.removeAllFiles(true);
    }
};

window.submitForm = function() {
    if (window.myDropzone && window.myDropzone.files.length > 0) {
        window.myDropzone.processQueue();
    } else {
        alert('Please add files to upload');
    }
};

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const modalBackdrop = document.getElementById('modalBackdrop');
    if (modalBackdrop) {
        modalBackdrop.addEventListener('click', window.closeUploadModal);
    }
    
    // Create backward compatibility for old modal functions
    if (!window.openModal) {
        window.openModal = window.openUploadModal;
    }
    if (!window.closeModal) {
        window.closeModal = window.closeUploadModal;
    }
});
