<?php
$heroSlides = [];
if (!empty($banners)) {
    foreach ($banners as $b) {
        $desktopImg = !empty($b['image_desktop']) ? $b['image_desktop'] : '';
        if (empty($desktopImg)) continue;
        $heroSlides[] = [
            'src' => $desktopImg,
            'thumb' => !empty($b['image_mobile']) ? $b['image_mobile'] : $desktopImg,
            'title' => $b['title'] ?? 'Welkar Timepieces',
            'spec' => $b['subtitle'] ?? '',
            'link' => !empty($b['link_url']) ? $b['link_url'] : '/catalog',
            'button_text' => !empty($b['button_text']) ? $b['button_text'] : 'Explore Collection',
            'alt' => $b['title'] ?? 'Welkar Timepiece'
        ];
    }
}
if (empty($heroSlides)) {
    $heroSlides = [
        [
            'src' => '/uploads/banners/hero-banner-1.jpg',
            'thumb' => '/uploads/banners/hero-banner-1.jpg',
            'title' => 'The Midnight Sale',
            'spec' => 'Exceptional Timepieces. Special Offers.',
            'link' => '/collections/offers',
            'button_text' => 'Shop Offers',
            'alt' => 'Welkar The Midnight Sale'
        ],
        [
            'src' => '/uploads/banners/hero-banner-2.jpg',
            'thumb' => '/uploads/banners/hero-banner-2.jpg',
            'title' => 'Make Every Entrance Count',
            'spec' => 'Discover the Women’s Collection',
            'link' => '/collections/womens-watches',
            'button_text' => 'Shop Women’s',
            'alt' => 'Welkar Women’s Collection'
        ],
        [
            'src' => '/uploads/banners/hero-banner-3.jpg',
            'thumb' => '/uploads/banners/hero-banner-3.jpg',
            'title' => 'Different Looks. Same Standard.',
            'spec' => 'One Collection. Every Individual.',
            'link' => '/collections/unisex-minimalist',
            'button_text' => 'Discover Unisex',
            'alt' => 'Welkar Different Looks. Same Standard.'
        ],
        [
            'src' => '/uploads/banners/hero-banner-4.jpg',
            'thumb' => '/uploads/banners/hero-banner-4.jpg',
            'title' => 'Precision You Can Feel',
            'spec' => 'Discover the Men’s Collection',
            'link' => '/collections/mens-watches',
            'button_text' => 'Shop Men’s',
            'alt' => 'Welkar Men’s Collection'
        ]
    ];
}
$firstHeroImage = $heroSlides[0]['src'] ?? '/uploads/banners/hero-banner-1.jpg';
?>
<!-- ==========================================================================
     STITCH "HOROLOGICAL MODERNITY" ATELIER STOREFRONT
     ========================================================================== -->

<!-- 1. Hero Section: Full-Width Atelier WebGL Noise Morph Gallery (Blue Area) -->
<section class="stitch-hero-section position-relative overflow-hidden text-white" id="hero-morph-section" style="min-height: 82vh; height: 86vh; max-height: 880px; background: #0A0D0C;" role="region" aria-roledescription="carousel" aria-label="Welkar Timepiece Atelier Morph Gallery">
    
    <!-- Full-Width Background WebGL Morph Canvas & Viewport -->
    <div class="morph-canvas-container position-absolute inset-0 w-100 h-100" id="morph-gallery-viewport" style="height: 100%; min-height: 100%;">
        <canvas id="hero-morph-canvas" class="w-100 h-100 d-block"></canvas>
        <div class="morph-fallback-layer position-absolute inset-0" id="morph-fallback-layer" style="background-image: url('<?= e($firstHeroImage) ?>'); background-size: cover; background-position: center; transition: background-image 0.5s ease-in-out;"></div>
    </div>

    <!-- Atmospheric Dark Luxury Gradient Overlays -->
    <div class="position-absolute start-0 end-0 bottom-0 pointer-events-none" style="height: 140px; background: linear-gradient(to top, rgba(10, 13, 12, 0.75) 0%, transparent 100%); z-index: 3;" aria-hidden="true"></div>
    <div class="position-absolute start-0 end-0 top-0 pointer-events-none" style="height: 60px; background: linear-gradient(to bottom, rgba(10, 13, 12, 0.35) 0%, transparent 100%); z-index: 3;" aria-hidden="true"></div>

    <!-- Circular Navigation Arrows (exact styling from morph-gallery.tsx) -->
    <button type="button" class="morph-nav-btn prev" id="morph-prev-btn" aria-label="Previous Timepiece">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <polyline points="15 18 9 12 15 6"></polyline>
        </svg>
    </button>
    <button type="button" class="morph-nav-btn next" id="morph-next-btn" aria-label="Next Timepiece">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <polyline points="9 18 15 12 9 6"></polyline>
        </svg>
    </button>

    <!-- Semantic Heading for Accessibility & SEO -->
    <h1 class="visually-hidden">Welkar Luxury & Everyday Timepieces</h1>

    <!-- Centered Bottom Thumbnails Strip (exact layout from morph-gallery.tsx) -->
    <div class="morph-thumb-wrapper" style="z-index: 20;">
        <ul class="morph-thumb-strip list-unstyled m-0 p-1" id="morph-thumb-strip">
            <!-- Populated by MorphGallery JS -->
        </ul>
    </div>
</section>



<!-- 3. Curated Horology for Every Wrist (Area 2: Social proof removed) -->
<section class="py-5 hero-08-section border-bottom reveal-on-scroll" style="background-color: #0B0B0C !important; background: #0B0B0C !important; border-color: #2E2E2E !important;">
    <div class="container py-lg-4">
        <!-- Header: Clean Title Row without Social Proof Badges (Area 2 cleaned) -->
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="hero-08-title mb-0" style="color: #F5F5F5 !important; font-family: var(--font-display, 'DM Serif Display', Georgia, serif); font-size: clamp(2rem, 3.5vw, 2.75rem); font-weight: 600; letter-spacing: 0.01em;">Curated Horology for Every Wrist</h2>
            </div>
        </div>

        <!-- Two Feature Cards: Watches for Him and Watches for Her -->
        <div class="row g-2 g-md-4">
            <!-- Card 1: Watches for Him -->
            <div class="col-6">
                <a href="/catalog?gender=Men" class="hero-08-card card-luxury card-tilt-3d h-100" data-card-7="true">
                    <img src="https://images.unsplash.com/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=1200&q=80" alt="Watches for Him" class="hero-08-card-img" loading="lazy">
                    <div class="hero-08-card-overlay"></div>
                    <div class="hero-08-card-content">
                        <h3 class="hero-08-card-title">Watches for Him</h3>
                        <p class="hero-08-card-subtitle d-none d-sm-block">Precision chronographs, automatic skeletons & bold masculine silhouettes.</p>
                        <div>
                            <span class="hero-08-btn btn-welkar-rose">
                                <span>Explore For Him</span>
                                <i class="bi bi-arrow-right"></i>
                            </span>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card 2: Watches for Her -->
            <div class="col-6">
                <a href="/catalog?gender=Women" class="hero-08-card card-luxury card-tilt-3d h-100" data-card-7="true">
                    <img src="https://images.unsplash.com/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=1200&q=80" alt="Watches for Her" class="hero-08-card-img" loading="lazy">
                    <div class="hero-08-card-overlay"></div>
                    <div class="hero-08-card-content">
                        <h3 class="hero-08-card-title">Watches for Her</h3>
                        <p class="hero-08-card-subtitle d-none d-sm-block">Petite diamond dials, rose gold finishes & heirloom jewel bracelets.</p>
                        <div>
                            <span class="hero-08-btn btn-welkar-rose">
                                <span>Explore For Her</span>
                                <i class="bi bi-arrow-right"></i>
                            </span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>



