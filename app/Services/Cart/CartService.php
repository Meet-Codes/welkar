<?php

namespace App\Services\Cart;

use App\Support\Database;
use App\Support\Session;
use App\Services\Pricing\PricingService;

class CartService
{
    private static function getSessionId(): string
    {
        Session::start();
        $sessId = Session::get('cart_session_id');
        if (!$sessId) {
            $sessId = bin2hex(random_bytes(16));
            Session::set('cart_session_id', $sessId);
        }
        return $sessId;
    }

    public static function getCart(): array
    {
        $sessionId = self::getSessionId();
        $user = Session::get('customer_user');
        $userId = $user['id'] ?? null;

        $cart = Database::fetch(
            "SELECT * FROM carts WHERE session_id = ? OR (user_id IS NOT NULL AND user_id = ?)",
            [$sessionId, $userId]
        );

        if (!$cart) {
            Database::execute(
                "INSERT INTO carts (session_id, user_id) VALUES (?, ?)",
                [$sessionId, $userId]
            );
            $cart = Database::fetch("SELECT * FROM carts WHERE session_id = ?", [$sessionId]);
        }

        // Fetch cart items
        $items = Database::query(
            "SELECT ci.id AS cart_item_id, ci.quantity, ci.unit_price,
                    pv.id AS variant_id, pv.sku, pv.variant_name, pv.dial_color, pv.strap_color, pv.price,
                    p.id AS product_id, p.name AS product_name, p.slug AS product_slug,
                    p.is_live_dial_animated,
                    COALESCE(pi.image_url, '/assets/images/products/placeholder.jpg') AS image_url,
                    COALESCE(s.quantity, 0) AS stock_quantity
             FROM cart_items ci
             JOIN product_variants pv ON ci.variant_id = pv.id
             JOIN products p ON pv.product_id = p.id
             LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_primary = 1
             LEFT JOIN inventory_stock s ON pv.id = s.variant_id
             WHERE ci.cart_id = ?",
            [$cart['id']]
        );

        // Fetch coupon if present
        $coupon = null;
        if (!empty($cart['coupon_code'])) {
            $coupon = Database::fetch(
                "SELECT * FROM coupons WHERE code = ? AND is_active = 1 AND (expires_at IS NULL OR expires_at > NOW())",
                [$cart['coupon_code']]
            );
        }

        $deliveryState = Session::get('checkout_delivery_state', 'Maharashtra');
        $pricing = PricingService::calculate($items, $coupon, $deliveryState);

        return [
            'cart_id' => $cart['id'],
            'coupon_code' => $cart['coupon_code'],
            'items' => $items,
            'summary' => $pricing,
        ];
    }

    public static function addItem(int $variantId, int $quantity = 1): array
    {
        $cart = self::getCart();
        $cartId = $cart['cart_id'];

        $variant = Database::fetch(
            "SELECT pv.*, p.name AS product_name, COALESCE(s.quantity, 0) AS stock_qty
             FROM product_variants pv
             JOIN products p ON pv.product_id = p.id
             LEFT JOIN inventory_stock s ON pv.id = s.variant_id
             WHERE pv.id = ? AND pv.is_active = 1",
            [$variantId]
        );

        if (!$variant) {
            return ['success' => false, 'message' => 'Watch variant not found or inactive.'];
        }

        $availableStock = (int)$variant['stock_qty'];
        if ($availableStock < 1) {
            return ['success' => false, 'message' => 'Sorry, this watch is currently out of stock.'];
        }

        $existing = Database::fetch(
            "SELECT * FROM cart_items WHERE cart_id = ? AND variant_id = ?",
            [$cartId, $variantId]
        );

        $newQty = $existing ? ((int)$existing['quantity'] + $quantity) : $quantity;
        if ($newQty > $availableStock) {
            $newQty = $availableStock;
        }

        if ($existing) {
            Database::execute(
                "UPDATE cart_items SET quantity = ?, unit_price = ?, updated_at = NOW() WHERE id = ?",
                [$newQty, $variant['price'], $existing['id']]
            );
        } else {
            Database::execute(
                "INSERT INTO cart_items (cart_id, variant_id, quantity, unit_price) VALUES (?, ?, ?, ?)",
                [$cartId, $variantId, $newQty, $variant['price']]
            );
        }

        return ['success' => true, 'message' => 'Added to your bag!'];
    }

