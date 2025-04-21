<?php

namespace App\Notifications;

use App\Models\TaxCalendarTask;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskReviewed extends Notification implements ShouldQueue
{
    use Queueable;

    protected $task;

    public function __construct(TaxCalendarTask $task)
    {
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $status = ucfirst($this->task->status);
        $taskName = $this->task->taxCalendar->name;

        return (new MailMessage)
            ->subject("Task Review: {$status}")
            ->line("Your task '{$taskName}' has been reviewed.")
            ->line("Status: {$status}")
            ->line("Review Comments: {$this->task->review_comments}")
            ->action('View Task', route('user.tax-calendar.show', $this->task->id))
            ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'task_id' => $this->task->id,
            'status' => $this->task->status,
            'review_comments' => $this->task->review_comments,
            'reviewed_at' => $this->task->reviewed_at,
            'reviewed_by' => $this->task->reviewed_by,
        ];
    }
} 