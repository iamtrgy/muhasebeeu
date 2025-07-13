@props([
    'currentTab' => 'analyzed',
    'tabCounts' => []
])

<!-- Tabs -->
<div class="border-b border-gray-200 dark:border-gray-700">
    <nav class="-mb-px flex space-x-6 px-4 pt-3" aria-label="Tabs">
        <a href="{{ route('user.ai-analysis.history', ['tab' => 'analyzed'] + request()->only(['search', 'date_from', 'date_to', 'folder', 'confidence', 'status'])) }}" 
           class="{{ $currentTab === 'analyzed' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm">
            Analyzed <span class="text-xs text-gray-400">({{ $tabCounts['analyzed'] ?? 0 }})</span>
        </a>
        <a href="{{ route('user.ai-analysis.history', ['tab' => 'not_analyzed'] + request()->only(['search', 'date_from', 'date_to', 'folder', 'confidence', 'status'])) }}"
           class="{{ $currentTab === 'not_analyzed' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm">
            Not Analyzed <span class="text-xs text-gray-400">({{ $tabCounts['not_analyzed'] ?? 0 }})</span>
        </a>
        <a href="{{ route('user.ai-analysis.history', ['tab' => 'all'] + request()->only(['search', 'date_from', 'date_to', 'folder', 'confidence', 'status'])) }}"
           class="{{ $currentTab === 'all' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm">
            All Files <span class="text-xs text-gray-400">({{ $tabCounts['all'] ?? 0 }})</span>
        </a>
    </nav>
</div>