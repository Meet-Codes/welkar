<?php
require_once __DIR__ . '/../app/Support/Autoloader.php';
\App\Support\Autoloader::register(__DIR__ . '/..');
\App\Support\Env::load(__DIR__ . '/../.env');

use App\Support\Database;
Database::connect();

$faqs = Database::query("SELECT * FROM product_faqs");
echo "Total Product FAQs: " . count($faqs) . "\n";
foreach ($faqs as $f) {
    echo "ID: {$f['id']} | ProductID: {$f['product_id']} | Q: {$f['question']}\n";
}
