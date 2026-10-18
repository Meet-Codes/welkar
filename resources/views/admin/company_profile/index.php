<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark m-0" style="font-family: 'Inter', sans-serif;">Company Profile &amp; Brand Storytelling</h1>
            <p class="text-muted small m-0">Manage WELKAR's verified brand facts, founding story, founders, mission, values, milestones, and shared content.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="/about" target="_blank" class="btn btn-outline-dark btn-sm">
                <i class="bi bi-box-arrow-up-right me-1"></i> Preview Live About Us Page
            </a>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-pills mb-4 gap-2 bg-white p-2 border rounded-3 shadow-sm" id="companyTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-semibold small" id="general-tab" data-bs-toggle="pill" data-bs-target="#tab-general" type="button" role="tab">
                <i class="bi bi-info-circle me-1"></i> Brand &amp; Story
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-semibold small" id="founders-tab" data-bs-toggle="pill" data-bs-target="#tab-founders" type="button" role="tab">
                <i class="bi bi-people me-1"></i> Founders (<?= count($founders) ?>)
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-semibold small" id="values-tab" data-bs-toggle="pill" data-bs-target="#tab-values" type="button" role="tab">
                <i class="bi bi-compass me-1"></i> Mission, Vision &amp; Values
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-semibold small" id="quality-tab" data-bs-toggle="pill" data-bs-target="#tab-quality" type="button" role="tab">
                <i class="bi bi-shield-check me-1"></i> Quality &amp; Commitment
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-semibold small" id="milestones-tab" data-bs-toggle="pill" data-bs-target="#tab-milestones" type="button" role="tab">
                <i class="bi bi-calendar-event me-1"></i> Milestones (<?= count($milestones) ?>)
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-semibold small" id="shared-tab" data-bs-toggle="pill" data-bs-target="#tab-shared" type="button" role="tab">
                <i class="bi bi-share me-1"></i> Shared Website Summaries
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="companyTabsContent">

        <!-- =================================================================
             TAB 1: Brand & Story
             ================================================================= -->
        <div class="tab-pane fade show active" id="tab-general" role="tabpanel">
            <form action="/admin/company-profile" method="POST">
                <?= csrf_field() ?>
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="fw-bold m-0 text-dark">Group A &amp; B: Core Brand Identity &amp; Founding Story</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-muted text-uppercase">Brand Name</label>
                                <input type="text" name="brand_name" class="form-control" value="<?= e($profile['brand_name']) ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-muted text-uppercase">Founding Year</label>
                                <input type="text" name="founding_year" class="form-control" value="<?= e($profile['founding_year']) ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold small text-muted text-uppercase">Brand Tagline</label>
                                <input type="text" name="tagline" class="form-control" value="<?= e($profile['tagline']) ?>" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted text-uppercase">About Page Hero Title</label>
                            <input type="text" name="hero_title" class="form-control" value="<?= e($profile['hero_title']) ?>" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted text-uppercase">About Page Hero Introduction</label>
                            <textarea name="hero_intro" class="form-control" rows="3" required><?= e($profile['hero_intro']) ?></textarea>
                            <small class="text-muted">High-level brand introduction explaining founding year 2002 and core perspectives.</small>
                        </div>

                        <hr class="my-4">

                        <h6 class="fw-bold text-dark mb-3">Our Story Narrative</h6>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Story Section Title</label>
                            <input type="text" name="story_title" class="form-control" value="<?= e($profile['story_title']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Story Summary / Lead Introduction</label>
                            <textarea name="story_intro" class="form-control" rows="3" required><?= e($profile['story_intro']) ?></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted text-uppercase">Full Story Body Content</label>
                            <textarea name="story_content" class="form-control" rows="6" required><?= e($profile['story_content']) ?></textarea>
                            <small class="text-muted">Honest origin narrative between Mr. Sumit Singh (Artistic) and Mr. Nevil Sharma (Technical).</small>
                        </div>

                        <hr class="my-4">

                        <h6 class="fw-bold text-dark mb-3">SEO &amp; Metadata</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted text-uppercase">About Page SEO Title</label>
                                <input type="text" name="seo_title" class="form-control" value="<?= e($profile['seo_title']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted text-uppercase">Meta Description</label>
                                <input type="text" name="seo_description" class="form-control" value="<?= e($profile['seo_description']) ?>">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light py-3 text-end">
                        <button type="submit" class="btn btn-dark px-4 fw-semibold">
                            <i class="bi bi-save me-1"></i> Save Brand &amp; Story Settings
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- =================================================================
             TAB 2: Founders
             ================================================================= -->
        <div class="tab-pane fade" id="tab-founders" role="tabpanel">
            <div class="row g-4">
                <?php foreach ($founders as $founder): ?>
                <div class="col-lg-6">
                    <form action="/admin/company-profile/founders" method="POST">
                        <?= csrf_field() ?>
                        <input type="hidden" name="founder_id" value="<?= (int)$founder['id'] ?>">
                        <div class="card border-0 shadow-sm rounded-3 h-100">
                            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                                <h6 class="fw-bold m-0 text-dark">
                                    <i class="bi bi-person-badge me-2 text-warning"></i><?= e($founder['name']) ?>
                                </h6>
                                <span class="badge bg-secondary"><?= e($founder['perspective']) ?></span>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-3 mb-3">
                                    <div class="col-sm-7">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Full Name</label>
                                        <input type="text" name="name" class="form-control" value="<?= e($founder['name']) ?>" required>
                                    </div>
                                    <div class="col-sm-5">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Designation</label>
                                        <input type="text" name="designation" class="form-control" value="<?= e($founder['designation']) ?>" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Founding Perspective</label>
                                    <input type="text" name="perspective" class="form-control" value="<?= e($founder['perspective']) ?>" required>
                                    <small class="text-muted">e.g. "Artistic Perspective" or "Technical Perspective"</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Approved Biography</label>
                                    <textarea name="bio" class="form-control" rows="5" required><?= e($founder['bio']) ?></textarea>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-sm-8">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Authentic Photo URL (Optional)</label>
                                        <input type="text" name="image_url" class="form-control" value="<?= e($founder['image_url'] ?? '') ?>" placeholder="/assets/images/founders/sumit.jpg">
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Display Order</label>
                                        <input type="number" name="display_order" class="form-control" value="<?= (int)$founder['display_order'] ?>">
                                    </div>
                                </div>

                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="is_published" value="1" id="pub_founder_<?= $founder['id'] ?>" <?= $founder['is_published'] ? 'checked' : '' ?>>
                                    <label class="form-check-label small fw-semibold" for="pub_founder_<?= $founder['id'] ?>">Publish on About Us Page</label>
                                </div>
                            </div>
                            <div class="card-footer bg-light py-3 text-end">
                                <button type="submit" class="btn btn-dark btn-sm px-3 fw-semibold">
                                    Update <?= e($founder['name']) ?>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- =================================================================
             TAB 3: Mission, Vision & Values
             ================================================================= -->
        <div class="tab-pane fade" id="tab-values" role="tabpanel">
            <form action="/admin/company-profile" method="POST">
                <?= csrf_field() ?>
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="fw-bold m-0 text-dark">Group D: Mission, Vision, and Guiding Philosophy</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted text-uppercase">Philosophy Title</label>
                            <input type="text" name="philosophy_title" class="form-control" value="<?= e($profile['philosophy_title']) ?>" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted text-uppercase">Philosophy Overview</label>
                            <textarea name="philosophy_intro" class="form-control" rows="3" required><?= e($profile['philosophy_intro']) ?></textarea>
                        </div>

                        <hr class="my-4">

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3 border">
                                    <label class="form-label fw-bold text-dark text-uppercase small"><i class="bi bi-flag me-1 text-primary"></i> Mission Statement</label>
                                    <textarea name="mission_statement" class="form-control" rows="4" required><?= e($profile['mission_statement']) ?></textarea>
                                    <small class="text-muted d-block mt-1">Proposed editorial statement on thoughtful design, practical utility, and customer confidence.</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3 border">
                                    <label class="form-label fw-bold text-dark text-uppercase small"><i class="bi bi-eye me-1 text-success"></i> Vision Statement</label>
                                    <textarea name="vision_statement" class="form-control" rows="4" required><?= e($profile['vision_statement']) ?></textarea>
                                    <small class="text-muted d-block mt-1">Aspirational statement on building WELKAR into a recognised, trusted brand.</small>
                                </div>
                            </div>
                        </div>

                        <h6 class="fw-bold text-dark mb-3">Active Core Values (5 Principles)</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle small">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">Order</th>
                                        <th style="width: 140px;">Value</th>
                                        <th style="width: 200px;">Tagline</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($values as $v): ?>
                                    <tr>
                                        <td class="text-center fw-bold"><?= (int)$v['display_order'] ?></td>
                                        <td class="fw-bold text-dark"><i class="bi <?= e($v['icon_class']) ?> me-2 text-warning"></i><?= e($v['name']) ?></td>
                                        <td class="text-muted"><?= e($v['tagline']) ?></td>
                                        <td><?= e($v['description']) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-light py-3 text-end">
                        <button type="submit" class="btn btn-dark px-4 fw-semibold">
                            <i class="bi bi-save me-1"></i> Save Mission &amp; Vision
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- =================================================================
             TAB 4: Quality & Commitment
             ================================================================= -->
        <div class="tab-pane fade" id="tab-quality" role="tabpanel">
            <form action="/admin/company-profile" method="POST">
                <?= csrf_field() ?>
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="fw-bold m-0 text-dark">Group E: Horological Approach, Quality Philosophy &amp; Customer Commitment</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted text-uppercase">Approach to Watches Title</label>
                            <input type="text" name="approach_watches_title" class="form-control" value="<?= e($profile['approach_watches_title']) ?>" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted text-uppercase">Approach to Watches Narrative</label>
                            <textarea name="approach_watches_content" class="form-control" rows="4" required><?= e($profile['approach_watches_content']) ?></textarea>
                            <small class="text-muted">Explains design balance, daily wear, dial aesthetics, and transparent information.</small>
                        </div>

                        <hr class="my-4">

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted text-uppercase">Quality Philosophy Title</label>
                            <input type="text" name="quality_title" class="form-control" value="<?= e($profile['quality_title']) ?>" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted text-uppercase">Quality Approach Narrative</label>
                            <textarea name="quality_content" class="form-control" rows="4" required><?= e($profile['quality_content']) ?></textarea>
                            <small class="text-muted">Honest quality philosophy without fabricating unverified laboratory tests or certifications.</small>
                        </div>

                        <hr class="my-4">

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted text-uppercase">Customer Commitment Title</label>
                            <input type="text" name="commitment_title" class="form-control" value="<?= e($profile['commitment_title']) ?>" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted text-uppercase">Customer Commitment Narrative</label>
                            <textarea name="commitment_content" class="form-control" rows="4" required><?= e($profile['commitment_content']) ?></textarea>
                            <small class="text-muted">Describes customer experience, transparent pricing, warranties, returns, and support access.</small>
                        </div>

                        <hr class="my-4">

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted text-uppercase">The Journey Ahead Title</label>
                            <input type="text" name="journey_ahead_title" class="form-control" value="<?= e($profile['journey_ahead_title']) ?>" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted text-uppercase">The Journey Ahead Narrative</label>
                            <textarea name="journey_ahead_content" class="form-control" rows="3" required><?= e($profile['journey_ahead_content']) ?></textarea>
                        </div>
                    </div>
                    <div class="card-footer bg-light py-3 text-end">
                        <button type="submit" class="btn btn-dark px-4 fw-semibold">
                            <i class="bi bi-save me-1"></i> Save Quality &amp; Commitments
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- =================================================================
             TAB 5: Milestones
             ================================================================= -->
        <div class="tab-pane fade" id="tab-milestones" role="tabpanel">
            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h6 class="fw-bold m-0 text-dark">Verified Brand Milestones</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle small">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 70px;">Year</th>
                                            <th>Milestone Title</th>
                                            <th>Description</th>
                                            <th style="width: 80px;" class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($milestones)): ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-3">No milestones configured.</td>
                                        </tr>
                                        <?php else: ?>
                                            <?php foreach ($milestones as $m): ?>
                                            <tr>
                                                <td class="fw-bold text-dark"><span class="badge bg-light text-dark border"><?= e($m['year_date']) ?></span></td>
                                                <td class="fw-semibold"><?= e($m['title']) ?></td>
                                                <td class="text-muted small"><?= e(substr($m['description'], 0, 90)) ?>...</td>
                                                <td class="text-end">
                                                    <?php if ($m['year_date'] !== '2002'): ?>
                                                    <form action="/admin/company-profile/milestones/<?= $m['id'] ?>/delete" method="POST" onsubmit="return confirm('Remove this milestone?');" class="d-inline">
                                                        <?= csrf_field() ?>
                                                        <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                    <?php else: ?>
                                                    <span class="badge bg-light text-muted border">Founding</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h6 class="fw-bold m-0 text-dark"><i class="bi bi-plus-circle me-2 text-success"></i>Add Verified Milestone</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="alert alert-info py-2 px-3 small mb-3">
                                <i class="bi bi-info-circle me-1"></i> Factual rule: Only add milestones with verified company evidence.
                            </div>
                            <form action="/admin/company-profile/milestones" method="POST">
                                <?= csrf_field() ?>
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Year or Date</label>
                                    <input type="text" name="year_date" class="form-control" placeholder="e.g. 2002" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Milestone Title</label>
                                    <input type="text" name="title" class="form-control" placeholder="e.g. Founding of WELKAR" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Description</label>
                                    <textarea name="description" class="form-control" rows="3" placeholder="Verified event details..." required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Display Order</label>
                                    <input type="number" name="display_order" class="form-control" value="0">
                                </div>
                                <button type="submit" class="btn btn-dark w-100 fw-semibold">
                                    Save Milestone
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- =================================================================
             TAB 6: Shared Website Summaries
             ================================================================= -->
        <div class="tab-pane fade" id="tab-shared" role="tabpanel">
            <form action="/admin/company-profile" method="POST">
                <?= csrf_field() ?>
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="fw-bold m-0 text-dark">Group G: Cross-Site Reusable Summaries</h5>
                        <p class="text-muted small m-0">Synchronize short brand introductions across the homepage, website footer, product pages, and collections.</p>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted text-uppercase">Homepage Brand Panel Summary</label>
                            <textarea name="home_summary" class="form-control" rows="3" required><?= e($profile['home_summary']) ?></textarea>
                            <small class="text-muted">Displays in the brand introduction block on the Homepage.</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted text-uppercase">Website Global Footer Description</label>
                            <textarea name="footer_summary" class="form-control" rows="2" required><?= e($profile['footer_summary']) ?></textarea>
                            <small class="text-muted">Displays in the website footer alongside the tagline.</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted text-uppercase">Product Details Page (PDP) Brand Panel</label>
                            <textarea name="pdp_summary" class="form-control" rows="2" required><?= e($profile['pdp_summary']) ?></textarea>
                            <small class="text-muted">Displays in the "About WELKAR" brand card on every watch PDP.</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted text-uppercase">Collections Page Header Summary</label>
                            <textarea name="collection_summary" class="form-control" rows="2" required><?= e($profile['collection_summary']) ?></textarea>
                            <small class="text-muted">Connects category and collection discovery to WELKAR's design philosophy.</small>
                        </div>
                    </div>
                    <div class="card-footer bg-light py-3 text-end">
                        <button type="submit" class="btn btn-dark px-4 fw-semibold">
                            <i class="bi bi-save me-1"></i> Update Shared Summaries
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
