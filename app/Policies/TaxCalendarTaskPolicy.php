<?php

namespace App\Policies;

use App\Models\TaxCalendarTask;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaxCalendarTaskPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the task.
     */
    public function view(User $user, TaxCalendarTask $task)
    {
        // Admin can view any task
        if ($user->is_admin) {
            return true;
        }

        // Accountants can view tasks they are assigned to
        if ($user->is_accountant) {
            return $task->user_id === $user->id;
        }

        // Users can view tasks for their companies
        return $user->companies->contains('id', $task->company_id);
    }

    /**
     * Determine whether the user can update the task.
     */
    public function update(User $user, TaxCalendarTask $task)
    {
        // Admin can update any task
        if ($user->is_admin) {
            return true;
        }

        // Accountants can update tasks they are assigned to
        if ($user->is_accountant) {
            return $task->user_id === $user->id;
        }

        // Users can update tasks for their companies
        return $user->companies->contains('id', $task->company_id);
    }

    /**
     * Determine whether the user can update the checklist.
     */
    public function updateChecklist(User $user, TaxCalendarTask $task)
    {
        // Admin can update any checklist
        if ($user->is_admin) {
            return true;
        }

        // Accountants can update the accountant checklist for their tasks
        if ($user->is_accountant) {
            return $task->user_id === $user->id;
        }

        // Users can update the user checklist for their company tasks
        return $user->companies->contains('id', $task->company_id);
    }

    /**
     * Determine whether the user can review the task.
     */
    public function review(User $user, TaxCalendarTask $task)
    {
        // Only accountants can review tasks
        if (!$user->is_accountant) {
            return false;
        }

        // Accountant can only review tasks from their assigned companies
        return $user->assignedCompanies()
            ->where('companies.id', $task->company_id)
            ->exists();
    }
} 