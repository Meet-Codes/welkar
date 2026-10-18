<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Welkar Admin Portal') ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/favicon.png">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .auth-card {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border-radius: 12px;
            padding: 35px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.25);
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="text-center mb-4">
            <a href="/" class="d-inline-block mb-2">
                <img src="/assets/images/welkar-logo.png" alt="Welkar" style="height: 48px; width: auto; object-fit: contain;">
            </a>
            <div class="text-muted small">Administration System</div>
        </div>

        <?php if ($error = flash('error')): ?>
            <div class="alert alert-danger py-2 small"><?= e($error) ?></div>
        <?php endif; ?>
        <?php if ($info = flash('info')): ?>
            <div class="alert alert-info py-2 small"><?= e($info) ?></div>
        <?php endif; ?>

        <?= $content ?>
    </div>
</body>
</html>
