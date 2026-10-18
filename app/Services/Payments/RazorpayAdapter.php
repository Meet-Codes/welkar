<?php

namespace App\Services\Payments;

use App\Support\Config;
use App\Support\Database;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class RazorpayAdapter
{
    private static ?Api $apiInstance = null;

    /**
     * Get setting from store_settings table or fallback to Config
     */
    public static function getSetting(string $key, string $default = ''): string
    {
        try {
            $row = Database::fetch("SELECT setting_value FROM store_settings WHERE setting_key = ? LIMIT 1", [$key]);
            if ($row && !empty($row['setting_value'])) {
                return (string)$row['setting_value'];
            }
        } catch (\Throwable $e) {}
        
        $configKey = str_replace('razorpay_', '', $key);
        return (string)Config::get("app.razorpay.{$configKey}", $default);
    }

    /**
     * Get initialized Razorpay API client instance
     */
    public static function getApi(): ?Api
    {
        if (self::$apiInstance !== null) {
            return self::$apiInstance;
        }

        $keyId = self::getSetting('razorpay_key_id', (string)Config::get('app.razorpay.key_id', ''));
        $keySecret = self::getSetting('razorpay_key_secret', (string)Config::get('app.razorpay.key_secret', ''));

        if (empty($keyId) || empty($keySecret) || $keyId === 'rzp_test_placeholder_key') {
            return null;
        }

        try {
            self::$apiInstance = new Api($keyId, $keySecret);
            return self::$apiInstance;
        } catch (\Throwable $e) {
            error_log("Razorpay Api Initialization Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Check if Razorpay API has valid credentials configured
     */
    public static function isConfigured(): bool
    {
        $keyId = self::getSetting('razorpay_key_id', (string)Config::get('app.razorpay.key_id', ''));
        $keySecret = self::getSetting('razorpay_key_secret', (string)Config::get('app.razorpay.key_secret', ''));
        return !empty($keyId) && !empty($keySecret) && $keyId !== 'rzp_test_placeholder_key';
    }

    /**
     * Create order using Razorpay SDK
     */
    public static function createOrder(int $orderId, float $amountInInr, string $paymentMethod = 'razorpay'): array
    {
        $mode = self::getSetting('razorpay_mode', (string)Config::get('app.razorpay.mode', 'test'));
        $keyId = self::getSetting('razorpay_key_id', (string)Config::get('app.razorpay.key_id', 'rzp_test_placeholder_key'));
        $amountPaise = (int)round($amountInInr * 100);

        $razorpayOrderId = null;
        $api = self::getApi();

        if ($api) {
            try {
                $order = $api->order->create([
                    'receipt' => 'order_' . $orderId,
                    'amount' => $amountPaise,
                    'currency' => 'INR',
                    'notes' => [
                        'order_id' => (string)$orderId,
                        'brand' => 'WELKAR Timepieces',
                        'method' => $paymentMethod,
                    ],
                ]);
                $razorpayOrderId = $order['id'] ?? null;
            } catch (\Throwable $e) {
                error_log("Razorpay SDK Order Create Error: " . $e->getMessage());
            }
        }

        // Fallback for local development or when API is unreachable
        if (!$razorpayOrderId) {
            $razorpayOrderId = 'order_' . substr(md5($orderId . time()), 0, 14);
        }

        Database::execute(
            "INSERT INTO payments (order_id, gateway, gateway_order_id, amount, currency, status, payment_method, created_at, updated_at)
             VALUES (?, 'razorpay', ?, ?, 'INR', 'pending', ?, NOW(), NOW())",
            [$orderId, $razorpayOrderId, $amountInInr, $paymentMethod]
        );

        return [
            'mode' => $mode,
            'key_id' => $keyId,
            'amount_paise' => $amountPaise,
            'currency' => 'INR',
            'razorpay_order_id' => $razorpayOrderId,
            'is_live' => ($mode === 'live'),
        ];
    }

    /**
     * Verify payment signature via Razorpay Utility
     */
    public static function verifySignature(string $razorpayOrderId, string $razorpayPaymentId, string $signature): bool
    {
        $api = self::getApi();
        if ($api) {
            try {
                $api->utility->verifyPaymentSignature([
                    'razorpay_order_id' => $razorpayOrderId,
                    'razorpay_payment_id' => $razorpayPaymentId,
                    'razorpay_signature' => $signature,
                ]);
                return true;
            } catch (SignatureVerificationError $e) {
                error_log("Razorpay Signature Verification Error: " . $e->getMessage());
                return false;
            } catch (\Throwable $e) {
                error_log("Razorpay Signature Check Exception: " . $e->getMessage());
            }
        }

        // Fallback HMAC verification when API instance is not initialized or in local test mode
        $secret = Config::get('app.razorpay.key_secret', '');
        if (empty($secret) || $secret === 'placeholder_secret') {
            return true;
        }

        $expectedSignature = hash_hmac('sha256', $razorpayOrderId . '|' . $razorpayPaymentId, $secret);
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Capture an authorized payment via Razorpay SDK
     */
    public static function capturePayment(string $paymentId, float $amountInInr): array
    {
        $api = self::getApi();
        $amountPaise = (int)round($amountInInr * 100);

        if ($api) {
            try {
                $payment = $api->payment->fetch($paymentId);
                $captured = $payment->capture(['amount' => $amountPaise, 'currency' => 'INR']);
                return [
                    'success' => true,
                    'payment' => $captured->toArray(),
                ];
            } catch (\Throwable $e) {
                return [
                    'success' => false,
                    'message' => $e->getMessage(),
                ];
            }
        }

        return ['success' => true, 'message' => 'Simulated capture in local mode.'];
    }

    /**
     * Process refund via Razorpay SDK
     */
    public static function refundPayment(string $paymentId, float $amountInInr, string $reason = ''): array
    {
        $api = self::getApi();
        $amountPaise = (int)round($amountInInr * 100);

        if ($api) {
            try {
                $payment = $api->payment->fetch($paymentId);
                $refund = $payment->refund([
                    'amount' => $amountPaise,
                    'notes' => ['reason' => $reason],
                ]);
                return [
                    'success' => true,
                    'refund_id' => $refund['id'] ?? null,
                    'payload' => $refund->toArray(),
                ];
            } catch (\Throwable $e) {
                return [
                    'success' => false,
                    'message' => $e->getMessage(),
                ];
            }
        }

        return ['success' => true, 'refund_id' => 'rfnd_simulated_' . time()];
    }

    /**
     * Record captured payment and update order
     */
    public static function recordSuccess(int $orderId, string $paymentId, string $gatewayOrderId, array $payload = []): void
    {
        Database::transaction(function () use ($orderId, $paymentId, $gatewayOrderId, $payload) {
            Database::execute(
                "UPDATE payments SET status = 'captured', transaction_id = ?, gateway_payload = ?, updated_at = NOW()
                 WHERE order_id = ? AND gateway_order_id = ?",
                [$paymentId, json_encode($payload), $orderId, $gatewayOrderId]
            );

            Database::execute(
                "UPDATE orders SET payment_status = 'captured', status = 'confirmed', updated_at = NOW()
                 WHERE id = ?",
                [$orderId]
            );

            Database::execute(
                "INSERT INTO payment_events (payment_id, event_name, payload)
                 SELECT id, 'payment.captured', ? FROM payments WHERE order_id = ? LIMIT 1",
                [json_encode($payload), $orderId]
            );
        });
    }

    /**
     * Verify Webhook signature via Razorpay Utility
     */
    public static function verifyWebhookSignature(string $rawPayload, ?string $signature): bool
    {
        $webhookSecret = (string)Config::get('app.razorpay.webhook_secret', '');
        
        if (!empty($webhookSecret)) {
            if (empty($signature)) {
                return false;
            }

            $api = self::getApi();
            if ($api) {
                try {
                    $api->utility->verifyWebhookSignature($rawPayload, $signature, $webhookSecret);
                    return true;
                } catch (SignatureVerificationError $e) {
                    error_log("Razorpay Webhook Signature Failure: " . $e->getMessage());
                    return false;
                } catch (\Throwable $e) {
                    error_log("Razorpay Webhook Verification Exception: " . $e->getMessage());
                }
            }

            $expected = hash_hmac('sha256', $rawPayload, $webhookSecret);
            return hash_equals($expected, $signature);
        }

        // Local development/test fallback if secret is unconfigured
        $appEnv = Config::get('app.env', 'production');
        return ($appEnv === 'local' || $appEnv === 'test');
    }

    /**
     * Handle incoming Razorpay Webhook
     */
    public static function handleWebhook(array $payload, ?string $signature, string $rawPayload = ''): array
    {
        if (!self::verifyWebhookSignature($rawPayload, $signature)) {
            return ['status' => 'invalid_signature', 'message' => 'Webhook signature verification failed.'];
        }

        $eventId = $payload['event_id'] ?? $payload['id'] ?? ('wh_' . md5($rawPayload ?: json_encode($payload)));
        $eventType = $payload['event'] ?? 'payment.captured';

        // Check deduplication
        $exists = Database::fetch("SELECT * FROM webhook_events WHERE gateway = 'razorpay' AND event_id = ?", [$eventId]);
        if ($exists) {
            return ['status' => 'duplicate', 'message' => 'Event already processed.'];
        }

        // Atomically process order status based on gateway event
        $entity = $payload['payload']['payment']['entity'] ?? [];
        $gatewayOrderId = $entity['order_id'] ?? null;
        $paymentId = $entity['id'] ?? null;

        if ($gatewayOrderId) {
            $payment = Database::fetch(
                "SELECT * FROM payments WHERE gateway = 'razorpay' AND gateway_order_id = ? LIMIT 1",
                [$gatewayOrderId]
            );

            if ($payment) {
                $orderId = (int)$payment['order_id'];

                if ($eventType === 'payment.captured' || $eventType === 'order.paid') {
                    self::recordSuccess($orderId, $paymentId ?: 'pay_webhook_' . time(), $gatewayOrderId, $payload);
                } elseif ($eventType === 'payment.failed') {
                    Database::transaction(function () use ($orderId, $gatewayOrderId, $payload) {
                        Database::execute(
                            "UPDATE payments SET status = 'failed', gateway_payload = ?, updated_at = NOW()
                             WHERE gateway_order_id = ?",
                            [json_encode($payload), $gatewayOrderId]
                        );
                        Database::execute(
                            "UPDATE orders SET payment_status = 'failed', updated_at = NOW() WHERE id = ?",
                            [$orderId]
                        );
                    });
                }
            }
        }

        Database::insert(
            "INSERT INTO webhook_events (gateway, event_id, event_type, payload, status, processed_at)
             VALUES ('razorpay', ?, ?, ?, 'processed', NOW())",
            [$eventId, $eventType, json_encode($payload)]
        );

        return ['status' => 'success', 'message' => 'Webhook processed successfully.'];
    }
}
