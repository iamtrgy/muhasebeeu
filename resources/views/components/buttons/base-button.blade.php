@props([
    'type' => 'button',
    'color' => 'indigo', // Options: indigo, red, green, blue, gray
    'disabled' => false,
    'size' => 'default', // Options: default, sm, lg
])

@php
    // Define the base classes according to the standardized pattern
    $baseClasses = "inline-flex items-center px-4 py-2 bg-{$color}-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-{$color}-700 focus:bg-{$color}-700 active:bg-{$color}-900 focus:outline-none focus:ring-2 focus:ring-{$color}-500 focus:ring-offset-2 transition ease-in-out duration-150";
    
    // Add size variations while keeping the px-4 py-2 standard for default size
    if ($size === 'sm') {
        $baseClasses = str_replace('px-4 py-2', 'px-3 py-1', $baseClasses);
    } else if ($size === 'lg') {
        $baseClasses = str_replace('px-4 py-2', 'px-6 py-3', $baseClasses);
        $baseClasses = str_replace('text-xs', 'text-sm', $baseClasses);
    }
    
    if ($disabled) {
        $baseClasses .= ' opacity-50 cursor-not-allowed';
    }
@endphp

<button 
    type="{{ $type }}" 
    {{ $disabled ? 'disabled' : '' }}
    {{ $attributes->merge(['class' => $baseClasses]) }}
>
    {{ $slot }}
</button>
