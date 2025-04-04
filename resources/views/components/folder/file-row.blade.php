@props(['file', 'folder'])

<tr>
    <td class="px-6 py-4 whitespace-nowrap w-2/5">
        <div class="flex items-center">
            <div class="flex-shrink-0 h-5 w-5">
                <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div class="ml-4 truncate">
                <button onclick="previewFile('{{ $file->name }}', '{{ $file->mime_type }}', '{{ $file->url }}')" 
                        class="text-sm font-medium text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400 text-left">
                    {{ $file->name }}
                </button>
            </div>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap w-1/12">
        <div class="text-sm text-gray-500 dark:text-gray-400">
            File
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap w-1/6">
        <div class="text-sm text-gray-500 dark:text-gray-400">
            {{ number_format($file->size / 1024, 2) }} KB
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap w-1/6">
        <div class="text-sm text-gray-500 dark:text-gray-400">
            {{ $file->uploader->name }}
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 w-1/6">
        {{ $file->created_at->format('M d, Y H:i') }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium w-1/12">
        <div class="flex items-center space-x-3 justify-end">
            <a href="{{ route('user.files.download', $file) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
            </a>
            <form action="{{ route('user.folders.files.destroy', [$folder, $file]) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" onclick="return confirm('Are you sure you want to delete this file?')">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </form>
        </div>
    </td>
</tr> 