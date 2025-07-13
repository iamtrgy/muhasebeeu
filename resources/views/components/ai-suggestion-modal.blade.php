@props(['file' => null])

<!-- AI Suggestion Modal - Modern Design -->
<div id="ai-suggestion-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeAISuggestionModal()"></div>
        
        <!-- Modal Content -->
        <div class="relative bg-white dark:bg-gray-900 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">AI Document Analysis</h3>
                </div>
                <button onclick="closeAISuggestionModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Content -->
            <div class="p-6 overflow-y-auto max-h-[60vh]">
                <div class="space-y-6">
                    <!-- Loading State -->
                    <div id="ai-loading" class="flex items-center justify-center py-12">
                        <div class="text-center">
                            <svg class="animate-spin h-8 w-8 text-indigo-600 dark:text-indigo-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Analyzing document...</p>
                        </div>
                    </div>
                    
                    <!-- Analysis Results -->
                    <div id="ai-results" class="hidden space-y-6">
                        <!-- Document Info Card -->
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 rounded-xl p-4 border border-gray-200 dark:border-gray-600">
                            <div class="flex items-center space-x-3 mb-3">
                                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-base font-semibold text-gray-900 dark:text-white">Document Information</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400" id="ai-document-name"></p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Suggested Folder Section -->
                        <div class="bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-900/20 dark:to-blue-900/20 rounded-xl p-4 border border-indigo-200 dark:border-indigo-700">
                            <div class="flex items-center space-x-3 mb-3">
                                <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                    </svg>
                                </div>
                                <h4 class="text-base font-semibold text-gray-900 dark:text-white">Suggested Location</h4>
                            </div>
                            <div id="ai-suggested-folder-breadcrumb" class="mb-3"></div>
                            <div id="ai-confidence-container" class="flex items-center justify-between bg-white dark:bg-gray-800 rounded-lg p-3 border border-gray-200 dark:border-gray-600">
                                <span id="ai-confidence-label" class="text-sm font-medium text-gray-700 dark:text-gray-300">Confidence Level</span>
                                <div class="flex items-center space-x-3">
                                    <div class="w-24 bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                        <div id="ai-confidence-bar" class="bg-gradient-to-r from-indigo-500 to-blue-500 h-2 rounded-full transition-all duration-500" style="width: 0%"></div>
                                    </div>
                                    <span class="text-sm font-semibold text-indigo-600 dark:text-indigo-400" id="ai-confidence-text">0%</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- AI Analysis Section -->
                        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 rounded-xl p-4 border border-purple-200 dark:border-purple-700">
                            <div class="flex items-center space-x-3 mb-3">
                                <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                    </svg>
                                </div>
                                <h4 class="text-base font-semibold text-gray-900 dark:text-white">AI Analysis</h4>
                            </div>
                            <p class="text-sm leading-relaxed text-gray-700 dark:text-gray-300" id="ai-reasoning"></p>
                        </div>
                        
                        <!-- Key Information -->
                        <div id="ai-key-info-section" class="hidden bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl p-4 border border-green-200 dark:border-green-700">
                            <div class="flex items-center space-x-3 mb-3">
                                <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h4 class="text-base font-semibold text-gray-900 dark:text-white">Key Information</h4>
                            </div>
                            <ul class="space-y-1 text-sm text-gray-700 dark:text-gray-300" id="ai-key-info"></ul>
                        </div>
                        
                        <!-- Alternative Folders -->
                        <div id="ai-alternatives-section" class="hidden bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-xl p-4 border border-amber-200 dark:border-amber-700">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-8 h-8 bg-amber-100 dark:bg-amber-900 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </div>
                                <h4 class="text-base font-semibold text-gray-900 dark:text-white">Alternative Suggestions</h4>
                            </div>
                            <div class="grid gap-3" id="ai-alternatives"></div>
                        </div>
                        
                        <!-- Manual Folder Selection -->
                        <div id="ai-manual-section" class="hidden bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 rounded-xl p-4 border border-gray-200 dark:border-gray-600">
                            <div class="flex items-center space-x-3 mb-3">
                                <div class="w-8 h-8 bg-gray-100 dark:bg-gray-800 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4" />
                                    </svg>
                                </div>
                                <label class="text-base font-semibold text-gray-900 dark:text-white">Manual Folder Selection</label>
                            </div>
                            <select id="manual-folder-select" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">-- Select Folder --</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Error State -->
                    <div id="ai-error" class="hidden">
                        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-xl p-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-base font-semibold text-red-900 dark:text-red-200">Error</h4>
                                    <p class="text-sm text-red-700 dark:text-red-300" id="ai-error-message"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Actions Footer -->
            <div class="p-6 bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                <div class="flex flex-wrap gap-3">
                    <!-- Primary Action Button -->
                    <button type="button" id="ai-accept-btn" class="hidden inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Accept & Move
                    </button>
                    
                    <!-- Re-analyze Button -->
                    <button type="button" id="ai-reanalyze-btn" class="hidden inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Re-analyze
                    </button>
                    
                    <!-- Manual Move Button -->
                    <button type="button" id="ai-manual-btn" class="hidden inline-flex items-center px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg border border-gray-300 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                        </svg>
                        Move Manually
                    </button>
                    
                    <!-- Close Button -->
                    <button type="button" id="ai-cancel-btn" onclick="closeAISuggestionModal()" class="inline-flex items-center px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg border border-gray-300 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentFileId = null;
