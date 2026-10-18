<?php

namespace App\Services\Orders;

use App\Support\Database;
use App\Services\Cart\CartService;
use App\Services\Inventory\InventoryService;
use App\Services\Invoices\InvoiceService;

class OrderService
{
    public static function createOrder(array $data): array
    {
        return Database::transaction(function ($pdo) use ($data) {
            $cart = CartService::getCart();
            $items = $cart['items'];
            $summary = $cart['summary'];

            if (empty($items)) {
                throw new \Exception("Cannot place order with an empty bag.");
            }

            // Generate unique human-readable order number
            $orderNumber = 'WEL-ORD-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));

            $userId = $data['user_id'] ?? null;
            $guestPhone = $data['phone'] ?? '';
            $guestEmail = $data['email'] ?? null;
            $paymentMethod = $data['payment_method'] ?? 'cod';
            $shippingState = $data['state'] ?? 'Maharashtra';

            $isOnline = ($paymentMethod !== 'cod');
            $paymentStatus = $isOnline ? 'authorized' : 'unpaid';
            $orderStatus = $isOnline ? 'confirmed' : 'pending';

            $orderId = Database::insert(
                "INSERT INTO orders (
                    order_number, user_id, guest_email, guest_phone, status,
                    payment_status, fulfilment_status, currency, subtotal,
                    discount_amount, shipping_fee, tax_amount, total_amount,
                    payment_method, coupon_code, notes, shipping_state, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, 'unfulfilled', 'INR', ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())",
                [
                    $orderNumber, $userId, $guestEmail, $guestPhone, $orderStatus,
                    $paymentStatus, $summary['subtotal'], $summary['discount_amount'],
                    $summary['shipping_fee'], $summary['tax_amount'], $summary['total_amount'],
                    $paymentMethod, $cart['coupon_code'], $data['notes'] ?? null, $shippingState
                ]
            );

            // If COD, record initial payment ledger entry
            if (!$isOnline) {
                Database::execute(
                    "INSERT INTO payments (order_id, gateway, gateway_order_id, amount, currency, status, payment_method, created_at, updated_at)
                     VALUES (?, 'cod', ?, ?, 'INR', 'pending', 'cod', NOW(), NOW())",
                    [$orderId, 'cod_' . $orderNumber, $summary['total_amount']]
                );
            }

            // Save Shipping Address
            Database::execute(
                "INSERT INTO order_addresses (
                    order_id, type, full_name, phone, address_line1,
                    address_line2, city, state, state_code, pincode
                ) VALUES (?, 'shipping', ?, ?, ?, ?, ?, ?, ?, ?)",
                [
                    $orderId, $data['full_name'], $guestPhone,
                    $data['address_line1'], $data['address_line2'] ?? null,
                    $data['city'], $shippingState, $data['state_code'] ?? '27', $data['pincode']
                ]
            );

            // Record Initial Order Status History
            Database::execute(
                "INSERT INTO order_status_history (order_id, old_status, new_status, comment, changed_by)
                 VALUES (?, NULL, ?, 'Order placed by customer', 'customer')",
                [$orderId, $orderStatus]
            );

            // Create Order Items with Immutable Snapshots & Deduct Stock
            foreach ($items as $item) {
                $qty = (int)$item['quantity'];
                $unitPrice = (float)$item['price'];
                $itemSubtotal = $unitPrice * $qty;

                // Share of discount
                $discountShare = 0.0;
                if ($summary['subtotal'] > 0 && $summary['discount_amount'] > 0) {
                    $discountShare = round(($itemSubtotal / $summary['subtotal']) * $summary['discount_amount'], 2);
                }

                $taxableLine = max(0.0, $itemSubtotal - $discountShare);
                $taxAmount = round(($taxableLine * 18.0) / 100.0, 2);
                $lineTotal = round($taxableLine + $taxAmount, 2);

                $snapshot = json_encode([
                    'product_id' => $item['product_id'],
                    'product_name' => $item['product_name'],
                    'variant_id' => $item['variant_id'],
                    'variant_name' => $item['variant_name'],
                    'sku' => $item['sku'],
                    'dial_color' => $item['dial_color'],
                    'strap_color' => $item['strap_color'],
                    'unit_price' => $unitPrice,
                    'quantity' => $qty,
                ]);

                Database::execute(
                    "INSERT INTO order_items (
                        order_id, product_id, variant_id, product_name, variant_name,
                        sku, unit_price, quantity, subtotal, discount_share,
                        tax_rate, tax_amount, total, snapshot_data
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 18.00, ?, ?, ?)",
                    [
                        $orderId, $item['product_id'], $item['variant_id'],
                        $item['product_name'], $item['variant_name'], $item['sku'],
                        $unitPrice, $qty, $itemSubtotal, $discountShare,
                        $taxAmount, $lineTotal, $snapshot
                    ]
                );

                // Deduct stock in central inventory ledger
                InventoryService::deductStock($item['variant_id'], $qty, $orderNumber, 'storefront_checkout');
            }

