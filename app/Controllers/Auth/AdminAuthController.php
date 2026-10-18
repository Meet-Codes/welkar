<?php

namespace App\Controllers\Auth;

use App\Support\Request;
use App\Support\View;
use App\Support\Response;
use App\Support\Session;
use App\Services\Auth\AdminAuthService;

class AdminAuthController
{
    public function showLogin(Request $request): string
    {
        if (admin_user() && !Session::get('admin_mfa_pending', false)) {
            Response::redirect('/admin');
        }

        return View::render('auth.admin_login', [
            'title' => 'Welkar Administration Portal Login',
        ], 'layouts.admin_auth');
    }

    public function login(Request $request): void
    {
        $username = trim((string)$request->input('username'));
        $password = (string)$request->input('password');

        $result = AdminAuthService::attempt($username, $password);

        if (!$result['success']) {
            Session::flash('error', $result['message']);
            Response::redirect('/admin/login');
        }

        if (!empty($result['mfa_required'])) {
            Session::flash('info', 'MFA code sent to your registered admin email.');
            Response::redirect('/admin/mfa-verify');
        }

        Session::flash('success', 'Logged in to Welkar Admin Portal.');
        Response::redirect('/admin');
    }

    public function showMfa(Request $request): string
    {
        if (!Session::get('admin_mfa_pending', false)) {
            Response::redirect('/admin/login');
        }

        $isLocalDebug = in_array(\App\Support\Config::get('app.env', 'production'), ['local', 'test']) && 
                        (bool)\App\Support\Config::get('app.debug', false);

        return View::render('auth.admin_mfa', [
            'title' => 'Two-Factor Authentication | Welkar Admin',
            'debugCode' => $isLocalDebug ? Session::get('debug_mfa_code') : null,
        ], 'layouts.admin_auth');
    }

    public function verifyMfa(Request $request): void
    {
        $code = (string)$request->input('code');
        $result = AdminAuthService::verifyMfa($code);

        if ($result['success']) {
            Session::flash('success', 'MFA authentication verified.');
            Response::redirect('/admin');
        }

        Session::flash('error', $result['message']);
        Response::redirect('/admin/mfa-verify');
    }

    public function logout(Request $request): void
    {
        AdminAuthService::logout();
        Response::redirect('/admin/login');
    }
}
