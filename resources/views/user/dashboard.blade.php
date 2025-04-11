<x-app-layout>
    <!-- Include the file preview modal component -->
    <x-folder.file-preview-modal />

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Add Accountant Style Breadcrumb Navigation -->
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="px-4 py-4 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center text-sm">
                         <x-dashboard.breadcrumb />
                    </div>
                </div>
            </div>

            <!-- Tax Calendar -->
            <div class="mb-8">
                <x-tax-calendar :days-to-show="30" :show-all="request()->has('show_all_deadlines')" />
            </div>

            <!-- Pending Classifications Alert -->
            @php
                $pendingClassificationsCount = \App\Models\File::whereNotNull('suggested_folder_id')
                    ->where('classification_reviewed', false)
                    ->where(function($query) {
                        $user = auth()->user();
                        if (!$user->is_admin && !$user->is_accountant) {
                            $query->whereHas('folder', function($q) use ($user) {
                                $q->whereIn('company_id', $user->companies->pluck('id'));
                            });
                        }
                    })
                    ->count();
            @endphp

            @if($pendingClassificationsCount > 0)
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-400 p-4 mb-8 rounded-md">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700 dark:text-yellow-300 font-medium">
                                You have {{ $pendingClassificationsCount }} {{ Str::plural('document', $pendingClassificationsCount) }} pending classification.
                            </p>
                            <div class="mt-2">
                                <a href="{{ route('user.files.classification') }}" class="text-sm font-medium text-yellow-700 dark:text-yellow-300 underline hover:text-yellow-600 dark:hover:text-yellow-200">
                                    Review classification suggestions
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Recent Activity -->
            <x-cards.activity-card title="Recent Activity">
                @foreach($recentFiles as $file)
                    <x-activity.activity-item
                        title="{{ $file->name }}"
                        subtitle="{{ $file->folder->name }}"
                        timestamp="{{ $file->created_at->diffForHumans() }}"
                    >
                        <x-slot name="icon">
                            <svg class="h-6 w-6 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </x-slot>
                    </x-activity.activity-item>
                @endforeach
            </x-cards.activity-card>
        </div>
    </div>
</x-app-layout>
