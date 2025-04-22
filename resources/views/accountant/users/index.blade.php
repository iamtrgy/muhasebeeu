<x-app-layout>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb Navigation -->
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="px-4 py-4 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center text-sm">
                        <a href="{{ route('accountant.dashboard') }}" class="flex items-center text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                            <svg class="w-5 h-5 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                            </svg>
                            {{ __('Dashboard') }}
                        </a>
                        
                        <span class="mx-2 text-gray-400">/</span>
                        
                        <span class="text-gray-900 dark:text-gray-100">{{ __('Users') }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Users Section -->
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Assigned Users') }}</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Users assigned to your account') }}</p>
                    </div>
                </div>
                
                <div class="border-t border-gray-200 dark:border-gray-700">
                    @if($users->count() > 0)
                        <x-admin.table>
                            <x-slot name="header">
                                <x-admin.table.tr>
                                    <x-admin.table.th>{{ __('User') }}</x-admin.table.th>
                                    <x-admin.table.th>{{ __('Email') }}</x-admin.table.th>
                                    <x-admin.table.th>{{ __('Companies') }}</x-admin.table.th>
                                </x-admin.table.tr>
                            </x-slot>

                            @foreach($users as $user)
                                <x-admin.table.tr>
                                    <x-admin.table.td>
                                        <a href="{{ route('accountant.users.show', $user) }}" class="flex items-center hover:text-blue-600 dark:hover:text-blue-400">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-800 flex items-center justify-center">
                                                <span class="text-lg font-medium text-blue-700 dark:text-blue-300">{{ substr($user->name, 0, 1) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $user->name }}
                                                </div>
                                            </div>
                                        </a>
                                    </x-admin.table.td>
                                    <x-admin.table.td>
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $user->email }}</div>
                                    </x-admin.table.td>
                                    <x-admin.table.td>
                                        @if($user->companies->count() > 0)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                {{ $user->companies->count() }}
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                0
                                            </span>
                                        @endif
                                    </x-admin.table.td>
                                </x-admin.table.tr>
                            @endforeach
                        </x-admin.table>
                    @else
                        <div class="py-8 text-center">
                            <x-icons name="user" class="mx-auto h-12 w-12 text-gray-400" />
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('No Users Assigned') }}</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('You do not have any users assigned to your account yet.') }}</p>
                        </div>
                    @endif

                    @if($users instanceof \Illuminate\Pagination\LengthAwarePaginator && $users->hasPages())
                        <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-3">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 