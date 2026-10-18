<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Watch Catalog & Stock</h3>
        <p class="text-muted small m-0">Manage watch models, dial variations, pricing, and live inventory.</p>
    </div>
    <a href="/admin/products/create" class="btn btn-dark btn-sm fw-bold">
        <i class="bi bi-plus-lg me-1"></i> Add New Watch
    </a>
</div>

<div class="card border shadow-sm p-4 bg-white" style="border-radius: 8px;">
    <div class="table-responsive">
        <table class="table align-middle small mb-0">
            <thead class="table-light">
                <tr>
                    <th>Watch</th>
                    <th>Brand</th>
                    <th>Dial Style</th>
                    <th>Base SKU</th>
                    <th>Base Price</th>
                    <th>Total Stock</th>
                    <th>Live Dial Sync</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="<?= e($p['primary_image']) ?>" width="44" height="44" class="rounded border object-fit-cover me-2" alt="">
                            <div>
                                <strong class="d-block text-dark"><?= e($p['name']) ?></strong>
                                <span class="text-muted" style="font-size: 11px;"><?= (int)$p['variants_count'] ?> active variants</span>
                            </div>
                        </div>
                    </td>
                    <td><?= e($p['brand_name']) ?></td>
                    <td><span class="badge bg-light text-dark border"><?= e($p['dial_type']) ?></span></td>
                    <td><code><?= e($p['base_sku']) ?></code></td>
                    <td class="fw-bold"><?= format_currency($p['base_price']) ?></td>
                    <td>
                        <?php if ((int)$p['total_stock'] <= 5): ?>
                            <span class="badge bg-danger"><?= (int)$p['total_stock'] ?> units</span>
                        <?php else: ?>
                            <span class="badge bg-success-subtle text-success"><?= (int)$p['total_stock'] ?> units</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ((bool)$p['is_live_dial_animated']): ?>
                            <span class="badge bg-primary-subtle text-primary"><i class="bi bi-broadcast me-1"></i>Active</span>
                        <?php else: ?>
                            <span class="text-muted" style="font-size: 11px;">Static Photo</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?= (bool)$p['is_active'] ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Archived</span>' ?>
                    </td>
                    <td class="text-end">
                        <div class="d-inline-flex gap-1 align-items-center">
                            <a href="/admin/products/<?= (int)$p['id'] ?>/edit" class="btn btn-sm btn-outline-dark py-1 px-2 fw-semibold" title="Edit Watch & Photos">
                                <i class="bi bi-pencil-square me-1"></i>Edit
                            </a>
                            <form action="/admin/products/<?= (int)$p['id'] ?>/delete?_csrf_token=<?= e(csrf_token()) ?>" method="POST" class="d-inline m-0" onsubmit="return confirm('Are you sure you want to delete watch \'<?= e(addslashes($p['name'])) ?>\'?');">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" title="Delete Watch">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
