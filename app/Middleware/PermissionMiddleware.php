<?php

namespace App\Middleware;

use App\Support\Request;
use App\Support\Session;
use App\Support\Response;
use App\Services\Auth\AdminAuthService;

class PermissionMiddleware
{
    private string $permission;

    public function __construct(string $permission = '')
    {
        $this->permission = $permission;
    }

    public function handle(Request $request, callable $next): mixed
    {
        $admin = Session::get('admin_user');
        if (!$admin) {
            Response::redirect('/admin/login');
        }

        // Owner role has all permissions
        if (($admin['role_slug'] ?? '') === 'owner') {
            return $next();
        }

        if (!empty($this->permission)) {
            $permissions = Session::get('admin_permissions', []);
            if (!in_array($this->permission, $permissions)) {
                if ($request->isAjax()) {
                    Response::json(['error' => 'Permission denied: ' . $this->permission], 403);
                }
                Session::flash('error', 'You do not have permission to perform this action (' . $this->permission . ').');
                Response::redirect('/admin');
            }
        }

        return $next();
    }
}
