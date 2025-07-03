@props([
    'paginator',
    'showInfo' => true,
])

@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
        @if($showInfo)
            <div class="flex-1 flex justify-between sm:hidden">
                <x-ui.pagination.mobile-simple :paginator="$paginator" />
            </div>
        @endif

        <div class="{{ $showInfo ? 'hidden sm:flex-1 sm:flex sm:items-center sm:justify-between' : 'flex-1 flex items-center justify-center' }}">
            @if($showInfo)
                <div>
                    <x-ui.pagination.info :paginator="$paginator" />
                </div>
            @endif

            <div>
                <x-ui.pagination.links :paginator="$paginator" />
            </div>
        </div>
    </nav>
@endif