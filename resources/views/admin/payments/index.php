<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Payments &amp; Gateway Transactions</h3>
        <p class="text-muted small m-0 mt-1">Real-time ledger of Razorpay (UPI, Cards, NetBanking, Wallets) and Cash on Delivery payments across all orders.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="/admin/payments" class="btn btn-outline-dark btn-sm"><i class="bi bi-arrow-clockwise me-1"></i>Refresh</a>
    </div>
</div>

<!-- KPI Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card-kpi p-3 border-success">
            <div class="kpi-label text-success">Total Captured Volume</div>
            <div class="kpi-val text-success"><?= format_currency($kpis['total_volume']) ?></div>
            <span class="text-muted small" style="font-size: 11px;"><?= (int)$kpis['captured_count'] ?> Successful Payments</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-kpi p-3 border-warning">
            <div class="kpi-label text-warning">Pending / Authorized</div>
            <div class="kpi-val text-warning"><?= (int)$kpis['pending_count'] ?></div>
            <span class="text-muted small" style="font-size: 11px;">Awaiting capture or COD delivery</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-kpi p-3 border-danger">
            <div class="kpi-label text-danger">Refunded Volume</div>
            <div class="kpi-val text-danger"><?= format_currency($kpis['refunded_volume']) ?></div>
            <span class="text-muted small" style="font-size: 11px;">Processed refunds</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-kpi p-3">
            <div class="kpi-label">Active Gateway</div>
            <div class="kpi-val fs-5">Razorpay &amp; COD</div>
            <span class="text-muted small" style="font-size: 11px;">UPI, Cards, NetBanking, COD</span>
        </div>
    </div>
</div>

<!-- Filters & Search -->
<div class="card p-3 border shadow-sm mb-4 bg-white" style="border-radius: 8px;">
    <form method="GET" action="/admin/payments" class="row g-2 align-items-center">
        <div class="col-md-3">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                <input type="text" name="q" class="form-control" placeholder="Order #, txn ID, customer, phone..." value="<?= e($search ?? '') ?>">
            </div>
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="captured" <?= ($currentStatus === 'captured') ? 'selected' : '' ?>>Captured / Paid</option>
                <option value="authorized" <?= ($currentStatus === 'authorized') ? 'selected' : '' ?>>Authorized</option>
                <option value="pending" <?= ($currentStatus === 'pending') ? 'selected' : '' ?>>Pending</option>
                <option value="refunded" <?= ($currentStatus === 'refunded') ? 'selected' : '' ?>>Refunded</option>
                <option value="failed" <?= ($currentStatus === 'failed') ? 'selected' : '' ?>>Failed</option>
            </select>
        </div>
        <div class="col-md-3">
            <select name="method" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All Methods</option>
                <option value="upi" <?= ($currentMethod === 'upi') ? 'selected' : '' ?>>UPI (GPay / PhonePe / Paytm)</option>
                <option value="card" <?= ($currentMethod === 'card') ? 'selected' : '' ?>>Debit / Credit Card</option>
                <option value="netbanking" <?= ($currentMethod === 'netbanking') ? 'selected' : '' ?>>Net Banking</option>
                <option value="wallet" <?= ($currentMethod === 'wallet') ? 'selected' : '' ?>>Wallets &amp; Others</option>
                <option value="cod" <?= ($currentMethod === 'cod') ? 'selected' : '' ?>>Cash on Delivery (COD)</option>
                <option value="razorpay" <?= ($currentMethod === 'razorpay') ? 'selected' : '' ?>>Razorpay Standard</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="gateway" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All Gateways</option>
                <option value="razorpay" <?= ($currentGateway === 'razorpay') ? 'selected' : '' ?>>Razorpay</option>
                <option value="cod" <?= ($currentGateway === 'cod') ? 'selected' : '' ?>>Cash on Delivery</option>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-sm btn-dark w-100 fw-bold">Filter</button>
            <?php if (!empty($search) || !empty($currentStatus) || !empty($currentMethod) || !empty($currentGateway)): ?>
                <a href="/admin/payments" class="btn btn-sm btn-outline-secondary">Reset</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Transactions Table -->
<div class="card border shadow-sm bg-white" style="border-radius: 8px;">
    <div class="table-responsive">
        <table class="table table-hover align-middle small mb-0">
            <thead class="table-light">
                <tr>
                    <th>Txn ID / Ref</th>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Method</th>
                    <th>Gateway</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($payments)): ?>
                <tr>
                    <td colspan="9" class="text-center py-4 text-muted">
                        <i class="bi bi-credit-card fs-2 d-block mb-1"></i>
                        No payment transactions found matching criteria.
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($payments as $p): ?>
                    <tr>
                        <td>
                            <?php if (!empty($p['transaction_id'])): ?>
                                <code><?= e($p['transaction_id']) ?></code>
                            <?php elseif (!empty($p['gateway_order_id'])): ?>
                                <span class="text-muted font-monospace" style="font-size: 11px;"><?= e($p['gateway_order_id']) ?></span>
                            <?php else: ?>
                                <span class="text-muted">#<?= (int)$p['id'] ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="/admin/orders/<?= (int)$p['order_id'] ?>" class="fw-bold text-dark text-decoration-none">
                                <?= e($p['order_number']) ?>
                            </a>
                        </td>
                        <td>
                            <div class="fw-semibold"><?= e($p['full_name'] ?: 'Customer') ?></div>
                            <div class="text-muted" style="font-size: 11px;">+91 <?= e($p['phone'] ?: '—') ?></div>
                        </td>
                        <td>
                            <?php
                            $methodBadges = [
                                'upi' => ['badge bg-primary', 'UPI'],
                                'card' => ['badge bg-success', 'Card'],
                                'netbanking' => ['badge bg-info text-dark', 'Net Banking'],
                                'wallet' => ['badge bg-warning text-dark', 'Wallet / EMI'],
                                'cod' => ['badge bg-secondary', 'COD'],
                                'razorpay' => ['badge bg-dark', 'Razorpay'],
                            ];
                            $mb = $methodBadges[$p['payment_method']] ?? ['badge bg-light text-dark border', strtoupper(e($p['payment_method']))];
                            ?>
                            <span class="<?= $mb[0] ?>" style="font-size: 10.5px;"><?= $mb[1] ?></span>
                        </td>
                        <td>
                            <span class="text-uppercase fw-semibold" style="font-size: 11px;"><?= e($p['gateway']) ?></span>
                        </td>
                        <td>
                            <span class="fw-bold fs-6 text-dark"><?= format_currency($p['amount']) ?></span>
                        </td>
                        <td>
                            <?php
                            $sClasses = [
                                'captured' => 'badge bg-success',
                                'authorized' => 'badge bg-info text-dark',
                                'pending' => 'badge bg-warning text-dark',
                                'refunded' => 'badge bg-danger',
                                'failed' => 'badge bg-dark',
                            ];
                            ?>
                            <span class="<?= $sClasses[$p['status']] ?? 'badge bg-secondary' ?> text-uppercase" style="font-size: 10.5px;">
                                <?= e($p['status']) ?>
                            </span>
                        </td>
                        <td class="text-muted" style="font-size: 11px;">
                            <?= date('d M Y, h:i A', strtotime($p['created_at'])) ?>
                        </td>
                        <td class="text-end">
                            <a href="/admin/payments/<?= (int)$p['id'] ?>" class="btn btn-sm btn-outline-dark fw-semibold" style="font-size: 11px;">
                                <i class="bi bi-eye me-1"></i> Details
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
