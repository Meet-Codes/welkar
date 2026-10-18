<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="d-flex align-items-center gap-2">
            <h3 class="fw-bold mb-0">Payment Transaction #<?= (int)$payment['id'] ?></h3>
            <?php
            $sClasses = [
                'captured' => 'badge bg-success',
                'authorized' => 'badge bg-info text-dark',
                'pending' => 'badge bg-warning text-dark',
                'refunded' => 'badge bg-danger',
                'failed' => 'badge bg-dark',
            ];
            ?>
            <span class="<?= $sClasses[$payment['status']] ?? 'badge bg-secondary' ?> text-uppercase"><?= e($payment['status']) ?></span>
            <span class="badge bg-light text-dark border text-uppercase"><?= e($payment['gateway']) ?> (<?= e($payment['payment_method']) ?>)</span>
        </div>
        <p class="text-muted small m-0 mt-1">Order #<?= e($payment['order_number']) ?> &bull; Created on <?= date('d M Y, h:i A', strtotime($payment['created_at'])) ?></p>
    </div>

    <div class="d-flex gap-2">
        <a href="/admin/orders/<?= (int)$payment['order_id'] ?>" class="btn btn-outline-dark btn-sm">
            <i class="bi bi-box-seam me-1"></i> View Order
        </a>
        <a href="/admin/payments" class="btn btn-secondary btn-sm">Back to Payments</a>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Transaction Details & Gateway Payload -->
    <div class="col-lg-8">
        <!-- Summary Card -->
        <div class="card p-4 border shadow-sm mb-4 bg-white" style="border-radius: 8px;">
            <h5 class="fw-bold mb-3"><i class="bi bi-receipt me-2"></i>Transaction Summary</h5>
            <div class="row g-3 small">
                <div class="col-md-6">
                    <span class="text-muted d-block" style="font-size: 11px;">AMOUNT &amp; CURRENCY</span>
                    <span class="fs-4 fw-bold text-dark"><?= format_currency($payment['amount']) ?></span> <span class="text-muted"><?= e($payment['currency']) ?></span>
                </div>
                <div class="col-md-6">
                    <span class="text-muted d-block" style="font-size: 11px;">GATEWAY &amp; METHOD</span>
                    <span class="fw-bold text-dark text-uppercase"><?= e($payment['gateway']) ?></span> &bull; <span class="badge bg-light text-dark border text-uppercase"><?= e($payment['payment_method']) ?></span>
                </div>
                <div class="col-md-6">
                    <span class="text-muted d-block" style="font-size: 11px;">TRANSACTION ID (UTR / RZP ID)</span>
                    <code><?= e($payment['transaction_id'] ?: '— (Pending Capture)') ?></code>
                </div>
                <div class="col-md-6">
                    <span class="text-muted d-block" style="font-size: 11px;">GATEWAY ORDER ID</span>
                    <code><?= e($payment['gateway_order_id'] ?: '—') ?></code>
                </div>
                <div class="col-md-6">
                    <span class="text-muted d-block" style="font-size: 11px;">CREATED AT</span>
                    <span><?= date('d M Y, h:i:s A', strtotime($payment['created_at'])) ?></span>
                </div>
                <div class="col-md-6">
                    <span class="text-muted d-block" style="font-size: 11px;">LAST UPDATED</span>
                    <span><?= !empty($payment['updated_at']) ? date('d M Y, h:i:s A', strtotime($payment['updated_at'])) : '—' ?></span>
                </div>
            </div>
        </div>

        <!-- Raw Gateway Payload -->
        <div class="card p-4 border shadow-sm mb-4 bg-white" style="border-radius: 8px;">
            <h6 class="fw-bold mb-2"><i class="bi bi-code-slash me-2"></i>Gateway Cryptographic Payload</h6>
            <?php if (!empty($payment['gateway_payload'])): ?>
                <?php 
                $decoded = json_decode($payment['gateway_payload'], true);
                $pretty = json_encode($decoded ?: $payment['gateway_payload'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                ?>
                <pre class="bg-light p-3 border rounded small m-0" style="max-height: 250px; overflow-y: auto;"><code><?= htmlspecialchars($pretty) ?></code></pre>
            <?php else: ?>
                <div class="text-muted small py-2">No raw payload stored for this transaction.</div>
            <?php endif; ?>
        </div>

        <!-- Payment Events Log -->
        <div class="card p-4 border shadow-sm bg-white" style="border-radius: 8px;">
            <h6 class="fw-bold mb-3"><i class="bi bi-clock-history me-2"></i>Payment Lifecycle Events</h6>
            <div class="d-flex flex-column gap-2 small">
                <?php if (empty($events)): ?>
                    <span class="text-muted">No audit events recorded for this payment.</span>
                <?php else: ?>
                    <?php foreach ($events as $ev): ?>
                    <div class="p-2 border rounded bg-light d-flex justify-content-between align-items-center">
                        <div>
                            <strong class="font-monospace text-dark"><?= e($ev['event_name']) ?></strong>
                            <?php if (!empty($ev['payload'])): ?>
                                <div class="text-muted" style="font-size: 11px;"><?= e($ev['payload']) ?></div>
                            <?php endif; ?>
                        </div>
                        <span class="text-muted" style="font-size: 11px;"><?= date('d M Y, h:i A', strtotime($ev['created_at'])) ?></span>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Right Column: Status Update & Customer Profile -->
    <div class="col-lg-4">
        <!-- Status Update Card -->
        <div class="card p-4 border shadow-sm mb-4 bg-white border-warning" style="border-radius: 8px;">
            <h5 class="fw-bold mb-3"><i class="bi bi-sliders me-2 text-warning"></i>Update Payment State</h5>
            <form action="/admin/payments/<?= (int)$payment['id'] ?>/status" method="POST">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Payment Status</label>
                    <select name="status" class="form-select form-select-sm" required>
                        <option value="captured" <?= ($payment['status'] === 'captured') ? 'selected' : '' ?>>Captured / Paid</option>
                        <option value="authorized" <?= ($payment['status'] === 'authorized') ? 'selected' : '' ?>>Authorized</option>
                        <option value="pending" <?= ($payment['status'] === 'pending') ? 'selected' : '' ?>>Pending</option>
                        <option value="refunded" <?= ($payment['status'] === 'refunded') ? 'selected' : '' ?>>Refunded</option>
                        <option value="failed" <?= ($payment['status'] === 'failed') ? 'selected' : '' ?>>Failed</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Transaction ID / UTR</label>
                    <input type="text" name="transaction_id" class="form-control form-control-sm" placeholder="UTR or Razorpay Payment ID" value="<?= e($payment['transaction_id'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Audit / Verification Note</label>
                    <input type="text" name="notes" class="form-control form-control-sm" placeholder="e.g. Bank statement verified">
                </div>
                <button type="submit" class="btn btn-sm btn-dark w-100 fw-bold">Update &amp; Sync Order</button>
            </form>
        </div>

        <!-- Customer & Order Context -->
        <div class="card p-4 border shadow-sm mb-4 bg-white" style="border-radius: 8px;">
            <h6 class="fw-bold mb-3"><i class="bi bi-person me-2"></i>Customer &amp; Shipping</h6>
            <div class="small leading-relaxed">
                <div><strong>Customer:</strong> <?= e($payment['full_name'] ?: 'Customer') ?></div>
                <div><strong>Phone:</strong> <a href="tel:<?= e($payment['phone']) ?>" class="text-dark fw-bold">+91 <?= e($payment['phone']) ?></a></div>
                <?php if (!empty($payment['email'])): ?>
                    <div><strong>Email:</strong> <a href="mailto:<?= e($payment['email']) ?>"><?= e($payment['email']) ?></a></div>
                <?php endif; ?>
                <div><strong>Delivery Address:</strong><br>
                    <?= e($payment['address_line1']) ?>, <?= e($payment['city']) ?>, <?= e($payment['state']) ?> - <?= e($payment['pincode']) ?>
                </div>
                <div class="border-top mt-2 pt-2">
                    <div><strong>Order Number:</strong> <a href="/admin/orders/<?= (int)$payment['order_id'] ?>" class="fw-bold text-dark">#<?= e($payment['order_number']) ?></a></div>
                    <div><strong>Order Status:</strong> <span class="badge bg-secondary"><?= e($payment['order_status']) ?></span></div>
                </div>
            </div>
        </div>
    </div>
</div>
