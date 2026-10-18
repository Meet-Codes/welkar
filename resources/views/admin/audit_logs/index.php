<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="font-family: var(--font-display, serif);">Operations & Security Audit Trail</h4>
        <p class="text-muted small mb-0">Immutable chronological record of administrative actions, catalog changes, price updates, and authorization events.</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 font-monospace small">
                <thead class="bg-light text-muted small text-uppercase font-sans">
                    <tr>
                        <th class="ps-4">Timestamp</th>
                        <th>Actor</th>
                        <th>Action</th>
                        <th>Target Entity</th>
                        <th>IP Address</th>
                        <th class="text-end pe-4">Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($logs)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No audit trail records logged yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($logs as $l): ?>
                            <tr>
                                <td class="ps-4 text-muted"><?= date('Y-m-d H:i:s', strtotime($l['created_at'])) ?></td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <?= e($l['admin_username'] ? '@' . $l['admin_username'] : ucfirst($l['user_type'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold text-dark"><?= e($l['action']) ?></span>
                                </td>
                                <td>
                                    <code class="text-secondary"><?= e($l['entity_type']) ?> #<?= e($l['entity_id']) ?></code>
                                </td>
                                <td><?= e($l['ip_address']) ?></td>
                                <td class="text-end pe-4">
                                    <?php if (!empty($l['new_values']) || !empty($l['old_values'])): ?>
                                        <button class="btn btn-sm btn-outline-secondary py-0 px-2" data-bs-toggle="modal" data-bs-target="#logModal<?= $l['id'] ?>">
                                            Payload
                                        </button>
                                        <div class="modal fade text-start font-sans" id="logModal<?= $l['id'] ?>" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h6 class="modal-title fw-bold">Audit Event #<?= $l['id'] ?> Details</h6>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <?php if (!empty($l['old_values'])): ?>
                                                            <div class="mb-2">
                                                                <strong class="small text-muted">Previous State:</strong>
                                                                <pre class="bg-light p-2 rounded small mt-1"><code><?= e(json_encode(json_decode($l['old_values']), JSON_PRETTY_PRINT)) ?></code></pre>
                                                            </div>
                                                        <?php endif; ?>
                                                        <?php if (!empty($l['new_values'])): ?>
                                                            <div>
                                                                <strong class="small text-muted">New State / Changes:</strong>
                                                                <pre class="bg-light p-2 rounded small mt-1"><code><?= e(json_encode(json_decode($l['new_values']), JSON_PRETTY_PRINT)) ?></code></pre>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
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
