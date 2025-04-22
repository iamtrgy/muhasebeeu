@php
$dashboardIcon = '<svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>';

$companiesIcon = '<svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>';

$taskReviewsIcon = '<svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><path d="M14 2v6h6"></path><path d="M9 13h6"></path><path d="M9 17h6"></path><path d="M9 9h1"></path></svg>';

$profileIcon = '<svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>';
@endphp

<x-sidebar.base :dashboardRoute="route('accountant.dashboard')">
    <x-slot:mainNavigation>
        <x-sidebar.nav-item :route="route('accountant.dashboard')" routePattern="accountant.dashboard" :icon="$dashboardIcon">
            {{ __('Dashboard') }}
        </x-sidebar.nav-item>
        
        <x-sidebar.nav-item 
            :route="route('accountant.tax-calendar.reviews')" 
            routePattern="accountant.tax-calendar.reviews*" 
            :icon="$taskReviewsIcon"
            :badge="isset($pendingReviewCount) && $pendingReviewCount > 0 ? $pendingReviewCount : null">
            {{ __('Task Reviews') }}
        </x-sidebar.nav-item>
    </x-slot:mainNavigation>
    
    <x-slot:additionalNavigation>
        <x-sidebar.nav-item :route="route('accountant.companies.index')" routePattern="accountant.companies.*" :icon="$companiesIcon">
            {{ __('Companies') }}
        </x-sidebar.nav-item>
        
        <x-sidebar.nav-item :route="route('accountant.profile.edit')" routePattern="accountant.profile.*" :icon="$profileIcon">
            {{ __('Profile') }}
        </x-sidebar.nav-item>
    </x-slot:additionalNavigation>
</x-sidebar.base>
