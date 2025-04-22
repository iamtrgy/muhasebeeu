@props([
    'route' => '',
    'routePattern' => '',
    'icon' => '',
    'badge' => null,
])

<li>
    <a class="flex items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-900 dark:text-gray-400 dark:hover:text-slate-300 {{ request()->routeIs($routePattern) ? 'bg-gray-100 dark:bg-gray-900 dark:text-white' : '' }}" 
       href="{{ $route }}">
        
        @if(is_string($icon) && !empty($icon))
            {!! $icon !!}
        @endif
        
        {{ $slot }}
        
        @if($badge)
            <span class="ml-auto inline-flex items-center gap-x-1 py-0.5 px-2 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full dark:bg-yellow-500/10 dark:text-yellow-500">
                {{ $badge }}
            </span>
        @endif
    </a>
</li>
