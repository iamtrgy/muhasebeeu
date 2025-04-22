@extends('layouts.base')

@section('title-suffix', ' - User')

@section('sidebar')
    <!-- Sidebar is included in navigation.blade.php -->
@endsection

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
    {{ $slot }}
@endsection
