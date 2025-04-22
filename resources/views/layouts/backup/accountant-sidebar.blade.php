<?php
// Accountant Sidebar
?>
<div class="px-6">
    <a class="flex-none text-xl font-semibold dark:text-white" href="{{ route('accountant.dashboard') }}" aria-label="Brand">
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
                    <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-900 dark:text-gray-400 dark:hover:text-slate-300 {{ request()->routeIs('accountant.dashboard') ? 'bg-gray-100 dark:bg-gray-900 dark:text-white' : '' }}" href="{{ route('accountant.dashboard') }}">
                        <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        {{ __('Dashboard') }}
                    </a>
                </li>
                
                <li>
                    <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-900 dark:text-gray-400 dark:hover:text-slate-300 {{ request()->routeIs('accountant.companies.*') ? 'bg-gray-100 dark:bg-gray-900 dark:text-white' : '' }}" href="{{ route('accountant.companies.index') }}">
                        <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                        {{ __('Companies') }}
                    </a>
                </li>

                <li>
                    <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-900 dark:text-gray-400 dark:hover:text-slate-300 {{ request()->routeIs('accountant.users.*') ? 'bg-gray-100 dark:bg-gray-900 dark:text-white' : '' }}" href="{{ route('accountant.users.index') }}">
                        <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        {{ __('Users') }}
                    </a>
                </li>

                <li>
                    <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-900 dark:text-gray-400 dark:hover:text-slate-300 {{ request()->routeIs('accountant.tax-calendar.reviews*') ? 'bg-gray-100 dark:bg-gray-900 dark:text-white' : '' }}" href="{{ route('accountant.tax-calendar.reviews') }}">
                        <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><path d="M14 2v6h6"></path><path d="M9 13h6"></path><path d="M9 17h6"></path><path d="M9 9h1"></path></svg>
                        {{ __('Task Reviews') }}
                        @if(isset($pendingReviewCount) && $pendingReviewCount > 0)
                            <span class="ml-auto inline-flex items-center gap-x-1 py-0.5 px-2 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full dark:bg-yellow-500/10 dark:text-yellow-500">
                                {{ $pendingReviewCount }}
                            </span>
                        @endif
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Bottom Actions -->
    <div class="mt-auto border-t border-gray-200 dark:border-gray-700 pt-4">
        <ul class="space-y-1.5">
            <!-- Settings -->
            <li>
                <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-900 dark:text-gray-400 dark:hover:text-slate-300 {{ request()->routeIs('accountant.profile.edit') ? 'bg-gray-100 dark:bg-gray-900 dark:text-white' : '' }}" href="{{ route('accountant.profile.edit') }}">
                    <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                    {{ __('Settings') }}
                </a>
            </li>

            <!-- Logout Form -->
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-900 dark:text-gray-400 dark:hover:text-slate-300">
                        <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                        {{ __('Log Out') }}
                    </button>
                </form>
            </li>
        </ul>
    </div>
</nav>
