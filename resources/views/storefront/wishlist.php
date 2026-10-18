<div class="container py-5 my-2">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small" style="font-family: var(--font-ui, 'Inter', sans-serif);">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none" style="color: #A0A0A5;">Home</a></li>
            <li class="breadcrumb-item active fw-semibold" style="color: #F5F5F5;" aria-current="page">Wishlist</li>
        </ol>
    </nav>

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-3" style="border-bottom: 1px solid #2A2A2E;">
        <div>
            <span class="badge mb-2 px-3 py-1 text-uppercase" style="background: rgba(197, 155, 63, 0.15); color: #C59B3F; border: 1px solid rgba(197, 155, 63, 0.3); font-size: 11px; letter-spacing: 1px;">
                Saved Creations
            </span>
            <h1 class="h2 fw-bold mt-1 mb-0" style="font-family: var(--font-display, 'DM Serif Display', serif); color: #F5F5F5; letter-spacing: -0.5px;">My Wishlist</h1>
        </div>
        <div class="mt-2 mt-sm-0">
            <span class="badge px-3 py-2 fw-semibold" style="font-size: 12px; background: #1A1A1C; color: #F5F5F5; border-radius: 6px; border: 1px solid #2A2A2E;">
                <?= count($items) ?> <?= count($items) === 1 ? 'Timepiece' : 'Timepieces' ?> Saved
            </span>
        </div>
    </div>

    <?php if (empty($items)): ?>
        <div class="text-center py-5 border my-4 p-4 p-md-5 card-luxury" style="background: #141416; border-radius: 10px; border-color: #2A2A2E !important;">
            <div class="p-3 rounded-circle d-inline-flex mb-3" style="background: rgba(197, 155, 63, 0.15); color: #C59B3F;">
                <i class="bi bi-heart fs-1"></i>
            </div>
            <h3 class="fw-bold mb-2" style="font-family: var(--font-display, 'DM Serif Display', serif); color: #F5F5F5;">Your Wishlist is Empty</h3>
            <p class="small mx-auto mb-4" style="max-width: 460px; line-height: 1.7; color: #A0A0A5; font-family: var(--font-ui, 'Inter', sans-serif);">
                Curate your personal collection of chronographs, automatic skeletons, and minimalist timepieces to monitor availability and secure yours when ready.
            </p>
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <a href="/catalog" class="btn btn-welkar px-4 py-2" style="border-radius: 6px; font-weight: 600;">Explore All Watches</a>
                <a href="/collections/new-arrivals" class="btn btn-outline-light px-4 py-2" style="border-radius: 6px; border-color: #2A2A2E; font-weight: 600;">View New Drops</a>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-4 row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4">
            <?php foreach ($items as $item): ?>
            <div class="col">
                <div class="card h-100 position-relative card-luxury" style="background: #141416; border-radius: 10px; border: 1px solid #2A2A2E; overflow: hidden;">
                    <!-- Product Image Container (Locked 4:5 Aspect Ratio) -->
                    <div class="position-relative" style="aspect-ratio: 4/5; background: #1A1A1C; display: flex; align-items: center; justify-content: center; padding: 1.25rem;">
                        <a href="/product/<?= e($item['slug']) ?>" class="d-flex w-100 h-100 align-items-center justify-content-center">
                            <img src="<?= e($item['primary_image'] ?: '/assets/images/products/placeholder.jpg') ?>" 
                                 class="img-fluid" 
                                 alt="<?= e($item['product_name']) ?>" 
                                 loading="lazy"
                                 style="max-height: 100%; max-width: 100%; object-fit: contain; transition: transform 0.4s var(--ease-standard, ease);">
                        </a>

                        <!-- Remove Button -->
                        <form action="/wishlist/remove" method="POST" class="position-absolute top-0 end-0 m-2">
                            <?= csrf_field() ?>
                            <input type="hidden" name="item_id" value="<?= (int)$item['wishlist_item_id'] ?>">
                            <button type="submit" class="btn btn-sm shadow-sm p-0 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border-radius: 6px; background: #141416; border: 1px solid #2A2A2E;" title="Remove from wishlist" aria-label="Remove watch">
                                <i class="bi bi-x-lg" style="font-size: 12px; color: #E63946;"></i>
                            </button>
                        </form>
                    </div>

                    <div class="card-body p-3 d-flex flex-column justify-content-between">
                        <div>
                            <div class="text-uppercase fw-semibold mb-1" style="font-size: 10.5px; letter-spacing: 0.8px; color: #C59B3F;">
                                <?= e($item['brand_name'] ?? 'Welkar') ?> &bull; <?= e($item['dial_type'] ?? 'Watch') ?>
                            </div>
                            <h6 class="card-title fw-bold mb-2" style="font-family: var(--font-ui, 'Inter', sans-serif); font-size: 14px; line-height: 1.4;">
                                <a href="/product/<?= e($item['slug']) ?>" class="text-decoration-none" style="color: #F5F5F5;">
                                    <?= e($item['product_name']) ?>
                                </a>
                            </h6>

                            <!-- Price -->
                            <div class="d-flex align-items-baseline gap-2 mb-2">
                                <span class="fw-bold fs-6" style="color: #F5F5F5; font-family: var(--font-ui, 'Inter', sans-serif);"><?= format_currency($item['variant_price'] ?? $item['base_price']) ?></span>
                                <?php if (($item['compare_at_price'] ?? 0) > ($item['variant_price'] ?? $item['base_price'])): ?>
                                    <small class="text-decoration-line-through" style="font-size: 12px; color: #8A8A90;"><?= format_currency($item['compare_at_price']) ?></small>
                                <?php endif; ?>
                            </div>

                            <!-- Stock state -->
                            <div class="mb-3">
                                <?php if ((int)$item['stock_quantity'] > 0): ?>
                                    <span class="badge" style="font-size: 11px; background: rgba(46, 125, 50, 0.2); color: #2ECC71; border: 1px solid rgba(46, 125, 50, 0.3); border-radius: 4px; font-weight: 500;">
                                        <i class="bi bi-check-circle me-1"></i>In Stock
                                    </span>
                                <?php else: ?>
                                    <span class="badge" style="font-size: 11px; background: rgba(230, 57, 70, 0.2); color: #E63946; border: 1px solid rgba(230, 57, 70, 0.3); border-radius: 4px; font-weight: 500;">
                                        <i class="bi bi-x-circle me-1"></i>Out of Stock
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Actions (Standardized 6px buttons) -->
                        <div class="d-grid gap-2 border-top pt-3" style="border-color: #2A2A2E !important;">
                            <form action="/wishlist/move-to-cart" method="POST">
                                <?= csrf_field() ?>
                                <input type="hidden" name="item_id" value="<?= (int)$item['wishlist_item_id'] ?>">
                                <button type="submit" class="btn btn-welkar btn-sm w-100" style="border-radius: 6px; height: 40px; font-weight: 600;" <?= ((int)$item['stock_quantity'] <= 0) ? 'disabled' : '' ?>>
                                    <i class="bi bi-bag-plus me-1"></i>Move to Bag
                                </button>
                            </form>
                            <a href="/product/<?= e($item['slug']) ?>" class="btn btn-outline-light btn-sm text-center fw-semibold" style="border-radius: 6px; border-color: #2A2A2E; height: 38px; display: inline-flex; align-items: center; justify-content: center; font-size: 12px;">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
