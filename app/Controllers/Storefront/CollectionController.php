<?php

namespace App\Controllers\Storefront;

use App\Support\Request;
use App\Support\View;
use App\Support\Response;
use App\Services\Catalog\CatalogService;

class CollectionController
{
    private static array $collections = [
        'mens-watches' => [
            'slug' => 'mens-watches',
            'title' => "Men's Luxury & Chronograph Watches | Welkar",
            'heading' => "Men's Timepieces",
            'gender' => 'Men',
            'tag' => "MEN'S ATELIER COLLECTION",
            'hero_title' => 'Distinction in Every Caliber',
            'hero_desc' => 'From surgical-grade 316L black PVD chronographs to executive day-date analog timepieces, engineered for boardroom precision and everyday resilience.',
            'badge' => '316L Stainless Steel &bull; 30M Waterproof',
            'styles' => [
                ['name' => 'Round Chrono', 'icon' => 'bi-stopwatch', 'desc' => 'Motorsport & dual-calendar tachymeter styling'],
                ['name' => 'Square Dial', 'icon' => 'bi-square', 'desc' => 'Rounded architectural geometric silhouette'],
                ['name' => 'Minimalist Stick', 'icon' => 'bi-dash-lg', 'desc' => 'Understated baton indices for black-tie affairs'],
            ],
            'guide_title' => "Men's Horological Buying Guide",
            'guide_content' => [
                'Case Diameter' => '42mm to 44mm is the optimal universal proportion for masculine wrist presence without overwhelming cuff comfort.',
                'Hydrostatic Seal' => 'Double-gasket silicone crown ensures complete resilience against rain, accidental splashes, and humidity.',
                'Movement Type' => 'Japanese Quartz calibers calibrated to +/-15 seconds per month accuracy with zero winding maintenance.',
            ],
            'faqs' => [
                ['q' => 'Can I wear my Welkar Men\'s watch while swimming?', 'a' => 'Our 30M / 3 ATM watches are tested for rain, handwashing, and water splashes. For regular swimming, choose our 50M Hydro-Lab series.'],
                ['q' => 'Are the stainless steel links adjustable?', 'a' => 'Yes, all solid 316L stainless steel bracelets include removable link pins and can be sized at home or by any local jeweler.'],
                ['q' => 'What is the warranty coverage?', 'a' => 'All Welkar men\'s watches come with a 2-Year movement warranty with complimentary replacement pickup across India.'],
            ]
        ],
        'womens-watches' => [
            'slug' => 'womens-watches',
            'title' => "Women's Elegant Watches & Milanese Mesh | Welkar",
            'heading' => "Women's Timepieces",
            'gender' => 'Women',
            'tag' => "WOMEN'S GRACE ATELIER",
            'hero_title' => 'Understated Glamour & Featherlight Grace',
            'hero_desc' => 'Petite 28mm to 30mm silhouettes, woven Milanese magnetic mesh, and pavé crystal hour markers designed to weigh under 48 grams for all-day elegance.',
            'badge' => 'Featherlight &bull; Hypoallergenic Ion Plating',
            'styles' => [
                ['name' => 'Milanese Mesh', 'icon' => 'bi-grid-3x3', 'desc' => 'Smooth woven steel with infinitely adjustable magnetic clasp'],
                ['name' => 'Jewellery Bracelet', 'icon' => 'bi-gem', 'desc' => 'Polished linked jewelry bracelets for celebratory occasions'],
                ['name' => 'Sunray Dial', 'icon' => 'bi-brightness-high', 'desc' => 'Refractive metallic faces that shimmer under natural light'],
            ],
            'color_swatches' => [
                ['name' => 'Rose Gold', 'color' => '#b76e79'],
                ['name' => 'Champagne Gold', 'color' => '#d4af37'],
                ['name' => 'Pure Silver', 'color' => '#c0c0c0'],
                ['name' => 'Blush Pink', 'color' => '#ffb6c1'],
            ],
            'guide_title' => "Women's Styling & Sizing Guide",
            'guide_content' => [
                'Petite 28mm Silhouette' => 'Sits flush and delicate on wrists between 5.0" to 6.8", blending seamlessly with stacked bracelets.',
                'Magnetic Milanese Mesh' => 'Zero buckle holes required. The neodymium magnetic end-clasp locks anywhere along the band for a bespoke fit.',
                'Hypoallergenic Ion Plating' => 'Nickel-free multi-layer PVD plating prevents skin irritation and sweat discoloration in tropical climates.',
            ],
            'faqs' => [
                ['q' => 'Is the Milanese magnetic strap secure for active movement?', 'a' => 'Yes, our reinforced neodymium magnetic closure is tested to resist standard hand gestures and daily wear without slipping.'],
                ['q' => 'Does the rose gold plating fade over time?', 'a' => 'We apply high-density physical vapor deposition (PVD) gold bonding that is 3x more resistant to abrasion than standard electroplating.'],
            ]
        ],
        'kids-youth-watches' => [
            'slug' => 'kids-youth-watches',
            'title' => "Kids & Youth Watches — Shock-Resistant & Active Sports | Welkar",
            'heading' => "Kids & Youth Watches",
            'gender' => 'Kids',
            'tag' => "JUNIOR & YOUTH SERIES",
            'hero_title' => 'Built Tough for Young Explorers',
            'hero_desc' => '50M swim-proof dual digital-analog displays, shatter-proof mineral crystal, non-toxic skin-safe silicone straps, and 7-color ambient night lights.',
            'badge' => '50M Swim-Proof &bull; Non-Toxic Silicone &bull; Drop-Safe',
            'styles' => [
                ['name' => 'Dual Digital-Analog', 'icon' => 'bi-display', 'desc' => '1/100s split lap timer with analog hands and digital clock'],
                ['name' => 'Shock-Guard LED', 'icon' => 'bi-lightning-charge', 'desc' => 'Reinforced bumper bezel with 7-color night light chimes'],
            ],
            'guide_title' => "Parent & Safety Information Guide",
            'guide_content' => [
                'BPA-Free Food-Grade Silicone' => 'Gentle on sensitive junior skin, washable with soap and water after playground dirt.',
                'Reinforced Drop-Safe Resin' => 'Engineered with bumper ridges that absorb impact if dropped onto pavement or tile floors.',
                'Dual Timekeeping Education' => 'Encourages young learners to master both traditional analog clock hands and digital 24-hr time.',
            ],
            'faqs' => [
                ['q' => 'Can my child wear this watch while swimming or in the bath?', 'a' => 'Yes, all Welkar Kids & Youth watches are sealed to 50M / 5 ATM, making them fully safe for swimming, showers, and rain.'],
                ['q' => 'What age range are these watches suited for?', 'a' => 'Our Junior series is optimized for ages 5 to 11 (36mm case), while the Youth series is designed for ages 10 to 17 (38mm case).'],
            ]
        ],
        'unisex-minimalist' => [
            'slug' => 'unisex-minimalist',
            'title' => "Unisex & Minimalist Bauhaus Watches | Welkar",
            'heading' => "Unisex & Minimalist",
            'gender' => 'Unisex',
            'tag' => "MONOLITH BAUHAUS MODERNITY",
            'hero_title' => 'Purity of Form. Essential Function.',
            'hero_desc' => 'Architectural 38mm and 39mm numberless faces, flush crowns, and monolithic 7.2mm ultra-slim profiles crafted for universal aesthetics on any wrist.',
            'badge' => '7.2mm Ultra-Slim &bull; Numberless Bauhaus Face',
            'styles' => [
                ['name' => 'Square Monolith', 'icon' => 'bi-square-fill', 'desc' => 'Deep black sunburst face with flush crown and rounded corners'],
                ['name' => 'Bauhaus Minimalist', 'icon' => 'bi-circle', 'desc' => 'Matte white numberless face with surgical needle hands'],
            ],
            'guide_title' => "The Minimalist Design Philosophy",
            'guide_content' => [
                'Monolithic Profile' => 'At only 7.2mm thickness, the casing slips effortlessly beneath tailored cuffs, sweaters, or casual denim.',
                'Universal Proportions' => 'Calibrated 38mm to 39mm case dimensions provide balanced harmony on wrists from 5.5 inches to 7.5 inches.',
                'Couple & Matching Pairing' => 'Pairs effortlessly across couples looking for cohesive, non-ostentatious luxury timepieces.',
            ],
            'faqs' => [
                ['q' => 'Is this watch truly unisex?', 'a' => 'Yes, our 38mm and 39mm casing sizes were selected according to international horological ergonomic standards to look natural on both men and women.'],
                ['q' => 'Are the quick-release straps interchangeable?', 'a' => 'Yes, with standard 20mm lug widths and quick-release pins, you can swap between mesh and leather in under 10 seconds without tools.'],
            ]
        ],
        'new-arrivals' => [
            'slug' => 'new-arrivals',
            'title' => "New Arrivals & Latest Drops | Welkar Timepieces",
            'heading' => "New Arrivals",
            'filter_type' => 'new-arrivals',
            'tag' => "DROP #04 &bull; 2026 COLLECTION",
            'hero_title' => 'Fresh Releases from the Welkar Atelier',
            'hero_desc' => 'Be the first to experience our newest precision Japanese quartz calibers, limited dial colorways, and hydrostatic innovations.',
            'badge' => 'Limited Capsule Quantities &bull; Express Dispatch',
            'styles' => [],
            'guide_title' => "Editorial Capsule Spotlight",
            'guide_content' => [
                'Drop #04 Curation' => 'Features the Welkar Stealth Square series and the new Hydro-Lab Submersion Master.',
                'Laser-Engraved Backplates' => 'Every new arrival includes individual batch serial numbers stamped into the 316L back plate.',
            ],
            'faqs' => [
                ['q' => 'How often do new capsule drops release?', 'a' => 'Welkar produces seasonal limited batches once every 6 to 8 weeks in numbered quantities.'],
            ]
        ],
        'offers' => [
            'slug' => 'offers',
            'title' => "Special Offers & Festive Promotions | Welkar",
            'heading' => "Offers & Special Deals",
            'filter_type' => 'offers',
            'tag' => "EXCLUSIVE VALUE PRICING",
            'hero_title' => 'Curated Timepieces at Unmatched Values',
            'hero_desc' => 'Discover genuine discounts up to 45% off original MRP, bundled travel sets, and festive coupon codes. Inclusive of complimentary insured transit.',
            'badge' => 'Save Up to 45% &bull; Extra 10% with WELKAR10',
            'active_coupons' => [
                ['code' => 'WELKAR10', 'discount' => '10% OFF', 'min' => 'Orders over ₹999', 'badge' => 'POPULAR'],
                ['code' => 'FIRST500', 'discount' => '₹500 OFF', 'min' => 'Orders over ₹3,500', 'badge' => 'FESTIVE'],
            ],
            'guide_title' => "Promotions & Warranty Transparency",
            'guide_content' => [
                'Genuine MRP Comparison' => 'All compare-at prices reflect verified retail benchmark pricing. We never inflate original prices to fake discounts.',
                'Full 2-Year Warranty Included' => 'Promotional items receive the exact same 2-year warranty and 7-day return privileges as full-price items.',
            ],
            'faqs' => [
                ['q' => 'Can I use coupon code WELKAR10 on discounted watches?', 'a' => 'Yes! WELKAR10 stacks on top of already-discounted watch prices at checkout.'],
                ['q' => 'Is Cash on Delivery available on discounted orders?', 'a' => 'Yes, Cash on Delivery is supported on all orders up to ₹5,000 across 26,000+ Indian pincodes.'],
            ]
        ],
        'luxury-watches' => [
            'slug' => 'luxury-watches',
            'title' => "Luxury & Automatic Skeleton Timepieces | Welkar",
            'heading' => "Luxury Timepieces",
            'category' => 'luxury-watches',
            'tag' => "HAUTE HORLOGERIE & MECHANICAL CALIBERS",
            'hero_title' => 'Artistry, Skeleton Calibers & Lasting Distinction',
            'hero_desc' => 'Explore self-winding automatic mechanical movements, exhibition casebacks, and sapphire-coated crystals crafted for collectors who revere mechanical horology.',
            'badge' => 'Self-Winding Mechanical Calibers &bull; Exhibition Casebacks',
            'styles' => [
                ['name' => 'Skeleton Movement', 'icon' => 'bi-gear-wide-connected', 'desc' => 'Intricate open-work gears visible through exhibition crystal'],
                ['name' => 'Roman Dress Dial', 'icon' => 'bi-award', 'desc' => 'Hand-applied Roman numerals on guilloché champagne dials'],
                ['name' => 'Surgical Steel Chrono', 'icon' => 'bi-stopwatch', 'desc' => 'Multi-dial sub-second chronograph registers'],
            ],
            'guide_title' => "Collector's Guide to Premium & Automatic Timepieces",
            'guide_content' => [
                'Automatic Caliber Mechanics' => 'Unlike quartz watches that require batteries, automatic movements harness the natural movement of your wrist via an oscillating rotor to wind the mainspring.',
                'Exhibition Exhibition Glass' => 'Transparent casebacks allow enthusiasts to inspect escapement balance wheels, jewels, and gear trains operating in real-time.',
                'Material Integrity' => 'Welkar luxury selections employ high-density 316L stainless steel, PVD electro-chemical gold bonding, and double-domed mineral crystals.',
            ],
            'faqs' => [
                ['q' => 'What should I consider before buying a premium automatic watch?', 'a' => 'Key factors include the caliber type (automatic self-winding vs. battery-powered quartz), case diameter (42-44mm for executive presence), water resistance rating, and warranty documentation.'],
                ['q' => 'How do I compare watch materials and specifications?', 'a' => 'Look for 316L solid stainless steel casing, genuine top-grain leather or solid link bracelets, and minimum 30M to 50M hydrostatic gasket sealing.'],
                ['q' => 'What does the term luxury watch mean at Welkar?', 'a' => 'At Welkar, luxury represents genuine horological craftsmanship—such as self-winding mechanical calibers with exhibition casebacks and hand-finished dials—without inflated intermediary markups.'],
                ['q' => 'What documentation and warranty information should I review?', 'a' => 'Every premium watch includes an official serialized warranty card covering internal movement precision for 1 to 2 years, with doorstep pickup across India.'],
            ]
        ],
        'sports-watches' => [
            'slug' => 'sports-watches',
            'title' => "Sports & Tactical Chronograph Watches | Welkar",
            'heading' => "Sports & Tactical Timepieces",
            'category' => 'sports-watches',
            'tag' => "HYDRO-RESILIENT & TACTICAL SERIES",
            'hero_title' => 'Engineered for Performance, Velocity & Resilience',
            'hero_desc' => 'From dual digital-analog tactical chronographs to high-impact silicone racer watches, tested to withstand active workouts, dust, and rain.',
            'badge' => '50M Water Resistance &bull; Shock-Guard Resin &bull; Split-Lap Timers',
            'styles' => [
                ['name' => 'Tachymeter Racer', 'icon' => 'bi-speedometer2', 'desc' => 'Calibrated bezel scale to compute speed over distance'],
                ['name' => 'Dual Digital-Analog', 'icon' => 'bi-display', 'desc' => 'High-contrast LCD digital screen with luminous analog hands'],
                ['name' => 'Perforated Sport Strap', 'icon' => 'bi-activity', 'desc' => 'Breathable sweat-resistant silicone straps for workouts'],
            ],
            'guide_title' => "Sports Watch Water Resistance & Performance Guide",
            'guide_content' => [
                'Hydrostatic Seal Ratings' => '30M (3 ATM) watches handle rain and handwashing. 50M (5 ATM) watches are sealed for surface swimming, recreational water sports, and showers.',
                'Shock-Resistant Housing' => 'Impact-absorbing resin bumpers and recessed crowns safeguard internal calibers from sudden drops or wrist impact during sports.',
                'Tachymeter Bezel Utility' => 'Allows runners, cyclists, and motorsport enthusiasts to measure elapsed time and calculate average speed per kilometer.',
            ],
            'faqs' => [
                ['q' => 'What should I consider when choosing a sports watch?', 'a' => 'Prioritize water resistance (minimum 50M for swimming), shock resistance, strap material (sweat-proof silicone or resin), and display readability under bright sunlight.'],
                ['q' => 'What is the difference between analog and digital sports watches?', 'a' => 'Analog sports watches offer classic tachymeter dial styling, while digital and dual-display models include split-lap stopwatches, countdown alarms, and LED backlights.'],
                ['q' => 'Can every water-resistant watch be used for swimming?', 'a' => 'No. Watches rated at 30M / 3 ATM are tested for rain and splashes only. Only watches rated at 50M (5 ATM) or higher with dual silicone seals should be worn for swimming. Never operate buttons underwater.'],
                ['q' => 'How should I maintain a sports watch after intense workouts?', 'a' => 'Rinse silicone and resin bands with fresh water after heavy perspiration or pool exposure, and pat dry with a soft cloth to avoid salt or chlorine buildup.'],
            ]
        ]
    ];

