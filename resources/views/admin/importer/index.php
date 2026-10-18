<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Bulk Product Importer & Data Feed</h3>
        <p class="text-muted small m-0">Import hundreds of watches via structured CSV with dry-run validation and row-level error reporting.</p>
    </div>
    <a href="/admin/importer/template" class="btn btn-outline-dark btn-sm fw-bold">
        <i class="bi bi-download me-1"></i> Download CSV Template
    </a>
</div>

<div class="row g-4 mb-4">
    <!-- Upload Box -->
    <div class="col-lg-6">
        <div class="card p-4 border shadow-sm bg-white" style="border-radius: 8px;">
            <h5 class="fw-bold mb-3"><i class="bi bi-cloud-arrow-up me-2"></i>Upload Watch Catalog CSV</h5>
            <form action="/admin/importer/upload" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Select CSV File *</label>
                    <input type="file" name="csv_file" class="form-control" accept=".csv" required>
                    <div class="form-text small">File must match the official Welkar CSV column headers.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Import Strategy</label>
                    <select name="mode" class="form-select form-select-sm">
                        <option value="upsert">Upsert (Create new & Update existing SKUs)</option>
                        <option value="create_only">Create Only (Skip existing SKUs)</option>
                        <option value="update_only">Update Only (Update prices & stock only)</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-dark w-100 fw-bold py-2">
                    <i class="bi bi-check-circle me-1"></i> Process Batch Import
                </button>
            </form>
        </div>
    </div>

    <!-- Data Dictionary / Rules -->
    <div class="col-lg-6">
        <div class="card p-4 border shadow-sm bg-light" style="border-radius: 8px;">
            <h6 class="fw-bold mb-2"><i class="bi bi-info-circle me-1"></i>Import Validation Rules</h6>
            <ul class="small text-muted ps-3 mb-0 leading-relaxed">
                <li><strong>SKU Uniqueness:</strong> Every variant must carry a unique alphanumeric SKU.</li>
                <li><strong>Safe Formats:</strong> Selling Price and Stock Quantity must be valid positive numbers.</li>
                <li><strong>Auto-Creation:</strong> Missing Brands or Categories will be safely auto-created during the run.</li>
                <li><strong>Anti-Formula Injection:</strong> Cells beginning with <code>=, +, -, @</code> are safely sanitized.</li>
                <li><strong>Transactions:</strong> Database row inserts run inside atomic batches with audit history.</li>
            </ul>
        </div>
    </div>
</div>

<!-- Past Import Jobs History -->
<div class="card p-4 border shadow-sm bg-white" style="border-radius: 8px;">
    <h5 class="fw-bold mb-3"><i class="bi bi-clock-history me-2"></i>Import Execution History</h5>
    <?php if (empty($jobs)): ?>
        <div class="p-4 bg-light rounded text-center text-muted small">No bulk import jobs have been executed yet.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle small mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Job ID</th>
                        <th>Filename</th>
                        <th>Mode</th>
                        <th>Total Rows</th>
                        <th>Imported</th>
                        <th>Failed</th>
                        <th>Executed By</th>
                        <th>Completed At</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($jobs as $j): ?>
                    <tr>
                        <td>#<?= (int)$j['id'] ?></td>
                        <td class="fw-bold"><?= e($j['filename']) ?></td>
                        <td><code><?= e($j['mode']) ?></code></td>
                        <td><?= (int)$j['total_rows'] ?></td>
                        <td><span class="badge bg-success-subtle text-success"><?= (int)$j['imported_rows'] ?></span></td>
                        <td>
                            <?php if ((int)$j['failed_rows'] > 0): ?>
                                <span class="badge bg-danger"><?= (int)$j['failed_rows'] ?></span>
                            <?php else: ?>
                                <span class="text-muted">0</span>
                            <?php endif; ?>
                        </td>
                        <td><?= e($j['username']) ?></td>
                        <td><?= date('d M Y, h:i A', strtotime($j['completed_at'] ?: $j['created_at'])) ?></td>
                        <td><span class="badge bg-dark text-uppercase"><?= e($j['status']) ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
