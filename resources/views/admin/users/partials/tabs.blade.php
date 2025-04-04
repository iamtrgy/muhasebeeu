<!-- Tabs -->
<div x-data="{ activeTab: '{{ request()->query('tab', 'details') }}' }">
    <!-- Tab Navigation -->
    <div class="border-b border-gray-200 dark:border-gray-700">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button
                @click="activeTab = 'details'; $dispatch('tab-changed', { tab: 'details' })"
                :class="activeTab === 'details' ? 'border-blue-500 text-blue-600 dark:text-blue-400 dark:border-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
            >
                {{ __('Account Details') }}
            </button>
            
            <button
                @click="activeTab = 'companies'; $dispatch('tab-changed', { tab: 'companies' })"
                :class="activeTab === 'companies' ? 'border-blue-500 text-blue-600 dark:text-blue-400 dark:border-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
            >
                {{ __('Companies') }}
            </button>
            
            <button
                @click="activeTab = 'documents'; $dispatch('tab-changed', { tab: 'documents' })"
                :class="activeTab === 'documents' ? 'border-blue-500 text-blue-600 dark:text-blue-400 dark:border-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
            >
                {{ __('Documents') }}
            </button>
            
            <button
                @click="activeTab = 'activity'; $dispatch('tab-changed', { tab: 'activity' })"
                :class="activeTab === 'activity' ? 'border-blue-500 text-blue-600 dark:text-blue-400 dark:border-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
            >
                {{ __('Activity') }}
            </button>
        </nav>
    </div>
    
    <div class="mt-6">
        <!-- Tab Content -->
        <div x-show="activeTab === 'details'">
            @include('admin.users.partials.tab-details')
        </div>
        
        <div x-show="activeTab === 'companies'">
            @include('admin.users.partials.tab-companies')
        </div>
        
        <div x-show="activeTab === 'documents'">
            @include('admin.users.partials.tab-documents')
        </div>
        
        <div x-show="activeTab === 'activity'">
            @include('admin.users.partials.tab-activity')
        </div>
    </div>
</div>
