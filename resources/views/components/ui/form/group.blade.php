@props([
    'spacing' => 'md', // sm, md, lg
])

@php
$spacingClasses = [
    'sm' => 'space-y-4',
    'md' => 'space-y-6',
    'lg' => 'space-y-8',
];

$classes = $spacingClasses[$spacing] ?? $spacingClasses['md'];
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>