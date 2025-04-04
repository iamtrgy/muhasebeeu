@php
    $user = auth()->user();
@endphp

@if($user && $user->is_admin)
    @include('layouts.admin-sidebar')
@elseif($user && $user->is_accountant)
    @include('layouts.accountant-sidebar')
@else
    @include('layouts.user-sidebar')
@endif