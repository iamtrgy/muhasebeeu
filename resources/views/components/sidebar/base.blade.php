<div class="px-6">
    <a class="flex-none text-xl font-semibold dark:text-white" href="{{ $dashboardRoute }}" aria-label="Brand">
        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
    </a>
</div>

<nav class="p-6 w-full flex flex-col flex-wrap h-auto md:h-[calc(100vh-80px)]" x-data="{isMobileMenuOpen: false}">
    <div class="flex-grow">
        <!-- Main Navigation -->
        <div class="mb-8">
            <p class="px-2.5 text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">{{ __('Main') }}</p>
            <ul class="space-y-1.5">
                {{ $mainNavigation }}
            </ul>
        </div>

        <!-- Additional Navigation (if provided) -->
        @isset($additionalNavigation)
        <div class="mb-4 mt-8">
            <p class="px-2.5 text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">{{ __('Additional') }}</p>
            <ul class="space-y-1.5">
                {{ $additionalNavigation }}
            </ul>
        </div>
        @endisset

        <!-- Management Navigation (if provided) -->
        @isset($managementNavigation)
        <div class="mb-4 mt-8">
            <p class="px-2.5 text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">{{ __('Management') }}</p>
            <ul class="space-y-1.5">
                {{ $managementNavigation }}
            </ul>
        </div>
        @endisset
    </div>

    <!-- Footer Navigation with Settings and Logout -->
    <div class="mt-auto border-t border-gray-200 dark:border-gray-700 pt-4">
        <ul class="space-y-1.5">
            @isset($footerNavigation)
                {{ $footerNavigation }}
            @endisset
            
            <!-- Settings Link -->
            <li>
                <a href="{{ route('user.profile.edit') }}" class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-900 dark:text-gray-400 dark:hover:text-slate-300 {{ request()->routeIs('user.profile.*') ? 'bg-gray-100 dark:bg-gray-900 dark:text-white' : '' }}">
                    <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    {{ __('Settings') }}
                </a>
            </li>
            
            <!-- Logout Link -->
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-900 dark:text-gray-400 dark:hover:text-slate-300">
                        <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        {{ __('Logout') }}
                    </a>
                </form>
            </li>
        </ul>
    </div>
</nav>
