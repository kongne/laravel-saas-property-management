<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentReceiptNotification extends Notification
{
    use Queueable;

    public function __construct(public Payment $payment) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Payment Receipt - ' . config('app.name'))
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your payment has been successfully processed.')
            ->line('Invoice: ' . $this->payment->invoice_number)
            ->line('Amount: $' . number_format($this->payment->amount, 2))
            ->line('Paid: $' . number_format($this->payment->paid_amount, 2))
            ->line('Method: ' . str_replace('_', ' ', ucwords($this->payment->payment_method ?? 'N/A', '_')))
            ->action('View Receipt', url(route('payments.receipt', $this->payment)))
            ->line('Thank you for your payment!');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'payment_receipt',
            'payment_id' => $this->payment->id,
            'invoice_number' => $this->payment->invoice_number,
            'amount' => $this->payment->amount,
            'paid_amount' => $this->payment->paid_amount,
            'message' => 'Payment of $' . number_format($this->payment->paid_amount, 2) . ' received for invoice ' . $this->payment->invoice_number,
        ];
    }
}
