<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="font-family: var(--font-display, serif);">Hero Banners & Visual Campaigns</h4>
        <p class="text-muted small mb-0">Control the storefront homepage hero slider, change banner images, update headlines, and manage promotional CTA destinations.</p>
    </div>
    <button class="btn btn-welkar px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#newBannerModal">
        <i class="bi bi-plus-lg me-1"></i> Add New Slide
    </button>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4" style="width: 70px;">Order</th>
                        <th style="width: 140px;">Banner Preview</th>
                        <th>Headings & Subtitle</th>
                        <th>CTA Link</th>
                        <th style="width: 100px;">Status</th>
                        <th class="text-end pe-4" style="width: 120px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($banners)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No custom hero banners found. The homepage will display default hero visuals.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($banners as $bn): ?>
                            <tr>
                                <td class="ps-4">
                                    <span class="badge bg-light text-dark border fw-bold"><?= (int)$bn['display_order'] ?></span>
                                </td>
                                <td>
                                    <?php if (!empty($bn['image_desktop'])): ?>
                                        <a href="<?= e($bn['image_desktop']) ?>" target="_blank" title="View Full Image">
                                            <img src="<?= e($bn['image_desktop']) ?>" alt="<?= e($bn['title']) ?>" class="rounded border shadow-sm" style="width: 120px; height: 60px; object-fit: cover;">
                                        </a>
                                    <?php else: ?>
                                        <div class="rounded bg-light border text-muted small d-flex align-items-center justify-content-center" style="width: 120px; height: 60px;">
                                            No Image
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark"><?= e($bn['title']) ?></div>
                                    <?php if (!empty($bn['subtitle'])): ?>
                                        <div class="text-muted small"><?= e($bn['subtitle']) ?></div>
                                    <?php endif; ?>
                                    <div class="small text-muted font-monospace mt-1" style="font-size: 11px;">
                                        <?= e($bn['image_desktop']) ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border"><?= e($bn['button_text']) ?></span>
                                    <div class="small text-muted text-truncate" style="max-width: 180px;"><code><?= e($bn['link_url']) ?></code></div>
                                </td>
                                <td>
                                    <?php if ($bn['is_active']): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary-subtle text-secondary border">Hidden</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <button class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="modal" data-bs-target="#editBannerModal<?= $bn['id'] ?>" title="Edit Banner">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="/admin/banners/<?= $bn['id'] ?>/delete" method="POST" class="d-inline" onsubmit="return confirm('Remove this banner slide?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Banner">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editBannerModal<?= $bn['id'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="/admin/banners/<?= $bn['id'] ?>/update" method="POST" enctype="multipart/form-data">
                                            <?= csrf_field() ?>
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold">Edit Banner Slide #<?= $bn['id'] ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Current Preview -->
                                                <?php if (!empty($bn['image_desktop'])): ?>
                                                    <div class="mb-3 text-center bg-light p-2 rounded border">
                                                        <div class="small text-muted mb-1 fw-semibold">Current Image</div>
                                                        <img src="<?= e($bn['image_desktop']) ?>" alt="Preview" class="img-fluid rounded border shadow-sm" style="max-height: 140px; object-fit: cover;">
                                                    </div>
                                                <?php endif; ?>

                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Upload New Image File</label>
                                                    <input type="file" name="banner_file" class="form-control" accept="image/*">
                                                    <div class="form-text small text-muted">Upload a new JPG, PNG, or WebP file to replace the current image.</div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Or Image Path / URL</label>
                                                    <input type="text" name="image_desktop" class="form-control" value="<?= e($bn['image_desktop']) ?>">
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Heading Title</label>
                                                    <input type="text" name="title" class="form-control" value="<?= e($bn['title']) ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Subtitle / Tagline</label>
                                                    <input type="text" name="subtitle" class="form-control" value="<?= e($bn['subtitle']) ?>">
                                                </div>

                                                <div class="row g-2 mb-3">
                                                    <div class="col-7">
                                                        <label class="form-label small fw-semibold">Destination URL</label>
                                                        <input type="text" name="link_url" class="form-control" value="<?= e($bn['link_url']) ?>">
                                                    </div>
                                                    <div class="col-5">
                                                        <label class="form-label small fw-semibold">Button Label</label>
                                                        <input type="text" name="button_text" class="form-control" value="<?= e($bn['button_text']) ?>">
                                                    </div>
                                                </div>
                                                <div class="row g-2 mb-3">
                                                    <div class="col-6">
                                                        <label class="form-label small fw-semibold">Display Order</label>
                                                        <input type="number" name="display_order" class="form-control" value="<?= (int)$bn['display_order'] ?>">
                                                    </div>
                                                    <div class="col-6 d-flex align-items-end">
                                                        <div class="form-check form-switch mb-2">
                                                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="actBn<?= $bn['id'] ?>" <?= $bn['is_active'] ? 'checked' : '' ?>>
                                                            <label class="form-check-label small" for="actBn<?= $bn['id'] ?>">Active</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-welkar">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- New Banner Modal -->
<div class="modal fade" id="newBannerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/admin/banners/store" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Add New Hero Banner Slide</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Upload Image File</label>
                        <input type="file" name="banner_file" class="form-control" accept="image/*">
                        <div class="form-text small text-muted">Select high-resolution JPG, PNG, or WebP image.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Or Image Path / URL</label>
                        <input type="text" name="image_desktop" class="form-control" placeholder="/uploads/banners/... or https://...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Heading Title</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Masterpieces of Precision" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Subtitle / Tagline</label>
                        <input type="text" name="subtitle" class="form-control" placeholder="e.g. Handcrafted Automatic Timepieces for Discerning Collectors">
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-7">
                            <label class="form-label small fw-semibold">Destination URL</label>
                            <input type="text" name="link_url" class="form-control" value="/catalog">
                        </div>
                        <div class="col-5">
                            <label class="form-label small fw-semibold">Button Label</label>
                            <input type="text" name="button_text" class="form-control" value="Shop The Collection">
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Display Order</label>
                            <input type="number" name="display_order" class="form-control" value="0">
                        </div>
                        <div class="col-6 d-flex align-items-end">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="newActBn" checked>
                                <label class="form-check-label small" for="newActBn">Active Now</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-welkar">Create Slide</button>
                </div>
            </form>
        </div>
    </div>
</div>
