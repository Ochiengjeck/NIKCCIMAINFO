<?php

namespace App\Notifications;

use App\Models\MembershipApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationStageAdvanced extends Notification
{
    use Queueable;

    public function __construct(
        public MembershipApplication $application,
        public string $stage,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        [$headline, $detail] = $this->messageFor();

        return (new MailMessage)
            ->subject('NiKCCIMA — Membership Application Update')
            ->greeting("Dear {$this->application->applicant_name},")
            ->line($headline)
            ->line($detail)
            ->line('Reference: #'.$this->application->id)
            ->line('We will email you again as your application progresses. Thank you for your patience.');
    }

    /**
     * @return array{0:string,1:string}
     */
    private function messageFor(): array
    {
        return match ($this->stage) {
            'membership-officer' => [
                'Good news — your membership application has been reviewed by our Membership Officer and is progressing.',
                'It now moves to the Chairman (Membership Development) for the next review.',
            ],
            'chairman' => [
                'Your membership application has been approved by the Chairman (Membership Development).',
                'It now moves to the Director General for final approval.',
            ],
            default => [
                'Your membership application has progressed to the next stage.',
                'We will keep you updated.',
            ],
        };
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => "Application advanced ({$this->stage}) for {$this->application->applicant_name}",
            'application_id' => $this->application->id,
        ];
    }
}
