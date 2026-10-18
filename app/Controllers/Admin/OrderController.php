<?php

namespace App\Controllers\Admin;

use App\Support\Request;
use App\Support\View;
use App\Support\Response;
use App\Support\Session;
use App\Support\Database;
use App\Services\Orders\OrderService;
use App\Services\Invoices\InvoiceService;

class OrderController
{
    public function index(Request $request): string
    {
        $status = $request->query('status');
        $search = $request->query('q');
        $payment = $request->query('payment_status');

        $where = ["1=1"];
        $params = [];

        if (!empty($status)) {
            $where[] = "o.status = ?";
            $params[] = $status;
        }

        if (!empty($payment)) {
            $where[] = "o.payment_status = ?";
            $params[] = $payment;
        }

        if (!empty($search)) {
            $where[] = "(o.order_number LIKE ? OR o.guest_phone LIKE ? OR oa.full_name LIKE ?)";
            $s = '%' . trim($search) . '%';
            $params[] = $s;
            $params[] = $s;
            $params[] = $s;
        }

        $whereClause = implode(" AND ", $where);
        $orders = Database::query(
            "SELECT o.*, oa.full_name, oa.city, oa.state
             FROM orders o
             LEFT JOIN order_addresses oa ON o.id = oa.order_id AND oa.type = 'shipping'
             WHERE {$whereClause}
             ORDER BY o.id DESC LIMIT 50",
            $params
        );

        return View::render('admin.orders.index', [
            'title' => 'Orders Management | Welkar Admin',
            'orders' => $orders,
            'currentStatus' => $status,
            'search' => $search,
        ], 'layouts.admin');
    }

    public function show(Request $request, string $id): string
    {
        $order = Database::fetch("SELECT * FROM orders WHERE id = ?", [(int)$id]);
        if (!$order) {
            http_response_code(404);
            return View::render('errors.404', ['title' => 'Order Not Found'], 'layouts.admin');
        }

        $items = Database::query("SELECT * FROM order_items WHERE order_id = ?", [$order['id']]);
        $shippingAddress = Database::fetch("SELECT * FROM order_addresses WHERE order_id = ? AND type = 'shipping'", [$order['id']]);
        $history = Database::query("SELECT * FROM order_status_history WHERE order_id = ? ORDER BY id DESC", [$order['id']]);
        $shipments = Database::query("SELECT * FROM shipping_shipments WHERE order_id = ? ORDER BY id DESC", [$order['id']]);
        $invoice = Database::fetch("SELECT * FROM invoices WHERE order_id = ?", [$order['id']]);
        $returns = Database::query("SELECT * FROM returns WHERE order_id = ? ORDER BY id DESC", [$order['id']]);
        $refunds = Database::query("SELECT * FROM refunds WHERE order_id = ? ORDER BY id DESC", [$order['id']]);
        $payments = Database::fetchAll("SELECT * FROM payments WHERE order_id = ? ORDER BY id DESC", [$order['id']]);

        return View::render('admin.orders.show', [
            'title' => 'Order ' . $order['order_number'] . ' | Welkar Admin',
            'order' => $order,
            'items' => $items,
            'address' => $shippingAddress,
            'history' => $history,
            'shipments' => $shipments,
            'invoice' => $invoice,
            'returns' => $returns,
            'refunds' => $refunds,
            'payments' => $payments,
        ], 'layouts.admin');
    }

