@props([
    'name',
    'id' => null,
    'value' => 1,
    'label' => null,
    'checked' => false,
    'required' => false,
    'disabled' => false,
    'helperText' => null,
    'error' => null,
    'size' => 'md', // sm, md, lg
])

@php
$id = $id ?? $name;

$sizeClasses = [
    'sm' => 'h-3 w-3',
    'md' => 'h-4 w-4',
    'lg' => 'h-5 w-5',
];

$labelSizeClasses = [
    'sm' => 'text-xs',
    'md' => 'text-sm',
    'lg' => 'text-base',
];

$borderClasses = $error 
    ? 'border-red-300 dark:border-red-600' 
    : 'border-gray-300 dark:border-gray-600';
$textClasses = 'text-indigo-600 dark:text-indigo-500';
$focusClasses = 'focus:ring-indigo-500 dark:focus:ring-indigo-400';
$backgroundClasses = 'bg-white dark:bg-gray-900';

$checkboxClasses = implode(' ', [
    'rounded',
    $sizeClasses[$size] ?? $sizeClasses['md'],
    $borderClasses,
    $textClasses,
    $focusClasses,
    $backgroundClasses,
    'disabled:bg-gray-50 dark:disabled:bg-gray-800 disabled:cursor-not-allowed',
]);

$isChecked = old($name) ? old($name) == $value : $checked;
@endphp

<div>
    <div class="flex items-start">
        <div class="flex items-center h-5">
            <input
                type="checkbox"
                name="{{ $name }}"
                id="{{ $id }}"
                value="{{ $value }}"
                @checked($isChecked)
                @required($required)
                @disabled($disabled)
                {{ $attributes->merge(['class' => $checkboxClasses]) }}
            />
        </div>
        @if($label || $slot->isNotEmpty())
            <div class="ml-3">
                <label for="{{ $id }}" class="{{ $labelSizeClasses[$size] ?? $labelSizeClasses['md'] }} text-gray-700 dark:text-gray-300">
                    @if($slot->isNotEmpty())
                        {{ $slot }}
                    @else
                        <span class="font-medium">
                            {{ $label }}
                            @if($required)
                                <span class="text-red-500">*</span>
                            @endif
                        </span>
                    @endif
                </label>
                @if($helperText)
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $helperText }}</p>
                @endif
            </div>
        @endif
    </div>
    
    @error($name)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @elseif($error)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
    @enderror
</div>