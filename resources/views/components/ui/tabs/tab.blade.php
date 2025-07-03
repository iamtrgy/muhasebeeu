@props([
    'name',
    'label' => null,
    'icon' => null,
    'badge' => null,
    'variant' => 'underline', // underline, pills, bordered
])

@php
$baseClasses = 'group relative min-w-0 flex-1 overflow-hidden py-2 px-4 text-sm font-medium text-center focus:z-10 transition-all duration-150';

$variantStyles = [
    'underline' => [
        'inactive' => 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200',
        'active' => 'text-indigo-600 dark:text-indigo-400',
        'indicator' => 'absolute inset-x-0 bottom-0 h-0.5 bg-indigo-600 dark:bg-indigo-400',
    ],
    'pills' => [
        'inactive' => 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200',
        'active' => 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 shadow-sm',
        'rounded' => 'rounded-md',
    ],
    'bordered' => [
        'inactive' => 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 border-transparent',
        'active' => 'text-gray-700 dark:text-gray-200 border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800',
        'border' => 'border rounded-t-lg -mb-px',
    ],
];

$styles = $variantStyles[$variant] ?? $variantStyles['underline'];
@endphp

<button
    type="button"
    role="tab"
    data-tab="{{ $name }}"
    :aria-selected="activeTab === '{{ $name }}'"
    @click="activeTab = '{{ $name }}'"
    :class="{
        '{{ $styles['active'] }}': activeTab === '{{ $name }}',
        '{{ $styles['inactive'] }}': activeTab !== '{{ $name }}',
        '{{ $styles['rounded'] ?? '' }}': true,
        '{{ $styles['border'] ?? '' }}': true,
    }"
    {{ $attributes->merge(['class' => $baseClasses]) }}
>
    <span class="flex items-center justify-center">
        @if($icon)
            <span class="mr-2">{{ $icon }}</span>
        @endif
        
        {{ $label ?? $slot }}
        
        @if($badge)
            <span class="ml-2">{{ $badge }}</span>
        @endif
    </span>
    
    @if($variant === 'underline')
        <span 
            x-show="activeTab === '{{ $name }}'"
            class="{{ $styles['indicator'] }}"
        ></span>
    @endif
</button>