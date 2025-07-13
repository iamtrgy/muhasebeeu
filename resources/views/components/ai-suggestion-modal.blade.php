@props(['file' => null])

<!-- AI Suggestion Modal - Minimal Design -->
<div id="ai-suggestion-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50" onclick="closeAISuggestionModal()"></div>
        
        <!-- Modal Content -->
        <div class="relative bg-white dark:bg-gray-900 rounded-lg shadow-xl max-w-lg w-full">
            <!-- Header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">AI Analysis</h3>
                <button onclick="closeAISuggestionModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Content -->
            <div class="p-4">
                <!-- Loading State -->
                <div id="ai-loading" class="py-8 text-center">
                    <svg class="animate-spin h-8 w-8 text-indigo-600 mx-auto mb-3" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-sm text-gray-500">Analyzing document...</p>
                </div>
                
                <!-- Analysis Results -->
                <div id="ai-results" class="hidden space-y-4">
                    <!-- File Name -->
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        <span class="font-medium">File:</span>
                        <span id="ai-document-name"></span>
                    </div>
                    
                    <!-- Suggested Folder -->
                    <div class="bg-gray-50 dark:bg-gray-800 rounded p-3">
                        <div class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Suggested Location:</div>
                        <div id="ai-suggested-folder-breadcrumb" class="text-sm text-indigo-600 dark:text-indigo-400"></div>
                        <div id="ai-confidence-container" class="mt-2">
                            <span class="text-xs text-gray-500">Confidence: </span>
                            <span id="ai-confidence-text" class="text-xs font-medium text-gray-700 dark:text-gray-300">0%</span>
                        </div>
                    </div>
                    
                    <!-- Reasoning -->
                    <div>
                        <div class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Why?</div>
                        <p id="ai-reasoning" class="text-sm text-gray-600 dark:text-gray-400"></p>
                    </div>
                    
                    <!-- Alternative Folders -->
                    <div id="ai-alternatives-section" class="hidden">
                        <div class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Other Options:</div>
                        <div id="ai-alternatives" class="space-y-2"></div>
                    </div>
                </div>
                
                <!-- Error State -->
                <div id="ai-error" class="hidden py-8">
                    <div class="text-center">
                        <svg class="w-12 h-12 text-red-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p id="ai-error-message" class="text-sm text-red-600 dark:text-red-400"></p>
                    </div>
                </div>
            </div>
            
            <!-- Actions Footer -->
            <div id="ai-actions" class="p-4 bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 hidden">
                <div class="flex gap-2">
                    <button id="ai-accept-btn" onclick="acceptSuggestion()" class="flex-1 px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded transition-colors">
                        Accept
                    </button>
                    <button onclick="closeAISuggestionModal()" class="flex-1 px-3 py-2 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded border border-gray-300 dark:border-gray-600 transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentFileId = null;
let currentAnalysis = null;
let currentSuggestedFolderId = null;

function showAISuggestionModal(fileId, forceNew = false) {
    console.log('showAISuggestionModal called with fileId:', fileId, 'type:', typeof fileId);
    currentFileId = fileId;
    console.log('currentFileId set to:', currentFileId);
    document.getElementById('ai-suggestion-modal').classList.remove('hidden');
    document.getElementById('ai-loading').classList.remove('hidden');
    document.getElementById('ai-results').classList.add('hidden');
    document.getElementById('ai-error').classList.add('hidden');
    document.getElementById('ai-actions').classList.add('hidden');
    
    analyzeDocument(fileId, forceNew);
}

function closeAISuggestionModal() {
    document.getElementById('ai-suggestion-modal').classList.add('hidden');
    currentFileId = null;
    currentAnalysis = null;
    currentSuggestedFolderId = null;
}

function analyzeDocument(fileId, forceNew = false) {
    const url = forceNew ? `/user/files/${fileId}/analyze?force_new=1` : `/user/files/${fileId}/analyze`;
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayAnalysis(data);
        } else {
            showError(data.error || 'Analysis failed');
        }
    })
    .catch(error => {
        console.error('Analysis error:', error);
        showError('Error: ' + error.message);
    });
}

