<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="px-4 py-4 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center text-sm">
                        <x-tax-calendar.breadcrumb />
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Tax Calendar
                            <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">Next 30 days</span>
                        </h2>
                    </div>

                    <div class="space-y-6">
                        @foreach($deadlines as $deadline)
                            @php
                                $urgencyClass = match($deadline['urgency']) {
                                    'overdue' => 'border-l-4 border-red-500 bg-gradient-to-r from-red-50 to-transparent dark:from-red-900/10',
                                    'urgent' => 'border-l-4 border-red-500 bg-gradient-to-r from-red-50 to-transparent dark:from-red-900/10',
                                    'warning' => 'border-l-4 border-yellow-500 bg-gradient-to-r from-yellow-50 to-transparent dark:from-yellow-900/10',
                                    'completed' => 'border-l-4 border-gray-300 bg-gradient-to-r from-gray-50 to-transparent dark:from-gray-900/10',
                                    default => 'border-l-4 border-green-500 bg-gradient-to-r from-green-50 to-transparent dark:from-green-900/10'
                                };

                                $statusClass = match($deadline['status'] ?? 'pending') {
                                    'approved' => 'bg-green-100 text-green-800 ring-1 ring-green-600/20 dark:bg-green-900/10 dark:text-green-400 dark:ring-green-500/20',
                                    'completed' => 'bg-green-100 text-green-800 ring-1 ring-green-600/20 dark:bg-green-900/10 dark:text-green-400 dark:ring-green-500/20',
                                    'pending' => 'bg-yellow-100 text-yellow-800 ring-1 ring-yellow-600/20 dark:bg-yellow-900/10 dark:text-yellow-400 dark:ring-yellow-500/20',
                                    'overdue' => 'bg-red-100 text-red-800 ring-1 ring-red-600/20 dark:bg-red-900/10 dark:text-red-400 dark:ring-red-500/20',
                                    'under_review' => 'bg-blue-100 text-blue-800 ring-1 ring-blue-600/20 dark:bg-blue-900/10 dark:text-blue-400 dark:ring-blue-500/20',
                                    'changes_requested' => 'bg-orange-100 text-orange-800 ring-1 ring-orange-600/20 dark:bg-orange-900/10 dark:text-orange-400 dark:ring-orange-500/20',
                                    'rejected' => 'bg-red-100 text-red-800 ring-1 ring-red-600/20 dark:bg-red-900/10 dark:text-red-400 dark:ring-red-500/20',
                                    default => 'bg-gray-100 text-gray-800 ring-1 ring-gray-600/20 dark:bg-gray-900/10 dark:text-gray-400 dark:ring-gray-500/20'
                                };

                                $statusText = match($deadline['status'] ?? 'pending') {
                                    'approved' => 'Approved',
                                    'completed' => 'Completed',
                                    'pending' => 'In Progress',
                                    'overdue' => 'Overdue',
                                    'under_review' => 'Waiting for Review',
                                    'changes_requested' => 'Changes Requested',
                                    'rejected' => 'Rejected',
                                    default => 'Unknown'
                                };

                                $dueText = $deadline['status'] === 'completed' ? 'Completed' : (
                                    $deadline['days_until'] < 0 ? 'Past due' : (
                                    $deadline['days_until'] === 0 ? 'Due today' : 
                                    'Due in ' . $deadline['days_until'] . ' days' . ($deadline['days_until'] <= 3 ? ' (Urgent!)' : ''))
                                );
                            @endphp
                            
                            <div class="{{ $urgencyClass }} rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                                <div class="p-5">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                                    {{ $deadline['name'] }}
                                                </h3>
                                                @if($deadline['form_code'])
                                                    <span class="px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-600 rounded-md dark:bg-gray-700 dark:text-gray-300">
                                                        {{ $deadline['form_code'] }}
                                                    </span>
                                                @endif
                                                <span class="px-2.5 py-0.5 text-xs font-medium {{ $statusClass }} rounded-full">
                                                    {{ $statusText }}
                                                </span>
                                            </div>
                                            
                                            <div class="flex items-center gap-2 mb-3">
                                                <span class="text-sm text-gray-600 dark:text-gray-400 flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    {{ $dueText }} ({{ $deadline['next_deadline']->format('M j, Y') }})
                                                </span>
                                                @if($deadline['next_payment'])
                                                    <span class="text-sm text-gray-500 dark:text-gray-400 flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                                        </svg>
                                                        Payment due: {{ $deadline['next_payment']->format('M j, Y') }}
                                                    </span>
                                                @endif
                                            </div>

                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                                {{ $deadline['description'] }}
                                            </p>

                                            @if($deadline['emta_link'])
                                                <a href="{{ $deadline['emta_link'] }}" target="_blank" rel="noopener noreferrer" 
                                                   class="inline-flex items-center text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors duration-200">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    EMTA Guide
                                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                    </svg>
                                                </a>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Progress Bar -->
                                    @if(isset($deadline['progress']))
                                        <div class="mt-4">
                                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 overflow-hidden">
                                                <div class="h-2 rounded-full transition-all duration-500 ease-out
                                                    {{ $deadline['progress'] >= 100 ? 'bg-green-500' : 'bg-blue-500' }}"
                                                     style="width: {{ $deadline['progress'] }}%"
                                                     title="{{ $deadline['progress'] }}% complete">
                                                </div>
                                            </div>
                                            <div class="mt-2 flex justify-between items-center">
                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $deadline['completed_tasks'] ?? 0 }}/{{ $deadline['total_tasks'] ?? 0 }} tasks completed
                                                </span>
                                                <span class="text-xs font-medium {{ $deadline['progress'] >= 100 ? 'text-green-600 dark:text-green-400' : 'text-blue-600 dark:text-blue-400' }}">
                                                    {{ number_format($deadline['progress'], 1) }}%
                                                </span>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- View Details Button -->
                                    <div class="mt-4 flex justify-end">
                                        <a href="{{ route('user.tax-calendar.show', $deadline['id']) }}" 
                                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 rounded-md transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            View Details
                                            <svg class="ml-2 -mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if(isset($deadlines) && method_exists($deadlines, 'links'))
                        <div class="mt-6">
                            {{ $deadlines->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('filterStatus').addEventListener('change', function() {
            window.location.href = updateQueryStringParameter(window.location.href, 'status', this.value);
        });

        document.getElementById('filterMonth').addEventListener('change', function() {
            window.location.href = updateQueryStringParameter(window.location.href, 'month', this.value);
        });

        function updateQueryStringParameter(uri, key, value) {
            var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
            var separator = uri.indexOf('?') !== -1 ? "&" : "?";
            if (uri.match(re)) {
                return uri.replace(re, '$1' + key + "=" + value + '$2');
            }
            else {
                return uri + separator + key + "=" + value;
            }
        }
    </script>
    @endpush
</x-app-layout> 