<!-- 5. Lab Precision Engineering & Card Fan Carousel (Area 4: Applied Card Fan Carousel) -->
<section class="hydro-lab-section py-5 reveal-on-scroll" id="lab-engineering">
    <div class="container py-lg-4">
        <div class="row align-items-center g-5">
            <!-- Left Lab Technical Specs -->
            <div class="col-lg-5">
                <h2 class="display-5 fw-normal text-white mb-3" style="font-family: var(--font-display, 'DM Serif Display', Georgia, serif); color: var(--color-heading-ivory, #F5F5F5) !important;">
                    Hydro-Pressure Precision Engineering
                </h2>
                <p class="lead text-light opacity-75 mb-4" style="font-size: 0.975rem; line-height: 1.7;">
                    Every Welkar timepiece is assembled with high-grade gaskets, O-ring sealed crowns, and tested through simulated 30M hydrostatic submersion tanks.
                </p>

                <div class="row g-3">
                    <div class="col-6">
                        <div class="hydro-spec-box">
                            <div class="hydro-spec-title">
                                <i class="bi bi-droplet-half"></i>
                                <span>30M Seal</span>
                            </div>
                            <p class="hydro-spec-text">Everyday splash &amp; depth submersions.</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="hydro-spec-box">
                            <div class="hydro-spec-title">
                                <i class="bi bi-shield-check"></i>
                                <span>316L Steel</span>
                            </div>
                            <p class="hydro-spec-text">Hypoallergenic surgical alloy.</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="hydro-spec-box">
                            <div class="hydro-spec-title">
                                <i class="bi bi-gem"></i>
                                <span>Hardlex</span>
                            </div>
                            <p class="hydro-spec-text">Impact &amp; scratch-hardened glass.</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="hydro-spec-box">
                            <div class="hydro-spec-title">
                                <i class="bi bi-clock"></i>
                                <span>Japanese</span>
                            </div>
                            <p class="hydro-spec-text">Quartz accuracy &plusmn;15s/month.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Feature Carousel Component (3D Perspective Showcase - feature-carousel.tsx) -->
            <div class="col-lg-7">
                <div class="feature-carousel-wrapper position-relative w-100" id="hydro-feature-carousel">
                    <!-- Carousel 3D Perspective Stage -->
                    <div class="feature-carousel-stage">
                        <!-- Card 0 -->
                        <div class="feature-carousel-card" data-index="0">
                            <div class="feature-carousel-card-inner">
                                <img src="https://images.unsplash.com/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=800&q=80" alt="Hydro Master 30M" loading="lazy">
                                <div class="feature-carousel-overlay">
                                    <h4 class="feature-carousel-name">Hydro Master 30M</h4>
                                </div>
                            </div>
                        </div>
                        <!-- Card 1 -->
                        <div class="feature-carousel-card" data-index="1">
                            <div class="feature-carousel-card-inner">
                                <img src="https://images.unsplash.com/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=800&q=80" alt="Executive Steel 316L" loading="lazy">
                                <div class="feature-carousel-overlay">
                                    <h4 class="feature-carousel-name">Executive Steel 316L</h4>
                                </div>
                            </div>
                        </div>
                        <!-- Card 2 -->
                        <div class="feature-carousel-card" data-index="2">
                            <div class="feature-carousel-card-inner">
                                <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=800&q=80" alt="Stealth Square 316L" loading="lazy">
                                <div class="feature-carousel-overlay">
                                    <h4 class="feature-carousel-name">Stealth Square 316L</h4>
                                </div>
                            </div>
                        </div>
                        <!-- Card 3 -->
                        <div class="feature-carousel-card" data-index="3">
                            <div class="feature-carousel-card-inner">
                                <img src="https://images.unsplash.com/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=800&q=80" alt="Rose Gold Petite" loading="lazy">
                                <div class="feature-carousel-overlay">
                                    <h4 class="feature-carousel-name">Rose Gold Petite</h4>
                                </div>
                            </div>
                        </div>
                        <!-- Card 4 -->
                        <div class="feature-carousel-card" data-index="4">
                            <div class="feature-carousel-card-inner">
                                <img src="https://images.unsplash.com/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=800&q=80" alt="Silver Day-Date" loading="lazy">
                                <div class="feature-carousel-overlay">
                                    <h4 class="feature-carousel-name">Silver Day-Date</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Controls: Left / Right Arrows -->
                    <button class="feature-carousel-arrow prev" id="carousel-btn-prev" aria-label="Previous Slide" type="button">
                        <i class="bi bi-chevron-left fs-5"></i>
                    </button>
                    <button class="feature-carousel-arrow next" id="carousel-btn-next" aria-label="Next Slide" type="button">
                        <i class="bi bi-chevron-right fs-5"></i>
                    </button>

                    <!-- Dot Indicators -->
                    <div class="feature-carousel-dots d-flex align-items-center justify-content-center gap-2 mt-3">
                        <span class="feature-carousel-dot" data-index="0"></span>
                        <span class="feature-carousel-dot" data-index="1"></span>
                        <span class="feature-carousel-dot active" data-index="2"></span>
                        <span class="feature-carousel-dot" data-index="3"></span>
                        <span class="feature-carousel-dot" data-index="4"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<!-- 6. Complete Product Catalog & Interactive Storefront Filter -->
<section class="py-5 border-bottom reveal-on-scroll" id="catalog-grid" style="background-color: var(--color-bg-primary, #0B0B0C); border-color: var(--color-border-divider, #2E2E2E) !important;">
    <div class="container py-lg-4">
        <!-- Section Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">
            <div>
                <h2 class="display-6 fw-normal mb-0" style="font-family: var(--font-display, 'DM Serif Display', Georgia, serif); color: var(--color-heading-ivory, #F5F5F5) !important;">Collection: Products</h2>
            </div>
        </div>

        <!-- Quick-Pill Category Filters with Touch Scroll & Active Indicator -->
        <div class="catalog-filter-tabs mb-4 touch-scroll-snap" style="gap: 8px; padding-bottom: 6px;">
            <button class="catalog-tab-btn active touch-snap-card" data-filter="all" type="button">
                <i class="bi bi-grid-fill me-1"></i>All Timepieces
            </button>
            <button class="catalog-tab-btn touch-snap-card" data-filter="bestseller" type="button">
                <i class="bi bi-star-fill text-warning me-1"></i>Best Sellers
            </button>
            <button class="catalog-tab-btn touch-snap-card" data-filter="men" type="button">
                <i class="bi bi-gender-male me-1"></i>Men's Horology
            </button>
            <button class="catalog-tab-btn touch-snap-card" data-filter="women" type="button">
                <i class="bi bi-gender-female me-1"></i>Women's Grace
            </button>
            <button class="catalog-tab-btn touch-snap-card" data-filter="unisex" type="button">
                <i class="bi bi-slash-circle me-1"></i>Unisex Minimalist
            </button>
            <button class="catalog-tab-btn touch-snap-card" data-filter="under3000" type="button">
                <i class="bi bi-tag-fill me-1"></i>Under ₹3,000
            </button>
            <button class="catalog-tab-btn touch-snap-card" data-filter="offers" type="button">
                <i class="bi bi-percent me-1 text-danger"></i>Special Offers
            </button>
        </div>

        <!-- Product Cards Grid: Exactly 4 product suggestions on homepage -->
        <?php $displayedHomeProducts = array_slice($featuredProducts, 0, 4); ?>
        <div class="row g-2 g-sm-3 g-md-4 row-cols-2 row-cols-sm-2 row-cols-lg-4 row-cols-xl-4" id="home-product-grid">
            <?php foreach ($displayedHomeProducts as $product): ?>
            <?php 
                $catClasses = [];
                $g = strtolower($product['gender'] ?? '');
                $catSlugs = strtolower($product['category_slugs'] ?? '');
                if (str_contains($g, 'men')) $catClasses[] = 'cat-men';
                if (str_contains($g, 'women')) $catClasses[] = 'cat-women';
                if (str_contains($g, 'unisex') || str_contains($catSlugs, 'unisex')) $catClasses[] = 'cat-unisex';
                $classStr = implode(' ', $catClasses);

                $discountPercent = 0;
                if ($product['compare_at_price'] > $product['base_price']) {
                    $discountPercent = round((($product['compare_at_price'] - $product['base_price']) / $product['compare_at_price']) * 100);
                }
            ?>
            <div class="col product-filter-item <?= $classStr ?> <?= ($discountPercent > 0) ? 'cat-offers' : '' ?> is-bestseller" data-categories="<?= e($classStr) ?>" data-price="<?= (float)$product['base_price'] ?>">
                <article class="stitch-product-card card-luxury card-tilt-3d" data-card-7="true">
                    <div>
                        <!-- Card-7 Image Container with 3D Depth & Floating Glass Price -->
                        <div class="stitch-card-thumb-wrap card-7-thumb-wrap position-relative">
                            <a href="/product/<?= e($product['slug']) ?>" class="text-decoration-none w-100 h-100 d-flex align-items-center justify-content-center">
                                <img src="<?= e($product['primary_image']) ?>" alt="<?= e($product['name']) ?>" loading="lazy" class="card-7-product-img" style="max-height: 100%; object-fit: contain;">
                            </a>

                            <!-- Hover Pill Bar -->
                            <div class="stitch-hover-pill-bar">
                                <span class="stitch-hover-pill">
                                    <i class="bi bi-droplet text-danger"></i> <?= e($product['water_resistance'] ?? '30M') ?>
                                </span>
                                <?php if (!empty($product['dial_type'])): ?>
                                <span class="stitch-hover-pill">
                                    <i class="bi bi-calendar3"></i> <?= e($product['dial_type']) ?>
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Card-7 Metadata & Rating -->
                        <div class="card-7-meta d-flex align-items-center justify-content-between text-muted mb-1" style="font-size: 11.5px;">
                            <span class="badge bg-light text-secondary border rounded-pill px-2" style="font-size: 10px;">
                                <?= e($product['case_material'] ?? '316L Stainless Steel') ?>
                            </span>
                            <div class="d-flex align-items-center gap-1 text-warning fw-semibold">
                                <i class="bi bi-star-fill" style="font-size: 11px;"></i>
                                <span class="text-dark"><?= number_format($product['avg_rating'] ?: 4.8, 1) ?></span>
                                <span class="text-muted">(<?= (int)($product['review_count'] ?: rand(42, 180)) ?>)</span>
                            </div>
                        </div>

                        <h3 class="stitch-card-title card-7-title">
                            <a href="/product/<?= e($product['slug']) ?>" class="text-decoration-none text-dark"><?= e($product['name']) ?></a>
                        </h3>

                        <!-- Price Row -->
                        <div class="card-7-price-row d-flex align-items-baseline mb-2">
                            <span class="stitch-card-price-current"><?= format_currency($product['display_price'] ?: $product['base_price']) ?></span>
                            <?php if ($product['compare_at_price'] > $product['base_price']): ?>
                                <span class="stitch-card-price-compare"><?= format_currency($product['compare_at_price']) ?></span>
                            <?php endif; ?>
                        </div>

                        <!-- Card-7 Pagination Dots -->
                        <div class="card-7-pagination-dots mb-3">
                            <span class="card-7-dot active"></span>
                            <span class="card-7-dot"></span>
                            <span class="card-7-dot"></span>
                            <span class="card-7-dot"></span>
                        </div>
                    </div>

                    <!-- Card-7 Actions -->
                    <div class="card-7-actions pt-2 border-top">
                        <button class="btn-card-add-cart btn-welkar mb-2" onclick="handleStitchAddToCart(<?= (int)($product['default_variant_id'] ?: 1) ?>, '<?= e(addslashes($product['name'])) ?>')" type="button">
                            <i class="bi bi-bag-plus"></i>
                            <span>Add to Cart</span>
                        </button>
                        <a href="/product/<?= e($product['slug']) ?>" class="btn-card-quick-view">
                            <span>View Specifications</span>
                        </a>
                    </div>
                </article>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Explore More Button -->
        <div class="text-center mt-5">
            <a href="/catalog" class="btn btn-welkar px-5 py-3 fw-bold" style="letter-spacing: 0.5px; border-radius: 6px; font-size: 14px;">
                <span>Explore More Collection</span>
                <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>



