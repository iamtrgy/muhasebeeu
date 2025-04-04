@props(['title' => null, 'description' => null, 'breadcrumbs' => [], 'actions' => null])

@php
    // Let the parent layout know this page has a custom header
    $pageHeader = true;
@endphp

<header class="bg-white dark:bg-gray-800 w-full">
    <div class="w-full border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between flex-wrap sm:flex-nowrap">
                <div class="flex-1 min-w-0">
                    @if($title)
                        <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:truncate">
                            {{ $title }}
                        </h2>
                        @if($description)
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $description }}</p>
                        @endif
                    @endif
                    
                    <!-- Always render the slot content regardless of whether title is provided -->
                    @if(!$slot->isEmpty())
                        <div class="mt-4">{{ $slot }}</div>
                    @endif
                </div>
                @if(isset($actions))
                    <div class="flex-shrink-0 flex mt-4 sm:mt-0 sm:ml-4">
                        {{ $actions }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    @if(!empty($breadcrumbs))
        <div class="w-full bg-gray-50 dark:bg-gray-700/30 border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
                @if(request()->is('admin*'))
                    <x-admin.breadcrumb :items="$breadcrumbs" />
                @else
                    <x-breadcrumb :items="$breadcrumbs" />
                @endif
            </div>
        </div>
    @endif
</header> 