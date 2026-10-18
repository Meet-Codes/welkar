<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Categories & Collections</h3>
        <p class="text-muted small m-0">Organize watches into navigational categories and homepage collections.</p>
    </div>
</div>

<div class="row g-4">
    <!-- Categories List -->
    <div class="col-lg-7">
        <div class="card p-4 border shadow-sm mb-4 bg-white" style="border-radius: 8px;">
            <h5 class="fw-bold mb-3">Store Categories</h5>
            <div class="table-responsive">
                <table class="table align-middle small mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Category</th>
                            <th>Slug</th>
                            <th>Watches</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $cat): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <?php if (!empty($cat['image_url'])): ?>
                                        <img src="<?= e($cat['image_url']) ?>" alt="<?= e($cat['name']) ?>" class="rounded border" style="width: 38px; height: 38px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="rounded bg-light border d-flex align-items-center justify-content-center text-muted fw-bold" style="width: 38px; height: 38px; font-size: 13px;">
                                            <i class="bi bi-tag"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <div class="fw-bold"><?= e($cat['name']) ?></div>
                                        <?php if (!empty($cat['description'])): ?>
                                            <div class="text-muted small text-truncate" style="max-width: 220px;"><?= e($cat['description']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td><code><?= e($cat['slug']) ?></code></td>
                            <td><span class="badge bg-secondary-subtle text-secondary"><?= (int)$cat['products_count'] ?> products</span></td>
                            <td>
                                <?php if ($cat['is_active']): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Hidden</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-secondary me-1 py-0 px-2" data-bs-toggle="modal" data-bs-target="#editCatModal<?= $cat['id'] ?>">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="/admin/categories/<?= $cat['id'] ?>/delete" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete category \'<?= e(addslashes($cat['name'])) ?>\'?');">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Category Modal -->
                        <div class="modal fade" id="editCatModal<?= $cat['id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="/admin/categories/<?= $cat['id'] ?>/update" method="POST" enctype="multipart/form-data">
                                        <?= csrf_field() ?>
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold">Edit Category: <?= e($cat['name']) ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Category Name *</label>
                                                <input type="text" name="name" class="form-control" value="<?= e($cat['name']) ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Slug</label>
                                                <input type="text" name="slug" class="form-control" value="<?= e($cat['slug']) ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Upload Category Photo</label>
                                                <input type="file" name="image_file" class="form-control form-control-sm" accept="image/*">
                                                <div class="form-text small" style="font-size: 11px;">Recommended size: 600x600px square photo (1:1 ratio).</div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Or Category Photo URL</label>
                                                <input type="text" name="image_url" class="form-control" value="<?= e($cat['image_url'] ?? '') ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Description</label>
                                                <textarea name="description" class="form-control" rows="3"><?= e($cat['description'] ?? '') ?></textarea>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="catAct<?= $cat['id'] ?>" <?= $cat['is_active'] ? 'checked' : '' ?>>
                                                <label class="form-check-label small" for="catAct<?= $cat['id'] ?>">Active (Visible in Storefront)</label>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-dark fw-bold">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Curated Collections -->
        <div class="card p-4 border shadow-sm bg-white" style="border-radius: 8px;">
            <h5 class="fw-bold mb-3">Curated Collections</h5>
            <div class="table-responsive">
                <table class="table align-middle small mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Collection</th>
                            <th>Slug</th>
                            <th>Linked Watches</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($collections as $col): ?>
                        <tr>
                            <td class="fw-bold"><?= e($col['name']) ?></td>
                            <td><code><?= e($col['slug']) ?></code></td>
                            <td><span class="badge bg-dark-subtle text-dark"><?= (int)$col['products_count'] ?> products</span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create Category Form -->
    <div class="col-lg-5">
        <div class="card p-4 border shadow-sm bg-white" style="border-radius: 8px;">
            <h5 class="fw-bold mb-3">Create New Category</h5>
            <form action="/admin/categories/store" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Category Name *</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Minimalist Dress Watches" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Upload Category Photo</label>
                    <input type="file" name="image_file" class="form-control form-control-sm" accept="image/*">
                    <div class="form-text small" style="font-size: 11px;">Recommended size: 600x600px square photo (1:1 ratio).</div>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Or Category Photo URL</label>
                    <input type="text" name="image_url" class="form-control" placeholder="/uploads/... or https://...">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Description</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Category merchandising description..."></textarea>
                </div>
                <button type="submit" class="btn btn-dark w-100 fw-bold">Save Category</button>
            </form>
        </div>
    </div>
</div>
