<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <!-- Admin Sidebar -->
        <x-admin.sidebar />
    @php
        // Set default breadcrumbs if not provided
        $breadcrumbs = $breadcrumbs ?? [];
        
        // Get current route information
        $routeName = request()->route()->getName();
        $routeParams = request()->route()->parameters();
        
        // Determine breadcrumbs automatically based on current route if not explicitly provided
        if (empty($breadcrumbs) && class_exists('\\App\\Services\\BreadcrumbService')) {
            // Extract the method name from the route
            $segments = explode('.', $routeName);
            $methodName = '';
            
            // Handle special routes with only 2 segments like 'admin.settings'
            if (count($segments) === 2) {
                // Check for special routes like 'admin.settings', 'admin.dashboard'
                $area = $segments[0]; // admin
                $action = $segments[1]; // settings, dashboard
                
                // Format method name like adminSettings
                $methodName = $area . ucfirst($action);
                
                // Check if the method exists in BreadcrumbService
                if (method_exists('\App\Services\BreadcrumbService', $methodName)) {
                    $breadcrumbs = \App\Services\BreadcrumbService::{$methodName}();
                }
            }
            else if (count($segments) >= 3) {
                // For example, 'admin.users.index' would become 'adminUsers'
                // 'admin.users.show' would become 'adminUserDetail'
                $area = $segments[0]; // admin
                $resource = $segments[1]; // users, folders, companies
                $action = $segments[2]; // index, show, edit
                
                // Format the method name based on common patterns
                if ($action === 'index') {
                    $methodName = $area . ucfirst($resource);
                } elseif ($action === 'show' || $action === 'edit' || $action === 'update') {
                    $singularResource = Illuminate\Support\Str::singular($resource);
                    $methodName = $area . ucfirst($singularResource) . 'Detail';
                    
                    // Check if the method exists
                    if (!method_exists('\\App\\Services\\BreadcrumbService', $methodName)) {
                        // Fallback to the resource list if detail method doesn't exist
                        $methodName = $area . ucfirst($resource);
                    }
                } elseif ($action === 'subscription') {
                    $singularResource = Illuminate\Support\Str::singular($resource);
                    $methodName = $area . ucfirst($singularResource) . 'Subscription';
                }
                
                // Call the breadcrumb method if it exists
                if (!empty($methodName) && method_exists('\\App\\Services\\BreadcrumbService', $methodName)) {
                    // Get the model parameter if it exists in the route parameters
                    $modelParam = null;
                    foreach ($routeParams as $param) {
                        if (is_object($param)) {
                            $modelParam = $param;
                            break;
                        }
                    }
                    
                    if ($modelParam) {
                        $breadcrumbs = \App\Services\BreadcrumbService::{$methodName}($modelParam);
                    } else {
                        $breadcrumbs = \App\Services\BreadcrumbService::{$methodName}();
                    }
                }
            }
        }
    @endphp
    
    <!-- Main Content Area -->
    <div class="lg:pl-56 flex flex-col">
        <x-admin.page-header :breadcrumbs="$breadcrumbs">
            @if(isset($header))
                {{ $header }}
            @endif
        </x-admin.page-header>
        
        <main class="flex-1 py-6">
            <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>
    </div>
    </div>
    @stack('scripts')
</body>
</html>