    public function recordRefund(Request $request, string $id): void
    {
        $orderId = (int)$id;
        $order = Database::fetch("SELECT * FROM orders WHERE id = ?", [$orderId]);
        if (!$order) {
            Session::flash('error', 'Order not found.');
            Response::redirect('/admin/orders');
        }

        $amount = (float)$request->input('amount', $order['total_amount']);
        $reason = trim((string)$request->input('reason', 'Customer requested refund'));
        $gatewayRefundId = trim((string)$request->input('gateway_refund_id', 'ref_' . bin2hex(random_bytes(6))));
        $restock = (bool)$request->input('restock_inventory', false);
        $admin = admin_user();

        if ($amount <= 0 || $amount > (float)$order['total_amount']) {
            Session::flash('error', 'Refund amount cannot exceed the order total (₹' . number_format((float)$order['total_amount'], 2) . ').');
            Response::redirect('/admin/orders/' . $id);
        }

        Database::transaction(function () use ($orderId, $amount, $reason, $gatewayRefundId, $restock, $admin, $order) {
            Database::execute(
                "INSERT INTO refunds (order_id, payment_id, amount, reason, gateway_refund_id, status, processed_by, created_at)
                 VALUES (?, NULL, ?, ?, ?, 'processed', ?, NOW())",
                [$orderId, $amount, $reason, $gatewayRefundId, $admin['username'] ?? 'admin']
            );

            Database::execute(
                "UPDATE orders SET payment_status = 'refunded', status = 'cancelled', updated_at = NOW() WHERE id = ?",
                [$orderId]
            );

            Database::execute(
                "INSERT INTO order_status_history (order_id, previous_status, new_status, comment, created_by, created_at)
                 VALUES (?, ?, 'cancelled', ?, ?, NOW())",
                [$orderId, $order['status'], "Refunded ₹{$amount}. Reason: {$reason}", $admin['username'] ?? 'admin']
            );

            if ($restock) {
                \App\Services\Inventory\InventoryService::restockOrder($orderId, $admin['username'] ?? 'admin');
            }
        });

        // Audit log
        Database::execute(
            "INSERT INTO audit_logs (user_type, user_id, action, entity_type, entity_id, new_values, ip_address, created_at)
             VALUES ('admin', ?, 'record_refund', 'order', ?, ?, ?, NOW())",
            [$admin['id'] ?? null, $orderId, json_encode(['amount' => $amount, 'reason' => $reason]), $request->ip()]
        );

        Session::flash('success', "Refund of ₹" . number_format($amount, 2) . " processed successfully.");
        Response::redirect('/admin/orders/' . $id);
    }

    public function processReturn(Request $request, string $id): void
    {
        $orderId = (int)$id;
        $order = Database::fetch("SELECT * FROM orders WHERE id = ?", [$orderId]);
        if (!$order) {
            Session::flash('error', 'Order not found.');
            Response::redirect('/admin/orders');
        }

        $status = (string)$request->input('status', 'approved');
        $reason = (string)$request->input('reason', 'Quality check return');
        $comments = (string)$request->input('comments');
        $restock = (bool)$request->input('restock_inventory', false);
        $admin = admin_user();

        Database::transaction(function () use ($orderId, $status, $reason, $comments, $restock, $admin, $order) {
            Database::execute(
                "INSERT INTO returns (order_id, user_id, status, reason, comments, is_restocked, restocked_at, created_at, updated_at)
                 VALUES (?, ?, ?, ?, ?, ?, IF(?=1, NOW(), NULL), NOW(), NOW())",
                [$orderId, $order['user_id'] ?? null, $status, $reason, $comments, $restock ? 1 : 0, $restock ? 1 : 0]
            );

            if ($restock) {
                \App\Services\Inventory\InventoryService::restockOrder($orderId, $admin['username'] ?? 'admin');
            }
        });

        Session::flash('success', "Return record created with status: " . ucfirst($status) . ".");
        Response::redirect('/admin/orders/' . $id);
    }

    public function updateStatus(Request $request, string $id): void
    {
        $newStatus = (string)$request->input('status');
        $comment = (string)$request->input('comment', 'Status updated by admin');
        $admin = admin_user();

        OrderService::updateStatus((int)$id, $newStatus, $comment, $admin['username'] ?? 'admin');
        Session::flash('success', 'Order status updated to ' . ucfirst($newStatus) . '.');
        Response::redirect('/admin/orders/' . $id);
    }

