<?php

namespace App\Controllers\Storefront;

use App\Support\Request;
use App\Support\View;
use App\Support\Response;
use App\Support\Session;
use App\Services\Wishlist\WishlistService;

class WishlistController
{
    public function index(Request $request): string
    {
        Response::redirect('/catalog');
        return '';
    }

    public function toggle(Request $request): void
    {
        $productId = (int)$request->input('product_id', 0);
        $variantId = $request->input('variant_id') ? (int)$request->input('variant_id') : null;

        if ($productId <= 0) {
            Response::json(['error' => 'Invalid product identifier'], 400);
            return;
        }

        $res = WishlistService::toggleItem($productId, $variantId);
        Response::json([
            'success' => true,
            'action' => $res['action'],
            'count' => $res['count'],
            'message' => $res['action'] === 'added' ? 'Saved to your Wishlist!' : 'Removed from Wishlist',
        ]);
    }

    public function remove(Request $request): void
    {
        $itemId = (int)$request->input('item_id', 0);
        if ($itemId > 0) {
            WishlistService::removeItem($itemId);
            Session::flash('info', 'Item removed from your wishlist.');
        }

        Response::redirect('/wishlist');
    }

    public function moveToCart(Request $request): void
    {
        $itemId = (int)$request->input('item_id', 0);
        if ($itemId > 0) {
            $success = WishlistService::moveToCart($itemId);
            if ($success) {
                Session::flash('success', 'Watch successfully moved to your shopping bag!');
                Response::redirect('/cart');
                return;
            }
        }

        Session::flash('error', 'Unable to move item to cart. Please check stock availability.');
        Response::redirect('/wishlist');
    }
}