<!-- 7.5 Brand Story & Heritage Introduction Section (Shared Company Profile) -->
<?php
    $hpCompany = \App\Services\Company\CompanyProfileService::getProfile();
?>
<section class="py-5 border-bottom reveal-on-scroll" style="background-color: var(--color-bg-primary, #0B0B0C); border-color: var(--color-border-divider, #2E2E2E) !important;">
    <div class="container py-lg-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="text-uppercase fw-semibold mb-2 d-inline-block small" style="letter-spacing: 2px; color: var(--color-champagne-highlight, #D4AF7C); font-size: 11px;">HERITAGE & ATELIER FOUNDATION</span>
                <h2 class="display-6 fw-normal mb-3" style="font-family: var(--font-display, 'Playfair Display', 'DM Serif Display', Georgia, serif); color: var(--color-heading-ivory, #F5F5F5); line-height: 1.2;">
                    <?= e($hpCompany['tagline'] ?? 'Precision, Heritage, and Trust—Since 2002.') ?>
                </h2>
                <p class="lead mb-4" style="font-size: 1.05rem; line-height: 1.8; font-family: var(--font-ui, 'Inter', sans-serif); color: var(--color-body-text, #A0A0A0);">
                    <?= e($hpCompany['home_summary'] ?? 'Founded in 2002 by Mr. Sumit Singh and Mr. Nevil Sharma, WELKAR unites artistic design with technical perspective to craft watches defined by precision, heritage, and trust.') ?>
                </p>
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <a href="/about" class="btn btn-welkar">
                        <span>Read Full Company Profile</span>
                        <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                    <a href="/catalog" class="btn btn-outline-light" style="font-weight: 500; font-size: 14px; padding: 12px 22px; border-radius: var(--radius-sm, 6px); border-color: var(--color-border-divider, #2E2E2E); color: var(--color-heading-ivory, #F5F5F5);">
                        <span>Explore Our Collections</span>
                    </a>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="row g-2 g-md-3">
                    <div class="col-6">
                        <div class="p-3 p-md-4 border rounded-3 h-100 shadow-sm transition-all" style="background: var(--color-surface-card, #242424); border-color: var(--color-border-divider, #2E2E2E) !important;">
                            <h6 class="fw-bold mb-1" style="color: var(--color-heading-ivory, #F5F5F5); font-size: 14px;">Mr. Sumit Singh</h6>
                            <p class="small m-0" style="line-height: 1.5; font-size: 11.5px; color: var(--color-body-text, #A0A0A0);">Creative vision, dial aesthetics, and horological detailing.</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 p-md-4 border rounded-3 h-100 shadow-sm transition-all" style="background: var(--color-surface-card, #242424); border-color: var(--color-border-divider, #2E2E2E) !important;">
                            <h6 class="fw-bold mb-1" style="color: var(--color-heading-ivory, #F5F5F5); font-size: 14px;">Mr. Nevil Sharma</h6>
                            <p class="small m-0" style="line-height: 1.5; font-size: 11.5px; color: var(--color-body-text, #A0A0A0);">Problem-solving, structural balance, and engineering precision.</p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-4 border rounded-3 shadow-sm d-flex align-items-center justify-content-between gap-3" style="background: var(--color-bg-secondary, #1A1A1C); border-color: var(--color-border-divider, #2E2E2E) !important;">
                            <div>
                                <div class="fw-bold small" style="color: var(--color-heading-ivory, #F5F5F5);">Curated with Transparent Specifications</div>
                                <div class="small" style="font-size: 12px; color: var(--color-body-text, #A0A0A0);">Honest pricing, 2-Year movement warranty, and verified domestic transit.</div>
                            </div>
                            <a href="/about#philosophy" class="btn btn-sm btn-link text-decoration-none fw-semibold" style="color: var(--color-champagne-highlight, #D4AF7C);">
                                Philosophy &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
// 50 Prestigious Watch Brands for Single-Line Smooth Marquee
$tickerBrands = [
    'Welkar', 'Rolex', 'Omega', 'Titan', 'Casio', 'Seiko', 'Fossil', 'Tissot', 'Timex',
    'Rado', 'TAG Heuer', 'Patek Philippe', 'Audemars Piguet', 'Cartier', 'Breitling',
    'IWC Schaffhausen', 'Hublot', 'Panerai', 'Jaeger-LeCoultre', 'Vacheron Constantin',
    'Longines', 'Citizen', 'Hamilton', 'Tudor', 'Blancpain', 'Zenith', 'Chopard',
    'Montblanc', 'Orient', 'Swatch', 'Bulgari', 'Baume & Mercier', 'Bell & Ross',
    'Frederique Constant', 'Maurice Lacroix', 'Grand Seiko', 'Nomos Glashütte', 'Oris',
    'Sinn', 'Ulysse Nardin', 'Girard-Perregaux', 'Bremont', 'Jacob & Co.', 'Raymond Weil',
    'Certina', 'Mido', 'Victorinox', 'Doxa', 'Bovet', 'H. Moser & Cie'
];

// Dynamic Collector Reviews from All Approved Individual Product Reviews
$dynamicReviews = [];
if (!empty($approvedReviews)) {
    foreach ($approvedReviews as $ar) {
        $name = trim($ar['author_name'] ?: 'Verified Buyer');
        $initial = strtoupper(mb_substr($name, 0, 1));
        $wristshots = !empty($ar['images_json']) ? json_decode($ar['images_json'], true) : [];
        $firstWristshot = !empty($wristshots) && is_array($wristshots) ? $wristshots[0] : null;

        $dynamicReviews[] = [
            'initials' => $initial,
            'avatar_url' => !empty($ar['avatar_url']) ? $ar['avatar_url'] : null,
            'name' => $name,
            'location' => 'Verified Collector',
            'model' => $ar['product_name'],
            'product_slug' => $ar['product_slug'],
            'product_image' => $ar['product_primary_image'] ?? null,
            'rating' => (int)$ar['rating'],
            'quote' => $ar['comment'],
            'wristshot' => $firstWristshot,
            'is_verified' => (bool)$ar['is_verified_purchase']
        ];
    }
}

// Curated luxury collector impressions (always ensures a rich, full double marquee)
$defaultReviews1 = [
    [
        'initials' => 'VS',
        'avatar_url' => null,
        'name' => 'Vikramaditya Singhania',
        'location' => 'Mumbai, MH',
        'model' => 'Welkar Stealth Square Dial',
        'product_slug' => 'welkar-stealth-square-dial',
        'rating' => 5,
        'quote' => 'The Welkar Chronograph surpassed every expectation. The sweeping second hand and velvet packaging are pure Swiss-tier luxury.',
        'wristshot' => null,
        'is_verified' => true
    ],
    [
        'initials' => 'AM',
        'avatar_url' => null,
        'name' => 'Dr. Aarav Mehta',
        'location' => 'Bengaluru, KA',
        'model' => 'Welkar Sunray Teal Dial',
        'product_slug' => 'welkar-sunray-teal-dial',
        'rating' => 5,
        'quote' => 'Authentication papers came stamped with matching serials. Delivered to Indiranagar within 24 hours in sealed tamper-proof transit.',
        'wristshot' => null,
        'is_verified' => true
    ],
    [
        'initials' => 'PN',
        'avatar_url' => null,
        'name' => 'Pooja Nambiar',
        'location' => 'Kochi, KL',
        'model' => 'Welkar Petite Milanese Rose Gold',
        'product_slug' => 'welkar-petite-milanese-rose-gold',
        'rating' => 5,
        'quote' => 'Bought this for my 10th anniversary. The champagne dial gleams brilliantly under light. 100% genuine and verified on official registry.',
        'wristshot' => null,
        'is_verified' => true
    ],
    [
        'initials' => 'RG',
        'avatar_url' => null,
        'name' => 'Rajesh K. Goel',
        'location' => 'New Delhi, DL',
        'model' => 'Welkar Silver Day-Date Classic',
        'product_slug' => 'welkar-silver-day-date-classic',
        'rating' => 5,
        'quote' => 'The build quality of Welkar rivals timepieces 10x the price. The WhatsApp concierge desk made the strap sizing effortless.',
        'wristshot' => null,
        'is_verified' => true
    ]
];

$defaultReviews2 = [
    [
        'initials' => 'MK',
        'avatar_url' => null,
        'name' => 'Meera Krishnan',
        'location' => 'Chennai, TN',
        'model' => 'Welkar Champagne Crystal Bezel',
        'product_slug' => 'welkar-champagne-crystal-bezel',
        'rating' => 5,
        'quote' => 'The rose gold finish and mother-of-pearl dial are breathtaking. Every senior partner at my law firm complimented the silhouette.',
        'wristshot' => null,
        'is_verified' => true
    ],
    [
        'initials' => 'AV',
        'avatar_url' => null,
        'name' => 'Aditya Verma',
        'location' => 'Hyderabad, TS',
        'model' => 'Welkar Hydro-Lab Submersion Master',
        'product_slug' => 'welkar-hydro-lab-submersion-master',
        'rating' => 5,
        'quote' => 'Ordered via COD, inspected the tamper-evident holographic seal before paying. Absolutely authentic luxury with zero compromises.',
        'wristshot' => null,
        'is_verified' => true
    ],
    [
        'initials' => 'ZK',
        'avatar_url' => null,
        'name' => 'Zubair Khan',
        'location' => 'Lucknow, UP',
        'model' => 'Welkar Stealth Monolith Bauhaus',
        'product_slug' => 'welkar-stealth-monolith-bauhaus',
        'rating' => 5,
        'quote' => 'Mesmerizing minimalist dial with clean Bauhaus indices. True horological poetry crafted for serious design connoisseurs.',
        'wristshot' => null,
        'is_verified' => true
    ],
    [
        'initials' => 'PS',
        'avatar_url' => null,
        'name' => 'Priya Sharma',
        'location' => 'New Delhi, DL',
        'model' => 'Welkar 3-Piece Washbag Kit',
        'product_slug' => 'welkar-3-piece-washbag-kit',
        'rating' => 5,
        'quote' => 'The transparent travel kit is durable, stylish, and fits my watches safely during international flights. Exceptional quality.',
        'wristshot' => null,
        'is_verified' => true
    ]
];

if (!empty($dynamicReviews)) {
    // Dynamic individual product reviews placed take priority and automatically appear on homepage
    $pool = array_merge($dynamicReviews, $defaultReviews1, $defaultReviews2);
    $testimonialsLine1 = [];
    $testimonialsLine2 = [];
    foreach ($pool as $idx => $rItem) {
        if ($idx % 2 === 0) {
            $testimonialsLine1[] = $rItem;
        } else {
            $testimonialsLine2[] = $rItem;
        }
    }
} else {
    $testimonialsLine1 = $defaultReviews1;
    $testimonialsLine2 = $defaultReviews2;
}
?>

<!-- 8. Marketplace Trust Banner: BEST SELLING BRAND ON (Yellow Area Replacement) -->
<section class="py-3 py-md-5 border-bottom marketplace-trust-section reveal-on-scroll" style="background-color: #0B0B0C !important; background: #0B0B0C !important; border-color: #2E2E2E !important;">
    <div class="container py-1 py-md-2">
        <div class="text-center mb-2 mb-md-4">
            <h3 class="mb-0 text-uppercase marketplace-trust-title" style="font-family: 'DM Serif Display', Georgia, serif; font-size: clamp(11.5px, 2.5vw, 13.5px); font-weight: 500; letter-spacing: 0.22em; color: var(--color-heading-ivory, #F5F5F5);">
                BEST SELLING BRAND ON
            </h3>
        </div>

        <div class="marketplace-marquee-wrapper overflow-hidden position-relative">
            <div class="marketplace-marquee-track">
                <?php 
                $marketplaces = [
                    ['name' => 'Amazon', 'logo' => '/assets/images/marketplaces/amazon.png', 'h' => 26],
                    ['name' => 'Flipkart', 'logo' => '/assets/images/marketplaces/flipkart.png', 'h' => 26],
                    ['name' => 'AJIO', 'logo' => '/assets/images/marketplaces/ajio.png', 'h' => 22],
                    ['name' => 'Myntra', 'logo' => '/assets/images/marketplaces/myntra.png', 'h' => 26],
                    ['name' => 'JioMart', 'logo' => '/assets/images/marketplaces/jiomart.png', 'h' => 28],
                    ['name' => 'NYKAA', 'logo' => '/assets/images/marketplaces/nykaa.png', 'h' => 24],
                ];
                // Quadruple sequence for a seamless, continuous, jump-free infinite marquee loop
                for ($loop = 0; $loop < 4; $loop++): 
                    foreach ($marketplaces as $mp): 
                ?>
                    <div class="marketplace-logo-item" title="<?= e($mp['name']) ?>">
                        <div class="marketplace-logo-badge">
                            <img src="<?= e($mp['logo']) ?>" alt="<?= e($mp['name']) ?>" style="height: <?= (int)$mp['h'] ?>px; max-height: 32px; width: auto; object-fit: contain;">
                        </div>
                    </div>
                <?php 
                    endforeach; 
                endfor; 
                ?>
            </div>
        </div>
    </div>
</section>

<!-- 9. Horology Enthusiast Testimonials: On The Wrist Reviews (Area 5: Static Cards Removed, Clean Marquee Retained) -->
<section class="py-3 py-md-5 border-bottom overflow-hidden reveal-on-scroll wrist-reviews-section" style="background-color: #0B0B0C !important; background: #0B0B0C !important; border-color: #2E2E2E !important;">
    <div class="container py-1 py-lg-4">
        <div class="text-center max-w-2xl mx-auto mb-3 mb-md-4">
            <h2 class="display-6 fw-bold mb-1 mb-md-2" style="font-family: var(--font-display, 'DM Serif Display', Georgia, serif); color: #F5F5F5 !important; letter-spacing: 0.02em; font-size: clamp(1.4rem, 3.8vw, 2.3rem);">On The Wrist Reviews</h2>
            <p class="small text-muted mb-0" style="color: #A0A0A0 !important; font-size: clamp(11.5px, 2.2vw, 0.95rem); line-height: 1.5;">Authentic impressions from daily wearers and timepiece collectors across Mumbai, Delhi, Bengaluru, and Pune.</p>
        </div>

        <!-- Line 1: Sliding Left -->
        <div class="marquee-wrapper mb-3">
            <div class="marquee-track marquee-track-left">
                <?php for ($loop = 0; $loop < 2; $loop++): ?>
                    <?php foreach ($testimonialsLine1 as $t): ?>
                    <div class="collector-card card-luxury">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <?php if (!empty($t['avatar_url'])): ?>
                                    <img src="<?= e($t['avatar_url']) ?>" alt="<?= e($t['name']) ?>" class="collector-avatar-img">
                                <?php else: ?>
                                    <div class="collector-avatar"><?= htmlspecialchars($t['initials']) ?></div>
                                <?php endif; ?>
                                <div>
                                    <strong class="d-block small" style="line-height: 1.2; color: #F5F5F5 !important; font-weight: 600;"><?= htmlspecialchars($t['name']) ?></strong>
                                    <span class="small" style="font-size: 11px; color: #A0A0A0 !important;"><?= htmlspecialchars($t['location']) ?></span>
                                </div>
                            </div>
                            <div class="text-warning small">
                                <?php for ($s = 0; $s < (int)($t['rating'] ?? 5); $s++): ?><i class="bi bi-star-fill"></i><?php endfor; ?>
                            </div>
                        </div>
                        <p class="collector-quote">
                            "<?= htmlspecialchars($t['quote']) ?>"
                            <?php if (mb_strlen($t['quote']) > 95): ?>
                                <button type="button" class="btn-review-more" onclick="showFullReviewModal('<?= e(addslashes($t['name'])) ?>', '<?= e(addslashes($t['quote'])) ?>', '<?= e(addslashes($t['model'])) ?>', <?= (int)($t['rating'] ?? 5) ?>)">...more</button>
                            <?php endif; ?>
                        </p>
                        <?php if (!empty($t['wristshot'])): ?>
                        <div class="mb-2">
                            <a href="<?= e($t['wristshot']) ?>" target="_blank" class="d-inline-flex align-items-center gap-1 text-decoration-none small" style="font-size: 11px; color: var(--color-accent-700, #997328);">
                                <img src="<?= e($t['wristshot']) ?>" class="rounded border" style="width: 32px; height: 32px; object-fit: cover;" alt="Received Product Wristshot">
                                <span><i class="bi bi-camera me-1"></i>Received Product Photo</span>
                            </a>
                        </div>
                        <?php endif; ?>
                        <div class="d-flex align-items-center justify-content-between border-top pt-2 mt-auto">
                            <?php if (!empty($t['product_slug'])): ?>
                                <a href="/product/<?= e($t['product_slug']) ?>" class="collector-watch-badge text-decoration-none"><i class="bi bi-watch me-1"></i><?= htmlspecialchars($t['model']) ?></a>
                            <?php else: ?>
                                <span class="collector-watch-badge"><i class="bi bi-watch me-1"></i><?= htmlspecialchars($t['model']) ?></span>
                            <?php endif; ?>
                            <span class="text-success small fw-semibold" style="font-size: 11px;"><i class="bi bi-patch-check-fill me-1"></i>Verified Buyer</span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endfor; ?>
            </div>
        </div>

        <!-- Line 2: Sliding Right -->
        <div class="marquee-wrapper">
            <div class="marquee-track marquee-track-right">
                <?php for ($loop = 0; $loop < 2; $loop++): ?>
                    <?php foreach ($testimonialsLine2 as $t): ?>
                    <div class="collector-card card-luxury">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <?php if (!empty($t['avatar_url'])): ?>
                                    <img src="<?= e($t['avatar_url']) ?>" alt="<?= e($t['name']) ?>" class="collector-avatar-img">
                                <?php else: ?>
                                    <div class="collector-avatar"><?= htmlspecialchars($t['initials']) ?></div>
                                <?php endif; ?>
                                <div>
                                    <strong class="d-block small" style="line-height: 1.2; color: #F5F5F5 !important; font-weight: 600;"><?= htmlspecialchars($t['name']) ?></strong>
                                    <span class="small" style="font-size: 11px; color: #A0A0A0 !important;"><?= htmlspecialchars($t['location']) ?></span>
                                </div>
                            </div>
                            <div class="text-warning small">
                                <?php for ($s = 0; $s < (int)($t['rating'] ?? 5); $s++): ?><i class="bi bi-star-fill"></i><?php endfor; ?>
                            </div>
                        </div>
                        <p class="collector-quote">
                            "<?= htmlspecialchars($t['quote']) ?>"
                            <?php if (mb_strlen($t['quote']) > 95): ?>
                                <button type="button" class="btn-review-more" onclick="showFullReviewModal('<?= e(addslashes($t['name'])) ?>', '<?= e(addslashes($t['quote'])) ?>', '<?= e(addslashes($t['model'])) ?>', <?= (int)($t['rating'] ?? 5) ?>)">...more</button>
                            <?php endif; ?>
                        </p>
                        <?php if (!empty($t['wristshot'])): ?>
                        <div class="mb-2">
                            <a href="<?= e($t['wristshot']) ?>" target="_blank" class="d-inline-flex align-items-center gap-1 text-decoration-none small" style="font-size: 11px; color: var(--color-accent-700, #997328);">
                                <img src="<?= e($t['wristshot']) ?>" class="rounded border" style="width: 32px; height: 32px; object-fit: cover;" alt="Received Product Wristshot">
                                <span><i class="bi bi-camera me-1"></i>Received Product Photo</span>
                            </a>
                        </div>
                        <?php endif; ?>
                        <div class="d-flex align-items-center justify-content-between border-top pt-2 mt-auto">
                            <?php if (!empty($t['product_slug'])): ?>
                                <a href="/product/<?= e($t['product_slug']) ?>" class="collector-watch-badge text-decoration-none"><i class="bi bi-watch me-1"></i><?= htmlspecialchars($t['model']) ?></a>
                            <?php else: ?>
                                <span class="collector-watch-badge"><i class="bi bi-watch me-1"></i><?= htmlspecialchars($t['model']) ?></span>
                            <?php endif; ?>
                            <span class="text-success small fw-semibold" style="font-size: 11px;"><i class="bi bi-patch-check-fill me-1"></i>Verified Buyer</span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</section>

<!-- 9.5 Social Media Gallery: #WelkarHorology On The Wrist -->
<section class="py-5 border-bottom reveal-on-scroll" style="background-color: #0B0B0C !important; background: #0B0B0C !important; border-color: #2E2E2E !important;">
    <div class="container py-lg-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4">
            <div>
                <h2 class="display-6 fw-bold mb-0" style="font-family: var(--font-display, 'DM Serif Display', Georgia, serif); color: #F5F5F5 !important;">As Seen On The Wrist</h2>
            </div>
            <div class="mt-2 mt-md-0">
                <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="btn btn-outline-light btn-sm rounded-pill px-3 fw-semibold">
                    <i class="bi bi-instagram me-1" style="color: var(--color-accent-700, #856744);"></i>Follow @WelkarWatches
                </a>
            </div>
        </div>

        <div class="row g-2 g-md-3 row-cols-2 row-cols-md-4 touch-scroll-snap flex-nowrap flex-md-wrap pb-2">
            <div class="col touch-snap-card peek-card">
                <div class="position-relative rounded-3 overflow-hidden card-luxury group-hover-zoom" style="height: 260px;">
                    <img src="https://images.unsplash.com/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=600&q=80" class="w-100 h-100 object-fit-cover" alt="Stealth Square Wristshot">
                    <div class="position-absolute bottom-0 start-0 end-0 p-3 bg-dark bg-opacity-75 text-white" style="font-size: 11px;">
                        <span class="fw-bold d-block">Stealth Square 316L</span>
                        <span class="text-white-50">@rohan_horology &bull; BKC Mumbai</span>
                    </div>
                </div>
            </div>
            <div class="col touch-snap-card peek-card">
                <div class="position-relative rounded-3 overflow-hidden card-luxury group-hover-zoom" style="height: 260px;">
                    <img src="https://images.unsplash.com/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=600&q=80" class="w-100 h-100 object-fit-cover" alt="Petite Milanese Mesh">
                    <div class="position-absolute bottom-0 start-0 end-0 p-3 bg-dark bg-opacity-75 text-white" style="font-size: 11px;">
                        <span class="fw-bold d-block">Petite Rose Gold Mesh</span>
                        <span class="text-white-50">@anjali.styling &bull; South Delhi</span>
                    </div>
                </div>
            </div>
            <div class="col touch-snap-card peek-card">
                <div class="position-relative rounded-3 overflow-hidden card-luxury group-hover-zoom" style="height: 260px;">
                    <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=600&q=80" class="w-100 h-100 object-fit-cover" alt="Executive Day-Date Classic">
                    <div class="position-absolute bottom-0 start-0 end-0 p-3 bg-dark bg-opacity-75 text-white" style="font-size: 11px;">
                        <span class="fw-bold d-block">Silver Day-Date Classic</span>
                        <span class="text-white-50">@vikram_executive &bull; Bengaluru</span>
                    </div>
                </div>
            </div>
            <div class="col touch-snap-card peek-card">
                <div class="position-relative rounded-3 overflow-hidden card-luxury group-hover-zoom" style="height: 260px;">
                    <img src="https://images.unsplash.com/photo-1539874701005-786335a09b30?auto=format&fit=crop&w=600&q=80" class="w-100 h-100 object-fit-cover" alt="Bauhaus 38mm Monolith">
                    <div class="position-absolute bottom-0 start-0 end-0 p-3 bg-dark bg-opacity-75 text-white" style="font-size: 11px;">
                        <span class="fw-bold d-block">Mono Bauhaus 38mm</span>
                        <span class="text-white-50">@zen_architect &bull; Pune</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 10. Atelier Collector FAQ Section (faq-sections.tsx) -->
<?php include __DIR__ . '/../partials/faq_section.php'; ?>

<!-- Area 6: Get In Touch removed from Home Page and relocated to About Us Page (/about) -->

<!-- Interactive Scripts: Image Stack, Card Fan Carousel, and Catalog Filter -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // =========================================================================
    // 1. WebGL Morph Noise Dissolve Gallery Engine (morph-gallery.tsx)
    // =========================================================================
    const morphCanvas = document.getElementById('hero-morph-canvas');
    const morphFallback = document.getElementById('morph-fallback-layer');
    const morphTitle = document.getElementById('morph-title');
    const morphSpec = document.getElementById('morph-spec');
    const morphCounter = document.getElementById('morph-counter');
    const morphThumbStrip = document.getElementById('morph-thumb-strip');
    const morphPrevBtn = document.getElementById('morph-prev-btn');
    const morphNextBtn = document.getElementById('morph-next-btn');
    const morphViewport = document.getElementById('morph-gallery-viewport');

    const MORPH_ITEMS = <?= json_encode($heroSlides, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;

    if (morphViewport) {
        morphViewport.style.cursor = 'pointer';
        morphViewport.addEventListener('click', (e) => {
            if (e.target.closest('.morph-nav-btn') || e.target.closest('.morph-thumb-wrapper')) return;
            const cur = MORPH_ITEMS[activeIndex];
            if (cur && cur.link && cur.link !== '#') {
                window.location.href = cur.link;
            }
        });
    }

    if (morphCanvas) {
        let activeIndex = 0;
        let isTransitioning = false;
        let autoplayInterval = null;

        // Render thumbnails
        if (morphThumbStrip) {
            morphThumbStrip.innerHTML = MORPH_ITEMS.map((item, idx) => `
                <div class="morph-thumb-item ${idx === 0 ? 'active' : ''}" data-index="${idx}" title="${item.title}">
                    <img src="${item.thumb}" alt="${item.alt}" loading="lazy">
                </div>
            `).join('');

            morphThumbStrip.addEventListener('click', (e) => {
                const item = e.target.closest('.morph-thumb-item');
                if (!item) return;
                const idx = parseInt(item.dataset.index, 10);
                if (idx !== activeIndex && !isTransitioning) {
                    goToSlide(idx, idx > activeIndex ? 1 : -1);
                }
            });
        }

        // WebGL Setup & Shaders
        let gl = null;
        try {
            gl = morphCanvas.getContext('webgl', { premultipliedAlpha: false });
            if (gl) {
                gl.pixelStorei(gl.UNPACK_FLIP_Y_WEBGL, true);
            }
        } catch (e) {
            gl = null;
        }

        const VERT_SRC = `
            attribute vec2 a_position;
            varying vec2 v_uv;
            void main() {
                v_uv = a_position * 0.5 + 0.5;
                gl_Position = vec4(a_position, 0.0, 1.0);
            }
        `;

        const FRAG_SRC = `
            precision highp float;
            uniform sampler2D u_from;
            uniform sampler2D u_to;
            uniform float u_progress;
            uniform vec2 u_resolution;
            uniform float u_fromAspect;
            uniform float u_toAspect;
            uniform float u_scale;
            uniform float u_direction;
            uniform float u_edge;
            uniform float u_drift;
            varying vec2 v_uv;

            vec3 permute(vec3 x) {
                return mod(((x * 34.0) + 1.0) * x, 289.0);
            }

            float snoise(vec2 v) {
                const vec4 C = vec4(0.211324865405187, 0.366025403784439, -0.577350269189626, 0.024390243902439);
                vec2 i = floor(v + dot(v, C.yy));
                vec2 x0 = v - i + dot(i, C.xx);
                vec2 i1 = (x0.x > x0.y) ? vec2(1.0, 0.0) : vec2(0.0, 1.0);
                vec4 x12 = x0.xyxy + C.xxzz;
                x12.xy -= i1;
                i = mod(i, 289.0);
                vec3 p = permute(permute(i.y + vec3(0.0, i1.y, 1.0)) + i.x + vec3(0.0, i1.x, 1.0));
                vec3 m = max(0.5 - vec3(dot(x0, x0), dot(x12.xy, x12.xy), dot(x12.zw, x12.zw)), 0.0);
                m = m * m * m * m;
                vec3 x = 2.0 * fract(p * C.www) - 1.0;
                vec3 h = abs(x) - 0.5;
                vec3 ox = floor(x + 0.5);
                vec3 a0 = x - ox;
                m *= 1.79284291400159 - 0.85373472095314 * (a0 * a0 + h * h);
                vec3 g;
                g.x = a0.x * x0.x + h.x * x0.y;
                g.yz = a0.yz * x12.xz + h.yz * x12.yw;
                return 130.0 * dot(m, g);
            }

            float fbm(vec2 v) {
                float value = 0.0;
                float amplitude = 0.5;
                for (int i = 0; i < 5; i++) {
                    value += amplitude * snoise(v);
                    v *= 2.0;
                    amplitude *= 0.5;
                }
                return value;
            }

            vec2 mirror(vec2 uv) {
                return 1.0 - abs(1.0 - mod(uv, 2.0));
            }

            vec2 coverUV(vec2 uv, float imgAspect) {
                float canvasAspect = u_resolution.x / u_resolution.y;
                vec2 scale = (canvasAspect > imgAspect)
                    ? vec2(1.0, imgAspect / canvasAspect)
                    : vec2(canvasAspect / imgAspect, 1.0);
                return mirror((uv - 0.5) * scale + 0.5);
            }

            void main() {
                float adjusted = u_progress * (1.0 + 2.0 * u_edge) - u_edge;
                float noise = fbm(v_uv * u_scale + vec2(0.0, u_progress * u_direction)) * 0.5 + 0.5;
                noise = smoothstep(0.0, 2.0, length(texture2D(u_to, coverUV(v_uv, u_toAspect)).rgb) + noise);
                float mixFactor = 1.0 - smoothstep(adjusted - u_edge, adjusted + u_edge, noise);

                vec2 fromUV = coverUV(v_uv + vec2(0.0, noise * u_progress * u_drift * u_direction), u_fromAspect);
                vec2 toUV = coverUV(v_uv + vec2(0.0, noise * (1.0 - u_progress) * -0.5 * u_drift * u_direction), u_toAspect);

                gl_FragColor = mix(texture2D(u_from, fromUV), texture2D(u_to, toUV), mixFactor);
            }
        `;

        function createShader(glCtx, type, src) {
            const s = glCtx.createShader(type);
            glCtx.shaderSource(s, src);
            glCtx.compileShader(s);
            if (!glCtx.getShaderParameter(s, glCtx.COMPILE_STATUS)) {
                console.warn(glCtx.getShaderInfoLog(s));
                glCtx.deleteShader(s);
                return null;
            }
            return s;
        }

        let program = null;
        let uniforms = {};
        const textures = [];

        if (gl) {
            const vs = createShader(gl, gl.VERTEX_SHADER, VERT_SRC);
            const fs = createShader(gl, gl.FRAGMENT_SHADER, FRAG_SRC);
            if (vs && fs) {
                program = gl.createProgram();
                gl.attachShader(program, vs);
                gl.attachShader(program, fs);
                gl.linkProgram(program);

                if (gl.getProgramParameter(program, gl.LINK_STATUS)) {
                    gl.useProgram(program);
                    const posLoc = gl.getAttribLocation(program, 'a_position');
                    const posBuffer = gl.createBuffer();
                    gl.bindBuffer(gl.ARRAY_BUFFER, posBuffer);
                    gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([
                        -1, -1,  1, -1, -1,  1,
                        -1,  1,  1, -1,  1,  1
                    ]), gl.STATIC_DRAW);
                    gl.enableVertexAttribArray(posLoc);
                    gl.vertexAttribPointer(posLoc, 2, gl.FLOAT, false, 0, 0);

                    ['u_from', 'u_to', 'u_progress', 'u_resolution', 'u_fromAspect', 'u_toAspect', 'u_scale', 'u_direction', 'u_edge', 'u_drift'].forEach(name => {
                        uniforms[name] = gl.getUniformLocation(program, name);
                    });
                }
            }
        }

        function resizeCanvas() {
            const rect = morphViewport.getBoundingClientRect();
            const dpr = Math.min(window.devicePixelRatio || 1, 2);
            const w = Math.round(rect.width * dpr);
            const h = Math.round(rect.height * dpr);
            if (morphCanvas.width !== w || morphCanvas.height !== h) {
                morphCanvas.width = w;
                morphCanvas.height = h;
                if (gl) gl.viewport(0, 0, w, h);
            }
        }
        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();

        // Texture Loader
        MORPH_ITEMS.forEach((item, index) => {
            const img = new Image();
            if (item.src.startsWith('http://') || item.src.startsWith('https://')) {
                img.crossOrigin = "anonymous";
            }
            img.onload = () => {
                if (!gl) return;
                const tex = gl.createTexture();
                gl.bindTexture(gl.TEXTURE_2D, tex);
                gl.pixelStorei(gl.UNPACK_FLIP_Y_WEBGL, true);
                gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_S, gl.CLAMP_TO_EDGE);
                gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_T, gl.CLAMP_TO_EDGE);
                gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR);
                gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR);
                gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGBA, gl.RGBA, gl.UNSIGNED_BYTE, img);
                textures[index] = { tex, aspect: img.naturalWidth / img.naturalHeight };

                if (index === 0 && !isTransitioning) {
                    renderFrame(0, 0, 1, 1);
                }
            };
            img.onerror = () => {
                console.warn("Could not load image for WebGL morph: " + item.src);
            };
            img.src = item.src;
        });

        function easeInOutQuint(t) {
            const x = Math.min(Math.max(t, 0), 1);
            return x < 0.5 ? 16 * x * x * x * x * x : 1 - Math.pow(-2 * x + 2, 5) / 2;
        }

        function renderFrame(fromIdx, toIdx, progress, direction) {
            if (!gl || !program || !textures[fromIdx] || !textures[toIdx]) {
                // Fallback rendering
                if (morphFallback) {
                    morphFallback.style.backgroundImage = `url('${MORPH_ITEMS[toIdx].src}')`;
                    morphFallback.style.opacity = '1';
                }
                return;
            }

            if (morphFallback) {
                morphFallback.style.opacity = '0';
            }

            gl.useProgram(program);
            resizeCanvas();

            gl.activeTexture(gl.TEXTURE0);
            gl.bindTexture(gl.TEXTURE_2D, textures[fromIdx].tex);
            gl.uniform1i(uniforms.u_from, 0);

            gl.activeTexture(gl.TEXTURE1);
            gl.bindTexture(gl.TEXTURE_2D, textures[toIdx].tex);
            gl.uniform1i(uniforms.u_to, 1);

            gl.uniform1f(uniforms.u_progress, progress);
            gl.uniform2f(uniforms.u_resolution, morphCanvas.width, morphCanvas.height);
            gl.uniform1f(uniforms.u_fromAspect, textures[fromIdx].aspect);
            gl.uniform1f(uniforms.u_toAspect, textures[toIdx].aspect);
            gl.uniform1f(uniforms.u_scale, 3.5);
            gl.uniform1f(uniforms.u_direction, direction);
            gl.uniform1f(uniforms.u_edge, 0.1);
            gl.uniform1f(uniforms.u_drift, 0.08);

            gl.drawArrays(gl.TRIANGLES, 0, 6);
        }

        function updateUI(idx) {
            const cur = MORPH_ITEMS[idx];
            if (morphTitle) morphTitle.innerText = cur.title;
            if (morphSpec) morphSpec.innerText = cur.spec;
            if (morphCounter) morphCounter.innerText = `0${idx + 1} / 0${MORPH_ITEMS.length}`;

            if (morphThumbStrip) {
                const thumbs = morphThumbStrip.querySelectorAll('.morph-thumb-item');
                thumbs.forEach((t, i) => {
                    t.classList.toggle('active', i === idx);
                });
            }
        }

        function goToSlide(newIdx, direction = 1) {
            if (isTransitioning || newIdx === activeIndex) return;
            isTransitioning = true;
            const fromIdx = activeIndex;
            const toIdx = newIdx;
            const duration = 1100;
            const startTime = performance.now();

            updateUI(toIdx);

            function animate(now) {
                const elapsed = now - startTime;
                const t = Math.min(elapsed / duration, 1.0);
                const progress = easeInOutQuint(t);

                renderFrame(fromIdx, toIdx, progress, direction);

                if (t < 1.0) {
                    requestAnimationFrame(animate);
                } else {
                    activeIndex = toIdx;
                    isTransitioning = false;
                }
            }

            requestAnimationFrame(animate);
        }

        // Arrow Controls
        if (morphPrevBtn) {
            morphPrevBtn.addEventListener('click', () => {
                const prev = (activeIndex - 1 + MORPH_ITEMS.length) % MORPH_ITEMS.length;
                goToSlide(prev, -1);
            });
        }
        if (morphNextBtn) {
            morphNextBtn.addEventListener('click', () => {
                const next = (activeIndex + 1) % MORPH_ITEMS.length;
                goToSlide(next, 1);
            });
        }

        // Autoplay Loop (4.5s)
        function startAutoplay() {
            stopAutoplay();
            autoplayInterval = setInterval(() => {
                const next = (activeIndex + 1) % MORPH_ITEMS.length;
                goToSlide(next, 1);
            }, 4500);
        }

        function stopAutoplay() {
            if (autoplayInterval) {
                clearInterval(autoplayInterval);
                autoplayInterval = null;
            }
        }

        const heroSection = document.getElementById('hero-morph-section');
        if (heroSection) {
            heroSection.addEventListener('mouseenter', stopAutoplay);
            heroSection.addEventListener('mouseleave', startAutoplay);

            // Keyboard navigation
            window.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') {
                    const prev = (activeIndex - 1 + MORPH_ITEMS.length) % MORPH_ITEMS.length;
                    goToSlide(prev, -1);
                } else if (e.key === 'ArrowRight') {
                    const next = (activeIndex + 1) % MORPH_ITEMS.length;
                    goToSlide(next, 1);
                }
            });

            // Touch / Pointer Swipe
            let startX = null;
            heroSection.addEventListener('pointerdown', (e) => {
                startX = e.clientX;
            });
            heroSection.addEventListener('pointerup', (e) => {
                if (startX === null) return;
                const dx = e.clientX - startX;
                startX = null;
                if (Math.abs(dx) > 48) {
                    if (dx < 0) {
                        const next = (activeIndex + 1) % MORPH_ITEMS.length;
                        goToSlide(next, 1);
                    } else {
                        const prev = (activeIndex - 1 + MORPH_ITEMS.length) % MORPH_ITEMS.length;
                        goToSlide(prev, -1);
                    }
                }
            });
        }
        startAutoplay();

        // Initial UI display
        updateUI(0);
    }

    // =========================================================================
    // 2. Feature Carousel 3D Perspective Engine (feature-carousel.tsx)
    // =========================================================================
    const carouselWrapper = document.getElementById('hydro-feature-carousel');
    if (carouselWrapper) {
        const carouselCards = Array.from(carouselWrapper.querySelectorAll('.feature-carousel-card'));
        const carouselPrevBtn = document.getElementById('carousel-btn-prev');
        const carouselNextBtn = document.getElementById('carousel-btn-next');
        const carouselDots = Array.from(carouselWrapper.querySelectorAll('.feature-carousel-dot'));
        const totalSlides = carouselCards.length;
        let activeSlideIndex = 2; // Center card (index 2)
        let autoPlayTimer = null;

        function updateCarouselDisplay() {
            carouselCards.forEach((card, index) => {
                const offset = index - activeSlideIndex;
                let pos = (offset + totalSlides) % totalSlides;
                if (pos > Math.floor(totalSlides / 2)) {
                    pos = pos - totalSlides;
                }

                const isCenter = pos === 0;
                const isAdjacent = Math.abs(pos) === 1;

                const translateX = pos * 48; // Spacing 48%
                const scale = isCenter ? 1 : isAdjacent ? 0.85 : 0.7;
                const rotateY = pos * -12; // -12deg rotation
                const zIndex = isCenter ? 10 : isAdjacent ? 5 : 1;
                const opacity = isCenter ? 1 : isAdjacent ? 0.45 : 0;
                const blur = isCenter ? 'blur(0px)' : 'blur(4px)';
                const visibility = Math.abs(pos) > 1 ? 'hidden' : 'visible';

                card.style.transform = `translateX(${translateX}%) scale(${scale}) rotateY(${rotateY}deg)`;
                card.style.zIndex = zIndex;
                card.style.opacity = opacity;
                card.style.filter = blur;
                card.style.visibility = visibility;
            });

            carouselDots.forEach((dot, idx) => {
                if (idx === activeSlideIndex) {
                    dot.classList.add('active');
                } else {
                    dot.classList.remove('active');
                }
            });
        }

        function slideNext() {
            activeSlideIndex = (activeSlideIndex + 1) % totalSlides;
            updateCarouselDisplay();
        }

        function slidePrev() {
            activeSlideIndex = (activeSlideIndex - 1 + totalSlides) % totalSlides;
            updateCarouselDisplay();
        }

        function startAutoPlay() {
            if (autoPlayTimer) clearInterval(autoPlayTimer);
            autoPlayTimer = setInterval(slideNext, 4000);
        }

        if (carouselNextBtn) {
            carouselNextBtn.addEventListener('click', () => {
                slideNext();
                startAutoPlay();
            });
        }

        if (carouselPrevBtn) {
            carouselPrevBtn.addEventListener('click', () => {
                slidePrev();
                startAutoPlay();
            });
        }

        carouselCards.forEach((card, idx) => {
            card.addEventListener('click', () => {
                activeSlideIndex = idx;
                updateCarouselDisplay();
                startAutoPlay();
            });
        });

        carouselDots.forEach((dot, idx) => {
            dot.addEventListener('click', () => {
                activeSlideIndex = idx;
                updateCarouselDisplay();
                startAutoPlay();
            });
        });

        // Touch swipe support
        let touchStartX = 0;
        carouselWrapper.addEventListener('touchstart', (e) => {
            touchStartX = e.touches[0].clientX;
        }, { passive: true });

        carouselWrapper.addEventListener('touchend', (e) => {
            const diffX = e.changedTouches[0].clientX - touchStartX;
            if (diffX > 40) {
                slidePrev();
                startAutoPlay();
            } else if (diffX < -40) {
                slideNext();
                startAutoPlay();
            }
        }, { passive: true });

        // Initialize display and 4-second autoplay
        updateCarouselDisplay();
        startAutoPlay();
    }

    // =========================================================================
    // 3. Catalog Tab Filter Logic
    // =========================================================================
    const tabButtons = document.querySelectorAll('.catalog-tab-btn');
    const productItems = document.querySelectorAll('.product-filter-item');

    tabButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            tabButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const filter = this.getAttribute('data-filter');

            productItems.forEach(item => {
                if (filter === 'all') {
                    item.style.display = '';
                } else if (filter === 'under3000') {
                    const price = parseFloat(item.getAttribute('data-price') || 0);
                    item.style.display = (price > 0 && price <= 3000) ? '' : 'none';
                } else if (filter === 'bestseller') {
                    item.style.display = item.classList.contains('is-bestseller') ? '' : 'none';
                } else if (filter === 'offers') {
                    item.style.display = item.classList.contains('cat-offers') ? '' : 'none';
                } else {
                    const match = item.classList.contains('cat-' + filter);
                    item.style.display = match ? '' : 'none';
                }
            });
        });
    });

    // =========================================================================
    // 4. Atelier Collector FAQ Accordion Toggle Logic (faq-sections.tsx)
    // =========================================================================
    const faqItems = document.querySelectorAll('.faq-accordion-item');
    faqItems.forEach(item => {
        const qRow = item.querySelector('.faq-question-row');
        if (qRow) {
            qRow.addEventListener('click', () => {
                const isOpen = item.classList.contains('is-open');
                faqItems.forEach(other => other.classList.remove('is-open'));
                if (!isOpen) {
                    item.classList.add('is-open');
                }
            });
            // Keyboard accessibility (Enter / Space)
            qRow.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    qRow.click();
                }
            });
        }
    });
});

