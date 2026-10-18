<div class="text-center mb-3" style="font-family: var(--font-ui, 'Inter', sans-serif);">
    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 56px; height: 56px; background: rgba(154, 121, 80, 0.1); color: var(--color-accent-700, #856744); border: 1px solid var(--color-border-default, #DDD9D0);">
        <i class="bi bi-shield-lock fs-3"></i>
    </div>
    <h5 class="fw-bold mt-2 text-dark" style="font-family: var(--font-display, 'DM Serif Display', serif);">Two-Factor Authentication</h5>
    <p class="text-muted small">Enter the 6-digit MFA security code generated for your administrator account.</p>
</div>

<?php if (!empty($debugCode)): ?>
<div class="py-2 small text-center mb-3" style="background: var(--color-surface-50, #F8F6F1); border-radius: 6px; border: 1px dashed var(--color-border-default, #DDD9D0); font-family: var(--font-ui, 'Inter', sans-serif);">
    <span class="text-muted">[Local Test Mode Only]</span> <strong>MFA Code:</strong> <code style="color: var(--color-accent-700, #856744); font-weight: bold;"><?= e($debugCode) ?></code>
</div>
<?php endif; ?>

<form action="/admin/mfa-verify" method="POST" style="font-family: var(--font-ui, 'Inter', sans-serif);">
    <?= csrf_field() ?>
    <div class="mb-4">
        <label class="form-label small fw-semibold text-dark">6-Digit Security Code</label>
        <input type="text" name="code" class="form-control text-center fs-4 fw-bold" placeholder="••••••" maxlength="6" required autofocus style="height: 48px; border-radius: 6px; border-color: var(--color-border-default, #DDD9D0); letter-spacing: 6px;">
    </div>
    <button type="submit" class="btn btn-welkar w-100" style="height: 48px; border-radius: 6px; font-weight: 600; font-size: 14px;">
        Verify Authentication &rarr;
    </button>
</form>
