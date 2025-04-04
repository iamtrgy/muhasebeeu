@props(['folder'])

<tr>
    <td class="px-6 py-4 whitespace-nowrap w-2/5">
        <div class="flex items-center">
            <div class="flex-shrink-0 h-5 w-5">
                <svg class="h-5 w-5 text-yellow-500" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                </svg>
            </div>
            <div class="ml-4 truncate">
                <a href="{{ route('user.folders.show', $folder) }}" class="text-sm font-medium text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400">
                    {{ $folder->name }}
                </a>
                @if($folder->activeChildrenCount() > 0)
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $folder->activeChildrenCount() }} {{ Str::plural('subfolder', $folder->activeChildrenCount()) }}
                    </div>
                @endif
            </div>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap w-1/12">
        <div class="text-sm text-gray-500 dark:text-gray-400">
            Folder
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap w-1/6">
        <div class="text-sm text-gray-500 dark:text-gray-400">
            {{ $folder->files_count }} {{ Str::plural('file', $folder->files_count) }}
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap w-1/6">
        <div class="text-sm text-gray-500 dark:text-gray-400">
            {{ optional($folder->creator)->name ?? 'System' }}
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 w-1/6">
        {{ $folder->created_at->format('M d, Y H:i') }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium w-1/12">
        <div class="flex items-center space-x-3 justify-end">
            <a href="{{ route('user.folders.show', $folder) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </a>
        </div>
    </td>
</tr> 