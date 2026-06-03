<?php

namespace App\Notifications;

use App\Models\MembershipApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationApproved extends Notification
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
            ->subject('NiKCCIMA — Membership Application Approved')
            ->greeting("Dear {$this->application->applicant_name},")
            ->line('Congratulations! Your membership application to NiKCCIMA has been approved.')
            ->line('Please proceed to payment to activate your membership.')
            ->action('Complete Payment', url('/'))
            ->line('Welcome to the Nigeria-Kenya Chamber of Commerce, Industry, Mines & Agriculture.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => "Application approved for {$this->application->applicant_name}",
            'application_id' => $this->application->id,
        ];
    }
}
