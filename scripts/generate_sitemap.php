<?php
require_once __DIR__ . '/../app/Support/Autoloader.php';
\App\Support\Autoloader::register(__DIR__ . '/..');
\App\Support\Env::load(__DIR__ . '/../.env');
require_once __DIR__ . '/../app/Support/helpers.php';

use App\Support\Database;

$domain = 'https://www.welkar.in';
$today = date('Y-m-d');

$xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
$xml .= '        xmlns:xhtml="http://www.w3.org/1999/xhtml">' . "\n";

// Helper closure
$addUrl = function($loc, $lastmod, $changefreq, $priority) use (&$xml, $domain) {
    $xml .= "    <url>\n";
    $xml .= "        <loc>" . htmlspecialchars($domain . $loc) . "</loc>\n";
    $xml .= "        <lastmod>{$lastmod}</lastmod>\n";
    $xml .= "        <changefreq>{$changefreq}</changefreq>\n";
    $xml .= "        <priority>{$priority}</priority>\n";
    $xml .= "        <xhtml:link rel=\"alternate\" hreflang=\"en\" href=\"" . htmlspecialchars($domain . $loc) . "\" />\n";
    $xml .= "        <xhtml:link rel=\"alternate\" hreflang=\"hi\" href=\"" . htmlspecialchars($domain . $loc . (str_contains($loc, '?') ? '&' : '?') . 'lang=hi') . "\" />\n";
    $xml .= "        <xhtml:link rel=\"alternate\" hreflang=\"x-default\" href=\"" . htmlspecialchars($domain . $loc) . "\" />\n";
    $xml .= "    </url>\n";
};

// 1. Core Storefront Pages
$addUrl('/', $today, 'daily', '1.0');
$addUrl('/catalog', $today, 'daily', '0.9');
$addUrl('/brands', $today, 'weekly', '0.8');

// 2. Collections
$collections = [
    'mens-watches' => '0.9',
    'womens-watches' => '0.9',
    'unisex-minimalist' => '0.8',
    'luxury-watches' => '0.9',
    'sports-watches' => '0.9',
    'kids-youth-watches' => '0.8',
    'new-arrivals' => '0.9',
    'offers' => '0.9',
];
foreach ($collections as $slug => $prio) {
    $addUrl('/collections/' . $slug, $today, 'daily', $prio);
}

// 3. Brands
$brands = Database::query("SELECT slug, created_at FROM brands WHERE is_active = 1 ORDER BY id ASC");
foreach ($brands as $b) {
    $mod = !empty($b['created_at']) ? substr($b['created_at'], 0, 10) : $today;
    $addUrl('/brand/' . $b['slug'], $mod, 'weekly', '0.8');
}

// 4. Products (Active Only)
$products = Database::query("SELECT slug, updated_at FROM products WHERE is_active = 1 ORDER BY id ASC");
foreach ($products as $p) {
    $mod = !empty($p['updated_at']) ? substr($p['updated_at'], 0, 10) : $today;
    $addUrl('/product/' . $p['slug'], $mod, 'weekly', '0.8');
}

// 5. Public CMS & Company Pages
$addUrl('/about', $today, 'monthly', '0.7');
$addUrl('/about-us', $today, 'monthly', '0.7');
$addUrl('/track-order', $today, 'weekly', '0.6');

$pages = Database::query("SELECT slug, updated_at FROM content_pages WHERE is_published = 1 AND slug NOT IN ('about-us', 'about') ORDER BY id ASC");
foreach ($pages as $cp) {
    $mod = !empty($cp['updated_at']) ? substr($cp['updated_at'], 0, 10) : $today;
    $addUrl('/page/' . $cp['slug'], $mod, 'monthly', '0.5');
}

$xml .= '</urlset>' . "\n";

file_put_contents(__DIR__ . '/../public/sitemap.xml', $xml);
echo "Generated public/sitemap.xml successfully (" . strlen($xml) . " bytes, " . (count($collections) + count($brands) + count($products) + count($pages) + 6) . " URLs).\n";
