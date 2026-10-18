<?php
/**
 * Welkar Horology Atelier — Amazon A+ Style Product Detail Page
 * Calibrated with 15 Structured Conversion & Storytelling Modules
 */
$primaryImage = $images[0]['image_url'] ?? '/assets/images/products/placeholder.jpg';
$defaultVariant = $variants[0] ?? [
    'id' => 1,
    'sku' => $product['base_sku'],
    'price' => $product['base_price'],
    'compare_at_price' => $product['compare_at_price'],
    'stock_quantity' => 25
];
$displayPrice = $defaultVariant['price'] ?? $product['base_price'];
$comparePrice = $defaultVariant['compare_at_price'] ?? $product['compare_at_price'];
$hasLiveDial = !empty($product['is_live_dial_animated']) && !empty($product['dial_base_image']);
$totalReviews = $review_stats['total'] ?? count($reviews);
$avgRating = $review_stats['average'] ?? 5.0;
?>

<div class="product-pdp-wrapper pb-5">
    <!-- =========================================================================
         MODULE 1: BREADCRUMBS
         ========================================================================= -->
    <div class="product-pdp-breadcrumb py-3 mb-4 border-bottom" style="border-color: #242428 !important; background: #0B0B0C;">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small m-0" style="font-family: var(--font-ui, 'Inter', sans-serif); font-size: 12px;">
                    <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-secondary">Home</a></li>
                    <li class="breadcrumb-item"><a href="/catalog" class="text-decoration-none text-secondary">Catalog</a></li>
                    <li class="breadcrumb-item"><a href="/collections/<?= strtolower($product['gender'] === 'Men' ? 'mens-watches' : ($product['gender'] === 'Women' ? 'womens-watches' : 'unisex-minimalist')) ?>" class="text-decoration-none text-secondary"><?= e($product['gender']) ?>'s Watches</a></li>
                    <li class="breadcrumb-item active text-white fw-semibold text-truncate" style="max-width: 240px;" aria-current="page"><?= e($product['name']) ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- =========================================================================
         MODULE 2: LUXURY HOROLOGY HERO / PRIMARY PRODUCT DETAIL
         ========================================================================= -->
    <div class="container mb-5">
        <div class="row g-lg-5 g-4 align-items-start">
            <!-- Left: Multi-Angle Gallery & Live Animated Dial Toggle -->
            <div class="col-lg-6">
                <div class="position-sticky" style="top: 90px;">
                    <!-- Main Image / Live Dial Viewport -->
                    <div class="pdp-viewport-container position-relative border text-center overflow-hidden" 
                         style="background-color: #141416 !important; border-color: #28282B !important; border-radius: 16px; aspect-ratio: 1/1; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                        
                        <!-- Top-Left Movement Pill Badge (Matches Reference: CALIBER JAPAN 2026) -->
                        <div class="position-absolute top-0 start-0 m-3 d-flex align-items-center gap-1 shadow-sm"
                             style="background: rgba(20, 20, 22, 0.88); backdrop-filter: blur(8px); border: 1px solid rgba(230, 57, 70, 0.4); color: #E63946; font-size: 11px; font-weight: 700; letter-spacing: 0.5px; border-radius: 9999px; padding: 5px 12px; z-index: 25;">
                            <span style="display:inline-block; width:6px; height:6px; border-radius:50%; background:#E63946;"></span>
                            <span>CALIBER JAPAN 2026</span>
                        </div>

                        <!-- Static Image View -->
                        <div id="static-dial-view" class="w-100 h-100 d-flex align-items-center justify-content-center p-4">
                            <img id="main-product-image" 
                                 src="<?= e($primaryImage) ?>"
                                 alt="<?= e($product['name']) ?>" 
                                 class="img-fluid" 
                                 style="max-width: 90%; max-height: 90%; object-fit: contain; transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);">
                        </div>

                        <!-- Live Sweeping Dial Simulation View (if calibrated) -->
                        <?php if ($hasLiveDial): ?>
                        <div id="live-dial-view" class="w-100 h-100 position-absolute inset-0 d-none align-items-center justify-content-center p-4" style="z-index: 10;">
                            <div class="welkar-dial-container" style="max-width: 360px; max-height: 360px; position: relative;">
                                <img src="<?= e($product['dial_base_image']) ?>" class="welkar-dial-face" alt="Live Calibrated Dial">
                                <div class="welkar-dial-overlay" id="dial-overlay-hands">
                                    <svg viewBox="0 0 100 100" class="welkar-dial-svg">
                                        <!-- Hour Hand -->
                                        <line id="svg-hour-hand" x1="50" y1="50" x2="50" y2="28" stroke="#FFFFFF" stroke-width="2.8" stroke-linecap="round" />
                                        <!-- Minute Hand -->
                                        <line id="svg-min-hand" x1="50" y1="50" x2="50" y2="18" stroke="#FFFFFF" stroke-width="2.0" stroke-linecap="round" />
                                        <!-- Sweeping Second Hand -->
                                        <line id="svg-sec-hand" x1="50" y1="54" x2="50" y2="14" stroke="#E63946" stroke-width="1.2" stroke-linecap="round" />
                                        <!-- Center Jewel Hub -->
                                        <circle cx="50" cy="50" r="3.2" fill="#E63946" stroke="#FFFFFF" stroke-width="0.8" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Live Hands Toggle Switch Badge -->
                        <?php if ($hasLiveDial): ?>
                        <button type="button" id="btn-toggle-live-dial" class="btn btn-sm position-absolute top-0 end-0 m-3 d-flex align-items-center gap-2 shadow-sm"
                                style="border-radius: 9999px; background: rgba(20, 20, 22, 0.88); backdrop-filter: blur(8px); color: #fff; font-size: 11px; font-weight: 600; border: 1px solid rgba(223, 183, 92, 0.4); padding: 5px 12px; z-index: 25;">
                            <i class="bi bi-clock-history" style="color: var(--color-accent-500, #DFB75C);"></i>
                            <span id="live-dial-text">Live Clock</span>
                        </button>
                        <?php endif; ?>

                        <!-- Tap to Zoom Dial Badge (Matches Reference: Bottom-Right ⤢ Button) -->
                        <button type="button" class="btn position-absolute bottom-0 end-0 m-3 d-flex align-items-center justify-content-center shadow"
                                style="width: 40px; height: 40px; border-radius: 50%; background: rgba(20, 20, 22, 0.88); backdrop-filter: blur(8px); color: #DFB75C; border: 1px solid rgba(223, 183, 92, 0.4); z-index: 25; cursor: pointer;"
                                onclick="openPdpZoomModal()"
                                title="Tap to Zoom Dial Details"
                                aria-label="Tap to Zoom Dial">
                            <i class="bi bi-arrows-fullscreen" style="font-size: 15px;"></i>
                        </button>
                    </div>

                    <!-- Centered Pagination Dots (Matches Reference: Active Red Dot ● ○ ○ ○) -->
                    <div class="d-flex justify-content-center align-items-center gap-2 mt-3" id="pdp-gallery-dots">
                        <?php 
                        $imgCount = max(count($images), 4);
                        for ($dIdx = 0; $dIdx < $imgCount; $dIdx++): 
                        ?>
                        <span class="pdp-dot <?= $dIdx === 0 ? 'active' : '' ?>" 
                              data-dot-index="<?= $dIdx ?>"
                              onclick="selectDotPhoto(<?= $dIdx ?>)"
                              style="width: <?= $dIdx === 0 ? '22px' : '7px' ?>; height: 7px; border-radius: 4px; background: <?= $dIdx === 0 ? '#E63946' : '#38383E' ?>; display: inline-block; cursor: pointer; transition: all 0.3s ease;"></span>
                        <?php endfor; ?>
                    </div>

                    <!-- Thumbnails Strip -->
                    <?php if (count($images) > 1): ?>
                    <div class="d-flex gap-2 mt-3 overflow-x-auto pb-2 justify-content-center" style="scrollbar-width: thin;">
                        <?php foreach ($images as $idx => $img): ?>
                        <button type="button" class="btn p-0 border overflow-hidden thumb-btn <?= $idx === 0 ? 'border-danger shadow' : '' ?>" 
                                style="width: 68px; height: 68px; flex-shrink: 0; border-radius: 8px; border-color: <?= $idx === 0 ? '#E63946' : '#28282B' ?> !important; background: #141416;"
                                data-img-src="<?= e($img['image_url']) ?>"
                                data-img-index="<?= $idx ?>"
                                onclick="switchMainPhoto(this)">
                            <img src="<?= e($img['image_url']) ?>" class="w-100 h-100 object-fit-contain p-2" alt="Angle <?= $idx + 1 ?>">
                        </button>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right: Commercial Controls, Variants, Pricing & Buy Actions -->
            <div class="col-lg-6">
                <!-- Micro Series Line & SKU -->
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span style="color: #E63946; font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase;">
                        ATELIER CLASSIC SERIES
                    </span>
                    <span class="small text-secondary" style="font-size: 11px;">
                        SKU: <strong id="display-sku" class="text-white"><?= e($defaultVariant['sku']) ?></strong>
                    </span>
                </div>

                <!-- Product Name (Bold Crisp White Uppercase Header) -->
                <h1 class="h3 fw-bold mb-2 text-white text-uppercase" style="font-family: var(--font-ui, 'Inter', sans-serif); letter-spacing: 0.5px; line-height: 1.3;">
                    <?= e($product['name']) ?>
                </h1>

                <!-- Ratings Star Anchor -->
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div style="color: #DFB75C; font-size: 13.5px;">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-half"></i>
                    </div>
                    <span class="fw-bold small text-white" style="font-size: 13px;"><?= number_format($avgRating, 1) ?></span>
                    <a href="#reviews-section" class="small text-decoration-none text-secondary" style="font-size: 12.5px;">
                        (<?= $totalReviews ?> Verified Reviews)
                    </a>
                </div>

                <!-- Price & Compare Block (Matches Reference: Bold White Price, Struck-through MRP, Red Save Badge) -->
                <div class="p-3 mb-3 rounded-3" style="background: #141416; border: 1px solid #28282B;">
                    <div class="d-flex align-items-baseline gap-2 mb-1 flex-wrap">
                        <span id="display-price" class="fs-2 fw-bold text-white" style="font-family: var(--font-ui, 'Inter', sans-serif); letter-spacing: -0.5px;">
                            <?= format_currency($displayPrice) ?>
                        </span>
                        <?php if ($comparePrice > $displayPrice): ?>
                        <span class="text-decoration-line-through text-secondary" style="font-size: 15px;">
                            <?= format_currency($comparePrice) ?>
                        </span>
                        <?php 
                        $savePercent = round((($comparePrice - $displayPrice) / $comparePrice) * 100);
                        ?>
                        <span class="badge" style="background: #E63946; color: #FFFFFF; font-size: 11px; font-weight: 700; padding: 4px 8px; border-radius: 4px;">
                            SAVE <?= $savePercent ?>%
                        </span>
                        <?php endif; ?>
                    </div>
                    <div class="text-secondary small" style="font-size: 11.5px;">
                        Inclusive of all taxes &bull; Complimentary insured doorstep express
                    </div>
                </div>

                <!-- Stock State with Emerald Beacon -->
                <div class="mb-4 d-flex align-items-center gap-2">
                    <span class="pulse-beacon-emerald"></span>
                    <span id="stock-badge" class="badge p-2" style="background: #1B1B1E; color: #D1D5DB; border: 1px solid #28282B; border-radius: 4px; font-size: 12px; font-weight: 500;">
                        <i class="bi bi-check-circle me-1" style="color: #DFB75C;"></i>In Stock &bull; Calibrated for Same-Day Dispatch
                    </span>
                </div>

                <!-- Add to Bag / Buy Now / Form (Matches Reference: [-] 1 [+] + Red Add to Bag + Full-Width Gold Buy Now) -->
                <form action="/cart/add" method="POST" class="mb-4" id="main-pdp-cart-form">
                    <?= csrf_field() ?>
                    <input type="hidden" name="variant_id" id="selected-variant-id" value="<?= (int)($defaultVariant['id']) ?>">

                    <div class="d-flex gap-2 align-items-center mb-2">
                        <!-- Quantity Counter [-] 1 [+] -->
                        <div class="d-flex align-items-center rounded-3 overflow-hidden flex-shrink-0" style="background: #161619; border: 1px solid #28282B; height: 48px; width: 115px;">
                            <button type="button" class="btn text-white p-0 d-flex align-items-center justify-content-center" style="width: 36px; height: 100%; border: none; font-size: 16px;" onclick="pdpQtyDec()">-</button>
                            <input type="number" id="pdp-qty-input" name="quantity" class="form-control text-center fw-bold text-white p-0 bg-transparent border-0" value="1" min="1" max="10" style="height: 100%; font-size: 15px;" readonly>
                            <button type="button" class="btn text-white p-0 d-flex align-items-center justify-content-center" style="width: 36px; height: 100%; border: none; font-size: 16px;" onclick="pdpQtyInc()">+</button>
                        </div>
                        <!-- Vibrant Red Add to Bag Button -->
                        <button type="submit" class="btn btn-pdp-red flex-grow-1 text-white fw-bold d-flex align-items-center justify-content-center gap-2" style="height: 48px; border-radius: 8px; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">
                            <i class="bi bi-bag-plus-fill"></i> ADD TO BAG
                        </button>
                    </div>

                    <!-- Full-Width Instant Gold Buy Now Button -->
                    <button type="submit" name="buy_now" value="1" class="btn btn-pdp-gold w-100 text-dark fw-bold d-flex align-items-center justify-content-center gap-2" style="height: 48px; border-radius: 8px; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">
                        <i class="bi bi-lightning-fill"></i> BUY WITH COD / UPI NOW
                    </button>
                </form>

                <!-- Pincode / Delivery Estimator Widget (Matches Reference: Red Left Bar | Check Doorstep Delivery + Green Badge) -->
                <div class="pincode-estimator-card p-3 rounded-3 mb-4" style="background: #141416; border: 1px solid #28282B;">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="small fw-bold text-white d-flex align-items-center gap-2" style="font-size: 13px;">
                            <span style="display:inline-block; width:3.5px; height:16px; background:#E63946; border-radius: 2px;"></span>
                            Check Doorstep Delivery
                        </span>
                        <span class="badge" style="background: rgba(34, 197, 94, 0.15); color: #4ADE80; border: 1px solid rgba(34, 197, 94, 0.3); font-size: 10.5px; font-weight: 600;">
                            Free Pan-India Express
                        </span>
                    </div>
                    <div class="input-group input-group-sm mb-1">
                        <input type="text" id="delivery-pincode-input" class="form-control text-white" placeholder="Enter 6-digit Pincode (e.g. 400001)" maxlength="6" pattern="[0-9]{6}" style="background: #0B0B0C; border: 1px solid #2E2E35; border-radius: 6px 0 0 6px; font-size: 13px; color: #FFF;">
                        <button type="button" id="delivery-pincode-btn" class="btn text-white fw-bold px-3" style="background: #202025; border: 1px solid #2E2E35; border-left: 0; border-radius: 0 6px 6px 0; font-size: 12px;" onclick="checkPincodeDelivery()">CHECK</button>
                    </div>
                    <div id="pincode-result" class="small mt-2" style="font-size: 11.5px; color: #8E8E93;">
                        <span><i class="bi bi-info-circle me-1"></i>Enter pin code to verify express transit date and COD availability.</span>
                    </div>
                </div>

                <!-- 4 Luxury Value Propositions (Matches Reference: 2x2 Dark Grid with Icons) -->
                <div class="row g-2 mb-4">
                    <div class="col-6">
                        <div class="p-2 px-3 rounded-3 d-flex align-items-center gap-2 h-100" style="background: #141416; border: 1px solid #242428;">
                            <i class="bi bi-shield-check fs-5" style="color: #DFB75C;"></i>
                            <span class="text-white" style="font-size: 11.5px; line-height: 1.3;"><?= (int)$product['warranty_years'] ?>-Year Warranty</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 px-3 rounded-3 d-flex align-items-center gap-2 h-100" style="background: #141416; border: 1px solid #242428;">
                            <i class="bi bi-truck fs-5" style="color: #DFB75C;"></i>
                            <span class="text-white" style="font-size: 11.5px; line-height: 1.3;">Free Insured Transit</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 px-3 rounded-3 d-flex align-items-center gap-2 h-100" style="background: #141416; border: 1px solid #242428;">
                            <i class="bi bi-cash-coin fs-5" style="color: #DFB75C;"></i>
                            <span class="text-white" style="font-size: 11.5px; line-height: 1.3;">COD Supported</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 px-3 rounded-3 d-flex align-items-center gap-2 h-100" style="background: #141416; border: 1px solid #242428;">
                            <i class="bi bi-arrow-repeat fs-5" style="color: #DFB75C;"></i>
                            <span class="text-white" style="font-size: 11.5px; line-height: 1.3;">7-Day Replacement</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- =========================================================================
         MODULE 3: TECHNICAL HOROLOGICAL SPECIFICATIONS (DESKTOP & MOBILE ACCORDIONS)
         ========================================================================= -->
    <section class="container my-4 my-md-5">
        <div class="p-3 p-md-5 rounded-4" style="background: #141416; border: 1px solid #28282B;">
            <div class="mb-3 mb-md-4 pb-2 pb-md-3 border-bottom" style="border-color: #242428 !important;">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span style="display:inline-block; width:3.5px; height:18px; background:#E63946; border-radius: 2px;"></span>
                    <h3 class="h5 fw-bold m-0 text-white text-uppercase" style="font-family: var(--font-ui, 'Inter', sans-serif); letter-spacing: 0.5px;">
                        Technical Specifications
                    </h3>
                </div>
                <p class="small text-secondary m-0" style="font-size: 12px;">Caliber tolerances, crystal geometry, and casing architecture.</p>
            </div>

            <!-- Desktop View: Side-by-Side Tables (d-none d-md-block) -->
            <div class="d-none d-md-block">
                <div class="row g-4">
                    <div class="col-md-6">
                        <table class="table table-sm small m-0" style="background: #141416; color: #E5E7EB; border: 1px solid #28282B;">
                            <tbody>
                                <tr style="border-bottom: 1px solid #242428;"><th class="w-50 p-2" style="background: #1B1B1F; color: #A0A0A5; font-weight: 500;">Dial Architecture</th><td class="p-2 text-white"><?= e($product['dial_type']) ?></td></tr>
                                <tr style="border-bottom: 1px solid #242428;"><th class="p-2" style="background: #1B1B1F; color: #A0A0A5; font-weight: 500;">Caliber Movement</th><td class="p-2 text-white"><?= e($product['movement']) ?> (+/-15s/mo)</td></tr>
                                <tr style="border-bottom: 1px solid #242428;"><th class="p-2" style="background: #1B1B1F; color: #A0A0A5; font-weight: 500;">Case Material</th><td class="p-2 text-white">316L Surgical Stainless Steel</td></tr>
                                <tr style="border-bottom: 1px solid #242428;"><th class="p-2" style="background: #1B1B1F; color: #A0A0A5; font-weight: 500;">Case Diameter</th><td class="p-2 text-white"><?= (int)$product['case_size_mm'] ?> mm</td></tr>
                                <tr style="border-bottom: 1px solid #242428;"><th class="p-2" style="background: #1B1B1F; color: #A0A0A5; font-weight: 500;">Strap Material</th><td class="p-2 text-white"><?= e($product['strap_material']) ?></td></tr>
                                <tr><th class="p-2" style="background: #1B1B1F; color: #A0A0A5; font-weight: 500;">Water Resistance</th><td class="p-2 text-white"><?= e($product['water_resistance']) ?></td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm small m-0" style="background: #141416; color: #E5E7EB; border: 1px solid #28282B;">
                            <tbody>
                                <tr style="border-bottom: 1px solid #242428;"><th class="w-50 p-2" style="background: #1B1B1F; color: #A0A0A5; font-weight: 500;">Country of Origin</th><td class="p-2 text-white"><?= e($product['country_of_origin']) ?></td></tr>
                                <tr style="border-bottom: 1px solid #242428;"><th class="p-2" style="background: #1B1B1F; color: #A0A0A5; font-weight: 500;">Harmonized HSN</th><td class="p-2 text-white"><?= e($product['hsn_code']) ?></td></tr>
                                <tr style="border-bottom: 1px solid #242428;"><th class="p-2" style="background: #1B1B1F; color: #A0A0A5; font-weight: 500;">Battery Standard</th><td class="p-2 text-white">SR626SW (2–3 Year Life)</td></tr>
                                <tr style="border-bottom: 1px solid #242428;"><th class="p-2" style="background: #1B1B1F; color: #A0A0A5; font-weight: 500;">Box Inclusions</th><td class="p-2 text-white">Watch, Warranty Card, Microfiber Cloth</td></tr>
                                <?php foreach ($specifications as $sp): ?>
                                <tr style="border-bottom: 1px solid #242428;"><th class="p-2" style="background: #1B1B1F; color: #A0A0A5; font-weight: 500;"><?= e($sp['spec_name']) ?></th><td class="p-2 text-white"><?= e($sp['spec_value']) ?></td></tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Mobile View: Clean Touch Accordions (d-md-none) -->
            <div class="d-md-none mobile-specs-accordion" id="mobileSpecsAccordion">
                <!-- Group 1: Movement & Dial Architecture -->
                <div class="rounded-3 mb-2 overflow-hidden" style="border: 1px solid #28282B; background: #141416;">
                    <button class="btn w-100 text-start p-3 d-flex align-items-center justify-content-between fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#specCol1" aria-expanded="true" style="font-size: 13.5px; background: #1B1B1F; color: #FFFFFF; border: none;">
                        <span><i class="bi bi-gear-wide-connected me-2" style="color: #DFB75C;"></i>Movement &amp; Precision</span>
                        <i class="bi bi-chevron-down text-secondary"></i>
                    </button>
                    <div id="specCol1" class="collapse show p-3" style="font-size: 12.5px; background: #141416; border-top: 1px solid #242428;">
                        <div class="d-flex justify-content-between py-1 border-bottom" style="border-color: #242428 !important;"><span class="text-secondary">Dial Architecture</span><span class="fw-semibold text-white"><?= e($product['dial_type']) ?></span></div>
                        <div class="d-flex justify-content-between py-1 border-bottom" style="border-color: #242428 !important;"><span class="text-secondary">Caliber Movement</span><span class="fw-semibold text-white"><?= e($product['movement']) ?> (+/-15s/mo)</span></div>
                        <div class="d-flex justify-content-between py-1"><span class="text-secondary">Battery Standard</span><span class="fw-semibold text-white">SR626SW (2–3 Year Life)</span></div>
                    </div>
                </div>

                <!-- Group 2: Case, Glass & Dimensions -->
                <div class="rounded-3 mb-2 overflow-hidden" style="border: 1px solid #28282B; background: #141416;">
                    <button class="btn w-100 text-start p-3 d-flex align-items-center justify-content-between fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#specCol2" aria-expanded="false" style="font-size: 13.5px; background: #1B1B1F; color: #FFFFFF; border: none;">
                        <span><i class="bi bi-bounding-box me-2" style="color: #DFB75C;"></i>Case, Dimensions &amp; Crystal</span>
                        <i class="bi bi-chevron-down text-secondary"></i>
                    </button>
                    <div id="specCol2" class="collapse p-3" style="font-size: 12.5px; background: #141416; border-top: 1px solid #242428;">
                        <div class="d-flex justify-content-between py-1 border-bottom" style="border-color: #242428 !important;"><span class="text-secondary">Case Diameter</span><span class="fw-semibold text-white"><?= (int)$product['case_size_mm'] ?> mm</span></div>
                        <div class="d-flex justify-content-between py-1 border-bottom" style="border-color: #242428 !important;"><span class="text-secondary">Case Material</span><span class="fw-semibold text-white">316L Surgical Stainless Steel</span></div>
                        <div class="d-flex justify-content-between py-1"><span class="text-secondary">Crystal Glass</span><span class="fw-semibold text-white">Double-Curved Hardened Crystal</span></div>
                    </div>
                </div>

                <!-- Group 3: Strap & Hydrostatic Rating -->
                <div class="rounded-3 mb-2 overflow-hidden" style="border: 1px solid #28282B; background: #141416;">
                    <button class="btn w-100 text-start p-3 d-flex align-items-center justify-content-between fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#specCol3" aria-expanded="false" style="font-size: 13.5px; background: #1B1B1F; color: #FFFFFF; border: none;">
                        <span><i class="bi bi-droplet-half me-2" style="color: #DFB75C;"></i>Strap &amp; Hydrostatic Rating</span>
                        <i class="bi bi-chevron-down text-secondary"></i>
                    </button>
                    <div id="specCol3" class="collapse p-3" style="font-size: 12.5px; background: #141416; border-top: 1px solid #242428;">
                        <div class="d-flex justify-content-between py-1 border-bottom" style="border-color: #242428 !important;"><span class="text-secondary">Strap Material</span><span class="fw-semibold text-white"><?= e($product['strap_material']) ?></span></div>
                        <div class="d-flex justify-content-between py-1"><span class="text-secondary">Water Resistance</span><span class="fw-semibold text-white"><?= e($product['water_resistance']) ?></span></div>
                    </div>
                </div>

                <!-- Group 4: Origin, Warranty & Box Inclusions -->
                <div class="rounded-3 overflow-hidden" style="border: 1px solid #28282B; background: #141416;">
                    <button class="btn w-100 text-start p-3 d-flex align-items-center justify-content-between fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#specCol4" aria-expanded="false" style="font-size: 13.5px; background: #1B1B1F; color: #FFFFFF; border: none;">
                        <span><i class="bi bi-shield-check me-2" style="color: #DFB75C;"></i>Authenticity &amp; Inclusions</span>
                        <i class="bi bi-chevron-down text-secondary"></i>
                    </button>
                    <div id="specCol4" class="collapse p-3" style="font-size: 12.5px; background: #141416; border-top: 1px solid #242428;">
                        <div class="d-flex justify-content-between py-1 border-bottom" style="border-color: #242428 !important;"><span class="text-secondary">Country of Origin</span><span class="fw-semibold text-white"><?= e($product['country_of_origin']) ?></span></div>
                        <div class="d-flex justify-content-between py-1 border-bottom" style="border-color: #242428 !important;"><span class="text-secondary">Harmonized HSN</span><span class="fw-semibold text-white"><?= e($product['hsn_code']) ?></span></div>
                        <div class="d-flex justify-content-between py-1 border-bottom" style="border-color: #242428 !important;"><span class="text-secondary">Box Inclusions</span><span class="fw-semibold text-white">Watch, Serial Warranty Card, Polishing Cloth</span></div>
                        <?php foreach ($specifications as $sp): ?>
                        <div class="d-flex justify-content-between py-1 border-bottom" style="border-color: #242428 !important;"><span class="text-secondary"><?= e($sp['spec_name']) ?></span><span class="fw-semibold text-white"><?= e($sp['spec_value']) ?></span></div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- =========================================================================
         MODULE 4: EDITORIAL WIDE BANNER (A+ Full Bleed Style)
         ========================================================================= -->
    <?php
    $bannerModule = null;
    $storiesModule = null;
    $lifestyleModule = null;
    foreach ($modules as $m) {
        if ($m['module_type'] === 'banner') {
            $bannerModule = json_decode($m['content_json'] ?? '{}', true);
        } elseif ($m['module_type'] === 'stories') {
            $storiesModule = json_decode($m['content_json'] ?? '{}', true);
        } elseif ($m['module_type'] === 'lifestyle') {
            $lifestyleModule = json_decode($m['content_json'] ?? '{}', true);
        }
    }
    ?>
    <section class="container my-5">
        <div class="position-relative rounded-4 overflow-hidden shadow-sm" style="min-height: 420px; background: #0A1128;">
            <img src="<?= e($bannerModule['image_url'] ?? 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=1600&q=80') ?>"
                 alt="Welkar Atelier Editorial Banner"
                 class="w-100 h-100 position-absolute inset-0 object-fit-cover opacity-40">
            <div class="position-relative z-2 p-5 d-flex flex-column justify-content-center text-white" style="min-height: 420px; max-width: 680px;">
                <h2 class="display-5 fw-normal mb-3" style="font-family: var(--font-display, 'DM Serif Display', Georgia, serif);">
                    <?= e($bannerModule['headline'] ?? 'CALIBRATED FOR HOROLOGICAL DISTINCTION') ?>
                </h2>
                <p class="lead opacity-90 mb-4" style="font-size: 15px; line-height: 1.8;">
                    <?= e($bannerModule['body'] ?? 'Engineered with solid 316L surgical stainless steel, dual-layer anti-reflective crystal, and precision Japanese movements for executive confidence on every wrist.') ?>
                </p>

            </div>
        </div>
    </section>

    <!-- =========================================================================
         MODULE 5: INTERACTIVE HOROLOGICAL ANATOMY HOTSPOTS
         ========================================================================= -->
    <?php if (!empty($hotspots)): ?>
    <section class="container my-5 py-lg-4">
        <div class="row g-4 align-items-center">
            <!-- Left: Hotspot Stage Canvas -->
            <div class="col-lg-7">
                <div class="hotspot-stage position-relative card-luxury p-3 text-center">
                    <img src="<?= e($primaryImage) ?>" alt="Watch Anatomy" class="img-fluid" id="hotspot-base-img" style="max-height: 480px;">
                    <?php foreach ($hotspots as $idx => $hp): ?>
                    <button type="button" 
                            class="hotspot-pin <?= $idx === 0 ? 'active' : '' ?>"
                            style="top: <?= (float)$hp['y_pos_percent'] ?>%; left: <?= (float)$hp['x_pos_percent'] ?>%;"
                            data-hotspot-idx="<?= $idx ?>"
                            data-title="<?= e($hp['title']) ?>"
                            data-desc="<?= e($hp['description']) ?>"
                            onclick="selectHotspot(this)"
                            title="<?= e($hp['title']) ?>">
                        <?= $idx + 1 ?>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Right: Active Hotspot Detail Panel -->
            <div class="col-lg-5">
                <div class="hotspot-card-detail" id="hotspot-detail-box">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge rounded-circle p-2" style="background: var(--color-accent-600, #C59B3F); color: #fff; width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;" id="hotspot-active-num">1</span>
                        <h4 class="h5 fw-bold m-0" style="color: var(--color-brand-950, #0A1128);" id="hotspot-active-title">
                            <?= e($hotspots[0]['title']) ?>
                        </h4>
                    </div>
                    <p class="small text-muted mb-4" style="line-height: 1.8;" id="hotspot-active-desc">
                        <?= e($hotspots[0]['description']) ?>
                    </p>

                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- =========================================================================
         MODULE 6: ALTERNATING CRAFTSMANSHIP FEATURE STORIES
         ========================================================================= -->
    <?php
    $storiesList = !empty($storiesModule['stories']) ? $storiesModule['stories'] : [
        [
            'title' => '316L Surgical Alloy Engineering',
            'text' => 'We forge our watch cases and bracelet links from medical-grade 316L alloy rather than standard zinc or plated metals. This provides exceptional resistance against perspiration corrosion and retains brilliant mirror-chamfered edges for years of daily wear.',
            'image_url' => 'https://images.unsplash.com/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=800&q=80',
            'bullets' => [
                'Hypoallergenic and nickel-safe for sensitive skin',
                'Micro-milled link tolerancing for smooth wrist articulation',
                'Dual-finishing combining satin-brushed flanks and polished bezels'
            ]
        ],
        [
            'title' => 'Double-Gasket Silicone Pressure Seals',
            'text' => 'Before any Welkar timepiece leaves our assembly chamber, it undergoes both negative and positive pressure immersion cycles. The fluted crown and threaded caseback utilize compression silicone O-rings engineered to repel moisture under heavy monsoon rains and accidental submersions.',
            'image_url' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=800&q=80',
            'bullets' => [
                'Individually tested to 30M (3 ATM) hydrostatic standards',
                'Threaded backplate with laser-etched serial numbering',
                'Impervious to everyday handwashing, rain, and humidity'
            ]
        ]
    ];
    ?>
    <?php if (!empty($storiesList)): ?>
    <section class="container my-5 py-lg-4">
        <?php foreach ($storiesList as $sIdx => $st): ?>
        <div class="row g-5 align-items-center mb-5 story-block <?= ($sIdx % 2 !== 0) ? 'flex-lg-row-reverse' : '' ?>">
            <div class="col-lg-6">
                <div class="story-block-img">
                    <img src="<?= e($st['image_url'] ?? 'https://images.unsplash.com/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=800&q=80') ?>" alt="<?= e($st['title'] ?? 'Craftsmanship Story') ?>">
                </div>
            </div>
            <div class="col-lg-6">

                <h3 class="h3 fw-normal mt-1 mb-3" style="font-family: var(--font-display, 'DM Serif Display', Georgia, serif); color: var(--color-brand-950, #0A1128);">
                    <?= e($st['title'] ?? '') ?>
                </h3>
                <p class="text-muted" style="line-height: 1.8; font-size: 14.5px;">
                    <?= e($st['text'] ?? '') ?>
                </p>
                <?php if (!empty($st['bullets']) && is_array($st['bullets'])): ?>
                <ul class="list-unstyled small text-secondary mt-3 d-flex flex-column gap-2">
                    <?php foreach ($st['bullets'] as $b): ?>
                    <li><i class="bi bi-check2-circle me-2" style="color: var(--color-accent-600, #C59B3F);"></i><?= e($b) ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </section>
    <?php endif; ?>

    <!-- =========================================================================
         MODULE 7: "ON THE WRIST" LIFESTYLE CAROUSEL
         ========================================================================= -->
    <?php
    $lifestyleImages = !empty($lifestyleModule['images']) ? $lifestyleModule['images'] : [
        [
            'url' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80',
            'caption' => 'Executive Cuff Profile',
            'subcaption' => '7.0" Wrist &bull; Suit Chamfer'
        ],
        [
            'url' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=600&q=80',
            'caption' => 'Milanese Mesh Grace',
            'subcaption' => '5.8" Wrist &bull; Evening Light'
        ],
        [
            'url' => 'https://images.unsplash.com/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=600&q=80',
            'caption' => 'Weekend Chrono Casual',
            'subcaption' => '6.5" Wrist &bull; Solid Links'
        ],
        [
            'url' => 'https://images.unsplash.com/photo-1539874701005-786335a09b30?auto=format&fit=crop&w=600&q=80',
            'caption' => 'Monolith Bauhaus Modern',
            'subcaption' => '6.8" Wrist &bull; Ultra-Slim Profile'
        ]
    ];
    ?>
    <?php if (!empty($lifestyleImages)): ?>
    <section class="py-5 my-5 border-top border-bottom" style="background-color: var(--color-surface-50, #F5F7FB); border-color: var(--color-border-default, #E1E7F0) !important;">
        <div class="container">
            <div class="mb-4">
                <h2 class="display-6 fw-normal mb-0" style="font-family: var(--font-display, 'DM Serif Display', Georgia, serif); color: var(--color-brand-950, #0A1128);">On The Wrist</h2>
            </div>

            <div class="row g-3 row-cols-2 row-cols-md-4">
                <?php foreach ($lifestyleImages as $ls): ?>
                <div class="col">
                    <div class="position-relative rounded-3 overflow-hidden card-luxury" style="height: 280px;">
                        <img src="<?= e($ls['url'] ?? '') ?>" class="w-100 h-100 object-fit-cover" alt="<?= e($ls['caption'] ?? 'Wristshot') ?>">
                        <div class="position-absolute bottom-0 start-0 end-0 p-3 bg-dark bg-opacity-75 text-white" style="font-size: 11px;">
                            <strong class="d-block"><?= e($ls['caption'] ?? '') ?></strong>
                            <?php if (!empty($ls['subcaption'])): ?>
                            <span class="text-white-50"><?= e($ls['subcaption']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>


    <!-- =========================================================================
         MODULE 10: TIMEPIECE FAMILY COMPARISON MATRIX
         ========================================================================= -->
    <?php if (!empty($comparison['products']) && !empty($comparison['rows'])): ?>
    <section class="container my-5 py-lg-4">
        <div class="mb-4">
            <div class="d-flex align-items-center gap-2 mb-1">
                <span style="display:inline-block; width:3.5px; height:18px; background:#E63946; border-radius: 2px;"></span>
                <h2 class="h5 fw-bold m-0 text-white text-uppercase" style="font-family: var(--font-ui, 'Inter', sans-serif); letter-spacing: 0.5px;">
                    Compare With Related Timepieces
                </h2>
            </div>
            <p class="small text-secondary m-0" style="font-size: 12px;">Caliber movements, crystal depth, and finishing comparison across the collection.</p>
        </div>

        <div class="table-responsive rounded-4 p-2 p-md-3" style="background: #141416; border: 1px solid #28282B;">
            <table class="table comparison-table w-100 m-0" style="background: #141416; color: #E5E7EB; border-color: #28282B;">
                <thead>
                    <tr>
                        <th style="background: #1B1B1F; color: #A0A0A5; font-size: 12px; border-color: #28282B;">Specification</th>
                        <?php foreach ($comparison['products'] as $cp): ?>
                        <td class="<?= $cp['id'] == $product['id'] ? 'current-product' : '' ?>" style="width: <?= round(75 / count($comparison['products'])) ?>%; background: <?= $cp['id'] == $product['id'] ? 'rgba(230, 57, 70, 0.08)' : '#141416' ?>; border-color: #28282B; text-align: center;">
                            <img src="<?= e($cp['primary_image']) ?>" alt="<?= e($cp['name']) ?>" class="img-fluid mb-2" style="height: 90px; object-fit: contain;">
                            <div class="fw-bold small text-truncate mb-1 text-white" style="max-width: 170px; margin: 0 auto; font-size: 12px;"><?= e($cp['name']) ?></div>
                            <div class="text-secondary small mb-2" style="font-size: 11px;"><?= format_currency($cp['base_price']) ?></div>
                            <?php if ($cp['id'] == $product['id']): ?>
                                <span class="badge py-1 px-2" style="background: #E63946; color: #fff; font-size: 10px; font-weight: 600;">Current Watch</span>
                            <?php else: ?>
                                <a href="/product/<?= e($cp['slug']) ?>" class="btn btn-sm btn-outline-light" style="font-size: 11px; border-radius: 6px; padding: 2px 12px;">View</a>
                            <?php endif; ?>
                        </td>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($comparison['rows'] as $cr): ?>
                    <tr style="border-top: 1px solid #242428;">
                        <th style="background: #1B1B1F; color: #A0A0A5; font-size: 12px; font-weight: 500; border-color: #28282B;"><?= e($cr['feature_name']) ?></th>
                        <?php foreach ($comparison['products'] as $cp): ?>
                        <td class="<?= $cp['id'] == $product['id'] ? 'current-product' : '' ?>" style="background: <?= $cp['id'] == $product['id'] ? 'rgba(230, 57, 70, 0.08)' : '#141416' ?>; color: #F5F5F5; font-size: 12px; border-color: #28282B; text-align: center;">
                            <?= e($cr['values'][$cp['id']] ?? '—') ?>
                        </td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
    <?php endif; ?>

    <!-- =========================================================================
         MODULE 11: CURATED WATCH PRE-PURCHASE FAQS
         ========================================================================= -->
    <?php
    $faq_title = "Frequently Asked Questions";
    $faq_badge = null;
    $faq_subtitle = "Answers regarding battery lifespan, strap adjustments, water submersion certifications, and authentic warranty support.";
    include __DIR__ . '/../partials/faq_section.php';
    ?>

    <!-- =========================================================================
         MODULE 12 & 13: REVIEWS & CUSTOMER VERIFICATION ENGINE
         ========================================================================= -->
    <section class="container my-5 pt-4" id="reviews-section">
        <div class="p-4 p-lg-5 rounded-4" style="background: #141416; border: 1px solid #28282B;">
            <div class="row g-5 align-items-start mb-5 pb-4 border-bottom" style="border-color: #242428 !important;">
                <!-- Review Score Big Badge -->
                <div class="col-lg-4 text-center text-lg-start border-end-lg" style="border-color: #242428 !important;">
                    <div class="d-flex align-items-center gap-2 mb-1 justify-content-center justify-content-lg-start">
                        <span style="display:inline-block; width:3.5px; height:18px; background:#E63946; border-radius: 2px;"></span>
                        <h4 class="small fw-bold m-0 text-white text-uppercase" style="letter-spacing: 0.5px;">Customer Rating</h4>
                    </div>
                    <div class="display-3 fw-bold mt-1 text-white" style="font-family: var(--font-ui, 'Inter', sans-serif);">
                        <?= number_format($avgRating, 1) ?>
                    </div>
                    <div class="mb-2" style="color: #DFB75C; font-size: 18px;">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-half"></i>
                    </div>
                    <p class="small text-secondary mb-4">Based on <?= $totalReviews ?> verified buyer reviews</p>
                    <button type="button" class="btn btn-pdp-red btn-sm px-4" data-bs-toggle="modal" data-bs-target="#writeReviewModal" style="height: 42px; border-radius: 8px; font-size: 13px; font-weight: 600;">
                        <i class="bi bi-pencil-square me-2"></i>Write an Atelier Review
                    </button>
                </div>

                <!-- Star Rating Distribution Bars (Vibrant Red Accent Bars) -->
                <div class="col-lg-8">
                    <h5 class="fw-semibold mb-3 small text-uppercase text-secondary" style="letter-spacing: 0.5px;">Rating Breakdown</h5>
                    <div class="d-flex flex-column gap-2" style="max-width: 520px;">
                        <?php for ($star = 5; $star >= 1; $star--): 
                            $count = $review_stats['stars'][$star]['count'] ?? 0;
                            $percent = $review_stats['stars'][$star]['percent'] ?? 0;
                        ?>
                        <div class="d-flex align-items-center gap-3">
                            <span class="small fw-semibold text-nowrap text-white" style="width: 48px; font-size: 12px;"><?= $star ?> Stars</span>
                            <div class="flex-grow-1 review-bar-container" style="background: #242428; height: 8px; border-radius: 4px; overflow: hidden;">
                                <div class="review-bar-fill" style="width: <?= $percent ?>%; background: #E63946; height: 100%; border-radius: 4px;"></div>
                            </div>
                            <span class="small text-secondary text-end" style="width: 42px; font-size: 12px;"><?= $percent ?>%</span>
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>

            <!-- Customer Uploaded Photos Gallery Strip -->
            <?php if (!empty($review_stats['photos'])): ?>
            <div class="mb-5 pb-4 border-bottom" style="border-color: #242428 !important;">
                <h5 class="fw-semibold mb-3 small text-uppercase text-secondary" style="letter-spacing: 0.5px;">Customer Wristshots (<?= count($review_stats['photos']) ?>)</h5>
                <div class="d-flex gap-3 overflow-x-auto pb-2">
                    <?php foreach ($review_stats['photos'] as $rp): ?>
                    <div class="rounded-3 overflow-hidden border" style="width: 100px; height: 100px; flex-shrink: 0; border-color: #28282B !important; background: #1B1B1E;">
                        <img src="<?= e($rp['url']) ?>" alt="Wristshot by <?= e($rp['author']) ?>" class="w-100 h-100 object-fit-cover" loading="lazy">
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Individual Customer Review Cards -->
            <div class="review-cards-list">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-semibold m-0 small text-uppercase text-secondary" style="letter-spacing: 0.5px;">Verified Owner Experiences</h5>
                    <span class="small text-secondary"><?= count($reviews) ?> reviews displayed</span>
                </div>

                <div class="row g-4">
                    <?php foreach ($reviews as $rev): ?>
                    <div class="col-md-6">
                        <div class="p-4 rounded-3 h-100 d-flex flex-column justify-content-between" style="background: #1B1B1E; border: 1px solid #28282B;">
                            <div>
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <?php 
                                        $initial = strtoupper(mb_substr(trim($rev['author_name'] ?: 'V'), 0, 1));
                                        if (!empty($rev['avatar_url'])): 
                                        ?>
                                            <img src="<?= e($rev['avatar_url']) ?>" alt="<?= e($rev['author_name']) ?>" class="rounded-circle border" style="width: 38px; height: 38px; object-fit: cover; flex-shrink: 0; border-color: rgba(223,183,92,0.4) !important;">
                                        <?php else: ?>
                                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 38px; height: 38px; background: #141416; border: 1px solid rgba(223,183,92,0.4); color: #DFB75C; font-size: 14px; flex-shrink: 0;">
                                                <?= e($initial) ?>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <strong class="d-block small text-white" style="line-height: 1.2;"><?= e($rev['author_name']) ?></strong>
                                            <small class="text-secondary" style="font-size: 11px;"><?= date('M d, Y', strtotime($rev['created_at'])) ?></small>
                                        </div>
                                    </div>
                                    <?php if (!empty($rev['is_verified_purchase'])): ?>
                                    <span class="badge" style="background: rgba(34, 197, 94, 0.15); color: #4ADE80; font-size: 10px; border-radius: 4px; border: 1px solid rgba(34, 197, 94, 0.3);">
                                        <i class="bi bi-patch-check-fill me-1"></i>Verified Purchase
                                    </span>
                                    <?php endif; ?>
                                </div>

                                <div class="mb-2" style="color: #DFB75C; font-size: 12px;">
                                    <?php for($i = 0; $i < (int)$rev['rating']; $i++): ?><i class="bi bi-star-fill"></i><?php endfor; ?>
                                </div>

                                <?php if (!empty($rev['title'])): ?>
                                <h6 class="fw-bold mb-1 text-white" style="font-size: 14px;"><?= e($rev['title']) ?></h6>
                                <?php endif; ?>

                                <p class="small text-secondary mb-3" style="line-height: 1.7; font-size: 13px;">
                                    <?= e($rev['comment']) ?>
                                </p>

                                <?php if (!empty($rev['images_json'])): 
                                    $revImgs = json_decode($rev['images_json'], true);
                                    if (is_array($revImgs) && !empty($revImgs)):
                                 ?>
                                <div class="mb-3">
                                    <span class="d-block small text-secondary mb-1" style="font-size: 11px;"><i class="bi bi-camera me-1"></i>Customer Received Photo:</span>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <?php foreach ($revImgs as $rImg): ?>
                                        <a href="<?= e($rImg) ?>" target="_blank" class="d-inline-block">
                                            <img src="<?= e($rImg) ?>" class="rounded border shadow-sm" style="width: 55px; height: 55px; object-fit: cover; border-color: #28282B !important;" alt="Received watch photo">
                                        </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <?php endif; endif; ?>
                            </div>

                            <!-- Helpful Button & Admin Reply -->
                            <div class="border-top pt-2 mt-auto" style="border-color: #242428 !important;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <button type="button" class="btn btn-sm btn-link text-decoration-none p-0 text-secondary" style="font-size: 11.5px;" onclick="voteReviewHelpful(<?= (int)$rev['id'] ?>, this)">
                                        <i class="bi bi-hand-thumbs-up me-1"></i>Helpful (<span class="helpful-counter"><?= (int)($rev['helpful_count'] ?? 0) ?></span>)
                                    </button>
                                </div>

                                <?php if (!empty($rev['admin_response'])): ?>
                                <div class="mt-2 p-2 rounded small" style="background: #141416; font-size: 11.5px; border-left: 2px solid #E63946;">
                                    <strong class="d-block" style="color: #DFB75C;">Welkar Atelier Response:</strong>
                                    <span class="text-secondary"><?= e($rev['admin_response']) ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- =========================================================================
         MODULE 14: RELATED TIMEPIECES ("YOU MAY ALSO APPRECIATE" - 2-COLUMN MOBILE)
         ========================================================================= -->
    <?php if (!empty($related)): ?>
    <section class="container my-5 pt-4 border-top" style="border-color: #242428 !important;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-2">
                <span style="display:inline-block; width:3.5px; height:18px; background:#E63946; border-radius: 2px;"></span>
                <h3 class="h5 fw-bold m-0 text-white text-uppercase" style="font-family: var(--font-ui, 'Inter', sans-serif); letter-spacing: 0.5px;">
                    You May Also Appreciate
                </h3>
            </div>
            <a href="/catalog" class="small fw-semibold text-decoration-none" style="color: #DFB75C; font-size: 12.5px;">View Full Catalog <i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="row g-2 g-sm-3 row-cols-2 row-cols-sm-2 row-cols-md-4">
            <?php foreach (array_slice($related, 0, 4) as $rel): ?>
            <div class="col">
                <div class="card h-100 border product-card position-relative overflow-hidden" style="background: #141416; border-color: #28282B !important; border-radius: 12px;">
                    <div class="position-relative text-center" style="width: 100%; aspect-ratio: 1/1; background: #0E0E10; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                        <a href="/product/<?= e($rel['slug']) ?>" class="d-block w-100 h-100 d-flex align-items-center justify-content-center p-2 p-sm-3">
                            <img src="<?= e($rel['primary_image'] ?: '/assets/images/products/placeholder.jpg') ?>" class="img-fluid" alt="<?= e($rel['name']) ?>" style="max-width: 90%; max-height: 90%; object-fit: contain; transition: transform 0.3s ease;">
                        </a>

                        <!-- Instant 1-Tap Quick Add Button on Thumbnail -->
                        <button type="button" 
                                class="btn btn-pdp-red position-absolute bottom-0 end-0 m-2 rounded-circle d-flex align-items-center justify-content-center shadow-sm btn-quick-add" 
                                style="width: 32px; height: 32px; z-index: 10; padding: 0;"
                                title="Quick Add"
                                onclick="quickAddToCart(<?= (int)($rel['default_variant_id'] ?? 1) ?>, '<?= e(addslashes($rel['name'])) ?>', <?= (float)$rel['base_price'] ?>, '<?= e($rel['primary_image']) ?>', this)">
                            <i class="bi bi-bag-plus-fill" style="font-size: 13px;"></i>
                        </button>

                        <!-- Red HOT SELLER / SAVE Badge -->
                        <?php if ($rel['compare_at_price'] > $rel['base_price']): ?>
                        <span class="badge position-absolute top-0 start-0 m-2" style="background: #E63946; color: #FFFFFF; font-size: 10px; font-weight: 700; border-radius: 4px; padding: 4px 8px;">
                            SAVE ₹<?= number_format($rel['compare_at_price'] - $rel['base_price']) ?>
                        </span>
                        <?php else: ?>
                        <span class="badge position-absolute top-0 start-0 m-2" style="background: #E63946; color: #FFFFFF; font-size: 10px; font-weight: 700; border-radius: 4px; padding: 4px 8px;">
                            HOT SELLER
                        </span>
                        <?php endif; ?>
                    </div>
                    <div class="card-body p-2 p-sm-3 d-flex flex-column justify-content-between">
                        <div>
                            <div class="text-uppercase fw-semibold mb-1 text-truncate" style="color: #8E8E93; font-size: 10px; letter-spacing: 0.5px;">
                                <?= e($rel['dial_type']) ?> Caliber
                            </div>
                            <h6 class="card-title fw-semibold mb-1" style="font-family: var(--font-ui, 'Inter', sans-serif); font-size: clamp(12px, 2.5vw, 13.5px); display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; min-height: 2.6em; line-height: 1.3;">
                                <a href="/product/<?= e($rel['slug']) ?>" class="text-decoration-none text-white" title="<?= e($rel['name']) ?>">
                                    <?= e($rel['name']) ?>
                                </a>
                            </h6>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top" style="border-color: #242428 !important;">
                            <span class="fw-bold text-white" style="font-family: var(--font-ui, 'Inter', sans-serif); font-size: 13.5px;"><?= format_currency($rel['base_price']) ?></span>
                            <a href="/product/<?= e($rel['slug']) ?>" class="btn btn-pdp-red btn-sm" style="font-size: 11px; height: 30px; padding: 0 12px; border-radius: 6px; display: flex; align-items: center; font-weight: 600;">
                                View
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

<!-- =========================================================================
     WRITE A REVIEW MODAL
     ========================================================================= -->
<div class="modal fade" id="writeReviewModal" tabindex="-1" aria-labelledby="writeReviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: var(--radius-md, 10px);">
            <div class="modal-header border-bottom p-4" style="border-color: var(--color-border-default, #E1E7F0) !important;">
                <div>
                    <h5 class="modal-title fw-bold mb-1" id="writeReviewModalLabel" style="color: var(--color-brand-950, #0A1128); font-family: var(--font-display, 'DM Serif Display', Georgia, serif);">
                        Write an Atelier Review
                    </h5>
                    <p class="text-muted small m-0" style="font-size: 12px;">Verified collector reviews & wrist impressions</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/product/<?= e($product['slug']) ?>/review" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="alert alert-info py-2 px-3 small mb-3 d-flex align-items-center gap-2" style="font-size: 12px;">
                        <i class="bi bi-shield-check fs-6 text-primary"></i>
                        <span>Reviews are reserved for verified delivered purchases and are reviewed by our atelier team before publishing.</span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Your Overall Rating *</label>
                        <select name="rating" class="form-select" required>
                            <option value="5" selected>★★★★★ — Exceptional Quality (5 Stars)</option>
                            <option value="4">★★★★☆ — Very Good (4 Stars)</option>
                            <option value="3">★★★☆☆ — Average (3 Stars)</option>
                            <option value="2">★★☆☆☆ — Below Expectations (2 Stars)</option>
                            <option value="1">★☆☆☆☆ — Poor (1 Star)</option>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-sm-6">
                            <label class="form-label small fw-semibold">Your Full Name *</label>
                            <input type="text" name="author_name" class="form-control" required placeholder="e.g. Vikram Singhania">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label small fw-semibold">Welkar Order Number *</label>
                            <input type="text" name="order_number" class="form-control" required value="<?= e($_GET['order_number'] ?? '') ?>" placeholder="e.g. WEL-2026-1035">
                        </div>
                    </div>
                    <small class="text-muted d-block mt-n2 mb-3" style="font-size: 11px;">Must match a delivered order for this timepiece. Sent in your delivery confirmation email.</small>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Review Title</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Impeccable dial finish and solid steel links">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Your Review & Wrist Impressions *</label>
                        <textarea name="comment" class="form-control" rows="3" required placeholder="Share your experience with the case weight, clasp mechanism, and dial finish..."></textarea>
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-sm-6">
                            <label class="form-label small fw-semibold"><i class="bi bi-person-circle me-1"></i>Reviewer Photo</label>
                            <input type="file" name="avatar_file" class="form-control form-control-sm" accept="image/*">
                            <small class="text-muted" style="font-size: 10.5px;">Optional. If omitted, your name's First Letter Logo will be displayed.</small>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label small fw-semibold"><i class="bi bi-camera me-1"></i>Received Product Photo</label>
                            <input type="file" name="review_image" class="form-control form-control-sm" accept="image/*">
                            <small class="text-muted" style="font-size: 10.5px;">Upload a wristshot or unboxing photo of your received watch.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top p-3" style="border-color: var(--color-border-default, #E1E7F0) !important;">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="btn-submit-review" class="btn btn-welkar btn-sm px-4">Submit Verified Review</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const reviewForm = document.querySelector('form[action*="/review"]');
    if (reviewForm) {
        reviewForm.addEventListener('submit', function() {
            const btn = document.getElementById('btn-submit-review');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Submitting Review...';
            }
        });
    }
});
</script>