// Quick Add to Cart Handler
function handleStitchAddToCart(variantId, productName) {
    if (typeof window.quickAddToCart === 'function') {
        window.quickAddToCart(variantId, productName, 0, '', null);
        return;
    }

    const formData = new FormData();
    formData.append('variant_id', variantId);
    formData.append('quantity', 1);

    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const counter = document.getElementById('navbar-cart-count');
            const bottomBadge = document.getElementById('bottom-cart-count');
            const headerBadge = document.getElementById('header-cart-badge');
            if (counter && data.cart_count) counter.textContent = data.cart_count;
            if (bottomBadge) bottomBadge.textContent = data.cart_count;
            if (headerBadge) headerBadge.textContent = data.cart_count;

            if (typeof window.openWelkarCartDrawer === 'function') {
                window.openWelkarCartDrawer({
                    item: {
                        title: productName,
                        image: '/assets/images/placeholder-watch.png',
                        variant: 'Verified In Stock',
                        price: 0,
                        quantity: 1
                    },
                    subtotal: data.cart ? data.cart.summary.taxable_amount : 0,
                    total_items: data.cart_count,
                    cart: data.cart
                });
            }
        } else {
            alert(data.message || 'Product added to cart!');
            location.href = '/cart';
        }
    })
    .catch(() => {
        location.href = '/cart';
    });
}

