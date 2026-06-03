<?php

namespace App\Http\Controllers;

use App\Models\FinancialTransaction;
use App\Notifications\PaymentReceived;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Notification;

class FlutterwaveWebhookController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $hash = config('services.flutterwave.secret_hash', '');

        if ($hash && $request->header('verif-hash') !== $hash) {
            abort(403, 'Invalid webhook signature.');
        }

        $data = $request->all();
        $status = $data['status'] ?? '';
        $txData = $data['data'] ?? [];

        if ($status === 'successful' && isset($txData['tx_ref'])) {
            $transactions = FinancialTransaction::with('member')
                ->where('reference', $txData['tx_ref'])
                ->where('status', 'pending')
                ->get();

            foreach ($transactions as $tx) {
                $tx->update([
                    'status' => 'paid',
                    'gateway_ref' => (string) ($txData['id'] ?? ''),
                    'paid_at' => now(),
                ]);

                // Email the payer a receipt — never let a mail failure fail the webhook
                // (that would trigger Flutterwave retries).
                try {
                    if ($email = optional($tx->member)->email) {
                        Notification::route('mail', $email)->notify(new PaymentReceived($tx));
                    }
                } catch (\Throwable $e) {
                    report($e);
                }
            }
        }

        return response('OK', 200);
    }
}
