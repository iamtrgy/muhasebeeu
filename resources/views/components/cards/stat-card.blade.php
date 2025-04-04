@props([
    'title',
    'value',
    'icon',
    'color' => 'blue', // Options: blue, emerald, purple, etc.
])

@php
    $gradientFrom = "from-{$color}-500";
    $gradientTo = "to-{$color}-600";
    $textColor = "{$color}-100";
@endphp

<div class="bg-gradient-to-br {{ $gradientFrom }} {{ $gradientTo }} rounded-lg shadow-lg overflow-hidden">
    <div class="px-6 py-8">
        <div class="flex items-center justify-between">
            <div class="p-3 rounded-full bg-white/20 backdrop-blur-sm">
                {{ $icon }}
            </div>
            <div class="text-right">
                <p class="text-{{ $textColor }} text-sm">{{ $title }}</p>
                <p class="text-3xl font-bold text-white">{{ $value }}</p>
            </div>
        </div>
    </div>
</div>
