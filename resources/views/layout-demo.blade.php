<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: false, sidebarOpen: false }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Layout Demo - {{ config('app.name', 'Laravel') }}</title>

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
                    href="/layout-demo" 
                    :active="true"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>'
                >
                    Home
                </x-ui.layout.sidebar-item>
                
                <x-ui.layout.sidebar-item 
                    href="#analytics"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>'
                    badge="New"
                    badgeType="success"
                >
                    Analytics
                </x-ui.layout.sidebar-item>
            </x-ui.layout.sidebar-group>

            <!-- Management Group -->
            <x-ui.layout.sidebar-group label="Management">
                <x-ui.layout.sidebar-item 
                    href="#users"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>'
                    badge="23"
                >
                    Users
                </x-ui.layout.sidebar-item>
                
                <x-ui.layout.sidebar-item 
                    href="#companies"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>'
                >
                    Companies
                </x-ui.layout.sidebar-item>
                
                <x-ui.layout.sidebar-item 
                    href="#invoices"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>'
                    badge="5"
                    badgeType="warning"
                >
                    Invoices
                </x-ui.layout.sidebar-item>
            </x-ui.layout.sidebar-group>

            <!-- Settings Group -->
            <x-ui.layout.sidebar-group label="Settings">
                <x-ui.layout.sidebar-item 
                    href="#profile"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>'
                >
                    Profile
                </x-ui.layout.sidebar-item>
                
                <x-ui.layout.sidebar-item 
                    href="#preferences"
                    icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>'
                >
                    Preferences
                </x-ui.layout.sidebar-item>
            </x-ui.layout.sidebar-group>
        </x-ui.layout.sidebar>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <x-ui.layout.header>
                <x-slot name="breadcrumb">
                    <x-ui.breadcrumb.base>
                        <x-ui.breadcrumb.item href="/" :first="true">Home</x-ui.breadcrumb.item>
                        <x-ui.breadcrumb.item href="/admin/dashboard">Admin</x-ui.breadcrumb.item>
                        <x-ui.breadcrumb.item :active="true">Layout Demo</x-ui.breadcrumb.item>
                    </x-ui.breadcrumb.base>
                </x-slot>
            </x-ui.layout.header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto">
                <div class="px-4 sm:px-6 lg:px-8 py-8">
                    <!-- Page Title -->
                    <div class="mb-8">
                        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Layout Demo Page</h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">This demonstrates the new layout components with sidebar</p>
                    </div>

    <div class="space-y-6">
        <!-- Welcome Card -->
        <x-ui.card.base>
            <x-ui.card.header>
                <h2 class="text-lg font-medium">Welcome to the Layout Demo</h2>
            </x-ui.card.header>
            <x-ui.card.body>
                <p class="text-gray-600 dark:text-gray-400">
                    This page demonstrates the new layout system with:
                </p>
                <ul class="mt-4 space-y-2 text-gray-600 dark:text-gray-400">
                    <li class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Collapsible sidebar with groups
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Header with user menu and dark mode toggle
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Responsive design that works on all devices
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Breadcrumb navigation
                    </li>
                </ul>
            </x-ui.card.body>
        </x-ui.card.base>

        <!-- Feature Highlights -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <x-ui.card.base>
                <x-ui.card.body>
                    <div class="flex items-center mb-4">
                        <div class="p-3 bg-indigo-100 dark:bg-indigo-900 rounded-lg">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </div>
                        <h3 class="ml-3 text-lg font-medium">Sidebar Navigation</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400">
                        The sidebar supports nested groups, icons, badges, and collapse functionality.
                    </p>
                </x-ui.card.body>
            </x-ui.card.base>

            <x-ui.card.base>
                <x-ui.card.body>
                    <div class="flex items-center mb-4">
                        <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                        </div>
                        <h3 class="ml-3 text-lg font-medium">Dark Mode</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400">
                        Toggle between light and dark themes using the button in the header.
                    </p>
                </x-ui.card.body>
            </x-ui.card.base>

            <x-ui.card.base>
                <x-ui.card.body>
                    <div class="flex items-center mb-4">
                        <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <h3 class="ml-3 text-lg font-medium">User Menu</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400">
                        Access your profile, settings, and logout from the dropdown in the header.
                    </p>
                </x-ui.card.body>
            </x-ui.card.base>
        </div>

        <!-- Interactive Elements -->
        <x-ui.card.base>
            <x-ui.card.header>
                <h2 class="text-lg font-medium">Try These Features</h2>
            </x-ui.card.header>
            <x-ui.card.body>
                <div class="space-y-4">
                    <div>
                        <h3 class="font-medium mb-2">1. Sidebar Toggle</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Click the hamburger menu in the top-left corner to toggle the sidebar on/off.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-medium mb-2">2. Sidebar Groups</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Click on any group header in the sidebar to expand/collapse its items.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-medium mb-2">3. Dark Mode</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Click the moon/sun icon in the header to switch between light and dark themes.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-medium mb-2">4. User Menu</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Click on your avatar in the top-right corner to open the user menu dropdown.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-medium mb-2">5. Responsive Design</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Resize your browser window to see how the layout adapts to different screen sizes.
                        </p>
                    </div>
                </div>
            </x-ui.card.body>
        </x-ui.card.base>

        <!-- Example Content -->
        <x-ui.card.base>
            <x-ui.card.header>
                <h2 class="text-lg font-medium">Example Content Area</h2>
            </x-ui.card.header>
            <x-ui.card.body>
                <p class="text-gray-600 dark:text-gray-400 mb-4">
                    This is where your main content would go. The layout provides:
                </p>
                <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-6">
                    <h3 class="font-medium mb-3">Content Features:</h3>
                    <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                        <li>• Responsive padding that adjusts based on screen size</li>
                        <li>• Maximum width constraint for better readability</li>
                        <li>• Smooth transitions when sidebar toggles</li>
                        <li>• Proper spacing from header and sidebar</li>
                        <li>• Support for full-width or constrained layouts</li>
                    </ul>
                </div>
            </x-ui.card.body>
        </x-ui.card.base>
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <x-ui.layout.footer>
                <x-slot name="links">
                    <a href="#" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                        Documentation
                    </a>
                    <a href="#" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                        API
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

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>