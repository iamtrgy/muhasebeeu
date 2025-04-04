@props(['type' => 'default', 'size' => 'sm'])

@php
    $baseClasses = 'inline-flex font-semibold rounded-full';
    
    $sizeClasses = match ($size) {
        'xs' => 'px-2 py-0.5 text-xs leading-4',
        'sm' => 'px-2 py-1 text-xs leading-5',
        'md' => 'px-3 py-1 text-sm leading-5',
        default => 'px-2 py-1 text-xs leading-5'
    };
    
    $typeClasses = match ($type) {
        'success' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        'info' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        'warning' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
        'danger' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
        'primary' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
        'secondary' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
        default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
    };
    
    $classes = $baseClasses . ' ' . $sizeClasses . ' ' . $typeClasses;
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
