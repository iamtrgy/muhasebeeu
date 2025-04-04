@props(['title', 'content' => null])

<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
    <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ $title }}</h2>
        @if($content)
            {{ $content }}
        @else
            {{ $slot }}
        @endif
    </div>
</div>
