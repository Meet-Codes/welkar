<?php

use App\Support\Env;

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
];

// Remote / Cloud Database SSL Configuration (Aiven, DigitalOcean, Railway, AWS RDS, etc.)
$sslCa = Env::get('DB_SSL_CA');
$sslMode = strtolower((string)Env::get('DB_SSL_MODE', ''));
$sslVerify = Env::get('DB_SSL_VERIFY_SERVER_CERT');

if (!empty($sslCa) && file_exists($sslCa)) {
    $options[PDO::MYSQL_ATTR_SSL_CA] = $sslCa;
} elseif ($sslMode === 'required' || $sslMode === 'require') {
    if (defined('PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT') && ($sslVerify === 'false' || $sslVerify === false)) {
        $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
    }
}

return [
    'driver' => 'mysql',
    'host' => Env::get('DB_HOST', '127.0.0.1'),
    'port' => (int)Env::get('DB_PORT', 3306),
    'database' => Env::get('DB_DATABASE', 'welkar_db'),
    'username' => Env::get('DB_USERNAME', 'root'),
    'password' => Env::get('DB_PASSWORD', ''),
    'charset' => Env::get('DB_CHARSET', 'utf8mb4'),
    'collation' => 'utf8mb4_unicode_ci',
    'options' => $options
];