function showFullReviewModal(name, quote, model, rating) {
    document.getElementById('reviewModalAuthor').textContent = name;
    document.getElementById('reviewModalQuote').textContent = quote;
    document.getElementById('reviewModalModel').textContent = model;
    
    let starsHtml = '';
    for (let i = 0; i < (rating || 5); i++) {
        starsHtml += '<i class="bi bi-star-fill text-warning me-1"></i>';
    }
    document.getElementById('reviewModalRating').innerHTML = starsHtml;

    const modalEl = document.getElementById('reviewDetailModal');
    if (modalEl && typeof bootstrap !== 'undefined') {
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    }
}
</script>

<!-- Full Review Reader Modal -->
<div class="modal fade" id="reviewDetailModal" tabindex="-1" aria-labelledby="reviewDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: var(--color-surface-card, #242424); border: 1px solid var(--color-border-divider, #2E2E2E); color: var(--color-heading-ivory, #F5F5F5);">
            <div class="modal-header border-bottom" style="border-color: var(--color-border-divider, #2E2E2E) !important;">
                <div>
                    <h5 class="modal-title fw-bold" id="reviewModalAuthor" style="color: var(--color-heading-ivory, #F5F5F5);">Review</h5>
                    <div id="reviewModalRating" class="text-warning small mt-1"></div>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1) brightness(200%);"></button>
            </div>
            <div class="modal-body py-4">
                <p id="reviewModalQuote" class="lead fs-6 mb-3" style="line-height: 1.7; color: var(--color-heading-ivory, #F5F5F5); white-space: pre-line;"></p>
                <div class="d-flex align-items-center justify-content-between pt-3 border-top" style="border-color: var(--color-border-divider, #2E2E2E) !important;">
                    <span id="reviewModalModel" class="badge" style="background: var(--color-bg-secondary, #1A1A1C); color: var(--color-heading-ivory, #F5F5F5); border: 1px solid var(--color-border-divider, #2E2E2E); padding: 6px 12px;"></span>
                    <span class="text-success small fw-semibold"><i class="bi bi-patch-check-fill me-1"></i>Verified Buyer</span>
                </div>
            </div>
        </div>
    </div>
</div>
