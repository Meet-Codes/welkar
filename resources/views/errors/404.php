<div class="container py-5 my-md-5">
    <div class="row justify-content-center text-center">
        <div class="col-lg-8 col-xl-7">
            <div class="display-1 fw-bold opacity-20 mb-2" style="font-family: var(--font-display, 'Playfair Display', 'DM Serif Display', serif); font-size: calc(3.5rem + 3vw); color: var(--color-border-divider, #2E2E2E); letter-spacing: 4px;">404</div>
            <h1 class="h2 fw-normal mb-3" style="font-family: var(--font-display, 'Playfair Display', 'DM Serif Display', serif); color: var(--color-heading-ivory, #F5F5F5);">Timepiece or Page Not Found</h1>
            <p class="mb-4 lead" style="font-size: 16px; color: var(--color-body-text, #A0A0A0); line-height: 1.7;">
                The horological reference or page you requested could not be located. It may have been archived, renamed, or temporarily relocated in our atelier vault.
            </p>

            <!-- Search Form -->
            <form action="/catalog" method="GET" class="mb-5 mx-auto" style="max-width: 480px;" role="search">
                <div class="input-group shadow">
                    <span class="input-group-text border-end-0" style="background: var(--color-bg-secondary, #1A1A1C); border-color: var(--color-border-divider, #2E2E2E); color: var(--color-muted-text, #737373);"><i class="bi bi-search"></i></span>
                    <input type="search" name="q" class="form-control border-start-0 ps-0" placeholder="Search timepieces by name, caliber, or style..." aria-label="Search timepieces" required style="background: var(--color-bg-secondary, #1A1A1C); border-color: var(--color-border-divider, #2E2E2E); color: var(--color-heading-ivory, #F5F5F5); height: 48px;">
                    <button class="btn btn-welkar px-4" type="submit" style="height: 48px;">Search</button>
                </div>
            </form>

            <!-- Curated Destination Links -->
            <div class="p-4 rounded-4 mb-4" style="background: var(--color-bg-secondary, #1A1A1C); border: 1px solid var(--color-border-divider, #2E2E2E);">
                <div class="text-uppercase fw-semibold mb-3 small" style="letter-spacing: 1.5px; color: var(--color-champagne-highlight, #D4AF7C); font-size: 11px;">Explore Curated Atelier Collections</div>
                <div class="row g-2 g-sm-3 text-start">
                    <div class="col-6 col-sm-3">
                        <a href="/collections/mens-watches" class="card h-100 text-decoration-none border shadow-sm p-3 rounded-3 text-center transition-all" style="background: var(--color-surface-card, #242424); border-color: var(--color-border-divider, #2E2E2E) !important;">
                            <i class="bi bi-watch fs-4 mb-2" style="color: var(--color-champagne-highlight, #D4AF7C);"></i>
                            <div class="fw-semibold small" style="color: var(--color-heading-ivory, #F5F5F5);">Men's Watches</div>
                        </a>
                    </div>
                    <div class="col-6 col-sm-3">
                        <a href="/collections/womens-watches" class="card h-100 text-decoration-none border shadow-sm p-3 rounded-3 text-center transition-all" style="background: var(--color-surface-card, #242424); border-color: var(--color-border-divider, #2E2E2E) !important;">
                            <i class="bi bi-gem fs-4 mb-2" style="color: var(--color-champagne-highlight, #D4AF7C);"></i>
                            <div class="fw-semibold small" style="color: var(--color-heading-ivory, #F5F5F5);">Women's Watches</div>
                        </a>
                    </div>
                    <div class="col-6 col-sm-3">
                        <a href="/collections/unisex-minimalist" class="card h-100 text-decoration-none border shadow-sm p-3 rounded-3 text-center transition-all" style="background: var(--color-surface-card, #242424); border-color: var(--color-border-divider, #2E2E2E) !important;">
                            <i class="bi bi-circle fs-4 mb-2" style="color: var(--color-champagne-highlight, #D4AF7C);"></i>
                            <div class="fw-semibold small" style="color: var(--color-heading-ivory, #F5F5F5);">Minimalist</div>
                        </a>
                    </div>
                    <div class="col-6 col-sm-3">
                        <a href="/collections/offers" class="card h-100 text-decoration-none border shadow-sm p-3 rounded-3 text-center transition-all" style="background: var(--color-surface-card, #242424); border-color: var(--color-border-divider, #2E2E2E) !important;">
                            <i class="bi bi-tag-fill fs-4 mb-2" style="color: var(--color-accent-deep-red, #E63946);"></i>
                            <div class="fw-semibold small" style="color: var(--color-heading-ivory, #F5F5F5);">Special Offers</div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <a href="/" class="btn btn-welkar px-4 py-2 fw-semibold">
                    <i class="bi bi-house-door me-2"></i>Return to Homepage
                </a>
                <a href="/catalog" class="btn btn-outline-light px-4 py-2 fw-semibold" style="border-color: var(--color-border-divider, #2E2E2E); color: var(--color-heading-ivory, #F5F5F5);">
                    <i class="bi bi-grid me-2"></i>Browse Full Catalog
                </a>
                <a href="/about#contact" class="btn btn-link text-decoration-none py-2" style="color: var(--color-champagne-highlight, #D4AF7C);">
                    <i class="bi bi-headset me-1"></i>Contact Concierge
                </a>
            </div>
        </div>
    </div>
</div>
