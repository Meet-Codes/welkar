<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/Support/Autoloader.php';
\App\Support\Autoloader::register(__DIR__ . '/..');
\App\Support\Env::load(__DIR__ . '/../.env');
require_once __DIR__ . '/../app/Support/helpers.php';

use App\Support\Database;

try {
    echo "Running migration 002_product_page_aplus_schema.sql...\n";
    $sql = file_get_contents(__DIR__ . '/../database/migrations/002_product_page_aplus_schema.sql');
    
    // Check if reviews table needs additional columns
    $reviewCols = Database::fetchAll("DESCRIBE reviews");
    $existing = array_column($reviewCols, 'Field');
    
    if (!in_array('variant_id', $existing)) {
        Database::execute("ALTER TABLE `reviews` ADD COLUMN `variant_id` BIGINT UNSIGNED NULL AFTER `order_id`");
        echo "Added variant_id to reviews\n";
    }
    if (!in_array('helpful_count', $existing)) {
        Database::execute("ALTER TABLE `reviews` ADD COLUMN `helpful_count` INT UNSIGNED NOT NULL DEFAULT 0 AFTER `status`");
        echo "Added helpful_count to reviews\n";
    }
    if (!in_array('images_json', $existing)) {
        Database::execute("ALTER TABLE `reviews` ADD COLUMN `images_json` TEXT NULL AFTER `helpful_count`");
        echo "Added images_json to reviews\n";
    }
    if (!in_array('admin_response', $existing)) {
        Database::execute("ALTER TABLE `reviews` ADD COLUMN `admin_response` TEXT NULL AFTER `images_json`");
        echo "Added admin_response to reviews\n";
    }
    if (!in_array('admin_responded_at', $existing)) {
        Database::execute("ALTER TABLE `reviews` ADD COLUMN `admin_responded_at` DATETIME NULL AFTER `admin_response`");
        echo "Added admin_responded_at to reviews\n";
    }
    
    $pdo = Database::connect();
    $pdo->exec($sql);
    echo "Migration 002 completed successfully!\n";
} catch (\Throwable $e) {
    echo "Migration error: " . $e->getMessage() . "\n";
    exit(1);
}
