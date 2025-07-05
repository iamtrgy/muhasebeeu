<x-admin.layout 
    title="{{ __('Assign Accountants') }} - {{ $company->name }}"
    :breadcrumbs="[
        ['title' => __('Dashboard'), 'href' => route('admin.dashboard'), 'first' => true],
        ['title' => __('Companies'), 'href' => route('admin.companies.index')],
        ['title' => $company->name, 'href' => route('admin.companies.show', $company)],
        ['title' => __('Assign Accountants')]
    ]"
>
    <div class="space-y-6">
        <x-ui.card.base>
            <x-ui.card.header>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Assign Accountants') }}</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Select accountants who can access and manage this company.') }}</p>
            </x-ui.card.header>
            <x-ui.card.body>
                    <form method="POST" action="{{ route('admin.companies.update.accountants', $company) }}" class="space-y-6">
                        @csrf

                        <!-- Instructions -->
                        <div class="mb-4">
                            <x-ui.alert variant="info">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium">{{ __('Accountant Assignment Information') }}</p>
                                    <p class="text-sm mt-1">{{ __('Assigning an accountant to this company will automatically grant them access to the company owner\'s account (:ownerName).', ['ownerName' => $company->user->name]) }}</p>
                                </div>
                            </x-ui.alert>
                        </div>

                        <!-- Search Input -->
                        <div class="mb-4">
                            <label for="accountantSearch" class="sr-only">{{ __('Search Accountants') }}</label>
                            <x-ui.form.input
                                id="accountantSearch"
                                name="accountantSearch"
                                type="search"
                                placeholder="{{ __('Search accountants...') }}"
                                onkeyup="filterAccountants()"
                            >
                                <x-slot name="leadingIcon">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </x-slot>
                            </x-ui.form.input>
                        </div>

                        <!-- No accountants message -->
                        @if($availableAccountants->isEmpty())
                            <x-ui.alert variant="warning">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <h3 class="text-sm font-medium">{{ __('No accountants available') }}</h3>
                                    <div class="mt-2 text-sm">
                                        <p>{{ __('There are no accountants in the system. Please create accountant users first.') }}</p>
                                    </div>
                                    <div class="mt-4">
                                        <x-ui.button.primary href="{{ route('admin.users.create') }}" size="sm">
                                            {{ __('Create User') }}
                                        </x-ui.button.primary>
                                    </div>
                                </div>
                            </x-ui.alert>
                        @else
                            <!-- Accountant List -->
                            <div class="space-y-4 max-h-96 overflow-y-auto">
                                @foreach($availableAccountants as $accountant)
                                    <div class="accountant-item flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                        <x-ui.form.checkbox
                                            id="accountant-{{ $accountant->id }}"
                                            name="assigned_accountants[]"
                                            value="{{ $accountant->id }}"
                                            :checked="in_array($accountant->id, $assignedAccountants)"
                                        >
                                            <div>
                                                <span class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $accountant->name }}
                                                </span>
                                                <span class="block text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $accountant->email }}
                                                </span>
                                            </div>
                                        </x-ui.form.checkbox>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end">
                                <x-ui.button.primary type="submit">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    {{ __('Save Assignments') }}
                                </x-ui.button.primary>
                            </div>
                        @endif
                    </form>

                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <x-ui.button.secondary href="{{ route('admin.companies.show', $company) }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            {{ __('Back to Company') }}
                        </x-ui.button.secondary>
                    </div>
            </x-ui.card.body>
        </x-ui.card.base>
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
</x-admin.layout> 