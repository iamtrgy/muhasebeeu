@props([
    'sortable' => false,
    'sortKey' => null,
    'currentSort' => null,
    'currentDirection' => 'asc',
    'align' => 'left',
    'width' => null,
])

@php
$alignClasses = [
    'left' => 'text-left',
    'center' => 'text-center',
    'right' => 'text-right',
][$align] ?? 'text-left';

$baseClasses = 'px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider';

$classes = collect([
    $baseClasses,
    $alignClasses,
    $sortable ? 'cursor-pointer select-none' : '',
])->filter()->join(' ');

$isSorted = $sortable && $sortKey && $currentSort === $sortKey;
$nextDirection = $isSorted && $currentDirection === 'asc' ? 'desc' : 'asc';
@endphp

<th 
    scope="col"
    @if($width) style="width: {{ $width }}" @endif
    @if($sortable && $sortKey)
        wire:click="sortBy('{{ $sortKey }}')"
        x-data
        x-on:click="$dispatch('sort-table', { key: '{{ $sortKey }}', direction: '{{ $nextDirection }}' })"
    @endif
    class="{{ $classes }}"
    {{ $attributes }}
>
    <div class="flex items-center {{ $align === 'right' ? 'justify-end' : ($align === 'center' ? 'justify-center' : '') }}">
        <span>{{ $slot }}</span>
        
        @if($sortable)
            <span class="ml-2 flex-none">
                @if($isSorted)
                    @if($currentDirection === 'asc')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                        </svg>
                    @else
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    @endif
                @else
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                    </svg>
                @endif
            </span>
        @endif
    </div>
</th>