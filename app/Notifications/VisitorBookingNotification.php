<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VisitorBookingNotification extends Notification
{
    use Queueable;

    public function __construct(public Booking $booking) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Visit Booking Received - ' . config('app.name'))
            ->greeting('Hello ' . $this->booking->name . '!')
            ->line('We have received your request to book a property viewing.')
            ->line('Property: ' . $this->booking->property->name)
            ->line('Date of Visit: ' . $this->booking->visit_date->format('M d, Y'))
            ->line('Time of Visit: ' . $this->booking->visit_time)
            ->line('Status: Pending Confirmation')
            ->line('The landlord or agent will contact you shortly to confirm or reschedule the visit.')
            ->line('Thank you for using ' . config('app.name') . '!');
    }
}
