@props(['items' => []])

<nav aria-label="Breadcrumb">
    <ol role="list" class="flex items-center space-x-2 text-sm">
        @php
            $isDashboard = count($items) === 1 && isset($items[0]['current']) && $items[0]['current'] === true && $items[0]['title'] === 'Admin';
        @endphp
        
        @if(!$isDashboard)
            <!-- Home link -->
            <li>
                <div class="flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 flex items-center">
                        <svg class="h-4 w-4 flex-shrink-0 mr-1.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd" />
                        </svg>
                        <span>Home</span>
                    </a>
                </div>
            </li>
        @endif
        
        @foreach($items as $item)
            <li>
                <div class="flex items-center">
                    @if(!$isDashboard || !$loop->first)
                        <svg class="h-4 w-4 flex-shrink-0 text-gray-400 dark:text-gray-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                        </svg>
                    @endif
                    
                    @if($isDashboard && $loop->first)
                        <span class="font-medium text-blue-600 dark:text-blue-400 flex items-center">
                            <svg class="h-4 w-4 flex-shrink-0 mr-1.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd" />
                            </svg>
                            {{ $item['title'] }}
                        </span>
                    @elseif(isset($item['url']))
                        <a href="{{ $item['url'] }}" class="{{ !$isDashboard || !$loop->first ? 'ml-1.5' : '' }} font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">{{ $item['title'] }}</a>
                    @else
                        <span class="{{ !$isDashboard || !$loop->first ? 'ml-1.5' : '' }} font-medium {{ isset($item['current']) && $item['current'] ? 'text-gray-700 dark:text-gray-300' : 'text-gray-500 dark:text-gray-400' }}">{{ $item['title'] }}</span>
                    @endif
                </div>
            </li>
        @endforeach
    </ol>
</nav>