<!-- =========================================================================
     COMPACT BRAND STORY PANEL (SHARED COMPANY PROFILE)
     ========================================================================= -->
<?php
    $pdpCompany = \App\Services\Company\CompanyProfileService::getProfile();
?>
<section class="container my-5">
    <div class="p-4 p-md-5 rounded-4" style="background: #141416; border: 1px solid #28282B;">
        <div class="row align-items-center g-4 justify-content-between">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge fw-semibold" style="background: rgba(223, 183, 92, 0.15); color: #DFB75C; border: 1px solid rgba(223, 183, 92, 0.3); font-size: 11px; letter-spacing: 1px;">
                        EST. <?= e($pdpCompany['founding_year'] ?? '2002') ?>
                    </span>
                    <span class="text-secondary small fw-semibold">The WELKAR Story</span>
                </div>
                <h3 class="h4 fw-bold mb-2 text-uppercase" style="color: #E63946; font-family: var(--font-ui, 'Inter', sans-serif); letter-spacing: 0.5px;">
                    <?= e($pdpCompany['tagline'] ?? 'Precision, Heritage, and Trust — Since 2002.') ?>
                </h3>
                <p class="text-secondary small mb-0" style="line-height: 1.8; font-family: var(--font-ui, 'Inter', sans-serif); max-width: 660px;">
                    <?= e($pdpCompany['pdp_summary'] ?? 'Founded in 2002 by Mr. Sumit Singh and Mr. Nevil Sharma, WELKAR creates timepieces balancing thoughtful design, practical utility, and transparent quality. Every model in our catalog features distinct, clearly documented specifications.') ?>
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="/about" class="btn btn-outline-light" style="border-radius: 8px; font-size: 13px; padding: 10px 22px; font-weight: 600;">
                    <span>Read Our Full Story</span>
                    <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Sticky Floating Buy Bar (Appears on scroll on mobile & desktop) -->
