<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1">Customer Reviews Moderation</h1>
        <p class="text-muted small m-0">Review, approve, or reject customer feedback and verified ratings.</p>
    </div>
    <div class="btn-group">
        <a href="/admin/reviews" class="btn btn-sm <?= empty($currentStatus) ? 'btn-dark' : 'btn-outline-dark' ?>">All</a>
        <a href="/admin/reviews?status=approved" class="btn btn-sm <?= $currentStatus === 'approved' ? 'btn-dark' : 'btn-outline-dark' ?>">Approved</a>
        <a href="/admin/reviews?status=pending" class="btn btn-sm <?= $currentStatus === 'pending' ? 'btn-dark' : 'btn-outline-dark' ?>">Pending</a>
        <a href="/admin/reviews?status=rejected" class="btn btn-sm <?= $currentStatus === 'rejected' ? 'btn-dark' : 'btn-outline-dark' ?>">Rejected</a>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light small text-uppercase fw-bold text-muted">
                <tr>
                    <th>Product</th>
                    <th>Author & Rating</th>
                    <th>Review Content</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($reviews)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted small">No reviews found matching this criteria.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($reviews as $rev): 
                        $revInitial = strtoupper(mb_substr(trim($rev['author_name'] ?: 'V'), 0, 1));
                        $wristshots = !empty($rev['images_json']) ? json_decode($rev['images_json'], true) : [];
                    ?>
                    <tr>
                        <td style="max-width: 190px;">
                            <a href="/product/<?= e($rev['product_slug']) ?>" target="_blank" class="fw-bold text-dark text-decoration-none small d-block text-truncate">
                                <?= e($rev['product_name']) ?>
                            </a>
                            <span class="badge bg-secondary-subtle text-secondary" style="font-size: 10px;">ID #<?= (int)$rev['product_id'] ?></span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <?php if (!empty($rev['avatar_url'])): ?>
                                    <img src="<?= e($rev['avatar_url']) ?>" alt="<?= e($rev['author_name']) ?>" class="rounded-circle border" style="width: 36px; height: 36px; object-fit: cover; flex-shrink: 0;">
                                <?php else: ?>
                                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 36px; height: 36px; background: linear-gradient(135deg, #0A1128, #1E293B); border: 1px solid rgba(223,183,92,0.4); color: #DFB75C; font-size: 13px; flex-shrink: 0;">
                                        <?= e($revInitial) ?>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <strong class="small d-block text-dark"><?= e($rev['author_name']) ?></strong>
                                    <div class="text-warning small" style="font-size: 11px;">
                                        <?php for($i=0; $i<(int)$rev['rating']; $i++): ?><i class="bi bi-star-fill"></i><?php endfor; ?>
                                        <span class="text-muted ms-1">(<?= (int)$rev['rating'] ?>/5)</span>
                                    </div>
                                    <?php if ($rev['is_verified_purchase']): ?>
                                        <span class="badge bg-success-subtle text-success mt-1" style="font-size: 9px;"><i class="bi bi-patch-check me-1"></i>Verified</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td style="max-width: 320px;">
                            <?php if (!empty($rev['title'])): ?>
                                <div class="fw-bold small text-dark"><?= e($rev['title']) ?></div>
                            <?php endif; ?>
                            <p class="text-secondary small m-0 mb-1 text-truncate" title="<?= e($rev['comment']) ?>"><?= e($rev['comment']) ?></p>
                            <?php if (!empty($wristshots) && is_array($wristshots)): ?>
                                <div class="d-flex gap-1 align-items-center">
                                    <span class="text-muted" style="font-size: 10px;"><i class="bi bi-camera me-1"></i>Photo:</span>
                                    <?php foreach ($wristshots as $wImg): ?>
                                        <a href="<?= e($wImg) ?>" target="_blank">
                                            <img src="<?= e($wImg) ?>" class="rounded border" style="width: 32px; height: 32px; object-fit: cover;" alt="Wristshot">
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="small text-muted text-nowrap">
                            <?= date('d M Y', strtotime($rev['created_at'])) ?>
                        </td>
                        <td>
                            <?php if ($rev['status'] === 'approved'): ?>
                                <span class="badge bg-success-subtle text-success">Approved</span>
                            <?php elseif ($rev['status'] === 'pending'): ?>
                                <span class="badge bg-warning-subtle text-warning">Pending</span>
                            <?php else: ?>
                                <span class="badge bg-danger-subtle text-danger">Rejected</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end text-nowrap">
                            <div class="d-inline-flex gap-1">
                                <button type="button" class="btn btn-sm btn-outline-primary" title="Edit Review" data-bs-toggle="modal" data-bs-target="#editReviewModal<?= (int)$rev['id'] ?>">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <?php if ($rev['status'] !== 'approved'): ?>
                                <form action="/admin/reviews/<?= (int)$rev['id'] ?>/status" method="POST" class="m-0">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="status" value="approved">
                                    <button class="btn btn-sm btn-outline-success" title="Approve"><i class="bi bi-check-lg"></i></button>
                                </form>
                                <?php endif; ?>

                                <?php if ($rev['status'] !== 'rejected'): ?>
                                <form action="/admin/reviews/<?= (int)$rev['id'] ?>/status" method="POST" class="m-0">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="status" value="rejected">
                                    <button class="btn btn-sm btn-outline-warning" title="Reject"><i class="bi bi-slash-circle"></i></button>
                                </form>
                                <?php endif; ?>

                                <form action="/admin/reviews/<?= (int)$rev['id'] ?>/delete" method="POST" class="m-0" onsubmit="return confirm('Delete this review permanently?');">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>

                            <!-- EDIT REVIEW MODAL -->
                            <div class="modal fade text-start" id="editReviewModal<?= (int)$rev['id'] ?>" tabindex="-1" aria-labelledby="editReviewModalLabel<?= (int)$rev['id'] ?>" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h6 class="modal-title fw-bold" id="editReviewModalLabel<?= (int)$rev['id'] ?>">Edit Review #<?= (int)$rev['id'] ?> &bull; <?= e($rev['product_name']) ?></h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="/admin/reviews/<?= (int)$rev['id'] ?>/update?_csrf_token=<?= e(csrf_token()) ?>" method="POST" enctype="multipart/form-data">
                                            <?= csrf_field() ?>
                                            <div class="modal-body">
                                                <div class="row g-2 mb-2">
                                                    <div class="col-7">
                                                        <label class="form-label small fw-semibold">Reviewer Name *</label>
                                                        <input type="text" name="author_name" class="form-control form-control-sm" value="<?= e($rev['author_name']) ?>" required>
                                                    </div>
                                                    <div class="col-5">
                                                        <label class="form-label small fw-semibold">Rating</label>
                                                        <select name="rating" class="form-select form-select-sm">
                                                            <?php for($star=5; $star>=1; $star--): ?>
                                                                <option value="<?= $star ?>" <?= (int)$rvStar = ((int)$rev['rating'] === $star ? 'selected' : ''); echo $rvStar; ?>><?= $star ?> Stars (<?= str_repeat('★', $star) ?>)</option>
                                                            <?php endfor; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row g-2 mb-2">
                                                    <div class="col-6">
                                                        <label class="form-label small fw-semibold">Moderation Status</label>
                                                        <select name="status" class="form-select form-select-sm">
                                                            <option value="approved" <?= ($rev['status'] ?? '') === 'approved' ? 'selected' : '' ?>>Approved (Published)</option>
                                                            <option value="pending" <?= ($rev['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending Moderation</option>
                                                            <option value="rejected" <?= ($rev['status'] ?? '') === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-6 d-flex align-items-center pt-3">
                                                        <div class="form-check m-0">
                                                            <input class="form-check-input" type="checkbox" name="is_verified_purchase" value="1" id="m_rv_ver_<?= (int)$rev['id'] ?>" <?= !empty($rev['is_verified_purchase']) ? 'checked' : '' ?>>
                                                            <label class="form-check-label small fw-semibold" for="m_rv_ver_<?= (int)$rev['id'] ?>">Verified Purchase</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-2">
                                                    <label class="form-label small fw-semibold">Review Title</label>
                                                    <input type="text" name="title" class="form-control form-control-sm" value="<?= e($rev['title'] ?? '') ?>">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Review Comment *</label>
                                                    <textarea name="comment" class="form-control form-control-sm" rows="3" required><?= e($rev['comment']) ?></textarea>
                                                </div>
                                                <div class="row g-2 mb-2">
                                                    <div class="col-6">
                                                        <label class="form-label small fw-semibold">Reviewer Photo</label>
                                                        <input type="file" name="avatar_file" class="form-control form-control-sm" accept="image/*">
                                                        <input type="text" name="avatar_url" class="form-control form-control-sm mt-1" value="<?= e($rev['avatar_url'] ?? '') ?>" placeholder="Or image URL">
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label small fw-semibold">Received Watch Photo</label>
                                                        <input type="file" name="review_image" class="form-control form-control-sm" accept="image/*">
                                                        <input type="text" name="wristshot_url" class="form-control form-control-sm mt-1" value="<?= e(!empty($wristshots) ? $wristshots[0] : '') ?>" placeholder="Or image URL">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer py-2">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary btn-sm fw-bold">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
