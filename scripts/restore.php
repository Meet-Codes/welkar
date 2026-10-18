<?php

declare(strict_types=1);

// Welkar Database Restore Utility
require_once __DIR__ . '/../app/Support/Autoloader.php';
\App\Support\Autoloader::register(__DIR__ . '/..');
\App\Support\Env::load(__DIR__ . '/../.env');

$file = $argv[1] ?? null;
if (!$file || !file_exists($file)) {
    echo "Usage: php scripts/restore.php <path-to-sql-backup-file>\n";
    exit(1);
}

echo "Restoring Welkar database from: {$file}...\n";

$mariadbClient = 'C:\\Users\\mahek\\AppData\\Local\\Programs\\mariadb\\bin\\mariadb.exe';
if (!file_exists($mariadbClient)) {
    $mariadbClient = 'mysql';
}

$dbHost = \App\Support\Config::get('database.host', '127.0.0.1');
$dbUser = \App\Support\Config::get('database.username', 'root');
$dbName = \App\Support\Config::get('database.database', 'welkar_db');

$cmd = "\"{$mariadbClient}\" -h {$dbHost} -u {$dbUser} {$dbName} < \"{$file}\"";
exec($cmd, $output, $returnCode);

if ($returnCode === 0) {
    echo " [SUCCESS] Database restored successfully.\n";
} else {
    echo " [ERROR] Database restore failed with exit code: {$returnCode}\n";
    exit(1);
}