<div id="sticky-bar" class="sticky-purchase-bar">
    <div class="container">
        <form action="/cart/add" method="POST" class="m-0">
            <?= csrf_field() ?>
            <input type="hidden" name="variant_id" id="sticky-variant-id" value="<?= (int)($defaultVariant['id']) ?>">
            <input type="hidden" name="quantity" value="1">

            <div class="d-flex align-items-center justify-content-between gap-2 gap-md-4">
                <!-- Left: Thumbnail & Title Info -->
                <div class="d-flex align-items-center gap-2 gap-sm-3 text-truncate">
                    <img src="<?= e($primaryImage) ?>" alt="" width="44" height="44" class="rounded border object-fit-contain p-1 flex-shrink-0" style="background: #141416; border-color: #28282B !important;">
                    <div class="text-truncate">
                        <div class="fw-bold small text-truncate text-white" style="font-family: var(--font-ui, 'Inter', sans-serif);"><?= e($product['name']) ?></div>
                        <div class="d-flex align-items-center gap-2">
                            <span id="sticky-price" class="fw-bold text-white fs-6"><?= format_currency($displayPrice) ?></span>
                            <span class="badge" style="background: #1B1B1E; color: #DFB75C; font-size: 10px; border: 1px solid #28282B;" id="sticky-variant-name"><?= e($defaultVariant['variant_name'] ?? 'Standard') ?></span>
                        </div>
                    </div>
                </div>

                <!-- Right: Quick Actions -->
                <div class="d-flex align-items-center gap-2 flex-shrink-0">
                    <button type="submit" class="btn btn-pdp-red btn-sm px-3 text-nowrap d-flex align-items-center gap-1" style="height: 42px; border-radius: 8px; font-size: 13px; font-weight: 700; text-transform: uppercase;">
                        <i class="bi bi-bag-plus-fill"></i> Add to Bag
                    </button>
                    <button type="submit" name="buy_now" value="1" class="btn btn-pdp-gold btn-sm px-3 text-dark fw-bold d-none d-sm-inline-flex align-items-center gap-1" style="height: 42px; border-radius: 8px; font-size: 13px; text-transform: uppercase;">
                        <i class="bi bi-lightning-fill"></i> Buy Now
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
</div> <!-- /product-pdp-wrapper -->

