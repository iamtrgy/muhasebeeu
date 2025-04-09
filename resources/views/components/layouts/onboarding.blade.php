<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Onboarding</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Alpine.js x-cloak -->
        <style>
            [x-cloak] { display: none !important; }
            
            /* Custom gradient background */
            .gradient-bg {
                background: linear-gradient(135deg, #f0f9ff 0%, #e1f5fe 100%);
            }
            
            .dark .gradient-bg {
                background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full gradient-bg dark:bg-gray-900">
        <!-- Logout Button -->
        <div class="absolute top-4 right-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 dark:text-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
        
        <div class="flex min-h-screen flex-col justify-center py-12 sm:px-6 lg:px-8">
            <div class="sm:mx-auto sm:w-full sm:max-w-md">
                <div class="flex justify-center">
                    <a href="/">
                        <x-application-logo class="h-20 w-auto fill-current text-blue-600 dark:text-blue-500" />
                    </a>
                </div>
                <h2 class="mt-6 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900 dark:text-gray-100">
                    {{ $title ?? 'Onboarding' }}
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
                    {{ $subtitle ?? 'Complete your account setup' }}
                </p>
            </div>

            <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
                <div class="bg-white dark:bg-gray-800 px-6 py-8 shadow-lg sm:rounded-lg sm:px-10 border border-gray-100 dark:border-gray-700">
                    <!-- Onboarding Progress -->
                    @php
                        // Convert the step slot to an integer
                        $stepValue = $step ?? '1';
                        $currentStep = is_numeric($stepValue) ? (int)$stepValue : 1;
                    @endphp
                    <div class="mb-8">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full {{ $currentStep >= 1 ? 'bg-blue-600 ring-4 ring-blue-100 dark:ring-blue-900' : 'bg-gray-200 dark:bg-gray-700' }}">
                                    <span class="text-sm font-medium text-white">1</span>
                                </div>
                                <div class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-100">Country</div>
                            </div>
                            <div class="flex-1 mx-4 h-1 {{ $currentStep >= 2 ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-700' }}"></div>
                            <div class="flex items-center">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full {{ $currentStep >= 2 ? 'bg-blue-600 ring-4 ring-blue-100 dark:ring-blue-900' : 'bg-gray-200 dark:bg-gray-700' }}">
                                    <span class="text-sm font-medium text-white">2</span>
                                </div>
                                <div class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-100">Company</div>
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    {{ $slot }}
                </div>
                
                <!-- Footer -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Need help? <a href="#" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300">Contact support</a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Stack for additional scripts -->
        @stack('scripts')
    </body>
</html> 