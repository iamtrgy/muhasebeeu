@props([
    'type' => 'info', // info, success, warning, error/danger
    'title' => null,
    'dismissible' => false,
    'icon' => true,
])

@php
$typeStyles = [
    'info' => [
        'container' => 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800',
        'icon' => 'text-blue-400 dark:text-blue-300',
        'title' => 'text-blue-800 dark:text-blue-200',
        'text' => 'text-blue-700 dark:text-blue-300',
    ],
    'success' => [
        'container' => 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-800',
        'icon' => 'text-emerald-400 dark:text-emerald-300',
        'title' => 'text-emerald-800 dark:text-emerald-200',
        'text' => 'text-emerald-700 dark:text-emerald-300',
    ],
    'warning' => [
        'container' => 'bg-amber-50 dark:bg-amber-900/20 border-amber-200 dark:border-amber-800',
        'icon' => 'text-amber-400 dark:text-amber-300',
        'title' => 'text-amber-800 dark:text-amber-200',
        'text' => 'text-amber-700 dark:text-amber-300',
    ],
    'error' => [
        'container' => 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800',
        'icon' => 'text-red-400 dark:text-red-300',
        'title' => 'text-red-800 dark:text-red-200',
        'text' => 'text-red-700 dark:text-red-300',
    ],
    'danger' => [
        'container' => 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800',
        'icon' => 'text-red-400 dark:text-red-300',
        'title' => 'text-red-800 dark:text-red-200',
        'text' => 'text-red-700 dark:text-red-300',
    ],
];

$styles = $typeStyles[$type] ?? $typeStyles['info'];

$icons = [
    'info' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
    'success' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
    'warning' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>',
    'error' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
    'danger' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
];

$iconPath = $icons[$type] ?? $icons['info'];
@endphp

<div 
    @if($dismissible)
        x-data="{ show: true }"
        x-show="show"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    @endif
    {{ $attributes->merge(['class' => 'rounded-md border p-4 ' . $styles['container']]) }}
>
    <div class="flex">
        @if($icon)
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 {{ $styles['icon'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    {!! $iconPath !!}
                </svg>
            </div>
        @endif
        
        <div class="{{ $icon ? 'ml-3' : '' }} flex-1">
            @if($title)
                <h3 class="text-sm font-medium {{ $styles['title'] }}">
                    {{ $title }}
                </h3>
            @endif
            
            <div class="{{ $title ? 'mt-2' : '' }} text-sm {{ $styles['text'] }}">
                {{ $slot }}
            </div>
        </div>
        
        @if($dismissible)
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button
                        @click="show = false"
                        type="button"
                        class="inline-flex rounded-md p-1.5 {{ $styles['text'] }} hover:bg-opacity-20 hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        <span class="sr-only">Dismiss</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>
