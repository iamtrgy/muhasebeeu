@props([
    'size' => 'md',
    'type' => 'button',
    'disabled' => false,
    'fullWidth' => false,
])

<x-ui.button.base
    :variant="'secondary'"
    :size="$size"
    :type="$type"
    :disabled="$disabled"
    :fullWidth="$fullWidth"
    {{ $attributes }}
>
    {{ $slot }}
</x-ui.button.base>