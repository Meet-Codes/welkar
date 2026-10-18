<?php

namespace App\Controllers\Storefront;

use App\Support\Request;
use App\Support\View;
use App\Support\Database;
use App\Support\Session;
use App\Support\Response;

use App\Services\Company\CompanyProfileService;

class PageController
{
    public function show(Request $request, string $slug): string
    {
        if ($slug === 'about-us') {
            Response::redirect('/about', 301);
        }
        if ($slug === 'about') {
            return $this->about($request);
        }

        $locale = Session::get('locale', 'en');
        $page = Database::fetch(
            "SELECT * FROM content_pages WHERE slug = ? AND (locale = ? OR locale = 'en') AND is_published = 1 ORDER BY (locale = ?) DESC LIMIT 1",
            [$slug, $locale, $locale]
        );

        if (!$page) {
            http_response_code(404);
            return View::render('errors.404', ['title' => 'Page Not Found'], 'layouts.storefront');
        }

        $title = ($page['meta_title'] ?: $page['title']) . ' | Welkar';
        $desc = $page['meta_description'] ?: ('Read official ' . strtolower($page['title']) . ' at Welkar. Transparent terms, authentic timepieces, and customer trust.');

        $pageSchemas = [
            \App\Services\Seo\SeoService::getBreadcrumbSchema([
                ['name' => $page['title'], 'url' => '/page/' . $slug]
            ])
        ];

        // Add FAQPage schema for FAQ page
        if ($slug === 'faqs') {
            $faqs = [
                ['question' => 'Are all watches sold on Welkar 100% original and authentic?', 'answer' => 'Yes. Every timepiece sold on Welkar is 100% authentic, brand-new, and sourced directly from authorized manufacturers and brand partners with official warranty cards.'],
                ['question' => 'Is Cash on Delivery (COD) available across India?', 'answer' => 'Yes, Cash on Delivery (COD) is available for eligible delivery pincodes across India for orders up to ₹5,000 with zero upfront payment.'],
                ['question' => 'What is Welkar\'s return and warranty policy?', 'answer' => 'All Welkar timepieces come with a complimentary 2-Year movement warranty and a 7-day hassle-free return or replacement window from delivery date.'],
                ['question' => 'How does the Live Watch dial simulation work?', 'answer' => 'On supported models, our live watch view synchronizes the hour, minute, and second hands on the dial with your local device time in real-time.']
            ];
            $pageSchemas[] = \App\Services\Seo\SeoService::getFaqSchema($faqs);
        }

        $seo = [
            'title' => $title,
            'description' => $desc,
            'canonical' => \App\Services\Seo\SeoService::buildCanonicalUrl('/page/' . $slug),
            'schema' => $pageSchemas
        ];

        return View::render('storefront.page', [
            'title' => $title,
            'seo' => $seo,
            'page' => $page,
        ], 'layouts.storefront');
    }

    public function trackOrder(Request $request): string
    {
        $orderNumber = trim((string)$request->query('order_number', ''));
        $phone = trim((string)$request->query('phone', ''));
        $order = null;
        $shipment = null;

        if (!empty($orderNumber) && !empty($phone)) {
            $order = Database::fetch(
                "SELECT * FROM orders WHERE order_number = ? AND guest_phone LIKE ?",
                [$orderNumber, '%' . substr($phone, -10)]
            );
            if ($order) {
                $shipment = Database::fetch("SELECT * FROM shipping_shipments WHERE order_id = ? ORDER BY id DESC LIMIT 1", [$order['id']]);
            }
        }

        $seo = [
            'title' => 'Track Your Watch Delivery | Welkar',
            'description' => 'Real-time order tracking for Welkar timepieces across India via Bluedart, Delhivery, and Xpressbees.',
            'canonical' => \App\Services\Seo\SeoService::buildCanonicalUrl('/track-order'),
            'robots' => empty($orderNumber) ? 'index, follow' : 'noindex, nofollow', // Only index static landing page, protect query searches
            'schema' => \App\Services\Seo\SeoService::getBreadcrumbSchema([
                ['name' => 'Track Order', 'url' => '/track-order']
            ])
        ];

        return View::render('storefront.track_order', [
            'title' => 'Track Your Watch Delivery | Welkar',
            'seo' => $seo,
            'order' => $order,
            'shipment' => $shipment,
            'searched' => (!empty($orderNumber)),
        ], 'layouts.storefront');
    }

    public function about(Request $request): string
    {
        $profile = CompanyProfileService::getProfile();
        $founders = CompanyProfileService::getFounders();
        $values = CompanyProfileService::getValues();
        $milestones = CompanyProfileService::getMilestones();

        $title = $profile['seo_title'] ?: 'About WELKAR | Precision, Heritage, and Trust—Since 2002';
        $desc = $profile['seo_description'] ?: 'Discover the story of WELKAR, founded in 2002 by Mr. Sumit Singh and Mr. Nevil Sharma on precision, heritage, and customer trust.';

        $seo = [
            'title' => $title,
            'description' => $desc,
            'canonical' => \App\Services\Seo\SeoService::buildCanonicalUrl('/about'),
            'schema' => [
                \App\Services\Seo\SeoService::getOrganizationSchema(),
                \App\Services\Seo\SeoService::getBreadcrumbSchema([
                    ['name' => 'About Us', 'url' => '/about']
                ])
            ]
        ];

        return View::render('storefront.about', [
            'title' => $title,
            'meta_description' => $desc,
            'seo' => $seo,
            'profile' => $profile,
            'founders' => $founders,
            'values' => $values,
            'milestones' => $milestones,
            // Explicitly NO FAQs passed - About Us is strictly excluded from FAQ sections
        ], 'layouts.storefront');
    }

    public function sitemap(): void
    {
        header('Content-Type: application/xml; charset=utf-8');
        $file = __DIR__ . '/../../../public/sitemap.xml';
        if (file_exists($file)) {
            readfile($file);
            exit;
        }
        http_response_code(404);
        exit;
    }

    public function robots(): void
    {
        header('Content-Type: text/plain; charset=utf-8');
        $file = __DIR__ . '/../../../public/robots.txt';
        if (file_exists($file)) {
            readfile($file);
            exit;
        }
        http_response_code(404);
        exit;
    }
}
