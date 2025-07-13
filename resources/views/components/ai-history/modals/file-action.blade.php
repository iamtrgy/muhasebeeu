<!-- Unified File Action Modal -->
<div id="file-action-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeFileAction()"></div>
        
        <!-- Modal Content -->
        <div class="relative bg-white dark:bg-gray-900 rounded-2xl shadow-2xl max-w-md w-full overflow-hidden">
            <!-- Header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-3">
                    <div id="file-action-icon" class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                        <!-- Icon will be set by JavaScript -->
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="file-action-title">File Action</h3>
                </div>
                <button onclick="closeFileAction()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Content -->
            <div class="p-6">
                <!-- File Info -->
                <div class="mb-4">
                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100" id="file-action-name">File Name</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400" id="file-action-location">Current location</p>
                </div>
                
                <!-- Dynamic Content Area -->
                <div id="file-action-content">
                    <!-- Content will be populated by JavaScript -->
                </div>
            </div>
            
            <!-- Actions -->
            <div class="bg-gray-50 dark:bg-gray-800 px-6 py-4 flex justify-end space-x-3" id="file-action-buttons">
                <!-- Buttons will be populated by JavaScript -->
            </div>
        </div>
    </div>
</div>