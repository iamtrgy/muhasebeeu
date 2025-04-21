@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Back Navigation -->
    <div class="mb-6">
        <a href="{{ route('accountant.tax-calendar.review.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Reviews
        </a>
    </div>

    <!-- Header Section -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ $task->taxCalendar->name }}
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Submitted on {{ $task->submitted_at->format('F j, Y') }}
                </p>
            </div>
            <div class="flex items-center space-x-4">
                <span class="px-3 py-1 text-sm font-medium rounded-full
                    @if($task->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($task->status === 'approved') bg-green-100 text-green-800
                    @else bg-red-100 text-red-800 @endif">
                    {{ ucfirst($task->status) }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Task Details -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Task Details</h2>
                <dl class="grid grid-cols-1 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Due Date</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ $task->due_date->format('F j, Y') }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white whitespace-pre-line">
                            {{ $task->taxCalendar->description }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Instructions</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white whitespace-pre-line">
                            {{ $task->taxCalendar->task_instructions }}
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Company Details -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Company Details</h2>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Company Name</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ $task->user->company->name }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tax Number</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ $task->user->company->tax_number }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Contact Person</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ $task->user->name }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ $task->user->email }}
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Submission Details -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Submission Details</h2>
                <dl class="grid grid-cols-1 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Submission Notes</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white whitespace-pre-line">
                            {{ $task->submission_notes ?? 'No notes provided.' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Checklist</dt>
                        <dd class="mt-2">
                            <ul class="space-y-2">
                                @foreach(json_decode($task->user_checklist, true) ?? [] as $index => $item)
                                    <li class="flex items-start">
                                        <span class="h-5 w-5 flex items-center justify-center">
                                            @if($item['checked'])
                                                <svg class="h-4 w-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            @else
                                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            @endif
                                        </span>
                                        <span class="ml-2 text-sm text-gray-900 dark:text-white">
                                            {{ $item['title'] }}
                                            @if(!empty($item['notes']))
                                                <span class="block text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    {{ $item['notes'] }}
                                                </span>
                                            @endif
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Attachments -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Attachments</h2>
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($task->attachments as $attachment)
                        <li class="py-3 flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                </svg>
                                <span class="ml-2 text-sm text-gray-900 dark:text-white">
                                    {{ $attachment->original_name }}
                                </span>
                            </div>
                            <a href="{{ route('attachments.download', $attachment) }}" 
                               class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                Download
                            </a>
                        </li>
                    @empty
                        <li class="py-3 text-sm text-gray-500 dark:text-gray-400">
                            No attachments uploaded.
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- Right Column - Review Form -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 sticky top-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Review Task</h2>
                <form action="{{ route('accountant.tax-calendar.review.update', $task) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="space-y-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Status
                            </label>
                            <select id="status" name="status" required
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 
                                           dark:bg-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="pending" @if($task->status === 'pending') selected @endif>Pending</option>
                                <option value="approved" @if($task->status === 'approved') selected @endif>Approved</option>
                                <option value="rejected" @if($task->status === 'rejected') selected @endif>Rejected</option>
                            </select>
                        </div>

                        <div>
                            <label for="review_comments" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Review Comments
                            </label>
                            <textarea id="review_comments" name="review_comments" rows="4" required
                                      class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 
                                             dark:bg-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $task->review_comments }}</textarea>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Please provide detailed feedback about the submission.
                            </p>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm 
                                           text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none 
                                           focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Submit Review
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-save functionality for review comments
    let autoSaveTimeout;
    const reviewCommentsTextarea = document.getElementById('review_comments');
    const autoSaveIndicator = document.getElementById('auto-save-indicator');

    reviewCommentsTextarea.addEventListener('input', function() {
        clearTimeout(autoSaveTimeout);
        autoSaveIndicator.textContent = 'Saving...';
        autoSaveIndicator.classList.remove('text-green-500', 'text-red-500');
        autoSaveIndicator.classList.add('text-gray-500');

        autoSaveTimeout = setTimeout(async function() {
            try {
                const response = await fetch('{{ route("accountant.tax-calendar.review.save-comments", $task) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        review_comments: reviewCommentsTextarea.value
                    })
                });

                if (!response.ok) {
                    throw new Error('Failed to save comments');
                }

                const data = await response.json();
                autoSaveIndicator.textContent = 'Saved';
                autoSaveIndicator.classList.remove('text-gray-500', 'text-red-500');
                autoSaveIndicator.classList.add('text-green-500');
                toastr.success('Comments saved successfully');
            } catch (error) {
                console.error('Error saving comments:', error);
                autoSaveIndicator.textContent = 'Failed to save';
                autoSaveIndicator.classList.remove('text-gray-500', 'text-green-500');
                autoSaveIndicator.classList.add('text-red-500');
                toastr.error('Failed to save comments. Please try again.');
            }
        }, 1000);
    });

    // Confirmation dialog for task status update
    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();
        const status = document.querySelector('select[name="status"]').value;
        const comments = document.querySelector('textarea[name="review_comments"]').value;

        if (!comments.trim()) {
            toastr.error('Please provide review comments before updating the status.');
            return;
        }

        const confirmMessage = status === 'approved' 
            ? 'Are you sure you want to approve this task?' 
            : 'Are you sure you want to request changes for this task?';

        if (confirm(confirmMessage)) {
            this.submit();
        }
    });

    // Auto-resize textarea
    reviewCommentsTextarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
</script>
@endpush
@endsection 