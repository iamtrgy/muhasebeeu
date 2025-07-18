@props([
    'name',
    'id' => null,
    'value' => null,
    'label' => null,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'rows' => 4,
    'helperText' => null,
    'error' => null,
    'maxlength' => null,
    'size' => 'md', // sm, md, lg
])

@php
$id = $id ?? $name;

$sizeClasses = [
    'sm' => 'py-1.5 text-sm',
    'md' => 'py-2 text-sm',
    'lg' => 'py-3 text-base',
];

$baseClasses = 'block w-full rounded-md shadow-sm transition-colors duration-200';
$borderClasses = $error 
    ? 'border-red-300 dark:border-red-600' 
    : 'border-gray-300 dark:border-gray-700';
$backgroundClasses = 'bg-white dark:bg-gray-900';
$textClasses = $error 
    ? 'text-red-900 dark:text-red-300 placeholder-red-300 dark:placeholder-red-500' 
    : 'text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500';
$focusClasses = $error 
    ? 'focus:ring-red-500 dark:focus:ring-red-600 focus:border-red-500 dark:focus:border-red-600' 
    : 'focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-indigo-500 dark:focus:border-indigo-400';
$disabledClasses = 'disabled:bg-gray-50 dark:disabled:bg-gray-800 disabled:cursor-not-allowed';

$textareaClasses = implode(' ', [
    $baseClasses,
    $sizeClasses[$size] ?? $sizeClasses['md'],
    $borderClasses,
    $backgroundClasses,
    $textClasses,
    $focusClasses,
    $disabledClasses,
]);
@endphp

<div>
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <textarea
        name="{{ $name }}"
        id="{{ $id }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        @required($required)
        @disabled($disabled)
        @readonly($readonly)
        @if($maxlength) maxlength="{{ $maxlength }}" @endif
        {{ $attributes->merge(['class' => $textareaClasses]) }}
    >{{ old($name, $value) }}</textarea>
    
    @error($name)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @elseif($error)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
    @elseif($helperText)
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $helperText }}</p>
    @enderror
</div>