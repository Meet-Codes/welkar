<?php

namespace App\Services\Analytics;

use App\Support\Database;
use App\Support\Request;
use App\Support\Session;

class AnalyticsService
{
    public static function logEvent(string $eventName, Request $request, array $data = []): void
    {
        $sessionId = Session::get('cart_session_id', 'sess_' . md5($request->ip() . $request->userAgent()));
        $user = Session::get('customer_user');
        $userId = $user['id'] ?? null;

        $utmSource = $request->query('utm_source');
        $utmMedium = $request->query('utm_medium');
        $utmCampaign = $request->query('utm_campaign');
        $utmContent = $request->query('utm_content');
        $utmTerm = $request->query('utm_term');

        // Store UTM in session if present
        if ($utmSource) {
            Session::set('utm_data', [
                'utm_source' => $utmSource,
                'utm_medium' => $utmMedium,
                'utm_campaign' => $utmCampaign,
                'utm_content' => $utmContent,
                'utm_term' => $utmTerm,
            ]);

            Database::execute(
                "INSERT INTO utm_sessions (session_id, utm_source, utm_medium, utm_campaign, utm_content, utm_term)
                 VALUES (?, ?, ?, ?, ?, ?)",
                [$sessionId, $utmSource, $utmMedium, $utmCampaign, $utmContent, $utmTerm]
            );
        }

        $sessionUtm = Session::get('utm_data', []);

        Database::execute(
            "INSERT INTO analytics_events (
                event_name, session_id, user_id, page_url, referrer,
                ip_address, user_agent, utm_source, utm_medium, utm_campaign,
                utm_content, utm_term, event_data
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $eventName, $sessionId, $userId, $request->uri(),
                $request->header('Referer'), $request->ip(), substr($request->userAgent(), 0, 250),
                $utmSource ?? ($sessionUtm['utm_source'] ?? null),
                $utmMedium ?? ($sessionUtm['utm_medium'] ?? null),
                $utmCampaign ?? ($sessionUtm['utm_campaign'] ?? null),
                $utmContent ?? ($sessionUtm['utm_content'] ?? null),
                $utmTerm ?? ($sessionUtm['utm_term'] ?? null),
                empty($data) ? null : json_encode($data)
            ]
        );

