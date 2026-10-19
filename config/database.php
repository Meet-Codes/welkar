<?php

use App\Support\Env;

// 1. Support DATABASE_URL if provided (Standard on Render, Neon, Supabase, Railway)
$databaseUrl = Env::get('DATABASE_URL');
$driver = strtolower((string)Env::get('DB_CONNECTION', 'pgsql'));
$host = Env::get('DB_HOST', '127.0.0.1');
$port = (int)Env::get('DB_PORT', ($driver === 'pgsql' ? 5432 : 3306));
$database = Env::get('DB_DATABASE', 'welkar_db');
$username = Env::get('DB_USERNAME', 'root');
$password = Env::get('DB_PASSWORD', '');
$sslMode = strtolower((string)Env::get('DB_SSL_MODE', Env::get('DB_SSLMODE', '')));
$sslCa = Env::get('DB_SSL_CA');
$sslVerify = Env::get('DB_SSL_VERIFY_SERVER_CERT');

if (!empty($databaseUrl)) {
    $parsed = parse_url($databaseUrl);
    if ($parsed) {
        $scheme = strtolower($parsed['scheme'] ?? '');
        if (in_array($scheme, ['postgres', 'postgresql', 'pgsql'])) {
            $driver = 'pgsql';
            $port = isset($parsed['port']) ? (int)$parsed['port'] : 5432;
        } elseif (in_array($scheme, ['mysql', 'mariadb'])) {
            $driver = 'mysql';
            $port = isset($parsed['port']) ? (int)$parsed['port'] : 3306;
        }

        if (isset($parsed['host'])) {
            $host = $parsed['host'];
        }
        if (isset($parsed['user'])) {
            $username = urldecode($parsed['user']);
        }
        if (isset($parsed['pass'])) {
            $password = urldecode($parsed['pass']);
        }
        if (isset($parsed['path'])) {
            $database = ltrim($parsed['path'], '/');
        }
        if (isset($parsed['query'])) {
            parse_str($parsed['query'], $query);
            if (isset($query['sslmode'])) {
                $sslMode = strtolower($query['sslmode']);
            }
        }
    }
}

// Default SSL mode for cloud pgsql if not specified
if ($driver === 'pgsql' && empty($sslMode)) {
    $sslMode = 'prefer';
}

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

if ($driver === 'mysql') {
    $options[PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci";
    if (!empty($sslCa) && file_exists($sslCa)) {
        $options[PDO::MYSQL_ATTR_SSL_CA] = $sslCa;
    } elseif ($sslMode === 'required' || $sslMode === 'require') {
        if (defined('PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT') && ($sslVerify === 'false' || $sslVerify === false)) {
            $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
        }
    }
}

return [
    'driver' => $driver,
    'host' => $host,
    'port' => $port,
    'database' => $database,
    'username' => $username,
    'password' => $password,
    'charset' => Env::get('DB_CHARSET', 'utf8mb4'),
    'collation' => 'utf8mb4_unicode_ci',
    'sslmode' => $sslMode,
    'options' => $options
];
