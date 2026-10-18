<!-- ==========================================================================
     DEDICATED COLLECTION LANDING TEMPLATE - DARK LUXURY UNIFIED
     ========================================================================== -->

<!-- 1. Dedicated Collection Hero Banner -->
<section class="py-5 position-relative overflow-hidden reveal-on-scroll" style="background-color: #0B0B0C; border-bottom: 1px solid #2A2A2E;">
    <div class="container py-lg-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb small" style="font-family: var(--font-ui, 'Inter', sans-serif);">
                <li class="breadcrumb-item"><a href="/" class="text-decoration-none" style="color: #A0A0A5;">Home</a></li>
                <li class="breadcrumb-item"><a href="/catalog" class="text-decoration-none" style="color: #A0A0A5;">Collections</a></li>
                <li class="breadcrumb-item active fw-semibold" style="color: #F5F5F5;" aria-current="page"><?= e($config['heading']) ?></li>
            </ol>
        </nav>

        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <span class="badge mb-2 px-3 py-1 text-uppercase" style="background: rgba(197, 155, 63, 0.15); color: #C59B3F; border: 1px solid rgba(197, 155, 63, 0.3); font-size: 11px; letter-spacing: 1px;">
                    Curated Collection
                </span>
                <h1 class="display-5 fw-normal mb-3" style="font-family: var(--font-display, 'DM Serif Display', Georgia, serif); color: #F5F5F5 !important;">
                    <?= e($config['hero_title']) ?>
                </h1>
                <p class="lead mb-4" style="color: #A0A0A5; font-family: var(--font-ui, 'Inter', sans-serif); font-size: 1.05rem; line-height: 1.7; max-width: 680px;">
                    <?= e($config['hero_desc']) ?>
                </p>
            </div>

            <!-- Optional Active Coupons (For Offers Page) -->
            <?php if (!empty($config['active_coupons'])): ?>
            <div class="col-lg-4">
                <div class="vstack gap-3">
                    <?php foreach ($config['active_coupons'] as $cpn): ?>
                    <div class="p-3 border card-luxury position-relative overflow-hidden" style="background: #141416; border-color: #2A2A2E !important; border-radius: 8px;">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="badge text-uppercase" style="background: #E63946; color: #FFFFFF; font-size: 10px; border-radius: 4px;"><?= e($cpn['badge']) ?></span>
                            <span class="fs-5 fw-bold" style="color: #F5F5F5; font-family: var(--font-ui, 'Inter', sans-serif);"><?= e($cpn['discount']) ?></span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mt-2">
                            <code class="px-2 py-1 border rounded fw-bold fs-6" style="background: #1A1A1C; border-color: #2A2A2E !important; color: #C59B3F;"><?= e($cpn['code']) ?></code>
                            <button type="button" class="btn btn-sm btn-outline-light px-3 copy-coupon-btn" style="border-radius: 6px; font-size: 12px; font-weight: 600;" data-code="<?= e($cpn['code']) ?>" onclick="navigator.clipboard.writeText('<?= e($cpn['code']) ?>'); this.innerText='COPIED!'; setTimeout(() => this.innerText='COPY', 2000);">
                                COPY
                            </button>
                        </div>
                        <small class="d-block mt-2" style="font-size: 11px; color: #A0A0A5;"><i class="bi bi-info-circle me-1 text-warning"></i><?= e($cpn['min']) ?></small>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- 2. Product Catalog Grid & Filter Bar -->
