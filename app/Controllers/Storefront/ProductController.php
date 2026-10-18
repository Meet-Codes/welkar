<?php

namespace App\Controllers\Storefront;

use App\Support\Request;
use App\Support\View;
use App\Support\Response;
use App\Services\Catalog\CatalogService;
use App\Services\Analytics\AnalyticsService;

class ProductController
{
    public function show(Request $request, string $slug): string
    {
        $data = CatalogService::getProductBySlug($slug);
        if (!$data) {
            http_response_code(404);
            return View::render('errors.404', ['title' => 'Watch Not Found'], 'layouts.storefront');
        }

        // Track first party view_item analytics event
        AnalyticsService::logEvent('view_item', $request, [
            'product_id' => $data['product']['id'],
            'name' => $data['product']['name'],
            'price' => $data['product']['base_price']
        ]);

        $prod = $data['product'];
        $title = ($prod['meta_title'] ?: $prod['name']) . ' | Welkar';
        $desc = $prod['meta_description'] ?: (
            'Buy ' . $prod['name'] . ' at Welkar. Engineered with ' . ($prod['movement'] ?? 'precision') . ' caliber, ' . 
            ($prod['case_size_mm'] ? $prod['case_size_mm'] . 'mm case, ' : '') . 
            ($prod['water_resistance'] ? $prod['water_resistance'] . ', ' : '') . 
            'and authentic warranty with insured pan-India delivery.'
        );

        $primaryImage = null;
        if (!empty($data['images'])) {
            $primaryImage = $data['images'][0]['image_url'] ?? null;
        }

        $schemas = [
            \App\Services\Seo\SeoService::getProductSchema($prod, $data['images']),
            \App\Services\Seo\SeoService::getBreadcrumbSchema([
                ['name' => 'Catalog', 'url' => '/catalog'],
                ['name' => $prod['name'], 'url' => '/product/' . $slug]
            ])
        ];

        if (!empty($data['faqs'])) {
            $faqSchema = \App\Services\Seo\SeoService::getFaqSchema($data['faqs']);
            if (!empty($faqSchema)) {
                $schemas[] = $faqSchema;
            }
        }

        $seo = [
            'title' => $title,
            'description' => $desc,
            'canonical' => \App\Services\Seo\SeoService::buildCanonicalUrl('/product/' . $slug),
            'og_type' => 'product',
            'og_image' => $primaryImage ? (\App\Services\Seo\SeoService::getBaseUrl() . '/' . ltrim($primaryImage, '/')) : null,
            'schema' => $schemas
        ];

        return View::render('storefront.product', [
            'title' => $title,
            'seo' => $seo,
            'product' => $data['product'],
            'images' => $data['images'],
            'variants' => $data['variants'],
            'specifications' => $data['specifications'],
            'reviews' => $data['reviews'],
            'review_stats' => $data['review_stats'],
            'related' => $data['related'],
            'modules' => $data['modules'],
            'hotspots' => $data['hotspots'],
            'faqs' => $data['faqs'],
            'comparison' => $data['comparison'],
        ], 'layouts.storefront');
    }

    public function submitReview(Request $request, string $slug): mixed
    {
        $product = \App\Support\Database::fetch("SELECT id FROM products WHERE slug = ?", [$slug]);
        if (!$product) {
            return Response::json(['success' => false, 'message' => 'Product not found.'], 404);
        }

        $result = \App\Services\Reviews\ReviewService::submitReview([
            'product_id' => $product['id'],
            'author_name' => $request->input('author_name'),
            'avatar_url' => $request->input('avatar_url'),
            'rating' => $request->input('rating'),
            'title' => $request->input('title'),
            'comment' => $request->input('comment'),
            'order_number' => $request->input('order_number'),
            'wristshot_url' => $request->input('wristshot_url'),
        ]);

        if ($request->isAjax()) {
            return Response::json($result);
        }

        if ($result['success']) {
            flash('success', $result['message']);
        } else {
            flash('error', $result['message']);
        }
        return Response::redirect('/product/' . $slug . '#reviews-section');
    }

    public function voteHelpful(Request $request, string $id): mixed
    {
        $reviewId = (int)$id;
        $sessionId = \App\Support\Session::getId();
        $ip = $request->ip();

        $result = \App\Services\Reviews\ReviewService::voteHelpful($reviewId, $sessionId, $ip);
        return Response::json($result);
    }
}
