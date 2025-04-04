<x-admin-layout>
    <x-slot name="header">
        <x-admin.page-title 
            title="{{ __('Test Page') }}" 
            description="{{ __('This is a test description') }}"
        ></x-admin.page-title>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("This is a test page") }}
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
