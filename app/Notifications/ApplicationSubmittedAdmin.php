<?php

namespace App\Notifications;

use App\Models\MembershipApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationSubmittedAdmin extends Notification
{
    use Queueable;

    public function __construct(public MembershipApplication $application) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('New membership application #'.$this->application->id)
            ->greeting('New membership application')
            ->line('A new membership application has been submitted and is awaiting review.')
            ->line('Applicant: '.$this->application->applicant_name)
            ->line('Organization: '.$this->application->organization)
            ->line('Email: '.$this->application->email);

        if ($this->application->category) {
            $mail->line('Category: '.$this->application->category->name);
        }

        return $mail->action('Review Application', route('admin.membership.applications'));
    }
}
