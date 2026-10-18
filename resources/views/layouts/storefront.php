<?php
use App\Services\Seo\SeoService;

$rawSeo = $seo ?? [];
if (!isset($rawSeo['title']) && isset($title)) {
    $rawSeo['title'] = $title;
}
if (!isset($rawSeo['description']) && isset($meta_description)) {
    $rawSeo['description'] = $meta_description;
}
$seoData = SeoService::buildMetadata($rawSeo);

// Base structured data schemas
$globalSchemas = [
    SeoService::getOrganizationSchema(),
    SeoService::getWebSiteSchema(),
];
if (!empty($seoData['schema'])) {
    if (isset($seoData['schema']['@type'])) {
        $globalSchemas[] = $seoData['schema'];
    } else {
        foreach ($seoData['schema'] as $s) {
            $globalSchemas[] = $s;
        }
    }
}
if (isset($pageSchema) && is_array($pageSchema)) {
    $globalSchemas[] = $pageSchema;
}
?>
<!DOCTYPE html>
<html lang="<?= e(\App\Support\Session::get('locale', 'en')) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($seoData['title']) ?></title>
    <meta name="description" content="<?= e($seoData['description']) ?>">
    <link rel="canonical" href="<?= e($seoData['canonical']) ?>">
    <meta name="robots" content="<?= e($seoData['robots']) ?>">

    <!-- Alternate Language Hreflang Tags -->
    <link rel="alternate" hreflang="en" href="<?= e($seoData['hreflang']['en']) ?>">
    <link rel="alternate" hreflang="hi" href="<?= e($seoData['hreflang']['hi']) ?>">
    <link rel="alternate" hreflang="x-default" href="<?= e($seoData['hreflang']['x-default']) ?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="<?= e($seoData['og_type']) ?>">
    <meta property="og:site_name" content="Welkar">
    <meta property="og:title" content="<?= e($seoData['og_title']) ?>">
    <meta property="og:description" content="<?= e($seoData['og_description']) ?>">
    <meta property="og:url" content="<?= e($seoData['og_url']) ?>">
    <meta property="og:image" content="<?= e($seoData['og_image']) ?>">
    <meta property="og:locale" content="<?= e($seoData['locale']) ?>">

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="<?= e($seoData['twitter_card']) ?>">
    <meta name="twitter:title" content="<?= e($seoData['twitter_title']) ?>">
    <meta name="twitter:description" content="<?= e($seoData['twitter_description']) ?>">
    <meta name="twitter:image" content="<?= e($seoData['twitter_image']) ?>">

    <!-- Structured Data (JSON-LD) -->
    <?= SeoService::renderJsonLd($globalSchemas) ?>

    <!-- Meta Pixel & Conversions Tracking -->
    <?php 
        $metaPixelId = \App\Services\Marketing\MetaConversionsService::getPixelId();
        if (!empty($metaPixelId)): 
    ?>
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '<?= e($metaPixelId) ?>');
    fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=<?= urlencode($metaPixelId) ?>&ev=PageView&noscript=1"
    /></noscript>
    <?php endif; ?>

    <!-- Google Fonts: Inter & DM Serif Display (design.md locked typography) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Welkar Custom Animations & Ticker Styles -->
    <link rel="stylesheet" href="/assets/css/welkar.css?v=<?= @filemtime(dirname(__DIR__, 3) . '/public/assets/css/welkar.css') ?: time() ?>">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/favicon.png">
    <style>
        :root {
            --color-bg-primary: #0B0B0C;
            --color-bg-secondary: #1A1A1C;
            --color-surface-card: #242424;
            --color-heading-ivory: #F5F5F5;
            --color-body-text: #A0A0A0;
            --color-border-divider: #2E2E2E;
            --color-accent-deep-red: #E63946;
            --color-accent-red-hover: #C61F32;
            --color-champagne-highlight: #D4AF7C;
            --stitch-canvas: #0B0B0C;
            --stitch-card: #242424;
            --stitch-border: #2E2E2E;

            --w-navy: #0B0B0C;
            --w-slate: #1A1A1C;
            --w-crimson: #E63946;
            --w-crimson-hover: #C61F32;
            --w-steel: #A0A0A0;
            --w-silver: #2E2E2E;
            --w-canvas: #0B0B0C;
            --w-card: #242424;
            --w-bg: #0B0B0C;
            --w-surface: #1A1A1C;
            --w-border: #2E2E2E;
            --w-text: #F5F5F5;
            --w-muted: #A0A0A0;
            --w-accent: #E63946;
            --w-gold: #D4AF7C;
            --w-radius: 6px;
        }
        body {
            font-family: var(--font-ui, 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif);
            background-color: #0B0B0C !important;
            color: #A0A0A0 !important;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        h1, h2, h3, h4, h5, h6, .font-serif, .editorial-heading {
            font-family: var(--font-display, 'Playfair Display', 'DM Serif Display', Georgia, serif);
            letter-spacing: -0.01em;
            color: #F5F5F5 !important;
            font-weight: 500;
        }
        .bg-surface-canvas,
        .hero-08-section {
            background-color: #0B0B0C !important;
            background: #0B0B0C !important;
        }
        .announcement-bar {
            background-color: var(--color-bg-primary, #0B0B0C);
            color: var(--color-body-text, #A0A0A0);
            font-size: 11.5px;
            padding: 9px 0;
            text-align: center;
            letter-spacing: 0.6px;
            font-weight: 500;
            border-bottom: 1px solid var(--color-border-divider, #2E2E2E);
        }
        .announcement-bar .accent-icon {
            color: var(--color-champagne-highlight, #D4AF7C);
        }
        .welkar-navbar {
            border-bottom: 1px solid var(--color-border-divider, #2E2E2E);
            background: rgba(11, 11, 12, 0.94);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            position: sticky;
            top: 0;
            z-index: 1020;
            transition: box-shadow 0.3s ease, border-color 0.3s ease;
        }
        .navbar-brand {
            font-family: var(--font-ui, 'Inter', sans-serif);
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 2px;
            color: var(--color-heading-ivory, #F5F5F5) !important;
            text-transform: uppercase;
        }
        .welkar-header-logo {
            height: 74px !important;
            max-height: 85px !important;
            width: auto !important;
            object-fit: contain !important;
            display: block !important;
        }
        @media (max-width: 991px) {
            .welkar-header-logo {
                height: 58px !important;
            }
        }
        @media (max-width: 480px) {
            .welkar-header-logo {
                height: 48px !important;
            }
        }
        .nav-link {
            font-family: var(--font-ui, 'Inter', sans-serif);
            font-size: 13.5px;
            font-weight: 500;
            color: var(--color-heading-ivory, #F5F5F5) !important;
            padding: 8px 14px !important;
            transition: color var(--motion-fast) var(--ease-standard);
        }
        .nav-link:hover {
            color: var(--color-accent-deep-red, #E63946) !important;
        }
        .search-container {
            position: relative;
            width: 260px;
        }
        .search-input {
            border-radius: var(--radius-sm, 6px);
            font-size: 13px;
            padding-left: 36px;
            height: 42px;
            border: 1px solid var(--color-border-divider, #2E2E2E);
            background: var(--color-bg-secondary, #1A1A1C);
            color: var(--color-heading-ivory, #F5F5F5);
        }
        .search-input:focus {
            background: var(--color-surface-card, #242424);
            border-color: var(--color-accent-deep-red, #E63946);
            color: var(--color-heading-ivory, #F5F5F5);
            box-shadow: 0 0 0 2px rgba(230, 57, 70, 0.25);
        }
        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--color-muted-text, #737373);
            font-size: 13px;
        }
        #autocomplete-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: var(--color-bg-secondary, #1A1A1C);
            border: 1px solid var(--color-border-divider, #2E2E2E);
            border-radius: var(--radius-sm, 6px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.5);
            display: none;
            z-index: 1050;
            margin-top: 5px;
        }
        .badge-live-sync {
            position: absolute;
            top: 10px;
            left: 10px;
            background: var(--color-accent-deep-red, #E63946);
            color: #FFFFFF;
            font-size: 10px;
            padding: 3px 8px;
            border-radius: var(--radius-xs, 4px);
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        .footer-welkar {
            background: var(--color-bg-primary, #0B0B0C);
            color: var(--color-body-text, #A0A0A0);
            padding-top: 60px;
            padding-bottom: 30px;
            border-top: 1px solid var(--color-border-divider, #2E2E2E);
        }
        .footer-welkar h5 {
            color: var(--color-heading-ivory, #F5F5F5);
            font-size: 15px;
            letter-spacing: 0.5px;
            margin-bottom: 18px;
        }
        .footer-welkar a {
            color: var(--color-body-text, #A0A0A0);
            text-decoration: none;
            font-size: 13px;
            line-height: 2;
            transition: color var(--motion-fast) var(--ease-standard);
        }
        .footer-welkar a:hover {
            color: var(--color-accent-deep-red, #E63946);
        }
    </style>
</head>
<body>
    <!-- Skip to Content Link (Accessibility WCAG 2.1 AA) -->
    <a href="#main-content" class="visually-hidden-focusable position-fixed top-0 start-0 z-3 p-3 bg-dark text-white text-decoration-none fw-semibold shadow">Skip to main content</a>

    <!-- Announcement Bar: Horological Modernity -->
    <div class="announcement-bar py-1">
        <div class="container d-flex flex-wrap justify-content-center align-items-center gap-2 gap-md-4 text-uppercase" style="font-size: 11px; letter-spacing: 0.8px; font-weight: 500;">
            <span><i class="bi bi-shield-check accent-icon me-1"></i>Complimentary Insured Shipping Across India</span>
            <span class="d-none d-md-inline text-secondary opacity-50">&bull;</span>
            <span><i class="bi bi-cash-coin accent-icon me-1"></i>Cash on Delivery (COD) Available</span>
            <span class="d-none d-lg-inline text-secondary opacity-50">&bull;</span>
            <span class="d-none d-lg-inline"><i class="bi bi-arrow-repeat accent-icon me-1"></i>7-Day Hassle-Free Returns</span>
            <span class="d-none d-xl-inline text-secondary opacity-50">&bull;</span>
            <span class="d-none d-xl-inline"><i class="bi bi-award accent-icon me-1"></i>2-Year Movement Warranty</span>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="welkar-navbar">
        <div class="container py-2">
            <div class="d-flex align-items-center justify-content-between">
                <!-- Mobile Left: Hamburger Toggle Button (d-lg-none) -->
                <button class="btn btn-sm btn-icon-touch border-0 text-dark p-2 d-lg-none shadow-none" 
                        type="button" 
                        data-bs-toggle="offcanvas" 
                        data-bs-target="#welkarMobileMenu" 
                        aria-controls="welkarMobileMenu" 
                        aria-label="Open Navigation Menu">
                    <i class="bi bi-list fs-3" style="color: var(--color-heading-ivory, #F5F5F5);"></i>
                </button>

                <!-- Brand Logo -->
                <a class="navbar-brand m-0 d-inline-flex align-items-center" href="/" title="Welkar Timepieces">
                    <img src="/assets/images/welkar-logo.png" alt="Welkar" class="welkar-header-logo" style="height: 74px; max-height: 85px; width: auto; object-fit: contain;">
                </a>

                <!-- Nav Links Desktop: Clean Collections Structure -->
                <nav class="d-none d-lg-flex align-items-center gap-1">
                    <a class="nav-link fw-semibold" href="/">Home</a>
                    <a class="nav-link fw-semibold" href="/collections/mens-watches">Men's Watches</a>
                    <a class="nav-link fw-semibold" href="/collections/womens-watches">Women's Watches</a>
                    <a class="nav-link fw-semibold" href="/collections/unisex-minimalist">Unisex & Minimalist</a>
                    <a class="nav-link fw-semibold" style="color: var(--color-accent-deep-red, #E63946) !important;" href="/collections/offers">
                        Offers
                    </a>
                    <a class="nav-link fw-semibold" href="/about">About Us</a>
                </nav>

                <!-- Search & Actions -->
                <div class="d-flex align-items-center gap-2 gap-md-3">
                    <!-- Mobile Search Trigger Button (d-md-none) -->
                    <button class="btn btn-sm btn-icon-touch border-0 text-dark p-2 d-md-none shadow-none" 
                            type="button" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#mobileSearchCollapse" 
                            aria-expanded="false" 
                            aria-controls="mobileSearchCollapse" 
                            aria-label="Toggle Mobile Search">
                        <i class="bi bi-search fs-5" style="color: var(--color-heading-ivory, #F5F5F5);"></i>
                    </button>

                    <!-- Desktop Autocomplete Search Form -->
                    <form action="/catalog" method="GET" class="search-container d-none d-md-block m-0" role="search">
                        <i class="bi bi-search search-icon" aria-hidden="true"></i>
                        <input type="search" name="q" id="site-search-input" class="form-control search-input" placeholder="<?= __('search_placeholder') ?>" autocomplete="off" aria-label="Search timepieces">
                        <div id="autocomplete-dropdown"></div>
                    </form>

                    <!-- Customer Account (Desktop) -->
                    <?php if (auth_user()): ?>
                        <a href="/account" class="icon-pill-expand d-none d-md-inline-flex" style="--gradient-from: #202522; --gradient-to: #39423B;" title="<?= __('account') ?>">
                            <span class="pill-icon"><i class="bi bi-person-check-fill"></i></span>
                            <span class="pill-label"><?= e(explode(' ', auth_user()['full_name'])[0]) ?></span>
                        </a>
                    <?php else: ?>
                        <a href="/account/login" class="icon-pill-expand d-none d-md-inline-flex" style="--gradient-from: #202522; --gradient-to: #292F2B;" title="<?= __('login') ?>">
                            <span class="pill-icon"><i class="bi bi-person"></i></span>
                            <span class="pill-label"><?= __('login') ?></span>
                        </a>
                    <?php endif; ?>

                    <!-- Shopping Cart -->
                    <?php
                        $cartData = \App\Services\Cart\CartService::getCart();
                        $cartItemsCount = $cartData['summary']['total_items'] ?? 0;
                    ?>
                    <a href="/cart" class="icon-pill-expand position-relative" style="--gradient-from: #202522; --gradient-to: #9A7950;" title="<?= __('cart') ?>">
                        <span class="pill-icon"><i class="bi bi-bag-fill"></i></span>
                        <span class="pill-label d-none d-md-inline"><?= __('cart') ?></span>
                        <span id="header-cart-count" class="pill-badge"><?= $cartItemsCount ?></span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Mobile Expandable Search Bar (d-md-none) -->
        <div class="collapse bg-white border-top border-bottom shadow-sm d-md-none" id="mobileSearchCollapse">
            <div class="container py-2">
                <form action="/catalog" method="GET" class="position-relative m-0" role="search">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="font-size: 14px;" aria-hidden="true"></i>
                    <input type="search" name="q" id="mobile-search-input" class="form-control form-control-sm ps-5 pe-3" placeholder="<?= __('search_placeholder') ?>" autocomplete="off" style="height: 42px; border-radius: 6px; border: 1px solid var(--color-border-default, #DDD9D0); font-size: 13px;" aria-label="Search timepieces">
                    <div id="mobile-autocomplete-dropdown" style="position: absolute; top: 100%; left: 0; right: 0; background: #FFFFFF; border: 1px solid var(--color-border-default, #DDD9D0); border-radius: 6px; box-shadow: 0 10px 25px rgba(0,0,0,0.12); display: none; z-index: 1060; margin-top: 4px;"></div>
                </form>
                <div class="d-flex gap-2 overflow-x-auto pt-2 pb-1" style="scrollbar-width: none;">
                    <a href="/collections/mens-watches" class="badge bg-light text-dark text-decoration-none border px-2 py-1" style="font-weight: 500; font-size: 11px;">Men's</a>
                    <a href="/collections/womens-watches" class="badge bg-light text-dark text-decoration-none border px-2 py-1" style="font-weight: 500; font-size: 11px;">Women's</a>
                    <a href="/catalog?dial_type=Analog" class="badge bg-light text-dark text-decoration-none border px-2 py-1" style="font-weight: 500; font-size: 11px;">Analog</a>
                    <a href="/catalog?dial_type=Chronograph" class="badge bg-light text-dark text-decoration-none border px-2 py-1" style="font-weight: 500; font-size: 11px;">Chronograph</a>
                    <a href="/collections/offers" class="badge bg-light text-dark text-decoration-none border px-2 py-1" style="font-weight: 500; font-size: 11px;">Offers</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Navigation Offcanvas Drawer (d-lg-none) -->
    <div class="offcanvas offcanvas-start welkar-mobile-offcanvas d-lg-none" tabindex="-1" id="welkarMobileMenu" aria-labelledby="welkarMobileMenuLabel">
        <div class="offcanvas-header border-bottom py-3 px-4">
            <a class="navbar-brand m-0 d-inline-flex align-items-center" href="/" title="Welkar Timepieces">
                <img src="/assets/images/welkar-logo.png" alt="Welkar" style="height: 54px; width: auto; object-fit: contain;">
            </a>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0 d-flex flex-column justify-content-between">
            <div>
                <!-- User Status Card in Drawer -->
                <div class="p-3 mx-3 my-3 rounded-3" style="background: var(--color-surface-100, #EBF0F7); border: 1px solid var(--color-border-default, #E1E7F0);">
                    <?php if (auth_user()): ?>
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-dark text-white d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; font-weight: 700;">
                                    <?= strtoupper(substr(auth_user()['full_name'], 0, 1)) ?>
                                </div>
                                <div>
                                    <div class="fw-bold small text-dark"><?= e(auth_user()['full_name']) ?></div>
                                    <div class="text-muted" style="font-size: 11px;"><?= e(auth_user()['phone'] ?: auth_user()['email']) ?></div>
                                </div>
                            </div>
                            <a href="/account" class="btn btn-sm btn-outline-dark" style="font-size: 11.5px; border-radius: 4px;">Account</a>
                        </div>
                    <?php else: ?>
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="fw-bold small text-dark">Welcome to WELKAR</div>
                                <div class="text-muted" style="font-size: 11.5px;">Login for express checkout & order tracking</div>
                            </div>
                            <a href="/account/login" class="btn btn-sm btn-welkar px-3" style="font-size: 11.5px; height: 34px;">Login</a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Collections Navigation Links -->
                <div class="px-3 pb-3">
                    <div class="text-uppercase fw-semibold mb-2 px-2" style="font-size: 10px; letter-spacing: 1.5px; color: var(--color-text-muted, #718096);">Collections & Atelier</div>
                    <nav class="nav flex-column gap-1">
                        <a class="nav-link py-2 px-3 rounded-2 d-flex align-items-center justify-content-between text-dark" href="/">
                            <span><i class="bi bi-house me-3 text-secondary"></i>Home</span>
                            <i class="bi bi-chevron-right text-muted small"></i>
                        </a>
                        <a class="nav-link py-2 px-3 rounded-2 d-flex align-items-center justify-content-between text-dark" href="/collections/mens-watches">
                            <span><i class="bi bi-watch me-3 text-secondary"></i>Men's Watches</span>
                            <i class="bi bi-chevron-right text-muted small"></i>
                        </a>
                        <a class="nav-link py-2 px-3 rounded-2 d-flex align-items-center justify-content-between text-dark" href="/collections/womens-watches">
                            <span><i class="bi bi-gem me-3 text-secondary"></i>Women's Watches</span>
                            <i class="bi bi-chevron-right text-muted small"></i>
                        </a>
                        <a class="nav-link py-2 px-3 rounded-2 d-flex align-items-center justify-content-between text-dark" href="/collections/unisex-minimalist">
                            <span><i class="bi bi-circle me-3 text-secondary"></i>Unisex & Minimalist</span>
                            <i class="bi bi-chevron-right text-muted small"></i>
                        </a>
                        <a class="nav-link py-2 px-3 rounded-2 d-flex align-items-center justify-content-between" style="color: var(--color-accent-700, #856744) !important;" href="/collections/offers">
                            <span><i class="bi bi-tag-fill me-3" style="color: var(--color-accent-600, #C59B3F);"></i>Special Offers</span>
                            <span class="badge" style="background: var(--color-accent-600, #C59B3F); font-size: 9px;">UP TO 50%</span>
                        </a>
                        <a class="nav-link py-2 px-3 rounded-2 d-flex align-items-center justify-content-between text-dark" href="/catalog">
                            <span><i class="bi bi-grid-3x3-gap me-3 text-secondary"></i>Browse Full Catalog</span>
                            <i class="bi bi-chevron-right text-muted small"></i>
                        </a>
                        <a class="nav-link py-2 px-3 rounded-2 d-flex align-items-center justify-content-between text-dark" href="/about">
                            <span><i class="bi bi-building me-3 text-secondary"></i>About Welkar Atelier</span>
                            <i class="bi bi-chevron-right text-muted small"></i>
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Drawer Footer: Language & Guarantees -->
            <div class="p-3 border-top bg-light">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="small fw-semibold text-muted">Select Language</span>
                    <div class="btn-group btn-group-sm">
                        <a href="/lang/en" class="btn <?= \App\Support\Session::get('locale', 'en') === 'en' ? 'btn-dark' : 'btn-outline-secondary' ?> py-1 px-3">English</a>
                        <a href="/lang/hi" class="btn <?= \App\Support\Session::get('locale', 'en') === 'hi' ? 'btn-dark' : 'btn-outline-secondary' ?> py-1 px-3">हिंदी</a>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-around text-center small text-muted" style="font-size: 11px;">
                    <div><i class="bi bi-shield-check d-block fs-5 text-dark"></i>Insured Shipping</div>
                    <div><i class="bi bi-cash-coin d-block fs-5 text-dark"></i>COD Available</div>
                    <div><i class="bi bi-award d-block fs-5 text-dark"></i>2-Yr Warranty</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    <div class="container mt-3">
        <?php if ($success = flash('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i><?= e($success) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if ($error = flash('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><?= e($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if ($info = flash('info')): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="bi bi-info-circle-fill me-2"></i><?= e($info) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Main Page Content -->
    <main id="main-content">
        <?= $content ?>
    </main>

    <!-- Footer: Welkar Company Profile & Customer Care -->
    <?php
        $footerCompany = \App\Services\Company\CompanyProfileService::getProfile();
    ?>
    <footer class="footer-01 pt-5 border-top">
        <div class="footer-01-container pt-4">
            <div class="row g-5">
                <!-- Col 1: Brand & Verified Tagline -->
                <div class="col-lg-4">
                    <div class="d-flex flex-column gap-3">
                        <a href="/" class="footer-01-brand mb-1">
                            <img src="/assets/images/welkar-logo.png" alt="WELKAR" style="height: 64px; max-height: 75px; width: auto; object-fit: contain;">
                        </a>
                        <div class="badge rounded-pill align-self-start fw-semibold px-3 py-1" style="background: rgba(212, 175, 124, 0.12); color: var(--color-champagne-highlight, #D4AF7C); border: 1px solid rgba(212, 175, 124, 0.25); font-size: 11px; letter-spacing: 0.5px;">
                            <?= e($footerCompany['tagline'] ?? 'Precision, Heritage, and Trust—Since 2002.') ?>
                        </div>
                        <p class="footer-01-desc mb-2" style="font-size: 13.5px; line-height: 1.7; color: var(--color-body-text, #A0A0A0);">
                            <?= e($footerCompany['footer_summary'] ?? 'Founded in 2002 by Mr. Sumit Singh and Mr. Nevil Sharma, WELKAR unites creative vision and technical precision to deliver timepieces built on heritage and trust.') ?>
                        </p>
                        <div class="pt-2">
                            <span class="text-white-50 small d-block mb-1"><i class="bi bi-envelope me-2 text-warning"></i>Concierge Support</span>
                            <a href="mailto:welkarstore@gmail.com" class="text-white text-decoration-none fw-semibold small">welkarstore@gmail.com</a>
                            <span class="text-white-50 small d-block mt-2 mb-1"><i class="bi bi-whatsapp me-2 text-success"></i>WhatsApp Concierge</span>
                            <a href="https://wa.me/919876543210" class="text-white text-decoration-none fw-semibold small" target="_blank" rel="noopener noreferrer">+91 98765 43210</a>
                        </div>
                    </div>
                </div>

                <!-- Col 2-5: Nav Columns -->
                <div class="col-lg-8">
                    <div class="row g-4 row-cols-2 row-cols-sm-4">
                        <!-- Col: Collections -->
                        <div class="col">
                            <h4 class="footer-01-nav-title">Collections</h4>
                            <ul class="list-unstyled d-flex flex-column gap-2 mb-0">
                                <li><a href="/catalog?gender=Men" class="footer-01-link">Men's Watches</a></li>
                                <li><a href="/catalog?gender=Women" class="footer-01-link">Women's Watches</a></li>
                                <li><a href="/catalog?category=minimalist" class="footer-01-link">Minimalist Dials</a></li>
                                <li><a href="/catalog?category=chronograph" class="footer-01-link">Chronographs</a></li>
                                <li><a href="/catalog" class="footer-01-link">All Timepieces</a></li>
                            </ul>
                        </div>

                        <!-- Col: Company Profile -->
                        <div class="col">
                            <h4 class="footer-01-nav-title">Company Profile</h4>
                            <ul class="list-unstyled d-flex flex-column gap-2 mb-0">
                                <li><a href="/about" class="footer-01-link">About WELKAR</a></li>
                                <li><a href="/about#our-story" class="footer-01-link">Our Story (2002)</a></li>
                                <li><a href="/about#our-story" class="footer-01-link">Meet Our Founders</a></li>
                                <li><a href="/about#contact" class="footer-01-link">Contact &amp; Concierge</a></li>
                                <li><a href="/page/faqs" class="footer-01-link">Company FAQs</a></li>
                            </ul>
                        </div>

                        <!-- Col: Client Care -->
                        <div class="col">
                            <h4 class="footer-01-nav-title">Client Care</h4>
                            <ul class="list-unstyled d-flex flex-column gap-2 mb-0">
                                <li><a href="/track-order" class="footer-01-link">Track Delivery</a></li>
                                <li><a href="/page/warranty" class="footer-01-link">2-Year Movement Warranty</a></li>
                                <li><a href="/page/returns-exchanges" class="footer-01-link">7-Day Returns</a></li>
                                <li><a href="/page/shipping-policy" class="footer-01-link">Insured Transit</a></li>
                                <li><a href="/admin/login" class="footer-01-link">Admin Portal</a></li>
                            </ul>
                        </div>

                        <!-- Col: Legal & Compliance -->
                        <div class="col">
                            <h4 class="footer-01-nav-title">Policies</h4>
                            <ul class="list-unstyled d-flex flex-column gap-2 mb-0">
                                <li><a href="/page/privacy-policy" class="footer-01-link">Privacy Policy</a></li>
                                <li><a href="/page/terms-conditions" class="footer-01-link">Terms of Service</a></li>
                                <li><a href="/page/shipping-policy" class="footer-01-link">Shipping Terms</a></li>
                                <li><a href="/page/returns-exchanges" class="footer-01-link">Return Guidelines</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Sub-bar -->
            <div class="mt-5 pt-4 pb-3 border-top border-secondary border-opacity-25 d-flex flex-column-reverse flex-sm-row align-items-sm-center justify-content-between gap-3">
                <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-3 gap-sm-4 text-white-50 small">
                    <span>&copy; <?= date('Y') ?> WELKAR. Precision, Heritage, and Trust—Since 2002. All rights reserved.</span>
                </div>
                <div class="d-flex align-items-center gap-3 text-white-50 small">
                    <a href="/about" class="text-white-50 text-decoration-none">About Us</a>
                </div>
            </div>
        </div>

        <!-- Giant Watermark Typography -->
        <div class="w-100 overflow-hidden" aria-hidden="true">
            <div class="footer-01-watermark">
                WELKAR
            </div>
        </div>
    </footer>

    <!-- Mobile Persistent Bottom Navigation Bar (Thumb Zone) (d-md-none) -->
    <?php
        $currentUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    ?>
    <nav class="welkar-bottom-nav d-md-none" id="welkarMobileBottomNav" aria-label="Mobile Navigation">
        <a href="/" class="welkar-bottom-nav-item <?= ($currentUri === '/') ? 'active' : '' ?>">
            <i class="bi bi-house<?= ($currentUri === '/') ? '-fill' : '' ?>"></i>
            <span>Home</span>
        </a>
        <a href="/catalog" class="welkar-bottom-nav-item <?= (str_starts_with($currentUri, '/catalog') || str_starts_with($currentUri, '/collections')) ? 'active' : '' ?>">
            <i class="bi bi-grid<?= (str_starts_with($currentUri, '/catalog') || str_starts_with($currentUri, '/collections')) ? '-fill' : '' ?>"></i>
            <span>Catalog</span>
        </a>
        <a href="/cart" class="welkar-bottom-nav-item <?= ($currentUri === '/cart') ? 'active' : '' ?>">
            <div class="position-relative d-inline-block">
                <i class="bi bi-bag<?= ($currentUri === '/cart') ? '-fill' : '' ?>"></i>
                <span id="bottom-cart-count" class="welkar-bottom-nav-badge"><?= $cartItemsCount ?></span>
            </div>
            <span>Bag</span>
        </a>
        <a href="<?= auth_user() ? '/account' : '/account/login' ?>" class="welkar-bottom-nav-item <?= (str_starts_with($currentUri, '/account')) ? 'active' : '' ?>">
            <i class="bi bi-person<?= (str_starts_with($currentUri, '/account')) ? '-fill' : '' ?>"></i>
            <span><?= auth_user() ? 'Account' : 'Login' ?></span>
        </a>
    </nav>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Search Autocomplete & Cart Live Refresh Script -->
    <script>
        function setupSearchAutocomplete(inputId, dropdownId) {
            const input = document.getElementById(inputId);
            const drop = document.getElementById(dropdownId);
            if (!input || !drop) return;

            let timeout = null;
            input.addEventListener('input', function() {
                clearTimeout(timeout);
                const q = this.value.trim();
                if (q.length < 2) {
                    drop.style.display = 'none';
                    return;
                }
                timeout = setTimeout(() => {
                    fetch('/api/search/autocomplete?q=' + encodeURIComponent(q))
                        .then(res => res.json())
                        .then(data => {
                            if (data.results && data.results.length > 0) {
                                let html = '<div class="p-2">';
                                data.results.forEach(item => {
                                    html += `
                                        <a href="/product/${item.slug}" class="d-flex align-items-center p-2 text-decoration-none text-dark border-bottom hover-bg">
                                            <img src="${item.image_url}" width="36" height="36" class="rounded object-fit-cover me-2" alt="">
                                            <div class="text-truncate">
                                                <div class="fw-bold small text-truncate">${item.name}</div>
                                                <div class="text-muted" style="font-size: 11px;">₹${Number(item.base_price).toLocaleString('en-IN')} &bull; ${item.brand_name}</div>
                                            </div>
                                        </a>`;
                                });
                                html += '</div>';
                                drop.innerHTML = html;
                                drop.style.display = 'block';
                            } else {
                                drop.innerHTML = '<div class="p-3 text-muted small text-center">No watches found</div>';
                                drop.style.display = 'block';
                            }
                        })
                        .catch(() => {
                            drop.style.display = 'none';
                        });
                }, 200);
            });

            document.addEventListener('click', (e) => {
                if (!input.contains(e.target) && !drop.contains(e.target)) {
                    drop.style.display = 'none';
                }
            });
        }

        setupSearchAutocomplete('site-search-input', 'autocomplete-dropdown');
        setupSearchAutocomplete('mobile-search-input', 'mobile-autocomplete-dropdown');
    </script>

    <!-- Customer Support Chat & Ticket Helpdesk Widget with Floating Stack -->
    <?php include __DIR__ . '/../partials/support_widget.php'; ?>
    <?php include __DIR__ . '/../partials/cart_drawer.php'; ?>

    <!-- Back to Top & Floating Actions Script -->
    <script>
        const bttBtn = document.getElementById('welkar-back-to-top');
        if (bttBtn) {
            window.addEventListener('scroll', () => {
                if (window.scrollY > 350) {
                    bttBtn.classList.remove('d-none');
                    bttBtn.classList.add('d-flex');
                } else {
                    bttBtn.classList.remove('d-flex');
                    bttBtn.classList.add('d-none');
                }
            }, { passive: true });
            bttBtn.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }
    </script>

    <!-- GSAP for Card Fan Carousel -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <!-- Logo Cloud 15: BorderBeam & Dynamic Shimmer Wave Engine -->
    <script src="/assets/js/logo-cloud-beam.js"></script>
    <!-- Card-7 Interactive 3D Perspective Tilt Engine -->
    <script src="/assets/js/card-7-tilt.js"></script>
    <!-- Welkar Horological Motion & Scroll Reveal Engine -->
    <script src="/assets/js/welkar-motion.js"></script>
</body>
</html>
