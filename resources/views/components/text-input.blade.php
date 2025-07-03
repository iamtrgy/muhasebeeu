@props(['disabled' => false])

{{-- Legacy text-input wrapper - use x-ui.form.input directly for new implementations --}}
<input 
    @disabled($disabled) 
    {{ $attributes->merge(['class' => 'block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm']) }}
>