@props([
    'sortable' => false,
    'sortDirection' => null,
    'sortField' => null,
    'darkMode' => true
])

<th scope="col" {{ $attributes->merge(['class' => 'px-6 py-3 text-left text-xs font-medium text-gray-500 ' . ($darkMode ? 'dark:text-gray-300' : '') . ' uppercase tracking-wider']) }}>
    @if($sortable)
        <button type="button" class="group inline-flex items-center space-x-1">
            <span>{{ $slot }}</span>
            <span class="relative flex items-center">
                @if($sortDirection === 'asc' && $sortField === $attributes->get('wire:click'))
                    <svg class="w-3 h-3 text-gray-400" viewBox="0 0 12 12" fill="currentColor">
                        <path d="M3.5 3.5L6 1l2.5 2.5m-5 5L6 11l2.5-2.5" />
                    </svg>
                @elseif($sortDirection === 'desc' && $sortField === $attributes->get('wire:click'))
                    <svg class="w-3 h-3 text-gray-400" viewBox="0 0 12 12" fill="currentColor">
                        <path d="M3.5 8.5L6 11l2.5-2.5m-5-5L6 1l2.5 2.5" />
                    </svg>
                @else
                    <svg class="w-3 h-3 opacity-0 group-hover:opacity-100 text-gray-400" viewBox="0 0 12 12" fill="currentColor">
                        <path d="M3.5 3.5L6 1l2.5 2.5m-5 5L6 11l2.5-2.5" />
                    </svg>
                @endif
            </span>
        </button>
    @else
        {{ $slot }}
    @endif
</th>
