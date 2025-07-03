@props([
    'separator' => 'chevron', // chevron, slash, arrow
])

@php
$separators = [
    'chevron' => '<svg class="flex-shrink-0 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
    </svg>',
    'slash' => '<svg class="flex-shrink-0 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
        <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
    </svg>',
    'arrow' => '<svg class="flex-shrink-0 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
        <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd" />
    </svg>',
];

$separatorIcon = $separators[$separator] ?? $separators['chevron'];
@endphp

<nav {{ $attributes->merge(['class' => 'flex', 'aria-label' => 'Breadcrumb']) }}>
    <ol role="list" class="flex items-center space-x-4">
        {{ $slot }}
    </ol>
</nav>

@pushonce('breadcrumb-separator')
<template id="breadcrumb-separator">
    <div class="text-gray-400 dark:text-gray-500">
        {!! $separatorIcon !!}
    </div>
</template>
@endpushonce