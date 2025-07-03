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

$toggleSizes = [
    'sm' => ['toggle' => 'h-5 w-9', 'dot' => 'h-3 w-3', 'translate' => 'translate-x-4'],
    'md' => ['toggle' => 'h-6 w-11', 'dot' => 'h-4 w-4', 'translate' => 'translate-x-5'],
    'lg' => ['toggle' => 'h-7 w-14', 'dot' => 'h-5 w-5', 'translate' => 'translate-x-7'],
];

$size = $toggleSizes[$size] ?? $toggleSizes['md'];

$isChecked = old($name) ? old($name) == $value : $checked;
@endphp

<div x-data="{ checked: {{ $isChecked ? 'true' : 'false' }} }">
    <div class="flex items-start">
        <button
            type="button"
            @click="checked = !checked"
            :class="checked ? 'bg-indigo-600 dark:bg-indigo-500' : 'bg-gray-200 dark:bg-gray-700'"
            class="relative inline-flex items-center {{ $size['toggle'] }} flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 {{ $disabled ? 'opacity-50 cursor-not-allowed' : '' }}"
            :aria-pressed="checked.toString()"
            @if($disabled) disabled @endif
        >
            <span class="sr-only">{{ $label ?? 'Toggle' }}</span>
            <span
                :class="checked ? '{{ $size['translate'] }}' : 'translate-x-1'"
                class="pointer-events-none inline-block {{ $size['dot'] }} transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
            >
            </span>
        </button>
        
        <input
            type="hidden"
            name="{{ $name }}"
            :value="checked ? '{{ $value }}' : '0'"
        />
        
        @if($label)
            <div class="ml-3">
                <label for="{{ $id }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer" @click="checked = !checked">
                    {{ $label }}
                    @if($required)
                        <span class="text-red-500">*</span>
                    @endif
                </label>
                @if($helperText)
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $helperText }}</p>
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