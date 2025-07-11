<x-admin.layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.users.assign-companies.update', $accountant) }}">
                        @csrf

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('Available Companies') }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                {{ __('Select the companies that this accountant should have access to.') }}
                            </p>

                            <div class="mt-4 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($availableCompanies as $company)
                                        <div class="flex items-center">
                                            <input 
                                                id="company_{{ $company->id }}" 
                                                name="assigned_companies[]" 
                                                type="checkbox" 
                                                value="{{ $company->id }}" 
                                                {{ in_array($company->id, $assignedCompanies) ? 'checked' : '' }}
                                                class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-800 dark:focus:border-blue-700 dark:focus:ring-blue-700"
                                            >
                                            <label for="company_{{ $company->id }}" class="ml-2 block text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $company->name }} 
                                                <span class="text-xs text-gray-500 dark:text-gray-400">({{ $company->user->name }})</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <a href="{{ route('admin.users.show', $accountant) }}" class="inline-flex items-center mr-2 px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 active:bg-gray-400 dark:active:bg-gray-500 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Save Changes') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin.layout> 