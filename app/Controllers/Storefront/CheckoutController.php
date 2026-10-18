<?php

namespace App\Controllers\Storefront;

use App\Support\Request;
use App\Support\View;
use App\Support\Response;
use App\Support\Session;
use App\Services\Cart\CartService;
use App\Services\Orders\OrderService;
use App\Services\Payments\RazorpayAdapter;
use App\Services\Analytics\AnalyticsService;

class CheckoutController
{
    private static array $indianStates = [
        'Maharashtra' => '27', 'Delhi' => '07', 'Karnataka' => '29', 'Gujarat' => '24',
        'Tamil Nadu' => '33', 'Uttar Pradesh' => '09', 'West Bengal' => '19', 'Telangana' => '36',
        'Rajasthan' => '08', 'Kerala' => '32', 'Madhya Pradesh' => '23', 'Haryana' => '06',
        'Punjab' => '03', 'Bihar' => '10', 'Andhra Pradesh' => '37', 'Odisha' => '21',
        'Assam' => '18', 'Goa' => '30', 'Uttarakhand' => '05', 'Jharkhand' => '20',
        'Chhattisgarh' => '22', 'Himachal Pradesh' => '02', 'Jammu and Kashmir' => '01',
        'Chandigarh' => '04', 'Puducherry' => '34'
    ];

    public function index(Request $request): string
    {
        $cart = CartService::getCart();
        if (empty($cart['items'])) {
            Session::flash('error', 'Your shopping bag is empty.');
            Response::redirect('/cart');
        }

        AnalyticsService::logEvent('begin_checkout', $request, [
            'value' => $cart['summary']['total_amount'],
            'items_count' => $cart['summary']['total_items']
        ]);

        $selectedState = Session::get('checkout_delivery_state', 'Maharashtra');

        return View::render('storefront.checkout', [
            'title' => 'Secure Checkout | Welkar',
            'cart' => $cart,
            'indianStates' => self::$indianStates,
            'selectedState' => $selectedState,
            'authUser' => auth_user(),
        ], 'layouts.storefront');
    }

    public function process(Request $request): void
    {
        $cart = CartService::getCart();
        if (empty($cart['items'])) {
            Response::redirect('/cart');
        }

        // Validation
        $fullName = trim((string)$request->input('full_name'));
        $phone = preg_replace('/[^0-9]/', '', (string)$request->input('phone'));
        $email = trim((string)$request->input('email'));
        $address1 = trim((string)$request->input('address_line1'));
        $address2 = trim((string)$request->input('address_line2'));
        $city = trim((string)$request->input('city'));
        $state = trim((string)$request->input('state', 'Maharashtra'));
        $pincode = preg_replace('/[^0-9]/', '', (string)$request->input('pincode'));
        $paymentMethod = $request->input('payment_method', 'cod');

        if (empty($fullName) || strlen($phone) < 10 || empty($address1) || empty($city) || strlen($pincode) !== 6) {
            Session::flash('error', 'Please fill in all mandatory delivery address fields accurately.');
            Response::redirect('/checkout');
        }

        $stateCode = self::$indianStates[$state] ?? '27';
        Session::set('checkout_delivery_state', $state);

        try {
            $user = auth_user();
            $order = OrderService::createOrder([
                'user_id' => $user['id'] ?? null,
                'full_name' => $fullName,
                'phone' => $phone,
                'email' => $email,
                'address_line1' => $address1,
                'address_line2' => $address2,
                'city' => $city,
                'state' => $state,
                'state_code' => $stateCode,
                'pincode' => $pincode,
                'payment_method' => $paymentMethod,
                'notes' => $request->input('notes'),
            ]);

            // Track purchase event
            AnalyticsService::logEvent('purchase', $request, [
                'order_number' => $order['order_number'],
                'revenue' => $order['total_amount']
            ]);

            // Forward to Meta Conversions API (CAPI) using official Facebook Business SDK
            try {
                $orderItems = \App\Support\Database::query("SELECT * FROM order_items WHERE order_id = ?", [$order['id']]);
                \App\Services\Marketing\MetaConversionsService::trackPurchase($order, $orderItems, [
                    'email' => $email,
                    'phone' => $phone,
                    'first_name' => explode(' ', $fullName)[0],
                    'last_name' => explode(' ', $fullName)[1] ?? '',
                    'city' => $city,
                    'state' => $state,
                    'pincode' => $pincode,
                ], $request);
            } catch (\Throwable $e) {
                error_log("Meta CAPI Purchase tracking error: " . $e->getMessage());
            }

            if ($paymentMethod !== 'cod') {
                $razorpayData = RazorpayAdapter::createOrder($order['id'], (float)$order['total_amount'], $paymentMethod);
                Session::set('pending_razorpay_order', [
                    'order_id' => $order['id'],
                    'order_number' => $order['order_number'],
                    'razorpay_order_id' => $razorpayData['razorpay_order_id'],
                    'amount_paise' => $razorpayData['amount_paise'],
                    'key_id' => $razorpayData['key_id'],
                    'preferred_method' => $paymentMethod,
                ]);
            }

            // Set secure session confirmation token to prevent IDOR order enumeration
            Session::set('placed_order_token_' . $order['order_number'], true);

            Response::redirect('/order/success/' . $order['order_number']);
        } catch (\Exception $e) {
            Session::flash('error', 'Error placing order: ' . $e->getMessage());
            Response::redirect('/checkout');
        }
    }

