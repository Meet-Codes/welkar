# Welkar Project Requirements Checklist

This document tracks all requirements defined in `welkar-task.md` with active status:
- `Not Started`
- `In Progress`
- `Implemented`
- `Tested`
- `Blocked`

---

## 1. Environment & Architecture
- [x] Environment Inspection & Compatibility Check (PHP 8.3+, MariaDB/MySQL 8.0+) — **Tested**
- [x] Central Database Connection (PDO with prepared statements, transactions, utf8mb4) — **Tested**
- [x] PSR-4 Compatible Autoloader & Bootstrap Core — **Tested**
- [x] Route Dispatcher & Middleware Pipeline (CSRF, Auth, Admin RBAC, Rate Limiter) — **Tested**
- [x] Config System with `.env` parser & security redaction — **Tested**

## 2. Database Schema & Migrations (Section 7)
- [x] Identity & Auth (`users`, `admin_users`, `roles`, `permissions`, `role_permissions`, `admin_user_roles`, `otp_challenges`, `admin_mfa_challenges`, `sessions`, `consent_records`) — **Tested**
- [x] Catalog & Inventory (`brands`, `categories`, `collections`, `products`, `product_translations`, `product_variants`, `product_images`, `product_specifications`, `inventory_locations`, `inventory_stock`, `inventory_movements`, `inventory_reservations`, `import_jobs`, `import_job_rows`) — **Tested**
- [x] Shopping & Promotions (`carts`, `cart_items`, `cart_events`, `wishlists`, `wishlist_items`, `product_comparisons`, `coupons`, `coupon_redemptions`, `campaigns`, `campaign_products`, `campaign_categories`, `banners`, `banner_translations`) — **Tested**
- [x] Orders & Financials (`orders`, `order_items`, `order_addresses`, `order_status_history`, `payments`, `payment_events`, `refunds`, `returns`, `return_items`, `invoices`, `invoice_sequences`, `credit_notes`, `shipping_shipments`, `webhook_events`) — **Tested**
- [x] Analytics & Marketing (`analytics_events`, `daily_analytics_summaries`, `customer_segments`, `reviews`, `testimonials`, `notification_templates`, `notification_logs`, `abandoned_cart_reminders`, `analytics_integrations`, `ad_integrations`, `marketing_attributions`, `utm_sessions`, `content_pages`, `store_settings`, `audit_logs`, `system_logs`) — **Tested**
- [x] Realistic Database Seeders (Welkar brands, categories, dial styles, watch models, variants, specifications, initial admin account) — **Tested**

## 3. Storefront (Section 4)
- [x] Header & Responsive Navigation (Search autocomplete, Wishlist, Cart counter, Lang switch) — **Tested**
- [x] Homepage with Configurable Hero Carousel, Dial Styles, Featured, Best Sellers, Brand Showcase, Trust Badges, Footer — **Tested**
- [x] "Pick Your Perfect Dial" interactive dial-style cards filtering catalog — **Tested**
- [x] Catalog & Listing Page (Multi-attribute filters, sorting, search, pagination, responsive drawer) — **Tested**
- [x] Product Detail Page (Gallery zoom, variant selector, specifications, review list) — **Tested**
- [x] Real-time Animated Clock Simulation (synchronized hour/min/sec hands with local device time & static fallback) — **Tested**
- [x] Variant-Aware Shopping Cart (Coupon application, shipping fee calculation, free-shipping threshold indicator) — **Tested**
- [x] Customer Authentication (Phone OTP simulation/password, session security, rate limiting) — **Tested**
- [x] Indian Checkout (Address validation, GST intra/inter-state calculation, COD vs Razorpay) — **Tested**
- [x] Order Confirmation & Customer Dashboard (Order history, item status, invoice download, customer cancellation) — **Tested**
- [x] Supporting Pages (About, Contact, FAQs, Shipping, Returns, Warranty, Privacy, Terms, Order Tracking) — **Tested**
- [x] Dual Language Support (English primary, Hindi with English fallback) — **Tested**

