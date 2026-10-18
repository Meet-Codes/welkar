<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="d-flex align-items-center gap-2">
            <h3 class="fw-bold mb-0">Ticket #<?= e($ticket['ticket_number']) ?></h3>
            <?php
            $sBadges = [
                'open' => 'badge bg-danger',
                'in_progress' => 'badge bg-warning text-dark',
                'resolved' => 'badge bg-success',
                'closed' => 'badge bg-secondary',
            ];
            ?>
            <span class="<?= $sBadges[$ticket['status']] ?? 'badge bg-secondary' ?> text-uppercase"><?= str_replace('_', ' ', e($ticket['status'])) ?></span>
            <span class="badge bg-light text-dark border text-uppercase"><?= e($ticket['category']) ?></span>
        </div>
        <p class="text-muted small m-0 mt-1">Logged on <?= date('d M Y, h:i A', strtotime($ticket['created_at'])) ?> &bull; Customer: <?= e($ticket['customer_name']) ?> (+91 <?= e($ticket['customer_phone']) ?>)</p>
    </div>

    <div class="d-flex gap-2">
        <a href="/admin/support" class="btn btn-outline-dark btn-sm"><i class="bi bi-arrow-left me-1"></i>Back to Helpdesk</a>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Chat Conversation Thread & Reply Form -->
    <div class="col-lg-8">
        <!-- Chat History -->
        <div class="card border shadow-sm mb-4 bg-white" style="border-radius: 8px;">
            <div class="card-header bg-light py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-chat-left-text me-2"></i>Live Conversation History</h6>
                <span class="small text-muted"><?= count($messages) ?> Messages Logged</span>
            </div>
            <div class="card-body p-3 overflow-y-auto d-flex flex-column gap-3" style="min-height: 380px; max-height: 480px; background: #FAFAFA;">
                <?php foreach ($messages as $m): ?>
                    <?php if ($m['sender_type'] === 'customer'): ?>
                        <!-- Customer Bubble -->
                        <div class="d-flex flex-column align-items-start" style="max-width: 82%;">
                            <div class="small fw-bold text-dark mb-1" style="font-size: 11px;">
                                <i class="bi bi-person-circle me-1"></i><?= e($m['sender_name']) ?> (Customer)
                            </div>
                            <div class="p-3 rounded-3 bg-white border text-dark shadow-sm" style="font-size: 13px; border-radius: 12px 12px 12px 2px !important;">
                                <?= nl2br(e($m['message'])) ?>
                            </div>
                            <span class="text-muted small mt-1" style="font-size: 10px;"><?= date('d M Y, h:i A', strtotime($m['created_at'])) ?></span>
                        </div>
                    <?php elseif ($m['sender_type'] === 'admin'): ?>
                        <!-- Admin Bubble -->
                        <div class="d-flex flex-column align-items-end align-self-end" style="max-width: 82%;">
                            <div class="small fw-bold text-dark mb-1" style="font-size: 11px;">
                                <i class="bi bi-shield-check text-success me-1"></i><?= e($m['sender_name']) ?> (Staff)
                            </div>
                            <div class="p-3 rounded-3 text-white shadow-sm" style="background: #202522; font-size: 13px; border-radius: 12px 12px 2px 12px !important;">
                                <?= nl2br(e($m['message'])) ?>
                            </div>
                            <span class="text-muted small mt-1" style="font-size: 10px;"><?= date('d M Y, h:i A', strtotime($m['created_at'])) ?></span>
                        </div>
                    <?php else: ?>
                        <!-- System Event -->
                        <div class="align-self-center my-1 py-1 px-3 rounded-pill bg-light border text-muted small text-center" style="font-size: 11px;">
                            <i class="bi bi-info-circle me-1"></i><?= e($m['message']) ?> &bull; <?= date('d M, h:i A', strtotime($m['created_at'])) ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <!-- Admin Reply Box -->
            <div class="card-footer bg-white p-3 border-top">
                <form action="/admin/support/<?= (int)$ticket['id'] ?>/reply" method="POST">
                    <?= csrf_field() ?>
                    <label class="form-label small fw-bold mb-1">Reply to Customer</label>
                    <textarea name="message" class="form-control mb-2" rows="3" placeholder="Type resolution or inquiry response here... This message appears instantly in customer's concierge chat." required></textarea>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small" style="font-size: 11px;">Customer receives instant response in the concierge widget.</span>
                        <button type="submit" class="btn btn-dark btn-sm px-4 fw-bold">
                            <i class="bi bi-send-fill me-1 text-warning"></i> Send Reply
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Column: Ticket Controls, Customer Info & Related Order -->
    <div class="col-lg-4">
        <!-- Status & Priority Controls -->
        <div class="card p-3 border shadow-sm mb-4 bg-white" style="border-radius: 8px;">
            <h6 class="fw-bold mb-3"><i class="bi bi-sliders me-2"></i>Ticket Resolution Controls</h6>
            <form action="/admin/support/<?= (int)$ticket['id'] ?>/status" method="POST">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Status</label>
                    <select name="status" class="form-select form-select-sm" required>
                        <option value="open" <?= ($ticket['status'] === 'open') ? 'selected' : '' ?>>Open (Pending Review)</option>
                        <option value="in_progress" <?= ($ticket['status'] === 'in_progress') ? 'selected' : '' ?>>In Progress (Investigating)</option>
                        <option value="resolved" <?= ($ticket['status'] === 'resolved') ? 'selected' : '' ?>>Resolved (Issue Solved)</option>
                        <option value="closed" <?= ($ticket['status'] === 'closed') ? 'selected' : '' ?>>Closed</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Priority</label>
                    <select name="priority" class="form-select form-select-sm" required>
                        <option value="low" <?= ($ticket['priority'] === 'low') ? 'selected' : '' ?>>Low</option>
                        <option value="medium" <?= ($ticket['priority'] === 'medium') ? 'selected' : '' ?>>Medium</option>
                        <option value="high" <?= ($ticket['priority'] === 'high') ? 'selected' : '' ?>>High</option>
                        <option value="urgent" <?= ($ticket['priority'] === 'urgent') ? 'selected' : '' ?>>Urgent</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-sm btn-dark w-100 fw-bold">Update Status</button>
            </form>
        </div>

        <!-- Customer Profile Card -->
        <div class="card p-3 border shadow-sm mb-4 bg-white" style="border-radius: 8px;">
            <h6 class="fw-bold mb-3"><i class="bi bi-person me-2"></i>Customer Information</h6>
            <div class="small leading-relaxed">
                <div><strong>Name:</strong> <?= e($ticket['customer_name']) ?></div>
                <div><strong>Phone:</strong> <a href="tel:<?= e($ticket['customer_phone']) ?>" class="text-dark fw-bold">+91 <?= e($ticket['customer_phone']) ?></a></div>
                <?php if (!empty($ticket['customer_email'])): ?>
                    <div><strong>Email:</strong> <a href="mailto:<?= e($ticket['customer_email']) ?>"><?= e($ticket['customer_email']) ?></a></div>
                <?php endif; ?>
                <div><strong>Category:</strong> <span class="badge bg-secondary"><?= e($ticket['category']) ?></span></div>
                <div><strong>Subject:</strong> <?= e($ticket['subject']) ?></div>
            </div>
        </div>

        <!-- Linked Order Card (If any) -->
        <?php if ($order): ?>
        <div class="card p-3 border shadow-sm mb-4 bg-white border-warning" style="border-radius: 8px;">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="fw-bold mb-0"><i class="bi bi-box-seam me-2 text-warning"></i>Linked Order</h6>
                <a href="/admin/orders/<?= (int)$order['id'] ?>" class="btn btn-sm btn-outline-dark py-0 px-2" style="font-size: 11px;">View Order</a>
            </div>
            <div class="small">
                <div><strong>Order #:</strong> <code><?= e($order['order_number']) ?></code></div>
                <div><strong>Total Amount:</strong> <?= format_currency($order['total_amount']) ?></div>
                <div><strong>Payment Status:</strong> <span class="badge bg-dark"><?= e($order['payment_status']) ?></span> (<?= strtoupper(e($order['payment_method'])) ?>)</div>
                <div><strong>Order Status:</strong> <span class="badge bg-info text-dark"><?= e($order['status']) ?></span></div>
                <div><strong>Placed On:</strong> <?= date('d M Y', strtotime($order['created_at'])) ?></div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Internal Notes Card -->
        <div class="card p-3 border shadow-sm bg-white" style="border-radius: 8px;">
            <h6 class="fw-bold mb-2"><i class="bi bi-lock me-2"></i>Internal Admin Notes</h6>
            <form action="/admin/support/<?= (int)$ticket['id'] ?>/notes" method="POST">
                <?= csrf_field() ?>
                <textarea name="admin_notes" class="form-control form-control-sm mb-2" rows="3" placeholder="Private notes visible only to admin team..."><?= e($ticket['admin_notes'] ?? '') ?></textarea>
                <button type="submit" class="btn btn-sm btn-outline-secondary w-100">Save Internal Notes</button>
            </form>
        </div>
    </div>
</div>
