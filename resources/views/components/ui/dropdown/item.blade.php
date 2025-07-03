@props([
    'href' => null,
    'type' => 'link', // link, button, submit
    'active' => false,
    'disabled' => false,
    'icon' => null,
])

@php
$baseClasses = 'block w-full text-left px-4 py-2 text-sm transition-colors duration-150';

if ($disabled) {
    $colorClasses = 'text-gray-400 dark:text-gray-500 cursor-not-allowed';
} elseif ($active) {
    $colorClasses = 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100';
} else {
    $colorClasses = 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100';
}

$classes = trim($baseClasses . ' ' . $colorClasses);
@endphp

@if($href && !$disabled)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            <span class="inline-flex items-center">
                <span class="mr-2">{{ $icon }}</span>
                {{ $slot }}
            </span>
        @else
            {{ $slot }}
        @endif
    </a>
@else
    <button
        type="{{ $type === 'submit' ? 'submit' : 'button' }}"
        @if($disabled) disabled @endif
        {{ $attributes->merge(['class' => $classes]) }}
    >
        @if($icon)
            <span class="inline-flex items-center">
                <span class="mr-2">{{ $icon }}</span>
                {{ $slot }}
            </span>
        @else
            {{ $slot }}
        @endif
    </button>
@endif