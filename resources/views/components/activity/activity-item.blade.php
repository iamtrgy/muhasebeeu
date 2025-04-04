@props(['icon', 'title', 'subtitle', 'timestamp'])

<div class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="p-2 rounded-full bg-gray-100 dark:bg-gray-600">
                    {{ $icon }}
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $title }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $subtitle }}</p>
            </div>
        </div>
        <div class="text-sm text-gray-500 dark:text-gray-400">
            {{ $timestamp }}
        </div>
    </div>
</div>
