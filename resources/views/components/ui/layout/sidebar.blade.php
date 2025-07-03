@props([
    'collapsed' => false,
])

<!-- Mobile backdrop -->
<div 
    x-show="sidebarOpen" 
    @click="sidebarOpen = false"
    x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-gray-900 bg-opacity-50 z-40 lg:hidden"
></div>

<!-- Sidebar -->
<div 
    x-data="{ 
        collapsed: {{ $collapsed ? 'true' : 'false' }},
        activeGroup: null,
        toggleGroup(group) {
            this.activeGroup = this.activeGroup === group ? null : group;
        },
        isGroupActive(group) {
            return this.activeGroup === group;
        }
    }"
    :class="{
        'translate-x-0': sidebarOpen,
        '-translate-x-full': !sidebarOpen,
        'lg:translate-x-0': true,
        'lg:w-64': !collapsed,
        'lg:w-20': collapsed
    }"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-900 shadow-xl lg:shadow-lg transition-all duration-300 transform lg:relative lg:transform-none"
>
        <div class="flex flex-col h-full">
            <!-- Sidebar Header -->
            <div class="flex items-center justify-between h-16 px-4 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
                <div class="flex items-center">
                    <div x-show="!collapsed" class="flex items-center">
                        @if(file_exists(public_path('images/logo.png')))
                            <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name', 'Laravel') }}" class="h-8 w-auto">
                        @elseif(file_exists(public_path('images/logo.svg')))
                            <img src="{{ asset('images/logo.svg') }}" alt="{{ config('app.name', 'Laravel') }}" class="h-8 w-auto">
                        @else
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center mr-3">
                                    <span class="text-white font-bold text-sm">{{ substr(config('app.name', 'L'), 0, 1) }}</span>
                                </div>
                                <span class="text-xl font-bold text-gray-900 dark:text-white">{{ config('app.name', 'Laravel') }}</span>
                            </div>
                        @endif
                    </div>
                    <div x-show="collapsed" class="flex items-center justify-center w-full">
                        @if(file_exists(public_path('images/logo-icon.png')))
                            <img src="{{ asset('images/logo-icon.png') }}" alt="{{ config('app.name', 'Laravel') }}" class="h-8 w-8">
                        @elseif(file_exists(public_path('images/logo-icon.svg')))
                            <img src="{{ asset('images/logo-icon.svg') }}" alt="{{ config('app.name', 'Laravel') }}" class="h-8 w-8">
                        @else
                            <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-sm">{{ substr(config('app.name', 'L'), 0, 1) }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Collapse Toggle (Desktop) -->
                <button 
                    @click="collapsed = !collapsed"
                    class="hidden lg:block p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800 dark:hover:text-gray-200 transition-all duration-200"
                >
                    <svg x-show="!collapsed" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                    </svg>
                    <svg x-show="collapsed" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                    </svg>
                </button>

                <!-- Close Button (Mobile) -->
                <button 
                    @click="sidebarOpen = false"
                    class="lg:hidden p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800 dark:hover:text-gray-200 transition-all duration-200"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Sidebar Content -->
            <div class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-800">
                <nav class="px-3 py-6 space-y-2">
                    {{ $slot }}
                </nav>
            </div>

            <!-- Sidebar Footer (Optional - can be removed since user info is in header) -->
            @if(isset($sidebarFooter))
                <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-900">
                    {{ $sidebarFooter }}
                </div>
            @endif
        </div>
    </div>