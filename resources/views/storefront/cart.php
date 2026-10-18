<div class="container py-4">
    <h1 class="h2 fw-normal mb-4" style="font-family: var(--font-display, 'DM Serif Display', Georgia, serif); color: #F5F5F5;">Your Shopping Bag</h1>

    <?php if (empty($cart['items'])): ?>
        <div class="text-center py-5 border card-luxury" style="background: #141416; border-color: #2A2A2E !important; border-radius: 10px;">
            <i class="bi bi-bag-x fs-1" style="color: #A0A0A5;"></i>
            <h4 class="fw-normal mt-3" style="font-family: var(--font-display, 'DM Serif Display', Georgia, serif); color: #F5F5F5;"><?= __('empty_cart') ?></h4>
            <p class="small" style="color: #A0A0A5;">Explore our curated collections of luxury, sports, and private-label watches.</p>
            <a href="/catalog" class="btn btn-welkar mt-2" style="border-radius: 6px;"><?= __('continue_shopping') ?></a>
        </div>
    <?php else: ?>
        <div class="row g-4 reveal-on-scroll">
            <!-- Items Table -->
            <div class="col-lg-8">
                <!-- Free Shipping Progress Tracker -->
                <?php
                    $threshold = (float)$cart['summary']['free_shipping_threshold'];
                    $subtotal = (float)$cart['summary']['subtotal'];
                    $progressPct = min(100, round(($subtotal / $threshold) * 100));
                    $needed = (float)$cart['summary']['needs_for_free_shipping'];
                ?>
                <div class="p-3 border mb-3 card-luxury" style="background: #141416; border-color: #2A2A2E !important; border-radius: 8px;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="small fw-semibold" style="color: #F5F5F5;">
                            <?php if ($needed <= 0): ?>
                                <i class="bi bi-check-circle-fill me-1" style="color: #2ECC71;"></i>Congratulations! You unlocked <strong>FREE Insured Express Shipping</strong>!
                            <?php else: ?>
                                <i class="bi bi-truck me-1" style="color: #C59B3F;"></i>Add <strong><?= format_currency($needed) ?></strong> more to unlock <strong>FREE Express Shipping</strong>!
                            <?php endif; ?>
                        </span>
                        <span class="badge" style="background: #1A1A1C; border: 1px solid #2A2A2E; color: #C59B3F; font-size: 10.5px; border-radius: 4px;"><?= $progressPct ?>%</span>
                    </div>
                    <div class="progress" style="height: 6px; background-color: #1A1A1C; border-radius: 3px;">
                        <div class="progress-bar" role="progressbar" style="width: <?= $progressPct ?>%; background-color: #E63946;" aria-valuenow="<?= $progressPct ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>

                <!-- Desktop Table View (d-none d-md-block) -->
                <div class="border overflow-hidden card-luxury d-none d-md-block mb-3" style="background: #141416; border-color: #2A2A2E !important; border-radius: 10px;">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" style="background: transparent; color: #F5F5F5; border-color: #2A2A2E !important;">
                            <thead style="background-color: #1A1A1C;">
                                <tr class="small fw-semibold text-uppercase" style="color: #A0A0A5; font-size: 11px; letter-spacing: 0.5px; border-bottom: 1px solid #2A2A2E;">
                                    <th style="padding: 14px 16px;">Watch</th>
                                    <th style="padding: 14px 16px;">Price</th>
                                    <th class="text-center" style="padding: 14px 16px;">Quantity</th>
                                    <th class="text-end" style="padding: 14px 16px;">Total</th>
                                    <th style="padding: 14px 16px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart['items'] as $item): ?>
                                <tr style="border-bottom: 1px solid #2A2A2E;">
                                    <td style="padding: 16px;">
                                        <div class="d-flex align-items-center">
                                            <img src="<?= e($item['image_url']) ?>" width="64" height="64" class="border object-fit-contain me-3 p-1 rounded" style="border-color: #2A2A2E !important; background: #1A1A1C;" alt="<?= e($item['product_name']) ?>">
                                            <div>
                                                <h6 class="mb-0 fw-semibold" style="font-family: var(--font-ui, 'Inter', sans-serif); font-size: 14px;">
                                                    <a href="/product/<?= e($item['product_slug']) ?>" class="text-decoration-none" style="color: #F5F5F5;"><?= e($item['product_name']) ?></a>
                                                </h6>
                                                <div class="small" style="font-size: 11px; color: #A0A0A5;">
                                                    <?= e($item['variant_name']) ?> &bull; SKU: <?= e($item['sku']) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="fw-semibold" style="color: #F5F5F5; padding: 16px;"><?= format_currency($item['price']) ?></td>
                                    <td class="text-center" style="width: 140px; padding: 16px;">
                                        <form action="/cart/update" method="POST" class="d-flex align-items-center justify-content-center gap-1">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="cart_item_id" value="<?= (int)$item['cart_item_id'] ?>">
                                            <button type="submit" name="quantity" value="<?= max(0, $item['quantity'] - 1) ?>" class="btn btn-sm px-2 py-0" style="background: #1A1A1C; color: #F5F5F5; border: 1px solid #2A2A2E; border-radius: 4px;" aria-label="Decrease quantity">&minus;</button>
                                            <span class="fw-bold px-2" style="color: #F5F5F5;"><?= (int)$item['quantity'] ?></span>
                                            <button type="submit" name="quantity" value="<?= $item['quantity'] + 1 ?>" class="btn btn-sm px-2 py-0" style="background: #1A1A1C; color: #F5F5F5; border: 1px solid #2A2A2E; border-radius: 4px;" aria-label="Increase quantity">&plus;</button>
                                        </form>
                                    </td>
                                    <td class="text-end fw-bold" style="color: #F5F5F5; padding: 16px;">
                                        <?= format_currency($item['price'] * $item['quantity']) ?>
                                    </td>
                                    <td class="text-end" style="padding: 16px;">
                                        <form action="/cart/remove" method="POST" onsubmit="return confirm('Remove watch from bag?');">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="cart_item_id" value="<?= (int)$item['cart_item_id'] ?>">
                                            <button type="submit" class="btn btn-sm border-0" style="color: #E63946;" title="Remove from bag" aria-label="Remove watch"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Mobile Card-Based Items List (d-md-none) -->
                <div class="d-flex flex-column gap-3 d-md-none mb-3">
                    <?php foreach ($cart['items'] as $item): ?>
                    <div class="p-3 border card-luxury" style="background: #141416; border-color: #2A2A2E !important; border-radius: 10px;">
                        <div class="d-flex gap-3 mb-2">
                            <img src="<?= e($item['image_url']) ?>" width="70" height="70" class="border object-fit-contain p-1 rounded-2 flex-shrink-0" style="border-color: #2A2A2E !important; background: #1A1A1C;" alt="<?= e($item['product_name']) ?>">
                            <div class="flex-grow-1 text-truncate">
                                <h6 class="mb-1 fw-semibold text-truncate" style="font-family: var(--font-ui, 'Inter', sans-serif); font-size: 14px;">
                                    <a href="/product/<?= e($item['product_slug']) ?>" class="text-decoration-none" style="color: #F5F5F5;"><?= e($item['product_name']) ?></a>
                                </h6>
                                <div class="small" style="font-size: 11px; color: #A0A0A5;">
                                    <?= e($item['variant_name']) ?> &bull; SKU: <?= e($item['sku']) ?>
                                </div>
                                <div class="fw-bold mt-1" style="color: #F5F5F5; font-size: 15px;">
                                    <?= format_currency($item['price']) ?>
                                </div>
                            </div>
                            <form action="/cart/remove" method="POST" onsubmit="return confirm('Remove watch from bag?');">
                                <?= csrf_field() ?>
                                <input type="hidden" name="cart_item_id" value="<?= (int)$item['cart_item_id'] ?>">
                                <button type="submit" class="btn btn-sm text-danger border-0 p-1" title="Remove" aria-label="Remove watch"><i class="bi bi-trash fs-5"></i></button>
                            </form>
                        </div>
                        <div class="d-flex align-items-center justify-content-between pt-2" style="border-top: 1px solid #2A2A2E;">
                            <div class="small" style="color: #A0A0A5;">Quantity:</div>
                            <form action="/cart/update" method="POST" class="d-flex align-items-center gap-2">
                                <?= csrf_field() ?>
                                <input type="hidden" name="cart_item_id" value="<?= (int)$item['cart_item_id'] ?>">
                                <button type="submit" name="quantity" value="<?= max(0, $item['quantity'] - 1) ?>" class="btn btn-sm px-3 py-1 shadow-none" style="background: #1A1A1C; color: #F5F5F5; border: 1px solid #2A2A2E; border-radius: 4px; font-size: 14px; min-width: 36px; min-height: 36px;" aria-label="Decrease">&minus;</button>
                                <span class="fw-bold px-2" style="font-size: 14px; color: #F5F5F5;"><?= (int)$item['quantity'] ?></span>
                                <button type="submit" name="quantity" value="<?= $item['quantity'] + 1 ?>" class="btn btn-sm px-3 py-1 shadow-none" style="background: #1A1A1C; color: #F5F5F5; border: 1px solid #2A2A2E; border-radius: 4px; font-size: 14px; min-width: 36px; min-height: 36px;" aria-label="Increase">&plus;</button>
                            </form>
                            <div class="text-end">
                                <span class="small d-block" style="font-size: 10px; color: #A0A0A5;">Item Total</span>
                                <span class="fw-bold" style="color: #F5F5F5; font-size: 14px;"><?= format_currency($item['price'] * $item['quantity']) ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="mt-4">
                    <a href="/catalog" class="btn btn-outline-light btn-sm" style="border-color: #2A2A2E; border-radius: 6px;">
                        <i class="bi bi-arrow-left me-1"></i><?= __('continue_shopping') ?>
                    </a>
                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="col-lg-4">
                <div class="p-4 border card-luxury" style="background: #141416; border-color: #2A2A2E !important; border-radius: 10px;">
                    <h5 class="fw-normal mb-3" style="font-family: var(--font-display, 'DM Serif Display', Georgia, serif); color: #F5F5F5;">Order Summary</h5>

                    <!-- Coupon Code Input -->
                    <div class="mb-3 border-bottom pb-3" style="border-color: #2A2A2E !important;">
                        <?php if (!empty($cart['coupon_code'])): ?>
                            <div class="d-flex justify-content-between align-items-center p-2 rounded mb-2" style="background: rgba(197, 155, 63, 0.15); border: 1px solid rgba(197, 155, 63, 0.3); color: #C59B3F; border-radius: 6px;">
                                <span class="small fw-semibold"><i class="bi bi-tag-fill me-1"></i><?= e($cart['coupon_code']) ?> APPLIED</span>
                                <form action="/cart/remove-coupon" method="POST" class="m-0">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-link p-0 text-decoration-none small" style="color: #E63946;">Remove</button>
                                </form>
                            </div>
                        <?php else: ?>
                            <form action="/cart/apply-coupon" method="POST" class="d-flex gap-2">
                                <?= csrf_field() ?>
                                <label for="cartCouponInput" class="visually-hidden">Coupon Code</label>
                                <input type="text" id="cartCouponInput" name="coupon_code" class="form-control form-control-sm text-uppercase" placeholder="Enter coupon code" required style="height: 40px; border-radius: 6px; border: 1px solid #2A2A2E; background: #1A1A1C; color: #F5F5F5;">
                                <button type="submit" class="btn btn-welkar btn-sm" style="height: 40px; border-radius: 6px; font-size: 13px; font-weight: 600; white-space: nowrap;"><?= __('apply_coupon') ?></button>
                            </form>
                        <?php endif; ?>
                    </div>

                    <!-- Breakdown table -->
                    <div class="d-flex justify-content-between mb-2 small">
                        <span style="color: #A0A0A5;"><?= __('subtotal') ?>:</span>
                        <span class="fw-semibold" style="color: #F5F5F5;"><?= format_currency($cart['summary']['subtotal']) ?></span>
                    </div>

                    <?php if ((float)$cart['summary']['discount_amount'] > 0): ?>
                    <div class="d-flex justify-content-between mb-2 small" style="color: #C59B3F;">
                        <span>Coupon Discount:</span>
                        <span class="fw-semibold">&minus; <?= format_currency($cart['summary']['discount_amount']) ?></span>
                    </div>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between mb-2 small">
                        <span style="color: #A0A0A5;"><?= __('shipping') ?>:</span>
                        <span class="fw-semibold">
                            <?= ((float)$cart['summary']['shipping_fee'] == 0) ? '<span style="color: #2ECC71;">FREE</span>' : format_currency($cart['summary']['shipping_fee']) ?>
                        </span>
                    </div>

                    <div class="d-flex justify-content-between mb-3 small">
                        <span style="color: #A0A0A5;"><?= __('tax') ?> (18% GST):</span>
                        <span class="fw-semibold" style="color: #F5F5F5;"><?= format_currency($cart['summary']['tax_amount']) ?></span>
                    </div>

                    <div class="d-flex justify-content-between py-3 border-top border-bottom mb-4" style="border-color: #2A2A2E !important;">
                        <span class="fs-5 fw-semibold" style="color: #F5F5F5;"><?= __('total') ?>:</span>
                        <span class="fs-4 fw-bold" style="color: #F5F5F5; font-family: var(--font-ui, 'Inter', sans-serif);"><?= format_currency($cart['summary']['total_amount']) ?></span>
                    </div>

                    <a href="/checkout" class="btn btn-welkar w-100" style="height: 48px; border-radius: 6px; font-size: 14px; font-weight: 600; display: flex; align-items: center; justify-content: center;">
                        <?= __('checkout') ?> <i class="bi bi-arrow-right ms-2"></i>
                    </a>

                    <div class="text-center mt-3 small" style="font-size: 11px; color: #A0A0A5;">
                        <i class="bi bi-shield-lock me-1 text-success"></i><?= __('secure_checkout') ?> &bull; 256-Bit SSL Encrypted
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
