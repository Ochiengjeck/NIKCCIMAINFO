<?php

namespace App\Notifications;

use App\Models\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MembershipExpiringSoon extends Notification
{
    use Queueable;

    public function __construct(public Member $member) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('NiKCCIMA — Membership Expiring Soon')
            ->greeting("Dear {$this->member->full_name},")
            ->line("Your NiKCCIMA membership (#{$this->member->membership_number}) will expire on {$this->member->expires_at->format('d F Y')}.")
            ->line('Please renew your membership to continue enjoying NiKCCIMA benefits.')
            ->action('Renew Membership', url('/'))
            ->line('Thank you for being a valued member of NiKCCIMA.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => "Membership expiring soon for {$this->member->full_name}",
            'member_id' => $this->member->id,
        ];
    }
}
