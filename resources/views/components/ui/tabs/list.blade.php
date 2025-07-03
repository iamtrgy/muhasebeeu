@props([
    'variant' => 'underline', // underline, pills, bordered
])

@php
$baseClasses = 'flex space-x-1';

$variantClasses = [
    'underline' => 'border-b border-gray-200 dark:border-gray-700',
    'pills' => 'bg-gray-100 dark:bg-gray-800 p-1 rounded-lg',
    'bordered' => '',
];

$classes = trim($baseClasses . ' ' . ($variantClasses[$variant] ?? $variantClasses['underline']));
@endphp

<div {{ $attributes->merge(['class' => $classes, 'role' => 'tablist']) }}>
    {{ $slot }}
</div>