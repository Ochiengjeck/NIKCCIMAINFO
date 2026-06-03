<?php

namespace App\Notifications;

use App\Models\MembershipApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationRejected extends Notification
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
            ->subject('NiKCCIMA — Membership Application Update')
            ->greeting("Dear {$this->application->applicant_name},")
            ->line('After careful review, we regret to inform you that your membership application was not successful at this time.')
            ->when($this->application->rejection_reason, fn ($mail) => $mail->line("Reason: {$this->application->rejection_reason}"))
            ->line('You are welcome to re-apply after addressing the above concerns.')
            ->line('Thank you for your interest in NiKCCIMA.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => "Application rejected for {$this->application->applicant_name}",
            'application_id' => $this->application->id,
        ];
    }
}
