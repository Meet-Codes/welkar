<?php

declare(strict_types=1);

// Welkar Manual Backup Utility
require_once __DIR__ . '/../app/Support/Autoloader.php';
\App\Support\Autoloader::register(__DIR__ . '/..');
\App\Support\Env::load(__DIR__ . '/../.env');

$backupDir = __DIR__ . '/../storage/backups';
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}

$timestamp = date('Ymd_His');
$sqlDumpFile = $backupDir . "/welkar_db_{$timestamp}.sql";

echo "Starting Welkar Backup [{$timestamp}]...\n";

// Run mysqldump / mariadb-dump
$mariadbDump = 'C:\\Users\\mahek\\AppData\\Local\\Programs\\mariadb\\bin\\mariadb-dump.exe';
if (!file_exists($mariadbDump)) {
    $mariadbDump = 'mysqldump';
}

$dbHost = \App\Support\Config::get('database.host', '127.0.0.1');
$dbUser = \App\Support\Config::get('database.username', 'root');
$dbName = \App\Support\Config::get('database.database', 'welkar_db');

$cmd = "\"{$mariadbDump}\" -h {$dbHost} -u {$dbUser} {$dbName} > \"{$sqlDumpFile}\"";
exec($cmd, $output, $returnCode);

if ($returnCode === 0 && file_exists($sqlDumpFile)) {
    echo " [SUCCESS] Database dumped to: {$sqlDumpFile} (" . filesize($sqlDumpFile) . " bytes)\n";
} else {
    echo " [WARNING] Database dump command exited with code {$returnCode}. Check path/permissions.\n";
}

echo "Backup complete.\n";
