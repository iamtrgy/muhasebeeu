<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}@yield('title-suffix')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Alpine.js x-cloak -->
        <style>
            [x-cloak] { display: none !important; }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        @stack('styles')
    </head>
    <body class="h-full bg-gray-50 dark:bg-gray-900">
        @include('layouts.navigation')

        @php
            // Set default breadcrumbs if not provided
            $breadcrumbs = $breadcrumbs ?? [];
            
            // Get current route information
            $routeName = request()->route()->getName();
            $routeParams = request()->route()->parameters();
            
            // Determine breadcrumbs automatically based on current route if not explicitly provided
            if (empty($breadcrumbs) && class_exists('\App\Services\BreadcrumbService')) {
                // Extract the method name from the route
                $segments = explode('.', $routeName);
                
                // Handle breadcrumb generation based on route structure
                // This logic can be customized based on your specific needs
                if (method_exists('\App\Services\BreadcrumbService', 'generateBreadcrumbs')) {
                    $breadcrumbs = \App\Services\BreadcrumbService::generateBreadcrumbs($routeName, $routeParams);
                }
            }
        @endphp

        <div class="flex h-full w-full">
            <!-- Sidebar content - will be included in role-specific layouts -->
            @yield('sidebar')
            
            <!-- Main content -->
            <main class="min-h-screen flex-1 @yield('main-padding', 'lg:pl-64')">
                <!-- Page Heading -->
                @hasSection('header')
                    <x-unified-header :breadcrumbs="$breadcrumbs ?? []">
                        @yield('header')
                    </x-unified-header>
                @endif

                <!-- Page Content -->
                @yield('content')
            </main>
        </div>

        <!-- Stack for additional scripts -->
        @stack('scripts')
    </body>
</html>
