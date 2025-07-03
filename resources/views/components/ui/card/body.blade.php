@props([
    'noPadding' => false,
])

<div class="{{ $noPadding ? '' : 'pt-6' }}">
    {{ $slot }}
</div>