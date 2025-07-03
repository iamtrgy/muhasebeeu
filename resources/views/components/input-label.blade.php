@props(['value'])

{{-- Legacy input-label wrapper - use x-ui.form.input with label prop for new implementations --}}
<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-gray-700 dark:text-gray-300']) }}>
    {{ $value ?? $slot }}
</label>