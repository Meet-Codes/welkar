<?php

declare(strict_types=1);

namespace App\Services\Seo;

use App\Support\Config;
use App\Support\Session;

class SeoService
{
    private const DEFAULT_DOMAIN = 'https://www.welkar.in';
    private const DEFAULT_TITLE = 'Welkar | Watches for Every Style';
    private const DEFAULT_DESCRIPTION = 'Discover curated men\'s, women\'s, unisex, luxury, and sports watches at Welkar. Authentic timepieces, transparent specifications, and pan-India insured delivery since 2002.';

    /**
     * Get base URL for canonical generation
     */
    public static function getBaseUrl(): string
    {
        $appUrl = (string)Config::get('app.url', self::DEFAULT_DOMAIN);
        if (str_contains($appUrl, 'localhost')) {
            // For production SEO rendering, use official domain
            return self::DEFAULT_DOMAIN;
        }
        return rtrim($appUrl, '/');
    }

    /**
     * Build canonical URL from path
     */
    public static function buildCanonicalUrl(string $path = '/'): string
    {
        $cleanPath = '/' . ltrim(parse_url($path, PHP_URL_PATH) ?? '/', '/');
        if ($cleanPath === '//' || $cleanPath === '') {
            $cleanPath = '/';
        }
        return self::getBaseUrl() . ($cleanPath === '/' ? '' : $cleanPath);
    }

    /**
     * Build metadata array for storefront layout
     */
    public static function buildMetadata(array $custom = []): array
    {
        $locale = Session::get('locale', 'en');
        $canonical = $custom['canonical'] ?? self::buildCanonicalUrl($_SERVER['REQUEST_URI'] ?? '/');
        
        $title = !empty($custom['title']) ? trim((string)$custom['title']) : self::DEFAULT_TITLE;
        if (!str_contains($title, 'Welkar') && !str_contains($title, 'WELKAR')) {
            $title .= ' | Welkar';
        }

        $description = !empty($custom['description']) ? trim((string)$custom['description']) : self::DEFAULT_DESCRIPTION;
        // Truncate to recommended 155-160 chars if overly long
        if (mb_strlen($description) > 165) {
            $description = mb_substr($description, 0, 162) . '...';
        }

        $ogImage = $custom['og_image'] ?? (self::getBaseUrl() . '/assets/images/welkar-og-banner.jpg');
        $ogType = $custom['og_type'] ?? 'website';
        $robots = $custom['robots'] ?? 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';

        // Alternate hreflangs
        $pathOnly = parse_url($canonical, PHP_URL_PATH) ?? '/';
        $hreflangEn = self::getBaseUrl() . $pathOnly;
        $hreflangHi = self::getBaseUrl() . $pathOnly . (str_contains($pathOnly, '?') ? '&' : '?') . 'lang=hi';

        return [
            'title' => $title,
            'description' => $description,
            'canonical' => $canonical,
            'robots' => $robots,
            'locale' => $locale === 'hi' ? 'hi_IN' : 'en_IN',
            'lang' => $locale,
            'og_type' => $ogType,
            'og_title' => $custom['og_title'] ?? $title,
            'og_description' => $custom['og_description'] ?? $description,
            'og_image' => $ogImage,
            'og_url' => $canonical,
            'twitter_card' => $custom['twitter_card'] ?? 'summary_large_image',
            'twitter_title' => $custom['twitter_title'] ?? $title,
            'twitter_description' => $custom['twitter_description'] ?? $description,
            'twitter_image' => $custom['twitter_image'] ?? $ogImage,
            'hreflang' => [
                'en' => $hreflangEn,
                'hi' => $hreflangHi,
                'x-default' => $hreflangEn
            ],
            'schema' => $custom['schema'] ?? []
        ];
    }

