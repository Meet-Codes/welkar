<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1">Content Pages CMS</h1>
        <p class="text-muted small m-0">Edit policy pages, customer care guides, and warranty terms without modifying code.</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light small text-uppercase fw-bold text-muted">
                <tr>
                    <th>Title & URL</th>
                    <th>Locale</th>
                    <th>Status</th>
                    <th>Last Updated</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pages as $p): ?>
                <tr>
                    <td>
                        <strong class="text-dark d-block"><?= e($p['title']) ?></strong>
                        <a href="/page/<?= e($p['slug']) ?>" target="_blank" class="small text-muted text-decoration-none">
                            <i class="bi bi-box-arrow-up-right me-1"></i>/page/<?= e($p['slug']) ?>
                        </a>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border text-uppercase"><?= e($p['locale']) ?></span>
                    </td>
                    <td>
                        <?php if ($p['is_published']): ?>
                            <span class="badge bg-success-subtle text-success">Published</span>
                        <?php else: ?>
                            <span class="badge bg-secondary-subtle text-secondary">Draft</span>
                        <?php endif; ?>
                    </td>
                    <td class="small text-muted text-nowrap">
                        <?= date('d M Y, H:i', strtotime($p['updated_at'])) ?>
                    </td>
                    <td class="text-end text-nowrap">
                        <a href="/admin/pages/<?= (int)$p['id'] ?>/edit" class="btn btn-sm btn-outline-dark">
                            <i class="bi bi-pencil-square me-1"></i>Edit Content
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