    public static function updateQuantity(int $cartItemId, int $quantity): array
    {
        $cart = self::getCart();
        if ($quantity <= 0) {
            Database::execute("DELETE FROM cart_items WHERE id = ? AND cart_id = ?", [$cartItemId, $cart['cart_id']]);
            return ['success' => true, 'message' => 'Item removed from bag.'];
        }

        $item = Database::fetch(
            "SELECT ci.*, COALESCE(s.quantity, 0) AS stock_qty
             FROM cart_items ci
             LEFT JOIN inventory_stock s ON ci.variant_id = s.variant_id
             WHERE ci.id = ? AND ci.cart_id = ?",
            [$cartItemId, $cart['cart_id']]
        );

        if (!$item) {
            return ['success' => false, 'message' => 'Item not found in your bag.'];
        }

        $stock = (int)$item['stock_qty'];
        $finalQty = min($quantity, $stock);

        Database::execute(
            "UPDATE cart_items SET quantity = ?, updated_at = NOW() WHERE id = ?",
            [$finalQty, $cartItemId]
        );

        return ['success' => true, 'message' => 'Bag updated.'];
    }

    public static function removeItem(int $cartItemId): array
    {
        $cart = self::getCart();
        Database::execute("DELETE FROM cart_items WHERE id = ? AND cart_id = ?", [$cartItemId, $cart['cart_id']]);
        return ['success' => true, 'message' => 'Item removed.'];
    }

    public static function applyCoupon(string $code): array
    {
        $cart = self::getCart();
        $cleanCode = strtoupper(trim($code));

        $coupon = Database::fetch(
            "SELECT * FROM coupons WHERE code = ? AND is_active = 1",
            [$cleanCode]
        );

        if (!$coupon) {
            return ['success' => false, 'message' => 'Invalid or inactive coupon code.'];
        }

        if (!empty($coupon['expires_at']) && strtotime($coupon['expires_at']) < time()) {
            return ['success' => false, 'message' => 'This coupon has expired.'];
        }

        if ($coupon['usage_limit_total'] !== null && (int)$coupon['usage_count'] >= (int)$coupon['usage_limit_total']) {
            return ['success' => false, 'message' => 'Coupon usage limit has been reached.'];
        }

        // Subtotal validation
        $subtotal = $cart['summary']['subtotal'];
        if ($subtotal < (float)$coupon['min_order_value']) {
            return [
                'success' => false,
                'message' => 'Minimum order amount for this coupon is ' . format_currency($coupon['min_order_value'])
            ];
        }

        Database::execute("UPDATE carts SET coupon_code = ? WHERE id = ?", [$cleanCode, $cart['cart_id']]);
        return ['success' => true, 'message' => "Coupon '{$cleanCode}' applied successfully!"];
    }

    public static function removeCoupon(): array
    {
        $cart = self::getCart();
        Database::execute("UPDATE carts SET coupon_code = NULL WHERE id = ?", [$cart['cart_id']]);
        return ['success' => true, 'message' => 'Coupon removed.'];
    }

    public static function mergeUserCart(int $userId): void
    {
        $sessionId = self::getSessionId();
        Database::execute(
            "UPDATE carts SET user_id = ? WHERE session_id = ?",
            [$userId, $sessionId]
        );
    }

    public static function clearCart(int $cartId): void
    {
        Database::execute("DELETE FROM cart_items WHERE cart_id = ?", [$cartId]);
        Database::execute("UPDATE carts SET coupon_code = NULL WHERE id = ?", [$cartId]);
    }
}
