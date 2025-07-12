@props(['file' => null])

<div id="ai-suggestion-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <!-- Modal panel -->
        <div class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-900 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                            AI Document Analysis
                        </h3>
                        
                        <!-- Loading State -->
                        <div id="ai-loading" class="mt-4">
                            <div class="flex items-center">
                                <x-ui.spinner class="h-5 w-5 mr-3" />
                                <p class="text-sm text-gray-500 dark:text-gray-400">Analyzing document...</p>
                            </div>
                        </div>
                        
                        <!-- Analysis Results -->
                        <div id="ai-results" class="hidden mt-4 space-y-4">
                            <!-- Document Info -->
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Document:</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400" id="ai-document-name"></p>
                            </div>
                            
                            <!-- Suggested Folder -->
                            <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-lg p-4">
                                <div class="flex items-start">
                                    <svg class="h-5 w-5 text-indigo-600 dark:text-indigo-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                    </svg>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-indigo-900 dark:text-indigo-200">Suggested Folder:</p>
                                        <div id="ai-suggested-folder-breadcrumb" class="mt-1"></div>
                                        <div id="ai-confidence-container" class="mt-2 flex items-center">
                                            <span id="ai-confidence-label" class="text-xs text-indigo-600 dark:text-indigo-400">Confidence:</span>
                                            <div class="ml-2 flex-1 bg-gray-200 dark:bg-gray-600 rounded-full h-2 max-w-[100px]">
                                                <div id="ai-confidence-bar" class="bg-indigo-600 dark:bg-indigo-400 h-2 rounded-full" style="width: 0%"></div>
                                            </div>
                                            <span class="ml-2 text-xs text-indigo-600 dark:text-indigo-400" id="ai-confidence-text">0%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Reasoning -->
                            <div>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Analysis:</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400" id="ai-reasoning"></p>
                            </div>
                            
                            <!-- Key Information -->
                            <div id="ai-key-info-section" class="hidden">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Key Information:</p>
                                <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-400" id="ai-key-info"></ul>
                            </div>
                            
                            <!-- Alternative Folders -->
                            <div id="ai-alternatives-section" class="hidden">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alternative Options:</p>
                                <div class="space-y-2" id="ai-alternatives"></div>
                            </div>
                        </div>
                        
                        <!-- Error State -->
                        <div id="ai-error" class="hidden mt-4">
                            <x-ui.alert variant="danger">
                                <p id="ai-error-message"></p>
                            </x-ui.alert>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="ai-accept-btn" class="hidden w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Accept & Move
                </button>
                <button type="button" id="ai-cancel-btn" onclick="closeAISuggestionModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentFileId = null;
let currentAnalysis = null;

function showAISuggestionModal(fileId, forceNew = false) {
    currentFileId = fileId;
    document.getElementById('ai-suggestion-modal').classList.remove('hidden');
    document.getElementById('ai-loading').classList.remove('hidden');
    document.getElementById('ai-results').classList.add('hidden');
    document.getElementById('ai-error').classList.add('hidden');
    document.getElementById('ai-accept-btn').classList.add('hidden');
    
    // Analyze the document
    analyzeDocument(fileId, forceNew);
}

function closeAISuggestionModal() {
    document.getElementById('ai-suggestion-modal').classList.add('hidden');
    currentFileId = null;
    currentAnalysis = null;
}

function showLoading() {
    document.getElementById('ai-loading').classList.remove('hidden');
    document.getElementById('ai-results').classList.add('hidden');
    document.getElementById('ai-error').classList.add('hidden');
}

function showError(message) {
    document.getElementById('ai-loading').classList.add('hidden');
    document.getElementById('ai-results').classList.add('hidden');
    document.getElementById('ai-error').classList.remove('hidden');
    document.getElementById('ai-error-message').textContent = message;
}

