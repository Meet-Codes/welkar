<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="d-flex align-items-center gap-2">
            <h3 class="fw-bold mb-0">Order <?= e($order['order_number']) ?></h3>
            <span class="badge bg-dark text-uppercase"><?= e($order['status']) ?></span>
        </div>
        <p class="text-muted small m-0 mt-1">Placed on <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?> &bull; Payment: <?= strtoupper(e($order['payment_method'])) ?> (<?= e($order['payment_status']) ?>)</p>
    </div>

    <div class="d-flex gap-2">
        <a href="/admin/orders/<?= (int)$order['id'] ?>/invoice" target="_blank" class="btn btn-outline-dark btn-sm fw-bold">
            <i class="bi bi-printer me-1"></i> Print Tax Invoice
        </a>
        <a href="/admin/orders" class="btn btn-secondary btn-sm">Back to Orders</a>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Items & Shipping -->
    <div class="col-lg-8">
        <!-- Purchased Items -->
        <div class="card p-4 border shadow-sm mb-4 bg-white" style="border-radius: 8px;">
            <h5 class="fw-bold mb-3">Order Items Snapshot</h5>
            <div class="table-responsive">
                <table class="table align-middle small mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Watch</th>
                            <th>SKU</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Unit Price</th>
                            <th class="text-end">GST</th>
                            <th class="text-end">Line Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                        <tr>
                            <td>
                                <strong><?= e($item['product_name']) ?></strong><br>
                                <span class="text-muted" style="font-size: 11px;"><?= e($item['variant_name']) ?></span>
                            </td>
                            <td><code><?= e($item['sku']) ?></code></td>
                            <td class="text-center"><?= (int)$item['quantity'] ?></td>
                            <td class="text-end"><?= format_currency($item['unit_price']) ?></td>
                            <td class="text-end"><?= format_currency($item['tax_amount']) ?> (<?= (float)$item['tax_rate'] ?>%)</td>
                            <td class="text-end fw-bold"><?= format_currency($item['total']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Totals breakdown -->
            <div class="border-top mt-3 pt-3 d-flex justify-content-end">
                <div style="width: 280px;" class="small">
                    <div class="d-flex justify-content-between mb-1 text-muted">
                        <span>Subtotal:</span>
                        <span><?= format_currency($order['subtotal']) ?></span>
                    </div>
                    <?php if ((float)$order['discount_amount'] > 0): ?>
                    <div class="d-flex justify-content-between mb-1 text-success">
                        <span>Discount:</span>
                        <span>- <?= format_currency($order['discount_amount']) ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="d-flex justify-content-between mb-1 text-muted">
                        <span>Shipping Fee:</span>
                        <span><?= format_currency($order['shipping_fee']) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-muted">
                        <span>GST (18%):</span>
                        <span><?= format_currency($order['tax_amount']) ?></span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold fs-6 border-top pt-2">
                        <span>Grand Total:</span>
                        <span><?= format_currency($order['total_amount']) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Lifecycle Transitions -->
        <div class="card p-4 border shadow-sm mb-4 bg-white" style="border-radius: 8px;">
            <h5 class="fw-bold mb-3"><i class="bi bi-arrow-repeat me-2"></i>Update Order State</h5>
            <form action="/admin/orders/<?= (int)$order['id'] ?>/status" method="POST" class="row g-2 align-items-center">
                <?= csrf_field() ?>
                <div class="col-md-5">
                    <select name="status" class="form-select form-select-sm" required>
                        <option value="pending" <?= ($order['status'] === 'pending') ? 'selected' : '' ?>>Pending Payment</option>
                        <option value="confirmed" <?= ($order['status'] === 'confirmed') ? 'selected' : '' ?>>Confirmed</option>
                        <option value="processing" <?= ($order['status'] === 'processing') ? 'selected' : '' ?>>Processing / Packing</option>
                        <option value="shipped" <?= ($order['status'] === 'shipped') ? 'selected' : '' ?>>Shipped</option>
                        <option value="delivered" <?= ($order['status'] === 'delivered') ? 'selected' : '' ?>>Delivered</option>
                        <option value="cancelled" <?= ($order['status'] === 'cancelled') ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <input type="text" name="comment" class="form-control form-control-sm" placeholder="Internal operational comment">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-sm btn-dark w-100 fw-bold">Update</button>
                </div>
            </form>
        </div>

        <!-- Status History Audit Trail -->
        <div class="card p-4 border shadow-sm bg-white" style="border-radius: 8px;">
            <h6 class="fw-bold mb-3">Status History & Audit Trail</h6>
            <div class="d-flex flex-column gap-2 small">
                <?php foreach ($history as $h): ?>
                <div class="p-2 border rounded bg-light d-flex justify-content-between align-items-center">
                    <div>
                        <strong class="text-uppercase"><?= e($h['new_status']) ?></strong>: <?= e($h['comment'] ?: 'No comment') ?>
                        <div class="text-muted" style="font-size: 11px;">By <?= e($h['changed_by']) ?></div>
                    </div>
                    <span class="text-muted" style="font-size: 11px;"><?= date('d M Y, h:i A', strtotime($h['created_at'])) ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Right Column: Shipping & Tracking -->
    <div class="col-lg-4">
        <!-- Payment & Gateway Information Card -->
        <div class="card p-4 border shadow-sm mb-4 bg-white border-warning" style="border-radius: 8px;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-credit-card-2-front me-2 text-warning"></i>Payment Details</h5>
                <?php
                $payStatusClasses = [
                    'captured' => 'badge bg-success',
                    'authorized' => 'badge bg-info text-dark',
                    'pending' => 'badge bg-warning text-dark',
                    'unpaid' => 'badge bg-secondary',
                    'refunded' => 'badge bg-danger',
                    'failed' => 'badge bg-dark',
                ];
                $ps = strtolower($order['payment_status'] ?? 'pending');
                ?>
                <span class="<?= $payStatusClasses[$ps] ?? 'badge bg-secondary' ?> text-uppercase"><?= e($order['payment_status']) ?></span>
            </div>

            <div class="small mb-3 leading-relaxed">
                <div><strong>Method:</strong> <span class="badge bg-light text-dark border text-uppercase"><?= e($order['payment_method']) ?></span></div>
                <div><strong>Amount:</strong> <span class="fs-6 fw-bold text-dark"><?= format_currency($order['total_amount']) ?></span></div>
                <?php 
                $latestPayment = !empty($payments) ? $payments[0] : null;
                if ($latestPayment): 
                ?>
                    <div><strong>Gateway:</strong> <?= ucfirst(e($latestPayment['gateway'])) ?></div>
                    <div><strong>Transaction ID:</strong> <code><?= e($latestPayment['transaction_id'] ?: '—') ?></code></div>
                    <div><strong>Gateway Order:</strong> <code><?= e($latestPayment['gateway_order_id'] ?: '—') ?></code></div>
                    <div><strong>Timestamp:</strong> <?= date('d M Y, h:i A', strtotime($latestPayment['created_at'])) ?></div>
                <?php else: ?>
                    <div class="text-muted">No external gateway transaction recorded yet.</div>
                <?php endif; ?>
            </div>

            <!-- Payment Status Update Form -->
            <div class="border-top pt-3">
                <h6 class="fw-bold small mb-2"><i class="bi bi-pencil-square me-1"></i>Update Payment Status</h6>
                <form action="/admin/orders/<?= (int)$order['id'] ?>/payment" method="POST">
                    <?= csrf_field() ?>
                    <div class="mb-2">
                        <select name="payment_status" class="form-select form-select-sm" required>
                            <option value="captured" <?= ($order['payment_status'] === 'captured') ? 'selected' : '' ?>>Captured / Paid</option>
                            <option value="authorized" <?= ($order['payment_status'] === 'authorized') ? 'selected' : '' ?>>Authorized</option>
                            <option value="unpaid" <?= ($order['payment_status'] === 'unpaid') ? 'selected' : '' ?>>Unpaid / Pending</option>
                            <option value="refunded" <?= ($order['payment_status'] === 'refunded') ? 'selected' : '' ?>>Refunded</option>
                            <option value="failed" <?= ($order['payment_status'] === 'failed') ? 'selected' : '' ?>>Failed</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <input type="text" name="transaction_id" class="form-control form-control-sm" placeholder="UTR / Razorpay Payment ID" value="<?= e($latestPayment['transaction_id'] ?? '') ?>">
                    </div>
                    <div class="mb-2">
                        <input type="text" name="payment_notes" class="form-control form-control-sm" placeholder="Internal verification notes">
                    </div>
                    <button type="submit" class="btn btn-sm btn-dark w-100 fw-bold">Update Payment</button>
                </form>
            </div>
        </div>

        <!-- Customer Delivery Address -->
        <div class="card p-4 border shadow-sm mb-4 bg-white" style="border-radius: 8px;">
            <h5 class="fw-bold mb-3"><i class="bi bi-geo-alt me-2"></i>Delivery Address</h5>
            <?php if ($address): ?>
                <div class="small leading-relaxed">
                    <strong><?= e($address['full_name']) ?></strong><br>
                    <?= e($address['address_line1']) ?><br>
                    <?php if (!empty($address['address_line2'])): ?>
                        <?= e($address['address_line2']) ?><br>
                    <?php endif; ?>
                    <?= e($address['city']) ?>, <?= e($address['state']) ?> - <?= e($address['pincode']) ?><br>
                    Phone: <strong>+91 <?= e($address['phone']) ?></strong>
                </div>
            <?php endif; ?>
        </div>

        <!-- Add Shipment Courier Tracking -->
        <div class="card p-4 border shadow-sm mb-4 bg-white" style="border-radius: 8px;">
            <h5 class="fw-bold mb-3"><i class="bi bi-truck me-2"></i>Dispatch Tracking</h5>
            <form action="/admin/orders/<?= (int)$order['id'] ?>/tracking" method="POST">
                <?= csrf_field() ?>
                <div class="mb-2">
                    <label class="form-label small fw-bold">Courier Partner</label>
                    <input type="text" name="courier_name" class="form-control form-control-sm" placeholder="Delhivery / Blue Dart / DTDC" required value="Delhivery">
                </div>
                <div class="mb-2">
                    <label class="form-label small fw-bold">Tracking / AWB Number</label>
                    <input type="text" name="tracking_number" class="form-control form-control-sm" placeholder="e.g. DEL123456789" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Courier URL (Optional)</label>
                    <input type="url" name="tracking_url" class="form-control form-control-sm" placeholder="https://track...">
                </div>
                <button type="submit" class="btn btn-sm btn-dark w-100 fw-bold">
                    <i class="bi bi-check2-circle me-1"></i> Attach Tracking & Mark Shipped
                </button>
            </form>
        </div>
    </div>
</div>
