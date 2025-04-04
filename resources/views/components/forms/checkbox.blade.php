@props([
    'name',
    'id' => null,
    'checked' => false,
    'label' => null,
    'value' => '1',
    'disabled' => false,
    'helperText' => null,
    'error' => null,
])

<div class="flex items-start">
    <div class="flex items-center h-5">
        <input
            type="checkbox"
            name="{{ $name }}"
            id="{{ $id ?? $name }}"
            value="{{ $value }}"
            @if($checked) checked @endif
            @if($disabled) disabled @endif
            {{ $attributes->merge([
                'class' => 'h-4 w-4 rounded border-gray-300 dark:border-gray-700 text-indigo-600 dark:text-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:bg-gray-900' .
                ($error ? ' border-red-300 dark:border-red-600 text-red-900 dark:text-red-300 focus:ring-red-500 dark:focus:ring-red-600 focus:border-red-500 dark:focus:border-red-600' : '') .
                ($disabled ? ' opacity-50 cursor-not-allowed' : '')
            ]) }}
        />
    </div>
    
    <div class="ml-3 text-sm">
        @if($label)
            <label for="{{ $id ?? $name }}" class="font-medium text-gray-700 dark:text-gray-300 {{ $disabled ? 'opacity-50 cursor-not-allowed' : '' }}">
                {{ $label }}
            </label>
        @endif
        
        @if($helperText)
            <p class="text-gray-500 dark:text-gray-400">{{ $helperText }}</p>
        @endif
        
        @if($error)
            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
        @endif
    </div>
</div>