<section class="py-5 reveal-on-scroll" style="background-color: #0B0B0C;">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-3" style="border-bottom: 1px solid #2A2A2E;">
            <div>
                <h3 class="h4 fw-normal mb-1" style="font-family: var(--font-display, 'DM Serif Display', Georgia, serif); color: #F5F5F5 !important;">Curated Timepieces</h3>
                <p class="small m-0" style="color: #A0A0A5;">Displaying <?= count($products) ?> handcrafted watches in stock</p>
            </div>

            <!-- Sorting Dropdown -->
            <div class="d-flex align-items-center gap-2 mt-3 mt-sm-0">
                <label for="collectionSortSelect" class="small fw-semibold text-nowrap" style="color: #A0A0A5;"><?= __('sort_by') ?>:</label>
                <select id="collectionSortSelect" class="form-select form-select-sm shadow-none" style="width: 190px; border-radius: 6px; border: 1px solid #2A2A2E; background-color: #141416; color: #F5F5F5;" onchange="location = this.value;">
                    <option value="?<?= http_build_query(array_merge($filters, ['sort' => 'featured'])) ?>" <?= ($filters['sort'] === 'featured') ? 'selected' : '' ?>><?= __('sort_featured') ?></option>
                    <option value="?<?= http_build_query(array_merge($filters, ['sort' => 'price_asc'])) ?>" <?= ($filters['sort'] === 'price_asc') ? 'selected' : '' ?>><?= __('sort_price_low') ?></option>
                    <option value="?<?= http_build_query(array_merge($filters, ['sort' => 'price_desc'])) ?>" <?= ($filters['sort'] === 'price_desc') ? 'selected' : '' ?>><?= __('sort_price_high') ?></option>
                    <option value="?<?= http_build_query(array_merge($filters, ['sort' => 'newest'])) ?>" <?= ($filters['sort'] === 'newest') ? 'selected' : '' ?>><?= __('sort_newest') ?></option>
                </select>
            </div>
        </div>

        <!-- Mobile Filter Trigger & Quick Bar (d-lg-none) -->
        <div class="d-lg-none mb-3 d-flex align-items-center justify-content-between">
            <button class="btn btn-outline-light btn-sm d-inline-flex align-items-center gap-2 px-3 py-2 shadow-none" 
                    type="button" 
                    data-bs-toggle="offcanvas" 
                    data-bs-target="#collectionFilterOffcanvas" 
                    aria-controls="collectionFilterOffcanvas"
                    style="border-radius: 6px; font-size: 13px; border-color: #2A2A2E;">
                <i class="bi bi-funnel-fill" style="color: #C59B3F;"></i>
                <span class="fw-semibold">Filter Watches</span>
                <?php
                    $activeFilterCount = count(array_filter([$filters['dial_type'] ?? '', $filters['min_price'] ?? '', $filters['max_price'] ?? '']));
                    if ($activeFilterCount > 0):
                ?>
                    <span class="badge bg-danger rounded-pill"><?= $activeFilterCount ?></span>
                <?php endif; ?>
            </button>
            <a href="/collections/<?= e($config['slug']) ?>" class="small text-decoration-none" style="color: #A0A0A5;">Reset All</a>
        </div>

        <div class="row g-4">
            <!-- Left Filter Sidebar (Offcanvas on <992px, Column on >=992px) -->
            <div class="col-lg-3">
                <div class="offcanvas-lg offcanvas-start" tabindex="-1" id="collectionFilterOffcanvas" aria-labelledby="collectionFilterOffcanvasLabel" style="background: #141416;">
                    <div class="offcanvas-header border-bottom py-3 px-4 d-lg-none" style="border-color: #2A2A2E !important;">
                        <h5 class="offcanvas-title fw-bold" id="collectionFilterOffcanvasLabel" style="font-size: 16px; color: #F5F5F5;">
                            <i class="bi bi-funnel me-2" style="color: #C59B3F;"></i>Filter &amp; Refine
                        </h5>
                        <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="offcanvas" data-bs-target="#collectionFilterOffcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body p-3 p-lg-0">
                        <div class="p-3 border card-luxury w-100" style="background: #141416; border-color: #2A2A2E !important; border-radius: 10px;">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-semibold m-0" style="color: #F5F5F5;"><i class="bi bi-funnel me-2 text-warning"></i><?= __('filter_by') ?></h6>
                                <a href="/collections/<?= e($config['slug']) ?>" class="small text-decoration-none" style="color: #C59B3F;">Reset</a>
                            </div>

                            <!-- Dial Type Filter -->
                            <div class="mb-3 border-bottom pb-3" style="border-color: #2A2A2E !important;">
                                <label class="form-label small fw-semibold text-uppercase" style="color: #A0A0A5; font-size: 11px;"><?= __('dial_type') ?></label>
                                <div class="d-flex flex-column gap-1">
                                    <?php foreach ($dialTypes as $dt): ?>
                                    <a href="?<?= http_build_query(array_merge($filters, ['dial_type' => $dt['dial_type']])) ?>"
                                       class="btn btn-sm text-start" 
                                       style="font-size: 13px; border-radius: 6px; <?= ($filters['dial_type'] === $dt['dial_type']) ? 'background: #E63946; color: #FFFFFF; border: 1px solid #E63946;' : 'background: #1A1A1C; color: #F5F5F5; border: 1px solid #2A2A2E;' ?>">
                                        <?= e($dt['dial_type']) ?>
                                    </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Price Filter -->
                            <div class="mb-2">
                                <label class="form-label small fw-semibold text-uppercase" style="color: #A0A0A5; font-size: 11px;">Price Range</label>
                                <div class="d-flex flex-column gap-1">
                                    <a href="?<?= http_build_query(array_merge($filters, ['min_price' => '', 'max_price' => 3000])) ?>" class="btn btn-sm text-start" style="font-size: 12.5px; border-radius: 6px; background: #1A1A1C; color: #F5F5F5; border: 1px solid #2A2A2E;">Under ₹3,000</a>
                                    <a href="?<?= http_build_query(array_merge($filters, ['min_price' => 3000, 'max_price' => 6000])) ?>" class="btn btn-sm text-start" style="font-size: 12.5px; border-radius: 6px; background: #1A1A1C; color: #F5F5F5; border: 1px solid #2A2A2E;">₹3,000 - ₹6,000</a>
                                    <a href="?<?= http_build_query(array_merge($filters, ['min_price' => 6000, 'max_price' => 10000])) ?>" class="btn btn-sm text-start" style="font-size: 12.5px; border-radius: 6px; background: #1A1A1C; color: #F5F5F5; border: 1px solid #2A2A2E;">₹6,000+</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Grid (2 columns on mobile, 3 on desktop) -->
            <div class="col-lg-9">
                <?php if (empty($products)): ?>
                    <div class="text-center py-5 border card-luxury" style="background: #141416; border-color: #2A2A2E !important; border-radius: 10px;">
                        <i class="bi bi-watch fs-1" style="color: #A0A0A5;"></i>
                        <h5 class="fw-semibold mt-3" style="font-family: var(--font-display, 'DM Serif Display', Georgia, serif); color: #F5F5F5;">No watches match this criteria</h5>
                        <p class="small" style="color: #A0A0A5;">Try broadening your search filters or resetting all parameters.</p>
                        <a href="/collections/<?= e($config['slug']) ?>" class="btn btn-welkar btn-sm px-4">Reset Filters</a>
                    </div>
                <?php else: ?>
                    <div class="row g-2 g-sm-3 row-cols-2 row-cols-sm-2 row-cols-md-3">
                        <?php foreach ($products as $prod): ?>
                        <div class="col">
                            <div class="card h-100 border product-card card-luxury card-tilt-3d position-relative overflow-hidden" style="background: #141416; border-color: #2A2A2E !important; border-radius: 10px;">
                                <!-- Primary Product Image & Link (Locked 4:5 aspect ratio) -->
                                <div class="position-relative text-center" style="width: 100%; aspect-ratio: 4/5; background: #1A1A1C; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                    <a href="/product/<?= e($prod['slug']) ?>" class="d-block w-100 h-100 d-flex align-items-center justify-content-center p-2 p-sm-3">
                                        <img src="<?= e($prod['primary_image'] ?: '/assets/images/products/placeholder.jpg') ?>" 
                                             class="img-fluid" 
                                             alt="<?= e($prod['name']) ?>" 
                                             loading="lazy"
                                             style="max-width: 90%; max-height: 90%; object-fit: contain; transition: transform var(--motion-standard) var(--ease-standard);">
                                    </a>

                                    <!-- Instant 1-Tap Quick Add Button on Thumbnail -->
                                    <button type="button" 
                                            class="btn btn-welkar position-absolute bottom-0 end-0 m-2 rounded-circle d-flex align-items-center justify-content-center shadow-sm btn-quick-add" 
                                            style="width: 34px; height: 34px; z-index: 10; padding: 0;"
                                            title="Instant Quick Add"
                                            onclick="quickAddToCart(<?= (int)($prod['default_variant_id'] ?? 1) ?>, '<?= e(addslashes($prod['name'])) ?>', <?= (float)$prod['base_price'] ?>, '<?= e($prod['primary_image']) ?>', this)">
                                        <i class="bi bi-bag-plus" style="font-size: 15px;"></i>
                                    </button>

                                    <!-- Savings Badge -->
                                    <?php if ($prod['compare_at_price'] > $prod['base_price']): ?>
                                    <span class="badge position-absolute top-0 start-0 m-2" style="background: #E63946; color: #FFFFFF; font-size: 10px; font-weight: 600; border-radius: 4px; padding: 4px 8px;">
                                        SAVE ₹<?= number_format($prod['compare_at_price'] - $prod['base_price']) ?>
                                    </span>
                                    <?php endif; ?>
                                </div>

                                <div class="card-body p-2 p-sm-3 d-flex flex-column justify-content-between">
                                    <div>
                                        <div class="text-uppercase fw-semibold mb-1 text-truncate" style="color: #A0A0A5; font-size: 10.5px; letter-spacing: 0.5px;">
                                            <?= e($prod['brand_name']) ?> &bull; <?= e($prod['dial_type']) ?>
                                        </div>
                                        <!-- 2-Line Title Clamp for uniform mobile card height -->
                                        <h6 class="card-title fw-semibold mb-1" style="font-family: var(--font-ui, 'Inter', sans-serif); font-size: clamp(12.5px, 2.5vw, 14.5px); display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; min-height: 2.6em; line-height: 1.3;">
                                            <a href="/product/<?= e($prod['slug']) ?>" class="text-decoration-none" style="color: #F5F5F5;" title="<?= e($prod['name']) ?>">
                                                <?= e($prod['name']) ?>
                                            </a>
                                        </h6>
                                    </div>

                                    <div class="pt-2 border-top mt-2" style="border-color: #2A2A2E !important;">
                                        <div class="d-flex align-items-baseline gap-2 mb-2 flex-wrap">
                                            <span class="fw-bold fs-6" style="color: #F5F5F5; font-family: var(--font-ui, 'Inter', sans-serif);"><?= format_currency($prod['base_price']) ?></span>
                                            <?php if ($prod['compare_at_price'] > $prod['base_price']): ?>
                                                <small class="text-decoration-line-through" style="font-size: 11px; color: #8A8A90;"><?= format_currency($prod['compare_at_price']) ?></small>
                                            <?php endif; ?>
                                        </div>

                                        <div class="d-flex gap-1">
                                            <a href="/product/<?= e($prod['slug']) ?>" class="btn btn-outline-light btn-sm flex-grow-1" style="font-size: 11.5px; height: 32px; border-radius: 6px; padding: 0 6px; display: flex; align-items: center; justify-content: center; border-color: #2A2A2E;">
                                                View Watch
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-welkar btn-sm px-2" 
                                                    style="height: 32px; border-radius: 6px; font-size: 11px; font-weight: 600;"
                                                    title="Quick Add"
                                                    onclick="quickAddToCart(<?= (int)($prod['default_variant_id'] ?? 1) ?>, '<?= e(addslashes($prod['name'])) ?>', <?= (float)$prod['base_price'] ?>, '<?= e($prod['primary_image']) ?>', this)">
                                                <i class="bi bi-bag-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($last_page > 1): ?>
                    <nav class="mt-4 d-flex justify-content-center" aria-label="Collection pagination">
                        <ul class="pagination pagination-sm">
                            <?php for ($p = 1; $p <= $last_page; $p++): ?>
                            <li class="page-item <?= ($p === $page) ? 'active' : '' ?>">
                                <a class="page-link" href="?<?= http_build_query(array_merge($filters, ['page' => $p])) ?>" style="<?= ($p === $page) ? 'background-color: #E63946; border-color: #E63946; color: #fff;' : 'background-color: #141416; color: #F5F5F5; border-color: #2A2A2E;' ?>"><?= $p ?></a>
                            </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- 3. Dedicated Buying / Styling Guide Section -->
