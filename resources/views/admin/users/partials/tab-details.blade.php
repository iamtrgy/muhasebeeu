<!-- Account Details Tab -->
<div x-show="activeTab === 'details'" class="space-y-6">
    @if($user->is_accountant)
        <!-- Assigned Users Section -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Assigned Users') }}</h3>
                <a href="{{ route('admin.users.assign', $user) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Manage Users') }}
                </a>
            </div>
            
            @if($user->assignedUsers->count() > 0)
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($user->assignedUsers as $assignedUser)
                        <li class="py-3 flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-800 flex items-center justify-center">
                                    <span class="text-sm font-medium text-blue-700 dark:text-blue-300">{{ substr($assignedUser->name, 0, 1) }}</span>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $assignedUser->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $assignedUser->email }}</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.users.show', $assignedUser) }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">{{ __('View') }}</a>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-center py-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No users assigned yet.') }}</p>
                    <a href="{{ route('admin.users.assign', $user) }}" class="mt-2 inline-flex items-center text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                        {{ __('Assign users now') }} →
                    </a>
                </div>
            @endif
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Account Statistics') }}</h3>
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6">
                <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Companies') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->companies->count() }}</dd>
                </div>
                <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Folders') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->folders->count() }}</dd>
                </div>
                <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Files') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->files->count() }}</dd>
                </div>
                <div class="pb-4">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Last Login') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                        @if(isset($lastLogin))
                            {{ $lastLogin->created_at->format('M d, Y H:i') }}
                            <span class="text-gray-500 dark:text-gray-400 text-xs">({{ $lastLogin->created_at->diffForHumans() }})</span>
                        @else
                            {{ __('Never') }}
                        @endif
                    </dd>
                </div>
            </dl>
        </div>
        
        <div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Recent Activity') }}</h3>
            @if(isset($activityLogs) && count($activityLogs) > 0)
                <ul class="space-y-4">
                    @foreach($activityLogs->take(5) as $log)
                        <li class="border-b border-gray-200 dark:border-gray-700 pb-3">
                            <div class="flex items-center">
                                <div class="h-8 w-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mr-3">
                                    @if(str_contains($log->action, 'login'))
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600 dark:text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                                        </svg>
                                    @elseif(str_contains($log->action, 'created'))
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600 dark:text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600 dark:text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm text-gray-900 dark:text-gray-100">{{ $log->action }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $log->created_at->format('M d, Y H:i') }}</p>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <div class="mt-4">
                    <button @click="activeTab = 'activity'" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                        {{ __('View all activity') }} →
                    </button>
                </div>
            @else
                <x-ui.empty-state 
                    icon="document" 
                    title="{{ __('No Recent Activity') }}" 
                    message="{{ __('This user has no recent activity.') }}">
                </x-ui.empty-state>
            @endif
        </div>
    </div>
</div>
