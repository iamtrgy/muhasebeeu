<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Tax Calendar Task') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <a href="{{ route('admin.tax-calendar.index') }}" class="text-indigo-600 hover:text-indigo-900">
                ← Back to Tax Calendar
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <form action="{{ route('admin.tax-calendar.store') }}" method="POST" class="p-6">
                @csrf
                
                <!-- Tax Calendar Selection -->
                <div class="mb-6">
                    <label for="tax_calendar_id" class="block text-sm font-medium text-gray-700">Tax Calendar</label>
                    <select name="tax_calendar_id" id="tax_calendar_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">Select a tax calendar</option>
                        @foreach($taxCalendars as $calendar)
                            <option value="{{ $calendar->id }}">
                                {{ $calendar->name }} - {{ $calendar->form_code }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Company Selection -->
                <div class="mb-6">
                    <label for="company_id" class="block text-sm font-medium text-gray-700">Company</label>
                    <select name="company_id" id="company_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">Select a company</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}">
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Accountant Selection -->
                <div class="mb-6">
                    <label for="user_id" class="block text-sm font-medium text-gray-700">Assign to Accountant</label>
                    <select name="user_id" id="user_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">Select an accountant</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Due Date -->
                <div class="mb-6">
                    <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                    <input type="date" name="due_date" id="due_date" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                <!-- Notes -->
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="notes" id="notes" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        placeholder="Add any additional notes here..."></textarea>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Create Task
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout> 