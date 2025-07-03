@props([
    'align' => 'right', // left, right
    'width' => '48', // 48, 60, full
    'contentClasses' => '',
    'dropdownClasses' => '',
])

@php
$alignmentClasses = [
    'left' => 'origin-top-left left-0',
    'right' => 'origin-top-right right-0',
];

$widthClasses = [
    '48' => 'w-48',
    '60' => 'w-60',
    'full' => 'w-full',
];
@endphp

<div 
    x-data="{ 
        open: false,
        toggle() {
            if (this.open) {
                return this.close()
            }
            
            this.$refs.button.focus()
            this.open = true
        },
        close(focusAfter) {
            if (! this.open) return
            
            this.open = false
            focusAfter && focusAfter.focus()
        }
    }"
    x-on:keydown.escape.prevent.stop="close($refs.button)"
    x-on:focusin.window="! $refs.panel.contains($event.target) && close()"
    x-id="['dropdown-button']"
    class="relative inline-block text-left {{ $dropdownClasses }}"
>
    <div x-ref="button">
        {{ $trigger }}
    </div>

    <div
        x-ref="panel"
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        x-on:click.outside="close($refs.button)"
        :id="$id('dropdown-button')"
        style="display: none;"
        class="absolute z-[9999] mt-2 {{ $widthClasses[$width] ?? $widthClasses['48'] }} rounded-md bg-white dark:bg-gray-800 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none {{ $alignmentClasses[$align] ?? $alignmentClasses['right'] }} {{ $contentClasses }}"
    >
        {{ $content }}
    </div>
</div>