@props([
    'title' => null,
    'breadcrumbs' => [],
])

<x-ui.layout.app>
    <x-slot name="title">{{ $title ?? config('app.name', 'Laravel') }} - User</x-slot>
    
    <!-- Sidebar Navigation -->
    <x-slot name="sidebar">
        <x-ui.layout.sidebar>
        <!-- Dashboard Group -->
        <x-ui.layout.sidebar-group label="Dashboard" :open="true">
            <x-ui.layout.sidebar-item 
                href="{{ route('user.dashboard') }}" 
                :active="request()->routeIs('user.dashboard')"
                icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>'
            >
                Dashboard
            </x-ui.layout.sidebar-item>
        </x-ui.layout.sidebar-group>

        <!-- Document Management Group -->
        <x-ui.layout.sidebar-group label="Document Management">
            <x-ui.layout.sidebar-item 
                href="{{ route('user.folders.index') }}" 
                :active="request()->routeIs('user.folders.*')"
                icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                </svg>'
            >
                My Documents
            </x-ui.layout.sidebar-item>
            
            <x-ui.layout.sidebar-item 
                href="{{ route('user.files.upload') }}" 
                :active="request()->routeIs('user.files.*')"
                icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>'
            >
                Upload Files
            </x-ui.layout.sidebar-item>
        </x-ui.layout.sidebar-group>

        <!-- Tax Calendar Group -->
        <x-ui.layout.sidebar-group label="Tax Calendar">
            <x-ui.layout.sidebar-item 
                href="{{ route('user.tax-calendar.index') }}" 
                :active="request()->routeIs('user.tax-calendar.*')"
                icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>'
                badge="{{ \App\Models\TaxCalendarTask::where('user_id', auth()->id())->where('status', 'pending')->count() }}"
                badgeType="info"
            >
                My Tasks
            </x-ui.layout.sidebar-item>
        </x-ui.layout.sidebar-group>

        <!-- Accounting Services Group -->
        <x-ui.layout.sidebar-group label="Services">
            <x-ui.layout.sidebar-item 
                href="{{ route('user.services.index') }}" 
                :active="request()->routeIs('user.services.*')"
                icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>'
            >
                My Services
            </x-ui.layout.sidebar-item>
        </x-ui.layout.sidebar-group>

        <!-- Account Group -->
        <x-ui.layout.sidebar-group label="Account">
            <x-ui.layout.sidebar-item 
                href="{{ route('user.profile.edit') }}" 
                :active="request()->routeIs('user.profile.*')"
                icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>'
            >
                Profile
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