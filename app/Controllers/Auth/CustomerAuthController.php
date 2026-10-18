<?php

namespace App\Controllers\Auth;

use App\Support\Request;
use App\Support\View;
use App\Support\Response;
use App\Support\Session;
use App\Support\Database;
use App\Services\Auth\CustomerAuthService;
use App\Services\Orders\OrderService;
use App\Services\Invoices\InvoiceService;

class CustomerAuthController
{
    public function showLogin(Request $request): string
    {
        if (auth_user()) {
            Response::redirect('/account');
        }

        return View::render('auth.customer_login', [
            'title' => 'Customer Login | Welkar',
        ], 'layouts.storefront');
    }

    public function sendOtp(Request $request): void
    {
        $phone = (string)$request->input('phone');
        $result = CustomerAuthService::sendOtp($phone);

        if ($request->isAjax()) {
            Response::json($result);
        }

        Session::flash($result['success'] ? 'success' : 'error', $result['message']);
        Response::redirect('/account/login');
    }

    public function verifyOtp(Request $request): void
    {
        $phone = (string)$request->input('phone');
        $otp = (string)$request->input('otp');

        $result = CustomerAuthService::verifyOtp($phone, $otp);

        if ($request->isAjax()) {
            Response::json($result);
        }

        if ($result['success']) {
            Session::flash('success', 'Welcome to Welkar!');
            $intended = Session::get('auth_intended', '/account');
            Session::remove('auth_intended');
            Response::redirect($intended);
        }

        Session::flash('error', $result['message']);
        Response::redirect('/account/login');
    }

    public function emailAuth(Request $request): void
    {
        $email = (string)$request->input('email');
        $password = (string)$request->input('password');

        $result = CustomerAuthService::loginOrRegisterWithEmail($email, $password);

        if ($request->isAjax()) {
            Response::json($result);
        }

        if ($result['success']) {
            Session::flash('success', $result['message']);
            $intended = Session::get('auth_intended', '/account');
            Session::remove('auth_intended');
            Response::redirect($intended);
        }

        Session::flash('error', $result['message']);
        Response::redirect('/account/login');
    }

    public function dashboard(Request $request): string
    {
        $user = auth_user();
        if (!$user) {
            Response::redirect('/account/login');
        }

        $orders = Database::query(
            "SELECT * FROM orders WHERE user_id = ? OR guest_phone = ? ORDER BY id DESC",
            [$user['id'], $user['phone']]
        );

        $addresses = Database::query("SELECT * FROM customer_addresses WHERE user_id = ?", [$user['id']]);

        return View::render('storefront.account_dashboard', [
            'title' => 'My Account & Orders | Welkar',
            'user' => $user,
            'orders' => $orders,
            'addresses' => $addresses,
        ], 'layouts.storefront');
    }

    public function cancelOrder(Request $request): void
    {
        $user = auth_user();
        $orderId = (int)$request->input('order_id');
        $reason = trim((string)$request->input('reason', 'Customer requested cancellation'));

        // IDOR check: verify order belongs to this customer
        $order = Database::fetch(
            "SELECT * FROM orders WHERE id = ? AND (user_id = ? OR guest_phone = ?)",
            [$orderId, $user['id'], $user['phone']]
        );

        if (!$order) {
            Session::flash('error', 'Unauthorized order cancellation request.');
            Response::redirect('/account');
        }

        $res = OrderService::cancelOrder($orderId, $reason, 'customer');
        Session::flash($res['success'] ? 'success' : 'error', $res['message']);
        Response::redirect('/account');
    }

    public function downloadInvoice(Request $request, string $orderNumber): void
    {
        $user = auth_user();
        $order = Database::fetch(
            "SELECT * FROM orders WHERE order_number = ? AND (user_id = ? OR guest_phone = ?)",
            [$orderNumber, $user['id'], $user['phone']]
        );

        if (!$order) {
            http_response_code(403);
            echo "Unauthorized access to invoice.";
            exit;
        }

        $invoice = Database::fetch("SELECT * FROM invoices WHERE order_id = ?", [$order['id']]);
        if (!$invoice) {
            $invoice = InvoiceService::generateForOrder($order['id']);
        }

        echo InvoiceService::getInvoiceHtml($invoice['id']);
        exit;
    }

    public function logout(Request $request): void
    {
        CustomerAuthService::logout();
        Response::redirect('/');
    }
}
