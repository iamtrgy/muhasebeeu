{{-- Legacy component wrapper - use x-ui.modal.base directly for new implementations --}}
@props([
    'id' => 'modal',
    'maxWidth' => '2xl',
    'closeButton' => true,
])

<x-ui.modal.base 
    :id="$id" 
    :maxWidth="$maxWidth"
    :closeButton="$closeButton"
    :focusable="true"
    :closeable="true"
    {{ $attributes }}
>
    {{ $slot }}
</x-ui.modal.base>