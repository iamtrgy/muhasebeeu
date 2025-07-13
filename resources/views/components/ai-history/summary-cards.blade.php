@props([
    'totalAnalyses' => 0,
    'acceptedCount' => 0,
    'avgConfidence' => 0,
    'lastAnalysis' => null
])

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-3">
    <x-ui.card.base :padding="false">
        <div class="p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Analyses</p>
                    <p class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ $totalAnalyses }}</p>
                </div>
            </div>
        </div>
    </x-ui.card.base>

    <x-ui.card.base :padding="false">
        <div class="p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Accepted</p>
                    <p class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ $acceptedCount }}</p>
                </div>
            </div>
        </div>
    </x-ui.card.base>

    <x-ui.card.base :padding="false">
        <div class="p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Avg Confidence</p>
                    <p class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ $avgConfidence }}%</p>
                </div>
            </div>
        </div>
    </x-ui.card.base>

    <x-ui.card.base :padding="false">
        <div class="p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Analysis</p>
                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                        {{ $lastAnalysis ? $lastAnalysis->diffForHumans() : 'Never' }}
                    </p>
                </div>
            </div>
        </div>
    </x-ui.card.base>
</div>