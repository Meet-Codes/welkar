<?php

namespace App\Controllers\Webhooks;

use App\Support\Request;
use App\Support\Response;
use App\Services\Payments\RazorpayAdapter;

class RazorpayWebhookController
{
    public function handle(Request $request): void
    {
        $rawBody = $request->rawBody();
        $payload = $request->json();
        $signature = $request->header('X-Razorpay-Signature');

        $result = RazorpayAdapter::handleWebhook($payload, $signature, $rawBody);

        $httpCode = match ($result['status']) {
            'invalid_signature' => 400,
            'error' => 500,
            default => 200,
        };

        Response::json([
            'status' => $result['status'],
            'message' => $result['message'] ?? 'Processed',
        ], $httpCode);
    }
}
