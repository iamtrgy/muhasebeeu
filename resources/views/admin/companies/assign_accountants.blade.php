<x-admin-layout>
    <x-slot name="header">
        <x-admin.page-title 
            title="{{ __('Assign Accountants') }}" 
            description="{{ $company->name }}"
        ></x-admin.page-title>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('admin.companies.update.accountants', $company) }}" class="space-y-6">
                        @csrf

                        <!-- Instructions -->
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Select the accountants you want to assign to this company. These accountants will be able to access and manage this company\'s data.') }}
                            </p>
                            <div class="mt-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-md">
                                <p class="text-sm text-blue-800 dark:text-blue-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                    {{ __('Note: Assigning an accountant to this company will automatically grant them access to the company owner\'s account (:ownerName).', ['ownerName' => $company->user->name]) }}
                                </p>
                            </div>
                        </div>

                        <!-- Search Input -->
                        <div class="mb-4">
                            <label for="accountantSearch" class="sr-only">{{ __('Search Accountants') }}</label>
                            <input
                                type="text"
                                id="accountantSearch"
                                class="form-input"
                                placeholder="{{ __('Search accountants...') }}"
                                onkeyup="filterAccountants()"
                            >
                        </div>

                        <!-- No accountants message -->
                        @if($availableAccountants->isEmpty())
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-md">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">{{ __('No accountants available') }}</h3>
                                        <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                            <p>
                                                {{ __('There are no accountants in the system. Please create accountant users first.') }}
                                            </p>
                                        </div>
                                        <div class="mt-4">
                                            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                {{ __('Create User') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Accountant List -->
                            <div class="space-y-4 max-h-96 overflow-y-auto">
                                @foreach($availableAccountants as $accountant)
                                    <div class="accountant-item flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                        <input
                                            type="checkbox"
                                            id="accountant-{{ $accountant->id }}"
                                            name="assigned_accountants[]"
                                            value="{{ $accountant->id }}"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                            {{ in_array($accountant->id, $assignedAccountants) ? 'checked' : '' }}
                                        >
                                        <label for="accountant-{{ $accountant->id }}" class="ml-3 block">
                                            <span class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $accountant->name }}
                                            </span>
                                            <span class="block text-sm text-gray-500 dark:text-gray-400">
                                                {{ $accountant->email }}
                                            </span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end">
                                <button
                                    type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                >
                                    {{ __('Save Assignments') }}
                                </button>
                            </div>
                        @endif
                    </form>

                    <div class="mt-6">
                        <a href="{{ route('admin.companies.show', $company) }}" class="text-sm text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                            &larr; {{ __('Back to Company') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function filterAccountants() {
            const input = document.getElementById('accountantSearch');
            const filter = input.value.toLowerCase();
            const accountants = document.getElementsByClassName('accountant-item');
            
            for (let i = 0; i < accountants.length; i++) {
                const accountant = accountants[i];
                const text = accountant.textContent || accountant.innerText;
                
                if (text.toLowerCase().indexOf(filter) > -1) {
                    accountant.style.display = "";
                } else {
                    accountant.style.display = "none";
                }
            }
        }
    </script>
</x-admin-layout> 