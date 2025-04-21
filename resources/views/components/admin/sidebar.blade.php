@props(['navItems' => []])

<!-- Mobile Admin Navigation -->
<div x-data="{ isSidebarOpen: false }" class="lg:hidden">
    <!-- Top Bar -->
    <div class="sticky top-0 inset-x-0 z-30 bg-white border-y px-4 sm:px-6 md:px-8 dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center justify-between py-4">
            <!-- Navigation Toggle -->
            <button type="button" class="text-gray-500 hover:text-gray-600" @click="isSidebarOpen = !isSidebarOpen" aria-label="Toggle navigation">
                <span class="sr-only">Toggle Navigation</span>
                <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </button>
            <!-- Logo -->
            <a class="flex-none text-xl font-semibold dark:text-white" href="{{ route('admin.dashboard') }}" aria-label="Brand">
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
         class="fixed inset-0 z-40 bg-gray-900 bg-opacity-50"
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
        <div class="px-6">
            <a class="flex-none text-xl font-semibold dark:text-white" href="{{ route('admin.dashboard') }}" aria-label="Brand">
                <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
            </a>
        </div>

        <nav class="p-6 w-full flex flex-col flex-wrap h-auto md:h-[calc(100vh-80px)]" x-data="{isMobileMenuOpen: false}">
            <div class="flex-grow">
                <!-- Main Navigation -->
                <div class="mb-8">
                    <p class="px-2.5 text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">{{ __('Main') }}</p>
                    <ul class="space-y-1.5">
                        <li>
                            <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-900 dark:text-gray-400 dark:hover:text-slate-300 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 dark:bg-gray-900 dark:text-white' : '' }}" href="{{ route('admin.dashboard') }}">
                                <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                {{ __('Dashboard') }}
                            </a>
                        </li>

                        <li>
                            <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-900 dark:text-gray-400 dark:hover:text-slate-300 {{ request()->routeIs('admin.users.*') ? 'bg-gray-100 dark:bg-gray-900 dark:text-white' : '' }}" href="{{ route('admin.users.index') }}">
                                <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                {{ __('Users') }}
                            </a>
                        </li>

                        <li>
                            <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-900 dark:text-gray-400 dark:hover:text-slate-300 {{ request()->routeIs('admin.folders.*') ? 'bg-gray-100 dark:bg-gray-900 dark:text-white' : '' }}" href="{{ route('admin.folders.index') }}">
                                <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.93a2 2 0 0 1-1.66-.9l-.82-1.2A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13c0 1.1.9 2 2 2Z"/></svg>
                                {{ __('Folders') }}
                            </a>
                        </li>

                        <li>
                            <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-900 dark:text-gray-400 dark:hover:text-slate-300 {{ request()->routeIs('admin.companies.*') ? 'bg-gray-100 dark:bg-gray-900 dark:text-white' : '' }}" href="{{ route('admin.companies.index') }}">
                                <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                                {{ __('Companies') }}
                            </a>
                        </li>

                        <li>
                            <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-900 dark:text-gray-400 dark:hover:text-slate-300 {{ request()->routeIs('admin.tax-calendar.*') ? 'bg-gray-100 dark:bg-gray-900 dark:text-white' : '' }}" href="{{ route('admin.tax-calendar.index') }}">
                                <svg class="flex-shrink-0 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ __('Tax Calendar') }}
                            </a>
                        </li>
                    </ul>
                </div>

            <!-- Bottom Actions -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                <ul class="space-y-1.5">
                    <!-- Settings -->
                    <li>
                        <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-900 dark:text-gray-400 dark:hover:text-slate-300 {{ request()->routeIs('admin.settings') ? 'bg-gray-100 dark:bg-gray-900 dark:text-white' : '' }}" href="{{ url('/admin/settings') }}">
                            <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                            {{ __('Settings') }}
                        </a>
                    </li>
                    <!-- Dark Mode Toggle -->
                    <li>
                        <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-900 dark:text-gray-400 dark:hover:text-slate-300" href="#" onclick="document.getElementById('darkModeToggle').click(); return false;">
                            <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
                            <span class="dark:hidden">{{ __('Dark Mode') }}</span>
                            <span class="hidden dark:inline">{{ __('Light Mode') }}</span>
                        </a>
                    </li>
                    <!-- Logout -->
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-red-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-900 dark:text-red-400 dark:hover:text-red-300">
                                <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>

