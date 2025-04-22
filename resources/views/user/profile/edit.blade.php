<x-app-layout>
    <x-unified-header />
    
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg overflow-hidden">
                    <!-- Tabs Navigation -->
                    <div class="border-b border-gray-200 dark:border-gray-700">
                        <ul class="flex flex-wrap text-sm font-medium text-center" id="profileTabs" role="tablist">
                            <li class="mr-2" role="presentation">
                                <button class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 dark:text-gray-400 border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 aria-selected:text-blue-600 dark:aria-selected:text-blue-400 aria-selected:border-blue-600 dark:aria-selected:border-blue-400" id="profile-tab" data-tabs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="true">
                                    {{ __('Personal Information') }}
                                </button>
                            </li>
                            <li class="mr-2" role="presentation">
                                <button class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 dark:text-gray-400 border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="password-tab" data-tabs-target="#password" type="button" role="tab" aria-controls="password" aria-selected="false">
                                    {{ __('Password') }}
                                </button>
                            </li>
                            @if(!auth()->user()->is_admin)
                            <li class="mr-2" role="presentation">
                                <button class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 dark:text-gray-400 border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="subscription-tab" data-tabs-target="#subscription" type="button" role="tab" aria-controls="subscription" aria-selected="false">
                                    {{ __('Subscription') }}
                                </button>
                            </li>
                            @endif
                        </ul>
                    </div>
                    
                    <!-- Tab Content -->
                    <div id="profileTabContent">
                        <!-- Profile Tab -->
                        <div class="p-6 rounded-lg bg-white dark:bg-gray-800" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            @include('user.profile.partials.update-profile-information-form')
                        </div>
                        
                        <!-- Update Password Tab -->
                        <div class="hidden p-6 rounded-lg bg-white dark:bg-gray-800" id="password" role="tabpanel" aria-labelledby="password-tab">
                            @include('user.profile.partials.update-password-form')
                        </div>
                        
                        <!-- Subscription Tab -->
                        @if(!auth()->user()->is_admin)
                        <div class="hidden p-6 rounded-lg bg-white dark:bg-gray-800" id="subscription" role="tabpanel" aria-labelledby="subscription-tab">
                            @include('user.profile.partials.manage-subscription-form')
                        </div>
                        @endif
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('user.profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tab Functionality Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('[role="tab"]');
            const tabContents = document.querySelectorAll('[role="tabpanel"]');
            
            // Function to activate a specific tab
            function activateTab(tabId) {
                // Deactivate all tabs
                tabs.forEach(t => {
                    t.classList.remove('text-blue-600', 'dark:text-blue-400', 'border-blue-600', 'dark:border-blue-400');
                    t.classList.add('text-gray-500', 'dark:text-gray-400', 'border-transparent');
                    t.setAttribute('aria-selected', 'false');
                });
                
                // Hide all tab contents
                tabContents.forEach(content => {
                    content.classList.add('hidden');
                });
                
                // Activate the selected tab
                const selectedTab = document.querySelector(`[data-tabs-target="#${tabId}"]`);
                if (selectedTab) {
                    selectedTab.classList.remove('text-gray-500', 'dark:text-gray-400', 'border-transparent');
                    selectedTab.classList.add('text-blue-600', 'dark:text-blue-400', 'border-blue-600', 'dark:border-blue-400');
                    selectedTab.setAttribute('aria-selected', 'true');
                    
                    // Show the selected tab content
                    const selectedContent = document.getElementById(tabId);
                    if (selectedContent) {
                        selectedContent.classList.remove('hidden');
                    }
                }
            }
            
            // Check for tab parameter in URL
            const urlParams = new URLSearchParams(window.location.search);
            const tabParam = urlParams.get('tab');
            if (tabParam) {
                activateTab(tabParam);
            } else {
                // Default to first tab if no parameter
                activateTab('profile');
            }
            
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Deactivate all tabs
                    tabs.forEach(t => {
                        t.classList.remove('text-blue-600', 'dark:text-blue-400', 'border-blue-600', 'dark:border-blue-400');
                        t.classList.add('text-gray-500', 'dark:text-gray-400', 'border-transparent');
                        t.setAttribute('aria-selected', 'false');
                    });
                    
                    // Hide all tab contents
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });
                    
                    // Activate current tab
                    this.classList.remove('text-gray-500', 'dark:text-gray-400', 'border-transparent');
                    this.classList.add('text-blue-600', 'dark:text-blue-400', 'border-blue-600', 'dark:border-blue-400');
                    this.setAttribute('aria-selected', 'true');
                    
                    // Show current tab content
                    const targetId = this.getAttribute('data-tabs-target').replace('#', '');
                    document.getElementById(targetId).classList.remove('hidden');
                });
            });
            
            // Initialize by clicking the first tab
            document.getElementById('profile-tab').click();
        });
    </script>
</x-app-layout>
