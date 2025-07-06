<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\AnonymousNotifiable;

class InvoiceSent extends Notification implements ShouldQueue
{
    use Queueable;

    protected $invoice;
    protected $message;
    protected $ccEmail;

    public function __construct(Invoice $invoice, string $message, ?string $ccEmail = null)
    {
        $this->invoice = $invoice;
        $this->message = $message;
        $this->ccEmail = $ccEmail;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject(__('Invoice') . ' #' . $this->invoice->invoice_number . ' - ' . $this->invoice->company->name)
            ->greeting(__('Dear Customer,'))
            ->line($this->message);

        // Add CC if provided
        if ($this->ccEmail) {
            $mail->cc($this->ccEmail);
        }

        // Attach PDF if it exists
        if ($this->invoice->pdf_path && file_exists(storage_path('app/' . $this->invoice->pdf_path))) {
            $mail->attach(storage_path('app/' . $this->invoice->pdf_path), [
                'as' => 'invoice-' . $this->invoice->invoice_number . '.pdf',
                'mime' => 'application/pdf',
            ]);
        } elseif ($this->invoice->pdf_url) {
            // If using external storage (Bunny CDN), we need to download and attach
            $mail->line(__('Please find the invoice PDF at the following link:'))
                 ->action(__('Download Invoice PDF'), $this->invoice->download_url);
        }

        $mail->line(__('Thank you for your business!'));

        return $mail;
    }
}