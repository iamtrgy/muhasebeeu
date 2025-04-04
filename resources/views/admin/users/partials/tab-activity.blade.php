<!-- Activity Log Tab -->
<div x-show="activeTab === 'activity'" class="space-y-6">
    <div class="relative">
        <div class="absolute inset-0 flex items-center" aria-hidden="true">
            <div class="w-full border-t border-gray-300 dark:border-gray-700"></div>
        </div>
        <div class="relative flex justify-center">
            <span class="bg-white dark:bg-gray-800 px-3 text-sm text-gray-500 dark:text-gray-400">{{ __('Activity Timeline') }}</span>
        </div>
    </div>
    
    <div class="flow-root">
        <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 mb-4">
                    {{ __('User Activity Timeline') }}
                </h3>
                
                @if(isset($activityLogs) && count($activityLogs) > 0)
                    <ul class="space-y-6">
                        @foreach($activityLogs as $log)
                            <li class="relative">
                                <div class="relative flex items-start space-x-3">
                                    <div class="relative">
                                        <div class="h-8 w-8 bg-blue-100 dark:bg-blue-900 rounded-full ring-8 ring-white dark:ring-gray-800 flex items-center justify-center">
                                            @if(str_contains($log->action, 'login'))
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 dark:text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                                                </svg>
                                            @elseif(str_contains($log->action, 'created'))
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 dark:text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                                </svg>
                                            @elseif(str_contains($log->action, 'updated'))
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 dark:text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                </svg>
                                            @elseif(str_contains($log->action, 'deleted'))
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 dark:text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 dark:text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                                </svg>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $log->action }}
                                            </div>
                                            <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $log->created_at->format('M d, Y H:i:s') }}
                                            </p>
                                        </div>
                                        @if($log->properties)
                                            <div class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                                                <div class="bg-gray-50 dark:bg-gray-700 rounded p-2 font-mono text-xs overflow-x-auto">
                                                    @php
                                                        $properties = json_decode($log->properties, true);
                                                    @endphp
                                                    @if(is_array($properties))
                                                        @foreach($properties as $key => $value)
                                                            <div class="mb-1">
                                                                <span class="font-semibold">{{ $key }}:</span> 
                                                                @if(is_array($value))
                                                                    <pre>{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                                                @else
                                                                    {{ $value }}
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        {{ $log->properties }}
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <x-ui.empty-state 
                        icon="document" 
                        title="{{ __('No Activity Logs') }}" 
                        message="{{ __('No activity logs found for this user.') }}">
                    </x-ui.empty-state>
                @endif
            </div>
        </div>
    </div>
</div>
