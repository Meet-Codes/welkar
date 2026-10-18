<!-- Welkar Slide-Up Bottom Sheet Cart Drawer -->
<div id="welkar-cart-drawer" class="d-none" style="font-family: var(--font-ui, 'Inter', sans-serif);">
    <!-- Backdrop Overlay -->
    <div id="cart-drawer-backdrop" class="position-fixed inset-0 w-100 h-100" style="background: rgba(10, 13, 12, 0.65); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); z-index: 1060; opacity: 0; transition: opacity 0.3s ease; top: 0; left: 0;"></div>

    <!-- Slide-Up Bottom Sheet Panel -->
    <div id="cart-drawer-panel" class="position-fixed start-0 end-0 mx-auto shadow-lg bg-white" style="bottom: 0; max-width: 500px; width: 100%; border-radius: 20px 20px 0 0; z-index: 1070; transform: translateY(100%); transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1); border-top: 2px solid #C59B3F; padding-bottom: max(16px, env(safe-area-inset-bottom));">
        
        <!-- Drag Handle / Grab Bar -->
        <div class="d-flex justify-content-center pt-2 pb-1">
            <span class="d-inline-block rounded-pill bg-secondary opacity-25" style="width: 36px; height: 4px;"></span>
        </div>

        <!-- Header -->
        <div class="px-4 py-2 d-flex align-items-center justify-content-between border-bottom" style="border-color: #EBF0F7 !important;">
            <div class="d-flex align-items-center gap-2">
                <span class="rounded-circle d-flex align-items-center justify-content-center bg-success text-white" style="width: 24px; height: 24px; font-size: 13px;">
                    <i class="bi bi-check-lg"></i>
                </span>
                <h6 class="mb-0 fw-bold" style="font-size: 14.5px; color: #171C19;">Added to Shopping Bag</h6>
            </div>
            <button type="button" id="cart-drawer-close" class="btn btn-sm text-secondary p-1 border-0 rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; background: #F5F7FB;" aria-label="Close Shopping Bag">
                <i class="bi bi-x-lg" style="font-size: 14px;"></i>
            </button>
        </div>

        <div class="p-4">
            <!-- Product Preview Card -->
            <div class="d-flex align-items-center gap-3 p-3 rounded-3 mb-3" style="background: #F9F9FB; border: 1px solid #ECEEF3;">
                <img id="cart-drawer-img" src="/assets/images/placeholder-watch.png" width="56" height="56" class="rounded object-fit-contain bg-white border p-1" alt="Watch">
                <div class="flex-grow-1 overflow-hidden">
                    <div id="cart-drawer-title" class="fw-bold small text-truncate text-dark mb-1">Welkar Timepiece</div>
                    <div class="d-flex align-items-center gap-2">
                        <span id="cart-drawer-variant" class="text-muted" style="font-size: 11.5px;">Standard Edition</span>
                        <span class="text-muted" style="font-size: 11px;">&bull; Qty: <strong id="cart-drawer-qty">1</strong></span>
                    </div>
                </div>
                <div id="cart-drawer-price" class="fw-bold fs-6 text-dark flex-shrink-0">₹0</div>
            </div>

            <!-- Free Shipping Progress Tracker -->
            <div class="mb-4 p-3 rounded-3" style="background: #F4F8F5; border: 1px solid #D6EAD9;">
                <div class="d-flex align-items-center justify-content-between small mb-1">
                    <span id="cart-drawer-shipping-text" class="fw-semibold text-success" style="font-size: 12px;">
                        <i class="bi bi-truck me-1"></i>Calculating Insured Shipping...
                    </span>
                    <span id="cart-drawer-shipping-status" class="fw-bold" style="font-size: 11px; color: #2D6A4F;">FREE SHIPPING</span>
                </div>
                <div class="progress" style="height: 6px; background-color: #E2EDE4; border-radius: 4px;">
                    <div id="cart-drawer-shipping-bar" class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: 75%; border-radius: 4px;"></div>
                </div>
            </div>

            <!-- Subtotal & Savings Row -->
            <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom" style="border-color: #EBF0F7 !important;">
                <div>
                    <div class="text-muted small" style="font-size: 12px;">Bag Subtotal (<span id="cart-drawer-count">1</span> items)</div>
                    <div class="text-success fw-semibold" style="font-size: 11px;"><i class="bi bi-shield-check me-1"></i>Complimentary Transit Insurance Included</div>
                </div>
                <div id="cart-drawer-subtotal" class="fs-5 fw-bold" style="font-family: var(--font-ui, 'Inter', sans-serif); color: #0D1527;">₹0</div>
            </div>

            <!-- Instant Dual Action Buttons -->
            <div class="d-flex flex-column gap-2">
                <a href="/checkout" class="btn btn-welkar w-100 py-3 fw-bold d-flex align-items-center justify-content-center gap-2 shadow-sm" style="border-radius: 8px; font-size: 14.5px; height: 50px;">
                    <i class="bi bi-lightning-charge-fill text-warning"></i>
                    <span>Proceed to Checkout (UPI / Cards / COD)</span>
                    <i class="bi bi-arrow-right ms-1"></i>
                </a>
                <button type="button" id="cart-drawer-continue" class="btn btn-light w-100 py-2 text-secondary fw-semibold border" style="border-radius: 8px; font-size: 13.5px; background: #FFFFFF; border-color: #DDD9D0 !important; height: 44px;">
                    <span>Continue Exploring Timepieces</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Cart Drawer Controller Script -->
