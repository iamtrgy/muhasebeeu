@props(['title' => null, 'description' => null, 'actions' => null])

<div class="flex-1 min-w-0">
    @if($title)
        <h1 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:truncate">{{ $title }}</h1>
        
        @if($description)
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $description }}</p>
        @endif
    @endif
</div>

@if($actions)
    <div class="flex-shrink-0 flex mt-4 sm:mt-0 sm:ml-4">
        {{ $actions }}
    </div>
@endif
