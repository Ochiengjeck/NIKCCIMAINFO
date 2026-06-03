<?php

namespace App\Notifications;

use App\Models\FinancialTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentReceived extends Notification
{
    use Queueable;

    public function __construct(public FinancialTransaction $transaction) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $amount = number_format((float) $this->transaction->amount, 2);
        $currency = $this->transaction->currency ?: 'NGN';

        $mail = (new MailMessage)
            ->subject('Payment received — NiKCCIMA')
            ->greeting('Payment confirmed')
            ->line('We have received your payment. Thank you.')
            ->line('Amount: '.$currency.' '.$amount)
            ->line('Reference: '.$this->transaction->reference);

        if ($this->transaction->description) {
            $mail->line('For: '.$this->transaction->description);
        }

        return $mail
            ->line('Date: '.optional($this->transaction->paid_at)->format('d M Y, g:i A'))
            ->line('This receipt confirms your transaction with NiKCCIMA.');
    }
}
