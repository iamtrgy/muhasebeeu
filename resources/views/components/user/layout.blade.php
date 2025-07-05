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
            <!-- Dashboard Group -->
            <x-ui.layout.sidebar-group label="Dashboard" :open="true">
                <x-ui.layout.sidebar-item 
                    :href="route('dashboard')" 
                    :active="request()->routeIs('dashboard')"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>'
                >
                    {{ __('Dashboard') }}
                </x-ui.layout.sidebar-item>
            </x-ui.layout.sidebar-group>

            <!-- Document Management Group -->
            <x-ui.layout.sidebar-group label="My Documents">
                <x-ui.layout.sidebar-item 
                    :href="route('user.folders.index')" 
                    :active="request()->routeIs('user.folders.*')"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" /></svg>'
                >
                    {{ __('My Folders') }}
                </x-ui.layout.sidebar-item>
                
                <x-ui.layout.sidebar-item 
                    :href="route('user.files.upload')" 
                    :active="request()->routeIs('user.files.*')"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>'
                >
                    {{ __('Upload Files') }}
                </x-ui.layout.sidebar-item>
            </x-ui.layout.sidebar-group>

            <!-- Tax Calendar Group -->
            <x-ui.layout.sidebar-group label="Tax Services">
                @php $userTasks = \App\Models\TaxCalendarTask::where('user_id', auth()->id())->where('status', 'pending')->count(); @endphp
                <x-ui.layout.sidebar-item 
                    :href="route('user.tax-calendar.index')" 
                    :active="request()->routeIs('user.tax-calendar.*')"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>'
                    :badge="$userTasks > 0 ? (string)$userTasks : null"
                    badgeType="info"
                >
                    {{ __('My Tax Tasks') }}
                </x-ui.layout.sidebar-item>

                <x-ui.layout.sidebar-item 
                    :href="route('user.services.index')" 
                    :active="request()->routeIs('user.services.*')"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
                >
                    {{ __('My Services') }}
                </x-ui.layout.sidebar-item>
            </x-ui.layout.sidebar-group>

            <!-- Account Group -->
            <x-ui.layout.sidebar-group label="Account">
                <x-ui.layout.sidebar-item 
                    :href="route('profile.edit')" 
                    :active="request()->routeIs('profile.*')"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>'
                >
                    {{ __('Profile') }}
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

    @stack('modals')
    @stack('scripts')
</body>
</html>