@props([
    'label' => '',
    'icon' => null,
    'open' => false,
])

<div x-data="{ open: {{ $open ? 'true' : 'false' }} }" class="mb-6">
    <!-- Group Label -->
    @if($label)
        <div class="px-3 mb-3">
            <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                {{ $label }}
            </h3>
        </div>
    @endif
    
    <!-- Group Items -->
    <div class="space-y-1">
        {{ $slot }}
    </div>
</div>