@props([
    'src' => null,
    'alt' => 'Avatar',
    'size' => 'md', // xs, sm, md, lg, xl
    'name' => null, // For initials
    'status' => null, // online, offline, away, busy
    'statusPosition' => 'bottom-right', // top-right, top-left, bottom-right, bottom-left
    'rounded' => 'full', // full, lg, md, none
])

@php
$sizeClasses = [
    'xs' => 'h-6 w-6 text-xs',
    'sm' => 'h-8 w-8 text-sm',
    'md' => 'h-10 w-10 text-base',
    'lg' => 'h-12 w-12 text-lg',
    'xl' => 'h-16 w-16 text-xl',
];

$statusSizeClasses = [
    'xs' => 'h-1.5 w-1.5',
    'sm' => 'h-2 w-2',
    'md' => 'h-2.5 w-2.5',
    'lg' => 'h-3 w-3',
    'xl' => 'h-4 w-4',
];

$statusPositionClasses = [
    'top-right' => 'top-0 right-0',
    'top-left' => 'top-0 left-0',
    'bottom-right' => 'bottom-0 right-0',
    'bottom-left' => 'bottom-0 left-0',
];

$statusColorClasses = [
    'online' => 'bg-emerald-400',
    'offline' => 'bg-gray-400',
    'away' => 'bg-amber-400',
    'busy' => 'bg-red-400',
];

$roundedClasses = [
    'full' => 'rounded-full',
    'lg' => 'rounded-lg',
    'md' => 'rounded-md',
    'none' => 'rounded-none',
];

$avatarSize = $sizeClasses[$size] ?? $sizeClasses['md'];
$statusSize = $statusSizeClasses[$size] ?? $statusSizeClasses['md'];
$statusPos = $statusPositionClasses[$statusPosition] ?? $statusPositionClasses['bottom-right'];
$statusColor = isset($statusColorClasses[$status]) ? $statusColorClasses[$status] : null;
$roundedClass = $roundedClasses[$rounded] ?? $roundedClasses['full'];

// Generate initials from name
$initials = '';
if (!$src && $name) {
    $nameParts = explode(' ', trim($name));
    $initials = strtoupper(substr($nameParts[0], 0, 1));
    if (count($nameParts) > 1) {
        $initials .= strtoupper(substr(end($nameParts), 0, 1));
    }
}

// Generate background color from name for consistency
$bgColors = [
    'bg-gray-500',
    'bg-red-500',
    'bg-amber-500',
    'bg-emerald-500',
    'bg-blue-500',
    'bg-indigo-500',
    'bg-purple-500',
    'bg-pink-500',
];
$bgColor = $name ? $bgColors[crc32($name) % count($bgColors)] : 'bg-gray-400';
@endphp

<div class="relative inline-block">
    @if($src)
        <img 
            class="{{ $avatarSize }} {{ $roundedClass }} object-cover"
            src="{{ $src }}"
            alt="{{ $alt }}"
            {{ $attributes }}
        >
    @else
        <div 
            class="{{ $avatarSize }} {{ $roundedClass }} {{ $bgColor }} flex items-center justify-center font-medium text-white"
            {{ $attributes }}
        >
            @if($initials)
                {{ $initials }}
            @else
                <svg class="w-3/5 h-3/5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                </svg>
            @endif
        </div>
    @endif
    
    @if($status)
        <span class="absolute {{ $statusPos }} block {{ $statusSize }} {{ $statusColor }} rounded-full ring-2 ring-white dark:ring-gray-800"></span>
    @endif
</div>