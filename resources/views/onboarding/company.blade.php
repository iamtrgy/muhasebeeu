<x-layouts.onboarding :step="2">
    <x-slot name="title">Company Information</x-slot>
    <x-slot name="subtitle">Tell us about your company in {{ $country->name }}</x-slot>

    <!-- Back to Country Selection link -->
    <div class="mb-6 text-center">
        <a href="{{ route('onboarding.step1') }}" class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Back to Country Selection
        </a>
    </div>

    <div x-data="{ 
        option: null, 
        isEstonia: {{ $country->code === 'EE' ? 'true' : 'false' }},
        searchTerm: '',
        searchResults: [],
        selectedCompany: null,
        manualVatNumber: '',
        loading: false,
        formError: '',
        
        // Clear validation error when user changes options
        clearError() {
            this.formError = '';
        },
        
        async searchCompanies() {
            if (this.searchTerm.length < 2) {
                this.searchResults = [];
                return;
            }
            
            this.loading = true;
            try {
                const response = await fetch(`https://ariregister.rik.ee/est/api/autocomplete?q=${encodeURIComponent(this.searchTerm)}`);
                const data = await response.json();
                if (data && data.data) {
                    // Log the response to see available fields
                    console.log('Company data from API:', data.data);
                    
                    // Just use the data as is
                    this.searchResults = data.data;
                } else {
                    this.searchResults = [];
                }
            } catch (error) {
                console.error('Error fetching companies:', error);
                this.searchResults = [];
            } finally {
                this.loading = false;
            }
        },
        selectCompany(company) {
            // Log the selected company to see available fields
            console.log('Selected company:', company);
            this.selectedCompany = company;
            this.searchTerm = company.name;
            this.searchResults = [];
            this.formError = '';
            
            // Reset VAT number field when selecting a new company
            this.manualVatNumber = '';
        }
    }" class="space-y-6">
        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md border border-gray-200 dark:border-gray-600">
            <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">Please select an option:</h3>
            
            <div class="mt-4 space-y-4">
                <div class="flex items-center">
                    <input id="create-company" name="company-option" type="radio" value="create"
                        class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-600"
                        x-on:click="option = 'create'; clearError()">
                    <label for="create-company" class="ml-3 block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                        I want to create a new company
                    </label>
                </div>
                
                <div class="flex items-center">
                    <input id="existing-company" name="company-option" type="radio" value="existing"
                        class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-600"
                        x-on:click="option = 'existing'; clearError()">
                    <label for="existing-company" class="ml-3 block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                        I already have a company
                    </label>
                </div>
            </div>
        </div>

        <!-- Message when no option is selected -->
        <div x-show="option === null" class="text-center p-4 border border-gray-200 dark:border-gray-700 rounded-md bg-gray-50 dark:bg-gray-800">
            <p class="text-gray-600 dark:text-gray-400">Please select an option above to continue</p>
        </div>

        <!-- Create New Company Form -->
        <form method="POST" action="{{ route('onboarding.postStep2') }}" x-show="option === 'create'" x-cloak>
            @csrf
            <input type="hidden" name="option" value="create">
            
            <div class="space-y-4">
                <!-- Company Name -->
                <div>
                    <label for="name" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                        Company Name
                    </label>
                    <div class="mt-2">
                        <input type="text" id="name" name="name" required
                            class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-white dark:ring-gray-600"
                            placeholder="Enter your company name">
                    </div>
                    @error('name')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tax Number -->
                <div>
                    <label for="tax_number" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                        Tax Number <span class="text-gray-500 text-xs">(Optional)</span>
                    </label>
                    <div class="mt-2">
                        <input type="text" id="tax_number" name="tax_number"
                            class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-white dark:ring-gray-600"
                            placeholder="Enter your tax ID number">
                    </div>
                </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                        Address <span class="text-gray-500 text-xs">(Optional)</span>
                    </label>
                    <div class="mt-2">
                        <input type="text" id="address" name="address"
                            class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-white dark:ring-gray-600"
                            placeholder="Enter company address">
                    </div>
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                        Phone <span class="text-gray-500 text-xs">(Optional)</span>
                    </label>
                    <div class="mt-2">
                        <input type="text" id="phone" name="phone"
                            class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-white dark:ring-gray-600"
                            placeholder="Enter company phone number">
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                        Email <span class="text-gray-500 text-xs">(Optional)</span>
                    </label>
                    <div class="mt-2">
                        <input type="email" id="email" name="email"
                            class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-white dark:ring-gray-600"
                            placeholder="Enter company email address">
                    </div>
                </div>

                <div class="mt-4">
                    <label for="foundation_date" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                        Foundation Date <span class="text-gray-500 text-xs">(Optional)</span>
                    </label>
                    <div class="mt-2">
                        <input type="date" id="foundation_date" name="foundation_date"
                            class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-white dark:ring-gray-600">
                    </div>
                </div>

                <div class="pt-2">
                    <div class="flex gap-4">
                        <a href="{{ route('onboarding.step1') }}" class="flex justify-center items-center gap-2 rounded-md bg-gray-200 dark:bg-gray-700 px-4 py-3 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-200 w-1/3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            Back
                        </a>
                        <button type="submit" class="flex justify-center items-center gap-2 rounded-md bg-blue-600 px-4 py-3 text-base font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-colors duration-200 w-2/3 dark:bg-blue-700 dark:hover:bg-blue-600" 
                        :class="{ 'opacity-50 cursor-not-allowed': isEstonia && !selectedCompany }"
                        :disabled="isEstonia && !selectedCompany">
                            Complete Setup
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Existing Company Form -->
        <form method="POST" action="{{ route('onboarding.postStep2') }}" 
              x-show="option === 'existing'" 
              x-cloak
              @submit="if(isEstonia && !selectedCompany) { $event.preventDefault(); formError = 'Please select a company from the search results first'; return false; }">
            @csrf
            <input type="hidden" name="option" value="existing">
            
            <div class="space-y-4">
                <!-- Standard Company Entry (non-Estonia) -->
                <div x-show="!isEstonia">
                    <!-- Company Name -->
                    <div>
                        <label for="company_name" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                            Company Name
                        </label>
                        <div class="mt-2">
                            <input type="text" id="company_name" name="company_name" :required="!isEstonia"
                                class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-white dark:ring-gray-600"
                                placeholder="Enter your existing company name">
                        </div>
                        @error('company_name')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Estonia Company Search -->
                <div x-show="isEstonia" x-cloak>
                    <div class="mb-4">
                        <label for="company_search" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                            Search Estonian Business Registry
                        </label>
                        
                        <!-- Search container with fixed width -->
                        <div class="mt-2 w-full relative">
                            <!-- Search input -->
                            <input type="text" id="company_search" 
                                x-model="searchTerm"
                                x-on:input.debounce.300ms="searchCompanies()"
                                placeholder="Start typing company name or registry code"
                                class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-white dark:ring-gray-600">
                            
                            <!-- Loading indicator -->
                            <div x-show="loading" class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                            
                            <!-- Results dropdown -->
                            <div x-show="searchResults.length > 0" x-cloak
                                 class="absolute left-0 z-10 mt-1 w-full max-h-60 overflow-auto rounded-md bg-white dark:bg-gray-700 py-1 text-sm shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                <template x-for="company in searchResults" :key="company.reg_code">
                                    <div @click="selectCompany(company)"
                                         class="cursor-pointer select-none py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 border-b border-gray-100 dark:border-gray-600 last:border-0">
                                        <div class="flex flex-col">
                                            <div class="flex justify-between items-center">
                                                <span class="font-medium text-gray-900 dark:text-white truncate mr-2" x-text="company.name"></span>
                                                <span class="text-xs text-blue-600 dark:text-blue-400 whitespace-nowrap" x-text="company.reg_code"></span>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate" x-text="company.legal_address || ''"></p>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- No results message -->
                        <div x-show="searchTerm.length >= 2 && !loading && searchResults.length === 0" class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            No companies found for your search term
                        </div>

                        <!-- Selected company info -->
                        <div x-show="selectedCompany" x-cloak class="mt-3 p-3 border border-gray-200 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-800">
                            <h4 class="font-medium text-sm text-gray-900 dark:text-gray-100" x-text="selectedCompany?.name"></h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                <span class="font-medium">Registry code:</span> 
                                <span x-text="selectedCompany?.reg_code"></span>
                            </p>
                            <p class="text-xs text-gray-600 dark:text-gray-400" x-show="selectedCompany?.legal_address">
                                <span class="font-medium">Address:</span> 
                                <span x-text="selectedCompany?.legal_address"></span>
                            </p>
                        </div>
                        
                        <!-- Manual VAT Number Input -->
                        <div x-show="selectedCompany" class="mt-4">
                            <label for="manual_vat_number" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                                VAT Number <span class="text-gray-500 text-xs">(Optional)</span>
                            </label>
                            <div class="mt-2">
                                <input type="text" id="manual_vat_number" name="company_vat_number" x-model="manualVatNumber"
                                    class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-white dark:ring-gray-600"
                                    placeholder="Enter VAT number if applicable">
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                For Estonian companies, VAT numbers typically follow the format "EE" followed by the registry code.
                            </p>
                        </div>
                    </div>

                    <!-- Form error message -->
                    <div x-show="formError" class="mt-2 text-sm text-red-600 dark:text-red-400" x-text="formError"></div>
                    
                    <!-- Estonia company fields - make them hidden but not with absolute positioning -->
                    <div>
                        <input type="hidden" id="est_company_name" name="company_name" :value="selectedCompany ? selectedCompany.name : ''">
                        <input type="hidden" id="est_registry_code" name="company_registry_code" :value="selectedCompany ? selectedCompany.reg_code : ''">
                        <input type="hidden" id="est_company_address" name="company_address" :value="selectedCompany ? selectedCompany.legal_address : ''">
                        <!-- VAT number is now provided through the manual input field -->
                    </div>
                </div>

                <!-- Additional fields for non-Estonian existing companies -->
                <div x-show="!isEstonia && option === 'existing'" x-cloak>
                    <!-- Company Address -->
                    <div class="mt-4">
                        <label for="company_address" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                            Company Address <span class="text-gray-500 text-xs">(Optional)</span>
                        </label>
                        <div class="mt-2">
                            <input type="text" id="company_address" name="company_address"
                                class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-white dark:ring-gray-600"
                                placeholder="Enter company address">
                        </div>
                    </div>

                    <!-- Company Phone -->
                    <div class="mt-4">
                        <label for="company_phone" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                            Company Phone <span class="text-gray-500 text-xs">(Optional)</span>
                        </label>
                        <div class="mt-2">
                            <input type="text" id="company_phone" name="company_phone"
                                class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-white dark:ring-gray-600"
                                placeholder="Enter company phone number">
                        </div>
                    </div>

                    <!-- Company Email -->
                    <div class="mt-4">
                        <label for="company_email" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                            Company Email <span class="text-gray-500 text-xs">(Optional)</span>
                        </label>
                        <div class="mt-2">
                            <input type="email" id="company_email" name="company_email"
                                class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-white dark:ring-gray-600"
                                placeholder="Enter company email">
                        </div>
                    </div>

                    <!-- Company VAT Number -->
                    <div class="mt-4">
                        <label for="company_vat_number" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                            VAT Number <span class="text-gray-500 text-xs">(Optional)</span>
                        </label>
                        <div class="mt-2">
                            <input type="text" id="company_vat_number" name="company_vat_number"
                                class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-white dark:ring-gray-600"
                                placeholder="Enter VAT number">
                        </div>
                    </div>
                </div>

                <div class="pt-2">
                    <div class="flex gap-4">
                        <a href="{{ route('onboarding.step1') }}" class="flex justify-center items-center gap-2 rounded-md bg-gray-200 dark:bg-gray-700 px-4 py-3 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-200 w-1/3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            Back
                        </a>
                        <button type="submit" class="flex justify-center items-center gap-2 rounded-md bg-blue-600 px-4 py-3 text-base font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-colors duration-200 w-2/3 dark:bg-blue-700 dark:hover:bg-blue-600" 
                        :class="{ 'opacity-50 cursor-not-allowed': isEstonia && !selectedCompany }"
                        :disabled="isEstonia && !selectedCompany">
                            Complete Setup
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layouts.onboarding> 