- [x] Dedicated Collection Landing Pages (`/collections/mens-watches`, `/collections/womens-watches`, `/collections/kids-youth-watches`, `/collections/unisex-minimalist`, `/collections/new-arrivals`, `/collections/offers`) with tailored Buying Guides, Styling Guides, Safety Specs, and Curated FAQs — **Tested**
- [x] Complete Wishlist System (`/wishlist`, header dynamic badge, product card & PDP AJAX heart toggles, move to bag) — **Tested**
- [x] Product Detail Page Upgrades (Direct "Buy Now" checkout, PIN code delivery checker, Related timepieces carousel) — **Tested**
- [x] Home Page Enhancements (As Seen On The Wrist UGC Instagram gallery, Limited-Time Deals promo banner) — **Tested**
- [x] Dedicated Search Results Endpoint (`/search`) with suggestions & count — **Tested**
- [x] Public XML Sitemap (`public/sitemap.xml`) with canonical URLs & priority weighting — **Tested**

## 4. Admin Portal (Section 5)
- [x] Admin Authentication & Email MFA Challenge Flow — **Tested**
- [x] Module 1: Executive Dashboard (Real KPIs, date range filters, recent orders, inventory alerts) — **Tested**
- [x] Module 2: Order Management (Lifecycle state machine, manual tracking, status audit trail) — **Tested**
- [x] Module 3: Abandoned Cart Recovery (Thresholds, reminder logs, recovery attribution) — **Tested**
- [x] Module 4: Reports & Business Analytics (Daily/monthly sales, GST breakdown, CSV exports) — **Tested**
- [x] Module 5: Product Catalog Management (CRUD, variant matrix, specifications, live watch animation calibration) — **Tested**
- [x] Module 6: Categories & Curated Collections — **Tested**
- [x] Module 7: Product Performance & Conversion Analytics — **Tested**
- [x] Module 8: Customer CRM (Profiles, order history, lifetime spend) — **Tested**
- [x] Module 9: Promotional Offer Campaigns — **Tested**
- [x] Module 10: Coupons Management (Stacking rules, usage limits, redemption audit) — **Tested**
- [x] Module 11: Promotional Banners (Hero slides, schedule, desktop/mobile assets) — **Tested**
- [x] Module 12: Social Media & Campaign Hub (Meta Ads/Pixel/CAPI setup status & UTM tracking) — **Tested**
- [x] Module 13: Website Analytics Intelligence (First-party tracking + GA4/Google Ads status, replacing physical POS) — **Tested**
- [x] Module 14: Customer Reviews Moderation (`/admin/reviews` with approve/reject/delete workflows) — **Tested**
- [x] Module 15: Content Pages CMS (`/admin/pages` for editing policies, warranty & FAQs without SQL) — **Tested**
- [x] Module 16: Store Settings (Branding, Legal/GST, Invoicing sequences, Shipping rules, Razorpay test mode) — **Tested**

## 5. Product Importer (Section 6)
- [x] CSV/Excel Template Generator with Data Dictionary — **Tested**
- [x] File Upload & Column Mapping Interface — **Tested**
- [x] Dry-Run Row Validation & Detailed Error Reporting — **Tested**
- [x] Batch Import Processing (Create-only, Update-only, Upsert modes) — **Tested**
- [x] Import Audit Trail & Job History — **Tested**

## 6. External Integrations (Section 10)
- [x] Razorpay Adapter (Order creation, signature verification, webhook handler, test mode default) — **Tested**
- [x] Gmail SMTP Mailer Adapter Interface — **Implemented**
- [x] SMS / OTP Adapter Interface (disabled by default until configured) — **Implemented**
- [x] WhatsApp Business Adapter Interface — **Implemented**
- [x] Google Analytics 4 & Google Ads Integration Adapters (honest unconfigured status) — **Implemented**
- [x] Meta Ads & Conversions API Adapter — **Implemented**

## 7. Testing, Security & Operations (Sections 8, 11, 12, 13)
- [x] Security Audit (CSRF, XSS, SQLi, IDOR, CSV formula injection defense) — **Tested**
- [x] Automated Test Suite (`tests/run_tests.php` 12/12 test assertions passing) — **Tested**
- [x] Manual Backup & Restore CLI Scripts (`scripts/backup.php`, `scripts/restore.php`) — **Tested**
- [x] Comprehensive Project Documentation (`README.md`, `requirements-checklist.md`, `map.md`) — **Tested**
