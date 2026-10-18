# Welkar — Premium Watch E-Commerce Platform

Welcome to the **Welkar** e-commerce platform codebase, designed and built according to the comprehensive specifications in `welkar-task.md`.

---

## 1. System Overview & Technology Stack

- **Target Market**: India (`INR ₹`)
- **Language**: English (Default) with Hindi (`hi`) fallback
- **Backend Architecture**: Core PHP 8.3+ with custom MVC routing, Dependency-Free PSR-4 Autoloader, PDO database service with transaction wrapping, strict `DECIMAL` monetary math, immutable order snapshots.
- **Database Engine**: MySQL 8.0+ / MariaDB 10.11+ (`InnoDB`, `utf8mb4_unicode_ci`)
- **Frontend**: Bootstrap 5, Modern Google Fonts (*Plus Jakarta Sans* & *Outfit*), CSS3 animations, Vanilla JavaScript.
- **Deployment Compatibility**: Production-ready for standard Apache/Nginx shared hosting environments without requiring a Node.js runtime.

---

## 2. Directory Structure

```text
welkar/
├── app/
│   ├── Controllers/
│   │   ├── Storefront/       # Home, Catalog, Product PDP, Cart, Checkout, Pages
│   │   ├── Admin/            # 14 Admin Portal Modules (Dashboard, Orders, Importer...)
│   │   ├── Auth/             # Customer Phone OTP & Admin MFA Controllers
│   │   └── Webhooks/         # Razorpay Webhook Signature Verification
│   ├── Services/
│   │   ├── Catalog/          # Filtering, dial styles, search autocomplete
│   │   ├── Cart/             # Cart session management & stock revalidation
│   │   ├── Pricing/          # GST (Intra/Inter-state), Coupons, Shipping threshold
│   │   ├── Orders/           # Order state machine, stock deduction, cancellation
│   │   ├── Payments/         # Razorpay Gateway adapter & signature verification
│   │   ├── Inventory/        # Stock ledger movements & reservations
│   │   ├── Invoices/         # Sequential tax invoicing (WEL-2026-XXXX)
│   │   ├── ImportExport/     # CSV product bulk importer with dry-run validation
│   │   ├── Analytics/        # First-party commerce tracking & UTM attribution
│   │   └── Auth/             # Bcrypt admin authentication & OTP challenges
│   ├── Middleware/           # CSRF protection, Customer Auth, Admin RBAC
│   └── Support/              # Autoloader, Env, Config, Database, Request, Response, View, Session
├── config/                   # App, database, and route mappings
├── database/
│   ├── migrations/           # 001_create_welkar_schema.sql (65 normalized tables)
│   └── seeders/              # 001_seed_master_data.sql (Realistic watches, dial styles, admins)
├── public/
│   ├── index.php             # Front controller entry point
│   ├── robots.txt            # Search engine indexing directives
│   └── .htaccess             # Apache URL rewriting rules
├── resources/
│   ├── views/                # Storefront, Admin, Auth, Error views
│   ├── languages/            # en.php and hi.php translation dictionaries
│   └── templates/            # invoice.php printable GST tax invoice template
├── storage/
│   ├── logs/                 # Encrypted/redacted error logs
│   ├── invoices/             # Stored invoice records
│   ├── imports/              # Uploaded bulk CSV batches
│   └── backups/              # Database dump snapshots
├── scripts/                  # backup.php & restore.php utilities
├── tests/                    # run_tests.php automated test suite
├── requirements-checklist.md # Complete project requirements status tracking
├── .env.example              # Configuration environment template
└── welkar-task.md            # Original master specification
```

---

## 3. Quickstart & Local Setup

### Step 1: Clone and Configure Environment
Copy `.env.example` to `.env` and configure your local MariaDB / MySQL credentials:
```bash
cp .env.example .env
```

### Step 2: Run Database Migrations & Seeders
Execute the SQL files located in `database/`:
```bash
mariadb -u root welkar_db < database/migrations/001_create_welkar_schema.sql
mariadb -u root welkar_db < database/seeders/001_seed_master_data.sql
```

### Step 3: Run the Local PHP Development Server
Start the built-in PHP development server pointing to `public/`:
```bash
php -S localhost:8000 -t public
```
- Storefront: `http://localhost:8000/`
- Admin Portal: `http://localhost:8000/admin`
  - Default Admin Username: `admin`
  - Default Admin Password: `admin@welkar123`

---

## 4. Key Highlights & Features

1. **"Pick Your Perfect Dial"**:
   Interactive dial architecture cards (Classic Roman, Minimalist Stick, Chronograph, Automatic Skeleton, Moon Phase, Digital) filtering the catalog directly.
2. **Live Animated Watch Clock Simulation**:
   For supported analog watches (e.g. *Welkar Aura White Silver Steel*), the dial renders real-time hour, minute, and smooth sweeping second hands synchronized to the visitor's local device time. Includes static photo fallback.
3. **Indian GST & Checkout Engine**:
   - Intra-state delivery (Maharashtra -> Maharashtra) computes **CGST (9%) + SGST (9%)**.
   - Inter-state delivery computes **IGST (18%)**.
   - Free Express Shipping on orders over ₹1,499 (Flat ₹60 fee applied below threshold).
   - Real-time cart progress indicator shows how much is needed to unlock free shipping.
4. **Sequential Tax Invoicing**:
   Automatic allocation of concurrency-safe invoice sequences (e.g. `WEL-2026-27-1001`) with printable PDF/HTML invoice templates.
5. **Admin Portal (14 Modules)**:
   Executive Dashboard, Orders Lifecycle, Cart Abandonment Recovery, Reports & Business Analytics, Products with Dial Animation Calibration, Categories, Product Performance, Customer CRM, Offer Campaigns, Coupons, Banners, Social Media & Campaign Hub, Website Analytics Intelligence (replacing physical POS), and Store Settings.
6. **Bulk Product Importer**:
   CSV template generator, column mapping, dry-run row-level error validation, formula injection protection, and batch upsert modes.

---

## 5. Automated Testing

To run the full functional and security test suite:
```bash
php tests/run_tests.php
```
Test suite validates:
- Database relational integrity across all tables
- Intra-state vs Inter-state GST calculations
- Shipping fee threshold triggers
- Coupon discount logic & tax re-basis
- Inventory ledger balances & atomic order deductions
- Sequential invoice allocation
- Admin bcrypt authentication & session security
- CSV formula injection sanitization

---

## 6. Shared Hosting Deployment Guide

1. Upload the project files to your shared hosting account.
2. Set the web root / document root of your domain to the `public/` directory.
3. Ensure folders `storage/logs/`, `storage/invoices/`, `storage/imports/`, and `storage/backups/` have write permissions (`chmod 755`).
4. Import `database/migrations/001_create_welkar_schema.sql` and `database/seeders/001_seed_master_data.sql` via cPanel phpMyAdmin or SSH.
5. Update `.env` with production database credentials and your verified Razorpay / SMTP keys.

---

## 7. Owner-Supplied Information Required Prior to Live Launch

The platform is configured in **Test Mode** with unconfigured indicators where external provider credentials are required. Before going live, the business owner must provide:
1. **GSTIN & Official Registered Address**: To be entered in `/admin/settings`.
2. **Razorpay Live API Keys**: Key ID, Key Secret, and Webhook Secret for live transactions.
3. **Gmail SMTP App Password**: In `.env` for transactional confirmation emails.
4. **SMS / WhatsApp Gateway**: If automated SMS or WhatsApp notifications are desired.
5. **Meta Pixel ID & Google Analytics 4 Measurement ID**: For external ad conversion tracking.
