<?php

namespace App\Controllers\Admin;

use App\Support\Request;
use App\Support\View;
use App\Support\Database;

class CustomerController
{
    public function index(Request $request): string
    {
        $search = $request->query('q');
        $where = "1=1";
        $params = [];

        if (!empty($search)) {
            $where = "(u.full_name LIKE ? OR u.phone LIKE ? OR u.email LIKE ?)";
            $s = '%' . trim($search) . '%';
            $params = [$s, $s, $s];
        }

        $customers = Database::query(
            "SELECT u.*,
                    COUNT(DISTINCT o.id) AS total_orders,
                    COALESCE(SUM(o.total_amount), 0) AS lifetime_spend,
                    MAX(o.created_at) AS last_order_date
             FROM users u
             LEFT JOIN orders o ON u.id = o.user_id AND o.status != 'cancelled'
             WHERE {$where}
             GROUP BY u.id
             ORDER BY lifetime_spend DESC LIMIT 50",
            $params
        );

        return View::render('admin.customers.index', [
            'title' => 'Customer CRM & Profiles | Welkar Admin',
            'customers' => $customers,
            'search' => $search,
        ], 'layouts.admin');
    }
}