<script>
(function() {
    const drawer = document.getElementById('welkar-cart-drawer');
    const backdrop = document.getElementById('cart-drawer-backdrop');
    const panel = document.getElementById('cart-drawer-panel');
    const btnClose = document.getElementById('cart-drawer-close');
    const btnContinue = document.getElementById('cart-drawer-continue');

    function openCartDrawer(data) {
        if (!drawer || !backdrop || !panel) return;

        // Populate elements
        if (data.item) {
            document.getElementById('cart-drawer-img').src = data.item.image || '/assets/images/placeholder-watch.png';
            document.getElementById('cart-drawer-title').textContent = data.item.title || 'Welkar Timepiece';
            document.getElementById('cart-drawer-variant').textContent = data.item.variant || 'Standard Edition';
            document.getElementById('cart-drawer-qty').textContent = data.item.quantity || '1';
            document.getElementById('cart-drawer-price').textContent = '₹' + Number(data.item.price || 0).toLocaleString('en-IN');
        }

        const subtotal = Number(data.subtotal || (data.cart ? data.cart.summary.taxable_amount : 0));
        const totalItems = data.total_items || (data.cart ? data.cart.summary.total_items : 1);
        
        document.getElementById('cart-drawer-subtotal').textContent = '₹' + subtotal.toLocaleString('en-IN');
        document.getElementById('cart-drawer-count').textContent = totalItems;

        // Shipping Progress: Free shipping threshold ₹1,499
        const threshold = 1499;
        const textElem = document.getElementById('cart-drawer-shipping-text');
        const barElem = document.getElementById('cart-drawer-shipping-bar');
        const statusElem = document.getElementById('cart-drawer-shipping-status');

        if (subtotal >= threshold) {
            textElem.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i><strong>Unlocked Free Insured Delivery!</strong>';
            barElem.style.width = '100%';
            statusElem.textContent = 'ELIGIBLE';
        } else {
            const diff = threshold - subtotal;
            const pct = Math.min(100, Math.round((subtotal / threshold) * 100));
            textElem.innerHTML = `<i class="bi bi-truck me-1"></i>Add <strong>₹${diff.toLocaleString('en-IN')}</strong> more for Free Shipping`;
            barElem.style.width = pct + '%';
            statusElem.textContent = pct + '%';
        }

        // Show drawer
        drawer.classList.remove('d-none');
        requestAnimationFrame(() => {
            backdrop.style.opacity = '1';
            panel.style.transform = 'translateY(0)';
        });
    }

    function closeCartDrawer() {
        if (!drawer || !backdrop || !panel) return;
        backdrop.style.opacity = '0';
        panel.style.transform = 'translateY(100%)';
        setTimeout(() => {
            drawer.classList.add('d-none');
        }, 320);
    }

    if (btnClose) btnClose.addEventListener('click', closeCartDrawer);
    if (btnContinue) btnContinue.addEventListener('click', closeCartDrawer);
    if (backdrop) backdrop.addEventListener('click', closeCartDrawer);

    // Global listener for all Add to Bag forms
    document.addEventListener('submit', function(e) {
        const form = e.target.closest('form[action="/cart/add"]');
        if (!form) return;

        // If Buy Now button was clicked, let default form submit take them to checkout
        if (e.submitter && e.submitter.name === 'buy_now') {
            return;
        }

        e.preventDefault();
        const submitBtn = form.querySelector('button[type="submit"]:not([name="buy_now"])');
        let oldHtml = '';
        if (submitBtn) {
            oldHtml = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Adding...';
        }

        const formData = new FormData(form);

        fetch('/cart/add', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = oldHtml;
            }

            if (data.success) {
                // Update badge counters
                const bottomBadge = document.getElementById('bottom-cart-count');
                const headerBadge = document.getElementById('header-cart-badge');
                if (bottomBadge) bottomBadge.textContent = data.cart_count;
                if (headerBadge) headerBadge.textContent = data.cart_count;

                // Extract product title, img and price from form context if available
                let prodTitle = document.querySelector('h1')?.textContent || 'Welkar Timepiece';
                let prodImg = document.querySelector('.pdp-main-image, .stitch-card-thumb-wrap img')?.src || '';
                let prodPrice = document.getElementById('display-price')?.textContent?.replace(/[^\d]/g, '') || 3499;

                openCartDrawer({
                    item: {
                        title: prodTitle.trim(),
                        image: prodImg,
                        variant: 'Verified In Stock',
                        price: prodPrice,
                        quantity: formData.get('quantity') || 1
                    },
                    subtotal: data.cart ? data.cart.summary.taxable_amount : prodPrice,
                    total_items: data.cart_count,
                    cart: data.cart
                });
            } else {
                alert(data.message || 'Could not add to bag.');
            }
        })
        .catch(() => {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = oldHtml;
            }
            form.submit(); // fallback
        });
    });

    window.openWelkarCartDrawer = openCartDrawer;

    // Global Instant 1-Tap Quick Add Function for Product Cards
    window.quickAddToCart = function(variantId, title, price, img, btn) {
        let oldHtml = '';
        if (btn) {
            btn.disabled = true;
            oldHtml = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" style="width: 12px; height: 12px;" role="status"></span>';
        }

        const formData = new FormData();
        formData.append('variant_id', variantId);
        formData.append('quantity', 1);

        fetch('/cart/add', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-check-lg text-success"></i>';
                setTimeout(() => { btn.innerHTML = oldHtml; }, 1800);
            }

            if (data.success) {
                const bottomBadge = document.getElementById('bottom-cart-count');
                const headerBadge = document.getElementById('header-cart-badge');
                if (bottomBadge) bottomBadge.textContent = data.cart_count;
                if (headerBadge) headerBadge.textContent = data.cart_count;

                openCartDrawer({
                    item: {
                        title: title || 'Welkar Timepiece',
                        image: img || '/assets/images/placeholder-watch.png',
                        variant: 'Standard Edition',
                        price: price || 0,
                        quantity: 1
                    },
                    subtotal: data.cart ? data.cart.summary.taxable_amount : price,
                    total_items: data.cart_count,
                    cart: data.cart
                });
            } else {
                alert(data.message || 'Could not add to bag.');
            }
        })
        .catch(() => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = oldHtml;
            }
            location.href = '/cart';
        });
    };
})();
</script>
