@props(['tabs' => [], 'currentTab' => null])

<div class="border-b border-gray-200 dark:border-gray-700">
    <nav class="flex space-x-4" aria-label="Tabs">
        @foreach ($tabs as $tab)
            @php
                $isActive = $currentTab === $tab['id'];
                // Exact styling from user's requirements
                $activeClass = $isActive 
                    ? 'inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 dark:text-blue-400 border-b-2 border-blue-600 dark:border-blue-400'
                    : 'inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 dark:text-gray-400 border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300';
            @endphp
            
            <a 
                href="{{ $tab['url'] }}" 
                class="{{ $activeClass }}"
                @if($isActive) aria-current="page" @endif
            >
                @if(isset($tab['icon']))
                    <span class="mr-2">{{ $tab['icon'] }}</span>
                @endif
                {{ $tab['label'] }}
            </a>
        @endforeach
    </nav>
</div>