        // Forward to Meta Conversions API (CAPI) using official Facebook Business SDK
        try {
            if (\App\Services\Marketing\MetaConversionsService::isConfigured()) {
                $userContext = [
                    'email' => $user['email'] ?? null,
                    'phone' => $user['phone'] ?? null,
                    'first_name' => isset($user['full_name']) ? explode(' ', $user['full_name'])[0] : null,
                ];

                match ($eventName) {
                    'view_item' => \App\Services\Marketing\MetaConversionsService::trackViewContent($data, $request),
                    'add_to_cart' => \App\Services\Marketing\MetaConversionsService::trackAddToCart($data, $request),
                    'page_view' => \App\Services\Marketing\MetaConversionsService::sendEvent(
                        'PageView',
                        \App\Services\Marketing\MetaConversionsService::buildUserData($userContext, $request),
                        null,
                        $request->fullUrl()
                    ),
                    default => null,
                };
            }
        } catch (\Throwable $e) {
            error_log("Meta CAPI Dispatch Error in AnalyticsService: " . $e->getMessage());
        }
    }

    public static function getDashboardKpis(string $dateRange = '30d'): array
    {
        $days = match ($dateRange) {
            'today' => 0,
            '7d' => 7,
            'this_month' => 30,
            default => 30,
        };

        $dateCondition = function(string $table = '') use ($days) {
            $col = $table ? "{$table}.created_at" : "created_at";
            return ($days === 0) ? "DATE({$col}) = CURDATE()" : "{$col} >= DATE_SUB(NOW(), INTERVAL {$days} DAY)";
        };

        $baseWhere = $dateCondition();

        // Orders summary
        $orderStats = Database::fetch(
            "SELECT COUNT(id) AS total_orders,
                    COALESCE(SUM(total_amount), 0) AS gross_sales,
                    COALESCE(SUM(subtotal), 0) AS subtotal_sales,
                    COALESCE(SUM(discount_amount), 0) AS total_discounts,
                    COALESCE(SUM(shipping_fee), 0) AS total_shipping,
                    COALESCE(SUM(tax_amount), 0) AS total_gst,
                    COALESCE(AVG(total_amount), 0) AS aov
             FROM orders
             WHERE status != 'cancelled' AND {$baseWhere}"
        );

        // Cancelled orders
        $cancelledStats = Database::fetch(
            "SELECT COUNT(id) AS cancelled_orders, COALESCE(SUM(total_amount), 0) AS cancelled_value
             FROM orders WHERE status = 'cancelled' AND {$baseWhere}"
        );

        // First-party Traffic Stats
        $trafficStats = Database::fetch(
            "SELECT COUNT(DISTINCT session_id) AS total_sessions,
                    COUNT(id) AS total_pageviews
             FROM analytics_events
             WHERE event_name = 'page_view' AND {$baseWhere}"
        );

        // Add to cart events
        $cartEvents = Database::fetch(
            "SELECT COUNT(id) AS add_to_cart_count
             FROM analytics_events
             WHERE event_name = 'add_to_cart' AND {$baseWhere}"
        );

        // Top Selling Watches
        $orderWhere = $dateCondition('o');
        $topProducts = Database::query(
            "SELECT oi.product_name, oi.sku, SUM(oi.quantity) AS units_sold, SUM(oi.total) AS total_revenue
             FROM order_items oi
             JOIN orders o ON oi.order_id = o.id
             WHERE o.status != 'cancelled' AND {$orderWhere}
             GROUP BY oi.product_id, oi.product_name, oi.sku
             ORDER BY units_sold DESC
             LIMIT 5"
        );

        // Recent Orders
        $recentOrders = Database::query(
            "SELECT o.*, oa.full_name AS customer_name, oa.city, oa.state
             FROM orders o
             LEFT JOIN order_addresses oa ON o.id = oa.order_id AND oa.type = 'shipping'
             ORDER BY o.id DESC
             LIMIT 8"
        );

        // Inventory Alerts (Low stock or out of stock)
        $lowStock = Database::query(
            "SELECT p.name AS product_name, pv.variant_name, pv.sku, s.quantity, s.low_stock_threshold
             FROM inventory_stock s
             JOIN product_variants pv ON s.variant_id = pv.id
             JOIN products p ON pv.product_id = p.id
             WHERE s.quantity <= s.low_stock_threshold
             ORDER BY s.quantity ASC
             LIMIT 6"
        );

        return [
            'total_orders' => (int)($orderStats['total_orders'] ?? 0),
            'gross_sales' => (float)($orderStats['gross_sales'] ?? 0),
            'net_sales' => (float)(($orderStats['gross_sales'] ?? 0) - ($cancelledStats['cancelled_value'] ?? 0)),
            'total_discounts' => (float)($orderStats['total_discounts'] ?? 0),
            'total_gst' => (float)($orderStats['total_gst'] ?? 0),
            'total_shipping' => (float)($orderStats['total_shipping'] ?? 0),
            'aov' => (float)($orderStats['aov'] ?? 0),
            'cancelled_orders' => (int)($cancelledStats['cancelled_orders'] ?? 0),
            'cancelled_value' => (float)($cancelledStats['cancelled_value'] ?? 0),
            'sessions' => (int)($trafficStats['total_sessions'] ?? 0),
            'pageviews' => (int)($trafficStats['total_pageviews'] ?? 0),
            'add_to_cart_count' => (int)($cartEvents['add_to_cart_count'] ?? 0),
            'top_products' => $topProducts,
            'recent_orders' => $recentOrders,
            'low_stock_alerts' => $lowStock,
        ];
    }
}
