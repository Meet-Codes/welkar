<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Customer Support &amp; Helpdesk Tickets</h3>
        <p class="text-muted small m-0 mt-1">Review, chat, and resolve customer support inquiries, returns, order questions, and payment issues.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="/admin/support" class="btn btn-outline-dark btn-sm"><i class="bi bi-arrow-clockwise me-1"></i>Refresh</a>
    </div>
</div>

<!-- KPI Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card-kpi p-3">
            <div class="kpi-label">Total Inquiries</div>
            <div class="kpi-val"><?= (int)$counts['all'] ?></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-kpi p-3 border-danger">
            <div class="kpi-label text-danger">Open Tickets</div>
            <div class="kpi-val text-danger"><?= (int)$counts['open'] ?></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-kpi p-3 border-warning">
            <div class="kpi-label text-warning">In Progress</div>
            <div class="kpi-val text-warning"><?= (int)$counts['in_progress'] ?></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-kpi p-3 border-success">
            <div class="kpi-label text-success">Resolved</div>
            <div class="kpi-val text-success"><?= (int)$counts['resolved'] ?></div>
        </div>
    </div>
</div>

<!-- Filters & Search -->
<div class="card p-3 border shadow-sm mb-4 bg-white" style="border-radius: 8px;">
    <form method="GET" action="/admin/support" class="row g-2 align-items-center">
        <div class="col-md-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                <input type="text" name="q" class="form-control" placeholder="Search ticket #, name, phone, order #..." value="<?= e($search ?? '') ?>">
            </div>
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="open" <?= ($currentStatus === 'open') ? 'selected' : '' ?>>Open</option>
                <option value="in_progress" <?= ($currentStatus === 'in_progress') ? 'selected' : '' ?>>In Progress</option>
                <option value="resolved" <?= ($currentStatus === 'resolved') ? 'selected' : '' ?>>Resolved</option>
                <option value="closed" <?= ($currentStatus === 'closed') ? 'selected' : '' ?>>Closed</option>
            </select>
        </div>
        <div class="col-md-3">
            <select name="category" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All Categories</option>
                <option value="return_exchange" <?= ($currentCategory === 'return_exchange') ? 'selected' : '' ?>>Return / Exchange</option>
                <option value="order_status" <?= ($currentCategory === 'order_status') ? 'selected' : '' ?>>Order Status &amp; Dispatch</option>
                <option value="payment_issue" <?= ($currentCategory === 'payment_issue') ? 'selected' : '' ?>>Payment / Refund</option>
                <option value="product_find" <?= ($currentCategory === 'product_find') ? 'selected' : '' ?>>Watch Finder</option>
                <option value="general" <?= ($currentCategory === 'general') ? 'selected' : '' ?>>General Inquiry</option>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-sm btn-dark w-100 fw-bold">Filter</button>
            <?php if (!empty($search) || !empty($currentStatus) || !empty($currentCategory)): ?>
                <a href="/admin/support" class="btn btn-sm btn-outline-secondary">Reset</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Tickets Table -->
<div class="card border shadow-sm bg-white" style="border-radius: 8px;">
    <div class="table-responsive">
        <table class="table table-hover align-middle small mb-0">
            <thead class="table-light">
                <tr>
                    <th>Ticket #</th>
                    <th>Customer</th>
                    <th>Category</th>
                    <th>Subject / Message</th>
                    <th>Linked Order</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Last Activity</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($tickets)): ?>
                <tr>
                    <td colspan="9" class="text-center py-4 text-muted">
                        <i class="bi bi-inbox fs-2 d-block mb-1"></i>
                        No customer support tickets found matching criteria.
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($tickets as $t): ?>
                    <tr>
                        <td>
                            <strong class="text-dark">#<?= e($t['ticket_number']) ?></strong>
                        </td>
                        <td>
                            <div class="fw-bold"><?= e($t['customer_name']) ?></div>
                            <div class="text-muted" style="font-size: 11px;">+91 <?= e($t['customer_phone']) ?></div>
                        </td>
                        <td>
                            <?php 
                            $catBadges = [
                                'return_exchange' => ['badge bg-warning text-dark', 'Return/Exchange'],
                                'order_status' => ['badge bg-primary', 'Order Status'],
                                'payment_issue' => ['badge bg-danger', 'Payment Issue'],
                                'product_find' => ['badge bg-success', 'Watch Finder'],
                                'general' => ['badge bg-secondary', 'General'],
                            ];
                            $b = $catBadges[$t['category']] ?? ['badge bg-secondary', $t['category']];
                            ?>
                            <span class="<?= $b[0] ?>" style="font-size: 10.5px;"><?= $b[1] ?></span>
                        </td>
                        <td>
                            <div class="fw-semibold text-truncate" style="max-width: 200px;"><?= e($t['subject']) ?></div>
                        </td>
                        <td>
                            <?php if (!empty($t['order_number'])): ?>
                                <a href="/admin/orders?q=<?= urlencode($t['order_number']) ?>" class="badge bg-light text-dark border text-decoration-none">
                                    <i class="bi bi-box-seam me-1"></i><?= e($t['order_number']) ?>
                                </a>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                            $pBadges = [
                                'low' => 'text-muted',
                                'medium' => 'text-primary',
                                'high' => 'text-warning fw-bold',
                                'urgent' => 'text-danger fw-bold',
                            ];
                            ?>
                            <span class="<?= $pBadges[$t['priority']] ?? 'text-muted' ?> text-uppercase" style="font-size: 11px;"><?= e($t['priority']) ?></span>
                        </td>
                        <td>
                            <?php
                            $sBadges = [
                                'open' => 'badge bg-danger',
                                'in_progress' => 'badge bg-warning text-dark',
                                'resolved' => 'badge bg-success',
                                'closed' => 'badge bg-secondary',
                            ];
                            ?>
                            <span class="<?= $sBadges[$t['status']] ?? 'badge bg-secondary' ?> text-uppercase" style="font-size: 10.5px;">
                                <?= str_replace('_', ' ', e($t['status'])) ?>
                            </span>
                        </td>
                        <td class="text-muted" style="font-size: 11px;">
                            <?= date('d M, h:i A', strtotime($t['updated_at'] ?? $t['created_at'])) ?>
                        </td>
                        <td class="text-end">
                            <a href="/admin/support/<?= (int)$t['id'] ?>" class="btn btn-sm btn-dark fw-semibold" style="font-size: 11.5px;">
                                <i class="bi bi-chat-dots me-1"></i> View &amp; Reply
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
