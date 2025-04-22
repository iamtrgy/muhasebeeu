<x-admin-layout>
    <x-slot name="header">
        <x-admin.page-title title="{{ __('Admin Settings') }}"></x-admin.page-title>
    </x-slot>
    
    <div class="py-8">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg overflow-hidden">
                <!-- Tabs Navigation -->
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <ul class="flex flex-wrap text-sm font-medium text-center" id="profileTabs" role="tablist">
                        <li class="mr-2" role="presentation">
                            <button class="inline-block p-4 border-b-2 border-blue-600 text-blue-600 rounded-t-lg" id="profile-tab" data-tabs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="true">
                                {{ __('Personal Information') }}
                            </button>
                        </li>
                        <li class="mr-2" role="presentation">
                            <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="password-tab" data-tabs-target="#password" type="button" role="tab" aria-controls="password" aria-selected="false">
                                {{ __('Password') }}
                            </button>
                        </li>
                    </ul>
                </div>
                
                <!-- Tab Content -->
                <div id="profileTabContent">
                    <!-- Profile Tab -->
                    <div class="p-6 rounded-lg bg-white dark:bg-gray-800" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        @include('admin.profile.partials.update-profile-information-form')
                    </div>
                    
                    <!-- Update Password Tab -->
                    <div class="hidden p-6 rounded-lg bg-white dark:bg-gray-800" id="password" role="tabpanel" aria-labelledby="password-tab">
                        @include('admin.profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('admin.profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tab Functionality Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('[role="tab"]');
            const tabContents = document.querySelectorAll('[role="tabpanel"]');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Deactivate all tabs
                    tabs.forEach(t => {
                        t.classList.remove('border-blue-600', 'text-blue-600');
                        t.classList.add('border-transparent');
                        t.setAttribute('aria-selected', 'false');
                    });
                    
                    // Hide all tab contents
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });
                    
                    // Activate current tab
                    this.classList.add('border-blue-600', 'text-blue-600');
                    this.classList.remove('border-transparent');
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
</x-admin-layout>
