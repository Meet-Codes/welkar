<?php

namespace App\Controllers\Admin;

use App\Support\Request;
use App\Support\View;
use App\Support\Response;
use App\Support\Session;
use App\Support\Database;

class StaffController
{
    public function index(Request $request): string
    {
        $staff = Database::query(
            "SELECT u.*, r.name AS role_name 
             FROM admin_users u 
             LEFT JOIN roles r ON u.role_slug = r.slug 
             ORDER BY u.id ASC"
        );

        $roles = Database::query("SELECT * FROM roles ORDER BY id ASC");

        return View::render('admin.staff.index', [
            'title' => 'Staff Roles & Access Control | Welkar Admin',
            'staff' => $staff,
            'roles' => $roles,
        ], 'layouts.admin');
    }

    public function store(Request $request): void
    {
        $username = trim((string)$request->input('username'));
        $email = strtolower(trim((string)$request->input('email')));
        $fullName = trim((string)$request->input('full_name'));
        $password = (string)$request->input('password');
        $roleSlug = trim((string)$request->input('role_slug', 'staff'));
        $mfaEnabled = $request->input('mfa_enabled') ? 1 : 0;

        if (empty($username) || empty($email) || strlen($password) < 8) {
            Session::flash('error', 'Username, email, and a password of at least 8 characters are required.');
            Response::redirect('/admin/staff');
        }

        // Check unique username or email
        $exists = Database::fetch("SELECT id FROM admin_users WHERE username = ? OR email = ?", [$username, $email]);
        if ($exists) {
            Session::flash('error', 'Username or email is already taken by another administrator.');
            Response::redirect('/admin/staff');
        }

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        $userId = Database::insert(
            "INSERT INTO admin_users (username, email, password_hash, full_name, role_slug, status, mfa_enabled, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, 'active', ?, NOW(), NOW())",
            [$username, $email, $passwordHash, $fullName, $roleSlug, $mfaEnabled]
        );

        // Assign role in admin_user_roles mapping
        $role = Database::fetch("SELECT id FROM roles WHERE slug = ?", [$roleSlug]);
        if ($role) {
            Database::execute(
                "INSERT INTO admin_user_roles (admin_user_id, role_id) VALUES (?, ?)",
                [$userId, $role['id']]
            );
        }

        // Audit log
        $currentAdmin = admin_user();
        Database::execute(
            "INSERT INTO audit_logs (user_type, user_id, action, entity_type, entity_id, new_values, ip_address, created_at)
             VALUES ('admin', ?, 'create_staff', 'admin_user', ?, ?, ?, NOW())",
            [$currentAdmin['id'] ?? null, $userId, json_encode(['username' => $username, 'role' => $roleSlug]), $request->ip()]
        );

        Session::flash('success', "Staff member '{$fullName}' created successfully.");
        Response::redirect('/admin/staff');
    }

    public function toggleStatus(Request $request, string $id): void
    {
        $staffId = (int)$id;
        $currentAdmin = admin_user();

        if ((int)$currentAdmin['id'] === $staffId) {
            Session::flash('error', 'You cannot disable your own active account.');
            Response::redirect('/admin/staff');
        }

        $user = Database::fetch("SELECT * FROM admin_users WHERE id = ?", [$staffId]);
        if (!$user) {
            Session::flash('error', 'Staff account not found.');
            Response::redirect('/admin/staff');
        }

        $newStatus = ($user['status'] === 'active') ? 'inactive' : 'active';
        Database::execute("UPDATE admin_users SET status = ?, updated_at = NOW() WHERE id = ?", [$newStatus, $staffId]);

        // Audit log
        Database::execute(
            "INSERT INTO audit_logs (user_type, user_id, action, entity_type, entity_id, new_values, ip_address, created_at)
             VALUES ('admin', ?, 'toggle_staff_status', 'admin_user', ?, ?, ?, NOW())",
            [$currentAdmin['id'] ?? null, $staffId, json_encode(['status' => $newStatus]), $request->ip()]
        );

        Session::flash('success', "Account status for '{$user['username']}' updated to {$newStatus}.");
        Response::redirect('/admin/staff');
    }
}
