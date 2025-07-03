@props([
    'data' => collect(), // Collection or paginated data
    'columns' => [], // Array of column definitions
    'searchable' => true,
    'searchPlaceholder' => 'Search...',
    'bulkActions' => [], // Array of bulk actions
    'filters' => [], // Array of filter options
    'defaultSort' => null,
    'defaultDirection' => 'asc',
    'striped' => true,
    'hoverable' => true,
    'responsive' => true,
    'loading' => false,
])

@php
// Ensure we have a collection
if (is_array($data)) {
    $data = collect($data);
}

$isPaginated = $data instanceof \Illuminate\Contracts\Pagination\Paginator;
$items = $isPaginated ? $data->items() : $data;

// Generate unique ID for this table instance
$tableId = 'data-table-' . uniqid();
@endphp

<div 
    x-data="{
        selected: [],
        selectAll: false,
        search: '',
        sortBy: '{{ $defaultSort }}',
        sortDirection: '{{ $defaultDirection }}',
        toggleSelectAll() {
            this.selectAll = !this.selectAll;
            this.selected = this.selectAll 
                ? @json(collect($items)->pluck('id')->toArray())
                : [];
        },
        toggleSelect(id) {
            const index = this.selected.indexOf(id);
            if (index === -1) {
                this.selected.push(id);
            } else {
                this.selected.splice(index, 1);
            }
            this.selectAll = this.selected.length === {{ count($items) }};
        },
        sort(column) {
            if (this.sortBy === column) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortBy = column;
                this.sortDirection = 'asc';
            }
            // Trigger server-side sorting
            this.submitSort();
        },
        submitSort() {
            const url = new URL(window.location);
            url.searchParams.set('sort', this.sortBy);
            url.searchParams.set('direction', this.sortDirection);
            window.location.href = url.toString();
        },
        submitSearch() {
            const url = new URL(window.location);
            if (this.search) {
                url.searchParams.set('search', this.search);
            } else {
                url.searchParams.delete('search');
            }
            window.location.href = url.toString();
        }
    }"
    class="w-full"
    id="{{ $tableId }}"
