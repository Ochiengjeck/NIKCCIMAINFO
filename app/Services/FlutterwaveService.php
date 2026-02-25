<?php

namespace App\Services;

use Illuminate\Support\Str;

class FlutterwaveService
{
    protected string $publicKey;

    protected string $secretKey;

    protected string $secretHash;

    public function __construct()
    {
        $this->publicKey = config('services.flutterwave.public_key', '');
        $this->secretKey = config('services.flutterwave.secret_key', '');
        $this->secretHash = config('services.flutterwave.secret_hash', '');
    }

    /**
     * Generate a payment link for the given transaction data.
     * Returns ['link' => url, 'reference' => tx_ref]
     */
    public function initiatePayment(array $data): array
    {
        $reference = $data['reference'] ?? 'NIK-'.strtoupper(Str::random(12));

        $payload = [
            'tx_ref' => $reference,
            'amount' => $data['amount'],
            'currency' => $data['currency'] ?? 'NGN',
            'redirect_url' => config('services.flutterwave.redirect_url'),
            'customer' => [
                'email' => $data['email'] ?? '',
                'name' => $data['name'] ?? '',
            ],
            'customizations' => [
                'title' => 'NiKCCIMA Payment',
                'description' => $data['description'] ?? 'Payment to NiKCCIMA',
            ],
        ];

        if (empty($this->secretKey)) {
            // Flutterwave not configured — return placeholder
            return ['link' => '#', 'reference' => $reference];
        }

        $response = \Illuminate\Support\Facades\Http::withToken($this->secretKey)
            ->post('https://api.flutterwave.com/v3/payments', $payload);

        return [
            'link' => $response->json('data.link', '#'),
            'reference' => $reference,
        ];
    }

    /**
     * Verify a transaction by Flutterwave transaction ID.
     */
    public function verifyTransaction(string $transactionId): array
    {
        if (empty($this->secretKey)) {
            return ['status' => 'error', 'message' => 'Flutterwave not configured.'];
        }

        $response = \Illuminate\Support\Facades\Http::withToken($this->secretKey)
            ->get("https://api.flutterwave.com/v3/transactions/{$transactionId}/verify");

        return $response->json() ?? ['status' => 'error'];
    }

    /**
     * Validate incoming webhook signature.
     */
    public function validateWebhookSignature(string $header): bool
    {
        return $this->secretHash !== '' && $header === $this->secretHash;
    }
}
