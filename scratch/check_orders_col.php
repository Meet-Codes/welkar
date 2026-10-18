<?php
require_once __DIR__ . '/../app/Support/Env.php';
require_once __DIR__ . '/../app/Support/Config.php';
require_once __DIR__ . '/../app/Support/Database.php';

\App\Support\Env::load(__DIR__ . '/../.env');
\App\Support\Database::connect();
$cols = \App\Support\Database::fetchAll("SHOW COLUMNS FROM payments");
foreach ($cols as $c) {
    echo "{$c['Field']} ({$c['Type']})\n";
}
