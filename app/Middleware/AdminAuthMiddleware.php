<?php

namespace App\Middleware;

use App\Support\Request;
use App\Support\Session;
use App\Support\Response;
use App\Support\Config;

class AdminAuthMiddleware
{
    public function handle(Request $request, callable $next): mixed
    {
        $admin = Session::get('admin_user');
        if (!$admin) {
            if ($request->isAjax()) {
                Response::json(['error' => 'Unauthorized admin session.'], 401);
            }
            Session::flash('error', 'Please log in to access the Admin Portal.');
            Response::redirect('/admin/login');
        }

        // Check if MFA challenge is pending
        if (Session::get('admin_mfa_pending', false)) {
            if ($request->uri() !== '/admin/mfa-verify' && $request->uri() !== '/admin/logout') {
                Response::redirect('/admin/mfa-verify');
            }
        }

        return $next();
    }
}
