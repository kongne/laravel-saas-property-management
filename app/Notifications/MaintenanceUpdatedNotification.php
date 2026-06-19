<?php

namespace App\Notifications;

use App\Models\MaintenanceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MaintenanceUpdatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public MaintenanceRequest $request,
        public string $action,
        public ?string $note = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $subject = match ($this->action) {
            'created' => 'New Maintenance Request Submitted',
            'assigned' => 'Maintenance Request Assigned',
            'resolved' => 'Maintenance Request Resolved',
            default => 'Maintenance Request Updated',
        };

        return (new MailMessage)
            ->subject($subject . ' - ' . config('app.name'))
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line($this->getDescription())
            ->line('Title: ' . $this->request->title)
            ->line('Priority: ' . ucfirst($this->request->priority))
            ->line('Status: ' . str_replace('_', ' ', ucwords($this->request->status, '_')))
            ->when($this->note, fn ($msg) => $msg->line('Note: ' . $this->note))
            ->action('View Request', url(route('maintenance.show', $this->request)))
            ->line('Thank you for using ' . config('app.name') . '!');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'maintenance_' . $this->action,
            'maintenance_id' => $this->request->id,
            'title' => $this->request->title,
            'status' => $this->request->status,
            'action' => $this->action,
            'message' => $this->getDescription(),
        ];
    }

    private function getDescription(): string
    {
        return match ($this->action) {
            'created' => 'A new maintenance request has been submitted: ' . $this->request->title,
            'assigned' => 'Maintenance request "' . $this->request->title . '" has been assigned to ' . ($this->request->assigned_to ?? 'a technician'),
            'resolved' => 'Maintenance request "' . $this->request->title . '" has been resolved.',
            default => 'Maintenance request "' . $this->request->title . '" has been updated.',
        };
    }
}
