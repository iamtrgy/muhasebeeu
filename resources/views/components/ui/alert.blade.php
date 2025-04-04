@props(['type' => 'info', 'dismissible' => false, 'title' => null])

@php
    $types = [
        'info' => [
            'wrapper' => 'bg-blue-50 dark:bg-blue-900/20 border-blue-400 dark:border-blue-800 text-blue-700 dark:text-blue-300',
            'icon' => 'text-blue-400 dark:text-blue-300',
            'title' => 'text-blue-800 dark:text-blue-300',
            'content' => 'text-blue-700 dark:text-blue-200',
        ],
        'success' => [
            'wrapper' => 'bg-green-50 dark:bg-green-900/20 border-green-400 dark:border-green-800 text-green-700 dark:text-green-300',
            'icon' => 'text-green-400 dark:text-green-300',
            'title' => 'text-green-800 dark:text-green-300',
            'content' => 'text-green-700 dark:text-green-200',
        ],
        'warning' => [
            'wrapper' => 'bg-yellow-50 dark:bg-yellow-900/20 border-yellow-400 dark:border-yellow-800 text-yellow-700 dark:text-yellow-300',
            'icon' => 'text-yellow-400 dark:text-yellow-300',
            'title' => 'text-yellow-800 dark:text-yellow-300',
            'content' => 'text-yellow-700 dark:text-yellow-200',
        ],
        'error' => [
            'wrapper' => 'bg-red-50 dark:bg-red-900/20 border-red-400 dark:border-red-800 text-red-700 dark:text-red-300',
            'icon' => 'text-red-400 dark:text-red-300',
            'title' => 'text-red-800 dark:text-red-300',
            'content' => 'text-red-700 dark:text-red-200',
        ],
    ];

    $icons = [
        'info' => '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
        'success' => '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
        'warning' => '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>',
        'error' => '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
    ];
@endphp

<div class="rounded-md border p-4 {{ $types[$type]['wrapper'] }}" {{ $attributes }}>
    <div class="flex">
        <div class="flex-shrink-0">
            <div class="{{ $types[$type]['icon'] }}">
                {!! $icons[$type] !!}
            </div>
        </div>
        <div class="ml-3 flex-1">
            @if($title)
                <h3 class="text-sm font-medium {{ $types[$type]['title'] }}">{{ $title }}</h3>
            @endif
            <div class="text-sm {{ $types[$type]['content'] }} mt-2">
                {{ $slot }}
            </div>
        </div>
        @if($dismissible)
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button type="button" onclick="this.parentElement.parentElement.parentElement.remove()" class="inline-flex rounded-md p-1.5 {{ $types[$type]['icon'] }} hover:bg-{{ substr($type, 0, 1) === 'i' ? 'blue' : $type }}-100 dark:hover:bg-{{ substr($type, 0, 1) === 'i' ? 'blue' : $type }}-900/40 focus:outline-none focus:ring-2 focus:ring-{{ substr($type, 0, 1) === 'i' ? 'blue' : $type }}-500 dark:focus:ring-{{ substr($type, 0, 1) === 'i' ? 'blue' : $type }}-400 focus:ring-offset-2">
                        <span class="sr-only">Dismiss</span>
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>
