@props([
    'id' => null,
    'responsive' => true,
    'striped' => false,
    'hoverable' => true,
    'bordered' => false,
    'compact' => false,
    'rounded' => true,
])

@php
$wrapperClasses = $responsive ? 'overflow-x-auto' : '';
$roundedClasses = $rounded ? 'rounded-lg overflow-hidden' : '';

$tableClasses = collect([
    'w-full',
    'divide-y divide-gray-200 dark:divide-gray-700',
    $bordered ? 'border border-gray-200 dark:border-gray-700' : '',
])->filter()->join(' ');
@endphp

<div class="{{ $wrapperClasses }} {{ $roundedClasses }}">
            <table 
        @if($id) id="{{ $id }}" @endif
        class="{{ $tableClasses }}"
        {{ $attributes }}
    >
        @if(isset($head))
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    {{ $head }}
                </tr>
            </thead>
        @endif
        
        @if(isset($body))
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                {{ $body }}
            </tbody>
        @else
            {{ $slot }}
        @endif
    </table>
</div>