@props(['folder'])

<nav class="flex min-w-full" aria-label="Breadcrumb">
    <ol role="list" class="flex items-center space-x-4">
        <li>
            <a href="{{ $folder->parent_id ? route('user.folders.show', $folder->parent) : route('user.folders.index') }}" class="flex items-center">
                <svg class="w-5 h-5 text-gray-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span class="text-sm font-medium text-gray-500 hover:text-gray-900 dark:hover:text-gray-300">Back</span>
            </a>
        </li>
        
        <li>
            <a href="{{ route('user.folders.index') }}" class="flex items-center">
                <span class="text-gray-400 mx-1.5">/</span>
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                    </svg>
                    <span class="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">Folders</span>
                </div>
            </a>
        </li>
        
        @php
            $ancestors = collect([]);
            $current = $folder;
            while($current->parent) {
                $ancestors->prepend($current->parent);
                $current = $current->parent;
            }
        @endphp

        @foreach($ancestors as $ancestor)
            <li>
                <a href="{{ route('user.folders.show', $ancestor) }}" class="flex items-center">
                    <span class="text-gray-400 mx-1.5">/</span>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                        <span class="text-sm font-medium text-gray-500 hover:text-gray-900 dark:hover:text-gray-300">{{ $ancestor->name }}</span>
                    </div>
                </a>
            </li>
        @endforeach

        <li>
            <div class="flex items-center">
                <span class="text-gray-400 mx-1.5">/</span>
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-gray-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                    </svg>
                    <span class="text-sm font-medium text-gray-500">{{ $folder->name }}</span>
                </div>
            </div>
        </li>
    </ol>
</nav> 