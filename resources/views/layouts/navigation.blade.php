<!-- Mobile Navigation -->
<div x-data="{ isSidebarOpen: false }" class="lg:hidden">
    <!-- Top Bar -->
    <div class="sticky top-0 inset-x-0 z-20 bg-white border-y px-4 sm:px-6 md:px-8 dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center justify-between py-4">
            <!-- Navigation Toggle -->
            <button type="button" class="text-gray-500 hover:text-gray-600" @click="isSidebarOpen = !isSidebarOpen" aria-label="Toggle navigation">
                <span class="sr-only">Toggle Navigation</span>
                <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </button>
            <!-- Logo -->
            @php
    $dashboardRoute = auth()->user()->is_admin ? 'admin.dashboard' : (auth()->user()->is_accountant ? 'accountant.dashboard' : 'user.dashboard');
@endphp
<a class="flex-none text-xl font-semibold dark:text-white" href="{{ route($dashboardRoute) }}" aria-label="Brand">
                <x-application-logo class="block h-8 w-auto fill-current text-gray-800 dark:text-gray-200" />
            </a>
            <!-- Spacer -->
            <div class="w-4"></div>
        </div>
    </div>

    <!-- Sidebar backdrop -->
    <div x-show="isSidebarOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-30 bg-gray-900 bg-opacity-50"
         @click="isSidebarOpen = false">
    </div>

    <!-- Mobile Sidebar -->
    <div x-show="isSidebarOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed top-0 start-0 bottom-0 z-40 w-64 bg-white border-r border-gray-200 pt-7 pb-10 overflow-y-auto dark:bg-gray-800 dark:border-gray-700">
        <!-- Sidebar Content -->
        @php
            $user = auth()->user();
        @endphp

        @if($user && $user->is_admin)
            <x-sidebar-admin />
        @elseif($user && $user->is_accountant)
            <x-sidebar-accountant />
        @else
            <x-sidebar-user />
        @endif
    </div>
</div>

<!-- Desktop Sidebar -->
<div class="hidden lg:fixed lg:inset-y-0 lg:z-30 lg:flex lg:w-64 lg:flex-col">
    <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-white border-r border-gray-200 pt-7 pb-10 dark:bg-gray-800 dark:border-gray-700">
        <!-- Sidebar Content -->
        @php
            $user = auth()->user();
        @endphp

        @if($user && $user->is_admin)
            <x-sidebar-admin />
        @elseif($user && $user->is_accountant)
            <x-sidebar-accountant />
        @else
            <x-sidebar-user />
        @endif
    </div>
</div>
