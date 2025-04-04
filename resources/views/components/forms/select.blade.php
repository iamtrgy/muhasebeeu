@props(['name', 'id' => null, 'label' => null, 'items' => [], 'selected' => null, 'valueField' => 'id', 'textField' => 'name', 'infoField' => null, 'required' => false, 'placeholder' => '-- Select an option --', 'error' => null])

<div>
    @if($label)
        <label for="{{ $id ?? $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <select
        name="{{ $name }}"
        id="{{ $id ?? $name }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge([
            'class' => 'mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm' .
            ($error ? ' border-red-300 dark:border-red-600 ring-red-500 dark:ring-red-600' : ''),
            'style' => 'text-overflow: ellipsis;'
        ]) }}
    >
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        
        @foreach($items as $item)
            @php
                $value = $item->{$valueField} ?? $item[$valueField] ?? $item;
                $text = $item->{$textField} ?? $item[$textField] ?? $item;
                $info = $infoField ? ($item->{$infoField} ?? $item[$infoField] ?? null) : null;
                $displayText = $info ? "$text ($info)" : $text;
            @endphp
            
            <option 
                value="{{ $value }}" 
                {{ old($name, $selected) == $value ? 'selected' : '' }}
                title="{{ $displayText }}"
            >
                {{ $displayText }}
            </option>
        @endforeach
    </select>
    
    @if($error)
        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
    @endif
</div>
