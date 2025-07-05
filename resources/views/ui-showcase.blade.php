<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('UI Component Showcase') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- Button Components --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Button Components</h3>
                    
                    {{-- Button Variants --}}
                    <div class="space-y-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Button Variants</h4>
                            <div class="flex flex-wrap gap-3">
                                <x-ui.button.primary>Primary Button</x-ui.button.primary>
                                <x-ui.button.secondary>Secondary Button</x-ui.button.secondary>
                                <x-ui.button.danger>Danger Button</x-ui.button.danger>
                                <x-ui.button.base variant="success">Success Button</x-ui.button.base>
                                <x-ui.button.base variant="warning">Warning Button</x-ui.button.base>
                            </div>
                        </div>

                        {{-- Button Sizes --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Button Sizes</h4>
                            <div class="flex flex-wrap items-center gap-3">
                                <x-ui.button.primary size="sm">Small</x-ui.button.primary>
                                <x-ui.button.primary size="md">Medium</x-ui.button.primary>
                                <x-ui.button.primary size="lg">Large</x-ui.button.primary>
                            </div>
                        </div>

                        {{-- Button States --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Button States</h4>
                            <div class="flex flex-wrap gap-3">
                                <x-ui.button.primary>Normal</x-ui.button.primary>
                                <x-ui.button.primary disabled>Disabled</x-ui.button.primary>
                            </div>
                        </div>

                        {{-- Full Width Button --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Full Width Button</h4>
                            <x-ui.button.primary fullWidth>Full Width Button</x-ui.button.primary>
                        </div>

                        {{-- Buttons with Icons --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Buttons with Icons</h4>
                            <div class="flex flex-wrap gap-3">
                                <x-ui.button.primary>
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Add Item
                                </x-ui.button.primary>
                                
                                <x-ui.button.secondary>
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Download
                                </x-ui.button.secondary>
                                
                                <x-ui.button.danger size="sm">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Delete
                                </x-ui.button.danger>
                            </div>
                        </div>

                        {{-- Legacy Components Test --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Legacy Components (Should look identical)</h4>
                            <div class="flex flex-wrap gap-3">
                                <x-primary-button>Old Primary</x-primary-button>
                                <x-secondary-button>Old Secondary</x-secondary-button>
                                <x-danger-button>Old Danger</x-danger-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card Components --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Card Components</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Basic Card --}}
                        <x-ui.card.base>
                            <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-2">Basic Card</h4>
                            <p class="text-gray-600 dark:text-gray-400">This is a basic card with default padding and shadow.</p>
                        </x-ui.card.base>
                        
                        {{-- Card with Header --}}
                        <x-ui.card.base :padding="false">
                            <div class="p-6">
                                <x-ui.card.header title="Card with Header" description="This card has a header section" />
                                <x-ui.card.body>
                                    <p class="text-gray-600 dark:text-gray-400">Card body content goes here.</p>
                                </x-ui.card.body>
                            </div>
                        </x-ui.card.base>
                        
                        {{-- Card with Custom Shadow --}}
                        <x-ui.card.base shadow="lg">
                            <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-2">Large Shadow Card</h4>
                            <p class="text-gray-600 dark:text-gray-400">This card has a larger shadow for more emphasis.</p>
                        </x-ui.card.base>
                        
                        {{-- Card with No Rounded Corners --}}
                        <x-ui.card.base rounded="none">
                            <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-2">Sharp Corners Card</h4>
                            <p class="text-gray-600 dark:text-gray-400">This card has no rounded corners.</p>
                        </x-ui.card.base>
                    </div>
                    
                    {{-- Dashboard Stat Cards --}}
                    <div class="mt-8">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Dashboard Stat Cards</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            {{-- Users Stat Card --}}
                            <x-ui.card.base class="hover:shadow-lg transition-shadow">
                                <x-ui.card.body class="p-6">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 bg-indigo-500 rounded-lg p-3">
                                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </div>
                                        <div class="ml-5 flex-1">
                                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                Total Users
                                            </div>
                                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                                1,234
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                        <x-ui.button.primary size="sm" class="w-full">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            Manage Users
                                        </x-ui.button.primary>
                                    </div>
                                </x-ui.card.body>
                            </x-ui.card.base>
                            
                            {{-- Companies Stat Card --}}
                            <x-ui.card.base class="hover:shadow-lg transition-shadow">
                                <x-ui.card.body class="p-6">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 bg-emerald-500 rounded-lg p-3">
                                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                        </div>
                                        <div class="ml-5 flex-1">
                                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                Total Companies
                                            </div>
                                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                                567
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                        <x-ui.button.secondary size="sm" class="w-full">
                                            View All
                                        </x-ui.button.secondary>
                                    </div>
                                </x-ui.card.body>
                            </x-ui.card.base>
                            
                            {{-- Files Stat Card --}}
                            <x-ui.card.base class="hover:shadow-lg transition-shadow">
                                <x-ui.card.body class="p-6">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 bg-amber-500 rounded-lg p-3">
                                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                            </svg>
                                        </div>
                                        <div class="ml-5 flex-1">
                                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                Total Files
                                            </div>
                                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                                8,901
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                        <x-ui.button.primary size="sm" class="w-full">
                                            File Manager
                                        </x-ui.button.primary>
                                    </div>
                                </x-ui.card.body>
                            </x-ui.card.base>
                        </div>
                        
                        <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                            These stat cards follow the pattern used in the admin dashboard: icon + stats + action button.
                        </p>
                        
                        {{-- Business & Subscription Stat Cards --}}
                        <div class="mt-8">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Subscription Analytics Cards</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                {{-- Active Subscriptions --}}
                                <x-ui.card.base class="hover:shadow-lg transition-shadow">
                                    <x-ui.card.body class="p-6">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 bg-emerald-500 rounded-lg p-3">
                                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <div class="ml-5 flex-1">
                                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                    Active Subscriptions
                                                </div>
                                                <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                                    87
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    73.1% conversion rate
                                                </div>
                                            </div>
                                        </div>
                                    </x-ui.card.body>
                                </x-ui.card.base>

                                {{-- Monthly Revenue --}}
                                <x-ui.card.base class="hover:shadow-lg transition-shadow">
                                    <x-ui.card.body class="p-6">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 bg-indigo-500 rounded-lg p-3">
                                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                                </svg>
                                            </div>
                                            <div class="ml-5 flex-1">
                                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                    Monthly Revenue
                                                </div>
                                                <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                                    $2,607.13
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    $29.97 avg per user
                                                </div>
                                            </div>
                                        </div>
                                    </x-ui.card.body>
                                </x-ui.card.base>

                                {{-- Trial Users --}}
                                <x-ui.card.base class="hover:shadow-lg transition-shadow">
                                    <x-ui.card.body class="p-6">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 bg-amber-500 rounded-lg p-3">
                                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <div class="ml-5 flex-1">
                                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                    Trial Users
                                                </div>
                                                <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                                    23
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    4 expiring soon
                                                </div>
                                            </div>
                                        </div>
                                    </x-ui.card.body>
                                </x-ui.card.base>

                                {{-- Total Platform Stats --}}
                                <x-ui.card.base class="hover:shadow-lg transition-shadow">
                                    <x-ui.card.body class="p-6">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 bg-gray-500 rounded-lg p-3">
                                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                            </div>
                                            <div class="ml-5 flex-1">
                                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                    Total Users
                                                </div>
                                                <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                                    119
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    45 companies, 2.1k files
                                                </div>
                                            </div>
                                        </div>
                                    </x-ui.card.body>
                                </x-ui.card.base>
                            </div>
                            
                            <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                                Enhanced stat cards with subscription metrics for SaaS admin dashboards. Features sub-metrics and business KPIs.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Component --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6" x-data>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Modal Component</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Event-based Modal --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Event-based Modal</h4>
                            <x-ui.button.primary x-on:click="$dispatch('open-modal', 'example-modal')">
                                Open Event Modal
                            </x-ui.button.primary>
                        </div>
                        
                        {{-- Function-based Modal --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Function-based Modal</h4>
                            <x-ui.button.secondary onclick="openModal('function-modal')">
                                Open Function Modal
                            </x-ui.button.secondary>
                        </div>
                        
                        {{-- Modal with Close Button --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Modal with Close Button</h4>
                            <x-ui.button.base variant="success" x-on:click="$dispatch('open-modal', 'closeable-modal')">
                                Open with Close Button
                            </x-ui.button.base>
                        </div>
                        
                        {{-- Small Modal --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Small Modal</h4>
                            <x-ui.button.base variant="warning" x-on:click="$dispatch('open-modal', 'small-modal')">
                                Open Small Modal
                            </x-ui.button.base>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Modal Definitions --}}
            <x-ui.modal.base name="example-modal" maxWidth="lg">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Event-based Modal</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        This modal is opened using Alpine.js events. It's the recommended approach for most use cases.
                    </p>
                    <div class="flex justify-end gap-3">
                        <x-ui.button.secondary x-on:click="$dispatch('close-modal', 'example-modal')">
                            Cancel
                        </x-ui.button.secondary>
                        <x-ui.button.primary x-on:click="$dispatch('close-modal', 'example-modal')">
                            Confirm
                        </x-ui.button.primary>
                    </div>
                </div>
            </x-ui.modal.base>
            
            <x-ui.modal.base id="function-modal" maxWidth="xl">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Function-based Modal</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        This modal is opened using the global openModal() function. Useful for non-Alpine contexts.
                    </p>
                    <div class="flex justify-end">
                        <x-ui.button.primary onclick="closeModal('function-modal')">
                            Close
                        </x-ui.button.primary>
                    </div>
                </div>
            </x-ui.modal.base>
            
            <x-ui.modal.base name="closeable-modal" :closeButton="true">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Modal with Close Button</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        This modal has a close button in the top-right corner. You can also close it by clicking the backdrop or pressing ESC.
                    </p>
                </div>
            </x-ui.modal.base>
            
            <x-ui.modal.base name="small-modal" maxWidth="sm">
                <div class="text-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Small Modal</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        This is a small modal, perfect for confirmations.
                    </p>
                    <x-ui.button.danger size="sm" x-on:click="$dispatch('close-modal', 'small-modal')">
                        Got it!
                    </x-ui.button.danger>
                </div>
            </x-ui.modal.base>

            {{-- Table Components --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Table Components</h3>
                    
                    {{-- Basic Table --}}
                    <div class="mb-8">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Basic Table</h4>
                        <x-ui.table.base>
                            <x-ui.table.header>
                                <x-ui.table.row>
                                    <x-ui.table.head-cell>Name</x-ui.table.head-cell>
                                    <x-ui.table.head-cell>Email</x-ui.table.head-cell>
                                    <x-ui.table.head-cell>Role</x-ui.table.head-cell>
                                    <x-ui.table.head-cell align="right">Actions</x-ui.table.head-cell>
                                </x-ui.table.row>
                            </x-ui.table.header>
                            <x-ui.table.body>
                                <x-ui.table.row>
                                    <x-ui.table.cell>John Doe</x-ui.table.cell>
                                    <x-ui.table.cell>john@example.com</x-ui.table.cell>
                                    <x-ui.table.cell>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Admin
                                        </span>
                                    </x-ui.table.cell>
                                    <x-ui.table.action-cell>
                                        <x-ui.button.base variant="secondary" size="sm">Edit</x-ui.button.base>
                                        <x-ui.button.base variant="danger" size="sm">Delete</x-ui.button.base>
                                    </x-ui.table.action-cell>
                                </x-ui.table.row>
                                <x-ui.table.row>
                                    <x-ui.table.cell>Jane Smith</x-ui.table.cell>
                                    <x-ui.table.cell>jane@example.com</x-ui.table.cell>
                                    <x-ui.table.cell>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            User
                                        </span>
                                    </x-ui.table.cell>
                                    <x-ui.table.action-cell>
                                        <x-ui.button.base variant="secondary" size="sm">Edit</x-ui.button.base>
                                        <x-ui.button.base variant="danger" size="sm">Delete</x-ui.button.base>
                                    </x-ui.table.action-cell>
                                </x-ui.table.row>
                            </x-ui.table.body>
                        </x-ui.table.base>
                    </div>
                    
                    {{-- Sortable Table --}}
                    <div class="mb-8">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Sortable Table</h4>
                        <x-ui.table.base>
                            <x-ui.table.header>
                                <x-ui.table.row>
                                    <x-ui.table.head-cell sortable sortKey="name" currentSort="name" currentDirection="asc">
                                        Name
                                    </x-ui.table.head-cell>
                                    <x-ui.table.head-cell sortable sortKey="date">
                                        Date
                                    </x-ui.table.head-cell>
                                    <x-ui.table.head-cell sortable sortKey="amount" align="right">
                                        Amount
                                    </x-ui.table.head-cell>
                                </x-ui.table.row>
                            </x-ui.table.header>
                            <x-ui.table.body>
                                <x-ui.table.row>
                                    <x-ui.table.cell>Invoice #001</x-ui.table.cell>
                                    <x-ui.table.cell>2024-01-15</x-ui.table.cell>
                                    <x-ui.table.cell align="right">$1,250.00</x-ui.table.cell>
                                </x-ui.table.row>
                                <x-ui.table.row>
                                    <x-ui.table.cell>Invoice #002</x-ui.table.cell>
                                    <x-ui.table.cell>2024-01-20</x-ui.table.cell>
                                    <x-ui.table.cell align="right">$850.00</x-ui.table.cell>
                                </x-ui.table.row>
                            </x-ui.table.body>
                        </x-ui.table.base>
                    </div>
                    
                    {{-- Empty State Table --}}
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Empty State</h4>
                        <x-ui.table.base>
                            <x-ui.table.header>
                                <x-ui.table.row>
                                    <x-ui.table.head-cell>Name</x-ui.table.head-cell>
                                    <x-ui.table.head-cell>Email</x-ui.table.head-cell>
                                    <x-ui.table.head-cell>Status</x-ui.table.head-cell>
                                </x-ui.table.row>
                            </x-ui.table.header>
                            <x-ui.table.body>
                                <x-ui.table.empty-state colspan="3" message="No users found">
                                    <x-ui.button.primary size="sm">
                                        Add User
                                    </x-ui.button.primary>
                                </x-ui.table.empty-state>
                            </x-ui.table.body>
                        </x-ui.table.base>
                    </div>
                </div>
            </div>
            
            {{-- Alert Components --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Alert Components</h3>
                    
                    <div class="space-y-4">
                        <x-ui.alert type="info" title="Information">
                            This is an informational alert with a title.
                        </x-ui.alert>
                        
                        <x-ui.alert type="success">
                            Success! Your changes have been saved.
                        </x-ui.alert>
                        
                        <x-ui.alert type="warning" dismissible>
                            Warning: This action cannot be undone. Click the X to dismiss this alert.
                        </x-ui.alert>
                        
                        <x-ui.alert type="error" title="Error occurred" dismissible>
                            There was an error processing your request. Please try again later.
                        </x-ui.alert>
                        
                        <x-ui.alert type="info" :icon="false">
                            This is an alert without an icon.
                        </x-ui.alert>
                    </div>
                </div>
            </div>
            
            {{-- Dropdown Components --}}
            <div class="bg-white dark:bg-gray-800 overflow-visible shadow-sm sm:rounded-lg relative z-10">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Dropdown Components</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-32">
                        {{-- Basic Dropdown --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Basic Dropdown</h4>
                            <x-ui.dropdown.base>
                                <x-slot name="trigger">
                                    <x-ui.button.secondary x-on:click="toggle()">
                                        Options
                                        <svg class="ml-2 -mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </x-ui.button.secondary>
                                </x-slot>
                                
                                <x-slot name="content">
                                    <div class="py-1">
                                        <x-ui.dropdown.item href="#">Profile</x-ui.dropdown.item>
                                        <x-ui.dropdown.item href="#">Settings</x-ui.dropdown.item>
                                        <x-ui.dropdown.item href="#">Billing</x-ui.dropdown.item>
                                        <x-ui.dropdown.divider />
                                        <x-ui.dropdown.item href="#">Sign out</x-ui.dropdown.item>
                                    </div>
                                </x-slot>
                            </x-ui.dropdown.base>
                        </div>
                        
                        {{-- Dropdown with Headers --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">With Headers</h4>
                            <x-ui.dropdown.base>
                                <x-slot name="trigger">
                                    <x-ui.button.primary x-on:click="toggle()">
                                        User Menu
                                        <svg class="ml-2 -mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </x-ui.button.primary>
                                </x-slot>
                                
                                <x-slot name="content">
                                    <x-ui.dropdown.header text="Manage Account" />
                                    <div class="py-1">
                                        <x-ui.dropdown.item href="#">
                                            <x-slot name="icon">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </x-slot>
                                            Profile
                                        </x-ui.dropdown.item>
                                        <x-ui.dropdown.item href="#">
                                            <x-slot name="icon">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </x-slot>
                                            Settings
                                        </x-ui.dropdown.item>
                                    </div>
                                    <x-ui.dropdown.divider />
                                    <x-ui.dropdown.header text="Danger Zone" />
                                    <div class="py-1">
                                        <x-ui.dropdown.item type="button" class="text-red-600 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20">
                                            Delete Account
                                        </x-ui.dropdown.item>
                                    </div>
                                </x-slot>
                            </x-ui.dropdown.base>
                        </div>
                        
                        {{-- Dropdown with States --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">With States</h4>
                            <x-ui.dropdown.base align="left">
                                <x-slot name="trigger">
                                    <x-ui.button.base variant="secondary" x-on:click="toggle()">
                                        Actions
                                        <svg class="ml-2 -mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </x-ui.button.base>
                                </x-slot>
                                
                                <x-slot name="content">
                                    <div class="py-1">
                                        <x-ui.dropdown.item href="#" active>Active Item</x-ui.dropdown.item>
                                        <x-ui.dropdown.item href="#">Normal Item</x-ui.dropdown.item>
                                        <x-ui.dropdown.item disabled>Disabled Item</x-ui.dropdown.item>
                                        <x-ui.dropdown.divider />
                                        <x-ui.dropdown.item type="submit">Submit Form</x-ui.dropdown.item>
                                    </div>
                                </x-slot>
                            </x-ui.dropdown.base>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Dropdown Actions (Table Row Actions) --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Dropdown Actions (Table Row Actions)</h3>
                    
                    <div class="space-y-6">
                        {{-- Basic Table Action Dropdown --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Basic Table Action Dropdown</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                Use this pattern for table row actions. The trigger is styled as an icon button that fits well in table cells.
                            </p>
                            
                            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                                <x-ui.table.base>
                                    <x-slot name="head">
                                        <x-ui.table.head-cell>Name</x-ui.table.head-cell>
                                        <x-ui.table.head-cell>Email</x-ui.table.head-cell>
                                        <x-ui.table.head-cell align="right">Actions</x-ui.table.head-cell>
                                    </x-slot>
                                    <x-slot name="body">
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                            <x-ui.table.cell>John Doe</x-ui.table.cell>
                                            <x-ui.table.cell>john@example.com</x-ui.table.cell>
                                            <x-ui.table.action-cell>
                                                <x-ui.dropdown.base align="right">
                                                    <x-slot name="trigger">
                                                        <button class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                                            </svg>
                                                        </button>
                                                    </x-slot>
                                                    
                                                    <x-ui.dropdown.item href="#">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        View Details
                                                    </x-ui.dropdown.item>
                                                    
                                                    <x-ui.dropdown.item href="#">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                        Edit
                                                    </x-ui.dropdown.item>
                                                    
                                                    <x-ui.dropdown.divider />
                                                    
                                                    <form action="#" method="POST" onsubmit="return confirm('Are you sure?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <x-ui.dropdown.item tag="button" type="submit">
                                                            <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                            <span class="text-red-600">Delete</span>
                                                        </x-ui.dropdown.item>
                                                    </form>
                                                </x-ui.dropdown.base>
                                            </x-ui.table.action-cell>
                                        </tr>
                                    </x-slot>
                                </x-ui.table.base>
                            </div>
                        </div>

                        {{-- Complete Action Pattern with Quick Actions --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Complete Action Pattern with Quick Actions</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                Combines quick action buttons for common operations with a dropdown for secondary actions.
                            </p>
                            
                            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                                <x-ui.table.base>
                                    <x-slot name="head">
                                        <x-ui.table.head-cell>User</x-ui.table.head-cell>
                                        <x-ui.table.head-cell>Status</x-ui.table.head-cell>
                                        <x-ui.table.head-cell align="right">Actions</x-ui.table.head-cell>
                                    </x-slot>
                                    <x-slot name="body">
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                            <x-ui.table.cell>
                                                <div class="flex items-center">
                                                    <x-ui.avatar name="Jane Smith" size="sm" />
                                                    <div class="ml-3">
                                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">Jane Smith</div>
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">jane@example.com</div>
                                                    </div>
                                                </div>
                                            </x-ui.table.cell>
                                            <x-ui.table.cell>
                                                <x-ui.badge variant="success" size="sm">Active</x-ui.badge>
                                            </x-ui.table.cell>
                                            <x-ui.table.action-cell>
                                                <div class="flex items-center justify-end gap-2">
                                                    {{-- Quick action buttons --}}
                                                    <a href="#" 
                                                       class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                                       title="View details">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </a>
                                                    <a href="#" 
                                                       class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                                       title="Edit">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>
                                                    
                                                    {{-- Dropdown for secondary actions --}}
                                                    <x-ui.dropdown.base align="right">
                                                        <x-slot name="trigger">
                                                            <button class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                                                </svg>
                                                            </button>
                                                        </x-slot>
                                                        
                                                        <x-ui.dropdown.item href="#">
                                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                            </svg>
                                                            Manage Subscription
                                                        </x-ui.dropdown.item>
                                                        
                                                        <x-ui.dropdown.item href="#">
                                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                            </svg>
                                                            Change Role
                                                        </x-ui.dropdown.item>
                                                        
                                                        <x-ui.dropdown.divider />
                                                        
                                                        <form action="#" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <x-ui.dropdown.item tag="button" type="submit">
                                                                <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                                <span class="text-red-600">Delete User</span>
                                                            </x-ui.dropdown.item>
                                                        </form>
                                                    </x-ui.dropdown.base>
                                                </div>
                                            </x-ui.table.action-cell>
                                        </tr>
                                    </x-slot>
                                </x-ui.table.base>
                            </div>
                        </div>

                        {{-- Code Example --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Implementation Guide</h4>
                            <div class="bg-gray-900 dark:bg-gray-950 rounded-lg p-4 overflow-x-auto">
                                <pre class="text-sm text-gray-100"><code>{{-- Inside table row --}}
&lt;x-ui.table.action-cell&gt;
    &lt;div class="flex items-center justify-end gap-2"&gt;
        {{-- Quick actions (optional) --}}
        &lt;a href="@{{ route('resource.show', $item) }}" 
           class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
           title="View details"&gt;
            {{-- View icon --}}
        &lt;/a&gt;
        
        {{-- Dropdown menu --}}
        &lt;x-ui.dropdown.base align="right"&gt;
            &lt;x-slot name="trigger"&gt;
                &lt;button class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"&gt;
                    {{-- Three dots icon --}}
                &lt;/button&gt;
            &lt;/x-slot&gt;
            
            {{-- Regular actions --}}
            &lt;x-ui.dropdown.item href="@{{ route('resource.edit', $item) }}"&gt;
                {{-- Icon and text --}}
            &lt;/x-ui.dropdown.item&gt;
            
            &lt;x-ui.dropdown.divider /&gt;
            
            {{-- Destructive action with form --}}
            &lt;form action="@{{ route('resource.destroy', $item) }}" method="POST" 
                  onsubmit="return confirm('Are you sure?');"&gt;
                @@csrf
                @@method('DELETE')
                &lt;x-ui.dropdown.item tag="button" type="submit"&gt;
                    &lt;span class="text-red-600"&gt;Delete&lt;/span&gt;
                &lt;/x-ui.dropdown.item&gt;
            &lt;/form&gt;
        &lt;/x-ui.dropdown.base&gt;
    &lt;/div&gt;
&lt;/x-ui.table.action-cell&gt;</code></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Badge Components --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Badge Components</h3>
                    
                    <div class="space-y-6">
                        {{-- Badge Variants --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Badge Variants</h4>
                            <div class="flex flex-wrap gap-2">
                                <x-ui.badge>Default</x-ui.badge>
                                <x-ui.badge variant="primary">Primary</x-ui.badge>
                                <x-ui.badge variant="secondary">Secondary</x-ui.badge>
                                <x-ui.badge variant="success">Success</x-ui.badge>
                                <x-ui.badge variant="warning">Warning</x-ui.badge>
                                <x-ui.badge variant="danger">Danger</x-ui.badge>
                            </div>
                        </div>
                        
                        {{-- Badge Sizes --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Badge Sizes</h4>
                            <div class="flex flex-wrap items-center gap-2">
                                <x-ui.badge size="sm" variant="primary">Small</x-ui.badge>
                                <x-ui.badge size="md" variant="primary">Medium</x-ui.badge>
                                <x-ui.badge size="lg" variant="primary">Large</x-ui.badge>
                            </div>
                        </div>
                        
                        {{-- Badge with Dot --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Badge with Dot</h4>
                            <div class="flex flex-wrap gap-2">
                                <x-ui.badge variant="success" dot>Active</x-ui.badge>
                                <x-ui.badge variant="danger" dot>Inactive</x-ui.badge>
                                <x-ui.badge variant="warning" dot>Pending</x-ui.badge>
                            </div>
                        </div>
                        
                        {{-- Rounded Badges --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Rounded Variations</h4>
                            <div class="flex flex-wrap gap-2">
                                <x-ui.badge variant="primary" rounded="sm">Small Rounded</x-ui.badge>
                                <x-ui.badge variant="primary" rounded="md">Medium Rounded</x-ui.badge>
                                <x-ui.badge variant="primary" rounded="lg">Large Rounded</x-ui.badge>
                                <x-ui.badge variant="primary" rounded="full">Full Rounded</x-ui.badge>
                            </div>
                        </div>
                        
                        {{-- Removable Badges --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Removable Badges</h4>
                            <div class="flex flex-wrap gap-2">
                                <x-ui.badge variant="primary" removable>Tag 1</x-ui.badge>
                                <x-ui.badge variant="secondary" removable>Tag 2</x-ui.badge>
                                <x-ui.badge variant="success" removable>Tag 3</x-ui.badge>
                                <x-ui.badge variant="warning" removable dot>Important</x-ui.badge>
                            </div>
                        </div>
                        
                        {{-- Usage in Context --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Usage in Context</h4>
                            <div class="space-y-3">
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-700 dark:text-gray-300">User Status:</span>
                                    <x-ui.badge variant="success" dot size="sm">Online</x-ui.badge>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-700 dark:text-gray-300">Order #1234:</span>
                                    <x-ui.badge variant="warning">Processing</x-ui.badge>
                                    <x-ui.badge variant="primary">Priority</x-ui.badge>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-700 dark:text-gray-300">Product:</span>
                                    <x-ui.badge variant="danger" size="sm">Out of Stock</x-ui.badge>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Pagination Components --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Pagination Components</h3>
                    
                    <div class="space-y-8">
                        {{-- Note about pagination --}}
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-md p-4">
                            <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                Note: These are static examples. In real usage, pass a Laravel paginator instance to the components.
                            </p>
                        </div>
                        
                        {{-- Example usage --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Usage Example</h4>
                            <pre class="bg-gray-100 dark:bg-gray-900 rounded-md p-4 text-sm overflow-x-auto">
<code class="text-gray-800 dark:text-gray-200">{{-- In your controller --}}
$users = User::paginate(15);

{{-- In your blade view --}}
&lt;x-ui.pagination.base :paginator="$users" /&gt;

{{-- Simple pagination --}}
&lt;x-ui.pagination.simple :paginator="$users" /&gt;

{{-- Without info text --}}
&lt;x-ui.pagination.base :paginator="$users" :show-info="false" /&gt;</code></pre>
                        </div>
                        
                        {{-- Visual examples (static) --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Visual Examples (Static)</h4>
                            
                            {{-- Full pagination --}}
                            <div class="mb-6">
                                <h5 class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">Full Pagination with Info</h5>
                                <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
                                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                        <div>
                                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                                Showing
                                                <span class="font-medium">1</span>
                                                to
                                                <span class="font-medium">10</span>
                                                of
                                                <span class="font-medium">97</span>
                                                results
                                            </p>
                                        </div>
                                        <div>
                                            <span class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                                <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-400 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 cursor-not-allowed rounded-l-md">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                                <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-white bg-indigo-600 dark:bg-indigo-500 border border-indigo-600 dark:border-indigo-500 cursor-default z-10">1</span>
                                                <a href="#" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">2</a>
                                                <a href="#" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">3</a>
                                                <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 cursor-default">...</span>
                                                <a href="#" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">9</a>
                                                <a href="#" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">10</a>
                                                <a href="#" class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-r-md hover:bg-gray-50 dark:hover:bg-gray-700">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </a>
                                            </span>
                                        </div>
                                    </div>
                                </nav>
                            </div>
                            
                            {{-- Simple pagination --}}
                            <div class="mb-6">
                                <h5 class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">Simple Pagination</h5>
                                <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-center">
                                    <div class="flex space-x-2">
                                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 cursor-not-allowed rounded-md">
                                            Previous
                                        </span>
                                        <a href="#" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                            Next
                                        </a>
                                    </div>
                                </nav>
                            </div>
                            
                            {{-- Small simple pagination --}}
                            <div>
                                <h5 class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">Simple Pagination (Small)</h5>
                                <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-center">
                                    <div class="flex space-x-2">
                                        <a href="#" class="relative inline-flex items-center px-3 py-1 text-xs font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                            Previous
                                        </a>
                                        <a href="#" class="relative inline-flex items-center px-3 py-1 text-xs font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                            Next
                                        </a>
                                    </div>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Breadcrumb Components --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Breadcrumb Components</h3>
                    
                    <div class="space-y-6">
                        {{-- Basic Breadcrumb --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Basic Breadcrumb</h4>
                            <x-ui.breadcrumb.base>
                                <x-ui.breadcrumb.item href="/" :first="true">Home</x-ui.breadcrumb.item>
                                <x-ui.breadcrumb.item href="/projects">Projects</x-ui.breadcrumb.item>
                                <x-ui.breadcrumb.item href="/projects/laravel">Laravel</x-ui.breadcrumb.item>
                                <x-ui.breadcrumb.item :active="true">Settings</x-ui.breadcrumb.item>
                            </x-ui.breadcrumb.base>
                        </div>
                        
                        {{-- Breadcrumb with Icons --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">With Icons</h4>
                            <x-ui.breadcrumb.base>
                                <x-ui.breadcrumb.item href="/" :first="true" 
                                    icon='<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>'>
                                    Home
                                </x-ui.breadcrumb.item>
                                <x-ui.breadcrumb.item href="/users"
                                    icon='<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>'>
                                    Users
                                </x-ui.breadcrumb.item>
                                <x-ui.breadcrumb.item :active="true">Profile</x-ui.breadcrumb.item>
                            </x-ui.breadcrumb.base>
                        </div>
                        
                        {{-- Folder Navigation Breadcrumb --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Folder Navigation Breadcrumb</h4>
                            <x-ui.card.base>
                                <x-ui.card.body class="p-4">
                                    <div class="flex items-center space-x-2 text-sm">
                                        <a href="#" class="flex items-center text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                            </svg>
                                            Root
                                        </a>
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                        <a href="#" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                            Documents
                                        </a>
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                        <a href="#" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                            Projects
                                        </a>
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                        <span class="font-medium text-gray-900 dark:text-gray-100">Current Folder</span>
                                    </div>
                                </x-ui.card.body>
                            </x-ui.card.base>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Table Action Patterns --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Table Action Patterns</h3>
                    
                    <div class="space-y-8">
                        {{-- Icon-only Actions (Recommended) --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Icon-only Actions (Recommended for tables)</h4>
                            <x-ui.card.base>
                                <x-ui.card.body>
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-700">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                                <th class="relative px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">Sample Item</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">Active</span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <div class="flex items-center justify-end gap-2">
                                                        <a href="#" 
                                                           class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                                           title="View details">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                        </a>
                                                        <a href="#" 
                                                           class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                                           title="Edit">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                        </a>
                                                        <a href="#" 
                                                           class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                                           title="Download">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                            </svg>
                                                        </a>
                                                        <button type="button" 
                                                                class="p-1 rounded-lg text-red-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                                                                title="Delete">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </x-ui.card.body>
                            </x-ui.card.base>
                        </div>
                        
                        {{-- Single Action Link --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Single Action (View-only tables)</h4>
                            <x-ui.card.base>
                                <x-ui.card.body>
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-700">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                                <th class="relative px-6 py-3"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">Report Q1 2024</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">Jan 15, 2024</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="#" class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                        View details
                                                        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                        </svg>
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </x-ui.card.body>
                            </x-ui.card.base>
                        </div>
                        
                        {{-- Button Actions --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Button Actions (When explicit actions are needed)</h4>
                            <x-ui.card.base>
                                <x-ui.card.body>
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-700">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                                <th class="relative px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">Important Document</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">Pending</span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <div class="flex items-center justify-end gap-2">
                                                        <x-ui.button.secondary size="sm" href="#">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                            Review
                                                        </x-ui.button.secondary>
                                                        <x-ui.button.primary size="sm">
                                                            Approve
                                                        </x-ui.button.primary>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </x-ui.card.body>
                            </x-ui.card.base>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Table with Search and Folder Breadcrumb --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Advanced Table with Folder Navigation</h3>
                    
                    <x-ui.card.base x-data="folderTableDemo()">
                        <x-ui.card.header>
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">Document Folders</h3>
                                    <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">Browse and manage documents</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <!-- Search -->
                                    <div class="relative">
                                        <input 
                                            type="text" 
                                            x-model="searchTerm"
                                            placeholder="Search folders..."
                                            class="w-64 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                            x-on:input="filterFolders"
                                        >
                                        <svg class="absolute right-3 top-2.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </x-ui.card.header>
                        <x-ui.card.body>
                            <!-- Folder Path Breadcrumb -->
                            <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center space-x-2 text-sm">
                                    <a href="#" class="flex items-center text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                        Root
                                    </a>
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                    <a href="#" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                        Documents
                                    </a>
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">Projects</span>
                                </div>
                            </div>
                            
                            <x-ui.table.base>
                                <x-slot name="head">
                                    <x-ui.table.head-cell>Folder Name</x-ui.table.head-cell>
                                    <x-ui.table.head-cell>Files</x-ui.table.head-cell>
                                    <x-ui.table.head-cell>Size</x-ui.table.head-cell>
                                    <x-ui.table.head-cell>Status</x-ui.table.head-cell>
                                    <x-ui.table.head-cell class="text-right">Actions</x-ui.table.head-cell>
                                </x-slot>
                                <x-slot name="body">
                                    <template x-for="folder in filteredFolders" :key="folder.id">
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                            <x-ui.table.cell>
                                                <a href="#" class="flex items-center hover:text-blue-600 dark:hover:text-blue-400 group">
                                                    <div class="flex-shrink-0">
                                                        <svg class="h-5 w-5 text-yellow-500 group-hover:text-yellow-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                                        </svg>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100" x-text="folder.name"></div>
                                                    </div>
                                                </a>
                                            </x-ui.table.cell>
                                            <x-ui.table.cell>
                                                <div class="flex items-center">
                                                    <span x-text="folder.files_count" class="text-sm font-medium text-gray-900 dark:text-gray-100"></span>
                                                    <svg class="h-4 w-4 ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </div>
                                            </x-ui.table.cell>
                                            <x-ui.table.cell x-text="folder.size"></x-ui.table.cell>
                                            <x-ui.table.cell>
                                                <x-ui.badge x-bind:variant="folder.is_public ? 'success' : 'secondary'" x-text="folder.is_public ? 'Public' : 'Private'"></x-ui.badge>
                                            </x-ui.table.cell>
                                            <x-ui.table.action-cell>
                                                <x-ui.button.secondary size="sm" href="#">
                                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    View
                                                </x-ui.button.secondary>
                                            </x-ui.table.action-cell>
                                        </tr>
                                    </template>
                                </x-slot>
                            </x-ui.table.base>
                        </x-ui.card.body>
                    </x-ui.card.base>
                    
                    <script>
                        function folderTableDemo() {
                            return {
                                folders: [
                                    { id: 1, name: 'Documents', files_count: 12, size: '24 MB', is_public: true },
                                    { id: 2, name: 'Images', files_count: 45, size: '120 MB', is_public: true },
                                    { id: 3, name: 'Private Files', files_count: 8, size: '15 MB', is_public: false },
                                    { id: 4, name: 'Reports', files_count: 23, size: '58 MB', is_public: false }
                                ],
                                filteredFolders: [],
                                searchTerm: '',
                                
                                init() {
                                    this.filteredFolders = [...this.folders];
                                },
                                
                                filterFolders() {
                                    if (this.searchTerm) {
                                        this.filteredFolders = this.folders.filter(folder => 
                                            folder.name.toLowerCase().includes(this.searchTerm.toLowerCase())
                                        );
                                    } else {
                                        this.filteredFolders = [...this.folders];
                                    }
                                }
                            }
                        }
                    </script>
                </div>
            </div>
            
            {{-- Tab Components --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Tab Components</h3>
                    
                    <div class="space-y-8">
                        {{-- Underline Tabs --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Underline Tabs (Default)</h4>
                            <x-ui.tabs.base defaultTab="tab1">
                                <x-ui.tabs.list>
                                    <x-ui.tabs.tab name="tab1" label="My Account" />
                                    <x-ui.tabs.tab name="tab2" label="Company" />
                                    <x-ui.tabs.tab name="tab3" label="Team Members" />
                                    <x-ui.tabs.tab name="tab4" label="Billing" />
                                </x-ui.tabs.list>
                                
                                <x-ui.tabs.panels>
                                    <x-ui.tabs.panel name="tab1">
                                        <div class="text-gray-700 dark:text-gray-300">
                                            <h5 class="font-medium mb-2">Account Settings</h5>
                                            <p>Manage your account settings and preferences here.</p>
                                        </div>
                                    </x-ui.tabs.panel>
                                    
                                    <x-ui.tabs.panel name="tab2">
                                        <div class="text-gray-700 dark:text-gray-300">
                                            <h5 class="font-medium mb-2">Company Information</h5>
                                            <p>Update your company details and business information.</p>
                                        </div>
                                    </x-ui.tabs.panel>
                                    
                                    <x-ui.tabs.panel name="tab3">
                                        <div class="text-gray-700 dark:text-gray-300">
                                            <h5 class="font-medium mb-2">Team Management</h5>
                                            <p>Add or remove team members and manage their permissions.</p>
                                        </div>
                                    </x-ui.tabs.panel>
                                    
                                    <x-ui.tabs.panel name="tab4">
                                        <div class="text-gray-700 dark:text-gray-300">
                                            <h5 class="font-medium mb-2">Billing & Subscription</h5>
                                            <p>View and manage your billing information and subscription plan.</p>
                                        </div>
                                    </x-ui.tabs.panel>
                                </x-ui.tabs.panels>
                            </x-ui.tabs.base>
                        </div>
                        
                        {{-- Pills Tabs --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Pills Tabs</h4>
                            <x-ui.tabs.base defaultTab="pill1" variant="pills">
                                <x-ui.tabs.list variant="pills">
                                    <x-ui.tabs.tab name="pill1" label="Overview" variant="pills" />
                                    <x-ui.tabs.tab name="pill2" label="Analytics" variant="pills" />
                                    <x-ui.tabs.tab name="pill3" label="Reports" variant="pills" />
                                    <x-ui.tabs.tab name="pill4" label="Notifications" variant="pills" />
                                </x-ui.tabs.list>
                                
                                <x-ui.tabs.panels>
                                    <x-ui.tabs.panel name="pill1">
                                        <div class="text-gray-700 dark:text-gray-300">
                                            <p>Overview content goes here.</p>
                                        </div>
                                    </x-ui.tabs.panel>
                                    
                                    <x-ui.tabs.panel name="pill2">
                                        <div class="text-gray-700 dark:text-gray-300">
                                            <p>Analytics content goes here.</p>
                                        </div>
                                    </x-ui.tabs.panel>
                                    
                                    <x-ui.tabs.panel name="pill3">
                                        <div class="text-gray-700 dark:text-gray-300">
                                            <p>Reports content goes here.</p>
                                        </div>
                                    </x-ui.tabs.panel>
                                    
                                    <x-ui.tabs.panel name="pill4">
                                        <div class="text-gray-700 dark:text-gray-300">
                                            <p>Notifications content goes here.</p>
                                        </div>
                                    </x-ui.tabs.panel>
                                </x-ui.tabs.panels>
                            </x-ui.tabs.base>
                        </div>
                        
                        {{-- Tabs with Icons and Badges --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Tabs with Icons & Badges</h4>
                            <x-ui.tabs.base defaultTab="icon1">
                                <x-ui.tabs.list>
                                    <x-ui.tabs.tab name="icon1">
                                        <x-slot name="icon">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </x-slot>
                                        Profile
                                    </x-ui.tabs.tab>
                                    
                                    <x-ui.tabs.tab name="icon2">
                                        <x-slot name="icon">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </x-slot>
                                        <x-slot name="badge">
                                            <x-ui.badge size="sm" variant="danger">3</x-ui.badge>
                                        </x-slot>
                                        Messages
                                    </x-ui.tabs.tab>
                                    
                                    <x-ui.tabs.tab name="icon3">
                                        <x-slot name="icon">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                            </svg>
                                        </x-slot>
                                        <x-slot name="badge">
                                            <x-ui.badge size="sm" variant="warning">12</x-ui.badge>
                                        </x-slot>
                                        Notifications
                                    </x-ui.tabs.tab>
                                </x-ui.tabs.list>
                                
                                <x-ui.tabs.panels>
                                    <x-ui.tabs.panel name="icon1">
                                        <div class="text-gray-700 dark:text-gray-300">
                                            <p>Profile content with icon.</p>
                                        </div>
                                    </x-ui.tabs.panel>
                                    
                                    <x-ui.tabs.panel name="icon2">
                                        <div class="text-gray-700 dark:text-gray-300">
                                            <p>You have 3 new messages.</p>
                                        </div>
                                    </x-ui.tabs.panel>
                                    
                                    <x-ui.tabs.panel name="icon3">
                                        <div class="text-gray-700 dark:text-gray-300">
                                            <p>You have 12 new notifications.</p>
                                        </div>
                                    </x-ui.tabs.panel>
                                </x-ui.tabs.panels>
                            </x-ui.tabs.base>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Form Components --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Form Components</h3>
                    
                    <form>
                        <x-ui.form.group>
                            {{-- Text Inputs --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <x-ui.form.input
                                    name="basic_input"
                                    label="Basic Input"
                                    placeholder="Enter text..."
                                />
                                
                                <x-ui.form.input
                                    name="required_input"
                                    label="Required Input"
                                    placeholder="This field is required"
                                    required
                                />
                                
                                <x-ui.form.input
                                    type="email"
                                    name="email_input"
                                    label="Email Input"
                                    placeholder="user@example.com"
                                    helperText="We'll never share your email."
                                />
                                
                                <x-ui.form.input
                                    name="error_input"
                                    label="Input with Error"
                                    value="Invalid value"
                                    error="This field has an error."
                                />
                                
                                <x-ui.form.input
                                    name="icon_input"
                                    label="Input with Icons"
                                    placeholder="Search..."
                                >
                                    <x-slot name="leadingIcon">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </x-slot>
                                    <x-slot name="trailingIcon">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </x-slot>
                                </x-ui.form.input>
                                
                                <x-ui.form.input
                                    name="disabled_input"
                                    label="Disabled Input"
                                    value="Cannot edit this"
                                    disabled
                                />
                            </div>
                            
                            {{-- Textarea --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <x-ui.form.textarea
                                    name="basic_textarea"
                                    label="Basic Textarea"
                                    placeholder="Enter your message..."
                                    rows="3"
                                />
                                
                                <x-ui.form.textarea
                                    name="helper_textarea"
                                    label="Textarea with Helper"
                                    placeholder="Describe your issue..."
                                    helperText="Provide as much detail as possible."
                                    rows="3"
                                />
                            </div>
                            
                            {{-- Select --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <x-ui.form.select
                                    name="basic_select"
                                    label="Basic Select"
                                    :options="[
                                        ['id' => 1, 'name' => 'Option 1'],
                                        ['id' => 2, 'name' => 'Option 2'],
                                        ['id' => 3, 'name' => 'Option 3'],
                                    ]"
                                />
                                
                                <x-ui.form.select
                                    name="required_select"
                                    label="Required Select"
                                    placeholder="Choose an option..."
                                    :options="['Small', 'Medium', 'Large', 'Extra Large']"
                                    optionValue=""
                                    optionText=""
                                    required
                                />
                            </div>
                            
                            {{-- Checkboxes and Toggles --}}
                            <div class="space-y-4">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Checkboxes & Toggles</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <x-ui.form.checkbox
                                            name="basic_checkbox"
                                            label="Basic Checkbox"
                                        />
                                        
                                        <x-ui.form.checkbox
                                            name="helper_checkbox"
                                            label="Checkbox with Helper"
                                            helperText="This option enables additional features."
                                        />
                                        
                                        <x-ui.form.checkbox
                                            name="checked_checkbox"
                                            label="Pre-checked Checkbox"
                                            checked
                                        />
                                    </div>
                                    
                                    <div class="space-y-4">
                                        <x-ui.form.toggle
                                            name="basic_toggle"
                                            label="Basic Toggle"
                                        />
                                        
                                        <x-ui.form.toggle
                                            name="helper_toggle"
                                            label="Toggle with Helper"
                                            helperText="Enable notifications for this item."
                                        />
                                        
                                        <x-ui.form.toggle
                                            name="checked_toggle"
                                            label="Pre-checked Toggle"
                                            checked
                                        />
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Radio Buttons --}}
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Radio Buttons</h4>
                                <div class="space-y-3">
                                    <x-ui.form.radio
                                        name="plan"
                                        value="free"
                                        label="Free Plan"
                                        helperText="Basic features for personal use"
                                    />
                                    
                                    <x-ui.form.radio
                                        name="plan"
                                        value="pro"
                                        label="Pro Plan"
                                        helperText="Advanced features for professionals"
                                        checked
                                    />
                                    
                                    <x-ui.form.radio
                                        name="plan"
                                        value="enterprise"
                                        label="Enterprise Plan"
                                        helperText="Full features with priority support"
                                    />
                                </div>
                            </div>
                            
                            {{-- Input Sizes --}}
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Input Sizes</h4>
                                <div class="space-y-3">
                                    <x-ui.form.input
                                        name="small_input"
                                        placeholder="Small input"
                                        size="sm"
                                    />
                                    
                                    <x-ui.form.input
                                        name="medium_input"
                                        placeholder="Medium input (default)"
                                        size="md"
                                    />
                                    
                                    <x-ui.form.input
                                        name="large_input"
                                        placeholder="Large input"
                                        size="lg"
                                    />
                                </div>
                            </div>
                        </x-ui.form.group>
                    </form>
                </div>
            </div>
            
            {{-- Vertical Form Layout Example --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Vertical Form Layout Pattern</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                        Standard pattern for vertical forms with proper spacing between fields. Each input is wrapped in a div, with space-y-6 on the form.
                    </p>
                    
                    <div class="max-w-2xl">
                        <form class="space-y-6">
                            @csrf
                            
                            <div>
                                <x-ui.form.input
                                    label="Full Name"
                                    name="name"
                                    type="text"
                                    placeholder="John Doe"
                                    helperText="Enter your full name as it will appear in the system"
                                    required
                                >
                                    <x-slot name="leadingIcon">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </x-slot>
                                </x-ui.form.input>
                            </div>
                            
                            <div>
                                <x-ui.form.input
                                    label="Email Address"
                                    name="email"
                                    type="email"
                                    placeholder="john@example.com"
                                    helperText="We'll use this to send you important notifications"
                                    required
                                >
                                    <x-slot name="leadingIcon">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </x-slot>
                                </x-ui.form.input>
                            </div>
                            
                            <div>
                                <x-ui.form.select
                                    name="department"
                                    label="Department"
                                    placeholder="Select department..."
                                    helperText="Choose the department this user belongs to"
                                >
                                    <option value="">Select Department</option>
                                    <option value="sales">Sales</option>
                                    <option value="marketing">Marketing</option>
                                    <option value="engineering">Engineering</option>
                                    <option value="hr">Human Resources</option>
                                </x-ui.form.select>
                            </div>
                            
                            <div>
                                <x-ui.form.textarea
                                    name="bio"
                                    label="Bio"
                                    rows="3"
                                    placeholder="Tell us about yourself..."
                                    helperText="Brief description for your profile (max 500 characters)"
                                />
                            </div>
                            
                            <div class="space-y-4">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Preferences</h4>
                                <div class="space-y-3">
                                    <x-ui.form.checkbox
                                        name="notifications"
                                        id="notifications"
                                        label="Email Notifications"
                                        helperText="Receive email updates about your account"
                                        checked
                                    />
                                    
                                    <x-ui.form.checkbox
                                        name="newsletter"
                                        id="newsletter"
                                        label="Newsletter"
                                        helperText="Get our monthly newsletter with tips and updates"
                                    />
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                                <x-ui.button.secondary>
                                    Cancel
                                </x-ui.button.secondary>
                                <x-ui.button.primary type="submit">
                                    Save Changes
                                </x-ui.button.primary>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            {{-- Detail View Pattern --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Detail View Pattern</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                        Standard pattern for detail/show pages with header, description lists, and tabbed content.
                    </p>
                    
                    <div class="space-y-6">
                        {{-- Detail Page Header Example --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Detail Page Header</h4>
                            <x-ui.card.base>
                                <x-ui.card.body>
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start space-x-4">
                                            <x-ui.avatar name="John Doe" size="lg" />
                                            <div>
                                                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                                                    John Doe
                                                </h2>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    john.doe@example.com
                                                </p>
                                                <div class="mt-2 flex items-center gap-2">
                                                    <x-ui.badge variant="success" size="sm">Active</x-ui.badge>
                                                    <x-ui.badge variant="secondary" size="sm">Admin</x-ui.badge>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <x-ui.dropdown.base align="right">
                                                <x-slot name="trigger">
                                                    <x-ui.button.secondary size="sm">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                        </svg>
                                                        Actions
                                                    </x-ui.button.secondary>
                                                </x-slot>
                                                
                                                <x-ui.dropdown.item href="#">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Edit
                                                </x-ui.dropdown.item>
                                                
                                                <x-ui.dropdown.divider />
                                                
                                                <x-ui.dropdown.item href="#">
                                                    <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    <span class="text-red-600">Delete</span>
                                                </x-ui.dropdown.item>
                                            </x-ui.dropdown.base>
                                            
                                            <x-ui.button.primary size="sm">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                                Send Email
                                            </x-ui.button.primary>
                                        </div>
                                    </div>
                                </x-ui.card.body>
                            </x-ui.card.base>
                        </div>
                        
                        {{-- Description List Example --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Description List</h4>
                            <x-ui.card.base>
                                <x-ui.card.header>
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                                        User Information
                                    </h3>
                                    <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                                        Personal details and application status.
                                    </p>
                                </x-ui.card.header>
                                <x-ui.card.body>
                                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                Full Name
                                            </dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                                John Doe
                                            </dd>
                                        </div>
                                        
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                Email Address
                                            </dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                                john.doe@example.com
                                            </dd>
                                        </div>
                                        
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                Status
                                            </dt>
                                            <dd class="mt-1">
                                                <x-ui.badge variant="success">Active</x-ui.badge>
                                            </dd>
                                        </div>
                                        
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                Member Since
                                            </dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                                Jan 15, 2024
                                                <span class="text-gray-500 dark:text-gray-400">
                                                    (2 months ago)
                                                </span>
                                            </dd>
                                        </div>
                                    </dl>
                                </x-ui.card.body>
                            </x-ui.card.base>
                        </div>
                        
                        {{-- Empty State for Detail Views --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Empty State in Detail Views</h4>
                            <x-ui.card.base>
                                <x-ui.card.body>
                                    <div class="text-center py-8">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                            No documents found for this user.
                                        </p>
                                    </div>
                                </x-ui.card.body>
                            </x-ui.card.base>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Advanced Components Section --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Advanced Components</h3>
                    
                    {{-- Tooltips --}}
                    <div class="mb-8">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Tooltips</h4>
                        <div class="flex flex-wrap gap-4">
                            <x-ui.tooltip text="This is a tooltip on top" position="top">
                                <x-ui.button.secondary size="sm">Hover me (Top)</x-ui.button.secondary>
                            </x-ui.tooltip>
                            
                            <x-ui.tooltip text="This is a tooltip on bottom" position="bottom">
                                <x-ui.button.secondary size="sm">Hover me (Bottom)</x-ui.button.secondary>
                            </x-ui.tooltip>
                            
                            <x-ui.tooltip text="This is a tooltip on left" position="left">
                                <x-ui.button.secondary size="sm">Hover me (Left)</x-ui.button.secondary>
                            </x-ui.tooltip>
                            
                            <x-ui.tooltip text="This is a tooltip on right" position="right">
                                <x-ui.button.secondary size="sm">Hover me (Right)</x-ui.button.secondary>
                            </x-ui.tooltip>
                            
                            <x-ui.tooltip text="Click to show tooltip" trigger="click">
                                <x-ui.button.primary size="sm">Click me</x-ui.button.primary>
                            </x-ui.tooltip>
                        </div>
                    </div>
                    
                    {{-- Spinners --}}
                    <div class="mb-8">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Loading Spinners</h4>
                        
                        <div class="space-y-4">
                            {{-- Sizes --}}
                            <div>
                                <h5 class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">Sizes</h5>
                                <div class="flex items-center gap-4">
                                    <x-ui.spinner size="sm" />
                                    <x-ui.spinner size="md" />
                                    <x-ui.spinner size="lg" />
                                    <x-ui.spinner size="xl" />
                                </div>
                            </div>
                            
                            {{-- Colors --}}
                            <div>
                                <h5 class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">Colors</h5>
                                <div class="flex items-center gap-4">
                                    <x-ui.spinner color="primary" />
                                    <x-ui.spinner color="secondary" />
                                    <div class="bg-gray-800 dark:bg-gray-700 p-2 rounded">
                                        <x-ui.spinner color="white" />
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Types --}}
                            <div>
                                <h5 class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">Types</h5>
                                <div class="flex items-center gap-4">
                                    <x-ui.spinner type="circle" text="Loading..." />
                                    <x-ui.spinner type="dots" text="Processing..." />
                                </div>
                            </div>
                            
                            {{-- Positions --}}
                            <div>
                                <h5 class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">Center Position</h5>
                                <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg h-32">
                                    <x-ui.spinner position="center" text="Centered spinner" />
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Progress Bars --}}
                    <div class="mb-8">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Progress Bars</h4>
                        
                        <div class="space-y-4">
                            {{-- Basic Progress --}}
                            <div>
                                <h5 class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">Basic Progress</h5>
                                <x-ui.progress value="60" />
                            </div>
                            
                            {{-- With Label --}}
                            <div>
                                <h5 class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">With Label</h5>
                                <x-ui.progress value="75" showLabel>Project Progress</x-ui.progress>
                            </div>
                            
                            {{-- Sizes --}}
                            <div>
                                <h5 class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">Sizes</h5>
                                <div class="space-y-2">
                                    <x-ui.progress value="30" size="sm" />
                                    <x-ui.progress value="50" size="md" />
                                    <x-ui.progress value="70" size="lg" showLabel labelPosition="inside" />
                                </div>
                            </div>
                            
                            {{-- Colors --}}
                            <div>
                                <h5 class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">Colors</h5>
                                <div class="space-y-2">
                                    <x-ui.progress value="25" color="primary" showLabel>Primary</x-ui.progress>
                                    <x-ui.progress value="50" color="success" showLabel>Success</x-ui.progress>
                                    <x-ui.progress value="75" color="warning" showLabel>Warning</x-ui.progress>
                                    <x-ui.progress value="90" color="danger" showLabel>Danger</x-ui.progress>
                                </div>
                            </div>
                            
                            {{-- Striped & Animated --}}
                            <div>
                                <h5 class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">Striped & Animated</h5>
                                <div class="space-y-2">
                                    <x-ui.progress value="60" striped />
                                    <x-ui.progress value="40" striped animated color="success" />
                                </div>
                            </div>
                            
                            {{-- Indeterminate --}}
                            <div>
                                <h5 class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">Indeterminate</h5>
                                <x-ui.progress indeterminate />
                            </div>
                        </div>
                    </div>
                    
                    {{-- Avatars --}}
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Avatars</h4>
                        
                        <div class="space-y-4">
                            {{-- Sizes --}}
                            <div>
                                <h5 class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">Sizes</h5>
                                <div class="flex items-center gap-4">
                                    <x-ui.avatar size="xs" name="John Doe" />
                                    <x-ui.avatar size="sm" name="Jane Smith" />
                                    <x-ui.avatar size="md" name="Bob Johnson" />
                                    <x-ui.avatar size="lg" name="Alice Brown" />
                                    <x-ui.avatar size="xl" name="Tom Wilson" />
                                </div>
                            </div>
                            
                            {{-- With Images --}}
                            <div>
                                <h5 class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">With Images</h5>
                                <div class="flex items-center gap-4">
                                    <x-ui.avatar size="sm" src="https://ui-avatars.com/api/?name=User+One" />
                                    <x-ui.avatar size="md" src="https://ui-avatars.com/api/?name=User+Two" />
                                    <x-ui.avatar size="lg" src="https://ui-avatars.com/api/?name=User+Three" />
                                </div>
                            </div>
                            
                            {{-- With Status --}}
                            <div>
                                <h5 class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">With Status</h5>
                                <div class="flex items-center gap-4">
                                    <x-ui.avatar name="Online User" status="online" />
                                    <x-ui.avatar name="Offline User" status="offline" />
                                    <x-ui.avatar name="Away User" status="away" />
                                    <x-ui.avatar name="Busy User" status="busy" />
                                </div>
                            </div>
                            
                            {{-- Different Shapes --}}
                            <div>
                                <h5 class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">Different Shapes</h5>
                                <div class="flex items-center gap-4">
                                    <x-ui.avatar name="Circle" rounded="full" />
                                    <x-ui.avatar name="Large Rounded" rounded="lg" />
                                    <x-ui.avatar name="Medium Rounded" rounded="md" />
                                    <x-ui.avatar name="Square" rounded="none" />
                                </div>
                            </div>
                            
                            {{-- Without Name (Default Icon) --}}
                            <div>
                                <h5 class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">Default Avatar</h5>
                                <div class="flex items-center gap-4">
                                    <x-ui.avatar size="sm" />
                                    <x-ui.avatar size="md" />
                                    <x-ui.avatar size="lg" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Detail View Patterns --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Detail View Patterns</h3>
                    
                    <div class="space-y-8">
                        {{-- Detail Page Header --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Detail Page Header</h4>
                            <x-ui.card.base>
                                <x-ui.card.body>
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start space-x-4">
                                            <x-ui.avatar name="John Doe" size="lg" />
                                            <div>
                                                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                                                    John Doe
                                                </h2>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    john.doe@example.com
                                                </p>
                                                <div class="mt-2 flex items-center gap-2">
                                                    <x-ui.badge variant="success" size="sm">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        Active
                                                    </x-ui.badge>
                                                    <x-ui.badge variant="secondary" size="sm">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                        </svg>
                                                        User
                                                    </x-ui.badge>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <x-ui.button.secondary size="sm">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Edit
                                            </x-ui.button.secondary>
                                            <x-ui.button.primary size="sm">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                                Send Email
                                            </x-ui.button.primary>
                                        </div>
                                    </div>
                                </x-ui.card.body>
                            </x-ui.card.base>
                        </div>
                        
                        {{-- Description List Pattern --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Description List</h4>
                            <x-ui.card.base>
                                <x-ui.card.header>
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">User Information</h3>
                                </x-ui.card.header>
                                <x-ui.card.body>
                                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-4">
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Full name</dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">John Doe</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email address</dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">john.doe@example.com</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone number</dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">+1 (555) 123-4567</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Registration date</dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">January 1, 2024</dd>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">About</dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                                Experienced software developer with a passion for creating elegant solutions to complex problems.
                                            </dd>
                                        </div>
                                    </dl>
                                </x-ui.card.body>
                            </x-ui.card.base>
                        </div>
                        
                        {{-- Empty State in Detail Views --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Empty State</h4>
                            <x-ui.card.base>
                                <x-ui.card.header>
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">Recent Activity</h3>
                                </x-ui.card.header>
                                <x-ui.card.body>
                                    <div class="text-center py-8">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No activity</h3>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">This user hasn't performed any actions yet.</p>
                                    </div>
                                </x-ui.card.body>
                            </x-ui.card.base>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>