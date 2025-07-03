@props([
    'striped' => false,
])

@php
$classes = collect([
    'bg-white dark:bg-gray-800',
    'divide-y divide-gray-200 dark:divide-gray-700',
])->filter()->join(' ');
@endphp

<tbody class="{{ $classes }}" {{ $attributes }}>
    {{ $slot }}
</tbody>