function displayAnalysis(data) {
    currentAnalysis = data.analysis;
    currentSuggestedFolderId = data.analysis.suggested_folder_id;
    
    document.getElementById('ai-loading').classList.add('hidden');
    document.getElementById('ai-results').classList.remove('hidden');
    
    // File name
    const docName = document.getElementById('ai-document-name');
    if (docName) {
        docName.textContent = data.file.name;
    }
    
    // Suggested folder or deletion
    const breadcrumb = document.getElementById('ai-suggested-folder-breadcrumb');
    if (breadcrumb) {
        if (data.analysis.suggest_deletion) {
            breadcrumb.innerHTML = '<span class="text-red-600 dark:text-red-400">No folder - Delete recommended</span>';
        } else {
            const folderPath = data.analysis.folder_path || data.analysis.folder_name || 'Unknown';
            breadcrumb.textContent = folderPath;
        }
    }
    
    // Confidence
    const confidenceText = document.getElementById('ai-confidence-text');
    if (confidenceText) {
        confidenceText.textContent = (data.analysis.confidence || 0) + '%';
    }
    
    // Reasoning
    const reasoning = document.getElementById('ai-reasoning');
    if (reasoning) {
        reasoning.textContent = data.analysis.reasoning || 'No reasoning provided';
    }
    
    // Alternative folders
    if (data.analysis.alternative_folders && data.analysis.alternative_folders.length > 0) {
        document.getElementById('ai-alternatives-section').classList.remove('hidden');
        const altContainer = document.getElementById('ai-alternatives');
        altContainer.innerHTML = '';
        
        data.analysis.alternative_folders.slice(0, 3).forEach(alt => {
            const div = document.createElement('div');
            div.className = 'flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-800 rounded text-sm';
            
            const folderName = alt.folder_path || alt.folder_name || 'Unknown';
            const confidence = alt.confidence || 0;
            
            div.innerHTML = `
                <span class="text-gray-700 dark:text-gray-300">${folderName}</span>
                <button onclick="acceptSuggestion(${alt.folder_id})" class="text-xs px-2 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded">
                    Use This
                </button>
            `;
            altContainer.appendChild(div);
        });
    }
    
    // Show actions based on analysis
    if (data.analysis.suggest_deletion) {
        // Show deletion suggestion
        document.getElementById('ai-actions').classList.remove('hidden');
        document.getElementById('ai-actions').innerHTML = `
            <div class="space-y-3">
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded p-3">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-red-800 dark:text-red-200">Suggested Action: Delete File</p>
                            <p class="text-sm text-red-600 dark:text-red-400 mt-1">${data.analysis.deletion_reason || 'This document is not related to any of your companies.'}</p>
                        </div>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button onclick="deleteFile()" class="flex-1 px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded transition-colors">
                        Delete File
                    </button>
                    <button onclick="closeAISuggestionModal()" class="flex-1 px-3 py-2 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded border border-gray-300 dark:border-gray-600 transition-colors">
                        Keep File
                    </button>
                </div>
            </div>
        `;
    } else if (currentSuggestedFolderId && data.file.current_folder_id !== currentSuggestedFolderId) {
        // Show normal move actions
        document.getElementById('ai-actions').classList.remove('hidden');
        document.getElementById('ai-actions').innerHTML = `
            <div class="flex gap-2">
                <button id="ai-accept-btn" onclick="acceptSuggestion()" class="flex-1 px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded transition-colors">
                    Accept
                </button>
                <button onclick="closeAISuggestionModal()" class="flex-1 px-3 py-2 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded border border-gray-300 dark:border-gray-600 transition-colors">
                    Cancel
                </button>
            </div>
        `;
    }
}

function showError(message) {
    document.getElementById('ai-loading').classList.add('hidden');
    document.getElementById('ai-results').classList.add('hidden');
    document.getElementById('ai-error').classList.remove('hidden');
    document.getElementById('ai-actions').classList.add('hidden');
    
    const errorMsg = document.getElementById('ai-error-message');
    if (errorMsg) {
        errorMsg.textContent = message;
    }
}

function acceptSuggestion(folderId = null) {
    const targetFolderId = folderId || currentSuggestedFolderId;
    if (!currentFileId || !targetFolderId) return;
    
    fetch(`/user/files/${currentFileId}/accept-suggestion`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            folder_id: targetFolderId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeAISuggestionModal();
            window.location.reload();
        } else {
            alert('Failed to move file: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        alert('Network error: ' + error.message);
    });
}

function deleteFile() {
    console.log('deleteFile called, currentFileId:', currentFileId);
    
    if (!currentFileId || currentFileId === 'null' || currentFileId === 'undefined') {
        console.error('ERROR: currentFileId is invalid:', currentFileId);
        alert('No file selected for deletion. Please try opening the modal again.');
        return;
    }
    
    if (!confirm('Are you sure you want to delete this file? This action cannot be undone.')) {
        return;
    }
    
    // Validate fileId is a positive number
    const fileId = parseInt(currentFileId);
    if (!fileId || fileId <= 0) {
        console.error('ERROR: Invalid file ID for deletion:', currentFileId);
        alert('Invalid file ID. Cannot delete file.');
        return;
    }
    
    const deleteUrl = `/user/files/${fileId}`;
    console.log('DELETE URL:', deleteUrl);
    console.log('Full URL that will be used:', new URL(deleteUrl, window.location.href).href);
    
    fetch(deleteUrl, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Delete response status:', response.status);
        
        if (response.ok) {
            // If response is OK (200-299), handle success
            console.log('File deleted successfully');
            closeAISuggestionModal();
            
            // Remove the file from the list if it exists
            const fileRow = document.querySelector(`div[data-file-id="${currentFileId}"]`);
            if (fileRow) {
                fileRow.remove();
            }
            
            // Show success message
            alert('File deleted successfully');
        } else {
            // If response is not OK, show error
            response.text().then(errorText => {
                console.error('Delete failed with status:', response.status, errorText);
                alert('Failed to delete file: ' + response.status);
            });
        }
    })
    .catch(error => {
        alert('Network error: ' + error.message);
    });
}

// Global function
window.showAISuggestionModal = showAISuggestionModal;

// Debug: Catch any navigation events
window.addEventListener('beforeunload', function(e) {
    console.log('Page unloading - this might be causing the DELETE request');
});

// Override fetch to log all requests
const originalFetch = window.fetch;
window.fetch = function(...args) {
    console.log('FETCH REQUEST:', args[0], args[1]?.method || 'GET');
    return originalFetch.apply(this, arguments);
};
</script>