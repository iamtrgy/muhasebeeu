@props([
    'paginator',
    'size' => 'md', // sm, md, lg
])

@php
$sizeClasses = [
    'sm' => 'px-3 py-1 text-xs',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-6 py-3 text-base',
];

$buttonSize = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex justify-center">
        <div class="flex space-x-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center {{ $buttonSize }} font-medium text-gray-400 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 cursor-not-allowed rounded-md">
                    {{ __('Previous') }}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center {{ $buttonSize }} font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors duration-150">
                    {{ __('Previous') }}
                </a>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center {{ $buttonSize }} font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors duration-150">
                    {{ __('Next') }}
                </a>
            @else
                <span class="relative inline-flex items-center {{ $buttonSize }} font-medium text-gray-400 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 cursor-not-allowed rounded-md">
                    {{ __('Next') }}
                </span>
            @endif
        </div>
    </nav>
@endif