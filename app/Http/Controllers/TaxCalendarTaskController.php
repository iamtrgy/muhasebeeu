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
            ->with(['taxCalendar', 'company']);

        // Filter by user's companies if not admin
        if (!auth()->user()->is_admin) {
            $query->whereIn('company_id', auth()->user()->companies->pluck('id'));
        }

        // Simple status filter
        if ($request->filled('status')) {
            if ($request->status === 'completed') {
                $query->completed();
            } else {
                $query->pending();
            }
        }

        if ($request->filled('month')) {
            $query->whereMonth('due_date', $request->month)
                  ->whereYear('due_date', $request->get('year', now()->year));
        }

        $tasks = $query->orderBy('is_completed')
            ->orderBy('due_date')
            ->get();

        return view('tax-calendar.index', compact('tasks'));
    }

    public function show(TaxCalendarTask $task)
    {
        $this->authorize('view', $task);

        $task->load(['company', 'taxCalendar', 'user']);

        return view('tax-calendar.show', compact('task'));
    }

    public function updateChecklist(Request $request, TaxCalendarTask $task)
    {
        $this->authorize('update', $task);

        try {
            $validated = $request->validate([
                'checklist' => ['required', 'array'],
                'checklist.*.completed' => ['required', 'boolean'],
                'checklist.*.title' => ['required', 'string'],
                'checklist.*.notes' => ['nullable', 'string'],
            ]);

            $task->updateChecklist($validated['checklist']);

            return response()->json([
                'success' => true,
                'message' => 'Progress saved successfully',
                'checklist' => $task->fresh()->checklist,
                'progress' => round($task->progress),
                'is_completed' => $task->fresh()->is_completed
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

        $validated = $request->validate([
            'user_notes' => 'nullable|string|max:1000'
        ]);

        $task->update(['user_notes' => $validated['user_notes']]);

        return back()->with('success', 'Notes updated successfully.');
    }

    public function toggleComplete(TaxCalendarTask $task)
    {
        $this->authorize('update', $task);

        if ($task->is_completed) {
            $task->reopen();
            $message = 'Task reopened.';
        } else {
            $task->complete();
            $message = 'Task marked as completed.';
        }

        return back()->with('success', $message);
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

        $task = TaxCalendarTask::create([
            'tax_calendar_id' => $request->tax_calendar_id,
            'company_id' => $request->company_id,
            'user_id' => $request->user_id,
            'due_date' => $request->due_date,
            'checklist' => $request->checklist,
            'notes' => $request->notes,
            'is_completed' => false
        ]);

        return redirect()->route('tax-calendar.show', $task)
            ->with('success', 'Task created successfully.');
    }

    public function edit(TaxCalendarTask $task)
    {
        $taxCalendars = TaxCalendar::active()->get();
        $companies = Company::all();
        $users = User::where('is_accountant', true)->get();

        return view('tax-calendar.edit', compact('task', 'taxCalendars', 'companies', 'users'));
    }

    public function update(Request $request, TaxCalendarTask $task)
    {
        $request->validate([
            'tax_calendar_id' => 'required|exists:tax_calendars,id',
            'company_id' => 'required|exists:companies,id',
            'user_id' => 'required|exists:users,id',
            'due_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        $task->update($request->only(['tax_calendar_id', 'company_id', 'user_id', 'due_date', 'notes']));

        return redirect()->route('tax-calendar.show', $task)
            ->with('success', 'Task updated successfully.');
    }

    public function destroy(TaxCalendarTask $task)
    {
        $task->delete();

        return redirect()->route('tax-calendar.index')
            ->with('success', 'Task deleted successfully.');
    }

    // User-specific methods remain simple
    public function userIndex(Request $request)
    {
        return $this->index($request); // Use the same index method
    }

    public function userShow(TaxCalendarTask $task)
    {
        return $this->show($task); // Use the same show method
    }
} 