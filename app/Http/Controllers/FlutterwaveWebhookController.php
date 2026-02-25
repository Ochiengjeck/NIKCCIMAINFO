<?php

namespace App\Http\Controllers;

use App\Models\FinancialTransaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
            FinancialTransaction::where('reference', $txData['tx_ref'])
                ->where('status', 'pending')
                ->update([
                    'status' => 'paid',
                    'gateway_ref' => (string) ($txData['id'] ?? ''),
                    'paid_at' => now(),
                ]);
        }

        return response('OK', 200);
    }
}
