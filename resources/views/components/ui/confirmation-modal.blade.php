<!-- Generic Confirmation Modal -->
<div id="confirmation-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeConfirmationModal()"></div>
        
        <!-- Modal Content -->
        <div class="relative bg-white dark:bg-gray-900 rounded-2xl shadow-2xl max-w-md w-full overflow-hidden">
            <!-- Header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-3">
                    <div id="confirmation-icon" class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 id="confirmation-title" class="text-lg font-semibold text-gray-900 dark:text-white">Confirm Action</h3>
                </div>
                <button onclick="closeConfirmationModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Content -->
            <div class="p-6">
                <p id="confirmation-message" class="text-sm text-gray-700 dark:text-gray-300">
                    Are you sure you want to proceed with this action?
                </p>
            </div>
            
            <!-- Actions -->
            <div class="bg-gray-50 dark:bg-gray-800 px-6 py-4 flex justify-end space-x-3">
                <button id="confirmation-cancel" onclick="closeConfirmationModal()" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-lg dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 transition-colors">
                    Cancel
                </button>
                <button id="confirmation-confirm" onclick="executeConfirmationAction()" 
                        class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors">
                    Confirm
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let confirmationCallback = null;
let confirmationData = null;

/**
 * Show confirmation modal with customizable options
 * @param {Object} options - Configuration object
 * @param {string} options.title - Modal title (default: "Confirm Action")
 * @param {string} options.message - Confirmation message (required)
 * @param {string} options.confirmText - Confirm button text (default: "Confirm")
 * @param {string} options.cancelText - Cancel button text (default: "Cancel")
 * @param {string} options.type - Modal type: 'warning', 'danger', 'info' (default: 'warning')
 * @param {Function} options.onConfirm - Callback function when confirmed
 * @param {*} options.data - Optional data to pass to callback
 */
function showConfirmationModal(options) {
    const {
        title = 'Confirm Action',
        message,
        confirmText = 'Confirm',
        cancelText = 'Cancel',
        type = 'warning',
        onConfirm,
        data = null
    } = options;

    if (!message) {
        console.error('Confirmation message is required');
        return false;
    }

    // Store callback and data
    confirmationCallback = onConfirm;
    confirmationData = data;

    // Update modal content
    document.getElementById('confirmation-title').textContent = title;
    document.getElementById('confirmation-message').textContent = message;
    document.getElementById('confirmation-confirm').textContent = confirmText;
    document.getElementById('confirmation-cancel').textContent = cancelText;

    // Update icon and colors based on type
    const iconContainer = document.getElementById('confirmation-icon');
    const confirmButton = document.getElementById('confirmation-confirm');
    
    switch (type) {
        case 'danger':
            iconContainer.className = 'w-8 h-8 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center';
            iconContainer.innerHTML = `
                <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            `;
            confirmButton.className = 'px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors';
            break;
            
        case 'info':
            iconContainer.className = 'w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center';
            iconContainer.innerHTML = `
                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            `;
            confirmButton.className = 'px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors';
            break;
            
        default: // warning
            iconContainer.className = 'w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center';
            iconContainer.innerHTML = `
                <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            `;
            confirmButton.className = 'px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors';
            break;
    }

    // Show modal
    document.getElementById('confirmation-modal').classList.remove('hidden');
    return true;
}

function closeConfirmationModal() {
    document.getElementById('confirmation-modal').classList.add('hidden');
    confirmationCallback = null;
    confirmationData = null;
}

function executeConfirmationAction() {
    if (confirmationCallback && typeof confirmationCallback === 'function') {
        confirmationCallback(confirmationData);
    }
    closeConfirmationModal();
}

// Make functions globally available
window.showConfirmationModal = showConfirmationModal;
window.closeConfirmationModal = closeConfirmationModal;
window.executeConfirmationAction = executeConfirmationAction;

// Helper function for simple confirmations (backwards compatibility)
window.showSimpleConfirm = function(message, onConfirm, options = {}) {
    return showConfirmationModal({
        message: message,
        onConfirm: onConfirm,
        ...options
    });
};
</script>