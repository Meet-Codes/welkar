<div class="container py-4">
    <!-- Breadcrumb & Title -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small" style="font-family: var(--font-ui, 'Inter', sans-serif);">
            <li class="breadcrumb-item"><a href="/" class="text-secondary text-decoration-none">Home</a></li>
            <li class="breadcrumb-item active fw-semibold" style="color: var(--color-heading-ivory, #F5F5F5);" aria-current="page">Catalog</li>
        </ol>
    </nav>

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-3 border-bottom" style="border-color: var(--color-border-divider, #2E2E2E) !important;">
        <div>
            <h1 class="h2 fw-normal mb-1" style="font-family: var(--font-display, 'Playfair Display', 'DM Serif Display', Georgia, serif); color: var(--color-heading-ivory, #F5F5F5);">Timepiece Collection</h1>
            <p class="small m-0" style="color: var(--color-body-text, #A0A0A0);">Showing <?= count($products) ?> of <?= (int)$total ?> handcrafted watches</p>
        </div>

        <!-- Sorting -->
        <div class="d-flex align-items-center gap-2 mt-3 mt-sm-0">
            <label class="small fw-semibold text-nowrap" style="color: var(--color-body-text, #A0A0A0);"><?= __('sort_by') ?>:</label>
            <select class="form-select form-select-sm shadow-none" style="width: 200px; border-radius: var(--radius-sm, 6px); border: 1px solid var(--color-border-divider, #2E2E2E); background-color: var(--color-bg-secondary, #1A1A1C); color: var(--color-heading-ivory, #F5F5F5);" onchange="location = this.value;">
                <option value="/catalog?<?= http_build_query(array_merge($filters, ['sort' => 'featured'])) ?>" <?= ($filters['sort'] === 'featured') ? 'selected' : '' ?>><?= __('sort_featured') ?></option>
                <option value="/catalog?<?= http_build_query(array_merge($filters, ['sort' => 'price_asc'])) ?>" <?= ($filters['sort'] === 'price_asc') ? 'selected' : '' ?>><?= __('sort_price_low') ?></option>
                <option value="/catalog?<?= http_build_query(array_merge($filters, ['sort' => 'price_desc'])) ?>" <?= ($filters['sort'] === 'price_desc') ? 'selected' : '' ?>><?= __('sort_price_high') ?></option>
                <option value="/catalog?<?= http_build_query(array_merge($filters, ['sort' => 'newest'])) ?>" <?= ($filters['sort'] === 'newest') ? 'selected' : '' ?>><?= __('sort_newest') ?></option>
            </select>
        </div>
    </div>

    <!-- Mobile Filter Trigger & Quick Bar (d-lg-none) -->
    <div class="d-lg-none mb-3 d-flex align-items-center justify-content-between">
        <button class="btn btn-outline-light btn-sm d-inline-flex align-items-center gap-2 px-3 py-2 shadow-none" 
                type="button" 
                data-bs-toggle="offcanvas" 
                data-bs-target="#catalogFilterOffcanvas" 
                aria-controls="catalogFilterOffcanvas"
                style="border-radius: var(--radius-sm, 6px); font-size: 13px; border-color: var(--color-border-divider, #2E2E2E); color: var(--color-heading-ivory, #F5F5F5);">
            <i class="bi bi-funnel-fill" style="color: var(--color-champagne-highlight, #D4AF7C);"></i>
            <span class="fw-semibold">Filter Watches</span>
            <?php
                $activeFilterCount = count(array_filter([$filters['gender'] ?? '', $filters['dial_type'] ?? '', $filters['brand'] ?? '', $filters['min_price'] ?? '', $filters['max_price'] ?? '']));
                if ($activeFilterCount > 0):
            ?>
                <span class="badge rounded-pill" style="background: var(--color-accent-deep-red, #E63946);"><?= $activeFilterCount ?></span>
            <?php endif; ?>
        </button>
        <a href="/catalog" class="small text-decoration-none" style="color: var(--color-champagne-highlight, #D4AF7C);">Reset All</a>
    </div>

    <div class="row g-4 reveal-on-scroll">
        <!-- Filter Sidebar (Responsive Offcanvas on <992px, Static Sidebar on >=992px) -->
        <div class="col-lg-3">
            <div class="offcanvas-lg offcanvas-start" tabindex="-1" id="catalogFilterOffcanvas" aria-labelledby="catalogFilterOffcanvasLabel" style="background-color: var(--color-bg-secondary, #1A1A1C); color: var(--color-heading-ivory, #F5F5F5);">
                <div class="offcanvas-header border-bottom py-3 px-4 d-lg-none" style="border-color: var(--color-border-divider, #2E2E2E) !important;">
                    <h5 class="offcanvas-title fw-bold" id="catalogFilterOffcanvasLabel" style="font-size: 16px; color: var(--color-heading-ivory, #F5F5F5);">
                        <i class="bi bi-funnel me-2" style="color: var(--color-champagne-highlight, #D4AF7C);"></i>Filter &amp; Refine
                    </h5>
                    <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="offcanvas" data-bs-target="#catalogFilterOffcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body p-3 p-lg-0">
                    <div class="p-3 border card-luxury w-100" style="background-color: var(--color-surface-card, #242424); border-color: var(--color-border-divider, #2E2E2E) !important; border-radius: var(--radius-md, 10px);">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-semibold m-0" style="color: var(--color-heading-ivory, #F5F5F5);"><i class="bi bi-funnel me-2" style="color: var(--color-champagne-highlight, #D4AF7C);"></i><?= __('filter_by') ?></h6>
                            <a href="/catalog" class="small text-decoration-none" style="color: var(--color-champagne-highlight, #D4AF7C);">Reset All</a>
                        </div>

                        <!-- Gender -->
                        <div class="mb-3 border-bottom pb-3" style="border-color: var(--color-border-divider, #2E2E2E) !important;">
                            <label class="form-label small fw-semibold text-uppercase" style="color: var(--color-muted-text, #737373); font-size: 11px;">Gender</label>
                            <div class="d-flex flex-column gap-1">
                                <?php foreach (['Men', 'Women', 'Unisex'] as $g): ?>
                                <a href="/catalog?<?= http_build_query(array_merge($filters, ['gender' => $g])) ?>"
                                   class="btn btn-sm text-start" 
                                   style="font-size: 13px; border-radius: var(--radius-sm, 6px); <?= ($filters['gender'] === $g) ? 'background: var(--color-accent-deep-red, #E63946); color: #FFFFFF; border: 1px solid var(--color-accent-deep-red, #E63946);' : 'background: var(--color-bg-secondary, #1A1A1C); color: var(--color-body-text, #A0A0A0); border: 1px solid var(--color-border-divider, #2E2E2E);' ?>">
                                    <?= e($g) ?>
                                </a>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Dial Type -->
                        <div class="mb-3 border-bottom pb-3" style="border-color: var(--color-border-divider, #2E2E2E) !important;">
                            <label class="form-label small fw-semibold text-uppercase" style="color: var(--color-muted-text, #737373); font-size: 11px;"><?= __('dial_type') ?></label>
                            <div class="d-flex flex-column gap-1">
                                <?php foreach ($dialTypes as $dt): ?>
                                <a href="/catalog?<?= http_build_query(array_merge($filters, ['dial_type' => $dt['dial_type']])) ?>"
                                   class="btn btn-sm text-start" 
                                   style="font-size: 13px; border-radius: var(--radius-sm, 6px); <?= ($filters['dial_type'] === $dt['dial_type']) ? 'background: var(--color-accent-deep-red, #E63946); color: #FFFFFF; border: 1px solid var(--color-accent-deep-red, #E63946);' : 'background: var(--color-bg-secondary, #1A1A1C); color: var(--color-body-text, #A0A0A0); border: 1px solid var(--color-border-divider, #2E2E2E);' ?>">
                                    <?= e($dt['dial_type']) ?>
                                </a>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Brands -->
                        <div class="mb-3 border-bottom pb-3" style="border-color: var(--color-border-divider, #2E2E2E) !important;">
                            <label class="form-label small fw-semibold text-uppercase" style="color: var(--color-muted-text, #737373); font-size: 11px;">Brand</label>
                            <div class="d-flex flex-column gap-1">
                                <?php foreach ($brands as $b): ?>
                                <a href="/catalog?<?= http_build_query(array_merge($filters, ['brand' => $b['slug']])) ?>"
                                   class="btn btn-sm text-start" 
                                   style="font-size: 13px; border-radius: var(--radius-sm, 6px); <?= ($filters['brand'] === $b['slug']) ? 'background: var(--color-accent-deep-red, #E63946); color: #FFFFFF; border: 1px solid var(--color-accent-deep-red, #E63946);' : 'background: var(--color-bg-secondary, #1A1A1C); color: var(--color-body-text, #A0A0A0); border: 1px solid var(--color-border-divider, #2E2E2E);' ?>">
                                    <?= e($b['name']) ?>
                                </a>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-2">
                            <label class="form-label small fw-semibold text-uppercase" style="color: var(--color-muted-text, #737373); font-size: 11px;">Price Range</label>
                            <div class="d-flex flex-column gap-1">
                                <a href="/catalog?<?= http_build_query(array_merge($filters, ['min_price' => '', 'max_price' => 3000])) ?>" class="btn btn-sm text-start" style="font-size: 12.5px; border-radius: var(--radius-sm, 6px); background: var(--color-bg-secondary, #1A1A1C); color: var(--color-body-text, #A0A0A0); border: 1px solid var(--color-border-divider, #2E2E2E);">Under ₹3,000</a>
                                <a href="/catalog?<?= http_build_query(array_merge($filters, ['min_price' => 3000, 'max_price' => 6000])) ?>" class="btn btn-sm text-start" style="font-size: 12.5px; border-radius: var(--radius-sm, 6px); background: var(--color-bg-secondary, #1A1A1C); color: var(--color-body-text, #A0A0A0); border: 1px solid var(--color-border-divider, #2E2E2E);">₹3,000 - ₹6,000</a>
                                <a href="/catalog?<?= http_build_query(array_merge($filters, ['min_price' => 6000, 'max_price' => 10000])) ?>" class="btn btn-sm text-start" style="font-size: 12.5px; border-radius: var(--radius-sm, 6px); background: var(--color-bg-secondary, #1A1A1C); color: var(--color-body-text, #A0A0A0); border: 1px solid var(--color-border-divider, #2E2E2E);">₹6,000 - ₹10,000</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-lg-9">
            <?php if (empty($products)): ?>
                <div class="text-center py-5 border card-luxury" style="background-color: var(--color-surface-card, #242424); border-color: var(--color-border-divider, #2E2E2E) !important; border-radius: var(--radius-md, 10px);">
                    <i class="bi bi-search fs-1" style="color: var(--color-muted-text, #737373);"></i>
                    <h5 class="fw-normal mt-3" style="font-family: var(--font-display, 'Playfair Display', 'DM Serif Display', Georgia, serif); color: var(--color-heading-ivory, #F5F5F5);">No watches match your criteria</h5>
                    <p class="small" style="color: var(--color-body-text, #A0A0A0);">Try broadening your search filters or resetting all parameters.</p>
                    <a href="/catalog" class="btn btn-welkar btn-sm px-4">Reset Filters</a>
                </div>
            <?php else: ?>
                <div class="row g-2 g-sm-3 g-md-4 row-cols-2 row-cols-sm-2 row-cols-md-3">
                    <?php foreach ($products as $prod): ?>
                    <div class="col">
                        <div class="card h-100 border product-card card-luxury card-tilt-3d position-relative overflow-hidden" style="background-color: var(--color-surface-card, #242424); border-color: var(--color-border-divider, #2E2E2E) !important; border-radius: var(--radius-md, 10px);">
                            <!-- Product Image Container (Locked 4:5 aspect ratio) -->
                            <div class="position-relative text-center" style="width: 100%; aspect-ratio: 4/5; background: #141416; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                <a href="/product/<?= e($prod['slug']) ?>" class="d-block w-100 h-100 d-flex align-items-center justify-content-center p-2 p-sm-3">
                                    <img src="<?= e($prod['primary_image']) ?>" 
                                         alt="<?= e($prod['name']) ?>" 
                                         class="img-fluid" 
                                         loading="lazy" 
                                         style="max-width: 90%; max-height: 90%; object-fit: contain; transition: transform var(--motion-standard) var(--ease-standard);">
                                </a>

                                <!-- Instant 1-Tap Quick Add Button on Thumbnail -->
                                <button type="button" 
                                        class="btn btn-welkar position-absolute bottom-0 end-0 m-2 rounded-circle d-flex align-items-center justify-content-center shadow-sm btn-quick-add" 
                                        style="width: 34px; height: 34px; z-index: 10; padding: 0;"
                                        title="Instant Quick Add"
                                        onclick="quickAddToCart(<?= (int)($prod['default_variant_id'] ?? 1) ?>, '<?= e(addslashes($prod['name'])) ?>', <?= (float)($prod['display_price'] ?: $prod['base_price']) ?>, '<?= e($prod['primary_image']) ?>', this)">
                                    <i class="bi bi-bag-plus" style="font-size: 15px;"></i>
                                </button>

                                <!-- Savings Badge -->
                                <?php if ($prod['compare_at_price'] > $prod['base_price']): ?>
                                <span class="badge position-absolute top-0 start-0 m-1 m-sm-2 d-none d-sm-inline-block" style="background: var(--color-accent-deep-red, #E63946); font-size: 10px; font-weight: 600; border-radius: var(--radius-xs, 4px); padding: 4px 8px;">
                                    SAVE ₹<?= number_format($prod['compare_at_price'] - $prod['base_price']) ?>
                                </span>
                                <?php endif; ?>
                            </div>

                            <div class="card-body p-2 p-sm-3 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="text-uppercase fw-semibold mb-1 text-truncate" style="color: var(--color-champagne-highlight, #D4AF7C); font-size: 10.5px; letter-spacing: 0.5px;">
                                        <?= e($prod['brand_name']) ?> &bull; <?= e($prod['dial_type']) ?>
                                    </div>
                                    <!-- 2-Line Title Clamp for uniform mobile card height -->
                                    <h6 class="card-title fw-semibold mb-1" style="font-family: var(--font-ui, 'Inter', sans-serif); font-size: clamp(12.5px, 2.5vw, 14.5px); display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; min-height: 2.6em; line-height: 1.3;">
                                        <a href="/product/<?= e($prod['slug']) ?>" class="text-decoration-none" style="color: var(--color-heading-ivory, #F5F5F5);" title="<?= e($prod['name']) ?>">
                                            <?= e($prod['name']) ?>
                                        </a>
                                    </h6>
                                </div>

                                <div class="pt-2 border-top mt-2" style="border-color: var(--color-border-divider, #2E2E2E) !important;">
                                    <div class="d-flex align-items-baseline gap-1 gap-sm-2 mb-2 flex-wrap">
                                        <span class="fw-bold" style="color: var(--color-heading-ivory, #F5F5F5); font-family: var(--font-ui, 'Inter', sans-serif); font-size: clamp(14px, 3.2vw, 18px);"><?= format_currency($prod['display_price'] ?: $prod['base_price']) ?></span>
                                        <?php if ($prod['compare_at_price'] > $prod['base_price']): ?>
                                            <small class="text-decoration-line-through d-none d-sm-inline" style="color: var(--color-muted-text, #737373); font-size: 11px;"><?= format_currency($prod['compare_at_price']) ?></small>
                                        <?php endif; ?>
                                    </div>

                                    <div class="d-flex gap-1">
                                        <a href="/product/<?= e($prod['slug']) ?>" class="btn btn-outline-light btn-sm flex-grow-1 p-0 d-flex align-items-center justify-content-center" style="font-size: 11.5px; height: 34px; border-radius: var(--radius-sm, 6px); border-color: var(--color-border-divider, #2E2E2E); color: var(--color-heading-ivory, #F5F5F5);">
                                            <span>View Watch</span>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-welkar btn-sm px-2" 
                                                style="height: 34px; border-radius: var(--radius-sm, 6px); font-size: 11px; font-weight: 600;"
                                                title="Quick Add"
                                                onclick="quickAddToCart(<?= (int)($prod['default_variant_id'] ?? 1) ?>, '<?= e(addslashes($prod['name'])) ?>', <?= (float)($prod['display_price'] ?: $prod['base_price']) ?>, '<?= e($prod['primary_image']) ?>', this)">
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
                <nav class="mt-5">
                    <ul class="pagination pagination-sm justify-content-center gap-1">
                        <?php for ($p = 1; $p <= $last_page; $p++): ?>
                        <li class="page-item <?= ($p === $page) ? 'active' : '' ?>">
                            <a class="page-link" href="/catalog?<?= http_build_query(array_merge($filters, ['page' => $p])) ?>" style="<?= ($p === $page) ? 'background-color: var(--color-accent-deep-red, #E63946); border-color: var(--color-accent-deep-red, #E63946); color: #fff;' : 'background-color: var(--color-surface-card, #242424); color: var(--color-heading-ivory, #F5F5F5); border-color: var(--color-border-divider, #2E2E2E);' ?>"><?= $p ?></a>
                        </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
