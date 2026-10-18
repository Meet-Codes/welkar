<?php

namespace App\Middleware;

use App\Support\Request;
use App\Support\Session;
use App\Support\Response;

class AuthMiddleware
{
    public function handle(Request $request, callable $next): mixed
    {
        $user = Session::get('customer_user');
        if (!$user) {
            if ($request->isAjax()) {
                Response::json(['error' => 'Unauthenticated.'], 401);
            }
            Session::flash('error', 'Please log in to continue.');
            Session::set('auth_intended', $request->uri());
            Response::redirect('/account/login');
        }

        return $next();
    }
}
