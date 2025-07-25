@props(['title' => '', 'breadcrumbs' => []])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: false, sidebarOpen: false }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ? $title . ' - Admin' : 'Admin Dashboard' }} - {{ config('app.name', 'Laravel') }}</title>

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
            <!-- Administration Group -->
            <x-ui.layout.sidebar-group label="Administration" :open="true">
                <x-ui.layout.sidebar-item 
                    :href="route('admin.dashboard')" 
                    :active="request()->routeIs('admin.dashboard')"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>'
                >
                    {{ __('Dashboard') }}
                </x-ui.layout.sidebar-item>

                <x-ui.layout.sidebar-item 
                    :href="route('admin.users.index')" 
                    :active="request()->routeIs('admin.users.*')"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" /></svg>'
                >
                    {{ __('Users') }}
                </x-ui.layout.sidebar-item>

                <x-ui.layout.sidebar-item 
                    :href="route('admin.companies.index')" 
                    :active="request()->routeIs('admin.companies.*')"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>'
                >
                    {{ __('Companies') }}
                </x-ui.layout.sidebar-item>

                <x-ui.layout.sidebar-item 
                    :href="route('admin.tax-calendar.index')" 
                    :active="request()->routeIs('admin.tax-calendar.*')"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>'
                >
                    {{ __('Tax Calendar') }}
                </x-ui.layout.sidebar-item>

                <x-ui.layout.sidebar-item 
                    :href="route('admin.folders.index')" 
                    :active="request()->routeIs('admin.folders.*')"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" /></svg>'
                >
                    {{ __('File Manager') }}
                </x-ui.layout.sidebar-item>
            </x-ui.layout.sidebar-group>
        </x-ui.layout.sidebar>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0">
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
                        System Status
                    </a>
                    <a href="#" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                        Documentation
                    </a>
                    <a href="#" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                        Support
                    </a>
                </x-slot>
            </x-ui.layout.footer>
        </div>
    </div>

    <!-- Modals -->
    <div id="modal-container"></div>

    <!-- Toast Notifications -->
    <div id="toast-container" class="fixed bottom-0 right-0 p-6 space-y-4 z-50"></div>

    @stack('modals')
    @stack('scripts')
</body>
</html>