let currentAnalysis = null;
let availableFolders = [];

function showAISuggestionModal(fileId, forceNew = false) {
    currentFileId = fileId;
    document.getElementById('ai-suggestion-modal').classList.remove('hidden');
    document.getElementById('ai-loading').classList.remove('hidden');
    document.getElementById('ai-results').classList.add('hidden');
    document.getElementById('ai-error').classList.add('hidden');
    
    // Hide all action buttons initially
    document.getElementById('ai-accept-btn').classList.add('hidden');
    document.getElementById('ai-reanalyze-btn').classList.add('hidden');
    document.getElementById('ai-manual-btn').classList.add('hidden');
    document.getElementById('ai-manual-section').classList.add('hidden');
    
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
    availableFolders = data.folders || [];
    
    // Make sure modal is visible
    document.getElementById('ai-suggestion-modal').classList.remove('hidden');
    document.getElementById('ai-loading').classList.add('hidden');
    document.getElementById('ai-results').classList.remove('hidden');
    
    // Document info
    document.getElementById('ai-document-name').textContent = data.file.name;
    
    // Check if file is already in correct folder
    if (data.analysis.already_in_correct_folder) {
        handleAlreadyCorrectFolder(data);
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
            // Create clickable breadcrumb
            const link = document.createElement('a');
            link.href = `/user/folders/${data.analysis.suggested_folder_id}`;
            link.target = '_blank';
            link.className = 'hover:text-indigo-800 dark:hover:text-indigo-200 underline';
            
            const parts = folderPath.split('/').filter(p => p);
            parts.forEach((part, index) => {
                const span = document.createElement('span');
                span.className = index === parts.length - 1 
                    ? 'text-sm text-indigo-700 dark:text-indigo-300 font-semibold' 
                    : 'text-sm text-indigo-600 dark:text-indigo-400';
                span.textContent = part;
                link.appendChild(span);
                
                if (index < parts.length - 1) {
                    const separator = document.createElement('span');
                    separator.className = 'mx-1 text-indigo-400 dark:text-indigo-500';
                    separator.textContent = '›';
                    link.appendChild(separator);
                }
            });
            breadcrumbContainer.appendChild(link);
        } else {
            // Single folder name - also clickable
            const link = document.createElement('a');
            link.href = `/user/folders/${data.analysis.suggested_folder_id}`;
            link.target = '_blank';
            link.className = 'text-sm text-indigo-700 dark:text-indigo-300 font-semibold hover:text-indigo-800 dark:hover:text-indigo-200 underline';
            link.textContent = data.analysis.folder_name;
            breadcrumbContainer.appendChild(link);
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

function handleAlreadyCorrectFolder(data) {
    // Show success message with enhanced details
    const breadcrumbContainer = document.getElementById('ai-suggested-folder-breadcrumb');
    breadcrumbContainer.innerHTML = `
        <div class="space-y-3">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-green-600 dark:text-green-400 font-medium">File is correctly placed</span>
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400">
                Current location: <a href="/user/folders/${data.file.current_folder_id}" target="_blank" class="font-medium text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 underline">${data.file.current_folder}</a>
            </div>
        </div>
    `;
    
    // Update confidence area to show detailed analysis
    const confidenceContainer = document.getElementById('ai-confidence-container');
    if (confidenceContainer) {
        confidenceContainer.innerHTML = `
            <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-3 mt-2">
                <p class="text-xs font-medium text-green-800 dark:text-green-300 mb-2">Why this folder is correct:</p>
                <ul class="text-xs text-green-700 dark:text-green-400 space-y-1">
                    ${data.analysis.document_type ? `<li>• Document type: ${data.analysis.document_type}</li>` : ''}
                    ${data.analysis.document_date ? `<li>• Date matches folder period: ${data.analysis.document_date}</li>` : ''}
                    ${data.analysis.transaction_type ? `<li>• Transaction type: ${data.analysis.transaction_type}</li>` : ''}
                    ${data.analysis.company_involved ? `<li>• Company: ${data.analysis.company_involved}</li>` : ''}
                </ul>
            </div>
        `;
    }
    
    // Update reasoning
    document.getElementById('ai-reasoning').innerHTML = `
        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
            <p class="text-sm">${data.analysis.reasoning || 'This file is already organized correctly based on its content and metadata.'}</p>
        </div>
    `;
    
    // Show re-analyze and manual move buttons
    document.getElementById('ai-reanalyze-btn').classList.remove('hidden');
    document.getElementById('ai-manual-btn').classList.remove('hidden');
    
    // Setup re-analyze button
    document.getElementById('ai-reanalyze-btn').onclick = () => {
        showAISuggestionModal(currentFileId, true);
    };
    
    // Setup manual move button
    document.getElementById('ai-manual-btn').onclick = () => {
        showManualFolderSelection();
    };
    
    // Show alternatives if available
    if (data.analysis.alternative_folders && data.analysis.alternative_folders.length > 0) {
        showAlternativesWithActions(data.analysis.alternative_folders);
    }
}

function showAlternativesWithActions(alternatives) {
    document.getElementById('ai-alternatives-section').classList.remove('hidden');
    const altContainer = document.getElementById('ai-alternatives');
    altContainer.innerHTML = '';
    
    alternatives.forEach(alt => {
        const div = document.createElement('div');
        div.className = 'bg-gray-100 dark:bg-gray-600 rounded-lg p-3 flex justify-between items-center';
        
        let pathDisplay = alt.folder_name || alt.name || 'Unknown';
        if (alt.folder_path || alt.path) {
            const path = alt.folder_path || alt.path;
            if (path.includes('/')) {
                const parts = path.split('/').filter(p => p);
                pathDisplay = parts.join(' › ');
            }
        }
        
        const confidence = alt.confidence || 0;
        const reason = alt.reason || '';
        
        div.innerHTML = `
            <div class="flex-1">
                <a href="/user/folders/${alt.folder_id || alt.id}" target="_blank" class="font-medium text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 underline">${pathDisplay}</a>
                ${reason ? `<div class="text-xs text-gray-600 dark:text-gray-400 mt-1">${reason}</div>` : ''}
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Confidence: ${confidence}%</div>
            </div>
            <button onclick="acceptSuggestion(${alt.folder_id || alt.id})" 
                    class="ml-3 px-3 py-1 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700 transition-colors">
                Move Here
            </button>
        `;
        altContainer.appendChild(div);
    });
}

function showManualFolderSelection() {
    document.getElementById('ai-manual-section').classList.remove('hidden');
    const select = document.getElementById('manual-folder-select');
    
    // Populate folders if not already done
    if (select.options.length <= 1 && availableFolders.length > 0) {
        availableFolders.forEach(folder => {
            const option = document.createElement('option');
            option.value = folder.id;
            option.textContent = folder.path || folder.name;
            select.appendChild(option);
        });
    }
    
    // Add change event listener
    select.onchange = function() {
        if (this.value) {
            // Show confirmation
            if (confirm(`Move file to ${this.options[this.selectedIndex].text}?`)) {
                acceptSuggestion(this.value);
            }
        }
    };
    
    // Scroll to manual section
    document.getElementById('ai-manual-section').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
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