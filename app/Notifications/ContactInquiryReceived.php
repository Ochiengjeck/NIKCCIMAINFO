<?php

namespace App\Notifications;

use App\Models\ContactInquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactInquiryReceived extends Notification
{
    use Queueable;

    public function __construct(public ContactInquiry $inquiry) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $chapter = ucfirst($this->inquiry->chapter ?? 'general');

        return (new MailMessage)
            ->subject('New contact inquiry: '.$this->inquiry->subject)
            ->replyTo($this->inquiry->email, $this->inquiry->name)
            ->greeting('New contact inquiry')
            ->line('A new message was submitted via the NiKCCIMA website.')
            ->line('From: '.$this->inquiry->name.' <'.$this->inquiry->email.'>')
            ->line('Chapter: '.$chapter)
            ->line('Subject: '.$this->inquiry->subject)
            ->line('Message:')
            ->line($this->inquiry->message)
            ->line('Reply directly to this email to respond to the sender.');
    }
}