            // Increment coupon usage count if used
            if (!empty($cart['coupon_code'])) {
                Database::execute(
                    "UPDATE coupons SET usage_count = usage_count + 1 WHERE code = ?",
                    [$cart['coupon_code']]
                );
            }

            // Generate official Tax Invoice
            InvoiceService::generateForOrder($orderId);

            // Clear Cart
            CartService::clearCart($cart['cart_id']);

            return Database::fetch("SELECT * FROM orders WHERE id = ?", [$orderId]);
        });
    }

    public static function updateStatus(int $orderId, string $newStatus, string $comment, string $changedBy = 'admin'): bool
    {
        $order = Database::fetch("SELECT * FROM orders WHERE id = ?", [$orderId]);
        if (!$order) {
            return false;
        }

        $oldStatus = $order['status'];
        if ($oldStatus === $newStatus) {
            return true;
        }

        Database::transaction(function () use ($orderId, $oldStatus, $newStatus, $comment, $changedBy) {
            Database::execute("UPDATE orders SET status = ?, updated_at = NOW() WHERE id = ?", [$newStatus, $orderId]);
            Database::execute(
                "INSERT INTO order_status_history (order_id, old_status, new_status, comment, changed_by)
                 VALUES (?, ?, ?, ?, ?)",
                [$orderId, $oldStatus, $newStatus, $comment, $changedBy]
            );
        });

        // Trigger review invitation email upon delivery
        if ($newStatus === 'delivered') {
            try {
                \App\Services\Mail\MailService::sendDeliveryReviewInvitation($orderId);
            } catch (\Throwable $e) {
                error_log("Failed to send review invitation email: " . $e->getMessage());
            }
        }

        return true;
    }

    public static function cancelOrder(int $orderId, string $reason, string $actor = 'customer'): array
    {
        $order = Database::fetch("SELECT * FROM orders WHERE id = ?", [$orderId]);
        if (!$order) {
            return ['success' => false, 'message' => 'Order not found.'];
        }

        if (in_array($order['status'], ['shipped', 'delivered', 'cancelled'])) {
            return ['success' => false, 'message' => 'Cannot cancel an order that is already ' . $order['status'] . '.'];
        }

        Database::transaction(function () use ($order, $orderId, $reason, $actor) {
            // Restore inventory stock
            $items = Database::query("SELECT * FROM order_items WHERE order_id = ?", [$orderId]);
            foreach ($items as $item) {
                InventoryService::adjustStock(
                    (int)$item['variant_id'],
                    (int)$item['quantity'],
                    "Order Cancelled ({$order['order_number']})",
                    $actor
                );
            }

            Database::execute(
                "UPDATE orders SET status = 'cancelled', is_cancelled = 1, cancelled_at = NOW(),
                                   cancel_reason = ?, updated_at = NOW() WHERE id = ?",
                [$reason, $orderId]
            );

            Database::execute(
                "INSERT INTO order_status_history (order_id, old_status, new_status, comment, changed_by)
                 VALUES (?, ?, 'cancelled', ?, ?)",
                [$orderId, $order['status'], "Cancelled: {$reason}", $actor]
            );
        });

        return ['success' => true, 'message' => 'Order cancelled and inventory restored.'];
    }

    public static function addTracking(int $orderId, string $courier, string $trackingNum, ?string $trackingUrl): bool
    {
        return Database::transaction(function () use ($orderId, $courier, $trackingNum, $trackingUrl) {
            Database::execute(
                "INSERT INTO shipping_shipments (order_id, courier_name, tracking_number, tracking_url, dispatched_at)
                 VALUES (?, ?, ?, ?, NOW())",
                [$orderId, $courier, $trackingNum, $trackingUrl]
            );

            self::updateStatus($orderId, 'shipped', "Dispatched with {$courier} (Tracking: {$trackingNum})", 'admin');
            Database::execute("UPDATE orders SET fulfilment_status = 'shipped' WHERE id = ?", [$orderId]);
            return true;
        });
    }
}
