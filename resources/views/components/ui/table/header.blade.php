@props([
    'sticky' => false,
])

@php
$classes = collect([
    'bg-gray-50 dark:bg-gray-700',
    $sticky ? 'sticky top-0 z-10' : '',
])->filter()->join(' ');
@endphp

<thead class="{{ $classes }}" {{ $attributes }}>
    {{ $slot }}
</thead>