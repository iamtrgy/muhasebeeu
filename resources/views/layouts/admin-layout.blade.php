@props([
    'title' => null,
    'breadcrumbs' => [],
])

<x-ui.layout.app>
    <x-slot name="title">{{ $title ?? config('app.name', 'Laravel') }} - Admin</x-slot>
    
    <!-- Sidebar Navigation -->
    <x-slot name="sidebar">
        <x-ui.layout.sidebar>
        <!-- Dashboard Group -->
        <x-ui.layout.sidebar-group label="Dashboard" :open="true">
            <x-ui.layout.sidebar-item 
                href="{{ route('admin.dashboard') }}" 
                :active="request()->routeIs('admin.dashboard')"
                icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>'
            >
                Dashboard
            </x-ui.layout.sidebar-item>
        </x-ui.layout.sidebar-group>

        <!-- User Management Group -->
        <x-ui.layout.sidebar-group label="User Management">
            <x-ui.layout.sidebar-item 
                href="{{ route('admin.users.index') }}" 
                :active="request()->routeIs('admin.users.*')"
                icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>'
            >
                Users
            </x-ui.layout.sidebar-item>
        </x-ui.layout.sidebar-group>

        <!-- Company Management Group -->
        <x-ui.layout.sidebar-group label="Company Management">
            <x-ui.layout.sidebar-item 
                href="{{ route('admin.companies.index') }}" 
                :active="request()->routeIs('admin.companies.*')"
                icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>'
            >
                Companies
            </x-ui.layout.sidebar-item>
            
            <x-ui.layout.sidebar-item 
                href="{{ route('admin.companies.duplicates') }}" 
                :active="request()->routeIs('admin.companies.duplicates')"
                icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>'
            >
                Duplicates
            </x-ui.layout.sidebar-item>
        </x-ui.layout.sidebar-group>

        <!-- File Management Group -->
        <x-ui.layout.sidebar-group label="File Management">
            <x-ui.layout.sidebar-item 
                href="{{ route('admin.folders.index') }}" 
                :active="request()->routeIs('admin.folders.*')"
                icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                </svg>'
            >
                Folders
            </x-ui.layout.sidebar-item>
        </x-ui.layout.sidebar-group>

        <!-- Tax Calendar Group -->
        <x-ui.layout.sidebar-group label="Tax Calendar">
            <x-ui.layout.sidebar-item 
                href="{{ route('admin.tax-calendar.index') }}" 
                :active="request()->routeIs('admin.tax-calendar.*')"
                icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>'
            >
                Tax Calendar
            </x-ui.layout.sidebar-item>
        </x-ui.layout.sidebar-group>

        <!-- Settings Group -->
        <x-ui.layout.sidebar-group label="Settings">
            <x-ui.layout.sidebar-item 
                href="{{ route('admin.settings') }}" 
                :active="request()->routeIs('admin.settings*')"
                icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>'
            >
                Settings
            </x-ui.layout.sidebar-item>
        </x-ui.layout.sidebar-group>
        </x-ui.layout.sidebar>
    </x-slot>

    <!-- Header with breadcrumbs -->
    <x-slot name="header">
        <x-ui.layout.header>
        @if(!empty($breadcrumbs))
            <x-slot name="breadcrumb">
                <x-ui.breadcrumb.base>
                    @foreach($breadcrumbs as $index => $breadcrumb)
                        <x-ui.breadcrumb.item 
                            :href="$breadcrumb['url'] ?? null"
                            :first="$index === 0"
                            :active="$loop->last"
                        >
                            {{ $breadcrumb['title'] }}
                        </x-ui.breadcrumb.item>
                    @endforeach
                </x-ui.breadcrumb.base>
            </x-slot>
        @endif
        </x-ui.layout.header>
    </x-slot>

    <!-- Main Content -->
    {{ $slot }}

    <!-- Footer -->
    <x-ui.layout.footer />
</x-ui.layout.app>