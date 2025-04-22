<?php
// User Sidebar
?>
<div class="px-6">
    <a class="flex-none text-xl font-semibold dark:text-white" href="{{ route('user.dashboard') }}" aria-label="Brand">
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
                    <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-900 dark:text-gray-400 dark:hover:text-slate-300 {{ request()->routeIs('user.dashboard') ? 'bg-gray-100 dark:bg-gray-900 dark:text-white' : '' }}" href="{{ route('user.dashboard') }}">
                        <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        {{ __('Home') }}
                    </a>
                </li>

                <li>
                    <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-900 dark:text-gray-400 dark:hover:text-slate-300 {{ request()->routeIs('user.folders.*') ? 'bg-gray-100 dark:bg-gray-900 dark:text-white' : '' }}" href="{{ route('user.folders.index') }}">
                        <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.93a2 2 0 0 1-1.66-.9l-.82-1.2A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13c0 1.1.9 2 2 2Z"/></svg>
                        {{ __('Folders') }}
                    </a>
                </li>

                <li>
                    <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-900 dark:text-gray-400 dark:hover:text-slate-300 {{ request()->routeIs('user.invoices.*') ? 'bg-gray-100 dark:bg-gray-900 dark:text-white' : '' }}" href="{{ route('user.invoices.index') }}">
                        <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/></svg>
                        {{ __('Invoices') }}
                    </a>
                </li>

                <li>
                    <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-900 dark:text-gray-400 dark:hover:text-slate-300 {{ request()->routeIs('user.tax-calendar.*') ? 'bg-gray-100 dark:bg-gray-900 dark:text-white' : '' }}" href="{{ route('user.tax-calendar.index') }}">
                        <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        {{ __('Tax Calendar') }}
                    </a>
                </li>
            </ul>
        </div>

        <!-- Additional Links Section -->
        <div class="mb-4 mt-8">
            <p class="px-2.5 text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">{{ __('Additional') }}</p>
            <ul class="space-y-1.5">
                <li>
                    <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-900 dark:text-gray-400 dark:hover:text-slate-300 {{ request()->routeIs('user.clients.*') ? 'bg-gray-100 dark:bg-gray-900 dark:text-white' : '' }}" href="{{ route('user.clients.index') }}">
                        <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        {{ __('Clients') }}
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Bottom Actions -->
    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
        <ul class="space-y-1.5">
            <!-- Company -->
            <li>
                <a href="{{ route('user.companies.index') }}" 
                   class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-900 dark:text-gray-400 dark:hover:text-gray-300 {{ request()->routeIs('user.companies.*') ? 'bg-gray-100 dark:bg-gray-900 dark:text-white' : '' }}">
                    <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    @php
                        $companies = auth()->user()->companies;
                        $companyCount = $companies->count();
                    @endphp
                    @if($companyCount === 1)
                        {{ $companies->first()->name }}
                    @else
                        {{ $companyCount > 0 ? $companyCount . ' Companies' : 'Companies' }}
                    @endif
                </a>
            </li>

            <!-- Settings -->
            <li>
                <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-900 dark:text-gray-400 dark:hover:text-slate-300 {{ request()->routeIs('user.profile.edit') ? 'bg-gray-100 dark:bg-gray-900 dark:text-white' : '' }}" href="{{ route('user.profile.edit') }}">
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
