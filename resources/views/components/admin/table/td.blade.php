@props([
    'darkMode' => true
])

<td {{ $attributes->merge(['class' => 'px-6 py-4 whitespace-nowrap text-sm ' . ($darkMode ? 'dark:text-gray-400' : '')]) }}>
    {{ $slot }}
</td>
