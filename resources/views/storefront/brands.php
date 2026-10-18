<div class="container py-5">
    <div class="text-center mb-5">
        <span class="badge mb-2 px-3 py-1 text-uppercase" style="background: rgba(197, 155, 63, 0.15); color: #C59B3F; border: 1px solid rgba(197, 155, 63, 0.3); font-size: 11px; letter-spacing: 1px;">
            Horological Partners
        </span>
        <h1 class="display-6 fw-bold mt-1" style="font-family: var(--font-display, serif); color: #F5F5F5;">Our Brand Portfolio</h1>
        <p class="mx-auto" style="max-width: 600px; color: #A0A0A5;">
            Explore our curated roster of authorized timepiece makers, from heritage classic watch houses to modern precision chronographs and Welkar's exclusive private label.
        </p>
    </div>

    <div class="row g-4">
        <?php foreach ($brands as $b): ?>
            <div class="col-6 col-md-4 col-lg-3">
                <a href="/brand/<?= e($b['slug']) ?>" class="card h-100 border text-decoration-none card-luxury text-center p-4 transition-all" style="background: #141416; border-color: #2A2A2E !important; border-radius: 10px;">
                    <div class="d-flex align-items-center justify-content-center mx-auto mb-3" style="height: 70px;">
                        <?php if (!empty($b['logo_url'])): ?>
                            <img src="<?= e($b['logo_url']) ?>" alt="<?= e($b['name']) ?>" style="max-height: 55px; max-width: 140px; object-fit: contain;">
                        <?php else: ?>
                            <span class="fs-4 fw-bold font-display" style="font-family: var(--font-display, serif); color: #F5F5F5;">
                                <?= e($b['name']) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <h5 class="fw-bold mb-1" style="font-family: var(--font-display, serif); color: #F5F5F5;"><?= e($b['name']) ?></h5>
                    <span class="badge border px-2 py-1 small mb-2 d-inline-block" style="background: #1A1A1C; border-color: #2A2A2E; color: #C59B3F;">
                        <?= (int)($b['product_count'] ?? 0) ?> Models
                    </span>
                    <?php if (!empty($b['description'])): ?>
                        <p class="small mb-0 line-clamp-2" style="color: #A0A0A5;"><?= e($b['description']) ?></p>
                    <?php endif; ?>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