    /**
     * Generate Organization schema
     */
    public static function getOrganizationSchema(): array
    {
        $base = self::getBaseUrl();
        $legalName = (string)Config::get('app.business.legal_name', 'Welkar Timepieces Private Limited');
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'Welkar',
            'legalName' => $legalName,
            'url' => $base,
            'logo' => $base . '/favicon.png',
            'foundingDate' => '2002',
            'founders' => [
                [
                    '@type' => 'Person',
                    'name' => 'Mr. Sumit Singh',
                    'jobTitle' => 'Co-Founder'
                ],
                [
                    '@type' => 'Person',
                    'name' => 'Mr. Nevil Sharma',
                    'jobTitle' => 'Co-Founder'
                ]
            ],
            'slogan' => 'Precision, Heritage, and Trust—Since 2002.',
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'contactType' => 'customer service',
                'email' => 'welkarstore@gmail.com',
                'areaServed' => 'IN',
                'availableLanguage' => ['en', 'hi']
            ],
            'sameAs' => [
                'https://www.welkar.in'
            ]
        ];
    }

    /**
     * Generate WebSite schema with Sitelinks Searchbox
     */
    public static function getWebSiteSchema(): array
    {
        $base = self::getBaseUrl();
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => 'Welkar',
            'alternateName' => 'Welkar Watches India',
            'url' => $base,
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => $base . '/catalog?q={search_term_string}'
                ],
                'query-input' => 'required name=search_term_string'
            ]
        ];
    }

    /**
     * Generate BreadcrumbList schema
     */
    public static function getBreadcrumbSchema(array $crumbs): array
    {
        $base = self::getBaseUrl();
        $items = [];
        $pos = 1;

        // Always include Home as first crumb
        $items[] = [
            '@type' => 'ListItem',
            'position' => $pos++,
            'name' => 'Home',
            'item' => $base . '/'
        ];

        foreach ($crumbs as $crumb) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $pos++,
                'name' => (string)$crumb['name'],
                'item' => str_starts_with($crumb['url'], 'http') ? $crumb['url'] : ($base . '/' . ltrim($crumb['url'], '/'))
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items
        ];
    }

    /**
     * Generate Product with Offer schema
     */
    public static function getProductSchema(array $product, array $images = [], array $brand = []): array
    {
        $base = self::getBaseUrl();
        $prodUrl = $base . '/product/' . urlencode((string)$product['slug']);
        
        $imageUrls = [];
        if (!empty($images)) {
            foreach ($images as $img) {
                $u = $img['image_url'] ?? '';
                if (!empty($u)) {
                    $imageUrls[] = str_starts_with($u, 'http') ? $u : ($base . '/' . ltrim($u, '/'));
                }
            }
        }
        if (empty($imageUrls)) {
            $imageUrls[] = $base . '/assets/images/default-watch.jpg';
        }

        $brandName = $brand['name'] ?? ($product['brand_name'] ?? 'Welkar');
        $price = (float)($product['base_price'] ?? 0);
        $inStock = (int)($product['is_active'] ?? 1) === 1;

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => (string)$product['name'],
            'image' => $imageUrls,
            'description' => !empty($product['short_description']) 
                ? strip_tags((string)$product['short_description']) 
                : (!empty($product['description']) ? mb_substr(strip_tags((string)$product['description']), 0, 200) : $product['name']),
            'sku' => (string)($product['base_sku'] ?? 'WEL-' . $product['id']),
            'mpn' => (string)($product['model_number'] ?? $product['base_sku'] ?? 'WEL-' . $product['id']),
            'brand' => [
                '@type' => 'Brand',
                'name' => $brandName
            ],
            'offers' => [
                '@type' => 'Offer',
                'url' => $prodUrl,
                'priceCurrency' => 'INR',
                'price' => number_format($price, 2, '.', ''),
                'priceValidUntil' => date('Y-12-31', strtotime('+1 year')),
                'itemCondition' => 'https://schema.org/NewCondition',
                'availability' => $inStock ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'seller' => [
                    '@type' => 'Organization',
                    'name' => 'Welkar'
                ],
                'shippingDetails' => [
                    '@type' => 'OfferShippingDetails',
                    'shippingRate' => [
                        '@type' => 'MonetaryAmount',
                        'value' => '0.00',
                        'currency' => 'INR'
                    ],
                    'shippingDestination' => [
                        '@type' => 'DefinedRegion',
                        'addressCountry' => 'IN'
                    ],
                    'deliveryTime' => [
                        '@type' => 'ShippingDeliveryTime',
                        'handlingTime' => [
                            '@type' => 'QuantitativeValue',
                            'minValue' => 1,
                            'maxValue' => 2,
                            'unitCode' => 'DAY'
                        ],
                        'transitTime' => [
                            '@type' => 'QuantitativeValue',
                            'minValue' => 3,
                            'maxValue' => 5,
                            'unitCode' => 'DAY'
                        ]
                    ]
                ],
                'hasMerchantReturnPolicy' => [
                    '@type' => 'MerchantReturnPolicy',
                    'applicableCountry' => 'IN',
                    'returnPolicyCategory' => 'https://schema.org/MerchantReturnFiniteReturnWindow',
                    'merchantReturnDays' => 7,
                    'returnMethod' => 'https://schema.org/ReturnByMail',
                    'returnFees' => 'https://schema.org/FreeReturn'
                ]
            ]
        ];

        if (!empty($product['gender'])) {
            $schema['audience'] = [
                '@type' => 'PeopleAudience',
                'suggestedGender' => $product['gender']
            ];
        }

        // Additional product properties
        $additionalProperties = [];
        if (!empty($product['movement'])) {
            $additionalProperties[] = ['@type' => 'PropertyValue', 'name' => 'Movement', 'value' => $product['movement']];
        }
        if (!empty($product['case_size_mm'])) {
            $additionalProperties[] = ['@type' => 'PropertyValue', 'name' => 'Case Diameter', 'value' => $product['case_size_mm'] . 'mm'];
        }
        if (!empty($product['case_material'])) {
            $additionalProperties[] = ['@type' => 'PropertyValue', 'name' => 'Case Material', 'value' => $product['case_material']];
        }
        if (!empty($product['strap_material'])) {
            $additionalProperties[] = ['@type' => 'PropertyValue', 'name' => 'Strap Material', 'value' => $product['strap_material']];
        }
        if (!empty($product['water_resistance'])) {
            $additionalProperties[] = ['@type' => 'PropertyValue', 'name' => 'Water Resistance', 'value' => $product['water_resistance']];
        }
        if (!empty($additionalProperties)) {
            $schema['additionalProperty'] = $additionalProperties;
        }

        return $schema;
    }

    /**
     * Generate FAQPage schema from questions and answers
     */
    public static function getFaqSchema(array $faqs): array
    {
        if (empty($faqs)) {
            return [];
        }

        $mainEntity = [];
        foreach ($faqs as $faq) {
            $question = trim((string)($faq['question'] ?? ''));
            $answer = trim(strip_tags((string)($faq['answer'] ?? '')));
            if (!empty($question) && !empty($answer)) {
                $mainEntity[] = [
                    '@type' => 'Question',
                    'name' => $question,
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $answer
                    ]
                ];
            }
        }

        if (empty($mainEntity)) {
            return [];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $mainEntity
        ];
    }

    /**
     * Render JSON-LD script tags safely escaped
     */
    public static function renderJsonLd(array $schemas): string
    {
        if (empty($schemas)) {
            return '';
        }

        $html = '';
        foreach ($schemas as $schema) {
            if (empty($schema)) continue;
            $json = json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            // Defend against XSS in JSON-LD
            $json = str_replace(['<script', '</script'], ['\u003Cscript', '\u003C/script'], $json);
            $html .= '<script type="application/ld+json">' . "\n" . $json . "\n" . '</script>' . "\n";
        }
        return $html;
    }
}
