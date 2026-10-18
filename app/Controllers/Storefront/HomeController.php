<?php

namespace App\Controllers\Storefront;

use App\Support\Request;
use App\Support\View;
use App\Support\Database;
use App\Services\Catalog\CatalogService;

class HomeController
{
    public function index(Request $request): string
    {
        $banners = Database::query(
            "SELECT * FROM banners WHERE is_active = 1 AND (starts_at IS NULL OR starts_at <= NOW()) AND (ends_at IS NULL OR ends_at >= NOW()) ORDER BY display_order ASC"
        );

        $categories = CatalogService::getCategories();
        $dialTypes = CatalogService::getDialTypes();
        $featuredProducts = CatalogService::getFeaturedProducts(12);
        $bestSellers = CatalogService::getBestSellers(12);
        $newArrivals = CatalogService::getNewArrivals(12);
        $brands = CatalogService::getBrands();
        $testimonials = Database::query("SELECT * FROM testimonials WHERE is_active = 1 ORDER BY display_order ASC LIMIT 3");
        
        $approvedReviews = Database::fetchAll(
            "SELECT r.*, p.name AS product_name, p.slug AS product_slug,
                    (SELECT image_url FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) AS product_primary_image
             FROM reviews r
             JOIN products p ON r.product_id = p.id
             WHERE r.status = 'approved'
             ORDER BY r.id DESC
             LIMIT 16"
        );

        $homeFaqs = [
            [
                'q' => 'What types of watches can I shop for at Welkar?',
                'a' => 'Welkar offers an India-focused, multi-brand collection including classic analog dress watches, chronographs, automatic skeleton timepieces, sports tachymeters, and dual digital-analog timepieces across trusted brands and private-label editions.'
            ],
            [
                'q' => 'Does Welkar offer men\'s, women\'s, and unisex watches?',
                'a' => 'Yes. We curate dedicated collections for Men (42-44mm case diameters), Women (28-30mm petite and Milanese mesh), Unisex & Minimalist (38-39mm Bauhaus silhouettes), and Junior/Youth shock-resistant models.'
            ],
            [
                'q' => 'How can I find the right watch for my style?',
                'a' => 'Use our catalog filters to explore by movement type (Quartz or Automatic), dial geometry (Round, Square, Skeleton), strap material (316L Stainless Steel, Genuine Leather, Silicone), and occasion (Formal, Casual, Sports).'
            ],
            [
                'q' => 'How do I choose a watch within my budget?',
                'a' => 'Welkar focuses on the accessible to premium ₹1,000 to ₹10,000 range. You can filter products by budget or browse our curated offers collection for verified discounts without inflated list prices.'
            ],
            [
                'q' => 'Where can I find product specifications?',
                'a' => 'Every product page details exact case diameter in millimeters, case material, movement caliber, strap dimensions, hydrostatic water resistance rating, and warranty coverage.'
            ],
            [
                'q' => 'Which payment methods are available?',
                'a' => 'We accept Cash on Delivery (COD) across eligible Indian pin codes, UPI (Google Pay, PhonePe, Paytm), Net Banking, and major Debit/Credit Cards via secure Razorpay checkout.'
            ],
            [
                'q' => 'Where can I find shipping and delivery information?',
                'a' => 'All orders include complimentary insured transit with doorstep tracking via Bluedart, Delhivery, or Xpressbees within 3 to 5 business days across India. Full details are available on our Shipping Policy page.'
            ],
            [
                'q' => 'How can I contact Welkar about a product?',
                'a' => 'You can reach our customer concierge via email at welkarstore@gmail.com or through our live WhatsApp Concierge desk for assistance with sizing, styling, and order status.'
            ]
        ];

        $seo = [
            'title' => 'Welkar | Watches for Every Style',
            'description' => 'Discover curated men\'s, women\'s, unisex, luxury, and sports watches at Welkar. Authentic timepieces, transparent specifications, and insured pan-India delivery since 2002.',
            'canonical' => \App\Services\Seo\SeoService::buildCanonicalUrl('/'),
        ];

        return View::render('storefront.home', [
            'title' => 'Welkar | Watches for Every Style',
            'seo' => $seo,
            'banners' => $banners,
            'categories' => $categories,
            'dialTypes' => $dialTypes,
            'featuredProducts' => $featuredProducts,
            'bestSellers' => $bestSellers,
            'newArrivals' => $newArrivals,
            'brands' => $brands,
            'testimonials' => $testimonials,
            'approvedReviews' => $approvedReviews,
            'faqs' => $homeFaqs,
        ], 'layouts.storefront');
    }
}