    public function show(Request $request, string $slug): string
    {
        if ($slug === 'unisex-watches' && !isset(self::$collections['unisex-watches'])) {
            $slug = 'unisex-minimalist';
        }

        if (!isset(self::$collections[$slug])) {
            http_response_code(404);
            return View::render('errors.404', ['title' => 'Collection Not Found | Welkar'], 'layouts.storefront');
        }

        $config = self::$collections[$slug];

        $filters = [
            'search' => $request->query('q'),
            'category' => $request->query('category', $config['category'] ?? null),
            'brand' => $request->query('brand'),
            'dial_type' => $request->query('dial_type'),
            'gender' => $request->query('gender', $config['gender'] ?? null),
            'min_price' => $request->query('min_price'),
            'max_price' => $request->query('max_price'),
            'sort' => $request->query('sort', ($config['filter_type'] ?? '') === 'new-arrivals' ? 'newest' : 'featured'),
        ];

        // If offers, prioritize discounted items
        if (($config['filter_type'] ?? '') === 'offers') {
            $filters['is_discounted'] = true;
        }

        $page = max(1, (int)$request->query('page', 1));
        $result = CatalogService::getFilteredProducts($filters, $page, 12);

        $categories = CatalogService::getCategories();
        $brands = CatalogService::getBrands();
        $dialTypes = CatalogService::getDialTypes();

        $canonicalUrl = \App\Services\Seo\SeoService::buildCanonicalUrl('/collections/' . $slug);
        $seo = [
            'title' => $config['title'],
            'description' => $config['hero_desc'] ?? 'Explore ' . $config['heading'] . ' at Welkar. Curated timepieces with authentic warranty and express pan-India shipping.',
            'canonical' => $canonicalUrl,
            'schema' => \App\Services\Seo\SeoService::getBreadcrumbSchema([
                ['name' => 'Collections', 'url' => '/catalog'],
                ['name' => $config['heading'], 'url' => '/collections/' . $slug]
            ])
        ];

        return View::render('storefront.collection', [
            'title' => $config['title'],
            'seo' => $seo,
            'config' => $config,
            'products' => $result['products'],
            'total' => $result['total'],
            'page' => $result['page'],
            'last_page' => $result['last_page'],
            'filters' => $filters,
            'categories' => $categories,
            'brands' => $brands,
            'dialTypes' => $dialTypes,
            'faqs' => $config['faqs'] ?? []
        ], 'layouts.storefront');
    }
}
