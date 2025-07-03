@props([
    'text' => null,
])

<div {{ $attributes->merge(['class' => 'px-4 py-2 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider']) }}>
    {{ $text ?? $slot }}
</div>