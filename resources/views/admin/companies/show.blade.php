<x-admin.layout 
    title="{{ $company->name }}"
    :breadcrumbs="[
        ['title' => __('Dashboard'), 'href' => route('admin.dashboard'), 'first' => true],
        ['title' => __('Companies'), 'href' => route('admin.companies.index')],
        ['title' => $company->name]
    ]"
>
    <div class="space-y-6" x-data>
        {{-- Company Overview Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <x-ui.card.base class="hover:shadow-lg transition-shadow">
                <x-ui.card.body class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-500 rounded-lg p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Company Status') }}</div>
                            <div class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ __('Active') }}</div>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <x-ui.button.primary size="sm" href="{{ route('admin.companies.edit', $company) }}" class="w-full">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            {{ __('Edit Details') }}
                        </x-ui.button.primary>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            <x-ui.card.base class="hover:shadow-lg transition-shadow">
                <x-ui.card.body class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-emerald-500 rounded-lg p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Accountants') }}</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $company->accountants->count() }}</div>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('admin.companies.assign.accountants', $company) }}" class="inline-flex items-center justify-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 shadow-sm transition ease-in-out duration-150 w-full">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            {{ __('Manage Access') }}
                        </a>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            <x-ui.card.base class="hover:shadow-lg transition-shadow">
                <x-ui.card.body class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-amber-500 rounded-lg p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Owner') }}</div>
                            <div class="text-lg font-bold text-gray-900 dark:text-gray-100 truncate">{{ $company->user->name }}</div>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <x-ui.button.secondary size="sm" href="{{ route('admin.users.show', $company->user) }}" class="w-full">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            {{ __('View Profile') }}
                        </x-ui.button.secondary>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>

            <x-ui.card.base class="hover:shadow-lg transition-shadow">
                <x-ui.card.body class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-500 rounded-lg p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5l-6.928-12c-.77-.833-2.694-.833-3.464 0l-6.928 12C.436 14.333 1.398 16 2.938 16z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Admin Actions') }}</div>
                            <div class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ __('Manage') }}</div>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <x-ui.button.danger size="sm" x-on:click="$dispatch('open-modal', 'delete-company-modal')" class="w-full">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            {{ __('Delete') }}
                        </x-ui.button.danger>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>
        </div>

        {{-- Company Information --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Basic Information --}}
            <x-ui.card.base>
                <x-ui.card.header>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Company Information') }}</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Basic company details and legal information') }}</p>
                </x-ui.card.header>
                <x-ui.card.body>
                    <dl class="space-y-4">
                        <div class="flex flex-col sm:flex-row sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-32 flex-shrink-0">{{ __('Company Name') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 font-medium">{{ $company->name }}</dd>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-32 flex-shrink-0">{{ __('Tax Number') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 font-mono">{{ $company->tax_number ?? 'N/A' }}</dd>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-32 flex-shrink-0">{{ __('VAT Number') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 font-mono">{{ $company->vat_number ?? 'N/A' }}</dd>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-32 flex-shrink-0">{{ __('Country') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0">{{ $company->country->name ?? 'N/A' }}</dd>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-32 flex-shrink-0">{{ __('Foundation Date') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0">
                                {{ $company->foundation_date ? $company->foundation_date->format('M d, Y') : 'N/A' }}
                            </dd>
                        </div>
                    </dl>
                </x-ui.card.body>
            </x-ui.card.base>

            {{-- Contact Information --}}
            <x-ui.card.base>
                <x-ui.card.header>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Contact Details') }}</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Company contact information and address') }}</p>
                </x-ui.card.header>
                <x-ui.card.body>
                    <dl class="space-y-4">
                        <div class="flex flex-col sm:flex-row sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-24 flex-shrink-0">{{ __('Email') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0">
                                @if($company->email)
                                    <a href="mailto:{{ $company->email }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        {{ $company->email }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </dd>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-24 flex-shrink-0">{{ __('Phone') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0">
                                @if($company->phone)
                                    <a href="tel:{{ $company->phone }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        {{ $company->phone }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </dd>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-24 flex-shrink-0">{{ __('Address') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0">{{ $company->address ?? 'N/A' }}</dd>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 sm:w-24 flex-shrink-0">{{ __('Owner') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0">
                                <div class="flex items-center">
                                    <x-ui.avatar :name="$company->user->name" size="sm" class="mr-2" />
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-gray-100">
                                            <a href="{{ route('admin.users.show', $company->user) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                {{ $company->user->name }}
                                            </a>
                                        </div>
                                        <a href="mailto:{{ $company->user->email }}" class="text-xs text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">{{ $company->user->email }}</a>
                                    </div>
                                </div>
                            </dd>
                        </div>
                    </dl>
                </x-ui.card.body>
            </x-ui.card.base>
        </div>

        {{-- Assigned Accountants --}}
        @if($company->accountants->count() > 0)
            <x-ui.card.base>
                <x-ui.card.header>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('Assigned Accountants') }}</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Accountants who can access this company') }}</p>
                </x-ui.card.header>
                <x-ui.card.body>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($company->accountants as $accountant)
                            <div class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                <x-ui.avatar name="{{ $accountant->name }}" size="md" class="mr-3" />
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                        <a href="{{ route('admin.users.show', $accountant) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                            {{ $accountant->name }}
                                        </a>
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $accountant->email }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>
        @else
            <x-ui.card.base>
                <x-ui.card.body>
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                        </svg>
                        <h3 class="mt-4 text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('No Accountants Assigned') }}</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('This company has no assigned accountants yet.') }}</p>
                        <div class="mt-6">
                            <a href="{{ route('admin.companies.assign.accountants', $company) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                {{ __('Assign Accountants') }}
                            </a>
                        </div>
                    </div>
                </x-ui.card.body>
            </x-ui.card.base>
        @endif

        {{-- System Information --}}
        <x-ui.card.base>
            <x-ui.card.header>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">{{ __('System Information') }}</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">{{ __('Administrative metadata and audit trail') }}</p>
            </x-ui.card.header>
            <x-ui.card.body>
                <dl class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="flex flex-col">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Created On') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                            <div class="font-medium">{{ $company->created_at->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $company->created_at->format('H:i:s') }} • {{ $company->created_at->diffForHumans() }}</div>
                        </dd>
                    </div>
                    <div class="flex flex-col">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Last Updated') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                            <div class="font-medium">{{ $company->updated_at->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $company->updated_at->format('H:i:s') }} • {{ $company->updated_at->diffForHumans() }}</div>
                        </dd>
                    </div>
                    <div class="flex flex-col">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Company ID') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                            <div class="font-mono">#{{ $company->id }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('Internal Reference') }}</div>
                        </dd>
                    </div>
                </dl>
            </x-ui.card.body>
        </x-ui.card.base>
    </div>

    {{-- Delete Confirmation Modal --}}
    <x-ui.modal.base name="delete-company-modal">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5l-6.928-12c-.77-.833-2.694-.833-3.464 0l-6.928 12C.436 14.333 1.398 16 2.938 16z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Delete Company') }}</h3>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Are you sure you want to delete this company? This action cannot be undone and will remove all associated data including:') }}
                </p>
                <ul class="mt-2 text-sm text-gray-600 dark:text-gray-400 list-disc list-inside space-y-1">
                    <li>{{ __('Company profile and business information') }}</li>
                    <li>{{ __('Assigned accountant relationships') }}</li>
                    <li>{{ __('Associated documents and folders') }}</li>
                </ul>
                <div class="mt-4 p-3 bg-red-50 dark:bg-red-900/20 rounded-md">
                    <p class="text-sm text-red-800 dark:text-red-300 font-medium">
                        {{ __('Type the company name to confirm:') }} <span class="font-mono">{{ $company->name }}</span>
                    </p>
                    <x-ui.form.input 
                        id="delete-confirmation" 
                        name="delete_confirmation"
                        type="text" 
                        placeholder="{{ $company->name }}"
                        class="mt-2"
                        onkeyup="checkDeleteConfirmation()"
                    />
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <x-ui.button.secondary x-on:click="$dispatch('close-modal', 'delete-company-modal')">
                    {{ __('Cancel') }}
                </x-ui.button.secondary>
                <x-ui.button.danger id="confirm-delete-btn" disabled onclick="deleteCompany()">
                    {{ __('Delete Company') }}
                </x-ui.button.danger>
            </div>
        </div>
    </x-ui.modal.base>

    <script>
        function checkDeleteConfirmation() {
            const input = document.getElementById('delete-confirmation');
            const button = document.getElementById('confirm-delete-btn');
            const companyName = '{{ $company->name }}';
            
            if (input.value === companyName) {
                button.disabled = false;
                button.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                button.disabled = true;
                button.classList.add('opacity-50', 'cursor-not-allowed');
            }
        }

        function deleteCompany() {
            // Create form and submit to delete route
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.companies.destroy", $company) }}';
            
            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            // Add DELETE method
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);
            
            document.body.appendChild(form);
            form.submit();
        }
    </script>
</x-admin.layout>