<x-layouts.onboarding :step="1">
    <x-slot name="title">Select Your Country</x-slot>
    <x-slot name="subtitle">Please select the country where you operate</x-slot>

    <form method="POST" action="{{ route('onboarding.postStep1') }}">
        @csrf

        <div class="space-y-6">
            <!-- Country Selection -->
            <div>
                <label for="country_id" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">
                    Country
                </label>
                <div class="mt-2 relative">
                    <select id="country_id" name="country_id" required
                        class="block w-full rounded-md border-0 py-2.5 pl-3 pr-10 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-white dark:ring-gray-600">
                        <option value="">Select a country</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-300">
                        <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                @error('country_id')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button type="submit"
                    class="flex w-full justify-center items-center gap-2 rounded-md bg-blue-600 px-4 py-3 text-base font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-colors duration-200 dark:bg-blue-700 dark:hover:bg-blue-600">
                    Continue
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>

                <p class="mt-4 text-xs text-center text-gray-500 dark:text-gray-400">
                    This information helps us provide country-specific features and compliance options.
                </p>
            </div>
        </div>
    </form>
</x-layouts.onboarding> 