<div class="container py-5">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-3 border-bottom" style="border-color: var(--color-border-default, #DDD9D0) !important;">
        <div>
            <span class="text-uppercase fw-bold" style="font-size: 11px; letter-spacing: 2px; color: var(--color-accent-700, #856744);">CUSTOMER PORTAL</span>
            <h1 class="h2 fw-bold text-dark mt-1 mb-1" style="font-family: var(--font-display, 'DM Serif Display', serif); letter-spacing: -0.5px;">Customer Dashboard</h1>
            <div class="text-muted small" style="font-family: var(--font-ui, 'Inter', sans-serif);">
                Welcome back, <strong class="text-dark"><?= e($user['full_name']) ?></strong> &bull; Verified Phone: +91 <?= e($user['phone']) ?>
            </div>
        </div>
        <a href="/account/logout" class="btn btn-outline-danger btn-sm" style="border-radius: 6px; font-weight: 500; font-family: var(--font-ui, 'Inter', sans-serif);">
            <i class="bi bi-box-arrow-right me-1"></i>Sign Out
        </a>
    </div>

    <div class="row g-4">
        <!-- Orders History -->
        <div class="col-lg-8">
            <div class="card p-4 bg-white border" style="border-radius: 10px; border-color: var(--color-border-default, #DDD9D0) !important; box-shadow: 0 4px 16px rgba(0,0,0,0.03);">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h5 class="fw-bold mb-0" style="font-family: var(--font-display, 'DM Serif Display', serif); font-size: 1.25rem;">
                        <i class="bi bi-box-seam me-2" style="color: var(--color-accent-700, #856744);"></i>My Orders History
                    </h5>
                    <span class="badge" style="background: var(--color-surface-100, #F0EDE6); color: var(--color-brand-950, #202522); border-radius: 6px; font-weight: 600; font-size: 11px; border: 1px solid var(--color-border-default, #DDD9D0);">
                        <?= count($orders) ?> <?= count($orders) === 1 ? 'Order' : 'Orders' ?>
                    </span>
                </div>

                <?php if (empty($orders)): ?>
                    <div class="p-5 text-center" style="background: var(--color-surface-50, #F8F6F1); border-radius: 8px; border: 1px dashed var(--color-border-default, #DDD9D0);">
                        <i class="bi bi-watch fs-1 d-block mb-3" style="color: var(--color-accent-700, #856744);"></i>
                        <h6 class="fw-bold text-dark mb-2" style="font-family: var(--font-ui, 'Inter', sans-serif);">No Orders Placed Yet</h6>
                        <p class="text-muted small mx-auto mb-3" style="max-width: 360px; font-family: var(--font-ui, 'Inter', sans-serif);">Explore our catalog to find your signature chronograph or automatic watch.</p>
                        <a href="/catalog" class="btn btn-welkar px-4 py-2" style="border-radius: 6px; font-weight: 600;">Start Exploring</a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table align-middle small mb-0" style="font-family: var(--font-ui, 'Inter', sans-serif);">
                            <thead>
                                <tr style="background: var(--color-surface-50, #F8F6F1); color: var(--color-text-secondary, #626760); font-size: 11.5px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid var(--color-border-default, #DDD9D0);">
                                    <th class="py-3 px-3">Order #</th>
                                    <th class="py-3 px-2">Date</th>
                                    <th class="py-3 px-2">Total</th>
                                    <th class="py-3 px-2">Status</th>
                                    <th class="py-3 px-3 text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $ord): ?>
                                <tr style="border-bottom: 1px solid var(--color-border-default, #DDD9D0);">
                                    <td class="py-3 px-3">
                                        <span class="fw-bold text-dark"><?= e($ord['order_number']) ?></span><br>
                                        <span class="text-muted" style="font-size: 11px;"><?= strtoupper(e($ord['payment_method'])) ?></span>
                                    </td>
                                    <td class="py-3 px-2 text-muted"><?= date('d M Y', strtotime($ord['created_at'])) ?></td>
                                    <td class="py-3 px-2 fw-bold" style="color: var(--color-brand-950, #202522);"><?= format_currency($ord['total_amount']) ?></td>
                                    <td class="py-3 px-2">
                                        <?php
                                            $badgeBg = 'var(--color-surface-100, #F0EDE6)';
                                            $badgeColor = 'var(--color-brand-950, #202522)';
                                            if ($ord['status'] === 'delivered') {
                                                $badgeBg = 'rgba(46, 125, 50, 0.1)';
                                                $badgeColor = '#2E7D32';
                                            } elseif ($ord['status'] === 'cancelled') {
                                                $badgeBg = 'rgba(198, 40, 40, 0.1)';
                                                $badgeColor = '#C62828';
                                            }
                                        ?>
                                        <span class="badge text-uppercase" style="background: <?= $badgeBg ?>; color: <?= $badgeColor ?>; border-radius: 4px; font-size: 10.5px; font-weight: 600; padding: 4px 8px;">
                                            <?= e($ord['status']) ?>
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 text-end">
                                        <div class="d-inline-flex gap-2">
                                            <a href="/account/invoice/<?= e($ord['order_number']) ?>" target="_blank" class="btn btn-sm btn-welkar-outline" style="border-radius: 6px; padding: 4px 8px;" title="View Invoice">
                                                <i class="bi bi-file-earmark-pdf me-1"></i>Invoice
                                            </a>
                                            <?php if (in_array($ord['status'], ['pending', 'confirmed', 'processing'])): ?>
                                            <form action="/account/orders/cancel" method="POST" onsubmit="return confirm('Cancel this order?');" class="d-inline">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="order_id" value="<?= (int)$ord['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius: 6px; padding: 4px 8px;" title="Cancel Order">
                                                    Cancel
                                                </button>
                                            </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Account Info -->
        <div class="col-lg-4">
            <div class="card p-4 bg-white border mb-4" style="border-radius: 10px; border-color: var(--color-border-default, #DDD9D0) !important; box-shadow: 0 4px 16px rgba(0,0,0,0.03);">
                <h5 class="fw-bold mb-3" style="font-family: var(--font-display, 'DM Serif Display', serif); font-size: 1.25rem;">
                    <i class="bi bi-shield-check me-2" style="color: var(--color-accent-700, #856744);"></i>Account Security
                </h5>
                <p class="small text-muted mb-2" style="font-family: var(--font-ui, 'Inter', sans-serif);">Verified Mobile: <strong class="text-dark">+91 <?= e($user['phone']) ?></strong></p>
                <p class="small text-muted mb-0" style="font-family: var(--font-ui, 'Inter', sans-serif);">
                    Status: <span class="badge" style="background: rgba(46, 125, 50, 0.1); color: #2E7D32; border-radius: 4px; font-weight: 600;">Active & Verified</span>
                </p>
            </div>

            <div class="card p-4 bg-white border" style="border-radius: 10px; border-color: var(--color-border-default, #DDD9D0) !important; box-shadow: 0 4px 16px rgba(0,0,0,0.03);">
                <h5 class="fw-bold mb-3" style="font-family: var(--font-display, 'DM Serif Display', serif); font-size: 1.25rem;">
                    <i class="bi bi-geo me-2" style="color: var(--color-accent-700, #856744);"></i>Saved Addresses
                </h5>
                <?php if (empty($addresses)): ?>
                    <p class="small text-muted m-0" style="font-family: var(--font-ui, 'Inter', sans-serif); line-height: 1.6;">No saved address yet. Your delivery address will be securely remembered during checkout.</p>
                <?php else: ?>
                    <?php foreach ($addresses as $addr): ?>
                    <div class="p-3 mb-2" style="background: var(--color-surface-50, #F8F6F1); border-radius: 6px; border: 1px solid var(--color-border-default, #DDD9D0); font-family: var(--font-ui, 'Inter', sans-serif); font-size: 12.5px;">
                        <strong class="text-dark"><?= e($addr['full_name']) ?></strong><br>
                        <span class="text-muted"><?= e($addr['address_line1']) ?>, <?= e($addr['city']) ?> - <?= e($addr['pincode']) ?></span>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