    public function addTracking(Request $request, string $id): void
    {
        $courier = (string)$request->input('courier_name', 'Delhivery');
        $trackingNum = trim((string)$request->input('tracking_number'));
        $trackingUrl = trim((string)$request->input('tracking_url'));

        if (!empty($trackingNum)) {
            OrderService::addTracking((int)$id, $courier, $trackingNum, $trackingUrl);
            Session::flash('success', 'Shipment tracking information attached and marked as Shipped.');
        }

        Response::redirect('/admin/orders/' . $id);
    }

    public function invoice(Request $request, string $id): void
    {
        $invoice = Database::fetch("SELECT * FROM invoices WHERE order_id = ?", [(int)$id]);
        if (!$invoice) {
            $invoice = InvoiceService::generateForOrder((int)$id);
        }

        echo InvoiceService::getInvoiceHtml($invoice['id']);
        exit;
    }

    public function updatePayment(Request $request, string $id): void
    {
        $orderId = (int)$id;
        $order = Database::fetch("SELECT * FROM orders WHERE id = ?", [$orderId]);
        if (!$order) {
            Session::flash('error', 'Order not found.');
            Response::redirect('/admin/orders');
            return;
        }

        $paymentStatus = (string)$request->input('payment_status');
        $transactionId = trim((string)$request->input('transaction_id'));
        $notes = trim((string)$request->input('payment_notes', 'Payment status updated by admin'));
        $admin = admin_user();

        $valid = ['unpaid', 'authorized', 'captured', 'refunded', 'failed'];
        if (!in_array($paymentStatus, $valid)) {
            Session::flash('error', 'Invalid payment status selected.');
            Response::redirect('/admin/orders/' . $id);
            return;
        }

        Database::transaction(function () use ($orderId, $order, $paymentStatus, $transactionId, $notes, $admin) {
            Database::execute(
                "UPDATE orders SET payment_status = ?, updated_at = NOW() WHERE id = ?",
                [$paymentStatus, $orderId]
            );

            // Update or create payment record
            $payment = Database::fetch("SELECT id FROM payments WHERE order_id = ? ORDER BY id DESC LIMIT 1", [$orderId]);
            if ($payment) {
                $statusMap = ($paymentStatus === 'unpaid') ? 'pending' : $paymentStatus;
                Database::execute(
                    "UPDATE payments SET status = ?, transaction_id = IF(? != '', ?, transaction_id), updated_at = NOW() WHERE id = ?",
                    [$statusMap, $transactionId, $transactionId, $payment['id']]
                );
            } else {
                $statusMap = ($paymentStatus === 'unpaid') ? 'pending' : $paymentStatus;
                Database::execute(
                    "INSERT INTO payments (order_id, gateway, gateway_order_id, amount, currency, status, payment_method, transaction_id, created_at, updated_at)
                     VALUES (?, ?, ?, ?, 'INR', ?, ?, ?, NOW(), NOW())",
                    [$orderId, $order['payment_method'], 'manual_' . time(), $order['total_amount'], $statusMap, $order['payment_method'], $transactionId ?: null]
                );
            }

            // If captured and order status was pending, confirm order
            if ($paymentStatus === 'captured' && $order['status'] === 'pending') {
                Database::execute("UPDATE orders SET status = 'confirmed', updated_at = NOW() WHERE id = ?", [$orderId]);
            }

            // History log
            Database::execute(
                "INSERT INTO order_status_history (order_id, old_status, new_status, comment, changed_by)
                 VALUES (?, ?, ?, ?, ?)",
                [$orderId, $order['status'], $order['status'], "Payment status updated to '{$paymentStatus}'. Note: {$notes}", $admin['username'] ?? 'admin']
            );
        });

        Session::flash('success', 'Payment status updated to ' . ucfirst($paymentStatus) . '.');
        Response::redirect('/admin/orders/' . $id);
    }
}

