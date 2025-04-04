@props(['breadcrumbs' => []])

<header class="bg-white dark:bg-gray-800 w-full">
    <div class="w-full border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between flex-wrap sm:flex-nowrap">
                {{ $slot }}
            </div>
        </div>
    </div>
    @if(count($breadcrumbs) > 0)
        <div class="w-full bg-gray-50 dark:bg-gray-700/30 border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
                <x-admin.breadcrumb :items="$breadcrumbs" />
            </div>
        </div>
    @endif
</header>
