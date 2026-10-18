<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Temporarily Unavailable | Welkar Timepieces</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #0B0B0C;
            color: #F5F5F5;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }
        .error-card {
            background: #141416;
            border: 1px solid #2A2A2E;
            border-radius: 12px;
            max-width: 580px;
            width: 100%;
            padding: 3rem 2.5rem;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }
        .btn-welkar {
            background: #E63946;
            color: #FFFFFF;
            border: none;
            padding: 12px 28px;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: opacity 0.2s;
        }
        .btn-welkar:hover {
            background: #D32F2F;
            color: #FFFFFF;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="mb-4">
            <span class="fs-3 fw-bold text-uppercase" style="font-family: 'DM Serif Display', Georgia, serif; letter-spacing: 2px; color: #F5F5F5;">WELKAR</span>
            <small class="d-block text-uppercase" style="font-size: 10px; letter-spacing: 2px; color: #C59B3F;">Haute Horlogerie</small>
        </div>
        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 72px; height: 72px; background: rgba(230, 57, 70, 0.15); border: 1px solid rgba(230, 57, 70, 0.3); color: #E63946;">
            <i class="bi bi-clock-history fs-2"></i>
        </div>
        <h1 class="h3 fw-normal mb-2" style="font-family: 'DM Serif Display', Georgia, serif; color: #F5F5F5;">Atelier Calibration in Progress</h1>
        <p class="small mb-4" style="color: #A0A0A5; line-height: 1.7;">
            Our servers encountered a brief synchronicity disruption. Our engineering team has been automatically alerted and the connection is being re-established.
        </p>

        <div class="p-3 mb-4 text-start rounded" style="background: #1A1A1C; border: 1px solid #2A2A2E; font-size: 13px;">
            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-telephone text-warning"></i>
                <span style="color: #A0A0A5;">Concierge Desk:</span>
                <a href="tel:+919820012345" class="text-decoration-none fw-semibold" style="color: #F5F5F5;">+91 98200 12345</a>
            </div>
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-envelope text-warning"></i>
                <span style="color: #A0A0A5;">Direct Dispatch:</span>
                <a href="mailto:concierge@welkar.in" class="text-decoration-none fw-semibold" style="color: #F5F5F5;">concierge@welkar.in</a>
            </div>
        </div>

        <div class="d-flex flex-wrap justify-content-center gap-2">
            <a href="/" class="btn-welkar">
                <i class="bi bi-arrow-left"></i> Return to Welkar Homepage
            </a>
            <button onclick="window.location.reload();" class="btn btn-outline-light px-4 py-2" style="border-radius: 6px; border-color: #2A2A2E; font-weight: 500;">
                <i class="bi bi-arrow-clockwise me-1"></i> Retry
            </button>
        </div>
    </div>
</body>
</html>
