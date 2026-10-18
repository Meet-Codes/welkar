<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/Support/Autoloader.php';
\App\Support\Autoloader::register(__DIR__ . '/..');
\App\Support\Env::load(__DIR__ . '/../.env');
require_once __DIR__ . '/../app/Support/helpers.php';

use App\Support\Database;

try {
    echo "Seeding A+ product page data and comparison matrix...\n";

    // 1. Comparison Rows
    $rows = [
        ['feature_name' => 'Case Diameter', 'sort_order' => 1],
        ['feature_name' => 'Movement Caliber', 'sort_order' => 2],
        ['feature_name' => 'Dial Glass', 'sort_order' => 3],
        ['feature_name' => 'Water Resistance', 'sort_order' => 4],
        ['feature_name' => 'Case & Strap Material', 'sort_order' => 5],
        ['feature_name' => 'Atelier Warranty', 'sort_order' => 6],
    ];

    foreach ($rows as $r) {
        $existing = Database::fetch("SELECT id FROM product_comparison_rows WHERE feature_name = ?", [$r['feature_name']]);
        if (!$existing) {
            Database::execute("INSERT INTO product_comparison_rows (feature_name, sort_order, is_active) VALUES (?, ?, 1)", [
                $r['feature_name'], $r['sort_order']
            ]);
        }
    }

    $allRows = Database::fetchAll("SELECT id, feature_name FROM product_comparison_rows");
    $rowMap = [];
    foreach ($allRows as $ar) {
        $rowMap[$ar['feature_name']] = $ar['id'];
    }

    // 2. Fetch products
    $products = Database::fetchAll("SELECT id, name, slug FROM products");
    echo "Found " . count($products) . " products to enhance with A+ data.\n";

    foreach ($products as $p) {
        $pId = $p['id'];

        // Populate comparison values
        $compData = [
            'Case Diameter' => '42mm Standard',
            'Movement Caliber' => 'Japanese Quartz (+/-15s/mo)',
            'Dial Glass' => 'Hardened Mineral Crystal',
            'Water Resistance' => '30M / 3 ATM (Hydro-Tested)',
            'Case & Strap Material' => '316L Surgical Stainless Steel',
            'Atelier Warranty' => '1 Year Mechanism Warranty',
        ];

        foreach ($compData as $fname => $val) {
            if (isset($rowMap[$fname])) {
                Database::execute(
                    "INSERT INTO product_comparison_values (product_id, row_id, value) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE value = ?",
                    [$pId, $rowMap[$fname], $val, $val]
                );
            }
        }

        // 3. Hotspots for watches
        $existingHotspots = Database::fetch("SELECT id FROM product_hotspots WHERE product_id = ?", [$pId]);
        if (!$existingHotspots) {
            $hotspots = [
                [
                    'x' => 48.5,
                    'y' => 22.0,
                    'title' => 'Luminescent Indices & Hands',
                    'desc' => 'High-phosphorescence compound coatings ensure optimal low-light legibility across executive suites or evening galas.',
                    'sort' => 1
                ],
                [
                    'x' => 76.0,
                    'y' => 50.0,
                    'title' => 'Double-Gasket Fluted Crown',
                    'desc' => 'Silicone dual O-ring construction guarantees tight hydrostatic sealing resistant to splashes, humidity, and atmospheric pressure changes.',
                    'sort' => 2
                ],
                [
                    'x' => 50.0,
                    'y' => 78.0,
                    'title' => 'Solid 316L Link Bracelet',
                    'desc' => 'Individually chamfered and micro-brushed surgical steel links with smooth dual-button butterfly deployant clasp.',
                    'sort' => 3
                ],
                [
                    'x' => 28.0,
                    'y' => 45.0,
                    'title' => 'Polished Bezel Chamfer',
                    'desc' => 'Mirror-finished angled chamfers catch and refract natural sunlight, emphasizing architectural depth and wrist presence.',
                    'sort' => 4
                ],
            ];

            foreach ($hotspots as $h) {
                Database::execute(
                    "INSERT INTO product_hotspots (product_id, x_pos_percent, y_pos_percent, title, description, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, 1)",
                    [$pId, $h['x'], $h['y'], $h['title'], $h['desc'], $h['sort']]
                );
            }
        }

        // 4. Product FAQs
        $existingFaqs = Database::fetch("SELECT id FROM product_faqs WHERE product_id = ?", [$pId]);
        if (!$existingFaqs) {
            $faqs = [
                ['q' => 'Is this watch safe for showering or heavy monsoon rain?', 'a' => 'Yes. Every unit is pressure-tested in our Bangalore testing chamber to 30M (3 ATM) hydrostatic depth with silicone crown seals, making it fully resilient against rain, hand-washing, and accidental submersions.'],
                ['q' => 'How can I adjust the stainless steel link bracelet?', 'a' => 'All solid 316L link bracelets include pin-removal arrows on the inner links. You can adjust it with a standard pin pusher at home, or visit any watch technician. Our WhatsApp Concierge also provides step-by-step video guidance.'],
                ['q' => 'What type of battery does this Japanese caliber use?', 'a' => 'It runs on an authentic Japanese SR626SW silver-oxide cell with a 2 to 3 year continuous lifespan. When replacement is due, any local jeweler can service it in minutes.'],
                ['q' => 'What is covered under the 1-Year Atelier Warranty?', 'a' => 'The warranty covers internal movement accuracy, hands alignment, and hydrostatic gasket seal integrity. In the rare event of a manufacturer defect, we provide complimentary doorstep pickup and exchange.'],
            ];

            foreach ($faqs as $fIdx => $f) {
                Database::execute(
                    "INSERT INTO product_faqs (product_id, question, answer, sort_order, is_active) VALUES (?, ?, ?, ?, 1)",
                    [$pId, $f['q'], $f['a'], $fIdx + 1]
                );
            }
        }

        // 5. A+ Content Modules
        $existingModules = Database::fetch("SELECT id FROM product_page_modules WHERE product_id = ?", [$pId]);
        if (!$existingModules) {
            // Module 1: Editorial Wide Banner
            $bannerData = json_encode([
                'headline' => 'CALIBRATED FOR HOROLOGICAL DISTINCTION',
                'body' => 'Engineered with solid 316L surgical stainless steel, dual-layer anti-reflective crystal, and precision Japanese movements for executive confidence on every wrist.',
                'image_url' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=1600&q=80',
                'badge' => 'THE ATELIER COLLECTION'
            ]);
            Database::execute(
                "INSERT INTO product_page_modules (product_id, module_type, title, subtitle, content_json, sort_order, is_enabled) VALUES (?, 'banner', 'Editorial Wide Banner', NULL, ?, 1, 1)",
                [$pId, $bannerData]
            );

            // Module 2: Feature Stories
            $storiesData = json_encode([
                'stories' => [
                    [
                        'eyebrow' => 'COLD-FORGED STEEL',
                        'title' => '316L Surgical Alloy Engineering',
                        'text' => 'We forge our cases from medical-grade 316L alloy rather than standard zinc or alloy metals. This provides exceptional resistance to perspiration corrosion and retains a brilliant mirror chamfer for decades.',
                        'image_url' => 'https://images.unsplash.com/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=800&q=80',
                        'bullets' => ['Hypoallergenic nickel-safe skin contact', 'High-density micro-milled link tolerancing', 'Scratch-resistant satin-brushed flank']
                    ],
                    [
                        'eyebrow' => 'HYDROSTATIC LAB',
                        'title' => 'Double-Gasket Silicone Pressure Seals',
                        'text' => 'Before any watch leaves our testing chamber, it undergoes negative and positive pressure immersion cycles. The fluted crown and caseback utilize compression O-rings engineered to repel moisture under all everyday conditions.',
                        'image_url' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=800&q=80',
                        'bullets' => ['30M (3 ATM) certified submersion standards', 'Threaded compression caseback', 'Rain and humidity resilience']
                    ],
                    [
                        'eyebrow' => 'AUTHENTICITY & SERIALS',
                        'title' => 'Sequential Stamped Collector Cards',
                        'text' => 'Every Welkar watch is laser-inscribed with a unique serial number that matches the stamped holographic warranty card in your presentation box. Your warranty is automatically registered in our active atelier database.',
                        'image_url' => 'https://images.unsplash.com/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=800&q=80',
                        'bullets' => ['Holographic tamper-evident verification', 'Digital warranty concierge registry', 'Complimentary pan-India service pickup']
                    ]
                ]
            ]);
            Database::execute(
                "INSERT INTO product_page_modules (product_id, module_type, title, subtitle, content_json, sort_order, is_enabled) VALUES (?, 'stories', 'Craftsmanship Feature Stories', NULL, ?, 2, 1)",
                [$pId, $storiesData]
            );

            // Module 3: Lifestyle Carousel
            $lifestyleData = json_encode([
                'title' => 'On The Wrist — Collector Styling',
                'subtitle' => 'Real-world wrist presence photographed across diverse formal and casual settings.',
                'images' => [
                    ['url' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80', 'caption' => 'Executive Office & Cuff Profile (7.0" Wrist)'],
                    ['url' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=600&q=80', 'caption' => 'Milanese Evening Grace (5.8" Wrist)'],
                    ['url' => 'https://images.unsplash.com/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=600&q=80', 'caption' => 'Weekend Chrono Casual (6.5" Wrist)'],
                    ['url' => 'https://images.unsplash.com/photo-1539874701005-786335a09b30?auto=format&fit=crop&w=600&q=80', 'caption' => 'Minimalist Monolith Architecture (6.8" Wrist)']
                ]
            ]);
            Database::execute(
                "INSERT INTO product_page_modules (product_id, module_type, title, subtitle, content_json, sort_order, is_enabled) VALUES (?, 'lifestyle', 'Lifestyle Carousel', NULL, ?, 3, 1)",
                [$pId, $lifestyleData]
            );
        }

        // 6. Seed high-quality customer reviews with verified purchases
        $existingReviews = Database::fetchAll("SELECT id FROM reviews WHERE product_id = ?", [$pId]);
        if (count($existingReviews) < 3) {
            $sampleReviews = [
                [
                    'author' => 'Arjun Singhania',
                    'rating' => 5,
                    'title' => 'Exceeded all expectations — looks like a ₹15,000 Swiss piece',
                    'comment' => 'The finishing on the 316L steel links is shockingly good. Mirror chamfers catch the light beautifully and the sweeping hands are dead accurate. Came in a gorgeous magnetic hard-shell box with serial card.',
                    'verified' => 1,
                    'helpful' => 18,
                    'admin_reply' => 'Thank you for your appreciation, Arjun! Enjoy the timepiece in good health.',
                    'images' => json_encode(['https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80'])
                ],
                [
                    'author' => 'Pooja Deshmukh',
                    'rating' => 5,
                    'title' => 'Delicate, featherlight, and perfectly sized',
                    'comment' => 'Ordered via COD to Pune. Received it within 2 days in secure tamper-proof packaging. The dial has a subtle sunburst texture that looks stunning in daytime.',
                    'verified' => 1,
                    'helpful' => 12,
                    'admin_reply' => 'We are delighted to hear you love the dial texture, Pooja! Welcome to the Welkar Atelier.',
                    'images' => null
                ],
                [
                    'author' => 'Rohan Mehta',
                    'rating' => 4,
                    'title' => 'Substantial wrist presence, very comfortable clasp',
                    'comment' => 'The butterfly clasp clicks with a reassuring solid snap. Only needed to remove two links which took 5 minutes with the included guidance. Great value.',
                    'verified' => 1,
                    'helpful' => 7,
                    'admin_reply' => null,
                    'images' => null
                ]
            ];

            foreach ($sampleReviews as $sr) {
                Database::execute(
                    "INSERT INTO reviews (product_id, author_name, rating, title, comment, is_verified_purchase, status, helpful_count, images_json, admin_response, created_at) VALUES (?, ?, ?, ?, ?, ?, 'approved', ?, ?, ?, NOW())",
                    [$pId, $sr['author'], $sr['rating'], $sr['title'], $sr['comment'], $sr['verified'], $sr['helpful'], $sr['images'], $sr['admin_reply']]
                );
            }
        }
    }

    echo "A+ product page data and reviews seeded successfully!\n";
} catch (\Throwable $e) {
    echo "Seeding error: " . $e->getMessage() . "\n";
    exit(1);
}
