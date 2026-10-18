<?php
require_once __DIR__ . '/../app/Support/Autoloader.php';
\App\Support\Autoloader::register(__DIR__ . '/..');
\App\Support\Env::load(__DIR__ . '/../.env');
require_once __DIR__ . '/../app/Support/helpers.php';

use App\Support\Database;

$rows = Database::query("SELECT pf.product_id, p.name, pf.question, pf.answer FROM product_faqs pf JOIN products p ON pf.product_id = p.id WHERE p.id = 7 ORDER BY pf.sort_order ASC");
echo "Fossil Townsman FAQs:\n";
foreach ($rows as $r) {
    echo "Q: {$r['question']}\nA: {$r['answer']}\n\n";
}
