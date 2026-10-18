<div class="container py-5" style="max-width: 640px;">
    <div class="text-center mb-4">
        <span class="badge mb-2 px-3 py-1 text-uppercase" style="background: rgba(197, 155, 63, 0.15); color: #C59B3F; border: 1px solid rgba(197, 155, 63, 0.3); font-size: 11px; letter-spacing: 1px;">
            Logistics &amp; Dispatch
        </span>
        <h1 class="h2 fw-bold mt-1 mb-2" style="font-family: var(--font-display, 'DM Serif Display', serif); color: #F5F5F5; letter-spacing: -0.5px;">Track Your Watch Delivery</h1>
        <p class="small mx-auto" style="max-width: 440px; color: #A0A0A5; font-family: var(--font-ui, 'Inter', sans-serif); line-height: 1.6;">
            Enter your Welkar order number and verified phone number to inspect live fulfillment and courier milestones.
        </p>
    </div>

    <div class="card p-4 p-md-5 border shadow-sm mb-4" style="background: #141416; border-radius: 10px; border-color: #2A2A2E !important; font-family: var(--font-ui, 'Inter', sans-serif);">
        <form action="/track-order" method="GET">
            <div class="mb-3">
                <label for="track_order_number" class="form-label small fw-semibold" style="color: #A0A0A5;">Order Number *</label>
                <input type="text" id="track_order_number" name="order_number" class="form-control" placeholder="e.g. WEL-ORD-20261012-ABC123" required value="<?= e($_GET['order_number'] ?? '') ?>" style="height: 48px; border-radius: 6px; border: 1px solid #2A2A2E; background: #1A1A1C; color: #F5F5F5; font-size: 14px;">
            </div>
            <div class="mb-4">
                <label for="track_phone" class="form-label small fw-semibold" style="color: #A0A0A5;">10-Digit Mobile Number *</label>
                <input type="tel" id="track_phone" name="phone" class="form-control" placeholder="e.g. 9876543210" required value="<?= e($_GET['phone'] ?? '') ?>" style="height: 48px; border-radius: 6px; border: 1px solid #2A2A2E; background: #1A1A1C; color: #F5F5F5; font-size: 14px;">
            </div>
            <button type="submit" class="btn btn-welkar w-100" style="height: 48px; border-radius: 6px; font-weight: 600; font-size: 14px; letter-spacing: 0.5px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-geo-alt me-2"></i>Track Shipment
            </button>
        </form>
    </div>

    <?php if ($searched): ?>
        <?php if ($order): ?>
            <div class="card p-4 border shadow-sm" style="background: #141416; border-radius: 10px; border-color: #2A2A2E !important; font-family: var(--font-ui, 'Inter', sans-serif);">
                <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-3" style="border-color: #2A2A2E !important;">
                    <div>
                        <span class="small text-uppercase" style="font-size: 10.5px; letter-spacing: 0.5px; color: #A0A0A5;">Order Reference</span>
                        <h6 class="fw-bold m-0" style="color: #F5F5F5;"><?= e($order['order_number']) ?></h6>
                    </div>
                    <span class="badge px-3 py-2 text-uppercase fw-semibold" style="background: rgba(46, 125, 50, 0.2); color: #2ECC71; border: 1px solid rgba(46, 125, 50, 0.3); border-radius: 4px; font-size: 11px;">
                        <?= e($order['status']) ?>
                    </span>
                </div>

                <?php if ($shipment): ?>
                    <div class="p-3 mb-2" style="background: #1A1A1C; border-radius: 6px; border: 1px solid #2A2A2E; font-size: 13px;">
                        <span style="color: #A0A0A5;">Courier Partner: </span><strong style="color: #F5F5F5;"><?= e($shipment['courier_name']) ?></strong><br>
                        <span style="color: #A0A0A5;">Tracking AWB: </span><strong style="color: #F5F5F5;"><?= e($shipment['tracking_number']) ?></strong><br>
                        <span style="color: #A0A0A5;">Dispatched At: </span><span style="color: #F5F5F5;"><?= date('d M Y, h:i A', strtotime($shipment['dispatched_at'])) ?></span>
                        <?php if (!empty($shipment['tracking_url'])): ?>
                            <div class="mt-2 pt-2 border-top" style="border-color: #2A2A2E !important;">
                                <a href="<?= e($shipment['tracking_url']) ?>" target="_blank" class="fw-semibold text-decoration-none" style="color: #C59B3F;">
                                    Direct Courier Tracking Portal &rarr;
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="p-3 small" style="background: #1A1A1C; color: #A0A0A5; border-radius: 6px; border: 1px dashed #2A2A2E;">
                        <i class="bi bi-box-seam me-2 text-warning"></i>
                        Order is currently in secure packing at our central vault. Tracking information will be updated as soon as custody transfers to our express courier partner.
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="p-3 text-center small" style="background: rgba(230, 57, 70, 0.12); color: #E63946; border: 1px solid rgba(230, 57, 70, 0.3); border-radius: 6px; font-family: var(--font-ui, 'Inter', sans-serif);">
                No matching order found. Please verify your order number and phone number, or contact Welkar Concierge.
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
