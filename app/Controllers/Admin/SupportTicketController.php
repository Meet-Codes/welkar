<?php

namespace App\Controllers\Admin;

use App\Support\Request;
use App\Support\View;
use App\Support\Response;
use App\Support\Session;
use App\Support\Database;

class SupportTicketController
{
    public function index(Request $request): string
    {
        $status = $request->query('status');
        $category = $request->query('category');
        $search = $request->query('q');

        $where = ["1=1"];
        $params = [];

        if (!empty($status)) {
            $where[] = "status = ?";
            $params[] = $status;
        }

        if (!empty($category)) {
            $where[] = "category = ?";
            $params[] = $category;
        }

        if (!empty($search)) {
            $where[] = "(ticket_number LIKE ? OR customer_name LIKE ? OR customer_phone LIKE ? OR order_number LIKE ? OR subject LIKE ?)";
            $s = '%' . trim($search) . '%';
            $params[] = $s;
            $params[] = $s;
            $params[] = $s;
            $params[] = $s;
            $params[] = $s;
        }

        $whereClause = implode(" AND ", $where);
        $tickets = Database::fetchAll(
            "SELECT * FROM support_tickets
             WHERE {$whereClause}
             ORDER BY 
                CASE 
                    WHEN status = 'open' THEN 1 
                    WHEN status = 'in_progress' THEN 2 
                    ELSE 3 
                END, 
                updated_at DESC 
             LIMIT 100",
            $params
        );

        // Summary counts
        $counts = [
            'all' => (int)(Database::fetch("SELECT COUNT(*) as total FROM support_tickets")['total'] ?? 0),
            'open' => (int)(Database::fetch("SELECT COUNT(*) as total FROM support_tickets WHERE status = 'open'")['total'] ?? 0),
            'in_progress' => (int)(Database::fetch("SELECT COUNT(*) as total FROM support_tickets WHERE status = 'in_progress'")['total'] ?? 0),
            'resolved' => (int)(Database::fetch("SELECT COUNT(*) as total FROM support_tickets WHERE status = 'resolved'")['total'] ?? 0),
        ];

        return View::render('admin.support.index', [
            'title' => 'Customer Support Helpdesk & Tickets | Welkar Admin',
            'tickets' => $tickets,
            'counts' => $counts,
            'currentStatus' => $status,
            'currentCategory' => $category,
            'search' => $search,
        ], 'layouts.admin');
    }

    public function show(Request $request, string $id): string
    {
        $ticketId = (int)$id;
        $ticket = Database::fetch("SELECT * FROM support_tickets WHERE id = ?", [$ticketId]);

        if (!$ticket) {
            http_response_code(404);
            return View::render('errors.404', ['title' => 'Ticket Not Found'], 'layouts.admin');
        }

        $messages = Database::fetchAll(
            "SELECT * FROM support_messages WHERE ticket_id = ? ORDER BY id ASC",
            [$ticketId]
        );

        $order = null;
        if (!empty($ticket['order_number'])) {
            $order = Database::fetch("SELECT * FROM orders WHERE order_number = ?", [$ticket['order_number']]);
        }

        return View::render('admin.support.show', [
            'title' => 'Ticket #' . $ticket['ticket_number'] . ' | Welkar Admin',
            'ticket' => $ticket,
            'messages' => $messages,
            'order' => $order,
        ], 'layouts.admin');
    }

    public function reply(Request $request, string $id): void
    {
        $ticketId = (int)$id;
        $ticket = Database::fetch("SELECT * FROM support_tickets WHERE id = ?", [$ticketId]);

        if (!$ticket) {
            Session::flash('error', 'Ticket not found.');
            Response::redirect('/admin/support');
            return;
        }

        $message = trim((string)$request->input('message'));
        if (empty($message)) {
            Session::flash('error', 'Reply message cannot be empty.');
            Response::redirect('/admin/support/' . $id);
            return;
        }

        $admin = admin_user();
        $adminName = $admin['full_name'] ?? $admin['username'] ?? 'Welkar Support Agent';

        Database::transaction(function () use ($ticketId, $adminName, $message, $ticket) {
            Database::insert(
                "INSERT INTO support_messages (ticket_id, sender_type, sender_name, message, created_at)
                 VALUES (?, 'admin', ?, ?, NOW())",
                [$ticketId, $adminName, $message]
            );

            $newStatus = ($ticket['status'] === 'open') ? 'in_progress' : $ticket['status'];
            Database::execute(
                "UPDATE support_tickets SET status = ?, last_message_at = NOW(), updated_at = NOW() WHERE id = ?",
                [$newStatus, $ticketId]
            );
        });

        Session::flash('success', 'Reply sent to customer successfully.');
        Response::redirect('/admin/support/' . $id);
    }

    public function updateStatus(Request $request, string $id): void
    {
        $ticketId = (int)$id;
        $ticket = Database::fetch("SELECT * FROM support_tickets WHERE id = ?", [$ticketId]);

        if (!$ticket) {
            Session::flash('error', 'Ticket not found.');
            Response::redirect('/admin/support');
            return;
        }

        $status = (string)$request->input('status', 'in_progress');
        $priority = (string)$request->input('priority', 'medium');
        $admin = admin_user();

        Database::transaction(function () use ($ticketId, $status, $priority, $ticket, $admin) {
            Database::execute(
                "UPDATE support_tickets SET status = ?, priority = ?, updated_at = NOW() WHERE id = ?",
                [$status, $priority, $ticketId]
            );

            if ($ticket['status'] !== $status) {
                $statusMsg = "Ticket status changed to '" . ucfirst(str_replace('_', ' ', $status)) . "' by " . ($admin['username'] ?? 'Admin') . ".";
                Database::insert(
                    "INSERT INTO support_messages (ticket_id, sender_type, sender_name, message, created_at)
                     VALUES (?, 'system', 'System', ?, NOW())",
                    [$ticketId, $statusMsg]
                );
            }
        });

        Session::flash('success', 'Ticket status and priority updated successfully.');
        Response::redirect('/admin/support/' . $id);
    }

    public function updateNotes(Request $request, string $id): void
    {
        $ticketId = (int)$id;
        $notes = trim((string)$request->input('admin_notes', ''));

        Database::execute(
            "UPDATE support_tickets SET admin_notes = ?, updated_at = NOW() WHERE id = ?",
            [$notes, $ticketId]
        );

        Session::flash('success', 'Internal notes saved.');
        Response::redirect('/admin/support/' . $id);
    }
}
