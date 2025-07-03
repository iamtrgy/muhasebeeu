@props([
    'align' => 'right',
    'compact' => false,
])

@php
$paddingClasses = $compact ? 'px-4 py-2' : 'px-6 py-4';

$classes = collect([
    $paddingClasses,
    'whitespace-nowrap text-sm font-medium',
])->filter()->join(' ');

$alignmentClass = match($align) {
    'left' => 'justify-start',
    'center' => 'justify-center',
    'right' => 'justify-end',
    default => 'justify-end'
};
@endphp

<td class="{{ $classes }}" {{ $attributes }}>
    <div class="flex items-center {{ $alignmentClass }} space-x-2">
        {{ $slot }}
    </div>
</td>