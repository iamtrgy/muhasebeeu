<x-admin-layout>
    <x-slot name="header">
        <x-admin.page-title title="{{ __('Admin Dashboard') }}"></x-admin.page-title>
    </x-slot>

    <!-- Include the file preview modal component -->
    <x-folder.file-preview-modal />

    <div class="py-6">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Users Card -->
            <x-cards.stat-card
                title="Total Users"
                value="{{ $stats['total_users'] }}"
                color="blue"
            >
                <x-slot name="icon">
                    <svg class="h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </x-slot>
            </x-cards.stat-card>

            <!-- Companies Card -->
            <x-cards.stat-card
                title="Total Companies"
                value="{{ $stats['total_companies'] }}"
                color="emerald"
            >
                <x-slot name="icon">
                    <svg class="h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </x-slot>
            </x-cards.stat-card>

            <!-- Folders Card -->
            <x-cards.stat-card
                title="Total Folders"
                value="{{ $stats['total_folders'] }}"
                color="purple"
            >
                <x-slot name="icon">
                    <svg class="h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                    </svg>
                </x-slot>
            </x-cards.stat-card>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Recent Users -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Recent Users</h3>
                    <div class="space-y-4">
                        @foreach($stats['recent_users'] as $user)
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                                </div>
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $user->created_at->diffForHumans() }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Recent Companies -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Recent Companies</h3>
                    <div class="space-y-4">
                        @foreach($stats['recent_companies'] as $company)
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $company->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $company->user->name }}</p>
                                </div>
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $company->created_at->diffForHumans() }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Files -->
        <x-cards.files-table-card 
            title="Recent Files" 
            :files="$stats['recent_files']" 
        />
    </div>
</x-admin-layout> 