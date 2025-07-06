<x-user.layout 
    title="{{ $company->name }}" 
    :breadcrumbs="[
        ['title' => __('Home'), 'href' => route('user.dashboard'), 'first' => true],
        ['title' => __('Companies'), 'href' => route('user.companies.index')],
        ['title' => $company->name]
    ]"
>
    <div class="space-y-6">
        @if(session('success'))
            <x-ui.alert variant="success">{{ session('success') }}</x-ui.alert>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Company Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Company Details Card -->
                <x-ui.card.base>
                    <x-ui.card.header>
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-16 w-16 rounded-full bg-indigo-100 dark:bg-indigo-800 flex items-center justify-center">
                                <span class="text-2xl font-medium text-indigo-700 dark:text-indigo-300">{{ substr($company->name, 0, 1) }}</span>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ $company->name }}</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $company->email ?: __('No email provided') }}</p>
                            </div>
                        </div>
                    </x-ui.card.header>
                    <x-ui.card.body>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Registry Number') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $company->tax_number ?: '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('VAT Number') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $company->vat_number ?: '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Country') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $company->country->name ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Phone') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $company->phone ?: '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Foundation Date') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $company->foundation_date ? $company->foundation_date->format('M d, Y') : '-' }}</dd>
                            </div>
                        </div>
                        
                        @if($company->address)
                            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Address') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 whitespace-pre-line">{{ $company->address }}</dd>
                            </div>
                        @endif
                    </x-ui.card.body>
                </x-ui.card.base>

                <!-- Bank Information Card -->
                @if($company->bank_name || $company->bank_account || $company->bank_iban || $company->bank_swift)
                    <x-ui.card.base>
                        <x-ui.card.header>
                            <div class="flex items-center">
                                <svg class="h-6 w-6 text-emerald-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Bank Information') }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Bank details for receiving payments') }}</p>
                                </div>
                            </div>
                        </x-ui.card.header>
                        <x-ui.card.body>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @if($company->bank_name)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Bank Name') }}</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $company->bank_name }}</dd>
                                    </div>
                                @endif
                                @if($company->bank_account)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Account Number') }}</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $company->bank_account }}</dd>
                                    </div>
                                @endif
                                @if($company->bank_iban)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('IBAN') }}</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $company->bank_iban }}</dd>
                                    </div>
                                @endif
                                @if($company->bank_swift)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('SWIFT/BIC') }}</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $company->bank_swift }}</dd>
                                    </div>
                                @endif
                            </div>
                        </x-ui.card.body>
                    </x-ui.card.base>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Quick Actions Card -->
                <x-ui.card.base>
                    <x-ui.card.header>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Quick Actions') }}</h3>
                    </x-ui.card.header>
                    <x-ui.card.body>
                        <div class="space-y-3">
                            <x-ui.button.secondary href="{{ route('user.companies.edit', $company->id) }}" class="w-full justify-start">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                {{ __('Edit Company') }}
                            </x-ui.button.secondary>
                            <x-ui.button.secondary href="#" class="w-full justify-start">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                {{ __('View Documents') }}
                            </x-ui.button.secondary>
                            <x-ui.button.secondary href="{{ route('user.invoices.index') }}" class="w-full justify-start">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                {{ __('View Invoices') }}
                            </x-ui.button.secondary>
                        </div>
                    </x-ui.card.body>
                </x-ui.card.base>

                <!-- Statistics Card -->
                <x-ui.card.base>
                    <x-ui.card.header>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Statistics') }}</h3>
                    </x-ui.card.header>
                    <x-ui.card.body>
                        <div class="space-y-4">
                            @php
                                // Count invoices created by this user for this company
                                $createdInvoiceCount = $company->invoices()->where('created_by', auth()->id())->count();
                                
                                // Count all folders for this company
                                $folderCount = $company->folders()->count();
                                
                                // Get latest invoice created by this user for this company
                                $latestInvoice = $company->invoices()->where('created_by', auth()->id())->latest()->first();
                                
                                // Get latest folder for this company
                                $latestFolder = $company->folders()->latest()->first();
                                
                                // Find the most recent activity
                                $latestActivity = null;
                                $activityType = null;
                                
                                if ($latestInvoice && $latestFolder) {
                                    if ($latestInvoice->created_at > $latestFolder->created_at) {
                                        $latestActivity = $latestInvoice;
                                        $activityType = 'invoice';
                                    } else {
                                        $latestActivity = $latestFolder;
                                        $activityType = 'folder';
                                    }
                                } elseif ($latestInvoice) {
                                    $latestActivity = $latestInvoice;
                                    $activityType = 'invoice';
                                } elseif ($latestFolder) {
                                    $latestActivity = $latestFolder;
                                    $activityType = 'folder';
                                }
                            @endphp
                            
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Created Invoices') }}</dt>
                                <dd class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $createdInvoiceCount }}</dd>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Folders') }}</dt>
                                <dd class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $folderCount }}</dd>
                            </div>
                            @if($latestActivity)
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Latest Activity') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        @if($activityType === 'invoice')
                                            {{ __('Invoice created') }} {{ $latestActivity->created_at->diffForHumans() }}
                                        @else
                                            {{ __('Folder created') }} {{ $latestActivity->created_at->diffForHumans() }}
                                        @endif
                                    </dd>
                                </div>
                            @endif
                        </div>
                    </x-ui.card.body>
                </x-ui.card.base>
            </div>
        </div>
    </div>
</x-user.layout>
