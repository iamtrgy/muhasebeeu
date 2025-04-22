<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- jQuery and Toastr -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script>
            // Configure Toastr
            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-right",
                timeOut: 3000,
                extendedTimeOut: 1000,
            };
        </script>

        <!-- Alpine.js x-cloak -->
        <style>
            [x-cloak] { display: none !important; }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                $methodName = '';
                
                if (count($segments) === 1) {
                    // For single segment routes like 'profile', 'dashboard'
                    $methodName = $segments[0];
                    
                    // Check if the method exists in BreadcrumbService
                    if (method_exists('\App\Services\BreadcrumbService', $methodName)) {
                        $breadcrumbs = \App\Services\BreadcrumbService::{$methodName}();
                    }
                }
                else if (count($segments) >= 2) {
                    // For routes like 'profile.edit', combine segments
                    $methodName = implode('', array_map('ucfirst', $segments));
                    
                    // Check if the method exists in BreadcrumbService
                    if (method_exists('\App\Services\BreadcrumbService', $methodName)) {
                        $breadcrumbs = \App\Services\BreadcrumbService::{$methodName}();
                    }
                }
            }
        @endphp

        <div class="flex h-full w-full">
            <!-- Main content -->
            <main class="min-h-screen flex-1 lg:pl-64">
                <!-- Page Heading -->
                @if (isset($header))
                    <x-unified-header :breadcrumbs="$breadcrumbs">
                        {{ $header }}
                    </x-unified-header>
                @endif

                <!-- Page Content -->
                {{ $slot }}
            </main>
        </div>

        <!-- Stack for additional scripts -->
        @stack('scripts')
    </body>
</html>
