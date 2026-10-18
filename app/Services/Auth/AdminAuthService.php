<?php

namespace App\Services\Auth;

use App\Support\Database;
use App\Support\Session;

class AdminAuthService
{
    public static function attempt(string $usernameOrEmail, string $password): array
    {
        $admin = Database::fetch(
            "SELECT * FROM admin_users WHERE (username = ? OR email = ?) AND status = 'active'",
            [$usernameOrEmail, $usernameOrEmail]
        );

        if (!$admin || !password_verify($password, $admin['password_hash'])) {
            return ['success' => false, 'message' => 'Invalid admin username or password.'];
        }

        // Check if MFA is enabled
        if ((bool)$admin['mfa_enabled']) {
            $code = (string)random_int(100000, 999999);
            Database::execute(
                "INSERT INTO admin_mfa_challenges (admin_user_id, code, expires_at)
                 VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 15 MINUTE))",
                [$admin['id'], $code]
            );

            Session::set('admin_mfa_user_id', $admin['id']);
            Session::set('admin_mfa_pending', true);

            $isLocalDebug = in_array(\App\Support\Config::get('app.env', 'production'), ['local', 'test']) && 
                            (bool)\App\Support\Config::get('app.debug', false);

            if ($isLocalDebug) {
                Session::set('debug_mfa_code', $code);
            }

            return ['success' => true, 'mfa_required' => true];
        }

        self::loginAdmin($admin);
        return ['success' => true, 'mfa_required' => false];
    }

    public static function verifyMfa(string $code): array
    {
        $adminId = Session::get('admin_mfa_user_id');
        if (!$adminId) {
            return ['success' => false, 'message' => 'No pending MFA challenge found.'];
        }

        $challenge = Database::fetch(
            "SELECT * FROM admin_mfa_challenges
             WHERE admin_user_id = ? AND verified_at IS NULL AND expires_at > NOW()
             ORDER BY id DESC LIMIT 1",
            [$adminId]
        );

        if (!$challenge || $challenge['code'] !== trim($code)) {
            return ['success' => false, 'message' => 'Invalid or expired MFA code.'];
        }

        Database::execute("UPDATE admin_mfa_challenges SET verified_at = NOW() WHERE id = ?", [$challenge['id']]);

        $admin = Database::fetch("SELECT * FROM admin_users WHERE id = ?", [$adminId]);
        Session::remove('admin_mfa_pending');
        Session::remove('admin_mfa_user_id');
        Session::remove('debug_mfa_code');

        self::loginAdmin($admin);
        return ['success' => true];
    }

    private static function loginAdmin(array $admin): void
    {
        Session::regenerate();

        // Load permissions
        $permissions = [];
        if ($admin['role_slug'] === 'owner') {
            $allPerms = Database::query("SELECT slug FROM permissions");
            $permissions = array_column($allPerms, 'slug');
        } else {
            $rolePerms = Database::query(
                "SELECT p.slug
                 FROM admin_user_roles aur
                 JOIN role_permissions rp ON aur.role_id = rp.role_id
                 JOIN permissions p ON rp.permission_id = p.id
                 WHERE aur.admin_user_id = ?",
                [$admin['id']]
            );
            $permissions = array_column($rolePerms, 'slug');
        }

        Database::execute("UPDATE admin_users SET last_login_at = NOW() WHERE id = ?", [$admin['id']]);

        Session::set('admin_user', $admin);
        Session::set('admin_permissions', $permissions);
    }

    public static function logout(): void
    {
        Session::remove('admin_user');
        Session::remove('admin_permissions');
        Session::regenerate();
    }
}
