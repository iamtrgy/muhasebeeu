@props([
    'name',
])

<div
    x-show="activeTab === '{{ $name }}'"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 transform translate-y-1"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform translate-y-1"
    role="tabpanel"
    :aria-hidden="activeTab !== '{{ $name }}'"
    {{ $attributes }}
>
    {{ $slot }}
</div>