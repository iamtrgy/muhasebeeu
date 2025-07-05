@props([
    'sticky' => true,
])

<header @class([
    'bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 shadow-sm',
    'sticky top-0 z-30' => $sticky,
])>
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Left Section -->
            <div class="flex items-center">
                <!-- Mobile menu button -->
                <button 
                    @click="sidebarOpen = !sidebarOpen"
                    class="lg:hidden p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-800 transition-colors mr-4"
                >
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Breadcrumb or Title -->
                @if(isset($breadcrumb))
                    <div class="flex items-center">
                        {{ $breadcrumb }}
                    </div>
                @elseif(isset($title))
                    <div class="flex items-center">
                        <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
                            {{ $title }}
                        </h1>
                    </div>
                @endif
            </div>

            <!-- Center Section -->
            @if(isset($center))
                <div class="hidden md:block flex-1 max-w-md mx-8">
                    {{ $center }}
                </div>
            @endif

            <!-- Right Section -->
            <div class="flex items-center space-x-3">
                <!-- Search -->
                @if(isset($search))
                    <div class="hidden md:block">
                        {{ $search }}
                    </div>
                @endif

                <!-- Actions -->
                @if(isset($actions))
                    {{ $actions }}
                @endif

                <!-- Notifications -->
                @if(isset($notifications))
                    <x-ui.dropdown.base align="right">
                        <x-slot name="trigger">
                            <button 
                                x-on:click="toggle()"
                                class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-800 rounded-lg transition-colors"
                            >
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                @if(isset($notificationCount) && $notificationCount > 0)
                                    <span class="absolute -top-1 -right-1 h-5 w-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">
                                        {{ $notificationCount > 9 ? '9+' : $notificationCount }}
                                    </span>
                                @endif
                            </button>
                        </x-slot>
                        
                        <x-slot name="content">
                            <div class="w-80">
                                {{ $notifications }}
                            </div>
                        </x-slot>
                    </x-ui.dropdown.base>
                @else
                    <!-- Default notification bell -->
                    <button class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-800 rounded-lg transition-colors">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </button>
                @endif

                <!-- Dark Mode Toggle -->
                <button 
                    @click="darkMode = !darkMode"
                    class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-800 rounded-lg transition-colors"
                    title="Toggle dark mode"
                >
                    <svg x-show="!darkMode" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <svg x-show="darkMode" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </button>

                <!-- User Menu -->
                @if(isset($userMenu))
                    {{ $userMenu }}
                @else
                    <x-ui.dropdown.base align="right">
                        <x-slot name="trigger">
                            <button x-on:click="toggle()" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <x-ui.avatar size="sm" name="{{ auth()->user()->name ?? 'User' }}" />
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </x-slot>
                        
                        <x-slot name="content">
                            <div class="py-1">
                                <x-ui.dropdown.header>
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ auth()->user()->name ?? 'User' }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ auth()->user()->email ?? 'user@example.com' }}
                                    </div>
                                </x-ui.dropdown.header>
                                
                                <x-ui.dropdown.divider />
                                
                                @if(auth()->user()->is_accountant)
                                    <x-ui.dropdown.item href="{{ route('accountant.profile.edit') }}">
                                        <x-slot name="icon">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </x-slot>
                                        Your Profile
                                    </x-ui.dropdown.item>
                                    
                                    <x-ui.dropdown.item href="{{ route('accountant.settings') }}">
                                        <x-slot name="icon">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </x-slot>
                                        Settings
                                    </x-ui.dropdown.item>
                                @elseif(auth()->user()->is_admin)
                                    <x-ui.dropdown.item href="{{ route('admin.settings') }}">
                                        <x-slot name="icon">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </x-slot>
                                        Your Profile
                                    </x-ui.dropdown.item>
                                    
                                    <x-ui.dropdown.item href="{{ route('admin.settings') }}">
                                        <x-slot name="icon">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </x-slot>
                                        Settings
                                    </x-ui.dropdown.item>
                                @else
                                    <x-ui.dropdown.item href="{{ route('profile.edit') }}">
                                        <x-slot name="icon">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </x-slot>
                                        Your Profile
                                    </x-ui.dropdown.item>
                                @endif
                                
                                <x-ui.dropdown.divider />
                                
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-ui.dropdown.item type="submit">
                                        <x-slot name="icon">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                        </x-slot>
                                        Sign out
                                    </x-ui.dropdown.item>
                                </form>
                            </div>
                        </x-slot>
                    </x-ui.dropdown.base>
                @endif
            </div>
        </div>
    </div>
</header>