<!-- =========================================================================
     FULLSCREEN HIGH-RES DIAL ZOOM LIGHTBOX MODAL
     ========================================================================= -->
<div id="pdpZoomModal" class="d-none position-fixed w-100 h-100" style="z-index: 2050; top: 0; left: 0; right: 0; bottom: 0; background: rgba(5, 8, 15, 0.96); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);">
    <!-- Close & Action Controls -->
    <div class="position-absolute top-0 start-0 end-0 p-3 p-md-4 d-flex align-items-center justify-content-between" style="z-index: 2060;">
        <div class="text-white d-flex align-items-center gap-2">
            <span class="badge py-2 px-3" style="background: rgba(197, 155, 63, 0.2); color: #DFB75C; border: 1px solid rgba(197, 155, 63, 0.4); font-size: 11px; letter-spacing: 1px;">
                <i class="bi bi-search me-1"></i>MACRO HOROLOGY ZOOM
            </span>
            <span class="d-none d-md-inline text-white-50 small">Tap image to toggle 2.5x macro magnification</span>
        </div>
        <button type="button" class="btn btn-outline-light rounded-circle d-flex align-items-center justify-content-center p-0 shadow" style="width: 40px; height: 40px; background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.3);" onclick="closePdpZoomModal()" aria-label="Close Fullscreen Zoom">
            <i class="bi bi-x-lg fs-6"></i>
        </button>
    </div>

    <!-- Centered High-Res Image Canvas -->
    <div class="w-100 h-100 d-flex align-items-center justify-content-center p-3 p-md-5 overflow-hidden position-relative" id="pdpZoomViewport" onclick="toggleZoomMagnify(event)">
        <img id="pdpZoomImage" src="<?= e($primaryImage) ?>" alt="<?= e($product['name']) ?>" class="img-fluid" style="max-height: 82vh; max-width: 88vw; object-fit: contain; transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1); cursor: zoom-in; user-select: none;">
    </div>

    <!-- Multi-Angle Thumbnails Rail inside Lightbox -->
    <?php if (count($images) > 1): ?>
    <div class="position-absolute bottom-0 start-0 end-0 p-3 d-flex justify-content-center gap-2 overflow-x-auto" style="z-index: 2060; background: linear-gradient(to top, rgba(0,0,0,0.85), transparent);">
        <?php foreach ($images as $idx => $img): ?>
        <button type="button" class="btn p-1 border rounded-3 zoom-thumb-btn <?= $idx === 0 ? 'border-warning shadow' : 'border-secondary' ?>" style="width: 52px; height: 52px; background: rgba(255,255,255,0.12); flex-shrink: 0;" onclick="switchZoomPhoto('<?= e($img['image_url']) ?>', this)">
            <img src="<?= e($img['image_url']) ?>" class="w-100 h-100 object-fit-contain p-1" alt="">
        </button>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<!-- =========================================================================
     PDP JAVASCRIPT: VARIANTS, HOTSPOTS, LIVE DIAL, STICKY BAR, REVIEWS
     ========================================================================= -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Variant Switching
    // 1. Variant Switching
    window.selectPdpVariant = function(btn) {
        document.querySelectorAll('.variant-pill').forEach(b => {
            b.classList.remove('active');
            b.style.background = '#141416';
            b.style.color = '#A0A0A5';
            b.style.borderColor = '#28282B';
        });
        btn.classList.add('active');
        btn.style.background = '#E63946';
        btn.style.color = '#FFFFFF';
        btn.style.borderColor = '#E63946';

        const varId = btn.dataset.variantId;
        const sku = btn.dataset.sku;
        const price = btn.dataset.price;
        const name = btn.dataset.variantName;
        const stock = parseInt(btn.dataset.stock, 10);

        document.getElementById('selected-variant-id').value = varId;
        document.getElementById('sticky-variant-id').value = varId;
        document.getElementById('display-sku').innerText = sku;
        document.getElementById('display-price').innerText = price;
        document.getElementById('sticky-price').innerText = price;
        document.getElementById('selected-variant-label').innerText = name;
        document.getElementById('sticky-variant-name').innerText = name;

        const badge = document.getElementById('stock-badge');
        if (stock < 1) {
            badge.className = 'badge bg-danger-subtle text-danger p-2';
            badge.innerHTML = '<i class="bi bi-x-circle me-1"></i>Currently Out of Stock';
        } else {
            badge.className = 'badge p-2';
            badge.style.background = '#1B1B1E';
            badge.style.color = '#D1D5DB';
            badge.innerHTML = '<i class="bi bi-check-circle me-1" style="color: #DFB75C;"></i>In Stock &bull; Calibrated for Same-Day Dispatch';
        }
    };

    // 2. Quantity Stepper Controls ([-] 1 [+])
    window.pdpQtyDec = function() {
        const input = document.getElementById('pdp-qty-input');
        if (!input) return;
        let val = parseInt(input.value, 10) || 1;
        if (val > 1) {
            input.value = val - 1;
        }
    };

    window.pdpQtyInc = function() {
        const input = document.getElementById('pdp-qty-input');
        if (!input) return;
        let val = parseInt(input.value, 10) || 1;
        if (val < 10) {
            input.value = val + 1;
        }
    };

    // 3. Dot Pagination Selector (● ○ ○ ○)
    window.selectDotPhoto = function(idx) {
        const thumbs = document.querySelectorAll('.thumb-btn');
        if (thumbs && thumbs[idx]) {
            thumbs[idx].click();
        } else {
            document.querySelectorAll('.pdp-dot').forEach((d, i) => {
                if (i === idx) {
                    d.classList.add('active');
                    d.style.width = '22px';
                    d.style.background = '#E63946';
                } else {
                    d.classList.remove('active');
                    d.style.width = '7px';
                    d.style.background = '#38383E';
                }
            });
        }
    };

    // 4. Thumbnail Switching with Pagination Dot Sync
    window.switchMainPhoto = function(btn) {
        const imgSrc = btn.dataset.imgSrc;
        const imgIdx = parseInt(btn.dataset.imgIndex, 10) || 0;
        const mainImg = document.getElementById('main-product-image');
        if (mainImg) mainImg.src = imgSrc;

        document.querySelectorAll('.thumb-btn').forEach(b => {
            b.classList.remove('border-danger', 'shadow');
            b.style.borderColor = '#28282B';
        });
        btn.classList.add('border-danger', 'shadow');
        btn.style.borderColor = '#E63946';

        // Synchronize carousel dots
        document.querySelectorAll('.pdp-dot').forEach((d, i) => {
            if (i === imgIdx) {
                d.classList.add('active');
                d.style.width = '22px';
                d.style.background = '#E63946';
            } else {
                d.classList.remove('active');
                d.style.width = '7px';
                d.style.background = '#38383E';
            }
        });

        // Turn off live dial view if active so photo is visible
        const liveView = document.getElementById('live-dial-view');
        const staticView = document.getElementById('static-dial-view');
        if (liveView && !liveView.classList.contains('d-none')) {
            liveView.classList.add('d-none');
            liveView.classList.remove('d-flex');
            staticView.classList.remove('d-none');
            staticView.classList.add('d-flex');
            const txt = document.getElementById('live-dial-text');
            if (txt) txt.innerText = 'Live Clock';
        }
    };

    // 3. Hotspot Pin Interaction
    window.selectHotspot = function(btn) {
        document.querySelectorAll('.hotspot-pin').forEach(p => p.classList.remove('active'));
        btn.classList.add('active');

        const num = btn.innerText.trim();
        const title = btn.dataset.title;
        const desc = btn.dataset.desc;

        document.getElementById('hotspot-active-num').innerText = num;
        document.getElementById('hotspot-active-title').innerText = title;
        document.getElementById('hotspot-active-desc').innerText = desc;
    };

    // 4. Live Clock Animated Hands Engine
    const liveDialBtn = document.getElementById('btn-toggle-live-dial');
    if (liveDialBtn) {
        let isLiveActive = false;
        let clockTimer = null;

        function updateClockHands() {
            const now = new Date();
            const sec = now.getSeconds() + now.getMilliseconds() / 1000;
            const min = now.getMinutes() + sec / 60;
            const hr = (now.getHours() % 12) + min / 60;

            const secAngle = sec * 6;
            const minAngle = min * 6;
            const hrAngle = hr * 30;

            const secHand = document.getElementById('svg-sec-hand');
            const minHand = document.getElementById('svg-min-hand');
            const hrHand = document.getElementById('svg-hour-hand');

            if (secHand) secHand.setAttribute('transform', 'rotate(' + secAngle + ' 50 50)');
            if (minHand) minHand.setAttribute('transform', 'rotate(' + minAngle + ' 50 50)');
            if (hrHand) hrHand.setAttribute('transform', 'rotate(' + hrAngle + ' 50 50)');
        }

        liveDialBtn.addEventListener('click', function () {
            isLiveActive = !isLiveActive;
            const staticView = document.getElementById('static-dial-view');
            const liveView = document.getElementById('live-dial-view');
            const txt = document.getElementById('live-dial-text');

            if (isLiveActive) {
                staticView.classList.add('d-none');
                staticView.classList.remove('d-flex');
                liveView.classList.remove('d-none');
                liveView.classList.add('d-flex');
                txt.innerText = 'Static Photo Mode';
                updateClockHands();
                clockTimer = setInterval(updateClockHands, 100);
            } else {
                liveView.classList.add('d-none');
                liveView.classList.remove('d-flex');
                staticView.classList.remove('d-none');
                staticView.classList.add('d-flex');
                txt.innerText = 'Live Clock Mode';
                if (clockTimer) clearInterval(clockTimer);
            }
        });
    }

    // 5. Sticky Floating Buy Bar Trigger on Scroll
    const stickyBar = document.getElementById('sticky-bar');
    if (stickyBar) {
        window.addEventListener('scroll', function () {
            if (window.scrollY > 480) {
                stickyBar.classList.add('is-visible');
            } else {
                stickyBar.classList.remove('is-visible');
            }
        }, { passive: true });
    }

    // 6. Review Helpful Upvote AJAX
    window.voteReviewHelpful = function(reviewId, btn) {
        fetch('/api/reviews/' + reviewId + '/vote', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const counter = btn.querySelector('.helpful-counter');
                if (counter && data.helpful_count) {
                    counter.innerText = data.helpful_count;
                }
                btn.classList.add('text-success');
                btn.disabled = true;
            } else {
                alert(data.message || 'Already voted.');
            }
        })
        .catch(() => {});
    };

    // 7. Pincode Delivery Availability Checker with LocalStorage & Dynamic Dates
    window.checkPincodeDelivery = function() {
        const input = document.getElementById('delivery-pincode-input');
        const resBox = document.getElementById('pincode-result');
        if (!input || !resBox) return;

        const pin = input.value.trim();
        if (!/^\d{6}$/.test(pin)) {
            resBox.className = 'small text-danger mt-2';
            resBox.innerHTML = '<i class="bi bi-exclamation-circle me-1"></i>Please enter a valid 6-digit Indian Postal PIN code.';
            return;
        }

        try {
            localStorage.setItem('welkar_pincode', pin);
        } catch(e) {}

        const now = new Date();
        const deliveryDate = new Date(now.getTime() + (3 * 24 * 60 * 60 * 1000));
        const options = { weekday: 'long', month: 'short', day: 'numeric' };
        const dateStr = deliveryDate.toLocaleDateString('en-IN', options);

        resBox.className = 'small mt-2 p-2 bg-success-subtle text-success rounded border border-success-subtle';
        resBox.innerHTML = `
            <div class="fw-bold mb-1"><i class="bi bi-truck text-success me-1"></i>Delivery by ${dateStr}</div>
            <div class="text-secondary" style="font-size: 11px;">
                <span>✓ Free Insured Express Courier</span> &bull; 
                <span>✓ Cash on Delivery (COD) Available</span> &bull; 
                <span>✓ 7-Day Doorstep Replacement</span>
            </div>
        `;
    };

    // Auto-load saved pincode on page load
    try {
        const savedPin = localStorage.getItem('welkar_pincode');
        const pinInput = document.getElementById('delivery-pincode-input');
        if (savedPin && pinInput) {
            pinInput.value = savedPin;
            window.checkPincodeDelivery();
        }
    } catch(e) {}

    // Backwards compatibility alias
    window.checkPincode = window.checkPincodeDelivery;

    // 8. Fullscreen High-Res Dial Zoom Lightbox Handlers
    const zoomModal = document.getElementById('pdpZoomModal');
    const zoomImg = document.getElementById('pdpZoomImage');
    let isMagnified = false;

    window.openPdpZoomModal = function() {
        if (!zoomModal || !zoomImg) return;
        const mainImg = document.getElementById('main-product-image');
        if (mainImg) {
            zoomImg.src = mainImg.src;
        }
        isMagnified = false;
        zoomImg.style.transform = 'scale(1)';
        zoomImg.style.cursor = 'zoom-in';
        zoomModal.classList.remove('d-none');
        document.body.style.overflow = 'hidden';
    };

    window.closePdpZoomModal = function() {
        if (!zoomModal) return;
        zoomModal.classList.add('d-none');
        document.body.style.overflow = '';
        if (zoomImg) {
            zoomImg.style.transform = 'scale(1)';
            isMagnified = false;
        }
    };

    window.switchZoomPhoto = function(url, btn) {
        if (!zoomImg) return;
        zoomImg.src = url;
        isMagnified = false;
        zoomImg.style.transform = 'scale(1)';
        zoomImg.style.cursor = 'zoom-in';
        document.querySelectorAll('.zoom-thumb-btn').forEach(b => {
            b.classList.remove('border-warning', 'shadow');
            b.classList.add('border-secondary');
        });
        if (btn) {
            btn.classList.remove('border-secondary');
            btn.classList.add('border-warning', 'shadow');
        }
    };

    window.toggleZoomMagnify = function(e) {
        if (!zoomImg) return;
        // If clicking close button or thumbnail bar, ignore
        if (e && (e.target.closest('button') || e.target.closest('.zoom-thumb-btn'))) return;

        isMagnified = !isMagnified;
        if (isMagnified) {
            zoomImg.style.transform = 'scale(2.4)';
            zoomImg.style.cursor = 'zoom-out';
        } else {
            zoomImg.style.transform = 'scale(1)';
            zoomImg.style.cursor = 'zoom-in';
        }
    };

    // Close zoom on ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && zoomModal && !zoomModal.classList.contains('d-none')) {
            window.closePdpZoomModal();
        }
    });

    // 9. Sticky Floating Purchase Bar Controller
    const stickyBar = document.getElementById('sticky-bar');
    const mainForm = document.getElementById('main-pdp-cart-form');
    if (stickyBar && mainForm) {
        window.addEventListener('scroll', () => {
            const rect = mainForm.getBoundingClientRect();
            if (rect.bottom < 60 && window.scrollY > 300) {
                stickyBar.classList.add('visible');
                stickyBar.classList.add('is-visible');
            } else {
                stickyBar.classList.remove('visible');
                stickyBar.classList.remove('is-visible');
            }
        }, { passive: true });
    }
});
</script>
