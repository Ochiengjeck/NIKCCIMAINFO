<?php

namespace App\Notifications;

use App\Models\MembershipApplication;
use Barryvdh\DomPDF\Facade\Pdf;
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
        $app = $this->application->loadMissing(['category', 'chapter']);

        $mail = (new MailMessage)
            ->subject('NiKCCIMA — Membership Application Approved: Payment Required')
            ->greeting("Dear {$app->applicant_name},")
            ->line('Congratulations! Your membership application to NiKCCIMA has been approved by the Director General.')
            ->line('**Category:** '.($app->category?->name ?? '—'));

        if ($app->hasPayableAmount()) {
            $mail->line('**Amount due:** '.$app->chargeLabel())
                ->line('A pro-forma invoice is attached to this email. Please complete payment to activate your membership.')
                ->action('Complete Payment', url('/'));

            try {
                $pdf = Pdf::loadView('pdf.membership-invoice', ['application' => $app])->output();
                $mail->attachData($pdf, "NiKCCIMA-Invoice-{$app->id}.pdf", ['mime' => 'application/pdf']);
            } catch (\Throwable $e) {
                report($e);
            }
        } else {
            $mail->line('Our secretariat will contact you shortly with the exact subscription fee and payment details.');
        }

        return $mail->line('Welcome to the Nigeria-Kenya Chamber of Commerce, Industry, Mines & Agriculture.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => "Application approved for {$this->application->applicant_name}",
            'application_id' => $this->application->id,
        ];
    }
}
