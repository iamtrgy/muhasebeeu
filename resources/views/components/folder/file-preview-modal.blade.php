<!-- File Preview Modal -->
<div id="filePreviewModal" class="hidden fixed inset-0 z-[70]" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" id="modal-backdrop"></div>

    <!-- Modal Content -->
    <div class="fixed inset-0 z-[80] overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl">
                <div class="absolute top-0 right-0 pt-4 pr-4">
                    <button type="button" onclick="closePreviewModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="bg-white dark:bg-gray-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="w-full">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-white mb-4 pt-4" id="preview-title"></h3>
                        <div id="preview-content" class="mt-2 max-h-[70vh] overflow-auto"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Make functions globally available
window.previewFile = function(fileName, mimeType, url) {
    const modal = document.getElementById('filePreviewModal');
    const title = document.getElementById('preview-title');
    const content = document.getElementById('preview-content');
    
    title.textContent = fileName;
    content.innerHTML = '<div class="flex justify-center items-center h-32"><svg class="animate-spin h-8 w-8 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></div>';
    
    modal.classList.remove('hidden');
    
    if (mimeType.startsWith('image/')) {
        content.innerHTML = `<img src="${url}" alt="${fileName}" class="max-w-full h-auto mx-auto">`;
    } else if (mimeType === 'application/pdf') {
        content.innerHTML = `<iframe src="${url}" class="w-full h-[70vh]" frameborder="0"></iframe>`;
    } else {
        // For non-previewable files, show a download prompt
        content.innerHTML = `
            <div class="text-center p-8">
                <p class="mb-4">This file type cannot be previewed directly.</p>
                <a href="${url}?download=${encodeURIComponent(fileName)}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Download File
                </a>
            </div>`;
    }
}

window.closePreviewModal = function() {
    const modal = document.getElementById('filePreviewModal');
    modal.classList.add('hidden');
}

// Initialize event listeners when the document is ready
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('filePreviewModal');
    const backdrop = document.getElementById('modal-backdrop');
    
    // Close modal when clicking the backdrop
    backdrop.addEventListener('click', function(event) {
        if (event.target === backdrop) {
            closePreviewModal();
        }
    });

    // Close modal on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closePreviewModal();
        }
    });
});
</script>
@endpush 