<!-- Desktop Admin Sidebar -->
<div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-56 lg:flex-col">
    <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-white border-r border-gray-200 px-6 pt-7 pb-10 dark:bg-gray-800 dark:border-gray-700">
        <!-- Logo -->
        <div>
            <a class="flex-none text-xl font-semibold dark:text-white" href="{{ route('admin.dashboard') }}" aria-label="Brand">
                <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
            </a>
        </div>

        <!-- Navigation -->
        <nav class="mt-6 w-full flex flex-col flex-wrap h-auto md:h-[calc(100vh-80px)]">
            <div class="flex-1 overflow-y-auto">
                <!-- Main Navigation -->
                <div class="mb-8">
                    <p class="px-2.5 text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">{{ __('Main') }}</p>
                    <ul class="space-y-1.5">
                        <li>
                            <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-900 dark:text-gray-400 dark:hover:text-slate-300 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 dark:bg-gray-900 dark:text-white' : '' }}" href="{{ route('admin.dashboard') }}">
                                <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                {{ __('Dashboard') }}
                            </a>
                        </li>

                        <li>
                            <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-900 dark:text-gray-400 dark:hover:text-slate-300 {{ request()->routeIs('admin.users.*') ? 'bg-gray-100 dark:bg-gray-900 dark:text-white' : '' }}" href="{{ route('admin.users.index') }}">
                                <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                {{ __('Users') }}
                            </a>
                        </li>

                        <li>
                            <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-900 dark:text-gray-400 dark:hover:text-slate-300 {{ request()->routeIs('admin.folders.*') ? 'bg-gray-100 dark:bg-gray-900 dark:text-white' : '' }}" href="{{ route('admin.folders.index') }}">
                                <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.93a2 2 0 0 1-1.66-.9l-.82-1.2A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13c0 1.1.9 2 2 2Z"/></svg>
                                {{ __('Folders') }}
                            </a>
                        </li>

                        <li>
                            <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-900 dark:text-gray-400 dark:hover:text-slate-300 {{ request()->routeIs('admin.companies.*') ? 'bg-gray-100 dark:bg-gray-900 dark:text-white' : '' }}" href="{{ route('admin.companies.index') }}">
                                <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                                {{ __('Companies') }}
                            </a>
                        </li>

                        <li>
                            <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-900 dark:text-gray-400 dark:hover:text-slate-300 {{ request()->routeIs('admin.tax-calendar.*') ? 'bg-gray-100 dark:bg-gray-900 dark:text-white' : '' }}" href="{{ route('admin.tax-calendar.index') }}">
                                <svg class="flex-shrink-0 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ __('Tax Calendar') }}
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Bottom Actions Section -->
            </div>
            
            <!-- Bottom Actions -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                <ul class="space-y-1.5">
                    <!-- Settings -->
                    <li>
                        <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-900 dark:text-gray-400 dark:hover:text-slate-300 {{ request()->routeIs('admin.settings') ? 'bg-gray-100 dark:bg-gray-900 dark:text-white' : '' }}" href="{{ url('/admin/settings') }}">
                            <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                            {{ __('Settings') }}
                        </a>
                    </li>
                    <!-- Dark Mode Toggle -->
                    <li>
                        <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-900 dark:text-gray-400 dark:hover:text-slate-300" href="#" onclick="document.getElementById('darkModeToggle').click(); return false;">
                            <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
                            <span class="dark:hidden">{{ __('Dark Mode') }}</span>
                            <span class="hidden dark:inline">{{ __('Light Mode') }}</span>
                        </a>
                    </li>
                    <!-- Logout -->
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-red-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-900 dark:text-red-400 dark:hover:text-red-300">
                                <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>
