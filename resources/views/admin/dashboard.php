<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Executive Dashboard</h3>
        <p class="text-muted small m-0">Authoritative store performance, inventory levels, and financial records.</p>
    </div>

    <!-- Date range selector -->
    <div class="btn-group btn-group-sm">
        <a href="/admin?range=today" class="btn <?= ($range === 'today') ? 'btn-dark' : 'btn-outline-secondary' ?>">Today</a>
        <a href="/admin?range=7d" class="btn <?= ($range === '7d') ? 'btn-dark' : 'btn-outline-secondary' ?>">Last 7 Days</a>
        <a href="/admin?range=30d" class="btn <?= ($range === '30d') ? 'btn-dark' : 'btn-outline-secondary' ?>">Last 30 Days</a>
    </div>
</div>

<!-- Primary KPI Cards Row -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card-kpi">
            <div class="kpi-label">Gross Sales</div>
            <div class="kpi-val"><?= format_currency($kpis['gross_sales']) ?></div>
            <div class="text-muted small mt-1">Discounts: <?= format_currency($kpis['total_discounts']) ?></div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card-kpi">
            <div class="kpi-label">Net Realized Revenue</div>
            <div class="kpi-val text-success"><?= format_currency($kpis['net_sales']) ?></div>
            <div class="text-muted small mt-1">Net of cancellations</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card-kpi">
            <div class="kpi-label">Confirmed Orders</div>
            <div class="kpi-val"><?= (int)$kpis['total_orders'] ?></div>
            <div class="text-muted small mt-1">AOV: <?= format_currency($kpis['aov']) ?></div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card-kpi">
            <div class="kpi-label">GST Liability (18%)</div>
            <div class="kpi-val text-primary"><?= format_currency($kpis['total_gst']) ?></div>
            <div class="text-muted small mt-1">Shipping: <?= format_currency($kpis['total_shipping']) ?></div>
        </div>
    </div>
</div>

<!-- Secondary Funnel Cards Row -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card-kpi bg-light">
            <div class="kpi-label">Unique Sessions</div>
            <div class="fs-4 fw-bold text-dark"><?= (int)$kpis['sessions'] ?></div>
            <div class="text-muted small">Total Pageviews: <?= (int)$kpis['pageviews'] ?></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-kpi bg-light">
            <div class="kpi-label">Cart Additions</div>
            <div class="fs-4 fw-bold text-dark"><?= (int)$kpis['add_to_cart_count'] ?></div>
            <div class="text-muted small">First-party event count</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-kpi bg-light">
            <div class="kpi-label">Cancelled Orders</div>
            <div class="fs-4 fw-bold text-danger"><?= (int)$kpis['cancelled_orders'] ?></div>
            <div class="text-muted small">Value: <?= format_currency($kpis['cancelled_value']) ?></div>
        </div>
    </div>
</div>

<!-- Tables Row -->
<div class="row g-4">
    <!-- Recent Orders -->
    <div class="col-lg-8">
        <div class="card border shadow-sm p-4 bg-white" style="border-radius: 8px;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold m-0"><i class="bi bi-receipt me-2"></i>Recent Orders</h5>
                <a href="/admin/orders" class="small fw-bold text-decoration-none text-dark">View All &rarr;</a>
            </div>

            <?php if (empty($kpis['recent_orders'])): ?>
                <div class="p-4 text-center text-muted small bg-light rounded">No orders recorded in this date range.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table align-middle small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>State</th>
                                <th>Payment</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($kpis['recent_orders'] as $ord): ?>
                            <tr>
                                <td class="fw-bold"><?= e($ord['order_number']) ?></td>
                                <td><?= e($ord['customer_name'] ?? $ord['guest_phone']) ?></td>
                                <td><?= e($ord['shipping_state']) ?></td>
                                <td><span class="badge bg-secondary-subtle text-secondary"><?= strtoupper(e($ord['payment_method'])) ?></span></td>
                                <td class="fw-bold"><?= format_currency($ord['total_amount']) ?></td>
                                <td><span class="badge bg-dark-subtle text-dark text-uppercase"><?= e($ord['status']) ?></span></td>
                                <td><a href="/admin/orders/<?= (int)$ord['id'] ?>" class="btn btn-sm btn-outline-dark py-0 px-2">Manage</a></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Inventory Stock Alerts -->
    <div class="col-lg-4">
        <div class="card border shadow-sm p-4 bg-white" style="border-radius: 8px;">
            <h5 class="fw-bold mb-3"><i class="bi bi-exclamation-triangle text-warning me-2"></i>Low Stock Watch Alerts</h5>

            <?php if (empty($kpis['low_stock_alerts'])): ?>
                <div class="p-3 bg-light rounded text-muted small text-center">All watch variants are above threshold limits.</div>
            <?php else: ?>
                <div class="d-flex flex-column gap-2">
                    <?php foreach ($kpis['low_stock_alerts'] as $alert): ?>
                    <div class="d-flex justify-content-between align-items-center p-2 border rounded bg-light small">
                        <div>
                            <strong class="d-block text-dark"><?= e($alert['product_name']) ?></strong>
                            <span class="text-muted" style="font-size: 11px;">SKU: <?= e($alert['sku']) ?></span>
                        </div>
                        <span class="badge bg-danger"><?= (int)$alert['quantity'] ?> units left</span>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="mt-4 border-top pt-3">
                <a href="/admin/products" class="btn btn-outline-dark btn-sm w-100 fw-bold">Manage Watch Inventory &rarr;</a>
            </div>
        </div>
    </div>
</div>