<?php if (!empty($config['guide_content'])): ?>
<section class="py-5 position-relative reveal-on-scroll" style="background-color: #0E0E10; border-top: 1px solid #2A2A2E;">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5">
                <span class="badge mb-2 px-3 py-1 text-uppercase" style="background: rgba(197, 155, 63, 0.15); color: #C59B3F; border: 1px solid rgba(197, 155, 63, 0.3); font-size: 11px; letter-spacing: 1px;">
                    Buyer's Advisory
                </span>
                <h2 class="display-6 fw-normal mb-3" style="font-family: var(--font-display, 'DM Serif Display', Georgia, serif); color: #F5F5F5;">
                    <?= e($config['guide_title']) ?>
                </h2>
                <p class="small" style="color: #A0A0A5; line-height: 1.8;">
                    Every Welkar creation is subjected to stringent tolerance benchmarks. Understand the engineering behind our dials, movement calibers, and strap ergonomics before choosing your signature piece.
                </p>
                <div class="d-inline-flex align-items-center gap-3 p-3 border card-luxury mt-2" style="background: #141416; border-color: #2A2A2E !important; border-radius: 8px;">
                    <i class="bi bi-award-fill fs-3" style="color: #C59B3F;"></i>
                    <div>
                        <strong class="d-block small" style="color: #F5F5F5;">2-Year Precision Movement Warranty</strong>
                        <small style="color: #A0A0A5; font-size: 11.5px;">Complimentary door-to-door insured transit across India</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="vstack gap-3">
                    <?php foreach ($config['guide_content'] as $gTitle => $gDesc): ?>
                    <div class="p-3 border card-luxury" style="background: #141416; border-color: #2A2A2E !important; border-radius: 8px;">
                        <h6 class="fw-semibold mb-1" style="font-family: var(--font-ui, 'Inter', sans-serif); color: #F5F5F5;">
                            <i class="bi bi-check2-circle me-2" style="color: #C59B3F;"></i><?= e($gTitle) ?>
                        </h6>
                        <p class="small mb-0 ps-4" style="color: #A0A0A5;"><?= e($gDesc) ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- 4. Frequently Asked Questions Section (Matching Home Page) -->
<?php
$faq_title = "Looking for answers?";
$faq_badge = "FAQ'S";
$faq_subtitle = "Everything you need to know about serial authentication, pan-India insured COD transit, 30M hydrostatic submersion testing, and atelier care.";
include __DIR__ . '/../partials/faq_section.php';
?>
