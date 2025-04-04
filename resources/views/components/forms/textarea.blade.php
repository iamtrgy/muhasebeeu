@props([
    'name',
    'id' => null,
    'value' => null,
    'label' => null,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'rows' => 3,
    'helperText' => null,
    'error' => null,
])

<div>
    @if($label)
        <label for="{{ $id ?? $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <textarea
        name="{{ $name }}"
        id="{{ $id ?? $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        @if($disabled) disabled @endif
        {{ $attributes->merge([
            'class' => 'mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm' .
            ($error ? ' border-red-300 dark:border-red-600 text-red-900 dark:text-red-300 placeholder-red-300 dark:placeholder-red-500 focus:ring-red-500 dark:focus:ring-red-600 focus:border-red-500 dark:focus:border-red-600' : '')
        ]) }}
    >{{ $value ?? old($name) }}</textarea>
    
    @if($error)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
    @elseif($helperText)
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $helperText }}</p>
    @endif
</div>