function analyzeDocument(fileId, forceNew = false) {
    console.log('analyzeDocument called with fileId:', fileId, 'forceNew:', forceNew);
    const url = forceNew ? `/user/files/${fileId}/analyze?force_new=1` : `/user/files/${fileId}/analyze`;
    
    // Show modal first
    document.getElementById('ai-suggestion-modal').classList.remove('hidden');
    showLoading();
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const contentType = response.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
            throw new TypeError("Response was not JSON");
        }
        return response.json();
    })
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
    console.log('displayAnalysis called with data:', data);
    currentFileId = data.file.id;
    currentAnalysis = data.analysis;
    
    // Make sure modal is visible
    document.getElementById('ai-suggestion-modal').classList.remove('hidden');
    document.getElementById('ai-loading').classList.add('hidden');
    document.getElementById('ai-results').classList.remove('hidden');
    
    // Document info
    document.getElementById('ai-document-name').textContent = data.file.name;
    
    // Check if file is already in correct folder
    if (data.analysis.already_in_correct_folder) {
        // Show success message
        document.getElementById('ai-accept-btn').classList.add('hidden');
        const breadcrumbContainer = document.getElementById('ai-suggested-folder-breadcrumb');
        breadcrumbContainer.innerHTML = '<span class="text-green-600 dark:text-green-400 font-medium">✓ ' + 
            'This file is already in the correct folder' + '</span>';
        
        // Update confidence to show success
        const confidenceLabel = document.getElementById('ai-confidence-label');
        if (confidenceLabel) {
            confidenceLabel.className = 'text-xs text-green-600 dark:text-green-400 font-medium';
            confidenceLabel.textContent = 'Status:';
        }
        
        // Hide the confidence bar and text
        const confidenceBar = document.getElementById('ai-confidence-bar');
        if (confidenceBar) {
            confidenceBar.parentElement.style.display = 'none';
        }
        const confidenceText = document.getElementById('ai-confidence-text');
        if (confidenceText) {
            confidenceText.style.display = 'none';
        }
        
        // Update reasoning
        document.getElementById('ai-reasoning').textContent = data.analysis.reasoning || 'This file is already organized correctly.';
        return;
    }
    
    // Check if there's a warning
    if (data.analysis.warning || data.analysis.transaction_type === 'not_related' || !data.analysis.suggested_folder_id) {
        // Show warning instead of folder suggestion
        document.getElementById('ai-accept-btn').classList.add('hidden');
        const breadcrumbContainer = document.getElementById('ai-suggested-folder-breadcrumb');
        breadcrumbContainer.innerHTML = '<span class="text-red-600 dark:text-red-400 font-medium">⚠️ ' + 
            (data.analysis.warning || 'This document does not belong to any of your companies') + '</span>';
        
        // Update confidence to show warning
        const confidenceLabel = document.getElementById('ai-confidence-label');
        if (confidenceLabel) {
            confidenceLabel.className = 'text-xs text-red-600 dark:text-red-400 font-medium';
            confidenceLabel.textContent = 'Warning:';
        }
        
        // Hide the confidence bar and text
        const confidenceBar = document.getElementById('ai-confidence-bar');
        if (confidenceBar) {
            confidenceBar.parentElement.style.display = 'none';
        }
        const confidenceText = document.getElementById('ai-confidence-text');
        if (confidenceText) {
            confidenceText.style.display = 'none';
        }
    } else {
        // Normal flow - show folder suggestion
        document.getElementById('ai-accept-btn').classList.remove('hidden');
        const folderPath = data.analysis.folder_path || data.analysis.folder_name;
        const breadcrumbContainer = document.getElementById('ai-suggested-folder-breadcrumb');
        breadcrumbContainer.innerHTML = '';
        
        if (folderPath && folderPath.includes('/')) {
            // Create breadcrumb
            const parts = folderPath.split('/').filter(p => p);
            parts.forEach((part, index) => {
                const span = document.createElement('span');
                span.className = index === parts.length - 1 
                    ? 'text-sm text-indigo-700 dark:text-indigo-300 font-semibold' 
                    : 'text-sm text-indigo-600 dark:text-indigo-400';
                span.textContent = part;
                breadcrumbContainer.appendChild(span);
                
                if (index < parts.length - 1) {
                    const separator = document.createElement('span');
                    separator.className = 'mx-1 text-indigo-400 dark:text-indigo-500';
                    separator.textContent = '›';
                    breadcrumbContainer.appendChild(separator);
                }
            });
        } else {
            // Single folder name
            const span = document.createElement('span');
            span.className = 'text-sm text-indigo-700 dark:text-indigo-300 font-semibold';
            span.textContent = data.analysis.folder_name;
            breadcrumbContainer.appendChild(span);
        }
    }
    
    // Confidence (only if not warning)
    if (!data.analysis.warning && data.analysis.transaction_type !== 'not_related' && data.analysis.suggested_folder_id) {
        const confidence = data.analysis.confidence || 0;
        document.getElementById('ai-confidence-bar').style.width = confidence + '%';
        document.getElementById('ai-confidence-text').textContent = confidence + '%';
        
        // Reset confidence elements
        const confidenceLabel = document.getElementById('ai-confidence-label');
        if (confidenceLabel) {
            confidenceLabel.className = 'text-xs text-indigo-600 dark:text-indigo-400';
            confidenceLabel.textContent = 'Confidence:';
        }
        
        const confidenceBarParent = document.getElementById('ai-confidence-bar')?.parentElement;
        if (confidenceBarParent) {
            confidenceBarParent.style.display = '';
        }
        
        const confidenceTextEl = document.getElementById('ai-confidence-text');
        if (confidenceTextEl) {
            confidenceTextEl.style.display = '';
        }
    }
    
    // Reasoning
    document.getElementById('ai-reasoning').textContent = data.analysis.reasoning;
    
    // Key information
    if (data.analysis.key_information && data.analysis.key_information.length > 0) {
        document.getElementById('ai-key-info-section').classList.remove('hidden');
        const keyInfoList = document.getElementById('ai-key-info');
        keyInfoList.innerHTML = '';
        data.analysis.key_information.forEach(info => {
            const li = document.createElement('li');
            // Handle both string and object formats
            if (typeof info === 'string') {
                li.textContent = info;
            } else if (typeof info === 'object') {
                // If it's an object, try to extract a meaningful string
                li.textContent = JSON.stringify(info);
            }
            keyInfoList.appendChild(li);
        });
    }
    
    // Alternative folders
    if (data.analysis.alternative_folders && data.analysis.alternative_folders.length > 0) {
        document.getElementById('ai-alternatives-section').classList.remove('hidden');
        const altContainer = document.getElementById('ai-alternatives');
        altContainer.innerHTML = '';
        
        data.analysis.alternative_folders.forEach(alt => {
            const div = document.createElement('div');
            div.className = 'bg-gray-100 dark:bg-gray-600 rounded p-2 text-sm';
            
            // Create breadcrumb for alternative path
            let pathDisplay = alt.folder_name || alt.name || 'Unknown';
            if (alt.folder_path || alt.path) {
                const path = alt.folder_path || alt.path;
                if (path.includes('/')) {
                    const parts = path.split('/').filter(p => p);
                    pathDisplay = parts.join(' › ');
                }
            }
            
            const reason = alt.reason || '';
            
            div.innerHTML = `
                <div class="font-medium text-gray-700 dark:text-gray-300">${pathDisplay}</div>
                ${reason ? `<div class="text-gray-600 dark:text-gray-400 mt-1">${reason}</div>` : ''}
            `;
            altContainer.appendChild(div);
        });
    }
    
    // Show accept button if current folder is different from suggested
    if (data.file.current_folder_id !== data.analysis.suggested_folder_id) {
        document.getElementById('ai-accept-btn').classList.remove('hidden');
        document.getElementById('ai-accept-btn').onclick = () => acceptSuggestion(data.analysis.suggested_folder_id);
    }
}


