@props([
    'hoverable' => true,
    'striped' => false,
    'clickable' => false,
])

@php
$classes = collect([
    $hoverable ? 'hover:bg-gray-50 dark:hover:bg-gray-700' : '',
    $clickable ? 'cursor-pointer' : '',
    'transition-colors duration-150',
])->filter()->join(' ');
@endphp

<tr class="{{ $classes }}" {{ $attributes }}>
    {{ $slot }}
</tr>