    public function verifyPayment(Request $request): void
    {
        $razorpayOrderId = (string)$request->input('razorpay_order_id', '');
        $razorpayPaymentId = (string)$request->input('razorpay_payment_id', '');
        $signature = (string)$request->input('razorpay_signature', '');
        $orderNumber = (string)$request->input('order_number', '');

        $order = \App\Support\Database::fetch("SELECT * FROM orders WHERE order_number = ?", [$orderNumber]);
        if (!$order) {
            Response::json(['success' => false, 'message' => 'Order not found.'], 404);
            return;
        }

        $isValid = RazorpayAdapter::verifySignature($razorpayOrderId, $razorpayPaymentId, $signature);
        if (!$isValid) {
            Response::json(['success' => false, 'message' => 'Invalid payment signature.'], 400);
            return;
        }

        RazorpayAdapter::recordSuccess((int)$order['id'], $razorpayPaymentId, $razorpayOrderId, [
            'razorpay_order_id' => $razorpayOrderId,
            'razorpay_payment_id' => $razorpayPaymentId,
            'razorpay_signature' => $signature,
            'verified_at' => date('Y-m-d H:i:s'),
        ]);

        Response::json(['success' => true, 'message' => 'Payment verified successfully.']);
    }

    public function success(Request $request, string $orderNumber): string
    {
        $order = \App\Support\Database::fetch("SELECT * FROM orders WHERE order_number = ?", [$orderNumber]);
        if (!$order) {
            http_response_code(404);
            return View::render('errors.404', ['title' => 'Order Not Found'], 'layouts.storefront');
        }

        // Ownership and session verification check (prevent unauthorized order harvesting)
        $authUser = auth_user();
        $isSessionOwner = (bool)Session::get('placed_order_token_' . $orderNumber, false);
        $isAccountOwner = ($authUser && !empty($order['user_id']) && (int)$authUser['id'] === (int)$order['user_id']);

        if (!$isSessionOwner && !$isAccountOwner) {
            http_response_code(403);
            return View::render('errors.404', ['title' => 'Access Denied'], 'layouts.storefront');
        }

        $items = \App\Support\Database::query("SELECT * FROM order_items WHERE order_id = ?", [$order['id']]);
        $address = \App\Support\Database::fetch("SELECT * FROM order_addresses WHERE order_id = ? AND type = 'shipping'", [$order['id']]);
        $invoice = \App\Support\Database::fetch("SELECT * FROM invoices WHERE order_id = ?", [$order['id']]);

        $pendingRazorpay = Session::get('pending_razorpay_order');

        return View::render('storefront.order_success', [
            'title' => 'Order Confirmed! - ' . $order['order_number'] . ' | Welkar',
            'order' => $order,
            'items' => $items,
            'address' => $address,
            'invoice' => $invoice,
            'pendingRazorpay' => ($pendingRazorpay && $pendingRazorpay['order_number'] === $orderNumber) ? $pendingRazorpay : null,
        ], 'layouts.storefront');
    }
}

