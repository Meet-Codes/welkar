<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/Support/Autoloader.php';
\App\Support\Autoloader::register(__DIR__ . '/..');
\App\Support\Env::load(__DIR__ . '/../.env');
require_once __DIR__ . '/../app/Support/helpers.php';

use App\Support\Database;

try {
    echo "Running migration 003_company_profile_schema.sql...\n";
    $sqlMigration = file_get_contents(__DIR__ . '/../database/migrations/003_company_profile_schema.sql');
    $pdo = Database::connect();
    $pdo->exec($sqlMigration);
    echo "Migration 003 tables created successfully!\n";

    echo "Running seeder 003_seed_company_profile.sql...\n";
    $sqlSeeder = file_get_contents(__DIR__ . '/../database/seeders/003_seed_company_profile.sql');
    $pdo->exec($sqlSeeder);
    echo "Seeder 003 data seeded successfully!\n";
} catch (\Throwable $e) {
    echo "Error executing migration/seeder 003: " . $e->getMessage() . "\n";
    exit(1);
}
