<?php
/**
 * Welkar Horological Atelier — About Us / Company Profile
 * Structured with 12 Horological Storytelling Sections
 */
?>
<!-- SEO JSON-LD Breadcrumbs & Organization Schema rendered by Layout -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "AboutPage",
  "name": "About WELKAR — Precision, Heritage, and Trust—Since 2002",
  "description": "Discover the story of WELKAR, founded in 2002 by Mr. Sumit Singh and Mr. Nevil Sharma on precision, heritage, and customer trust.",
  "url": "https://www.welkar.in/about",
  "mainEntity": {
    "@type": "Organization",
    "name": "WELKAR",
    "foundingDate": "2002",
    "founders": [
      {
        "@type": "Person",
        "name": "Mr. Sumit Singh",
        "jobTitle": "Co-Founder",
        "description": "Creative vision, dial aesthetics, and design detailing."
      },
      {
        "@type": "Person",
        "name": "Mr. Nevil Sharma",
        "jobTitle": "Co-Founder",
        "description": "Structural balance, mechanical perspective, and engineering."
      }
    ],
    "slogan": "Precision, Heritage, and Trust—Since 2002.",
    "knowsAbout": ["Horology", "Watches", "Chronographs", "Automatic Timepieces", "Watchmaking"]
  }
}
</script>

