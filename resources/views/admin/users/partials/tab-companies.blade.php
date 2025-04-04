<!-- Companies Tab -->
<div x-show="activeTab === 'companies'" class="space-y-6">
    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('User Companies') }}</h3>
    
    @if($user->companies->count() > 0)
        <x-tables.data-table :headers="['Name', 'Country', 'Tax ID', 'Created', 'Actions']">
            @foreach($user->companies as $company)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                <span class="text-sm font-medium text-blue-800 dark:text-blue-200">{{ substr($company->name, 0, 2) }}</span>
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
                        <div class="text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $company->tax_id ?? 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        {{ $company->created_at->format('M d, Y') }}
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
            icon="company" 
            title="{{ __('No Companies') }}" 
            message="{{ __('This user has not created any companies yet.') }}">
            <a href="{{ route('admin.companies.create') }}?user_id={{ $user->id }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                {{ __('Create Company') }}
            </a>
        </x-ui.empty-state>
    @endif
</div>
