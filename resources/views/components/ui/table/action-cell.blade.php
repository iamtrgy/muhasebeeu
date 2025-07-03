@props([
    'align' => 'right',
    'compact' => false,
])

@php
$paddingClasses = $compact ? 'px-4 py-2' : 'px-6 py-4';

$classes = collect([
    $paddingClasses,
    'whitespace-nowrap text-sm font-medium',
    'text-right',
])->filter()->join(' ');
@endphp

<td class="{{ $classes }}" {{ $attributes }}>
    <div class="flex items-center justify-end space-x-2">
        {{ $slot }}
    </div>
</td>