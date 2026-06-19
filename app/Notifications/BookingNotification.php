<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingNotification extends Notification
{
    use Queueable;

    public function __construct(public Booking $booking) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Visit Booking Request - ' . config('app.name'))
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A visitor has booked a viewing for your property.')
            ->line('Property: ' . $this->booking->property->name)
            ->line('Visitor Name: ' . $this->booking->name)
            ->line('Visitor Phone: ' . $this->booking->phone)
            ->line('Visitor Email: ' . $this->booking->email)
            ->line('Date of Visit: ' . $this->booking->visit_date->format('M d, Y'))
            ->line('Time of Visit: ' . $this->booking->visit_time)
            ->line('Message: ' . ($this->booking->message ?? 'No message provided.'))
            ->action('Manage Bookings', url('/dashboard'))
            ->line('Thank you!');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'booking_request',
            'booking_id' => $this->booking->id,
            'property_id' => $this->booking->property_id,
            'title' => 'New Visit Booking Request',
            'message' => $this->booking->name . ' booked a visit for ' . $this->booking->property->name . ' on ' . $this->booking->visit_date->format('M d, Y'),
        ];
    }
}
