<?php
require_once __DIR__ . '/../../app/Support/Env.php';
require_once __DIR__ . '/../../app/Support/Config.php';
require_once __DIR__ . '/../../app/Support/Database.php';

\App\Support\Env::load(__DIR__ . '/../../.env');
\App\Support\Database::connect();

echo "Running migrations...\n";

// 1. Alter orders.payment_method to VARCHAR(50) to support upi, card, netbanking, wallet, cod, razorpay
try {
    \App\Support\Database::execute("ALTER TABLE orders MODIFY COLUMN payment_method VARCHAR(50) NOT NULL DEFAULT 'cod'");
    echo "[OK] Modified orders.payment_method to VARCHAR(50)\n";
} catch (\Throwable $e) {
    echo "[INFO] orders.payment_method alter: " . $e->getMessage() . "\n";
}

// 2. Create support_tickets table
$sqlTickets = "
CREATE TABLE IF NOT EXISTS support_tickets (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ticket_number VARCHAR(30) NOT NULL UNIQUE,
    user_id BIGINT UNSIGNED NULL,
    order_id BIGINT UNSIGNED NULL,
    order_number VARCHAR(50) NULL,
    customer_name VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(25) NOT NULL,
    customer_email VARCHAR(120) NULL,
    category VARCHAR(50) NOT NULL DEFAULT 'general',
    subject VARCHAR(255) NOT NULL,
    status ENUM('open', 'in_progress', 'resolved', 'closed') NOT NULL DEFAULT 'open',
    priority ENUM('low', 'medium', 'high', 'urgent') NOT NULL DEFAULT 'medium',
    admin_notes TEXT NULL,
    last_message_at DATETIME NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_phone (customer_phone),
    INDEX idx_order (order_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";
\App\Support\Database::execute($sqlTickets);
echo "[OK] Created or verified support_tickets table\n";

// 3. Create support_messages table
$sqlMessages = "
CREATE TABLE IF NOT EXISTS support_messages (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ticket_id BIGINT UNSIGNED NOT NULL,
    sender_type ENUM('customer', 'admin', 'system') NOT NULL,
    sender_name VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_ticket (ticket_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";
\App\Support\Database::execute($sqlMessages);
echo "[OK] Created or verified support_messages table\n";

echo "Migrations completed successfully!\n";
