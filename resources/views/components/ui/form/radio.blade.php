@props([
    'name',
    'id' => null,
    'value',
    'label' => null,
    'checked' => false,
    'required' => false,
    'disabled' => false,
    'helperText' => null,
    'error' => null,
    'size' => 'md', // sm, md, lg
])

@php
$id = $id ?? $name . '_' . str_replace(' ', '_', $value);

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

$radioClasses = implode(' ', [
    $sizeClasses[$size] ?? $sizeClasses['md'],
    $borderClasses,
    $textClasses,
    $focusClasses,
    $backgroundClasses,
    'disabled:bg-gray-50 dark:disabled:bg-gray-800 disabled:cursor-not-allowed',
]);

$isChecked = old($name, $checked) == $value;
@endphp

<div>
    <div class="flex items-start">
        <div class="flex items-center h-5">
            <input
                type="radio"
                name="{{ $name }}"
                id="{{ $id }}"
                value="{{ $value }}"
                @checked($isChecked)
                @required($required)
                @disabled($disabled)
                {{ $attributes->merge(['class' => $radioClasses]) }}
            />
        </div>
        @if($label)
            <div class="ml-3">
                <label for="{{ $id }}" class="font-medium {{ $labelSizeClasses[$size] ?? $labelSizeClasses['md'] }} text-gray-700 dark:text-gray-300">
                    {{ $label }}
                </label>
                @if($helperText)
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $helperText }}</p>
                @endif
            </div>
        @endif
    </div>
</div>