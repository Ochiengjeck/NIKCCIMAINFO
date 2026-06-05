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
        $app = $this->application->loadMissing(['category', 'chapter']);

        $mail = (new MailMessage)
            ->subject('NiKCCIMA — Membership Application Received')
            ->greeting("Dear {$app->applicant_name},")
            ->line('Your membership application to NiKCCIMA has been received and is under review. Here is a summary of what you submitted:')
            ->line("**Reference:** #{$app->id}")
            ->line('**Applicant:** '.$app->applicant_name.($app->organization && $app->organization !== $app->applicant_name ? ' ('.$app->organization.')' : ''))
            ->line('**Category:** '.($app->category?->name ?? '—'))
            ->line('**Chapter:** '.($app->chapter?->name ?? '—'))
            ->line('**Submitted:** '.optional($app->submitted_at)->format('d M Y') ?? now()->format('d M Y'));

        if ($app->isPriceOnRequest()) {
            $mail->line('**Fee:** On request — our secretariat will advise the exact subscription fee.');
        } else {
            $mail->line('**Indicative fee:** '.$app->chargeLabel().' (payable once your application is approved).');
        }

        return $mail
            ->line('Our membership officer will review your application and you will be notified at each step.')
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