>
    {{-- Header Actions --}}
    <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        {{-- Search --}}
        @if($searchable)
            <div class="flex-1 max-w-sm">
                <div class="relative">
                    <input
                        type="text"
                        x-model="search"
                        @keydown.enter="submitSearch()"
                        placeholder="{{ $searchPlaceholder }}"
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-gray-300"
                    >
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <button
                        x-show="search"
                        @click="search = ''; submitSearch()"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center"
                    >
                        <svg class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        {{-- Actions and Filters --}}
        <div class="flex items-center gap-3">
            {{-- Bulk Actions --}}
            @if(count($bulkActions) > 0)
                <div x-show="selected.length > 0" x-cloak>
                    <x-ui.dropdown.base align="right">
                        <x-slot name="trigger">
                            <x-ui.button.secondary size="sm" x-on:click="toggle()">
                                <span x-text="`Actions (${selected.length})`"></span>
                                <svg class="ml-2 -mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </x-ui.button.secondary>
                        </x-slot>
                        
                        <x-slot name="content">
                            <div class="py-1">
                                @foreach($bulkActions as $action)
                                    <x-ui.dropdown.item 
                                        type="button"
                                        onclick="{{ $action['action'] }}(Alpine.$data(document.getElementById('{{ $tableId }}')).selected)"
                                        :class="isset($action['destructive']) && $action['destructive'] ? 'text-red-600 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20' : ''"
                                    >
                                        @if(isset($action['icon']))
                                            <x-slot name="icon">{!! $action['icon'] !!}</x-slot>
                                        @endif
                                        {{ $action['label'] }}
                                    </x-ui.dropdown.item>
                                @endforeach
                            </div>
                        </x-slot>
                    </x-ui.dropdown.base>
                </div>
            @endif

            {{-- Filters --}}
            @if(count($filters) > 0)
                <x-ui.dropdown.base align="right">
                    <x-slot name="trigger">
                        <x-ui.button.secondary size="sm" x-on:click="toggle()">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter
                        </x-ui.button.secondary>
                    </x-slot>
                    
                    <x-slot name="content">
                        <div class="p-4 space-y-4 min-w-[200px]">
                            @foreach($filters as $filter)
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        {{ $filter['label'] }}
                                    </label>
                                    @if($filter['type'] === 'select')
                                        <select 
                                            name="{{ $filter['name'] }}"
                                            onchange="window.location.href = updateQueryString('{{ $filter['name'] }}', this.value)"
                                            class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700"
                                        >
                                            <option value="">All</option>
                                            @foreach($filter['options'] as $value => $label)
                                                <option value="{{ $value }}" {{ request($filter['name']) == $value ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                            @endforeach
                            
                            @if(request()->hasAny(collect($filters)->pluck('name')->toArray()))
                                <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
                                    <a href="{{ url()->current() }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-500">
                                        Clear filters
                                    </a>
                                </div>
                            @endif
                        </div>
                    </x-slot>
                </x-ui.dropdown.base>
            @endif

            {{-- Additional slot for custom actions --}}
            @if(isset($actions))
                {{ $actions }}
            @endif
        </div>
    </div>

    {{-- Table --}}
    <div class="{{ $responsive ? 'overflow-x-auto' : '' }}">
        @if($loading)
            <div class="relative">
                <div class="absolute inset-0 bg-white/50 dark:bg-gray-800/50 z-10 flex items-center justify-center">
                    <x-ui.spinner size="lg" />
                </div>
            </div>
        @endif

        <x-ui.table.base :striped="$striped" :hoverable="$hoverable">
            <x-ui.table.header>
                <x-ui.table.row>
                    {{-- Select All Checkbox --}}
                    @if(count($bulkActions) > 0)
                        <x-ui.table.head-cell class="w-4">
                            <input
                                type="checkbox"
                                x-model="selectAll"
                                @change="toggleSelectAll()"
                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                            >
                        </x-ui.table.head-cell>
                    @endif

                    {{-- Column Headers --}}
                    @foreach($columns as $column)
                        <x-ui.table.head-cell 
                            :sortable="$column['sortable'] ?? false"
                            :sortKey="$column['key'] ?? ''"
                            :currentSort="request('sort')"
                            :currentDirection="request('direction', 'asc')"
                            :align="$column['align'] ?? 'left'"
                            @click="sort('{{ $column['key'] ?? '' }}')"
                        >
                            {{ $column['label'] }}
                        </x-ui.table.head-cell>
                    @endforeach

                    {{-- Actions Column --}}
                    @if(isset($rowActions))
                        <x-ui.table.head-cell align="right">Actions</x-ui.table.head-cell>
                    @endif
                </x-ui.table.row>
            </x-ui.table.header>

            <x-ui.table.body>
                @forelse($items as $item)
                    <x-ui.table.row>
                        {{-- Select Checkbox --}}
                        @if(count($bulkActions) > 0)
                            <x-ui.table.cell>
                                <input
                                    type="checkbox"
                                    :value="{{ $item->id }}"
                                    x-model="selected"
                                    @change="toggleSelect({{ $item->id }})"
                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                >
                            </x-ui.table.cell>
                        @endif

                        {{-- Data Cells --}}
                        @foreach($columns as $column)
                            <x-ui.table.cell :align="$column['align'] ?? 'left'">
                                @if(isset($column['component']))
                                    <x-dynamic-component 
                                        :component="$column['component']" 
                                        :item="$item"
                                        :value="data_get($item, $column['key'])"
                                    />
                                @elseif(isset($column['format']))
                                    {!! $column['format']($item, data_get($item, $column['key'])) !!}
                                @else
                                    {{ data_get($item, $column['key']) }}
                                @endif
                            </x-ui.table.cell>
                        @endforeach

                        {{-- Row Actions --}}
                        @if(isset($rowActions))
                            <x-ui.table.action-cell>
                                {{ $rowActions($item) }}
                            </x-ui.table.action-cell>
                        @endif
                    </x-ui.table.row>
                @empty
                    <x-ui.table.empty-state 
                        :colspan="count($columns) + (count($bulkActions) > 0 ? 1 : 0) + (isset($rowActions) ? 1 : 0)"
                        message="No data found"
                    >
                        @if(isset($emptyAction))
                            {{ $emptyAction }}
                        @endif
                    </x-ui.table.empty-state>
                @endforelse
            </x-ui.table.body>
        </x-ui.table.base>
    </div>

    {{-- Pagination --}}
    @if($isPaginated && $data->hasPages())
        <div class="mt-4">
            <x-ui.pagination.base :paginator="$data" />
        </div>
    @endif
</div>

@push('scripts')
<script>
function updateQueryString(key, value) {
    const url = new URL(window.location);
    if (value) {
        url.searchParams.set(key, value);
    } else {
        url.searchParams.delete(key);
    }
    return url.toString();
}
</script>
@endpush