function acceptSuggestion(folderId) {
    if (!currentFileId || !folderId) return;
    
    // Disable button
    const acceptBtn = document.getElementById('ai-accept-btn');
    acceptBtn.disabled = true;
    acceptBtn.textContent = 'Moving...';
    
    fetch(`/user/files/${currentFileId}/accept-suggestion`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            folder_id: folderId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message with toastr if available
            if (typeof toastr !== 'undefined') {
                toastr.success(`File successfully moved to ${data.new_folder} folder!`);
            }
            
            // Close modal and reload after a short delay
            setTimeout(() => {
                closeAISuggestionModal();
                window.location.reload();
            }, 1500);
        } else {
            if (typeof toastr !== 'undefined') {
                toastr.error('Failed to move file: ' + (data.error || 'Unknown error'));
            } else {
                alert('Failed to move file: ' + (data.error || 'Unknown error'));
            }
            acceptBtn.disabled = false;
            acceptBtn.textContent = 'Accept & Move';
        }
    })
    .catch(error => {
        alert('Network error: ' + error.message);
        acceptBtn.disabled = false;
        acceptBtn.textContent = 'Accept & Move';
    });
}

// Global function to show AI suggestion modal
window.showAISuggestionModal = function(fileId, forceNew = false) {
    analyzeDocument(fileId, forceNew);
};

// Close modal when clicking outside
document.getElementById('ai-suggestion-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAISuggestionModal();
    }
});
</script>