<?php

namespace App\Controllers\Storefront;

use App\Support\Request;
use App\Support\Response;
use App\Support\Database;

class SupportController
{
    public function createTicket(Request $request): void
    {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true) ?: $_POST;

        $category = trim((string)($data['category'] ?? 'general'));
        $name = trim((string)($data['customer_name'] ?? ''));
        $phone = preg_replace('/[^0-9]/', '', (string)($data['customer_phone'] ?? ''));
        $email = trim((string)($data['customer_email'] ?? ''));
        $orderNumber = trim((string)($data['order_number'] ?? ''));
        $message = trim((string)($data['message'] ?? ''));

        if (empty($name) || strlen($phone) < 10 || empty($message)) {
            Response::json(['success' => false, 'message' => 'Please provide your name, 10-digit phone number, and query details.'], 400);
            return;
        }

        $user = auth_user();
        $userId = $user['id'] ?? null;
        $orderId = null;

        if (!empty($orderNumber)) {
            $order = Database::fetch("SELECT id FROM orders WHERE order_number = ? LIMIT 1", [$orderNumber]);
            if ($order) {
                $orderId = (int)$order['id'];
            }
        }

        $categories = [
            'return_exchange' => 'Return or Exchange Request',
            'order_status' => 'Order Status & Tracking',
            'payment_issue' => 'Payment or Refund Issue',
            'product_find' => 'Watch Finder & Horological Advice',
            'general' => 'General Customer Support'
        ];
        $subject = $categories[$category] ?? 'Support Inquiry';

        // Generate unique ticket number
        $ticketNumber = 'TKT-2026-' . mt_rand(10000, 99999);

        try {
            $ticketId = Database::insert(
                "INSERT INTO support_tickets (
                    ticket_number, user_id, order_id, order_number, customer_name,
                    customer_phone, customer_email, category, subject, status,
                    priority, last_message_at, created_at, updated_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'open', 'medium', NOW(), NOW(), NOW())",
                [
                    $ticketNumber, $userId, $orderId, $orderNumber ?: null,
                    $name, $phone, $email ?: null, $category, $subject
                ]
            );

            // Record customer initial message
            Database::insert(
                "INSERT INTO support_messages (ticket_id, sender_type, sender_name, message, created_at)
                 VALUES (?, 'customer', ?, ?, NOW())",
                [$ticketId, $name, $message]
            );

            // Automated system acknowledgment
            $ack = "Hello {$name}, thank you for reaching out to Welkar Horological Support. Your ticket #{$ticketNumber} regarding '{$subject}' has been logged. Our concierge support team is reviewing your inquiry and will update you directly here.";
            Database::insert(
                "INSERT INTO support_messages (ticket_id, sender_type, sender_name, message, created_at)
                 VALUES (?, 'system', 'Welkar Concierge Bot', ?, NOW())",
                [$ticketId, $ack]
            );

            $ticket = Database::fetch("SELECT * FROM support_tickets WHERE id = ?", [$ticketId]);
            $messages = $this->getFormattedMessages($ticketId);

            Response::json([
                'success' => true,
                'ticket' => $ticket,
                'messages' => $messages
            ]);
        } catch (\Throwable $e) {
            error_log("Support Ticket Creation Error: " . $e->getMessage());
            Response::json(['success' => false, 'message' => 'Could not create ticket. Please try again.'], 500);
        }
    }

    public function getTicket(Request $request, string $ticketNumber): void
    {
        $ticket = Database::fetch("SELECT * FROM support_tickets WHERE ticket_number = ?", [$ticketNumber]);
        if (!$ticket) {
            Response::json(['success' => false, 'message' => 'Ticket not found.'], 404);
            return;
        }

        $messages = $this->getFormattedMessages((int)$ticket['id']);

        Response::json([
            'success' => true,
            'ticket' => $ticket,
            'messages' => $messages
        ]);
    }

    public function sendMessage(Request $request, string $ticketNumber): void
    {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true) ?: $_POST;
        $message = trim((string)($data['message'] ?? ''));

        if (empty($message)) {
            Response::json(['success' => false, 'message' => 'Message cannot be empty.'], 400);
            return;
        }

        $ticket = Database::fetch("SELECT * FROM support_tickets WHERE ticket_number = ?", [$ticketNumber]);
        if (!$ticket) {
            Response::json(['success' => false, 'message' => 'Ticket not found.'], 404);
            return;
        }

        $ticketId = (int)$ticket['id'];
        $senderName = $ticket['customer_name'] ?: 'Customer';

        Database::insert(
            "INSERT INTO support_messages (ticket_id, sender_type, sender_name, message, created_at)
             VALUES (?, 'customer', ?, ?, NOW())",
            [$ticketId, $senderName, $message]
        );

        $newStatus = in_array($ticket['status'], ['resolved', 'closed']) ? 'in_progress' : $ticket['status'];
        Database::execute(
            "UPDATE support_tickets SET status = ?, last_message_at = NOW(), updated_at = NOW() WHERE id = ?",
            [$newStatus, $ticketId]
        );

        $messages = $this->getFormattedMessages($ticketId);

        Response::json([
            'success' => true,
            'messages' => $messages
        ]);
    }

    private function getFormattedMessages(int $ticketId): array
    {
        $rows = Database::fetchAll(
            "SELECT id, sender_type, sender_name, message, created_at
             FROM support_messages
             WHERE ticket_id = ?
             ORDER BY id ASC",
            [$ticketId]
        );

        $result = [];
        foreach ($rows as $r) {
            $result[] = [
                'id' => (int)$r['id'],
                'sender_type' => $r['sender_type'],
                'sender_name' => $r['sender_name'],
                'message' => $r['message'],
                'time' => date('d M, h:i A', strtotime($r['created_at']))
            ];
        }
        return $result;
    }
}
