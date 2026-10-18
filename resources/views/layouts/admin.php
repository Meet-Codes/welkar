<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Welkar Admin Portal') ?></title>
    <!-- Google Fonts: Inter (design.md locked UI font) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/favicon.png">
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--color-surface-50, #F5F7FB);
            color: var(--color-text-primary, #0D1527);
            -webkit-font-smoothing: antialiased;
        }
        .admin-sidebar {
            width: 260px;
            background: var(--color-brand-950, #0A1128);
            color: #E3E3E9;
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            overflow-y: auto;
            z-index: 1000;
            border-right: 1px solid rgba(255, 255, 255, 0.08);
        }
        .admin-brand {
            padding: 20px 24px;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 2px;
            color: #ffffff;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
        .admin-nav-item {
            display: flex;
            align-items: center;
            padding: 11px 24px;
            color: #BEB8AC;
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: all 0.15s ease;
        }
        .admin-nav-item i {
            font-size: 17px;
            margin-right: 12px;
            width: 20px;
        }
        .admin-nav-item:hover, .admin-nav-item.active {
            color: #ffffff;
            background-color: #292F2B;
        }
        .nav-section-title {
            padding: 16px 24px 6px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #9A7950;
        }
        .admin-content {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .admin-topbar {
            height: 64px;
            background: #ffffff;
            border-bottom: 1px solid #DDD9D0;
            padding: 0 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        .card-kpi {
            border: 1px solid #DDD9D0;
            border-radius: 8px;
            background: #ffffff;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(32,37,34,0.03);
        }
        .card-kpi .kpi-label {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #626760;
            margin-bottom: 6px;
        }
        .card-kpi .kpi-val {
            font-size: 24px;
            font-weight: 700;
            color: #202522;
        }
    </style>
</head>
<body>
    <!-- Sidebar Navigation -->
    <aside class="admin-sidebar">
        <div class="admin-brand d-flex align-items-center justify-content-between py-3">
            <a href="/admin" class="d-inline-flex align-items-center text-decoration-none">
                <img src="/assets/images/welkar-logo-white.png" alt="Welkar" style="height: 30px; width: auto; object-fit: contain;">
            </a>
            <span class="badge bg-secondary" style="font-size: 10px;">ADMIN</span>
        </div>

        <nav class="py-2">
            <div class="nav-section-title">Operations</div>
            <a href="/admin" class="admin-nav-item">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="/admin/orders" class="admin-nav-item">
                <i class="bi bi-box-seam"></i> Orders Management
            </a>
            <a href="/admin/payments" class="admin-nav-item">
                <i class="bi bi-credit-card-2-front"></i> Payments &amp; Gateway
            </a>
            <a href="/admin/support" class="admin-nav-item">
                <i class="bi bi-headset"></i> Customer Support &amp; Tickets
            </a>
            <a href="/admin/products" class="admin-nav-item">
                <i class="bi bi-watch"></i> Products &amp; Stock
            </a>
            <a href="/admin/brands" class="admin-nav-item">
                <i class="bi bi-award"></i> Brand Portfolio
            </a>
            <a href="/admin/categories" class="admin-nav-item">
                <i class="bi bi-grid"></i> Categories &amp; Dials
            </a>
            <a href="/admin/banners" class="admin-nav-item">
                <i class="bi bi-images"></i> Hero Banners
            </a>
            <a href="/admin/reviews" class="admin-nav-item">
                <i class="bi bi-star"></i> Customer Reviews
            </a>
            <a href="/admin/importer" class="admin-nav-item">
                <i class="bi bi-cloud-arrow-up"></i> Bulk Product Importer
            </a>

            <div class="nav-section-title">Growth &amp; Marketing</div>
            <a href="/admin/marketing" class="admin-nav-item">
                <i class="bi bi-tag"></i> Coupons &amp; Carts
            </a>
            <a href="/admin/analytics" class="admin-nav-item">
                <i class="bi bi-graph-up-arrow"></i> Analytics Intelligence
            </a>
            <a href="/admin/customers" class="admin-nav-item">
                <i class="bi bi-people"></i> Customer CRM
            </a>

            <div class="nav-section-title">Configuration</div>
            <a href="/admin/company-profile" class="admin-nav-item">
                <i class="bi bi-building"></i> Company Profile &amp; Story
            </a>
            <a href="/admin/pages" class="admin-nav-item">
                <i class="bi bi-file-earmark-richtext"></i> Content Pages CMS
            </a>
            <a href="/admin/staff" class="admin-nav-item">
                <i class="bi bi-person-badge"></i> Staff &amp; Roles
            </a>
            <a href="/admin/audit-logs" class="admin-nav-item">
                <i class="bi bi-journal-text"></i> Audit Trail
            </a>
            <a href="/admin/settings" class="admin-nav-item">
                <i class="bi bi-gear"></i> Store Settings &amp; GST
            </a>
            <a href="/" target="_blank" class="admin-nav-item">
                <i class="bi bi-arrow-up-right-square"></i> View Storefront
            </a>
            <a href="/admin/logout" class="admin-nav-item text-danger">
                <i class="bi bi-box-arrow-right"></i> Sign Out
            </a>
        </nav>
    </aside>

    <!-- Main Container -->
    <div class="admin-content">
        <!-- Topbar -->
        <header class="admin-topbar">
            <div>
                <span class="fw-bold fs-6"><?= e($title ?? 'Admin Console') ?></span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-light text-dark border">
                    <i class="bi bi-shield-check text-success me-1"></i><?= e(admin_user()['full_name'] ?? 'Admin') ?> (<?= ucfirst(admin_user()['role_slug'] ?? 'Owner') ?>)
                </span>
                <a href="/admin/logout" class="btn btn-sm btn-outline-danger" title="Logout">
                    <i class="bi bi-power"></i>
                </a>
            </div>
        </header>

        <!-- Flash alerts -->
        <div class="container-fluid px-4 pt-3">
            <?php if ($success = flash('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i><?= e($success) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if ($error = flash('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i><?= e($error) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if ($info = flash('info')): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i><?= e($info) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
        </div>

        <!-- Body content -->
        <div class="p-4 flex-grow-1">
            <?= $content ?>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
