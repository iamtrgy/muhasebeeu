<?php

namespace App\Http\Controllers;

use App\Models\TaxCalendarTask;
use App\Notifications\TaskReviewed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaxCalendarReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = TaxCalendarTask::with(['taxCalendar', 'company', 'user'])
            ->whereHas('company', function ($query) {
                $query->whereIn('id', auth()->user()->assignedCompanies()->pluck('companies.id'));
            });

        if ($request->has('archived')) {
            $query->whereIn('status', ['completed', 'rejected'])
                  ->whereNotNull('reviewed_at');
        } elseif ($request->filled('status')) {
            if ($request->status === 'under_review') {
                // Show tasks waiting for initial review
                $query->where('status', 'under_review');
            } elseif ($request->status === 'changes_requested') {
                // Show tasks that need changes
                $query->where('status', 'changes_requested');
            } elseif ($request->status === 'rejected') {
                // Show rejected tasks
                $query->where('status', 'rejected');
            } elseif ($request->status === 'in_progress') {
                // Show tasks that are in progress
                $query->where('status', 'in_progress');
            } elseif ($request->status === 'completed') {
                // Show completed tasks
                $query->where('status', 'completed');
            }
        } else {
            // Default tab shows under review tasks
            $query->where('status', 'under_review');
        }

        // Add debug logging
        \Log::info('Task Query Status: ' . $request->status);
        \Log::info('SQL Query: ' . $query->toSql());
        \Log::info('SQL Bindings: ', $query->getBindings());

        $tasks = $query->latest('submitted_at')
            ->paginate(10);

        // Add debug logging for results
        \Log::info('Found Tasks Count: ' . $tasks->count());
        
        // Get total counts for all statuses (not filtered)
        $totalCounts = TaxCalendarTask::whereHas('company', function ($query) {
                $query->whereIn('id', auth()->user()->assignedCompanies()->pluck('companies.id'));
            })
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Ensure all statuses have a count
        $statusCounts = [
            'pending' => $totalCounts['pending'] ?? 0,
            'under_review' => $totalCounts['under_review'] ?? 0,
            'in_progress' => $totalCounts['in_progress'] ?? 0,
            'completed' => $totalCounts['completed'] ?? 0,
            'rejected' => $totalCounts['rejected'] ?? 0,
            'changes_requested' => $totalCounts['changes_requested'] ?? 0,
        ];

        // Calculate pending count (under_review + pending)
        $pendingCount = $statusCounts['under_review'] + $statusCounts['pending'];
        
        return view('tax-calendar.accountant.reviews.index', compact('tasks', 'statusCounts', 'pendingCount'));
    }

    public function show(TaxCalendarTask $task)
    {
        $this->authorize('review', $task);

        $task->load(['company.users', 'taxCalendar', 'user', 'messages.user']);
        
        return view('tax-calendar.accountant.reviews.show', compact('task'));
    }

    public function update(Request $request, TaxCalendarTask $task)
    {
        \Log::info('Update method called', [
            'task_id' => $task->id,
            'current_status' => $task->status,
            'requested_status' => $request->status,
            'request_data' => $request->all()
        ]);

        // Define allowed status transitions
        $allowedTransitions = [
            'under_review' => ['in_progress', 'changes_requested', 'rejected', 'completed'],
            'pending' => ['in_progress', 'changes_requested', 'rejected', 'completed'],
            'in_progress' => ['completed', 'changes_requested', 'rejected'],
            'changes_requested' => [], // No transitions allowed until user submits
            'rejected' => [], // No transitions allowed until user submits
            'completed' => [], // No transitions allowed after completion
        ];

        try {
            $validated = $request->validate([
                'action' => ['required', 'string', 'in:approve,request_changes,reject'],
                'review_notes' => ['nullable', 'string', 'max:1000'],
            ]);

            // Map action to status
            $statusMapping = [
                'approve' => 'completed',
                'request_changes' => 'changes_requested', 
                'reject' => 'rejected'
            ];
            
            $targetStatus = $statusMapping[$validated['action']];
            
            $validated['status'] = $targetStatus;
            $validated['review_comments'] = $validated['review_notes'];

            \Log::info('Validation passed', ['validated_data' => $validated]);

            // Check if the status transition is allowed
            if (!in_array($targetStatus, $allowedTransitions[$task->status] ?? [])) {
                \Log::warning('Invalid status transition', [
                    'current_status' => $task->status,
                    'requested_status' => $targetStatus,
                    'allowed_transitions' => $allowedTransitions[$task->status] ?? []
                ]);

                return back()
                    ->with('error', match($task->status) {
                        'changes_requested' => 'Cannot update status while waiting for user to submit changes.',
                        'rejected' => 'Cannot update status while waiting for user to submit a new version.',
                        'completed' => 'Task is already completed and cannot be modified.',
                        'in_progress' => 'Can only mark the task as completed from in progress status.',
                        default => 'Invalid status transition.'
                    })
                    ->withInput();
            }

            DB::beginTransaction();

            $updateData = [
                'status' => $targetStatus,
                'review_comments' => $validated['review_comments'] ?? null,
                'reviewed_at' => now(),
                'reviewed_by' => auth()->id(),
            ];

            \Log::info('Updating task with data', ['update_data' => $updateData]);

            $task->update($updateData);

            // Create a message about the review
            $statusMessage = match($targetStatus) {
                'in_progress' => '🔄 Task is now in progress.',
                'completed' => '✅ Task has been approved and completed.',
                'rejected' => '❌ Task has been rejected.',
                'changes_requested' => '🔄 Changes have been requested.',
            };

            if ($validated['review_comments']) {
                $messageContent = $statusMessage . "\n\nReview Comments:\n" . $validated['review_comments'];
            } else {
                $messageContent = $statusMessage;
            }

            $task->messages()->create([
                'user_id' => auth()->id(),
                'content' => $messageContent,
            ]);

            // Notify the user about the review result
            $task->user->notify(new TaskReviewed($task));

            DB::commit();

            \Log::info('Task updated successfully', [
                'task_id' => $task->id,
                'new_status' => $task->status
            ]);

            return redirect()->route('accountant.tax-calendar.reviews.show', $task->id)
                ->with('success', 'Review updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to update task', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->with('error', 'Failed to update review. Please try again.')
                ->withInput();
        }
    }

    public function store(Request $request, TaxCalendarTask $task)
    {
        // ... existing code ...
        return redirect()->route('accountant.tax-calendar.reviews.show', $task->id)
            ->with('success', 'Review submitted successfully.');
    }
} 