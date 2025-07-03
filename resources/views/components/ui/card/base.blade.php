@props([
    'padding' => true,
    'shadow' => 'sm',
    'rounded' => 'lg',
])

@php
$shadowClasses = [
    'none' => '',
    'sm' => 'shadow-sm',
    'md' => 'shadow',
    'lg' => 'shadow-lg',
    'xl' => 'shadow-xl',
];

$roundedClasses = [
    'none' => '',
    'sm' => 'rounded',
    'md' => 'rounded-md',
    'lg' => 'rounded-lg',
    'xl' => 'rounded-xl',
];

$baseClasses = 'bg-white dark:bg-gray-800 overflow-hidden';
$paddingClass = $padding ? 'p-6' : '';
$shadowClass = $shadowClasses[$shadow] ?? $shadowClasses['sm'];
$roundedClass = $roundedClasses[$rounded] ?? $roundedClasses['lg'];

$classes = implode(' ', array_filter([
    $baseClasses,
    $shadowClass,
    $roundedClass,
]));
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    @if($padding)
        <div class="p-6">
            {{ $slot }}
        </div>
    @else
        {{ $slot }}
    @endif
</div>