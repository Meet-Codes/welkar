<?php

namespace App\Controllers\Admin;

use App\Support\Request;
use App\Support\View;
use App\Support\Database;

class AuditLogController
{
    public function index(Request $request): string
    {
        $actionFilter = $request->query('action');
        $params = [];
        $where = "1=1";

        if (!empty($actionFilter)) {
            $where .= " AND action LIKE ?";
            $params[] = '%' . $actionFilter . '%';
        }

        $logs = Database::query(
            "SELECT al.*, au.username AS admin_username 
             FROM audit_logs al 
             LEFT JOIN admin_users au ON al.user_id = au.id AND al.user_type = 'admin'
             WHERE {$where}
             ORDER BY al.id DESC 
             LIMIT 100",
            $params
        );

        return View::render('admin.audit_logs.index', [
            'title' => 'Security & Operations Audit Trail | Welkar Admin',
            'logs' => $logs,
            'actionFilter' => $actionFilter,
        ], 'layouts.admin');
    }
}
