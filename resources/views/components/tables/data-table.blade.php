{{-- Legacy component wrapper - use x-ui.table components directly for new implementations --}}
@props(['headers' => []])

<x-ui.table.base {{ $attributes }}>
    @if(count($headers) > 0)
        <x-ui.table.header>
            <x-ui.table.row>
                @foreach($headers as $header)
                    <x-ui.table.head-cell>{{ $header }}</x-ui.table.head-cell>
                @endforeach
            </x-ui.table.row>
        </x-ui.table.header>
    @endif
    
    <x-ui.table.body>
        {{ $slot }}
    </x-ui.table.body>
</x-ui.table.base>