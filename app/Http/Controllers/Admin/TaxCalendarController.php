<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaxCalendar;
use Illuminate\Http\Request;

class TaxCalendarController extends Controller
{
    public function index()
    {
        $templates = TaxCalendar::orderBy('country_code')
            ->orderBy('frequency')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.tax-calendar-templates.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.tax-calendar-templates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country_code' => 'required|string|size:2',
            'frequency' => 'required|in:monthly,quarterly,annual',
            'due_day' => 'required|integer|min:1|max:31',
            'due_month' => 'nullable|integer|min:1|max:12',
            'form_code' => 'required|string|max:50',
            'description' => 'nullable|string',
            'emta_link' => 'nullable|url',
            'requires_payment' => 'boolean',
            'payment_due_day' => 'nullable|required_if:requires_payment,true|integer|min:1|max:31',
            'is_active' => 'boolean',
            'auto_create_tasks' => 'boolean',
            'reminder_days_before' => 'nullable|integer|min:1|max:30',
            'task_instructions' => 'nullable|string',
            'user_instructions' => 'nullable|string',
        ]);

        // Set defaults
        $validated['is_active'] = $validated['is_active'] ?? true;
        $validated['auto_create_tasks'] = $validated['auto_create_tasks'] ?? false;
        $validated['requires_payment'] = $validated['requires_payment'] ?? false;

        $taxCalendar = TaxCalendar::create($validated);

        return redirect()->route('admin.tax-calendar-templates.index')
            ->with('success', 'Tax calendar template created successfully.');
    }

    public function edit(TaxCalendar $taxCalendar)
    {
        return view('admin.tax-calendar-templates.edit', compact('taxCalendar'));
    }

    public function update(Request $request, TaxCalendar $taxCalendar)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country_code' => 'required|string|size:2',
            'frequency' => 'required|in:monthly,quarterly,annual',
            'due_day' => 'required|integer|min:1|max:31',
            'due_month' => 'nullable|integer|min:1|max:12',
            'form_code' => 'required|string|max:50',
            'description' => 'nullable|string',
            'emta_link' => 'nullable|url',
            'requires_payment' => 'boolean',
            'payment_due_day' => 'nullable|required_if:requires_payment,true|integer|min:1|max:31',
            'is_active' => 'boolean',
            'auto_create_tasks' => 'boolean',
            'reminder_days_before' => 'nullable|integer|min:1|max:30',
            'task_instructions' => 'nullable|string',
            'user_instructions' => 'nullable|string',
        ]);

        // Set defaults
        $validated['is_active'] = $validated['is_active'] ?? false;
        $validated['auto_create_tasks'] = $validated['auto_create_tasks'] ?? false;
        $validated['requires_payment'] = $validated['requires_payment'] ?? false;

        $taxCalendar->update($validated);

        return redirect()->route('admin.tax-calendar-templates.index')
            ->with('success', 'Tax calendar template updated successfully.');
    }

    public function destroy(TaxCalendar $taxCalendar)
    {
        // Check if there are any tasks using this template
        if ($taxCalendar->tasks()->exists()) {
            return redirect()->route('admin.tax-calendar-templates.index')
                ->with('error', 'Cannot delete this template because it has associated tasks.');
        }

        $taxCalendar->delete();

        return redirect()->route('admin.tax-calendar-templates.index')
            ->with('success', 'Tax calendar template deleted successfully.');
    }
}