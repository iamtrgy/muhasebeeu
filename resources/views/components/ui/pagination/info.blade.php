@props(['paginator'])

<p class="text-sm text-gray-700 dark:text-gray-300">
    {!! __('Showing') !!}
    @if ($paginator->firstItem())
        <span class="font-medium">{{ $paginator->firstItem() }}</span>
        {!! __('to') !!}
        <span class="font-medium">{{ $paginator->lastItem() }}</span>
    @else
        {{ $paginator->count() }}
    @endif
    {!! __('of') !!}
    <span class="font-medium">{{ $paginator->total() }}</span>
    {!! __('results') !!}
</p>