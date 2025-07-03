@props([
    'size' => 'md', // sm, md, lg, xl
    'color' => 'primary', // primary, secondary, white, current
    'type' => 'circle', // circle, dots
    'text' => null,
    'position' => 'inline', // inline, center, overlay
])

@php
$sizeClasses = [
    'sm' => 'h-4 w-4',
    'md' => 'h-6 w-6',
    'lg' => 'h-8 w-8',
    'xl' => 'h-12 w-12',
];

$colorClasses = [
    'primary' => 'text-indigo-600 dark:text-indigo-400',
    'secondary' => 'text-gray-600 dark:text-gray-400',
    'white' => 'text-white',
    'current' => 'text-current',
];

$spinnerSize = $sizeClasses[$size] ?? $sizeClasses['md'];
$spinnerColor = $colorClasses[$color] ?? $colorClasses['primary'];

$dotSizes = [
    'sm' => 'h-1 w-1',
    'md' => 'h-1.5 w-1.5',
    'lg' => 'h-2 w-2',
    'xl' => 'h-3 w-3',
];

$dotSize = $dotSizes[$size] ?? $dotSizes['md'];
@endphp

@if($position === 'overlay')
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 transition-opacity z-50">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="inline-flex items-center gap-3">
                @if($type === 'dots')
                    <div class="inline-flex items-center gap-1">
                        <div class="{{ $dotSize }} {{ $spinnerColor }} rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                        <div class="{{ $dotSize }} {{ $spinnerColor }} rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                        <div class="{{ $dotSize }} {{ $spinnerColor }} rounded-full animate-bounce" style="animation-delay: 300ms"></div>
                    </div>
                @else
                    <svg class="animate-spin {{ $spinnerSize }} {{ $spinnerColor }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                @endif
                
                @if($text)
                    <span class="text-sm font-medium {{ $spinnerColor }}">{{ $text }}</span>
                @endif
            </div>
        </div>
    </div>
@elseif($position === 'center')
    <div class="flex items-center justify-center p-4">
        <div class="inline-flex items-center gap-3">
            @if($type === 'dots')
                <div class="inline-flex items-center gap-1">
                    <div class="{{ $dotSize }} {{ $spinnerColor }} rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                    <div class="{{ $dotSize }} {{ $spinnerColor }} rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                    <div class="{{ $dotSize }} {{ $spinnerColor }} rounded-full animate-bounce" style="animation-delay: 300ms"></div>
                </div>
            @else
                <svg class="animate-spin {{ $spinnerSize }} {{ $spinnerColor }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            @endif
            
            @if($text)
                <span class="text-sm font-medium {{ $spinnerColor }}">{{ $text }}</span>
            @endif
        </div>
    </div>
@else
    <div class="inline-flex items-center gap-3">
        @if($type === 'dots')
            <div class="inline-flex items-center gap-1">
                <div class="{{ $dotSize }} {{ $spinnerColor }} rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                <div class="{{ $dotSize }} {{ $spinnerColor }} rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                <div class="{{ $dotSize }} {{ $spinnerColor }} rounded-full animate-bounce" style="animation-delay: 300ms"></div>
            </div>
        @else
            <svg class="animate-spin {{ $spinnerSize }} {{ $spinnerColor }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @endif
        
        @if($text)
            <span class="text-sm font-medium {{ $spinnerColor }}">{{ $text }}</span>
        @endif
    </div>
@endif