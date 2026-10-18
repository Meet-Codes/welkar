<?php
require_once __DIR__ . '/../app/Support/Autoloader.php';
\App\Support\Autoloader::register(__DIR__ . '/..');
\App\Support\Env::load(__DIR__ . '/../.env');
use App\Support\Database;

$products = Database::fetchAll("SELECT id, name, slug FROM products LIMIT 20");
foreach ($products as $p) {
    echo "ID: {$p['id']} | Slug: {$p['slug']} | Name: {$p['name']}\n";
}
