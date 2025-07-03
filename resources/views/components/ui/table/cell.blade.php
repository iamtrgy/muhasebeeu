@props([
    'align' => 'left',
    'nowrap' => true,
    'compact' => false,
])

@php
$alignClasses = [
    'left' => 'text-left',
    'center' => 'text-center',
    'right' => 'text-right',
][$align] ?? 'text-left';

$paddingClasses = $compact ? 'px-4 py-2' : 'px-6 py-4';

$classes = collect([
    $paddingClasses,
    $nowrap ? 'whitespace-nowrap' : '',
    'text-sm text-gray-900 dark:text-gray-100',
    $alignClasses,
])->filter()->join(' ');
@endphp

<td class="{{ $classes }}" {{ $attributes }}>
    {{ $slot }}
</td>