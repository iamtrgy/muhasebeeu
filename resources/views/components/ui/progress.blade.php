@props([
    'value' => 0,
    'max' => 100,
    'size' => 'md', // sm, md, lg
    'color' => 'primary', // primary, success, warning, danger
    'showLabel' => false,
    'labelPosition' => 'inside', // inside, outside
    'striped' => false,
    'animated' => false,
    'indeterminate' => false,
])

@php
$percentage = $max > 0 ? min(100, max(0, ($value / $max) * 100)) : 0;

$sizeClasses = [
    'sm' => 'h-2',
    'md' => 'h-4',
    'lg' => 'h-6',
];

$colorClasses = [
    'primary' => 'bg-indigo-600 dark:bg-indigo-500',
    'success' => 'bg-emerald-600 dark:bg-emerald-500',
    'warning' => 'bg-amber-600 dark:bg-amber-500',
    'danger' => 'bg-red-600 dark:bg-red-500',
];

$barHeight = $sizeClasses[$size] ?? $sizeClasses['md'];
$barColor = $colorClasses[$color] ?? $colorClasses['primary'];

$stripedClass = $striped ? 'bg-gradient-to-r from-transparent via-white/20 to-transparent bg-[length:20px_20px]' : '';
$animatedClass = $animated && $striped ? 'animate-[progress-stripes_1s_linear_infinite]' : '';
$indeterminateClass = $indeterminate ? 'animate-[progress-indeterminate_1.5s_ease-in-out_infinite]' : '';
@endphp

<div {{ $attributes->merge(['class' => 'w-full']) }}>
    @if($showLabel && $labelPosition === 'outside')
        <div class="flex justify-between mb-1">
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ $slot->isEmpty() ? 'Progress' : $slot }}
            </span>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ round($percentage) }}%
            </span>
        </div>
    @endif
    
    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full {{ $barHeight }} overflow-hidden">
        @if($indeterminate)
            <div class="{{ $barHeight }} {{ $barColor }} rounded-full w-1/3 {{ $indeterminateClass }}"></div>
        @else
            <div 
                class="{{ $barHeight }} {{ $barColor }} rounded-full transition-all duration-300 ease-out relative overflow-hidden {{ $stripedClass }} {{ $animatedClass }}"
                style="width: {{ $percentage }}%"
                role="progressbar"
                aria-valuenow="{{ $value }}"
                aria-valuemin="0"
                aria-valuemax="{{ $max }}"
            >
                @if($showLabel && $labelPosition === 'inside' && $size !== 'sm')
                    <span class="absolute inset-0 flex items-center justify-center text-xs font-medium text-white">
                        {{ round($percentage) }}%
                    </span>
                @endif
            </div>
        @endif
    </div>
</div>

@pushonce('progress-styles')
<style>
@keyframes progress-stripes {
    0% { background-position: 0 0; }
    100% { background-position: 20px 0; }
}

@keyframes progress-indeterminate {
    0% {
        transform: translateX(-100%);
    }
    100% {
        transform: translateX(400%);
    }
}
</style>
@endpushonce