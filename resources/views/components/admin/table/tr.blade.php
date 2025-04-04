@props([
    'darkMode' => true,
    'hover' => true
])

<tr {{ $attributes->merge(['class' => ($hover ? 'hover:bg-gray-50 ' . ($darkMode ? 'dark:hover:bg-gray-700' : '') : '')]) }}>
    {{ $slot }}
</tr>
