<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Customer CRM & Profiles</h3>
        <p class="text-muted small m-0">Directory of registered and purchasing customers, verified mobile phones, and lifetime values.</p>
    </div>
</div>

<div class="card p-3 border shadow-sm mb-4 bg-white" style="border-radius: 8px;">
    <form action="/admin/customers" method="GET" class="row g-2">
        <div class="col-md-5">
            <input type="text" name="q" class="form-control form-control-sm" placeholder="Search by name, phone or email..." value="<?= e($search ?? '') ?>">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-sm btn-dark px-3 fw-bold">Search CRM</button>
            <a href="/admin/customers" class="btn btn-sm btn-outline-secondary">Reset</a>
        </div>
    </form>
</div>

<div class="card border shadow-sm p-4 bg-white" style="border-radius: 8px;">
    <?php if (empty($customers)): ?>
        <div class="p-4 bg-light rounded text-center text-muted small">No customer profiles found.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle small mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Customer Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th class="text-center">Total Orders</th>
                        <th class="text-end">Lifetime Spend</th>
                        <th>Last Order</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $c): ?>
                    <tr>
                        <td class="fw-bold"><?= e($c['full_name']) ?></td>
                        <td>+91 <?= e($c['phone']) ?></td>
                        <td><?= e($c['email'] ?: '—') ?></td>
                        <td class="text-center"><span class="badge bg-secondary-subtle text-secondary"><?= (int)$c['total_orders'] ?></span></td>
                        <td class="text-end fw-bold"><?= format_currency($c['lifetime_spend']) ?></td>
                        <td><?= $c['last_order_date'] ? date('d M Y', strtotime($c['last_order_date'])) : '—' ?></td>
                        <td><span class="badge bg-success">Active</span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
