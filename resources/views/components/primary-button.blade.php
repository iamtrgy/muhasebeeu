{{-- Legacy component wrapper - use x-ui.button.primary directly for new implementations --}}
<x-ui.button.primary 
    :type="$attributes->get('type', 'submit')" 
    {{ $attributes->except('type') }}
>
    {{ $slot }}
</x-ui.button.primary>