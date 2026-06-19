<?php

namespace App\Notifications;

use App\Models\Lease;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RentReminderNotification extends Notification
{
    use Queueable;

    public function __construct(public Payment $payment, public Lease $lease) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Rent Reminder - ' . config('app.name'))
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('This is a reminder that your rent payment is due.')
            ->line('Unit: ' . $this->lease->unit->unit_number)
            ->line('Amount Due: $' . number_format($this->payment->amount, 2))
            ->line('Due Date: ' . $this->payment->due_date->format('M d, Y'))
            ->line('Invoice: ' . $this->payment->invoice_number)
            ->action('Pay Now', url(route('payments.index')))
            ->line('Thank you for your prompt payment!');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'rent_reminder',
            'payment_id' => $this->payment->id,
            'lease_id' => $this->lease->id,
            'invoice_number' => $this->payment->invoice_number,
            'amount' => $this->payment->amount,
            'due_date' => $this->payment->due_date->format('Y-m-d'),
            'message' => 'Rent payment of $' . number_format($this->payment->amount, 2) . ' is due on ' . $this->payment->due_date->format('M d, Y'),
        ];
    }
}
