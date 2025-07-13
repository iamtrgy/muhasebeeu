@props(['title' => '', 'breadcrumbs' => []])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: false, sidebarOpen: false }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ? $title . ' - Customer' : 'Customer Dashboard' }} - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <x-ui.layout.sidebar>
            <!-- Main Navigation -->
            <x-ui.layout.sidebar-group label="Dashboard" :open="true">
                <x-ui.layout.sidebar-item 
                    :href="route('user.dashboard')" 
                    :active="request()->routeIs('user.dashboard')"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>'
                >
                    {{ __('Home') }}
                </x-ui.layout.sidebar-item>

                <x-ui.layout.sidebar-item 
                    :href="route('user.invoices.index')" 
                    :active="request()->routeIs('user.invoices.*')"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/></svg>'
                >
                    {{ __('Invoices') }}
                </x-ui.layout.sidebar-item>

                <x-ui.layout.sidebar-item 
                    :href="route('user.banks.index')" 
                    :active="request()->routeIs('user.banks.*')"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>'
                >
                    {{ __('Banks') }}
                </x-ui.layout.sidebar-item>

                <x-ui.layout.sidebar-item 
                    :href="route('user.documents.index')" 
                    :active="request()->routeIs('user.documents.*')"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>'
                >
                    {{ __('Documents') }}
                </x-ui.layout.sidebar-item>

                <x-ui.layout.sidebar-item 
                    :href="route('user.clients.index')" 
                    :active="request()->routeIs('user.clients.*')"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>'
                >
                    {{ __('Clients') }}
                </x-ui.layout.sidebar-item>

                <x-ui.layout.sidebar-item 
                    :href="route('user.tax-calendar.index')" 
                    :active="request()->routeIs('user.tax-calendar.*')"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>'
                >
                    {{ __('Tax Calendar') }}
                </x-ui.layout.sidebar-item>

                @if(auth()->user()->subscribed('default'))
                    <x-ui.layout.sidebar-item 
                        :href="route('user.ai-analysis.history')" 
                        :active="request()->routeIs('user.ai-analysis.*')"
                        icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>'
                    >
                        {{ __('AI History') }}
                    </x-ui.layout.sidebar-item>
                @endif
            </x-ui.layout.sidebar-group>

            <!-- Company -->
            <x-ui.layout.sidebar-group label="Company">
                @php
                    $companies = auth()->user()->companies;
                    $companyCount = $companies->count();
                @endphp
                <x-ui.layout.sidebar-item 
                    :href="$companyCount === 1 ? route('user.companies.show', $companies->first()->id) : route('user.companies.index')" 
                    :active="request()->routeIs('user.companies.*')"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>'
                >
                    @if($companyCount === 1)
                        {{ $companies->first()->name }}
                    @else
                        {{ $companyCount > 0 ? $companyCount . ' Companies' : 'Companies' }}
                    @endif
                </x-ui.layout.sidebar-item>
            </x-ui.layout.sidebar-group>
        </x-ui.layout.sidebar>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 min-h-0">
            <!-- Header -->
            <x-ui.layout.header>
                @if(!empty($breadcrumbs))
                    <x-slot name="breadcrumb">
                        <x-ui.breadcrumb.base>
                            @foreach($breadcrumbs as $breadcrumb)
                                <x-ui.breadcrumb.item 
                                    :href="$breadcrumb['href'] ?? null"
                                    :active="$loop->last"
                                    :first="$breadcrumb['first'] ?? false"
                                >
                                    {{ $breadcrumb['title'] }}
                                </x-ui.breadcrumb.item>
                            @endforeach
                        </x-ui.breadcrumb.base>
                    </x-slot>
                @endif
            </x-ui.layout.header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto">
                <div class="px-4 sm:px-6 lg:px-8 py-8">
                    @if($title)
                        <!-- Page Title -->
                        <div class="mb-8">
                            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $title }}</h1>
                        </div>
                    @endif

                    {{ $slot }}
                </div>
            </main>

            <!-- Footer -->
            <x-ui.layout.footer>
                <x-slot name="links">
                    <a href="#" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                        Help
                    </a>
                    <a href="#" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                        Support
                    </a>
                    <a href="#" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                        Contact
                    </a>
                </x-slot>
            </x-ui.layout.footer>
        </div>
    </div>

    <!-- Modals -->
    <div id="modal-container"></div>

    <!-- Toast Notifications -->
    <div id="toast-container" class="fixed bottom-0 right-0 p-6 space-y-4 z-50"></div>

    <!-- Global Confirmation Modal -->
    <x-ui.confirmation-modal />
    
    @stack('modals')
    @stack('scripts')
</body>
</html>