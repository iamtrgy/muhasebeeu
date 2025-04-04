@props(['title' => 'Recent Activity'])

<div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ $title }}</h3>
    </div>
    <div class="divide-y divide-gray-200 dark:divide-gray-700">
        {{ $slot }}
    </div>
</div>
