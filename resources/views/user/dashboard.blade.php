<x-app-layout>
    <x-unified-header />
    
    <!-- Include the file preview modal component -->
    <x-folder.file-preview-modal />

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Tax Calendar -->
            <div class="mb-8">
                <x-tax-calendar :days-to-show="30" :show-all="request()->has('show_all_deadlines')" />
            </div>

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
