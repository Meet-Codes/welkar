<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="/admin/pages" class="text-decoration-none small text-muted"><i class="bi bi-arrow-left me-1"></i>Back to CMS Pages</a>
        <h1 class="h3 fw-bold mb-1 mt-1">Edit Page: <?= e($page['title']) ?></h1>
    </div>
    <div>
        <a href="/page/<?= e($page['slug']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-eye me-1"></i>View Live Page
        </a>
    </div>
</div>

<form action="/admin/pages/<?= (int)$page['id'] ?>/update" method="POST">
    <?= csrf_field() ?>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm p-4 rounded-3 mb-4">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-uppercase text-muted">Page Title</label>
                    <input type="text" name="title" class="form-control" value="<?= e($page['title']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-uppercase text-muted">Page Content (HTML supported)</label>
                    <textarea name="content_html" class="form-control font-monospace" rows="18" required style="font-size: 13px;"><?= htmlspecialchars($page['content_html']) ?></textarea>
                    <div class="form-text">You can use standard HTML elements like &lt;h2&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;strong&gt;, and Bootstrap alert classes.</div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-4 rounded-3 mb-4">
                <h6 class="fw-bold mb-3">Settings & SEO</h6>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-uppercase text-muted">URL Slug</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light">/page/</span>
                        <input type="text" name="slug" class="form-control" value="<?= e($page['slug']) ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-uppercase text-muted">Meta SEO Title</label>
                    <input type="text" name="meta_title" class="form-control form-control-sm" value="<?= e($page['meta_title'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-uppercase text-muted">Meta SEO Description</label>
                    <textarea name="meta_description" class="form-control form-control-sm" rows="3"><?= e($page['meta_description'] ?? '') ?></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-uppercase text-muted">Publication Status</label>
                    <select name="is_published" class="form-select form-select-sm">
                        <option value="1" <?= $page['is_published'] ? 'selected' : '' ?>>Published (Visible to public)</option>
                        <option value="0" <?= !$page['is_published'] ? 'selected' : '' ?>>Draft / Hidden</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-dark w-100 fw-bold">
                    <i class="bi bi-save me-1"></i>Save Changes
                </button>
            </div>
        </div>
    </div>
</form>
