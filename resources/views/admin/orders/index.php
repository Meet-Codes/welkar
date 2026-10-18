<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Orders Management</h3>
        <p class="text-muted small m-0">Inspect customer orders, fulfillments, tax invoices, and cancellations.</p>
    </div>
</div>

<!-- Filters Bar -->
<div class="card p-3 border shadow-sm mb-4 bg-white" style="border-radius: 8px;">
    <form action="/admin/orders" method="GET" class="row g-2 align-items-center">
        <div class="col-md-4">
            <input type="text" name="q" class="form-control form-control-sm" placeholder="Search order #, phone, customer..." value="<?= e($search ?? '') ?>">
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select form-select-sm">
                <option value="">All Order Statuses</option>
                <?php foreach (['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'] as $st): ?>
                    <option value="<?= $st ?>" <?= ($currentStatus === $st) ? 'selected' : '' ?>><?= ucfirst($st) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-sm btn-dark px-3 fw-bold">Filter Orders</button>
            <a href="/admin/orders" class="btn btn-sm btn-outline-secondary">Reset</a>
        </div>
    </form>
</div>

<!-- Orders Table -->
<div class="card border shadow-sm p-4 bg-white" style="border-radius: 8px;">
    <?php if (empty($orders)): ?>
        <div class="p-5 text-center text-muted small bg-light rounded">No matching orders found.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle small mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Order Number</th>
                        <th>Placed At</th>
                        <th>Customer / Contact</th>
                        <th>Destination</th>
                        <th>Payment</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $ord): ?>
                    <tr>
                        <td class="fw-bold">
                            <a href="/admin/orders/<?= (int)$ord['id'] ?>" class="text-dark"><?= e($ord['order_number']) ?></a>
                        </td>
                        <td><?= date('d M Y, h:i A', strtotime($ord['created_at'])) ?></td>
                        <td>
                            <strong><?= e($ord['full_name'] ?? 'Guest') ?></strong><br>
                            <span class="text-muted" style="font-size: 11px;">+91 <?= e($ord['guest_phone']) ?></span>
                        </td>
                        <td><?= e($ord['city']) ?>, <?= e($ord['shipping_state']) ?></td>
                        <td>
                            <span class="badge bg-secondary-subtle text-secondary"><?= strtoupper(e($ord['payment_method'])) ?></span>
                            <div style="font-size: 11px;" class="text-muted"><?= e($ord['payment_status']) ?></div>
                        </td>
                        <td class="fw-bold fs-6"><?= format_currency($ord['total_amount']) ?></td>
                        <td>
                            <?php
                                $badgeClass = match ($ord['status']) {
                                    'delivered' => 'bg-success text-white',
                                    'shipped' => 'bg-info text-dark',
                                    'cancelled' => 'bg-danger text-white',
                                    default => 'bg-dark-subtle text-dark',
                                };
                            ?>
                            <span class="badge <?= $badgeClass ?> text-uppercase"><?= e($ord['status']) ?></span>
                        </td>
                        <td class="text-end">
                            <a href="/admin/orders/<?= (int)$ord['id'] ?>" class="btn btn-sm btn-dark px-3 fw-bold">Manage</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
