@props([
    'title' => null,
    'description' => null,
    'border' => true,
])

<div class="{{ $border ? 'border-b border-gray-200 dark:border-gray-700' : '' }} {{ $border ? 'pb-6' : '' }}">
    @if($title)
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
            {{ $title }}
        </h3>
    @endif
    
    @if($description)
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ $description }}
        </p>
    @endif
    
    {{ $slot }}
</div>