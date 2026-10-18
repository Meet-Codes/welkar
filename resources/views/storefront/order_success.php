<div class="container py-5 text-center" style="max-width: 680px;">
    <div class="mb-4">
        <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background: rgba(46, 125, 50, 0.15); color: #2ECC71; border: 1px solid rgba(46, 125, 50, 0.35);">
            <i class="bi bi-check-lg fs-1"></i>
        </div>
    </div>

    <span class="text-uppercase fw-bold" style="font-size: 11px; letter-spacing: 2px; color: #C59B3F;">RESERVATION CONFIRMED</span>
    <h1 class="h2 fw-bold mt-1 mb-2" style="font-family: var(--font-display, 'DM Serif Display', serif); color: #F5F5F5; letter-spacing: -0.5px;">Thank You for Your Order</h1>
    <p class="small mx-auto mb-4" style="max-width: 480px; color: #A0A0A5; font-family: var(--font-ui, 'Inter', sans-serif); line-height: 1.6;">
        Your watch has been reserved and our horological fulfillment team is carefully preparing your protective packaging and inspection certificate.
    </p>

    <div class="card p-4 border text-start shadow-sm mb-4" style="background: #141416; border-radius: 10px; border-color: #2A2A2E !important; font-family: var(--font-ui, 'Inter', sans-serif);">
        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3" style="border-color: #2A2A2E !important;">
            <div>
                <span class="small text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px; color: #A0A0A5;">Order Number</span>
                <div class="fs-5 fw-bold" style="color: #F5F5F5;"><?= e($order['order_number']) ?></div>
            </div>
            <div>
                <span class="badge px-3 py-2 fw-semibold text-uppercase" style="background: rgba(46, 125, 50, 0.2); color: #2ECC71; border: 1px solid rgba(46, 125, 50, 0.3); border-radius: 4px; font-size: 11px;">
                    <?= e($order['status']) ?>
                </span>
            </div>
        </div>

        <div class="row g-3 small mb-3">
            <div class="col-6">
                <span class="text-uppercase fw-semibold d-block mb-1" style="font-size: 10.5px; letter-spacing: 0.5px; color: #A0A0A5;">Delivery Address</span>
                <strong style="color: #F5F5F5;"><?= e($address['full_name']) ?></strong><br>
                <span style="color: #A0A0A5;"><?= e($address['address_line1']) ?>, <?= e($address['city']) ?><br>
                <?= e($address['state']) ?> - <?= e($address['pincode']) ?><br>
                Phone: <?= e($address['phone']) ?></span>
            </div>
            <div class="col-6">
                <span class="text-uppercase fw-semibold d-block mb-1" style="font-size: 10.5px; letter-spacing: 0.5px; color: #A0A0A5;">Order Details</span>
                <span style="color: #A0A0A5;">Payment: </span><strong style="color: #F5F5F5;"><?= strtoupper(e($order['payment_method'])) ?></strong><br>
                <span style="color: #A0A0A5;">Total Paid: </span><strong style="color: #F5F5F5;"><?= format_currency($order['total_amount']) ?></strong><br>
                <span style="color: #A0A0A5;">Placed On: </span><span style="color: #F5F5F5;"><?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></span>
            </div>
        </div>

        <!-- Items -->
        <h6 class="fw-bold border-top pt-3 mb-2" style="font-family: var(--font-ui, 'Inter', sans-serif); font-size: 13px; border-color: #2A2A2E !important; color: #F5F5F5;">
            Watches in this shipment:
        </h6>
        <?php foreach ($items as $item): ?>
        <div class="d-flex justify-content-between align-items-center py-2 border-bottom small" style="border-color: #2A2A2E !important;">
            <div>
                <strong style="color: #F5F5F5;"><?= e($item['product_name']) ?></strong> &bull; <span style="color: #A0A0A5;"><?= e($item['variant_name']) ?></span><br>
                <span style="font-size: 11.5px; color: #8A8A90;">Qty: <?= (int)$item['quantity'] ?> &bull; SKU: <?= e($item['sku']) ?></span>
            </div>
            <span class="fw-bold" style="color: #F5F5F5;"><?= format_currency($item['total']) ?></span>
        </div>
        <?php endforeach; ?>

        <!-- Invoice & Tracking Buttons (6px radius) -->
        <div class="d-flex flex-wrap gap-3 mt-4">
            <a href="/account/invoice/<?= e($order['order_number']) ?>" target="_blank" class="btn btn-outline-light btn-sm flex-grow-1" style="border-radius: 6px; border-color: #2A2A2E; height: 42px; display: inline-flex; align-items: center; justify-content: center; font-weight: 600;">
                <i class="bi bi-file-earmark-pdf me-2 text-danger"></i>Download Tax Invoice
            </a>
            <a href="/track-order?order_number=<?= urlencode($order['order_number']) ?>&phone=<?= urlencode($address['phone']) ?>" class="btn btn-welkar btn-sm flex-grow-1" style="border-radius: 6px; height: 42px; display: inline-flex; align-items: center; justify-content: center; font-weight: 600;">
                <i class="bi bi-truck me-2"></i>Track Delivery
            </a>
        </div>
    </div>

    <?php if (!empty($pendingRazorpay) && ($order['payment_status'] ?? 'pending') !== 'captured'): ?>
    <!-- Razorpay Checkout Trigger Panel -->
    <div class="card p-4 text-start mb-4 border" style="background: #1A1A1C; border-color: #E63946 !important; border-radius: 10px;">
        <?php
        $methodNames = [
            'upi' => 'UPI (Google Pay / PhonePe / Paytm)',
            'card' => 'Debit / Credit Card',
            'netbanking' => 'Net Banking',
            'wallet' => 'Wallets / EMI / PayLater',
            'razorpay' => 'Razorpay Online Payment',
        ];
        $chosenMethodTitle = $methodNames[$pendingRazorpay['preferred_method'] ?? 'razorpay'] ?? 'Online Payment';
        ?>
        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
            <div>
                <h5 class="fw-bold mb-1" style="color: #F5F5F5;"><i class="bi bi-shield-check text-warning me-2"></i>Complete <?= e($chosenMethodTitle) ?></h5>
                <p class="small mb-0" style="color: #A0A0A5;">Authorize ₹<?= number_format((float)$order['total_amount'], 2) ?> via Razorpay Gateway to confirm and dispatch your timepiece.</p>
            </div>
            <button type="button" id="btn-pay-razorpay" class="btn btn-welkar px-4 py-2" style="font-weight: 600;">
                Pay Now <i class="bi bi-arrow-right ms-1"></i>
            </button>
        </div>
        <div id="razorpay-payment-alert" class="alert alert-info py-2 px-3 small d-none m-0" style="background: #141416; border-color: #2A2A2E; color: #F5F5F5;"></div>
    </div>

    <!-- Razorpay Official Checkout SDK -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const payBtn = document.getElementById('btn-pay-razorpay');
        const alertBox = document.getElementById('razorpay-payment-alert');

        const options = {
            key: "<?= e($pendingRazorpay['key_id']) ?>",
            amount: "<?= (int)$pendingRazorpay['amount_paise'] ?>",
            currency: "INR",
            name: "WELKAR Timepieces",
            description: "Order #<?= e($order['order_number']) ?>",
            image: "/assets/images/welkar-logo-transparent.png",
            order_id: "<?= e($pendingRazorpay['razorpay_order_id']) ?>",
            prefill: {
                name: "<?= e($address['full_name']) ?>",
                email: "<?= e($order['email'] ?? $address['email'] ?? '') ?>",
                contact: "<?= e($address['phone']) ?>"
            },
            theme: {
                color: "#E63946"
            },
            handler: function(response) {
                if (alertBox) {
                    alertBox.className = 'alert alert-info py-2 px-3 small';
                    alertBox.textContent = 'Verifying cryptographic payment signature with Welkar server...';
                    alertBox.classList.remove('d-none');
                }

                fetch('/checkout/verify-payment', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-Token': '<?= csrf_token() ?>'
                    },
                    body: new URLSearchParams({
                        _token: '<?= csrf_token() ?>',
                        order_number: "<?= e($order['order_number']) ?>",
                        razorpay_order_id: response.razorpay_order_id,
                        razorpay_payment_id: response.razorpay_payment_id,
                        razorpay_signature: response.razorpay_signature
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        if (alertBox) {
                            alertBox.className = 'alert alert-success py-2 px-3 small';
                            alertBox.textContent = 'Payment captured and verified! Reloading order confirmation...';
                        }
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        if (alertBox) {
                            alertBox.className = 'alert alert-danger py-2 px-3 small';
                            alertBox.textContent = data.message || 'Signature verification failed.';
                        }
                    }
                })
                .catch(err => {
                    if (alertBox) {
                        alertBox.className = 'alert alert-danger py-2 px-3 small';
                        alertBox.textContent = 'Payment verification request failed. Our concierge will review your order.';
                    }
                });
            }
        };

        const rzp = new Razorpay(options);
        if (payBtn) {
            payBtn.addEventListener('click', function(e) {
                e.preventDefault();
                rzp.open();
            });
            setTimeout(() => rzp.open(), 600);
        }
    });
    </script>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="/catalog" class="btn btn-outline-light btn-sm px-4 py-2" style="border-radius: 6px; border-color: #2A2A2E; font-weight: 600; font-family: var(--font-ui, 'Inter', sans-serif);">
            <i class="bi bi-arrow-left me-2"></i>Continue Exploring Welkar Catalog
        </a>
    </div>
</div>
