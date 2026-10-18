<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="font-family: var(--font-display, serif);">Staff Roles & Permissions</h4>
        <p class="text-muted small mb-0">Manage authorized administration team members, granular role assignments, and two-factor authentication requirements.</p>
    </div>
    <button class="btn btn-welkar px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#newStaffModal">
        <i class="bi bi-person-plus me-1"></i> Add Team Member
    </button>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4">User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>MFA Security</th>
                        <th>Last Sign-In</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($staff as $u): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center text-dark fw-bold" style="width: 36px; height: 36px;">
                                        <?= strtoupper(substr($u['full_name'] ?: $u['username'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark"><?= e($u['full_name'] ?: $u['username']) ?></div>
                                        <div class="text-muted small">@<?= e($u['username']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><?= e($u['email']) ?></td>
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1">
                                    <?= e($u['role_name'] ?: ucfirst($u['role_slug'])) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($u['mfa_enabled']): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle"><i class="bi bi-shield-check me-1"></i> 2FA Enabled</span>
                                <?php else: ?>
                                    <span class="badge bg-warning-subtle text-warning border"><i class="bi bi-shield-slash me-1"></i> Disabled</span>
                                <?php endif; ?>
                            </td>
                            <td class="small text-muted">
                                <?= $u['last_login_at'] ? date('d M Y, h:i A', strtotime($u['last_login_at'])) : 'Never' ?>
                            </td>
                            <td>
                                <?php if ($u['status'] === 'active'): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-danger-subtle text-danger border">Suspended</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <?php if ((int)$u['id'] !== (int)admin_user()['id']): ?>
                                    <form action="/admin/staff/<?= $u['id'] ?>/toggle" method="POST" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm <?= ($u['status'] === 'active') ? 'btn-outline-warning' : 'btn-outline-success' ?>">
                                            <?= ($u['status'] === 'active') ? 'Suspend' : 'Activate' ?>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="badge bg-light text-muted border">Current User</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Staff Modal -->
<div class="modal fade" id="newStaffModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/admin/staff/store" method="POST">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Provision Admin Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Full Name</label>
                        <input type="text" name="full_name" class="form-control" placeholder="e.g. Rahul Sharma" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Username</label>
                            <input type="text" name="username" class="form-control" placeholder="rsharma" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="rsharma@welkar.in" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Temporary Password (min 8 chars)</label>
                        <input type="password" name="password" class="form-control" required minlength="8">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">System Role</label>
                        <select name="role_slug" class="form-select" required>
                            <?php foreach ($roles as $r): ?>
                                <option value="<?= e($r['slug']) ?>"><?= e($r['name']) ?> (<?= e($r['description']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="mfa_enabled" value="1" id="staffMfa" checked>
                        <label class="form-check-label small" for="staffMfa">Require Email Two-Factor Authentication</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-welkar">Create Staff User</button>
                </div>
            </form>
        </div>
    </div>
</div>
