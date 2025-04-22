@extends('layouts.base')

@section('title-suffix', ' - Accountant')

@section('sidebar')
    <!-- Sidebar is included in navigation.blade.php -->
@endsection

@section('main-padding', 'lg:pl-56')

@section('header')
    @php
        // Ensure breadcrumbs variable is defined
        $breadcrumbs = $breadcrumbs ?? [];
    @endphp
    @if (isset($header))
        {{ $header }}
    @endif
@endsection

@section('content')
    <main class="flex-1 py-6">
        <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8">
            {{ $slot }}
        </div>
    </main>
@endsection
