@props(['folder'])

<nav class="flex min-w-full" aria-label="Breadcrumb">
    <ol role="list" class="flex items-center space-x-2">
        <li>
            <a href="{{ route('user.dashboard') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 dark:hover:text-gray-300">
                Home
            </a>
        </li>
        <li class="text-gray-400">/</li>
        <li>
            <a href="{{ route('user.folders.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 dark:hover:text-gray-300">
                Folders
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
            <li class="text-gray-400">/</li>
            <li>
                <a href="{{ route('user.folders.show', $ancestor) }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 dark:hover:text-gray-300">
                    {{ $ancestor->name }}
                </a>
            </li>
        @endforeach

        <li class="text-gray-400">/</li>
        <li>
            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $folder->name }}</span>
        </li>
    </ol>
</nav>