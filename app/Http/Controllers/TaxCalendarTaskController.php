<?php

namespace App\Http\Controllers;

use App\Models\TaxCalendarTask;
use App\Models\TaxCalendar;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;

class TaxCalendarTaskController extends Controller
{
    public function index(Request $request)
    {
        $query = TaxCalendarTask::query()
            ->with('taxCalendar');

        if (!auth()->user()->is_admin) {
            $query->where('user_id', auth()->id())
                ->where('company_id', session('company_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('month')) {
            $query->whereMonth('due_date', $request->month);
        }

        $tasks = $query->orderBy('due_date')
            ->paginate(10);

        return view('tax-calendar.index', compact('tasks'));
    }

    public function show(TaxCalendarTask $task)
    {
        $this->authorize('view', $task);

        $task->load(['company', 'taxCalendar', 'user']);
        
        $user = auth()->user();
        $checklistField = $user->is_accountant ? 'checklist' : 'user_checklist';
        $checklist = $task->$checklistField ?? [];
        
        // Calculate progress based on completed items
        $progress = !empty($checklist) 
            ? collect($checklist)->where('completed', true)->count() * 100 / count($checklist)
            : 0;

        return view('tax-calendar.show', [
            'task' => $task,
            'checklist' => $checklist,
            'progress' => round($progress),
        ]);
    }

    public function updateChecklist(Request $request, TaxCalendarTask $task)
    {
        $this->authorize('updateChecklist', $task);

        try {
            $validated = $request->validate([
                'user_checklist' => ['required', 'array'],
                'user_checklist.*.completed' => ['required', 'boolean'],
                'user_checklist.*.title' => ['required', 'string'],
                'user_checklist.*.notes' => ['nullable', 'string'],
            ]);

            $user = auth()->user();
            $checklistField = $user->is_accountant ? 'checklist' : 'user_checklist';
            
            $task->update([
                $checklistField => $validated['user_checklist'],
            ]);

            // Calculate new progress
            $progress = collect($validated['user_checklist'])->where('completed', true)->count() * 100 / count($validated['user_checklist']);

            return response()->json([
                'success' => true,
                'message' => 'Checklist updated successfully',
                'checklist' => $task->fresh()->$checklistField,
                'progress' => round($progress)
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update checklist',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateNotes(Request $request, TaxCalendarTask $task)
    {
        $this->authorize('update', $task);

        $task->update(['notes' => $request->notes]);

        return back()->with('success', 'Notes updated successfully.');
    }

    public function complete(TaxCalendarTask $task)
    {
        $this->authorize('update', $task);

        $task->complete();

        return back()->with('success', 'Task marked as completed.');
    }

    public function reopen(TaxCalendarTask $task)
    {
        $this->authorize('update', $task);

        $task->reopen();

        return back()->with('success', 'Task reopened.');
    }

    public function create()
    {
        $taxCalendars = TaxCalendar::active()->get();
        $companies = Company::all();
        $users = User::where('is_accountant', true)->get();

        return view('tax-calendar.create', compact('taxCalendars', 'companies', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tax_calendar_id' => 'required|exists:tax_calendars,id',
            'company_id' => 'required|exists:companies,id',
            'user_id' => 'required|exists:users,id',
            'due_date' => 'required|date',
            'checklist' => 'nullable|array',
            'notes' => 'nullable|string'
        ]);

        // Get the tax calendar to access its default checklist
        $taxCalendar = TaxCalendar::findOrFail($request->tax_calendar_id);
        
        // Create the task with both checklists
        $task = TaxCalendarTask::create([
            'tax_calendar_id' => $request->tax_calendar_id,
            'company_id' => $request->company_id,
            'user_id' => $request->user_id,
            'due_date' => $request->due_date,
            'checklist' => $request->checklist ?? $taxCalendar->default_checklist ?? [],
            'user_checklist' => collect($taxCalendar->default_checklist ?? [])->map(function ($item) {
                return [
                    'title' => $item['title'],
                    'completed' => false,
                    'notes' => $item['notes'] ?? null
                ];
            })->toArray(),
            'notes' => $request->notes,
            'status' => 'pending'
        ]);

        return redirect()->route('admin.tax-calendar.show', $task)
            ->with('success', 'Tax calendar task created successfully.');
    }

    public function userIndex(Request $request)
    {
        $query = TaxCalendarTask::query()
            ->with('taxCalendar')
            ->whereIn('company_id', auth()->user()->companies->pluck('id'))
            ->orderBy('due_date');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('month')) {
            $query->whereMonth('due_date', $request->month);
        }

        $tasks = $query->get()->map(function ($task) {
            return [
                'id' => $task->id,
                'name' => $task->taxCalendar->name,
                'form_code' => $task->taxCalendar->form_code,
                'description' => $task->taxCalendar->description,
                'next_deadline' => $task->due_date,
                'next_payment' => $task->taxCalendar->requires_payment ? $task->due_date->copy()->addDays($task->taxCalendar->payment_due_day - $task->taxCalendar->due_day) : null,
                'emta_link' => $task->taxCalendar->emta_link,
                'urgency' => $task->urgency_level,
                'status' => $task->status,
                'progress' => $task->progress,
                'completed_tasks' => collect($task->user_checklist)->where('completed', true)->count(),
                'total_tasks' => collect($task->user_checklist)->count(),
                'days_until' => $task->days_until_due
            ];
        });

        return view('tax-calendar.user.index', ['deadlines' => $tasks]);
    }

    public function userShow(TaxCalendarTask $task)
    {
        $this->authorize('view', $task);

        $task->load(['company', 'taxCalendar', 'user']);
        
        $user = auth()->user();
        
        return view('tax-calendar.user.show', compact('task'));
    }

    public function submitForReview(TaxCalendarTask $task)
    {
        $this->authorize('update', $task);

        // Verify all checklist items are completed
        $userChecklist = $task->user_checklist ?? [];
        $allCompleted = collect($userChecklist)->every(fn($item) => $item['completed'] === true);

        if (!$allCompleted) {
            return back()->with('error', 'Please complete all checklist items before submitting for review.');
        }

        // Update task status to under_review
        $task->update([
            'status' => 'under_review',
            'submitted_at' => now()
        ]);

        return back()->with('success', 'Task submitted for review successfully.');
    }

    public function accountantReviewIndex(Request $request)
    {
        $query = TaxCalendarTask::query()
            ->with(['taxCalendar', 'company', 'user'])
            ->where('status', 'under_review')
            ->whereHas('company', function ($query) {
                $query->whereIn('id', auth()->user()->assignedCompanies()->pluck('companies.id'));
            })
            ->orderBy('submitted_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tasks = $query->paginate(10);

        return view('tax-calendar.accountant.reviews.index', compact('tasks'));
    }

    public function accountantReviewShow(TaxCalendarTask $task)
    {
        $this->authorize('review', $task);

        $task->load(['company', 'taxCalendar', 'user', 'messages']);

        return view('tax-calendar.accountant.reviews.show', compact('task'));
    }

    public function approveTask(TaxCalendarTask $task)
    {
        $this->authorize('review', $task);

        $task->update([
            'status' => 'approved',
            'completed_at' => now()
        ]);

        // TODO: Notify user that their task was approved

        return back()->with('success', 'Task has been approved.');
    }

    public function requestChanges(Request $request, TaxCalendarTask $task)
    {
        $this->authorize('review', $task);

        $validated = $request->validate([
            'feedback' => 'required|string|min:10'
        ]);

        $task->update([
            'status' => 'changes_requested',
            'review_feedback' => $validated['feedback'],
            'review_feedback_date' => now()
        ]);

        // TODO: Notify user that changes were requested

        return back()->with('success', 'Changes have been requested from the user.');
    }

    public function rejectTask(Request $request, TaxCalendarTask $task)
    {
        $this->authorize('review', $task);

        $validated = $request->validate([
            'feedback' => 'required|string|min:10'
        ]);

        $task->update([
            'status' => 'rejected',
            'review_feedback' => $validated['feedback'],
            'review_feedback_date' => now()
        ]);

        // TODO: Notify user that task was rejected

        return back()->with('success', 'Task has been rejected.');
    }
} 