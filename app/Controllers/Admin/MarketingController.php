<?php

namespace App\Controllers\Admin;

use App\Support\Request;
use App\Support\View;
use App\Support\Response;
use App\Support\Session;
use App\Support\Database;

class MarketingController
{
    public function index(Request $request): string
    {
        $coupons = Database::query("SELECT * FROM coupons ORDER BY id DESC");
        $banners = Database::query("SELECT * FROM banners ORDER BY display_order ASC");
        $oneHourAgo = date('Y-m-d H:i:s', strtotime('-1 hour'));
        $abandonedCarts = Database::query(
            "SELECT c.id, c.user_id, c.session_id, c.created_at, c.updated_at,
                    COUNT(ci.id) AS items_count, SUM(ci.quantity * ci.unit_price) AS cart_value,
                    u.phone AS customer_phone, u.full_name AS customer_name
             FROM carts c
             JOIN cart_items ci ON c.id = ci.cart_id
             LEFT JOIN users u ON c.user_id = u.id
             WHERE c.updated_at < ?
             GROUP BY c.id, c.user_id, c.session_id, c.created_at, c.updated_at, u.phone, u.full_name
             ORDER BY c.updated_at DESC LIMIT 20",
            [$oneHourAgo]
        );

        return View::render('admin.marketing.index', [
            'title' => 'Promotions, Coupons & Banners | Welkar Admin',
            'coupons' => $coupons,
            'banners' => $banners,
            'abandonedCarts' => $abandonedCarts,
        ], 'layouts.admin');
    }

    public function storeCoupon(Request $request): void
    {
        $code = strtoupper(trim((string)$request->input('code')));
        $discountType = (string)$request->input('discount_type', 'percentage');
        $discountVal = (float)$request->input('discount_value');
        $minSpend = (float)$request->input('min_order_value', 0);
        $maxDiscount = !empty($request->input('max_discount_value')) ? (float)$request->input('max_discount_value') : null;
        $desc = trim((string)$request->input('description'));

        Database::execute(
            "INSERT INTO coupons (code, description, discount_type, discount_value, min_order_value, max_discount_value, is_active)
             VALUES (?, ?, ?, ?, ?, ?, 1)",
            [$code, $desc, $discountType, $discountVal, $minSpend, $maxDiscount]
        );

        Session::flash('success', "Coupon '{$code}' created successfully.");
        Response::redirect('/admin/marketing');
    }

    public function generateRecovery(Request $request, string $cartId): void
    {
        $id = (int)$cartId;
        $cart = Database::fetch("SELECT * FROM carts WHERE id = ?", [$id]);
        if (!$cart) {
            Session::flash('error', 'Cart not found.');
            Response::redirect('/admin/marketing');
        }

        $token = bin2hex(random_bytes(16));
        $phone = trim((string)$request->input('phone', ''));
        $email = trim((string)$request->input('email', ''));

        Database::execute(
            "INSERT INTO abandoned_cart_reminders (cart_id, user_id, email, phone, reminder_step, token, status, sent_at, created_at)
             VALUES (?, ?, ?, ?, 1, ?, 'sent', NOW(), NOW())",
            [$id, $cart['user_id'] ?? null, $email, $phone, $token]
        );

        $recoveryLink = '/cart?recover=' . $token;
        Session::flash('success', "Recovery token generated successfully: {$recoveryLink}");
        Response::redirect('/admin/marketing');
    }
}
