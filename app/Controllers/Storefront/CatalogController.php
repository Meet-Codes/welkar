<?php

namespace App\Controllers\Storefront;

use App\Support\Request;
use App\Support\View;
use App\Support\Response;
use App\Services\Catalog\CatalogService;

class CatalogController
{
    public function index(Request $request): string
    {
        $filters = [
            'search' => $request->query('q'),
            'category' => $request->query('category'),
            'brand' => $request->query('brand'),
            'dial_type' => $request->query('dial_type'),
            'gender' => $request->query('gender'),
            'min_price' => $request->query('min_price'),
            'max_price' => $request->query('max_price'),
            'sort' => $request->query('sort', 'featured'),
        ];

        $page = max(1, (int)$request->query('page', 1));
        $result = CatalogService::getFilteredProducts($filters, $page, 12);

        $categories = CatalogService::getCategories();
        $brands = CatalogService::getBrands();
        $dialTypes = CatalogService::getDialTypes();

        $isSearch = !empty($filters['search']);
        $title = $isSearch 
            ? 'Search Results for "' . htmlspecialchars((string)$filters['search']) . '" | Welkar' 
            : 'Curated Watch Catalog | Welkar';
        
        $desc = $isSearch
            ? 'Search results for watches matching "' . htmlspecialchars((string)$filters['search']) . '" at Welkar.'
            : 'Explore curated watches across men\'s, women\'s, unisex, luxury, and sports timepieces with transparent specifications, 1-2 year warranty, and insured pan-India delivery.';

        $seo = [
            'title' => $title,
            'description' => $desc,
            'canonical' => \App\Services\Seo\SeoService::buildCanonicalUrl('/catalog'),
            'robots' => $isSearch ? 'noindex, follow' : 'index, follow',
            'schema' => \App\Services\Seo\SeoService::getBreadcrumbSchema([
                ['name' => 'Catalog', 'url' => '/catalog']
            ])
        ];

        return View::render('storefront.catalog', [
            'title' => $title,
            'seo' => $seo,
            'products' => $result['products'],
            'total' => $result['total'],
            'page' => $result['page'],
            'last_page' => $result['last_page'],
            'filters' => $filters,
            'categories' => $categories,
            'brands' => $brands,
            'dialTypes' => $dialTypes,
        ], 'layouts.storefront');
    }

    public function brands(Request $request): string
    {
        $brands = \App\Support\Database::query(
            "SELECT b.*, COUNT(p.id) AS product_count 
             FROM brands b 
             LEFT JOIN products p ON b.id = p.brand_id AND p.is_active = 1
             WHERE b.is_active = 1
             GROUP BY b.id 
             ORDER BY b.is_featured DESC, b.name ASC"
        );

        $seo = [
            'title' => 'Watch Brands Portfolio | Titan, Casio, Fossil, Timex & Welkar',
            'description' => 'Explore authentic timepieces from renowned watchmakers including Titan, Casio, Fossil, Timex, and Welkar atelier editions. 100% verified specifications and official warranties.',
            'canonical' => \App\Services\Seo\SeoService::buildCanonicalUrl('/brands'),
            'schema' => \App\Services\Seo\SeoService::getBreadcrumbSchema([
                ['name' => 'Brands', 'url' => '/brands']
            ])
        ];

        return View::render('storefront.brands', [
            'title' => 'Watch Brands Portfolio | Welkar',
            'seo' => $seo,
            'brands' => $brands,
        ], 'layouts.storefront');
    }

    public function brand(Request $request, string $slug): string
    {
        $brand = \App\Support\Database::fetch("SELECT * FROM brands WHERE slug = ? AND is_active = 1", [$slug]);
        if (!$brand) {
            http_response_code(404);
            return View::render('errors.404', ['title' => 'Brand Not Found'], 'layouts.storefront');
        }

        // Forward to catalog with pre-selected brand filter
        $page = max(1, (int)$request->query('page', 1));
        $filters = [
            'brand' => $slug,
            'sort' => $request->query('sort', 'featured'),
        ];
        $result = CatalogService::getFilteredProducts($filters, $page, 12);

        $brandFaqs = [
            [
                'q' => 'Which products from ' . $brand['name'] . ' are available at Welkar?',
                'a' => 'Welkar curates authentic timepieces from ' . $brand['name'] . ' across analog, chronograph, and sports models. Each product listing reflects real-time stock availability and verified pricing.'
            ],
            [
                'q' => 'How can I compare watches from ' . $brand['name'] . '?',
                'a' => 'Use our side-by-side comparison tables on product pages to review case dimensions, glass crystals, movement types, and hydrostatic water ratings before purchasing.'
            ],
            [
                'q' => 'Where can I find the specifications of a model?',
                'a' => 'Click into any ' . $brand['name'] . ' timepiece to view technical specifications recorded directly from the manufacturer, including case size in mm, strap material, and caliber details.'
            ],
            [
                'q' => 'What warranty applies to ' . $brand['name'] . ' watches purchased on Welkar?',
                'a' => 'Every unit includes its verified movement warranty (typically 1 to 2 years) backed by manufacturer service networks and Welkar doorstep support across India.'
            ]
        ];

        $seo = [
            'title' => $brand['name'] . ' Watches Collection | Welkar',
            'description' => 'Shop authentic ' . $brand['name'] . ' watches at Welkar. Verified specifications, genuine manufacturer warranties, and insured pan-India delivery.',
            'canonical' => \App\Services\Seo\SeoService::buildCanonicalUrl('/brand/' . $slug),
            'schema' => \App\Services\Seo\SeoService::getBreadcrumbSchema([
                ['name' => 'Brands', 'url' => '/brands'],
                ['name' => $brand['name'], 'url' => '/brand/' . $slug]
            ])
        ];

        return View::render('storefront.catalog', [
            'title' => $brand['name'] . ' Watches Collection | Welkar',
            'seo' => $seo,
            'products' => $result['products'],
            'total' => $result['total'],
            'page' => $result['page'],
            'last_page' => $result['last_page'],
            'filters' => $filters,
            'categories' => CatalogService::getCategories(),
            'brands' => CatalogService::getBrands(),
            'dialTypes' => CatalogService::getDialTypes(),
            'brandHeader' => $brand,
            'faqs' => $brandFaqs,
        ], 'layouts.storefront');
    }

    public function autocomplete(Request $request): void
    {
        $query = $request->query('q', '');
        if (strlen(trim($query)) < 2) {
            Response::json(['results' => []]);
        }

        $results = CatalogService::searchAutocomplete($query, 6);
        Response::json(['results' => $results]);
    }
}
