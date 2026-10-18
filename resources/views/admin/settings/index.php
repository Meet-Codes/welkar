<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Store & Business Settings</h3>
        <p class="text-muted small m-0">Configure business legal entity credentials, GST parameters, payment gateways, and shipping rules.</p>
    </div>
</div>

<div class="card p-4 border shadow-sm bg-white" style="border-radius: 8px;">
    <form action="/admin/settings/update" method="POST">
        <?= csrf_field() ?>

        <!-- Business Legal Entity & GST Section -->
        <h5 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-building me-2"></i>Legal Entity & Taxation (GST)</h5>
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label small fw-bold">Legal Entity Name (As per GST certificate)</label>
                <input type="text" name="business_legal_name" class="form-control" value="<?= e($settings['business_legal_name'] ?? 'Welkar Timepieces Private Limited') ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-bold">GSTIN (Owner-Supplied)</label>
                <input type="text" name="business_gstin" class="form-control" placeholder="27AAAAA0000A1Z5" value="<?= e($settings['business_gstin'] ?? '') ?>">
                <div class="form-text small">Marked as owner-supplied. Validated during official invoicing.</div>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold">Business State</label>
                <input type="text" name="business_state" class="form-control" value="<?= e($settings['business_state'] ?? 'Maharashtra') ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold">GST State Code</label>
                <input type="text" name="business_state_code" class="form-control" value="<?= e($settings['business_state_code'] ?? '27') ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold">Default Watch GST Rate (%)</label>
                <input type="number" step="0.01" name="default_gst_rate" class="form-control" value="<?= e($settings['default_gst_rate'] ?? '18.00') ?>">
            </div>
        </div>

        <!-- Shipping Rules -->
        <h5 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-truck me-2"></i>Shipping Rules</h5>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label small fw-bold">Default Flat Shipping Fee (₹)</label>
                <input type="number" step="0.01" name="default_shipping_fee" class="form-control" value="<?= e($settings['default_shipping_fee'] ?? '60.00') ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold">Free Shipping Threshold (₹)</label>
                <input type="number" step="0.01" name="free_shipping_threshold" class="form-control" value="<?= e($settings['free_shipping_threshold'] ?? '1499.00') ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold">COD Max Order Limit (₹)</label>
                <input type="number" step="0.01" name="cod_max_amount" class="form-control" value="<?= e($settings['cod_max_amount'] ?? '5000.00') ?>">
            </div>
        </div>

        <!-- Razorpay Configuration -->
        <h5 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-credit-card me-2"></i>Razorpay Gateway Configuration</h5>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label small fw-bold">Gateway Mode</label>
                <select name="razorpay_mode" class="form-select">
                    <option value="test" <?= (($settings['razorpay_mode'] ?? 'test') === 'test') ? 'selected' : '' ?>>Test Mode (Default)</option>
                    <option value="live" <?= (($settings['razorpay_mode'] ?? '') === 'live') ? 'selected' : '' ?>>Live Mode (Requires verified keys)</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold">Razorpay Key ID</label>
                <input type="text" name="razorpay_key_id" class="form-control" value="<?= e($settings['razorpay_key_id'] ?? '') ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold">Razorpay Key Secret</label>
                <input type="password" name="razorpay_key_secret" class="form-control" placeholder="••••••••••••" value="<?= e($settings['razorpay_key_secret'] ?? '') ?>">
            </div>
            <div class="col-md-12">
                <label class="form-label small fw-bold">Razorpay Webhook Secret (Optional)</label>
                <input type="password" name="razorpay_webhook_secret" class="form-control" placeholder="For automated /webhooks/razorpay signature verification" value="<?= e($settings['razorpay_webhook_secret'] ?? '') ?>">
            </div>
        </div>

        <!-- Meta (Facebook) Ads, Pixel & Conversions API Configuration -->
        <h5 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-meta text-primary me-2"></i>Meta Ads, Pixel & Conversions API (CAPI)</h5>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label small fw-bold">Meta Pixel ID</label>
                <input type="text" name="meta_pixel_id" class="form-control" placeholder="e.g. 1234567890123456" value="<?= e($settings['meta_pixel_id'] ?? '') ?>">
                <div class="form-text" style="font-size: 11px;">Injected automatically into storefront <code>&lt;head&gt;</code> for browser tracking.</div>
            </div>
            <div class="col-md-5">
                <label class="form-label small fw-bold">Conversions API (CAPI) Access Token</label>
                <input type="password" name="meta_conversions_api_token" class="form-control" placeholder="EAAB..." value="<?= e($settings['meta_conversions_api_token'] ?? '') ?>">
                <div class="form-text" style="font-size: 11px;">Server-side event token generated in Meta Events Manager.</div>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">Test Event Code (Optional)</label>
                <input type="text" name="meta_test_event_code" class="form-control" placeholder="e.g. TEST12345" value="<?= e($settings['meta_test_event_code'] ?? '') ?>">
                <div class="form-text" style="font-size: 11px;">For live debugging in Meta Test Events console.</div>
            </div>
        </div>

        <button type="submit" class="btn btn-dark fw-bold px-4 py-2">
            <i class="bi bi-save me-1"></i> Save Store Settings
        </button>
    </form>
</div>
