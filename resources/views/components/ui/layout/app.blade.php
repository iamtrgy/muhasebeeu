@props([
    'sidebarCollapsed' => false,
    'darkMode' => false,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: {{ $darkMode ? 'true' : 'false' }}, sidebarOpen: false }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' - ' : '' }}{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Additional Head Content -->
    {{ $head ?? '' }}
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        @if(isset($sidebar))
            <x-ui.layout.sidebar :collapsed="$sidebarCollapsed">
                {{ $sidebar }}
            </x-ui.layout.sidebar>
        @endif

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            @if(isset($header))
                <x-ui.layout.header>
                    {{ $header }}
                </x-ui.layout.header>
            @endif

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto">
                @if(isset($pageHeader))
                    <div class="bg-white dark:bg-gray-800 shadow">
                        <div class="px-4 sm:px-6 lg:px-8 py-6">
                            {{ $pageHeader }}
                        </div>
                    </div>
                @endif

                <div class="{{ isset($fullWidth) && $fullWidth ? '' : 'px-4 sm:px-6 lg:px-8' }} py-8">
                    {{ $slot }}
                </div>
            </main>

            <!-- Footer -->
            @if(isset($footer))
                <x-ui.layout.footer>
                    {{ $footer }}
                </x-ui.layout.footer>
            @endif
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