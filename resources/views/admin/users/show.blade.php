<x-admin-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div x-data="{ activeTab: window.location.hash ? window.location.hash.substring(1) : 'profile' }" class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="flex -mb-px">
                        <button @click="activeTab = 'profile'; window.location.hash = 'profile'" :class="{ 'border-blue-500 text-blue-600 dark:text-blue-400': activeTab === 'profile', 'border-transparent text-gray-500 dark:text-gray-400': activeTab !== 'profile' }" class="px-6 py-4 text-sm font-medium border-b-2 focus:outline-none focus:text-blue-800 focus:border-blue-700">
                            {{ __('Profile') }}
                        </button>
                        <button @click="activeTab = 'folders'; window.location.hash = 'folders'" :class="{ 'border-blue-500 text-blue-600 dark:text-blue-400': activeTab === 'folders', 'border-transparent text-gray-500 dark:text-gray-400': activeTab !== 'folders' }" class="px-6 py-4 text-sm font-medium border-b-2 focus:outline-none focus:text-blue-800 focus:border-blue-700">
                            {{ __('Folders') }}
                        </button>
                        <button @click="activeTab = 'companies'; window.location.hash = 'companies'" :class="{ 'border-blue-500 text-blue-600 dark:text-blue-400': activeTab === 'companies', 'border-transparent text-gray-500 dark:text-gray-400': activeTab !== 'companies' }" class="px-6 py-4 text-sm font-medium border-b-2 focus:outline-none focus:text-blue-800 focus:border-blue-700">
                            {{ __('Companies') }}
                        </button>
                        @if($user->is_accountant)
                            <button @click="activeTab = 'assignments'; window.location.hash = 'assignments'" :class="{ 'border-blue-500 text-blue-600 dark:text-blue-400': activeTab === 'assignments', 'border-transparent text-gray-500 dark:text-gray-400': activeTab !== 'assignments' }" class="px-6 py-4 text-sm font-medium border-b-2 focus:outline-none focus:text-blue-800 focus:border-blue-700">
                                {{ __('Accountant Assignments') }}
                            </button>
                        @endif
                    </nav>
                </div>

                <!-- Profile Tab -->
                <div x-show="activeTab === 'profile'" class="p-6" x-cloak>
                    <div class="sm:flex sm:items-center sm:justify-between">
                        <div class="sm:flex sm:items-center">
                            <div class="flex-shrink-0 h-20 w-20 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                <span class="text-2xl font-medium text-blue-800 dark:text-blue-200">{{ substr($user->name, 0, 2) }}</span>
                            </div>
                            <div class="mt-4 sm:mt-0 sm:ml-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                                <div class="mt-1 flex">
                                    @if($user->is_admin)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                            {{ __('Admin') }}
                                        </span>
                                    @endif
                                    @if($user->is_accountant)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 ml-2">
                                            {{ __('Accountant') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Email') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->email }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Email verified at') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $user->email_verified_at ? $user->email_verified_at->format('M d, Y H:i') : 'Not verified' }}
                                </dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Country') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->country->name ?? 'N/A' }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Created at') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $user->created_at->format('M d, Y') }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Subscription status') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    @if($user->hasActiveSubscription())
                                        <span class="text-green-600 dark:text-green-400">{{ __('Active') }}</span>
                                    @else
                                        <span class="text-red-600 dark:text-red-400">{{ __('Inactive') }}</span>
                                    @endif
                                </dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Onboarding status') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    @if($user->onboarding_completed)
                                        <span class="text-green-600 dark:text-green-400">{{ __('Completed') }}</span>
                                    @else
                                        <span class="text-yellow-600 dark:text-yellow-400">{{ __('Incomplete') }} - Step {{ $user->onboarding_step ?? 0 }}</span>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
                        @if(!$user->email_verified_at)
                            <form method="POST" action="{{ route('admin.users.verify', $user) }}">
                                @csrf
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    {{ __('Verify Email') }}
                                </button>
                            </form>
                        @endif
                        
                        <a href="{{ route('admin.users.subscription.manage', $user) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                                <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" />
                            </svg>
                            {{ __('Manage Subscription') }}
                        </a>
                    </div>
                </div>

                <!-- Folders Tab -->
                <div x-show="activeTab === 'folders'" class="p-6" x-cloak>
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('User\'s Folders') }}</h3>
                    </div>
                    
                    @if(isset($folders) && $folders->count() > 0)
                        <x-tables.data-table :headers="['Name', 'Files Count', 'Created', 'Actions']">
                            @foreach($folders as $folder)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <svg class="h-6 w-6 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $folder->name }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">ID: {{ $folder->id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $folder->files->count() }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $folder->created_at->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $folder->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('admin.folders.show', $folder) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                            {{ __('View') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </x-tables.data-table>
                    @else
                        <x-ui.empty-state 
                            icon="folder" 
                            title="{{ __('No Folders') }}" 
                            message="{{ __('This user has no folders.') }}">
                        </x-ui.empty-state>
                    @endif
                </div>

                <!-- Companies Tab -->
                <div x-show="activeTab === 'companies'" class="p-6" x-cloak>
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('User\'s Companies') }}</h3>
                        <a href="{{ route('admin.companies.create') }}?user_id={{ $user->id }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            {{ __('Add Company') }}
                        </a>
                    </div>
                    
                    @if($user->companies->count() > 0)
                        <x-tables.data-table :headers="['Name', 'Country', 'Tax Number', 'Actions']">
                            @foreach($user->companies as $company)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                                <span class="text-sm font-medium text-green-800 dark:text-green-200">{{ substr($company->name, 0, 2) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $company->name }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $company->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $company->country->name ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $company->tax_number ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('admin.companies.show', $company) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">
                                            {{ __('View') }}
                                        </a>
                                        <a href="{{ route('admin.companies.edit', $company) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                            {{ __('Edit') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </x-tables.data-table>
                    @else
                        <x-ui.empty-state 
                            icon="office-building" 
                            title="{{ __('No Companies') }}" 
                            message="{{ __('This user has not created any companies yet.') }}">
                        </x-ui.empty-state>
                    @endif
                </div>

                <!-- Accountant Assignments Tab -->
                @if($user->is_accountant)
                    <div x-show="activeTab === 'assignments'" class="p-6" x-cloak>
                        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                            <!-- Assigned Users -->
                            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                                <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                                    <div>
                                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Assigned Users') }}</h3>
                                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Users this accountant can manage.') }}</p>
                                    </div>
                                    <a href="{{ route('admin.users.assign', $user) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        {{ __('Assign Users') }}
                                    </a>
                                </div>
                                
                                <div class="border-t border-gray-200 dark:border-gray-700 p-4">
                                    @if($user->assignedUsers->count() > 0)
                                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($user->assignedUsers as $assignedUser)
                                                <li class="py-3 flex items-center justify-between">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-8 w-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                                            <span class="text-xs font-medium text-blue-800 dark:text-blue-200">{{ substr($assignedUser->name, 0, 2) }}</span>
                                                        </div>
                                                        <div class="ml-3">
                                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $assignedUser->name }}</p>
                                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $assignedUser->email }}</p>
                                                        </div>
                                                    </div>
                                                    <a href="{{ route('admin.users.show', $assignedUser) }}" class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                        {{ __('View') }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="text-center py-4">
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No users assigned yet.') }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Assigned Companies -->
                            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                                <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                                    <div>
                                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Assigned Companies') }}</h3>
                                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Companies this accountant can manage.') }}</p>
                                    </div>
                                    <a href="{{ route('admin.users.assign-companies', $user) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        {{ __('Assign Companies') }}
                                    </a>
                                </div>
                                
                                <div class="border-t border-gray-200 dark:border-gray-700 p-4">
                                    @if($user->assignedCompanies->count() > 0)
                                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($user->assignedCompanies as $assignedCompany)
                                                <li class="py-3 flex items-center justify-between">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-8 w-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                                            <span class="text-xs font-medium text-green-800 dark:text-green-200">{{ substr($assignedCompany->name, 0, 2) }}</span>
                                                        </div>
                                                        <div class="ml-3">
                                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $assignedCompany->name }}</p>
                                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $assignedCompany->user->name }}</p>
                                                        </div>
                                                    </div>
                                                    <a href="{{ route('admin.companies.show', $assignedCompany) }}" class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                        {{ __('View') }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="text-center py-4">
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No companies assigned yet.') }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout> 