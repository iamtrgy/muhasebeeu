@props([
    'text' => '',
    'position' => 'top', // top, bottom, left, right
    'trigger' => 'hover', // hover, click
    'delay' => 0,
])

@php
$positionClasses = [
    'top' => 'bottom-full left-1/2 transform -translate-x-1/2 mb-2',
    'bottom' => 'top-full left-1/2 transform -translate-x-1/2 mt-2',
    'left' => 'right-full top-1/2 transform -translate-y-1/2 mr-2',
    'right' => 'left-full top-1/2 transform -translate-y-1/2 ml-2',
];

$arrowClasses = [
    'top' => 'top-full left-1/2 transform -translate-x-1/2 -mt-1',
    'bottom' => 'bottom-full left-1/2 transform -translate-x-1/2 -mb-1 rotate-180',
    'left' => 'left-full top-1/2 transform -translate-y-1/2 -ml-1 -rotate-90',
    'right' => 'right-full top-1/2 transform -translate-y-1/2 -mr-1 rotate-90',
];
@endphp

<div 
    x-data="{ 
        open: false,
        timeout: null,
        show() {
            clearTimeout(this.timeout);
            this.timeout = setTimeout(() => {
                this.open = true;
            }, {{ $delay }});
        },
        hide() {
            clearTimeout(this.timeout);
            this.open = false;
        }
    }" 
    class="relative inline-block"
    @if($trigger === 'hover')
        @mouseenter="show()"
        @mouseleave="hide()"
    @else
        @click="open = !open"
        @click.outside="open = false"
    @endif
>
    {{-- Trigger Element --}}
    <div>
        {{ $slot }}
    </div>
    
    {{-- Tooltip --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-50 {{ $positionClasses[$position] ?? $positionClasses['top'] }}"
        style="display: none;"
        @if($trigger === 'hover')
            @mouseenter="show()"
            @mouseleave="hide()"
        @endif
    >
        <div class="bg-gray-900 dark:bg-gray-700 text-white text-sm rounded-lg py-2 px-3 shadow-lg whitespace-nowrap">
            {{ $text }}
            {{-- Arrow --}}
            <div class="absolute {{ $arrowClasses[$position] ?? $arrowClasses['top'] }}">
                <svg class="h-2 w-2 text-gray-900 dark:text-gray-700" viewBox="0 0 8 4" fill="currentColor">
                    <path d="M0 0L4 4L8 0z"/>
                </svg>
            </div>
        </div>
    </div>
</div>