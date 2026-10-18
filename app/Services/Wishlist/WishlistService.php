<?php

namespace App\Services\Wishlist;

use App\Support\Database;
use App\Support\Session;
use App\Services\Cart\CartService;

class WishlistService
{
    public static function getWishlistId(): int
    {
        $userId = auth_user()['id'] ?? null;
        $sessionId = Session::getId();

        if ($userId) {
            $wishlist = Database::fetch("SELECT id FROM wishlists WHERE user_id = ? LIMIT 1", [$userId]);
            if ($wishlist) {
                return (int)$wishlist['id'];
            }

            // Check if there was an existing session wishlist to merge/link
            $sessionWishlist = Database::fetch("SELECT id FROM wishlists WHERE session_id = ? AND user_id IS NULL LIMIT 1", [$sessionId]);
            if ($sessionWishlist) {
                Database::query("UPDATE wishlists SET user_id = ? WHERE id = ?", [$userId, $sessionWishlist['id']]);
                return (int)$sessionWishlist['id'];
            }

            return (int)Database::insert("INSERT INTO wishlists (user_id, session_id) VALUES (?, ?)", [$userId, $sessionId]);
        }

        // Guest user
        $wishlist = Database::fetch("SELECT id FROM wishlists WHERE session_id = ? LIMIT 1", [$sessionId]);
        if ($wishlist) {
            return (int)$wishlist['id'];
        }

        return (int)Database::insert("INSERT INTO wishlists (session_id) VALUES (?)", [$sessionId]);
    }

    public static function getWishlistItems(): array
    {
        $wishlistId = self::getWishlistId();

        $sql = "SELECT wi.id AS wishlist_item_id, wi.product_id, wi.variant_id, wi.created_at,
                       p.name AS product_name, p.slug, p.base_sku, p.base_price, p.compare_at_price,
                       p.dial_type, b.name AS brand_name,
                       pv.variant_name, pv.price AS variant_price, pv.sku AS variant_sku,
                       (SELECT image_url FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) AS primary_image,
                       COALESCE((SELECT SUM(quantity - reserved_quantity) FROM inventory_stock WHERE variant_id = COALESCE(pv.id, (SELECT id FROM product_variants WHERE product_id = p.id LIMIT 1))), 0) AS stock_quantity
                FROM wishlist_items wi
                JOIN products p ON wi.product_id = p.id
                JOIN brands b ON p.brand_id = b.id
                LEFT JOIN product_variants pv ON wi.variant_id = pv.id
                WHERE wi.wishlist_id = ?
                ORDER BY wi.id DESC";

        return Database::fetchAll($sql, [$wishlistId]);
    }

    public static function getItemCount(): int
    {
        $wishlistId = self::getWishlistId();
        $row = Database::fetch("SELECT COUNT(*) AS total FROM wishlist_items WHERE wishlist_id = ?", [$wishlistId]);
        return (int)($row['total'] ?? 0);
    }

    public static function isProductInWishlist(int $productId): bool
    {
        $wishlistId = self::getWishlistId();
        $row = Database::fetch("SELECT id FROM wishlist_items WHERE wishlist_id = ? AND product_id = ? LIMIT 1", [$wishlistId, $productId]);
        return !empty($row);
    }

    public static function toggleItem(int $productId, ?int $variantId = null): array
    {
        $wishlistId = self::getWishlistId();

        // If variantId is null, pick the first variant for this product
        if (!$variantId) {
            $firstVar = Database::fetch("SELECT id FROM product_variants WHERE product_id = ? ORDER BY id ASC LIMIT 1", [$productId]);
            $variantId = $firstVar ? (int)$firstVar['id'] : null;
        }

        $existing = Database::fetch("SELECT id FROM wishlist_items WHERE wishlist_id = ? AND product_id = ? LIMIT 1", [$wishlistId, $productId]);

        if ($existing) {
            Database::query("DELETE FROM wishlist_items WHERE id = ?", [$existing['id']]);
            $action = 'removed';
        } else {
            Database::insert("INSERT INTO wishlist_items (wishlist_id, product_id, variant_id) VALUES (?, ?, ?)", [$wishlistId, $productId, $variantId]);
            $action = 'added';
        }

        return [
            'action' => $action,
            'count' => self::getItemCount(),
        ];
    }

    public static function removeItem(int $itemId): bool
    {
        $wishlistId = self::getWishlistId();
        return Database::query("DELETE FROM wishlist_items WHERE id = ? AND wishlist_id = ?", [$itemId, $wishlistId]) > 0;
    }

    public static function moveToCart(int $itemId): bool
    {
        $wishlistId = self::getWishlistId();
        $item = Database::fetch("SELECT * FROM wishlist_items WHERE id = ? AND wishlist_id = ?", [$itemId, $wishlistId]);

        if (!$item) {
            return false;
        }

        $variantId = (int)$item['variant_id'];
        if (!$variantId) {
            $firstVar = Database::fetch("SELECT id FROM product_variants WHERE product_id = ? ORDER BY id ASC LIMIT 1", [$item['product_id']]);
            $variantId = $firstVar ? (int)$firstVar['id'] : 0;
        }

        if ($variantId > 0) {
            CartService::addItem($variantId, 1);
            Database::query("DELETE FROM wishlist_items WHERE id = ?", [$itemId]);
            return true;
        }

        return false;
    }
}
