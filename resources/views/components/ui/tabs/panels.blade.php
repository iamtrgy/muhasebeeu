@props([
    'class' => '',
])

<div {{ $attributes->merge(['class' => 'mt-4 ' . $class]) }}>
    {{ $slot }}
</div>