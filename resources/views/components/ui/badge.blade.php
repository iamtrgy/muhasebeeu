@props([
    'variant' => 'default', // default, primary, secondary, success, warning, danger
    'size' => 'md', // sm, md, lg
    'rounded' => 'md', // sm, md, lg, full
    'dot' => false,
    'removable' => false,
])

@php
$sizeClasses = [
    'sm' => 'px-2 py-0.5 text-xs',
    'md' => 'px-2.5 py-0.5 text-sm',
    'lg' => 'px-3 py-1 text-base',
];

$roundedClasses = [
    'sm' => 'rounded',
    'md' => 'rounded-md',
    'lg' => 'rounded-lg',
    'full' => 'rounded-full',
];

$variantClasses = [
    'default' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
    'primary' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400',
    'secondary' => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400',
    'success' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400',
    'warning' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400',
    'danger' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
];

$baseClasses = 'inline-flex items-center font-medium';
$sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
$roundedClass = $roundedClasses[$rounded] ?? $roundedClasses['md'];
$variantClass = $variantClasses[$variant] ?? $variantClasses['default'];

$classes = implode(' ', [$baseClasses, $sizeClass, $roundedClass, $variantClass]);
@endphp

<span 
    @if($removable)
        x-data="{ show: true }"
        x-show="show"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    @endif
    {{ $attributes->merge(['class' => $classes]) }}
>
    @if($dot)
        <svg class="-ml-0.5 mr-1.5 h-2 w-2" fill="currentColor" viewBox="0 0 8 8">
            <circle cx="4" cy="4" r="3" />
        </svg>
    @endif
    
    {{ $slot }}
    
    @if($removable)
        <button
            type="button"
            @click="show = false"
            class="ml-1 -mr-0.5 inline-flex flex-shrink-0 h-4 w-4 items-center justify-center rounded-full hover:bg-gray-200 dark:hover:bg-gray-600 hover:text-gray-500 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
        >
            <span class="sr-only">Remove</span>
            <svg class="h-2 w-2" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                <path stroke-linecap="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7" />
            </svg>
        </button>
    @endif
</span>