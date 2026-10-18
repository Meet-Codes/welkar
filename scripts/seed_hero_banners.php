<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/Support/Autoloader.php';
\App\Support\Autoloader::register(__DIR__ . '/..');
\App\Support\Env::load(__DIR__ . '/../.env');

use App\Support\Database;

Database::execute('DELETE FROM banners');

$banners = [
    [
        'title' => 'The Midnight Sale',
        'subtitle' => 'Exceptional Timepieces. Special Offers.',
        'image_desktop' => '/uploads/banners/hero-banner-1.jpg',
        'image_mobile' => '/uploads/banners/hero-banner-1.jpg',
        'link_url' => '/collections/offers',
        'button_text' => 'Shop Offers',
        'display_order' => 1,
        'is_active' => 1
    ],
    [
        'title' => 'Make Every Entrance Count',
        'subtitle' => 'Discover the Women’s Collection',
        'image_desktop' => '/uploads/banners/hero-banner-2.jpg',
        'image_mobile' => '/uploads/banners/hero-banner-2.jpg',
        'link_url' => '/collections/womens-watches',
        'button_text' => 'Explore Women’s',
        'display_order' => 2,
        'is_active' => 1
    ],
    [
        'title' => 'Different Looks. Same Standard.',
        'subtitle' => 'One Collection. Every Individual.',
        'image_desktop' => '/uploads/banners/hero-banner-3.jpg',
        'image_mobile' => '/uploads/banners/hero-banner-3.jpg',
        'link_url' => '/collections/unisex-minimalist',
        'button_text' => 'Discover Unisex',
        'display_order' => 3,
        'is_active' => 1
    ],
    [
        'title' => 'Precision You Can Feel',
        'subtitle' => 'Discover the Men’s Collection',
        'image_desktop' => '/uploads/banners/hero-banner-4.jpg',
        'image_mobile' => '/uploads/banners/hero-banner-4.jpg',
        'link_url' => '/collections/mens-watches',
        'button_text' => 'Explore Men’s',
        'display_order' => 4,
        'is_active' => 1
    ]
];

foreach ($banners as $b) {
    Database::execute(
        'INSERT INTO banners (title, subtitle, image_desktop, image_mobile, link_url, button_text, display_order, is_active, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())',
        [$b['title'], $b['subtitle'], $b['image_desktop'], $b['image_mobile'], $b['link_url'], $b['button_text'], $b['display_order'], $b['is_active']]
    );
}

echo "Seeded " . count($banners) . " hero banners successfully.\n";
