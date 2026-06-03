<?php

namespace App\Notifications;

use App\Models\ContactInquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactInquiryAcknowledged extends Notification
{
    use Queueable;

    public function __construct(public ContactInquiry $inquiry) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('We received your message — NiKCCIMA')
            ->greeting("Dear {$this->inquiry->name},")
            ->line('Thank you for contacting the Nigeria-Kenya Chamber of Commerce, Industry, Mines & Agriculture.')
            ->line('We have received your message regarding "'.$this->inquiry->subject.'" and our team will respond shortly.')
            ->line('This is an automated acknowledgement — please do not reply to this email.')
            ->salutation('Warm regards, The NiKCCIMA Secretariat');
    }
}
