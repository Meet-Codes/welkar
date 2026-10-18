<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Website Analytics & Advertising Intelligence</h3>
        <p class="text-muted small m-0">First-party customer engagement, dial-style demand, UTM campaign attribution, and ad integration statuses.</p>
    </div>
</div>

<!-- Advertising & External Integrations Health -->
<?php
    $isMetaConfigured = \App\Services\Marketing\MetaConversionsService::isConfigured();
    $isRazorpayConfigured = \App\Services\Payments\RazorpayAdapter::isConfigured();
?>
<div class="card p-4 border shadow-sm mb-4 bg-white" style="border-radius: 8px;">
    <h5 class="fw-bold mb-3"><i class="bi bi-link-45deg me-2"></i>Marketing & Advertising Integrations Status</h5>
    <div class="row g-3">
        <div class="col-md-4">
            <div class="p-3 border rounded bg-light">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong class="text-dark"><i class="bi bi-meta text-primary me-1"></i>Meta Ads / Pixel & CAPI</strong>
                    <span class="badge <?= $isMetaConfigured ? 'bg-success' : 'bg-secondary' ?>">
                        <?= $isMetaConfigured ? 'Active (CAPI SDK)' : 'SDK Ready (Unconfigured)' ?>
                    </span>
                </div>
                <p class="text-muted small mb-0" style="font-size: 11.5px;">
                    <?= $isMetaConfigured ? 'Meta Business SDK active. Dispatching server-side CAPI events and browser pixel deduplication.' : 'Official Facebook Business PHP SDK loaded. Add <code>META_PIXEL_ID</code> & <code>META_CONVERSIONS_API_TOKEN</code> in Settings to dispatch live events.' ?>
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 border rounded bg-light">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong class="text-dark"><i class="bi bi-credit-card text-success me-1"></i>Razorpay Gateway</strong>
                    <span class="badge <?= $isRazorpayConfigured ? 'bg-success' : 'bg-info text-dark' ?>">
                        <?= $isRazorpayConfigured ? 'Live / Captured' : 'SDK v2.9.3 (Test Mode)' ?>
                    </span>
                </div>
                <p class="text-muted small mb-0" style="font-size: 11.5px;">Official Razorpay PHP SDK v2.9.3 integrated with order creation, signature verification, and automated refunds.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="p-3 border rounded bg-light">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong class="text-dark"><i class="bi bi-google text-danger me-1"></i>Google Analytics 4</strong>
                    <span class="badge bg-secondary">Unconfigured</span>
                </div>
                <p class="text-muted small mb-0" style="font-size: 11.5px;">GA4 Measurement ID not configured. Relying on Welkar first-party sessionization.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 border rounded bg-light">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong class="text-dark"><i class="bi bi-graph-up text-success me-1"></i>Google Ads</strong>
                    <span class="badge bg-secondary">Unconfigured</span>
                </div>
                <p class="text-muted small mb-0" style="font-size: 11.5px;">Google Ads reporting API inactive until owner supplies verified MCC account credentials.</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Dial Style Performance -->
    <div class="col-lg-6">
        <div class="card p-4 border shadow-sm h-100 bg-white" style="border-radius: 8px;">
            <h5 class="fw-bold mb-3"><i class="bi bi-clock me-2"></i>Dial Style Sales Breakdown</h5>
            <?php if (empty($salesByDial)): ?>
                <div class="p-4 bg-light rounded text-center text-muted small">No dial sales data recorded yet.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table align-middle small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Dial Architecture</th>
                                <th class="text-center">Units Sold</th>
                                <th class="text-end">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($salesByDial as $sd): ?>
                            <tr>
                                <td class="fw-bold"><?= e($sd['dial_type']) ?></td>
                                <td class="text-center"><?= (int)$sd['items_sold'] ?></td>
                                <td class="text-end fw-bold"><?= format_currency($sd['total_revenue']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Brand Performance -->
    <div class="col-lg-6">
        <div class="card p-4 border shadow-sm h-100 bg-white" style="border-radius: 8px;">
            <h5 class="fw-bold mb-3"><i class="bi bi-award me-2"></i>Brand Sales Breakdown</h5>
            <?php if (empty($salesByBrand)): ?>
                <div class="p-4 bg-light rounded text-center text-muted small">No brand sales data recorded yet.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table align-middle small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Brand</th>
                                <th class="text-center">Units Sold</th>
                                <th class="text-end">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($salesByBrand as $sb): ?>
                            <tr>
                                <td class="fw-bold"><?= e($sb['brand_name']) ?></td>
                                <td class="text-center"><?= (int)$sb['items_sold'] ?></td>
                                <td class="text-end fw-bold"><?= format_currency($sb['total_revenue']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- UTM Marketing Campaigns Attribution -->
<div class="card p-4 border shadow-sm bg-white" style="border-radius: 8px;">
    <h5 class="fw-bold mb-3"><i class="bi bi-funnel me-2"></i>First-Party UTM Campaign Attribution</h5>
    <?php if (empty($utmPerformance)): ?>
        <div class="p-4 bg-light rounded text-center text-muted small">
            No tagged UTM traffic sessions detected yet. Append <code>?utm_source=meta&utm_campaign=diwali</code> to inbound links to measure ad conversions.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle small mb-0">
                <thead class="table-light">
                    <tr>
                        <th>UTM Source</th>
                        <th>UTM Campaign</th>
                        <th class="text-center">Visitors</th>
                        <th class="text-center">Orders</th>
                        <th class="text-end">Attributed Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($utmPerformance as $utm): ?>
                    <tr>
                        <td class="fw-bold"><code><?= e($utm['utm_source']) ?></code></td>
                        <td><?= e($utm['utm_campaign'] ?: 'Default') ?></td>
                        <td class="text-center"><?= (int)$utm['visitors'] ?></td>
                        <td class="text-center fw-bold"><?= (int)$utm['attributed_orders'] ?></td>
                        <td class="text-end fw-bold"><?= format_currency($utm['attributed_revenue']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
