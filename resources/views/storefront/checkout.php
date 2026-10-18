<div class="container py-4">
    <h1 class="h2 fw-normal mb-4" style="font-family: var(--font-display, 'DM Serif Display', Georgia, serif); color: #F5F5F5;">Express Checkout</h1>

    <form action="/checkout/process" method="POST">
        <?= csrf_field() ?>
        <div class="row g-5 reveal-on-scroll">
            <!-- Left Column: Delivery Address & Payment Method -->
            <div class="col-lg-7">
                <div class="p-4 border card-luxury mb-4" style="background: #141416; border-color: #2A2A2E !important; border-radius: 10px;">
                    <h5 class="fw-normal mb-3" style="font-family: var(--font-display, 'DM Serif Display', Georgia, serif); color: #F5F5F5;">
                        <i class="bi bi-geo-alt me-2" style="color: #C59B3F;"></i>1. Shipping &amp; Delivery Address
                    </h5>

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="checkout_full_name" class="form-label small fw-semibold" style="color: #A0A0A5; font-size: 11px;">Full Name *</label>
                            <input type="text" id="checkout_full_name" name="full_name" class="form-control" value="<?= e($authUser['full_name'] ?? '') ?>" required placeholder="e.g. Rahul Sharma" style="height: 48px; border-radius: 6px; border: 1px solid #2A2A2E; background: #1A1A1C; color: #F5F5F5;">
                        </div>

                        <div class="col-md-6">
                            <label for="checkout_phone" class="form-label small fw-semibold" style="color: #A0A0A5; font-size: 11px;">Mobile Phone (10 Digits) *</label>
                            <div class="input-group">
                                <span class="input-group-text small" style="border-color: #2A2A2E; background: #222226; color: #F5F5F5; border-radius: 6px 0 0 6px;">+91</span>
                                <input type="tel" id="checkout_phone" name="phone" class="form-control" value="<?= e($authUser['phone'] ?? '') ?>" pattern="[0-9]{10}" maxlength="10" required placeholder="9876543210" style="height: 48px; border-radius: 0 6px 6px 0; border: 1px solid #2A2A2E; background: #1A1A1C; color: #F5F5F5;">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="checkout_email" class="form-label small fw-semibold" style="color: #A0A0A5; font-size: 11px;">Email Address (For Tax Invoice)</label>
                            <input type="email" id="checkout_email" name="email" class="form-control" value="<?= e($authUser['email'] ?? '') ?>" placeholder="rahul@example.com" style="height: 48px; border-radius: 6px; border: 1px solid #2A2A2E; background: #1A1A1C; color: #F5F5F5;">
                        </div>

                        <div class="col-12">
                            <label for="checkout_address_line1" class="form-label small fw-semibold" style="color: #A0A0A5; font-size: 11px;">Flat / House No. / Building / Street *</label>
                            <input type="text" id="checkout_address_line1" name="address_line1" class="form-control" required placeholder="Apartment, Studio, or Floor" style="height: 48px; border-radius: 6px; border: 1px solid #2A2A2E; background: #1A1A1C; color: #F5F5F5;">
                        </div>

                        <div class="col-12">
                            <label for="checkout_address_line2" class="form-label small fw-semibold" style="color: #A0A0A5; font-size: 11px;">Landmark / Colony (Optional)</label>
                            <input type="text" id="checkout_address_line2" name="address_line2" class="form-control" placeholder="Near metro station or landmark" style="height: 48px; border-radius: 6px; border: 1px solid #2A2A2E; background: #1A1A1C; color: #F5F5F5;">
                        </div>

                        <div class="col-md-5">
                            <label for="checkout_city" class="form-label small fw-semibold" style="color: #A0A0A5; font-size: 11px;">City *</label>
                            <input type="text" id="checkout_city" name="city" class="form-control" required placeholder="Mumbai" style="height: 48px; border-radius: 6px; border: 1px solid #2A2A2E; background: #1A1A1C; color: #F5F5F5;">
                        </div>

                        <div class="col-md-4">
                            <label for="checkout_state" class="form-label small fw-semibold" style="color: #A0A0A5; font-size: 11px;">State (Place of Supply) *</label>
                            <select id="checkout_state" name="state" class="form-select shadow-none" required style="height: 48px; border-radius: 6px; border: 1px solid #2A2A2E; background: #1A1A1C; color: #F5F5F5;">
                                <?php foreach ($indianStates as $stName => $stCode): ?>
                                    <option value="<?= e($stName) ?>" <?= ($selectedState === $stName) ? 'selected' : '' ?>>
                                        <?= e($stName) ?> (<?= $stCode ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="checkout_pincode" class="form-label small fw-semibold" style="color: #A0A0A5; font-size: 11px;">PIN Code *</label>
                            <input type="text" id="checkout_pincode" name="pincode" class="form-control" pattern="[0-9]{6}" maxlength="6" required placeholder="400001" style="height: 48px; border-radius: 6px; border: 1px solid #2A2A2E; background: #1A1A1C; color: #F5F5F5;">
                        </div>
                    </div>
                </div>

                <!-- Payment Selection (5 Separate Options) -->
                <div class="p-4 border card-luxury mb-4" style="background: #141416; border-color: #2A2A2E !important; border-radius: 10px;">
                    <h5 class="fw-normal mb-3" style="font-family: var(--font-display, 'DM Serif Display', Georgia, serif); color: #F5F5F5;">
                        <i class="bi bi-credit-card me-2" style="color: #C59B3F;"></i>2. Select Payment Method
                    </h5>

                    <div class="d-flex flex-column gap-3">
                        <!-- Option 1: UPI -->
                        <div class="border p-3 d-flex align-items-start gap-3 payment-option-card" style="background: #1A1A1C; border-color: #2A2A2E !important; border-radius: 6px; cursor: pointer; transition: all 0.2s ease;">
                            <input class="form-check-input mt-1" type="radio" name="payment_method" id="pay-upi" value="upi" checked>
                            <label class="form-check-label w-100" for="pay-upi" style="cursor: pointer;">
                                <div class="d-flex justify-content-between align-items-center mb-1 flex-wrap gap-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-qr-code-scan text-warning fs-5"></i>
                                        <strong style="color: #F5F5F5;">UPI Instant Payment</strong>
                                    </div>
                                    <div class="d-flex gap-1">
                                        <span class="badge border" style="background: #222226; border-color: #2A2A2E; color: #F5F5F5; font-size: 10px;">Google Pay</span>
                                        <span class="badge border" style="background: #222226; border-color: #2A2A2E; color: #F5F5F5; font-size: 10px;">PhonePe</span>
                                        <span class="badge border" style="background: #222226; border-color: #2A2A2E; color: #F5F5F5; font-size: 10px;">Paytm / BHIM</span>
                                    </div>
                                </div>
                                <div class="small" style="font-size: 11.5px; color: #A0A0A5;">Direct 1-click UPI authorization or QR scan via Razorpay Gateway. Instant order processing.</div>
                            </label>
                        </div>

                        <!-- Option 2: Credit / Debit Cards -->
                        <div class="border p-3 d-flex align-items-start gap-3 payment-option-card" style="background: #1A1A1C; border-color: #2A2A2E !important; border-radius: 6px; cursor: pointer; transition: all 0.2s ease;">
                            <input class="form-check-input mt-1" type="radio" name="payment_method" id="pay-card" value="card">
                            <label class="form-check-label w-100" for="pay-card" style="cursor: pointer;">
                                <div class="d-flex justify-content-between align-items-center mb-1 flex-wrap gap-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-credit-card-2-front text-success fs-5"></i>
                                        <strong style="color: #F5F5F5;">Debit / Credit Cards</strong>
                                    </div>
                                    <div class="d-flex gap-1">
                                        <span class="badge border" style="background: #222226; border-color: #2A2A2E; color: #F5F5F5; font-size: 10px;">Visa</span>
                                        <span class="badge border" style="background: #222226; border-color: #2A2A2E; color: #F5F5F5; font-size: 10px;">Mastercard</span>
                                        <span class="badge border" style="background: #222226; border-color: #2A2A2E; color: #F5F5F5; font-size: 10px;">RuPay</span>
                                    </div>
                                </div>
                                <div class="small" style="font-size: 11.5px; color: #A0A0A5;">All Indian &amp; International cards supported via secure 256-bit encrypted Razorpay Gateway.</div>
                            </label>
                        </div>

                        <!-- Option 3: Net Banking -->
                        <div class="border p-3 d-flex align-items-start gap-3 payment-option-card" style="background: #1A1A1C; border-color: #2A2A2E !important; border-radius: 6px; cursor: pointer; transition: all 0.2s ease;">
                            <input class="form-check-input mt-1" type="radio" name="payment_method" id="pay-netbanking" value="netbanking">
                            <label class="form-check-label w-100" for="pay-netbanking" style="cursor: pointer;">
                                <div class="d-flex justify-content-between align-items-center mb-1 flex-wrap gap-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-bank text-info fs-5"></i>
                                        <strong style="color: #F5F5F5;">Net Banking</strong>
                                    </div>
                                    <div class="d-flex gap-1">
                                        <span class="badge border" style="background: #222226; border-color: #2A2A2E; color: #F5F5F5; font-size: 10px;">HDFC</span>
                                        <span class="badge border" style="background: #222226; border-color: #2A2A2E; color: #F5F5F5; font-size: 10px;">ICICI</span>
                                        <span class="badge border" style="background: #222226; border-color: #2A2A2E; color: #F5F5F5; font-size: 10px;">SBI / 50+ Banks</span>
                                    </div>
                                </div>
                                <div class="small" style="font-size: 11.5px; color: #A0A0A5;">Direct secure net banking across all scheduled Indian banks via Razorpay Gateway.</div>
                            </label>
                        </div>

                        <!-- Option 4: Other Razorpay Options (Wallets, EMI, PayLater) -->
                        <div class="border p-3 d-flex align-items-start gap-3 payment-option-card" style="background: #1A1A1C; border-color: #2A2A2E !important; border-radius: 6px; cursor: pointer; transition: all 0.2s ease;">
                            <input class="form-check-input mt-1" type="radio" name="payment_method" id="pay-wallet" value="wallet">
                            <label class="form-check-label w-100" for="pay-wallet" style="cursor: pointer;">
                                <div class="d-flex justify-content-between align-items-center mb-1 flex-wrap gap-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-wallet2 text-warning fs-5"></i>
                                        <strong style="color: #F5F5F5;">Other Razorpay Options (Wallets &amp; EMI)</strong>
                                    </div>
                                    <div class="d-flex gap-1">
                                        <span class="badge border" style="background: #222226; border-color: #2A2A2E; color: #F5F5F5; font-size: 10px;">Wallets</span>
                                        <span class="badge border" style="background: #222226; border-color: #2A2A2E; color: #F5F5F5; font-size: 10px;">Cardless EMI</span>
                                        <span class="badge border" style="background: #222226; border-color: #2A2A2E; color: #F5F5F5; font-size: 10px;">PayLater</span>
                                    </div>
                                </div>
                                <div class="small" style="font-size: 11.5px; color: #A0A0A5;">Mobikwik, Airtel Money, Freecharge, No-cost Cardless EMI &amp; PayLater via Razorpay.</div>
                            </label>
                        </div>

                        <!-- Option 5: Cash On Delivery (COD) -->
                        <div class="border p-3 d-flex align-items-start gap-3 payment-option-card" style="background: #1A1A1C; border-color: #2A2A2E !important; border-radius: 6px; cursor: pointer; transition: all 0.2s ease;">
                            <input class="form-check-input mt-1" type="radio" name="payment_method" id="pay-cod" value="cod">
                            <label class="form-check-label w-100" for="pay-cod" style="cursor: pointer;">
                                <div class="d-flex justify-content-between align-items-center mb-1 flex-wrap gap-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-cash-stack text-secondary fs-5"></i>
                                        <strong style="color: #F5F5F5;"><?= __('cod_eligible') ?></strong>
                                    </div>
                                    <span class="badge" style="background: rgba(197, 155, 63, 0.2); border: 1px solid rgba(197, 155, 63, 0.4); color: #C59B3F; border-radius: 4px;">Verified Pincodes</span>
                                </div>
                                <div class="small" style="font-size: 11.5px; color: #A0A0A5;">Pay cash or UPI to the delivery courier upon arrival. Valid for orders up to ₹5,000.</div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Order Summary -->
            <div class="col-lg-5">
                <div class="p-4 border card-luxury sticky-top" style="top: 80px; background: #141416; border-color: #2A2A2E !important; border-radius: 10px;">
                    <h5 class="fw-normal mb-3" style="font-family: var(--font-display, 'DM Serif Display', Georgia, serif); color: #F5F5F5;">Order Review (<?= (int)$cart['summary']['total_items'] ?> Items)</h5>

                    <!-- Mini items list -->
                    <div class="overflow-y-auto mb-3" style="max-height: 240px;">
                        <?php foreach ($cart['items'] as $item): ?>
                        <div class="d-flex align-items-center justify-content-between py-2 border-bottom" style="border-color: #2A2A2E !important;">
                            <div class="d-flex align-items-center">
                                <img src="<?= e($item['image_url']) ?>" width="44" height="44" class="border object-fit-contain me-2 p-1 rounded" style="border-color: #2A2A2E !important; background: #1A1A1C;" alt="<?= e($item['product_name']) ?>">
                                <div>
                                    <div class="small fw-semibold text-truncate" style="max-width: 180px; color: #F5F5F5;"><?= e($item['product_name']) ?></div>
                                    <div style="font-size: 11px; color: #A0A0A5;">Qty: <?= (int)$item['quantity'] ?> &bull; <?= e($item['variant_name']) ?></div>
                                </div>
                            </div>
                            <span class="small fw-semibold" style="color: #F5F5F5;"><?= format_currency($item['price'] * $item['quantity']) ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Breakdown table -->
                    <div class="d-flex justify-content-between mb-2 small">
                        <span style="color: #A0A0A5;">Subtotal (Excl. Tax):</span>
                        <span class="fw-semibold" style="color: #F5F5F5;"><?= format_currency($cart['summary']['subtotal']) ?></span>
                    </div>

                    <?php if ((float)$cart['summary']['discount_amount'] > 0): ?>
                    <div class="d-flex justify-content-between mb-2 small" style="color: #C59B3F;">
                        <span>Coupon Discount:</span>
                        <span class="fw-semibold">&minus; <?= format_currency($cart['summary']['discount_amount']) ?></span>
                    </div>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between mb-2 small">
                        <span style="color: #A0A0A5;">Shipping Fee:</span>
                        <span class="fw-semibold">
                            <?= ((float)$cart['summary']['shipping_fee'] == 0) ? '<span style="color: #2ECC71;">FREE</span>' : format_currency($cart['summary']['shipping_fee']) ?>
                        </span>
                    </div>

                    <div class="d-flex justify-content-between mb-3 small">
                        <span style="color: #A0A0A5;">Goods &amp; Services Tax (18% GST):</span>
                        <span class="fw-semibold" style="color: #F5F5F5;"><?= format_currency($cart['summary']['tax_amount']) ?></span>
                    </div>

                    <div class="d-flex justify-content-between py-3 border-top border-bottom mb-4" style="border-color: #2A2A2E !important;">
                        <span class="fs-5 fw-semibold" style="color: #F5F5F5;">Total Amount:</span>
                        <span class="fs-4 fw-bold" style="color: #F5F5F5; font-family: var(--font-ui, 'Inter', sans-serif);"><?= format_currency($cart['summary']['total_amount']) ?></span>
                    </div>

                    <button type="submit" id="btn-place-order" class="btn btn-welkar w-100" style="height: 48px; border-radius: 6px; font-size: 14px; font-weight: 600; display: flex; align-items: center; justify-content: center;">
                        Confirm &amp; Place Order <i class="bi bi-shield-lock-fill ms-2"></i>
                    </button>

                    <div class="small text-center mt-3" style="font-size: 11px; color: #A0A0A5;">
                        By confirming your order, you agree to Welkar's <a href="/page/terms-conditions" target="_blank" style="color: #C59B3F;">Terms of Sale</a> and <a href="/page/privacy-policy" target="_blank" style="color: #C59B3F;">Privacy Policy</a>.
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const optionCards = document.querySelectorAll('.payment-option-card');
    function updateCardStyles() {
        optionCards.forEach(card => {
            const radio = card.querySelector('input[type="radio"]');
            if (radio && radio.checked) {
                card.style.borderColor = '#E63946';
                card.style.backgroundColor = 'rgba(230, 57, 70, 0.08)';
                card.style.boxShadow = '0 0 12px rgba(230, 57, 70, 0.2)';
            } else {
                card.style.borderColor = '#2A2A2E';
                card.style.backgroundColor = '#1A1A1C';
                card.style.boxShadow = 'none';
            }
        });
    }

    optionCards.forEach(card => {
        card.addEventListener('click', function(e) {
            const radio = this.querySelector('input[type="radio"]');
            if (radio && !radio.checked) {
                radio.checked = true;
                updateCardStyles();
            }
        });
        const radio = card.querySelector('input[type="radio"]');
        if (radio) {
            radio.addEventListener('change', updateCardStyles);
        }
    });
    updateCardStyles();

    const checkoutForm = document.querySelector('form[action="/checkout/process"]');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function() {
            const btn = document.getElementById('btn-place-order');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Securing Your Order...';
            }
        });
    }
});
</script>
