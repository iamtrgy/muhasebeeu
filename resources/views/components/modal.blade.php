{{-- Legacy component wrapper - use x-ui.modal.base directly for new implementations --}}
@props([
    'name',
    'show' => false,
    'maxWidth' => '2xl'
])

<x-ui.modal.base 
    :name="$name" 
    :show="$show" 
    :maxWidth="$maxWidth"
    :focusable="true"
    :closeable="true"
    {{ $attributes }}
>
    {{ $slot }}
</x-ui.modal.base>