@props([
    'href' => null,
    'active' => false,
    'icon' => null,
    'first' => false,
])

<li class="flex items-center">
    @unless($first)
        <!-- Separator Arrow -->
        <div class="text-gray-400 dark:text-gray-500 mx-2">
            <svg class="flex-shrink-0 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
            </svg>
        </div>
    @endunless
    
    @if($active)
        <span class="text-sm font-medium text-gray-500 dark:text-gray-400" aria-current="page">
            @if($icon)
                <span class="flex items-center">
                    <span class="flex-shrink-0 mr-1.5">{!! $icon !!}</span>
                    {{ $slot }}
                </span>
            @else
                {{ $slot }}
            @endif
        </span>
    @else
        <a href="{{ $href }}" class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors duration-150">
            @if($icon)
                <span class="flex items-center">
                    <span class="flex-shrink-0 mr-1.5">{!! $icon !!}</span>
                    {{ $slot }}
                </span>
            @else
                {{ $slot }}
            @endif
        </a>
    @endif
</li>