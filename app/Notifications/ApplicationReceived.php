<?php

namespace App\Notifications;

use App\Models\MembershipApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationReceived extends Notification
{
    use Queueable;

    public function __construct(public MembershipApplication $application) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('NiKCCIMA — Membership Application Received')
            ->greeting("Dear {$this->application->applicant_name},")
            ->line('Your membership application to NiKCCIMA has been received and is under review.')
            ->line('Our membership officer will contact you shortly.')
            ->action('View Application Status', url('/'))
            ->line('Thank you for your interest in NiKCCIMA.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => "Application received for {$this->application->applicant_name}",
            'application_id' => $this->application->id,
        ];
    }
}
