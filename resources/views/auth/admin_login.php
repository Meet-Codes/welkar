<form action="/admin/login" method="POST" style="font-family: var(--font-ui, 'Inter', sans-serif);">
    <?= csrf_field() ?>
    <div class="mb-3">
        <label class="form-label small fw-semibold text-dark">Admin Username or Email</label>
        <input type="text" name="username" class="form-control" placeholder="admin or email" value="admin" required style="height: 48px; border-radius: 6px; border-color: var(--color-border-default, #DDD9D0); font-size: 14px;">
    </div>
    <div class="mb-4">
        <label class="form-label small fw-semibold text-dark">Password</label>
        <input type="password" name="password" class="form-control" placeholder="••••••••" value="admin@welkar123" required style="height: 48px; border-radius: 6px; border-color: var(--color-border-default, #DDD9D0); font-size: 14px;">
    </div>
    <button type="submit" class="btn btn-welkar w-100" style="height: 48px; border-radius: 6px; font-weight: 600; font-size: 14px;">
        <i class="bi bi-shield-lock me-2"></i>Sign In to Portal
    </button>
</form>
