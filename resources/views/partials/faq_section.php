<!-- ==========================================================================
     ATELIER COLLECTOR FREQUENTLY ASKED QUESTIONS COMPONENT (faq-sections.tsx)
     Reusable across Home Page and all Collection Pages
     ========================================================================== -->
<section class="py-3 py-md-5 bg-white border-top border-bottom faq-section reveal-on-scroll" id="faq-section" style="border-color: var(--color-border-default, #E1E7F0) !important;">
    <div class="container py-1 py-lg-4">
        <div class="row g-3 g-md-5 align-items-start justify-content-center">
            <!-- Left: Hero Imagery & Guarantee Badge (Desktop Column & Compact Mobile Strip) -->
            <div class="col-lg-5 col-md-6">
                <!-- Desktop Luxury Imagery -->
                <div class="faq-image-card position-relative card-luxury card-tilt-3d d-none d-md-block" data-card-7="true">
                    <img src="<?= e($faq_image ?? 'https://images.unsplash.com/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=800&q=80') ?>"
                         alt="Welkar Atelier Craftsmanship"
                         class="img-fluid rounded-4 shadow-sm w-100 object-fit-cover"
                         style="max-height: 480px; aspect-ratio: 4/5;"
                         loading="lazy">
                    <div class="faq-badge-overlay position-absolute bottom-0 start-0 m-3 p-3 rounded-3" style="background: rgba(10, 17, 40, 0.92); border: 1px solid rgba(197, 155, 63, 0.35); backdrop-filter: blur(10px); color: #fff; max-width: 85%;">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="bi bi-shield-check" style="color: var(--color-accent-600, #C59B3F);"></i>
                            <span class="fw-bold small">Official Atelier Guarantee</span>
                        </div>
                        <p class="small text-white-50 m-0" style="font-size: 11px;">100% verified authentic timepieces with registered serial warranty cards.</p>
                    </div>
                </div>

                <!-- Compact Mobile Guarantee Strip (d-md-none) -->
                <div class="d-md-none p-2 px-3 rounded-3 mb-2 d-flex align-items-center gap-2" style="background: var(--color-surface-50, #F5F7FB); border: 1px solid var(--color-border-default, #E1E7F0);">
                    <i class="bi bi-shield-check fs-5 text-warning"></i>
                    <div style="font-size: 11px; line-height: 1.3;" class="text-secondary">
                        <strong class="text-dark d-block">Official Atelier Guarantee</strong>
                        100% verified authentic watches with stamped serial cards.
                    </div>
                </div>
            </div>

            <!-- Right: Content & Accordion -->
            <div class="col-lg-7 col-md-6">
                <?php if (!empty($faq_badge)): ?>
                <p class="text-uppercase fw-bold mb-1" style="font-size: 10px; letter-spacing: 1.5px; color: var(--color-accent-600, #C59B3F);">
                    <?= e($faq_badge) ?>
                </p>
                <?php endif; ?>
                <h2 class="display-6 fw-normal mb-1 mb-md-2" style="font-family: var(--font-display, 'DM Serif Display', Georgia, serif); color: var(--color-brand-950, #0A1128); font-size: clamp(1.35rem, 3.5vw, 2.2rem);">
                    <?= e($faq_title ?? "Looking for answers?") ?>
                </h2>
                <p class="small mb-3 mb-md-4 pb-2 border-bottom" style="color: var(--color-text-secondary, #4A5568); border-color: var(--color-border-default, #E1E7F0) !important; font-size: 12px; line-height: 1.5;">
                    <?= e($faq_subtitle ?? "Everything you need to know about serial authentication, pan-India insured COD transit, 30M hydrostatic submersion testing, and atelier care.") ?>
                </p>

                <!-- Accordion Items -->
                <div class="faq-accordion-container" id="faq-accordion">
                    <?php
                    $masterFaqs = [
                        [
                            'q' => 'How does Pan-India Cash on Delivery (COD) & verification work?',
                            'a' => 'We offer complimentary insured Cash on Delivery across all Indian pin codes. When the courier arrives, you can inspect our tamper-evident holographic seal before completing payment via Cash or UPI QR code.'
                        ],
                        [
                            'q' => 'Are all timepieces covered under authentic warranty?',
                            'a' => 'Yes. Every Welkar timepiece arrives with an official 1-Year Mechanism & Hydrostatic Seal Warranty with matching stamped serial credentials recorded in our atelier database.'
                        ],
                        [
                            'q' => 'What water resistance & hydrostatic depth standards are tested?',
                            'a' => 'Every unit is individually pressure-chamber tested to minimum 30M (3 ATM) hydrostatic standards with dual silicone O-ring crowns, ensuring complete resilience against rain, accidental submersions, and hand washing.'
                        ],
                        [
                            'q' => 'Can I customize strap sizing or get styling concierge advice?',
                            'a' => 'Yes! Our 24/7 Welkar Support Concierge Desk (available via the bottom-right Support Concierge button) provides instant link-removal guides, complementary wrist adjustments, and personalized styling recommendations for gifting or personal collections.'
                        ],
                        [
                            'q' => 'What is your doorstep return and exchange policy?',
                            'a' => 'We maintain a hassle-free 7-day doorstep return and exchange guarantee. If your timepiece arrives defective or doesn’t meet your expectations, our courier partner will collect it directly from your doorstep.'
                        ]
                    ];
                    $activeFaqs = (!empty($faqs) && is_array($faqs)) ? $faqs : $masterFaqs;
                    ?>
                    <?php foreach ($activeFaqs as $idx => $faq): ?>
                    <div class="faq-accordion-item border-bottom py-2 py-md-3 <?= $idx === 0 ? 'is-open' : '' ?>" data-faq-index="<?= $idx ?>" style="border-color: var(--color-border-default, #E1E7F0) !important;">
                        <button type="button" class="faq-question-row d-flex align-items-center justify-content-between w-100 bg-transparent border-0 text-start p-0" id="faq-heading-<?= $idx ?>" aria-expanded="<?= $idx === 0 ? 'true' : 'false' ?>" aria-controls="faq-collapse-<?= $idx ?>">
                            <h3 class="faq-question-title mb-0" style="font-size: clamp(13px, 2.8vw, 15px); font-weight: 600; color: var(--color-brand-950, #0A1128); font-family: var(--font-ui, 'Inter', sans-serif); line-height: 1.4;">
                                <?= htmlspecialchars($faq['q'] ?? $faq['question'] ?? '') ?>
                            </h3>
                            <div class="d-flex align-items-center gap-1 flex-shrink-0 ms-2">
                                <span class="badge rounded-pill bg-light text-secondary border faq-toggle-label d-none d-sm-inline" style="font-size: 10px; font-weight: 500;">
                                    <?= $idx === 0 ? 'Close' : 'Read' ?>
                                </span>
                                <div class="faq-chevron-icon">
                                    <svg width="16" height="16" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" class="faq-chevron-svg">
                                        <path d="m4.5 7.2 3.793 3.793a1 1 0 0 0 1.414 0L13.5 7.2" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                        </button>
                        <div class="faq-answer-collapse" id="faq-collapse-<?= $idx ?>" role="region" aria-labelledby="faq-heading-<?= $idx ?>">
                            <div class="faq-answer-inner pt-2 pt-md-3 text-muted" style="font-size: 12.5px; line-height: 1.6; color: var(--color-text-secondary, #4A5568) !important;">
                                <?= htmlspecialchars($faq['a'] ?? $faq['answer'] ?? '') ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Self-Contained Accordion Interaction Script -->
<script>
(function() {
    function initFaqAccordion() {
        const faqSection = document.getElementById('faq-section');
        if (!faqSection) return;
        const faqItems = faqSection.querySelectorAll('.faq-accordion-item');
        faqItems.forEach(item => {
            const qRow = item.querySelector('.faq-question-row');
            if (qRow && !qRow.dataset.faqBound) {
                qRow.dataset.faqBound = 'true';
                qRow.addEventListener('click', () => {
                    const isOpen = item.classList.contains('is-open');
                    faqItems.forEach(other => {
                        other.classList.remove('is-open');
                        const otherBtn = other.querySelector('.faq-question-row');
                        if (otherBtn) otherBtn.setAttribute('aria-expanded', 'false');
                    });
                    if (!isOpen) {
                        item.classList.add('is-open');
                        qRow.setAttribute('aria-expanded', 'true');
                    }
                });
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initFaqAccordion);
    } else {
        initFaqAccordion();
    }
})();
</script>
