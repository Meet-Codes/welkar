<?php

namespace App\Controllers\Storefront;

use App\Support\Request;
use App\Support\View;
use App\Support\Response;
use App\Support\Session;
use App\Services\Cart\CartService;
use App\Services\Analytics\AnalyticsService;

class CartController
{
    public function index(Request $request): string
    {
        $cart = CartService::getCart();
        AnalyticsService::logEvent('view_cart', $request, ['items_count' => count($cart['items'])]);

        return View::render('storefront.cart', [
            'title' => 'Your Shopping Bag | Welkar',
            'cart' => $cart,
        ], 'layouts.storefront');
    }

    public function add(Request $request): void
    {
        $variantId = (int)$request->input('variant_id');
        $qty = max(1, (int)$request->input('quantity', 1));

        $res = CartService::addItem($variantId, $qty);

        if ($res['success']) {
            AnalyticsService::logEvent('add_to_cart', $request, ['variant_id' => $variantId, 'quantity' => $qty]);
        }

        if ($request->isAjax()) {
            $cart = CartService::getCart();
            Response::json([
                'success' => $res['success'],
                'message' => $res['message'],
                'cart_count' => $cart['summary']['total_items'],
                'cart' => $cart,
            ]);
        }

        if ($request->input('buy_now')) {
            Session::flash($res['success'] ? 'success' : 'error', $res['message']);
            Response::redirect($res['success'] ? '/checkout' : '/cart');
            return;
        }

        Session::flash($res['success'] ? 'success' : 'error', $res['message']);
        Response::redirect('/cart');
    }

    public function update(Request $request): void
    {
        $itemId = (int)$request->input('cart_item_id');
        $qty = (int)$request->input('quantity');

        $res = CartService::updateQuantity($itemId, $qty);

        if ($request->isAjax()) {
            $cart = CartService::getCart();
            Response::json([
                'success' => $res['success'],
                'message' => $res['message'],
                'cart' => $cart
            ]);
        }

        Session::flash($res['success'] ? 'success' : 'error', $res['message']);
        Response::redirect('/cart');
    }

    public function remove(Request $request): void
    {
        $itemId = (int)$request->input('cart_item_id');
        $res = CartService::removeItem($itemId);

        if ($request->isAjax()) {
            $cart = CartService::getCart();
            Response::json(['success' => true, 'cart' => $cart]);
        }

        Session::flash('success', 'Item removed.');
        Response::redirect('/cart');
    }

    public function applyCoupon(Request $request): void
    {
        $code = (string)$request->input('coupon_code', '');
        $res = CartService::applyCoupon($code);

        if ($request->isAjax()) {
            $cart = CartService::getCart();
            Response::json([
                'success' => $res['success'],
                'message' => $res['message'],
                'cart' => $cart
            ]);
        }

        Session::flash($res['success'] ? 'success' : 'error', $res['message']);
        Response::redirect('/cart');
    }

    public function removeCoupon(Request $request): void
    {
        $res = CartService::removeCoupon();
        if ($request->isAjax()) {
            $cart = CartService::getCart();
            Response::json(['success' => true, 'cart' => $cart]);
        }

        Session::flash('success', 'Coupon removed.');
        Response::redirect('/cart');
    }
}
