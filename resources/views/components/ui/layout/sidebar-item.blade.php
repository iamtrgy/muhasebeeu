@props([
    'href' => '#',
    'active' => false,
    'icon' => null,
    'badge' => null,
    'badgeType' => 'primary', // primary, success, warning, danger
])

@php
$badgeClasses = [
    'primary' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-200',
    'success' => 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200',
    'warning' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-200',
    'danger' => 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200',
];
@endphp

<a 
    href="{{ $href }}"
    @class([
        'group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 relative',
        'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-200 shadow-sm' => $active,
        'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white' => !$active,
    ])
>
    @if($icon)
        <span @class([
            'flex-shrink-0 mr-3',
            'text-indigo-600 dark:text-indigo-300' => $active,
            'text-gray-400 dark:text-gray-500 group-hover:text-gray-600 dark:group-hover:text-gray-300' => !$active,
        ])>
            {!! $icon !!}
        </span>
    @endif
    
    <span class="flex-1 text-left">
        {{ $slot }}
    </span>
    
    @if($badge)
        <span @class([
            'ml-auto px-2 py-0.5 text-xs font-medium rounded-full',
            $badgeClasses[$badgeType] ?? $badgeClasses['primary']
        ])>
            {{ $badge }}
        </span>
    @endif
    
    <!-- Active indicator -->
    @if($active)
        <span class="absolute left-0 top-1/2 transform -translate-y-1/2 w-1 h-6 bg-indigo-600 dark:bg-indigo-400 rounded-r-full"></span>
    @endif
</a>