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