<div class="about-editorial-wrapper" style="background-color: var(--color-bg-primary, #0B0B0C); color: var(--color-body-text, #A0A0A0);">

    <!-- =========================================================================
         SECTION 1 — HERO / COMPANY INTRODUCTION
         ========================================================================= -->
    <section class="py-5 border-bottom reveal-on-scroll" style="background: var(--color-bg-primary, #0B0B0C); border-color: var(--color-border-divider, #2E2E2E) !important;">
        <div class="container py-lg-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <span class="text-uppercase fw-semibold mb-2 d-inline-block small" style="letter-spacing: 2px; color: var(--color-champagne-highlight, #D4AF7C); font-size: 11px;">HERITAGE ATELIER &bull; EST. 2002</span>
                    <h1 class="display-4 fw-normal mb-3" style="font-family: var(--font-display, 'Playfair Display', 'DM Serif Display', Georgia, serif); color: var(--color-heading-ivory, #F5F5F5); line-height: 1.15; letter-spacing: -0.02em;">
                        <?= e($profile['hero_title'] ?? 'Precision, Heritage, and Trust—Since 2002.') ?>
                    </h1>

                    <p class="lead mb-4" style="font-family: var(--font-ui, 'Inter', sans-serif); color: var(--color-body-text, #A0A0A0); font-size: 1.1rem; line-height: 1.8; max-width: 620px;">
                        <?= nl2br(e($profile['hero_intro'] ?? 'WELKAR is a watch brand founded in 2002, shaped by an appreciation for design, technical thinking, and the importance of trust. Our story began with two founders bringing complementary perspectives together, united by a vision to build a brand around precision, heritage, and lasting style.')) ?>
                    </p>

                    <div class="d-flex flex-wrap align-items-center gap-3">
                        <a href="#our-story" class="btn btn-welkar">
                            <span>Read Our Story</span>
                            <i class="bi bi-arrow-down ms-1"></i>
                        </a>
                        <a href="/catalog" class="btn btn-outline-light" style="border-color: var(--color-border-divider, #2E2E2E); color: var(--color-heading-ivory, #F5F5F5); font-weight: 500; font-size: 14px; padding: 12px 24px; border-radius: var(--radius-sm, 6px);">
                            <span>Explore Collections</span>
                            <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="p-4 p-md-5 rounded-3 border" style="background: var(--color-surface-card, #242424); border-color: var(--color-border-divider, #2E2E2E) !important;">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; border-radius: 50%; background: var(--color-bg-secondary, #1A1A1C); color: var(--color-champagne-highlight, #D4AF7C); border: 1px solid var(--color-border-divider, #2E2E2E);">
                                <i class="bi bi-shield-check fs-4"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold m-0" style="color: var(--color-heading-ivory, #F5F5F5);">Brand Pillar Guarantee</h6>
                                <small class="text-muted" style="font-size: 12px;">Authenticated Watchmaking Perspective</small>
                            </div>
                        </div>

                        <div class="vstack gap-3" style="font-size: 13.5px; color: var(--color-body-text, #A0A0A0); font-family: var(--font-ui, 'Inter', sans-serif);">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-check2 text-success mt-1"></i>
                                <span><strong class="text-light">Creative &amp; Technical Union:</strong> Founded by Mr. Sumit Singh &amp; Mr. Nevil Sharma.</span>
                            </div>
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-check2 text-success mt-1"></i>
                                <span><strong class="text-light">Verified Heritage:</strong> Continuous commitment to customer trust since 2002.</span>
                            </div>
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-check2 text-success mt-1"></i>
                                <span><strong class="text-light">Transparent Specifications:</strong> Clear case dimensions, movements, and honest materials.</span>
                            </div>
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-check2 text-success mt-1"></i>
                                <span><strong class="text-light">Customer-First Policies:</strong> Honest assistance, insured transit, and official warranties.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =========================================================================
         SECTION 2 — OUR STORY
         ========================================================================= -->
    <section class="py-5 border-bottom reveal-on-scroll" id="our-story" style="background: var(--color-bg-secondary, #141416); border-color: var(--color-border-divider, #2E2E2E) !important;">
        <div class="container py-lg-4">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="text-center mb-4">
                        <h2 class="display-6 fw-normal mt-1 mb-3" style="font-family: var(--font-display, 'Playfair Display', 'DM Serif Display', Georgia, serif); color: var(--color-heading-ivory, #F5F5F5);">
                            <?= e($profile['story_title'] ?? 'Our Story') ?>
                        </h2>
                        <div class="mx-auto" style="width: 50px; height: 2px; background: var(--color-accent-deep-red, #E63946);"></div>
                    </div>

                    <div class="p-4 p-md-5 border rounded-3 shadow-sm" style="background: var(--color-surface-card, #242424); border-color: var(--color-border-divider, #2E2E2E) !important;">
                        <p class="lead mb-4" style="color: var(--color-heading-ivory, #F5F5F5); font-size: 1.15rem; line-height: 1.8; font-weight: 500;">
                            <?= nl2br(e($profile['story_intro'])) ?>
                        </p>
                        <div class="story-body" style="color: var(--color-body-text, #A0A0A0); font-size: 15px; line-height: 1.9; font-family: var(--font-ui, 'Inter', sans-serif);">
                            <?= nl2br(e($profile['story_content'])) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =========================================================================
         SECTION 3 — MEET OUR FOUNDERS
         ========================================================================= -->
    <section class="py-5 border-bottom reveal-on-scroll" style="background: var(--color-bg-primary, #0B0B0C); border-color: var(--color-border-divider, #2E2E2E) !important;">
        <div class="container py-lg-4">
            <div class="text-center max-w-2xl mx-auto mb-5">
                <h2 class="display-6 fw-normal mt-1 mb-2" style="font-family: var(--font-display, 'Playfair Display', 'DM Serif Display', Georgia, serif); color: var(--color-heading-ivory, #F5F5F5);">
                    Meet Our Founders
                </h2>
                <p class="small mb-0" style="max-width: 600px; margin: 0 auto; color: var(--color-muted-text, #737373);">
                    Two complementary perspectives united by a shared dedication to design expression, mechanical balance, and customer trust.
                </p>
            </div>

            <div class="row g-4 justify-content-center">
                <?php foreach ($founders as $founder): ?>
                <div class="col-md-6 col-lg-5">
                    <div class="card h-100 p-4 p-lg-5 border rounded-3 shadow-sm" style="border-color: var(--color-border-divider, #2E2E2E) !important; background: var(--color-surface-card, #242424);">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <?php if (!empty($founder['image_url'])): ?>
                                <img src="<?= e($founder['image_url']) ?>" alt="<?= e($founder['image_alt'] ?? $founder['name']) ?>" class="rounded-circle object-fit-cover border" style="width: 72px; height: 72px; border-color: var(--color-champagne-highlight, #D4AF7C) !important;">
                            <?php else: ?>
                                <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 68px; height: 68px; background: var(--color-bg-secondary, #1A1A1C); color: var(--color-champagne-highlight, #D4AF7C); font-family: var(--font-display, 'Playfair Display', Georgia, serif); font-size: 24px; border: 2px solid var(--color-champagne-highlight, #D4AF7C);">
                                    <?= e(strtoupper(substr(trim(str_replace('Mr. ', '', $founder['name'])), 0, 1))) ?>
                                </div>
                            <?php endif; ?>
                            <div>
                                <h4 class="h5 fw-bold mb-1" style="font-family: var(--font-display, 'Playfair Display', 'DM Serif Display', Georgia, serif); color: var(--color-heading-ivory, #F5F5F5);">
                                    <?= e($founder['name']) ?>
                                </h4>
                                <div class="badge rounded-pill fw-semibold" style="background: rgba(212, 175, 124, 0.12); color: var(--color-champagne-highlight, #D4AF7C); border: 1px solid rgba(212, 175, 124, 0.25); font-size: 11px; padding: 4px 10px;">
                                    <?= e($founder['perspective']) ?>
                                </div>
                            </div>
                        </div>

                        <div class="founder-bio pt-2 border-top" style="border-color: var(--color-border-divider, #2E2E2E) !important; color: var(--color-body-text, #A0A0A0); font-size: 14.5px; line-height: 1.8; font-family: var(--font-ui, 'Inter', sans-serif);">
                            <?= nl2br(e($founder['bio'])) ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- =========================================================================
         SECTION 4 — OUR PHILOSOPHY
         ========================================================================= -->
    <section class="py-5 border-bottom reveal-on-scroll" id="philosophy" style="background: var(--color-bg-secondary, #141416); color: #FFFFFF; border-color: var(--color-border-divider, #2E2E2E) !important;">
        <div class="container py-lg-4">
            <div class="text-center max-w-2xl mx-auto mb-5">
                <h2 class="display-6 fw-normal mt-1 mb-2" style="font-family: var(--font-display, 'Playfair Display', 'DM Serif Display', Georgia, serif); color: var(--color-heading-ivory, #F5F5F5);">
                    <?= e($profile['philosophy_title'] ?? 'Our Philosophy') ?>
                </h2>
                <p class="small mb-0" style="max-width: 640px; margin: 0 auto; line-height: 1.7; color: var(--color-muted-text, #737373);">
                    <?= e($profile['philosophy_intro'] ?? 'Guided by a genuine passion for watchmaking, our philosophy centers on timeless elegance, mechanical reliability, rigorous attention to detail, and collaboration with skilled craftspeople.') ?>
                </p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="p-4 rounded-3 h-100" style="background: var(--color-surface-card, #242424); border: 1px solid var(--color-border-divider, #2E2E2E);">
                        <div class="mb-3" style="color: var(--color-champagne-highlight, #D4AF7C); font-size: 26px;">
                            <i class="bi bi-bullseye"></i>
                        </div>
                        <h5 class="fw-bold mb-2" style="font-family: var(--font-display, 'Playfair Display', Georgia, serif); color: var(--color-heading-ivory, #F5F5F5);">Precision</h5>
                        <p class="small mb-0" style="line-height: 1.7; color: var(--color-body-text, #A0A0A0);">
                            Thoughtful presentation, rigorous attention to proportion and dial detailing, and transparent technical specifications.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="p-4 rounded-3 h-100" style="background: var(--color-surface-card, #242424); border: 1px solid var(--color-border-divider, #2E2E2E);">
                        <div class="mb-3" style="color: var(--color-champagne-highlight, #D4AF7C); font-size: 26px;">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                        <h5 class="fw-bold mb-2" style="font-family: var(--font-display, 'Playfair Display', Georgia, serif); color: var(--color-heading-ivory, #F5F5F5);">Heritage</h5>
                        <p class="small mb-0" style="line-height: 1.7; color: var(--color-body-text, #A0A0A0);">
                            Our journey began in 2002. Respecting our origins shapes our present identity and anchors our pursuit of enduring quality.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="p-4 rounded-3 h-100" style="background: var(--color-surface-card, #242424); border: 1px solid var(--color-border-divider, #2E2E2E);">
                        <div class="mb-3" style="color: var(--color-champagne-highlight, #D4AF7C); font-size: 26px;">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h5 class="fw-bold mb-2" style="font-family: var(--font-display, 'Playfair Display', Georgia, serif); color: var(--color-heading-ivory, #F5F5F5);">Trust</h5>
                        <p class="small mb-0" style="line-height: 1.7; color: var(--color-body-text, #A0A0A0);">
                            Earned daily through truthful descriptions, transparent pricing, insured transit, and dependable customer service.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="p-4 rounded-3 h-100" style="background: var(--color-surface-card, #242424); border: 1px solid var(--color-border-divider, #2E2E2E);">
                        <div class="mb-3" style="color: var(--color-champagne-highlight, #D4AF7C); font-size: 26px;">
                            <i class="bi bi-compass"></i>
                        </div>
                        <h5 class="fw-bold mb-2" style="font-family: var(--font-display, 'Playfair Display', Georgia, serif); color: var(--color-heading-ivory, #F5F5F5);">Timelessness</h5>
                        <p class="small mb-0" style="line-height: 1.7; color: var(--color-body-text, #A0A0A0);">
                            A celebration of enduring aesthetic balance designed to complement personal style across generations, beyond passing fads.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =========================================================================
         SECTION 5 — OUR APPROACH TO WATCHES
         ========================================================================= -->
    <section class="py-5 border-bottom reveal-on-scroll" style="background: var(--color-bg-primary, #0B0B0C); border-color: var(--color-border-divider, #2E2E2E) !important;">
        <div class="container py-lg-4">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <h2 class="display-6 fw-normal mt-1 mb-3" style="font-family: var(--font-display, 'Playfair Display', 'DM Serif Display', Georgia, serif); color: var(--color-heading-ivory, #F5F5F5);">
                        <?= e($profile['approach_watches_title'] ?? 'Our Approach to Watches') ?>
                    </h2>
                    <div style="font-size: 15px; line-height: 1.85; font-family: var(--font-ui, 'Inter', sans-serif); color: var(--color-body-text, #A0A0A0);">
                        <?= nl2br(e($profile['approach_watches_content'])) ?>
                    </div>
                    <div class="pt-4">
                        <a href="/catalog" class="btn btn-outline-light" style="border-radius: var(--radius-sm, 6px); font-size: 13.5px; padding: 12px 24px; font-weight: 500; border-color: var(--color-border-divider, #2E2E2E); color: var(--color-heading-ivory, #F5F5F5);">
                            <span>Browse All Timepieces</span>
                            <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="p-4 border rounded-3 h-100 shadow-sm" style="background: var(--color-surface-card, #242424); border-color: var(--color-border-divider, #2E2E2E) !important;">
                                <div class="mb-2" style="color: var(--color-champagne-highlight, #D4AF7C); font-size: 20px;"><i class="bi bi-palette"></i></div>
                                <h6 class="fw-bold mb-1" style="color: var(--color-heading-ivory, #F5F5F5);">Visual Character</h6>
                                <p class="small mb-0" style="color: var(--color-body-text, #A0A0A0);">Harmonious dial layouts, uncluttered typography, and balanced case proportions.</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-4 border rounded-3 h-100 shadow-sm" style="background: var(--color-surface-card, #242424); border-color: var(--color-border-divider, #2E2E2E) !important;">
                                <div class="mb-2" style="color: var(--color-champagne-highlight, #D4AF7C); font-size: 20px;"><i class="bi bi-hand-index-thumb"></i></div>
                                <h6 class="fw-bold mb-1" style="color: var(--color-heading-ivory, #F5F5F5);">Everyday Ergonomics</h6>
                                <p class="small mb-0" style="color: var(--color-body-text, #A0A0A0);">Comfortable strap integration, secure clasps, and wrist-friendly case heights.</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-4 border rounded-3 h-100 shadow-sm" style="background: var(--color-surface-card, #242424); border-color: var(--color-border-divider, #2E2E2E) !important;">
                                <div class="mb-2" style="color: var(--color-champagne-highlight, #D4AF7C); font-size: 20px;"><i class="bi bi-file-text"></i></div>
                                <h6 class="fw-bold mb-1" style="color: var(--color-heading-ivory, #F5F5F5);">Clear Specifications</h6>
                                <p class="small mb-0" style="color: var(--color-body-text, #A0A0A0);">Direct disclosure of movement type, case diameter, water resistance, and lug width.</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-4 border rounded-3 h-100 shadow-sm" style="background: var(--color-surface-card, #242424); border-color: var(--color-border-divider, #2E2E2E) !important;">
                                <div class="mb-2" style="color: var(--color-champagne-highlight, #D4AF7C); font-size: 20px;"><i class="bi bi-gem"></i></div>
                                <h6 class="fw-bold mb-1" style="color: var(--color-heading-ivory, #F5F5F5);">Fine Materials</h6>
                                <p class="small mb-0" style="color: var(--color-body-text, #A0A0A0);">Carefully chosen stainless steel, mineral and sapphire glass, and supple leather straps.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =========================================================================
         SECTION 6 — OUR APPROACH TO QUALITY
         ========================================================================= -->
    <section class="py-5 border-bottom reveal-on-scroll" style="background: var(--color-bg-secondary, #141416); border-color: var(--color-border-divider, #2E2E2E) !important;">
        <div class="container py-lg-4">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center mb-4">
                    <h2 class="display-6 fw-normal mt-1 mb-3" style="font-family: var(--font-display, 'Playfair Display', 'DM Serif Display', Georgia, serif); color: var(--color-heading-ivory, #F5F5F5);">
                        <?= e($profile['quality_title'] ?? 'Our Approach to Quality') ?>
                    </h2>
                    <div class="mx-auto mb-3" style="width: 50px; height: 2px; background: var(--color-accent-deep-red, #E63946);"></div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="p-4 p-md-5 border rounded-3 shadow-sm text-start" style="background: var(--color-surface-card, #242424); border-color: var(--color-border-divider, #2E2E2E) !important; color: var(--color-body-text, #A0A0A0); font-size: 15px; line-height: 1.85; font-family: var(--font-ui, 'Inter', sans-serif);">
                        <?= nl2br(e($profile['quality_content'])) ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =========================================================================
         SECTION 7 & 8 — MISSION & VISION
         ========================================================================= -->
    <section class="py-5 border-bottom reveal-on-scroll" style="background: var(--color-bg-primary, #0B0B0C); border-color: var(--color-border-divider, #2E2E2E) !important;">
        <div class="container py-lg-4">
            <div class="row g-4 justify-content-center">
                <!-- Mission Block -->
                <div class="col-md-6 col-lg-5">
                    <div class="p-4 p-lg-5 rounded-3 border h-100 shadow-sm" style="border-color: var(--color-border-divider, #2E2E2E) !important; background: var(--color-surface-card, #242424);">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="badge px-3 py-1 fw-semibold text-uppercase" style="background: var(--color-accent-deep-red, #E63946); color: #FFFFFF; font-size: 10.5px; letter-spacing: 1.5px; border-radius: 4px;">
                                Purpose
                            </span>
                            <span class="small fw-semibold" style="color: var(--color-champagne-highlight, #D4AF7C);">Our Mission</span>
                        </div>
                        <h3 class="h4 fw-bold mb-3" style="font-family: var(--font-display, 'Playfair Display', 'DM Serif Display', Georgia, serif); color: var(--color-heading-ivory, #F5F5F5);">
                            Thoughtful Design &amp; Lasting Style
                        </h3>
                        <p class="mb-0" style="color: var(--color-body-text, #A0A0A0); font-size: 15px; line-height: 1.85; font-family: var(--font-ui, 'Inter', sans-serif);">
                            &ldquo;<?= e($profile['mission_statement']) ?>&rdquo;
                        </p>
                    </div>
                </div>

                <!-- Vision Block -->
                <div class="col-md-6 col-lg-5">
                    <div class="p-4 p-lg-5 rounded-3 border h-100 shadow-sm" style="border-color: var(--color-border-divider, #2E2E2E) !important; background: var(--color-surface-card, #242424);">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="badge px-3 py-1 fw-semibold text-uppercase" style="background: rgba(212, 175, 124, 0.2); color: var(--color-champagne-highlight, #D4AF7C); font-size: 10.5px; letter-spacing: 1.5px; border-radius: 4px; border: 1px solid rgba(212, 175, 124, 0.4);">
                                Aspiration
                            </span>
                            <span class="small fw-semibold" style="color: var(--color-champagne-highlight, #D4AF7C);">Our Vision</span>
                        </div>
                        <h3 class="h4 fw-bold mb-3" style="font-family: var(--font-display, 'Playfair Display', 'DM Serif Display', Georgia, serif); color: var(--color-heading-ivory, #F5F5F5);">
                            Enduring Identity &amp; Customer Trust
                        </h3>
                        <p class="mb-0" style="color: var(--color-body-text, #A0A0A0); font-size: 15px; line-height: 1.85; font-family: var(--font-ui, 'Inter', sans-serif);">
                            &ldquo;<?= e($profile['vision_statement']) ?>&rdquo;
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =========================================================================
         SECTION 9 — OUR CORE VALUES
         ========================================================================= -->
    <section class="py-5 border-bottom reveal-on-scroll" style="background: var(--color-bg-secondary, #141416); border-color: var(--color-border-divider, #2E2E2E) !important;">
        <div class="container py-lg-4">
            <div class="text-center max-w-2xl mx-auto mb-5">
                <h2 class="display-6 fw-normal mt-1 mb-2" style="font-family: var(--font-display, 'Playfair Display', 'DM Serif Display', Georgia, serif); color: var(--color-heading-ivory, #F5F5F5);">
                    Our Core Values
                </h2>
                <p class="small mb-0" style="max-width: 580px; margin: 0 auto; color: var(--color-muted-text, #737373);">
                    Five foundational values informing our conduct, product curation, and customer relationships.
                </p>
            </div>

            <div class="row g-4 justify-content-center">
                <?php foreach ($values as $val): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="p-4 border rounded-3 h-100 shadow-sm" style="background: var(--color-surface-card, #242424); border-color: var(--color-border-divider, #2E2E2E) !important;">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px; border-radius: 6px; background: rgba(212, 175, 124, 0.12); color: var(--color-champagne-highlight, #D4AF7C); font-size: 18px; border: 1px solid rgba(212, 175, 124, 0.25);">
                                <i class="bi <?= e($val['icon_class'] ?: 'bi-check-circle') ?>"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold m-0" style="font-family: var(--font-display, 'Playfair Display', Georgia, serif); color: var(--color-heading-ivory, #F5F5F5);">
                                    <?= e($val['name']) ?>
                                </h5>
                                <small class="fw-semibold" style="font-size: 11.5px; color: var(--color-champagne-highlight, #D4AF7C);"><?= e($val['tagline']) ?></small>
                            </div>
                        </div>
                        <p class="small mb-0" style="line-height: 1.7; font-family: var(--font-ui, 'Inter', sans-serif); color: var(--color-body-text, #A0A0A0);">
                            <?= e($val['description']) ?>
                        </p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- =========================================================================
         SECTION 10 — OUR COMMITMENT TO CUSTOMERS
         ========================================================================= -->
    <section class="py-5 border-bottom reveal-on-scroll" style="background: var(--color-bg-primary, #0B0B0C); border-color: var(--color-border-divider, #2E2E2E) !important;">
        <div class="container py-lg-4">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <h2 class="display-6 fw-normal mt-1 mb-3" style="font-family: var(--font-display, 'Playfair Display', 'DM Serif Display', Georgia, serif); color: var(--color-heading-ivory, #F5F5F5);">
                        <?= e($profile['commitment_title'] ?? 'Our Commitment to Customers') ?>
                    </h2>
                    <p class="mb-4" style="font-size: 15px; line-height: 1.85; font-family: var(--font-ui, 'Inter', sans-serif); color: var(--color-body-text, #A0A0A0);">
                        <?= nl2br(e($profile['commitment_content'])) ?>
                    </p>

                    <div class="vstack gap-2">
                        <div class="d-flex align-items-center gap-2 small" style="color: var(--color-body-text, #A0A0A0);">
                            <i class="bi bi-shield-check text-success fs-5"></i>
                            <span>Complimentary 2-Year manufacturer warranty on watch movements.</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 small" style="color: var(--color-body-text, #A0A0A0);">
                            <i class="bi bi-arrow-repeat text-success fs-5"></i>
                            <span>Straightforward 7-Day return and exchange policy for complete peace of mind.</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 small" style="color: var(--color-body-text, #A0A0A0);">
                            <i class="bi bi-box-seam text-success fs-5"></i>
                            <span>Insured domestic transit with real-time consignment tracking.</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 small" style="color: var(--color-body-text, #A0A0A0);">
                            <i class="bi bi-envelope text-success fs-5"></i>
                            <span>Direct concierge support at <a href="mailto:welkarstore@gmail.com" class="text-light text-decoration-none fw-semibold">welkarstore@gmail.com</a>.</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="p-4 p-md-5 rounded-3 border" style="background: var(--color-surface-card, #242424); border-color: var(--color-border-divider, #2E2E2E) !important;">
                        <h5 class="fw-bold mb-3" style="font-family: var(--font-display, 'Playfair Display', 'DM Serif Display', Georgia, serif); color: var(--color-heading-ivory, #F5F5F5);">
                            Explore Verified Store Policies
                        </h5>
                        <p class="small mb-4" style="color: var(--color-body-text, #A0A0A0);">
                            We encourage every patron to review our official policies for full clarity before ordering:
                        </p>

                        <div class="row g-2">
                            <div class="col-sm-6">
                                <a href="/page/warranty" class="p-3 border rounded text-decoration-none d-block transition-all" style="background: var(--color-bg-secondary, #1A1A1C); border-color: var(--color-border-divider, #2E2E2E) !important;">
                                    <div class="fw-bold small" style="color: var(--color-heading-ivory, #F5F5F5);"><i class="bi bi-patch-check me-2" style="color: var(--color-champagne-highlight, #D4AF7C);"></i>Warranty Terms</div>
                                    <small class="text-muted" style="font-size: 11px;">Coverage &amp; Movement Care</small>
                                </a>
                            </div>
                            <div class="col-sm-6">
                                <a href="/page/returns-exchanges" class="p-3 border rounded text-decoration-none d-block transition-all" style="background: var(--color-bg-secondary, #1A1A1C); border-color: var(--color-border-divider, #2E2E2E) !important;">
                                    <div class="fw-bold small" style="color: var(--color-heading-ivory, #F5F5F5);"><i class="bi bi-arrow-counterclockwise me-2 text-info"></i>7-Day Returns</div>
                                    <small class="text-muted" style="font-size: 11px;">Exchange Guidelines</small>
                                </a>
                            </div>
                            <div class="col-sm-6">
                                <a href="/page/shipping-policy" class="p-3 border rounded text-decoration-none d-block transition-all" style="background: var(--color-bg-secondary, #1A1A1C); border-color: var(--color-border-divider, #2E2E2E) !important;">
                                    <div class="fw-bold small" style="color: var(--color-heading-ivory, #F5F5F5);"><i class="bi bi-truck me-2 text-success"></i>Shipping &amp; COD</div>
                                    <small class="text-muted" style="font-size: 11px;">Timelines &amp; Delivery</small>
                                </a>
                            </div>
                            <div class="col-sm-6">
                                <a href="/track-order" class="p-3 border rounded text-decoration-none d-block transition-all" style="background: var(--color-bg-secondary, #1A1A1C); border-color: var(--color-border-divider, #2E2E2E) !important;">
                                    <div class="fw-bold small" style="color: var(--color-heading-ivory, #F5F5F5);"><i class="bi bi-geo-alt me-2" style="color: var(--color-accent-deep-red, #E63946);"></i>Track Order</div>
                                    <small class="text-muted" style="font-size: 11px;">Real-time Consignment Status</small>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =========================================================================
         SECTION 11 — OUR JOURNEY SINCE 2002
         ========================================================================= -->
    <section class="py-5 border-bottom reveal-on-scroll" style="background: var(--color-bg-secondary, #141416); border-color: var(--color-border-divider, #2E2E2E) !important;">
        <div class="container py-lg-4">
            <div class="text-center max-w-2xl mx-auto mb-5">
                <h2 class="display-6 fw-normal mt-1 mb-2" style="font-family: var(--font-display, 'Playfair Display', 'DM Serif Display', Georgia, serif); color: var(--color-heading-ivory, #F5F5F5);">
                    Our Journey Since 2002
                </h2>
                <p class="small mb-0" style="max-width: 580px; margin: 0 auto; color: var(--color-muted-text, #737373);">
                    Honoring authentic milestones in the evolution of WELKAR.
                </p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="timeline position-relative ps-4 ps-md-5" style="border-left: 2px solid var(--color-champagne-highlight, #D4AF7C); margin-left: 20px;">
                        <?php foreach ($milestones as $m): ?>
                        <div class="timeline-item position-relative mb-4">
                            <!-- Bullet Marker -->
                            <div class="position-absolute" style="left: -32px; top: 4px; width: 18px; height: 18px; border-radius: 50%; background: var(--color-bg-primary, #0B0B0C); border: 3px solid var(--color-champagne-highlight, #D4AF7C);"></div>
                            <div class="p-4 border rounded-3 shadow-sm" style="background: var(--color-surface-card, #242424); border-color: var(--color-border-divider, #2E2E2E) !important;">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge fw-semibold" style="background: rgba(212, 175, 124, 0.15); color: var(--color-champagne-highlight, #D4AF7C); border: 1px solid rgba(212, 175, 124, 0.3); font-size: 12px; padding: 4px 10px;">
                                        <?= e($m['year_date']) ?>
                                    </span>
                                    <h5 class="fw-bold m-0" style="font-family: var(--font-display, 'Playfair Display', Georgia, serif); color: var(--color-heading-ivory, #F5F5F5);">
                                        <?= e($m['title']) ?>
                                    </h5>
                                </div>
                                <p class="small mb-0" style="line-height: 1.8; font-family: var(--font-ui, 'Inter', sans-serif); color: var(--color-body-text, #A0A0A0);">
                                    <?= nl2br(e($m['description'])) ?>
                                </p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =========================================================================
         SECTION 12 — THE JOURNEY AHEAD & GET IN TOUCH
         ========================================================================= -->
    <section class="py-5 border-bottom reveal-on-scroll" id="contact" style="background: var(--color-bg-primary, #0B0B0C); border-color: var(--color-border-divider, #2E2E2E) !important;">
        <div class="container py-lg-4 text-center">
            <div class="max-w-2xl mx-auto mb-4" style="max-width: 680px;">
                <h2 class="display-6 fw-normal mt-1 mb-3" style="font-family: var(--font-display, 'Playfair Display', 'DM Serif Display', Georgia, serif); color: var(--color-heading-ivory, #F5F5F5);">
                    <?= e($profile['journey_ahead_title'] ?? 'The Journey Ahead') ?>
                </h2>
                <p class="lead mb-4" style="font-size: 1.05rem; line-height: 1.8; font-family: var(--font-ui, 'Inter', sans-serif); color: var(--color-body-text, #A0A0A0);">
                    <?= nl2br(e($profile['journey_ahead_content'])) ?>
                </p>

                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="/catalog" class="btn btn-welkar">
                        <span>Explore Our Collections</span>
                        <i class="bi bi-watch ms-2"></i>
                    </a>
                    <a href="mailto:welkarstore@gmail.com" class="btn btn-outline-light" style="font-weight: 500; font-size: 14px; padding: 12px 26px; border-radius: var(--radius-sm, 6px); border-color: var(--color-border-divider, #2E2E2E); color: var(--color-heading-ivory, #F5F5F5);">
                        <span>Contact Concierge Desk</span>
                        <i class="bi bi-envelope ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

</div>
