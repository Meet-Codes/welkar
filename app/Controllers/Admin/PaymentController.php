<?php

namespace App\Controllers\Admin;

use App\Support\Request;
use App\Support\View;
use App\Support\Response;
use App\Support\Session;
use App\Support\Database;

class PaymentController
{
    public function index(Request $request): string
    {
        $status = $request->query('status');
        $method = $request->query('method');
        $gateway = $request->query('gateway');
        $search = $request->query('q');

        $where = ["1=1"];
        $params = [];

        if (!empty($status)) {
            $where[] = "p.status = ?";
            $params[] = $status;
        }

        if (!empty($method)) {
            $where[] = "p.payment_method = ?";
            $params[] = $method;
        }

        if (!empty($gateway)) {
            $where[] = "p.gateway = ?";
            $params[] = $gateway;
        }

        if (!empty($search)) {
            $where[] = "(o.order_number LIKE ? OR p.transaction_id LIKE ? OR p.gateway_order_id LIKE ? OR oa.full_name LIKE ? OR oa.phone LIKE ?)";
            $s = '%' . trim($search) . '%';
            $params[] = $s;
            $params[] = $s;
            $params[] = $s;
            $params[] = $s;
            $params[] = $s;
        }

        $whereClause = implode(" AND ", $where);
        $payments = Database::fetchAll(
            "SELECT p.*, o.order_number, o.status as order_status, oa.full_name, oa.phone, oa.city, oa.state
             FROM payments p
             JOIN orders o ON p.order_id = o.id
             LEFT JOIN order_addresses oa ON o.id = oa.order_id AND oa.type = 'shipping'
             WHERE {$whereClause}
             ORDER BY p.id DESC
             LIMIT 100",
            $params
        );

        // KPI aggregates
        $kpis = [
            'total_volume' => (float)(Database::fetch("SELECT SUM(amount) as val FROM payments WHERE status = 'captured'")['val'] ?? 0),
            'captured_count' => (int)(Database::fetch("SELECT COUNT(*) as total FROM payments WHERE status = 'captured'")['total'] ?? 0),
            'pending_count' => (int)(Database::fetch("SELECT COUNT(*) as total FROM payments WHERE status IN ('pending', 'authorized')")['total'] ?? 0),
            'refunded_volume' => (float)(Database::fetch("SELECT SUM(amount) as val FROM payments WHERE status = 'refunded'")['val'] ?? 0),
        ];

        return View::render('admin.payments.index', [
            'title' => 'Payments & Transactions Management | Welkar Admin',
            'payments' => $payments,
            'kpis' => $kpis,
            'currentStatus' => $status,
            'currentMethod' => $method,
            'currentGateway' => $gateway,
            'search' => $search,
        ], 'layouts.admin');
    }

    public function show(Request $request, string $id): string
    {
        $payment = Database::fetch(
            "SELECT p.*, o.order_number, o.status as order_status, o.total_amount as order_total,
                    oa.full_name, oa.phone, oa.email, oa.city, oa.state, oa.address_line1, oa.pincode
             FROM payments p
             JOIN orders o ON p.order_id = o.id
             LEFT JOIN order_addresses oa ON o.id = oa.order_id AND oa.type = 'shipping'
             WHERE p.id = ?",
            [(int)$id]
        );

        if (!$payment) {
            http_response_code(404);
            return View::render('errors.404', ['title' => 'Payment Transaction Not Found'], 'layouts.admin');
        }

        $events = Database::fetchAll("SELECT * FROM payment_events WHERE payment_id = ? ORDER BY id DESC", [$payment['id']]);

        return View::render('admin.payments.show', [
            'title' => 'Payment #' . $payment['id'] . ' (' . $payment['order_number'] . ') | Welkar Admin',
            'payment' => $payment,
            'events' => $events,
        ], 'layouts.admin');
    }

    public function updateStatus(Request $request, string $id): void
    {
        $paymentId = (int)$id;
        $payment = Database::fetch("SELECT * FROM payments WHERE id = ?", [$paymentId]);

        if (!$payment) {
            Session::flash('error', 'Payment transaction not found.');
            Response::redirect('/admin/payments');
            return;
        }

        $newStatus = (string)$request->input('status');
        $transactionId = trim((string)$request->input('transaction_id', $payment['transaction_id'] ?? ''));
        $notes = trim((string)$request->input('notes', 'Payment status updated by admin'));
        $admin = admin_user();

        $validStatuses = ['pending', 'authorized', 'captured', 'failed', 'refunded'];
        if (!in_array($newStatus, $validStatuses)) {
            Session::flash('error', 'Invalid payment status.');
            Response::redirect('/admin/payments/' . $id);
            return;
        }

        Database::transaction(function () use ($paymentId, $payment, $newStatus, $transactionId, $notes, $admin) {
            Database::execute(
                "UPDATE payments SET status = ?, transaction_id = ?, updated_at = NOW() WHERE id = ?",
                [$newStatus, $transactionId ?: null, $paymentId]
            );

            // Sync order payment status
            $orderPaymentStatus = ($newStatus === 'captured') ? 'captured' : (($newStatus === 'refunded') ? 'refunded' : (($newStatus === 'failed') ? 'failed' : 'pending'));
            Database::execute(
                "UPDATE orders SET payment_status = ?, updated_at = NOW() WHERE id = ?",
                [$orderPaymentStatus, $payment['order_id']]
            );

            // If captured and order was pending, confirm order
            if ($newStatus === 'captured') {
                Database::execute(
                    "UPDATE orders SET status = 'confirmed', updated_at = NOW() WHERE id = ? AND status = 'pending'",
                    [$payment['order_id']]
                );
            }

            // Log event
            Database::execute(
                "INSERT INTO payment_events (payment_id, event_name, payload)
                 VALUES (?, 'admin.status_update', ?)",
                [$paymentId, json_encode(['from' => $payment['status'], 'to' => $newStatus, 'admin' => $admin['username'] ?? 'admin', 'notes' => $notes])]
            );

            // Audit log
            Database::execute(
                "INSERT INTO audit_logs (user_type, user_id, action, entity_type, entity_id, new_values, ip_address, created_at)
                 VALUES ('admin', ?, 'update_payment_status', 'payment', ?, ?, ?, NOW())",
                [$admin['id'] ?? null, $paymentId, json_encode(['status' => $newStatus, 'notes' => $notes]), $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1']
            );
        });

        Session::flash('success', 'Payment status successfully updated to ' . ucfirst($newStatus) . '.');
        Response::redirect('/admin/payments/' . $id);
    }
}
