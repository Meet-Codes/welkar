<?php

namespace App\Controllers\Admin;

use App\Support\Request;
use App\Support\View;
use App\Support\Database;
use App\Services\Analytics\AnalyticsService;

class AnalyticsController
{
    public function index(Request $request): string
    {
        $kpis = AnalyticsService::getDashboardKpis('30d');

        // Sales by Dial Type
        $salesByDial = Database::query(
            "SELECT p.dial_type, COUNT(oi.id) AS items_sold, SUM(oi.total) AS total_revenue
             FROM order_items oi
             JOIN products p ON oi.product_id = p.id
             JOIN orders o ON oi.order_id = o.id
             WHERE o.status != 'cancelled'
             GROUP BY p.dial_type
             ORDER BY total_revenue DESC"
        );

        // Sales by Brand
        $salesByBrand = Database::query(
            "SELECT b.name AS brand_name, COUNT(oi.id) AS items_sold, SUM(oi.total) AS total_revenue
             FROM order_items oi
             JOIN products p ON oi.product_id = p.id
             JOIN brands b ON p.brand_id = b.id
             JOIN orders o ON oi.order_id = o.id
             WHERE o.status != 'cancelled'
             GROUP BY b.id
             ORDER BY total_revenue DESC"
        );

        // UTM Attribution Data
        $utmPerformance = Database::query(
            "SELECT ae.utm_source, ae.utm_campaign,
                    COUNT(DISTINCT ae.session_id) AS visitors,
                    COUNT(DISTINCT o.id) AS attributed_orders,
                    COALESCE(SUM(o.total_amount), 0) AS attributed_revenue
             FROM analytics_events ae
             LEFT JOIN orders o ON ae.user_id = o.user_id AND o.status != 'cancelled'
             WHERE ae.utm_source IS NOT NULL AND ae.utm_source != ''
             GROUP BY ae.utm_source, ae.utm_campaign
             ORDER BY attributed_revenue DESC LIMIT 10"
        );

        return View::render('admin.analytics.index', [
            'title' => 'Website Analytics & Advertising Intelligence | Welkar Admin',
            'kpis' => $kpis,
            'salesByDial' => $salesByDial,
            'salesByBrand' => $salesByBrand,
            'utmPerformance' => $utmPerformance,
        ], 'layouts.admin');
    }
}
