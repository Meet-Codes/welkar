<?php

namespace App\Services\Inventory;

use App\Support\Database;

class InventoryService
{
    public static function reserveStock(int $variantId, int $quantity, string $sessionId, int $ttlMinutes = 15): bool
    {
        return Database::transaction(function ($pdo) use ($variantId, $quantity, $sessionId, $ttlMinutes) {
            $stock = Database::fetch(
                "SELECT * FROM inventory_stock WHERE variant_id = ? AND location_id = 1 FOR UPDATE",
                [$variantId]
            );

            if (!$stock) {
                return false;
            }

            $available = (int)$stock['quantity'] - (int)$stock['reserved_quantity'];
            if ($available < $quantity) {
                return false;
            }

            Database::execute(
                "UPDATE inventory_stock SET reserved_quantity = reserved_quantity + ? WHERE id = ?",
                [$quantity, $stock['id']]
            );

            Database::execute(
                "INSERT INTO inventory_reservations (variant_id, session_id, quantity, expires_at, status)
                 VALUES (?, ?, ?, DATE_ADD(NOW(), INTERVAL ? MINUTE), 'active')",
                [$variantId, $sessionId, $quantity, $ttlMinutes]
            );

            return true;
        });
    }

    public static function releaseExpiredReservations(): int
    {
        $expired = Database::query(
            "SELECT * FROM inventory_reservations WHERE status = 'active' AND expires_at < NOW()"
        );

        $releasedCount = 0;
        foreach ($expired as $res) {
            Database::transaction(function () use ($res) {
                Database::execute(
                    "UPDATE inventory_stock SET reserved_quantity = GREATEST(0, reserved_quantity - ?)
                     WHERE variant_id = ? AND location_id = 1",
                    [$res['quantity'], $res['variant_id']]
                );
                Database::execute(
                    "UPDATE inventory_reservations SET status = 'expired' WHERE id = ?",
                    [$res['id']]
                );
            });
            $releasedCount++;
        }

        return $releasedCount;
    }

    public static function deductStock(
        int $variantId,
        int $quantity,
        string $orderNumber,
        string $actor = 'system'
    ): void {
        Database::transaction(function ($pdo) use ($variantId, $quantity, $orderNumber, $actor) {
            $stock = Database::fetch(
                "SELECT * FROM inventory_stock WHERE variant_id = ? AND location_id = 1 FOR UPDATE",
                [$variantId]
            );

            if (!$stock) {
                throw new \Exception("Stock entry not found for variant #{$variantId}");
            }

            $currentQty = (int)$stock['quantity'];
            $newQty = max(0, $currentQty - $quantity);
            $newReserved = max(0, (int)$stock['reserved_quantity'] - $quantity);

            Database::execute(
                "UPDATE inventory_stock SET quantity = ?, reserved_quantity = ?, updated_at = NOW() WHERE id = ?",
                [$newQty, $newReserved, $stock['id']]
            );

            // Audit ledger movement
            Database::execute(
                "INSERT INTO inventory_movements (
                    variant_id, location_id, change_qty, balance_after,
                    reason, reference_type, reference_id, created_by
                ) VALUES (?, 1, ?, ?, 'Order Fulfilment', 'order', ?, ?)",
                [$variantId, -$quantity, $newQty, $orderNumber, $actor]
            );
        });
    }

    public static function adjustStock(
        int $variantId,
        int $changeQty,
        string $reason,
        string $actor
    ): int {
        return Database::transaction(function () use ($variantId, $changeQty, $reason, $actor) {
            $stock = Database::fetch(
                "SELECT * FROM inventory_stock WHERE variant_id = ? AND location_id = 1 FOR UPDATE",
                [$variantId]
            );

            $currentQty = $stock ? (int)$stock['quantity'] : 0;
            $newQty = max(0, $currentQty + $changeQty);

            if ($stock) {
                Database::execute(
                    "UPDATE inventory_stock SET quantity = ?, updated_at = NOW() WHERE id = ?",
                    [$newQty, $stock['id']]
                );
            } else {
                Database::execute(
                    "INSERT INTO inventory_stock (variant_id, location_id, quantity) VALUES (?, 1, ?)",
                    [$variantId, $newQty]
                );
            }

            Database::execute(
                "INSERT INTO inventory_movements (
                    variant_id, location_id, change_qty, balance_after,
                    reason, reference_type, reference_id, created_by
                ) VALUES (?, 1, ?, ?, ?, 'manual_adjustment', NULL, ?)",
                [$variantId, $changeQty, $newQty, $reason, $actor]
            );

            return $newQty;
        });
    }

    public static function restockOrder(int $orderId, string $actor = 'admin'): void
    {
        $items = Database::query("SELECT * FROM order_items WHERE order_id = ?", [$orderId]);
        $order = Database::fetch("SELECT order_number FROM orders WHERE id = ?", [$orderId]);
        $orderNum = $order['order_number'] ?? (string)$orderId;

        foreach ($items as $item) {
            if (!empty($item['variant_id'])) {
                self::adjustStock(
                    (int)$item['variant_id'],
                    (int)$item['quantity'],
                    "Restock Order #{$orderNum}",
                    $actor
                );
            }
        }
    }
}
