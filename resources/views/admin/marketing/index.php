<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Promotions, Coupons & Banners</h3>
        <p class="text-muted small m-0">Manage customer discounts, homepage hero banners, and abandoned cart recovery.</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Coupons Management -->
    <div class="col-lg-7">
        <div class="card p-4 border shadow-sm h-100 bg-white" style="border-radius: 8px;">
            <h5 class="fw-bold mb-3"><i class="bi bi-tag me-2"></i>Active Coupon Codes</h5>
            <div class="table-responsive">
                <table class="table align-middle small mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Discount</th>
                            <th>Min Spend</th>
                            <th>Redemptions</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($coupons as $c): ?>
                        <tr>
                            <td class="fw-bold"><code><?= e($c['code']) ?></code></td>
                            <td>
                                <?= ($c['discount_type'] === 'percentage') ? ((float)$c['discount_value'] . '%') : format_currency($c['discount_value']) ?>
                            </td>
                            <td><?= format_currency($c['min_order_value']) ?></td>
                            <td><span class="badge bg-secondary-subtle text-secondary"><?= (int)$c['usage_count'] ?> used</span></td>
                            <td><span class="badge bg-success">Active</span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create Coupon Form -->
    <div class="col-lg-5">
        <div class="card p-4 border shadow-sm bg-white" style="border-radius: 8px;">
            <h5 class="fw-bold mb-3">Create New Coupon</h5>
            <form action="/admin/marketing/coupons" method="POST">
                <?= csrf_field() ?>
                <div class="mb-2">
                    <label class="form-label small fw-bold">Coupon Code *</label>
                    <input type="text" name="code" class="form-control form-control-sm text-uppercase" placeholder="FESTIVE15" required>
                </div>
                <div class="row g-2 mb-2">
                    <div class="col-6">
                        <label class="form-label small fw-bold">Discount Type</label>
                        <select name="discount_type" class="form-select form-select-sm">
                            <option value="percentage">Percentage (%)</option>
                            <option value="fixed">Fixed Amount (₹)</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold">Value *</label>
                        <input type="number" step="0.01" name="discount_value" class="form-control form-control-sm" placeholder="10" required>
                    </div>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-bold">Min Spend (₹)</label>
                        <input type="number" step="0.01" name="min_order_value" class="form-control form-control-sm" placeholder="1999">
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold">Max Cap (₹)</label>
                        <input type="number" step="0.01" name="max_discount_value" class="form-control form-control-sm" placeholder="1000">
                    </div>
                </div>
                <button type="submit" class="btn btn-dark btn-sm w-100 fw-bold">Create Coupon</button>
            </form>
        </div>
    </div>
</div>

<!-- Abandoned Carts Section -->
<div class="card p-4 border shadow-sm bg-white mb-4" style="border-radius: 8px;">
    <h5 class="fw-bold mb-3"><i class="bi bi-cart-x me-2"></i>Abandoned Cart Recovery Pipeline</h5>
    <?php if (empty($abandonedCarts)): ?>
        <div class="p-3 bg-light rounded text-center text-muted small">No abandoned shopping sessions exceeding 1 hour.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle small mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Cart Session</th>
                        <th>Customer / Contact</th>
                        <th>Items Count</th>
                        <th>Estimated Value</th>
                        <th>Last Activity</th>
                        <th>Recovery Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($abandonedCarts as $ac): ?>
                    <tr>
                        <td><code><?= substr($ac['session_id'], 0, 14) ?>...</code></td>
                        <td><?= e($ac['customer_name'] ?? $ac['customer_phone'] ?? 'Guest Visitor') ?></td>
                        <td><?= (int)$ac['items_count'] ?> watches</td>
                        <td class="fw-bold"><?= format_currency($ac['cart_value']) ?></td>
                        <td><?= date('d M, h:i A', strtotime($ac['updated_at'])) ?></td>
                        <td>
                            <button class="btn btn-sm btn-outline-dark" onclick="alert('Recovery email adapter ready. Connect Gmail SMTP to deliver.')">
                                <i class="bi bi-envelope me-1"></i> Send Recovery Alert
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
