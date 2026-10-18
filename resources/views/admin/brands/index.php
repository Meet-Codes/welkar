<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="font-family: var(--font-display, serif);">Brand Portfolio</h4>
        <p class="text-muted small mb-0">Manage authorized watchmaker partner brands and private label catalog branding.</p>
    </div>
    <button class="btn btn-welkar px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#newBrandModal">
        <i class="bi bi-plus-lg me-1"></i> Add New Brand
    </button>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4">Brand</th>
                        <th>Slug</th>
                        <th>Catalog Products</th>
                        <th>Featured</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($brands)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No brands found. Click "Add New Brand" to create one.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($brands as $b): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if (!empty($b['logo_url'])): ?>
                                            <img src="<?= e($b['logo_url']) ?>" alt="<?= e($b['name']) ?>" class="rounded border p-1" style="width: 36px; height: 36px; object-fit: contain;">
                                        <?php else: ?>
                                            <div class="rounded bg-light border d-flex align-items-center justify-content-center text-muted fw-bold" style="width: 36px; height: 36px; font-size: 14px;">
                                                <?= strtoupper(substr($b['name'], 0, 1)) ?>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <span class="fw-bold text-dark"><?= e($b['name']) ?></span>
                                            <?php if (!empty($b['description'])): ?>
                                                <div class="text-muted small text-truncate" style="max-width: 250px;"><?= e($b['description']) ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td><code><?= e($b['slug']) ?></code></td>
                                <td>
                                    <span class="badge bg-light text-dark border px-2 py-1">
                                        <?= (int)($b['product_count'] ?? 0) ?> watches
                                    </span>
                                </td>
                                <td>
                                    <?php if ($b['is_featured']): ?>
                                        <span class="badge bg-warning text-dark"><i class="bi bi-star-fill me-1"></i> Featured</span>
                                    <?php else: ?>
                                        <span class="text-muted small">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($b['is_active']): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary-subtle text-secondary border">Hidden</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <button class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="modal" data-bs-target="#editBrandModal<?= $b['id'] ?>">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                    <form action="/admin/brands/<?= $b['id'] ?>/delete" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete brand <?= e($b['name']) ?>?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editBrandModal<?= $b['id'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="/admin/brands/<?= $b['id'] ?>/update" method="POST" enctype="multipart/form-data">
                                            <?= csrf_field() ?>
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold">Edit Brand: <?= e($b['name']) ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Brand Name</label>
                                                    <input type="text" name="name" class="form-control" value="<?= e($b['name']) ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">URL Slug</label>
                                                    <input type="text" name="slug" class="form-control" value="<?= e($b['slug']) ?>">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Upload Logo Photo</label>
                                                    <input type="file" name="logo_file" class="form-control form-control-sm" accept="image/*">
                                                    <div class="form-text small" style="font-size: 11px;">Recommended size: 400x200px transparent PNG or SVG.</div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Or Logo URL</label>
                                                    <input type="text" name="logo_url" class="form-control" value="<?= e($b['logo_url']) ?>">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Description</label>
                                                    <textarea name="description" class="form-control" rows="3"><?= e($b['description']) ?></textarea>
                                                </div>
                                                <div class="form-check form-switch mb-2">
                                                    <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="feat<?= $b['id'] ?>" <?= $b['is_featured'] ? 'checked' : '' ?>>
                                                    <label class="form-check-label small" for="feat<?= $b['id'] ?>">Show in Featured Brands</label>
                                                </div>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="act<?= $b['id'] ?>" <?= $b['is_active'] ? 'checked' : '' ?>>
                                                    <label class="form-check-label small" for="act<?= $b['id'] ?>">Active (Visible in catalog)</label>
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

<!-- New Brand Modal -->
<div class="modal fade" id="newBrandModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/admin/brands/store" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Add New Watch Brand</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Brand Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Welkar, Casio, Fossil" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">URL Slug (Optional)</label>
                        <input type="text" name="slug" class="form-control" placeholder="auto-generated if empty">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Upload Logo Photo</label>
                        <input type="file" name="logo_file" class="form-control form-control-sm" accept="image/*">
                        <div class="form-text small" style="font-size: 11px;">Recommended size: 400x200px transparent PNG or SVG.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Or Logo Image URL</label>
                        <input type="text" name="logo_url" class="form-control" placeholder="/assets/images/... or https://...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Brand Story / Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Brief heritage and brand ethos..."></textarea>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="newFeat" checked>
                        <label class="form-check-label small" for="newFeat">Show in Featured Brands</label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="newAct" checked>
                        <label class="form-check-label small" for="newAct">Active (Visible in Storefront)</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-welkar">Create Brand</button>
                </div>
            </form>
        </div>
    </div>
</div>
