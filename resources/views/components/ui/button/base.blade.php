@props([
    'variant' => 'primary', // primary, secondary, danger, success, warning
    'size' => 'md',        // sm, md, lg
    'type' => 'button',    // button, submit, reset
    'disabled' => false,
    'fullWidth' => false,
])

@php
$baseClasses = 'inline-flex items-center justify-center font-semibold uppercase tracking-widest transition ease-in-out duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';

$sizeClasses = [
    'sm' => 'px-3 py-1.5 text-xs rounded',
    'md' => 'px-4 py-2 text-xs rounded-md',
    'lg' => 'px-6 py-3 text-sm rounded-md',
];

$variantClasses = [
    'primary' => 'bg-indigo-600 border border-transparent text-white hover:bg-indigo-700 active:bg-indigo-800 focus:ring-indigo-500 dark:focus:ring-offset-gray-800',
    'secondary' => 'bg-white border border-gray-300 text-gray-700 shadow-sm hover:bg-gray-50 active:bg-gray-100 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 dark:focus:ring-offset-gray-800',
    'danger' => 'bg-red-600 border border-transparent text-white hover:bg-red-700 active:bg-red-800 focus:ring-red-500 dark:focus:ring-offset-gray-800',
    'success' => 'bg-emerald-600 border border-transparent text-white hover:bg-emerald-700 active:bg-emerald-800 focus:ring-emerald-500 dark:focus:ring-offset-gray-800',
    'warning' => 'bg-amber-600 border border-transparent text-white hover:bg-amber-700 active:bg-amber-800 focus:ring-amber-500 dark:focus:ring-offset-gray-800',
];

$widthClass = $fullWidth ? 'w-full' : '';

$classes = implode(' ', [
    $baseClasses,
    $sizeClasses[$size] ?? $sizeClasses['md'],
    $variantClasses[$variant] ?? $variantClasses['primary'],
    $widthClass,
]);
@endphp

<button 
    type="{{ $type }}"
    {{ $disabled ? 'disabled' : '' }}
    {{ $attributes->merge(['class' => $classes]) }}
>
    {{ $slot }}
</button>