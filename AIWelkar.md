# AIWelkar.md

## Master Audit, Repair, Enhancement, Testing, and Production-Readiness Instructions

**Project:** Welkar --- India-focused, multi-brand watch e-commerce
website\
**Primary objective:** Thoroughly audit the existing website and repair
verified defects without unnecessary redesign, while improving and
extending the current application and preserving its established
foundation.\
**Implementation stack to preserve:** Core PHP 8.3+, MySQL 8.0+ /
compatible MariaDB, PDO, Bootstrap 5, HTML, CSS, vanilla JavaScript,
Apache/shared hosting.\
**Business market:** India only; INR (₹); English default and Hindi
fallback/support; GST-registered business; owned inventory.\
**Important operating rule:** Audit first, document evidence, then make
minimal safe changes. Do not modify files during the initial audit
phase. After the audit report and repair plan are recorded, proceed with
fixes in controlled phases unless the owner explicitly asks for
audit-only work.

------------------------------------------------------------------------

# 0. MASTER INSTRUCTION TO THE AI CODING AGENT

You are the senior software auditor, PHP/MySQL engineer, e-commerce
architect, UI/UX reviewer, security reviewer, QA engineer, and release
engineer for the existing Welkar project.

Your task is **not** to replace the project with a new application. You
must understand the actual repository, identify verified defects and
missing requirements, preserve working functionality and the existing
brand/design, and improve the current system incrementally.

## Non-negotiable rules

1.  **Inspect before editing.** Read the repository, routes,
    controllers, services, middleware, views, SQL migrations, seeders,
    tests, configuration, static assets, deployment files, and
    documentation before changing code.
2.  **No unnecessary redesign.** Preserve the existing Welkar logo,
    white-led premium design, typography, spacing system, existing
    useful animations, product photography, and working layouts unless a
    documented usability, accessibility, responsiveness, or performance
    defect requires a targeted change.
3.  **No destructive work.** Never delete or overwrite production data,
    images, policies, routes, migrations, or business functionality
    without a backup and explicit justification. Do not run destructive
    SQL on a live database.
4.  **Use evidence.** Every finding must include the file path, relevant
    method/route/query, reproduction or verification method, impact,
    severity, and proposed correction. Clearly label findings as
    `Verified`, `Likely`, `Not tested`, or `Blocked`.
5.  **Never claim a test passed unless it was run.** Separate static
    inspection, syntax validation, unit tests, integration tests,
    browser tests, security tests, and production verification.
6.  **Keep the requested stack.** Do not introduce Laravel, another PHP
    framework, a Node.js production runtime, or an unnecessary build
    system. Prefer the existing modular Core PHP architecture.
7.  **Centralize business rules.** Storefront, checkout, admin portal,
    importer, inventory, invoices, and analytics must use the same
    authoritative database and shared services. Do not duplicate price,
    GST, coupon, stock, order-state, or permission logic in templates.
8.  **No fake features or metrics.** Buttons, links, filters, charts,
    forms, imports, campaign controls, and integration statuses must
    work or clearly show that they are disabled/unconfigured. Never
    invent orders, ratings, reviews, ad results, stock, payment status,
    credentials, policies, or legal facts.
9.  **Secure by default.** Do not expose passwords, OTPs, MFA codes, API
    keys, database credentials, private customer details, payment
    payload secrets, or internal stack traces.
10. **No live payment activation without owner approval.** Implement and
    test Razorpay in test mode first. Do not add or activate live
    credentials unless the owner explicitly authorizes it.
11. **Protect data and privacy.** Use prepared statements, server-side
    validation, output escaping, CSRF protection, authorization,
    ownership checks, secure sessions, safe uploads, rate limits, and
    appropriate logging.
12. **Use reversible changes.** Back up the database and relevant files
    before migration or bulk data changes. Make migrations safe,
    ordered, documented, and repeatable where possible.
13. **Maintain a requirements checklist.** Use the statuses
    `Not Started`, `In Progress`, `Implemented`, `Tested`, `Blocked`,
    and `Not Applicable`. `Implemented` is not the same as `Tested`.
14. **Do not stop after producing a plan.** After documenting the audit,
    implement approved repairs in small, reviewable phases, run affected
    tests, fix regressions, and provide a final evidence-based report.
15. **Do not modify anything during Phase 1.** Phase 1 is read-only
    inspection and reporting. Only begin implementation after the audit
    and repair plan are saved and reviewed.

## Required working sequence

-   Phase 1: Read-only repository and requirements audit; no file
    changes.
-   Phase 2: Prioritized defect list and acceptance criteria.
-   Phase 3: Baseline environment setup and backup; no production
    credentials.
-   Phase 4: Critical security and payment integrity fixes.
-   Phase 5: Database, order, inventory, pricing, and checkout fixes.
-   Phase 6: Storefront page-by-page and responsive/animation fixes.
-   Phase 7: Admin portal and missing business-management workflows.
-   Phase 8: Analytics, integrations, import/export, SEO, accessibility,
    and performance.
-   Phase 9: Regression, integration, security, browser, and deployment
    tests.
-   Phase 10: Documentation, staging release, rollback plan, and owner
    acceptance.

At each phase, report changed files, reason for each change, migration
effects, tests run, outcomes, and remaining blockers.

------------------------------------------------------------------------

# 1. EXISTING PROJECT CONTEXT AND SOURCE OF TRUTH

The uploaded archive is named `welkar_production_website.zip`. Work from
the extracted repository supplied by the owner/IDE. Do not assume the
archive's file tree is identical to a working checkout: confirm the
actual tree and Git status first.

The package includes, among other things:

-   `app/Controllers/Storefront/`
-   `app/Controllers/Admin/`
-   `app/Controllers/Auth/`
-   `app/Controllers/Webhooks/`
-   `app/Services/`
-   `app/Middleware/`
-   `app/Support/`
-   `config/routes.php`
-   `config/app.php`
-   `config/database.php`
-   `database/migrations/`
-   `database/seeders/`
-   `database/welkar_production_backup.sql`
-   `public/index.php`
-   `public/assets/css/welkar.css`
-   `public/assets/css/live-dial.css`
-   `public/assets/js/live-dial.js`
-   `public/assets/js/welkar-motion.js`
-   `public/assets/js/card-7-tilt.js`
-   `public/assets/js/logo-cloud-beam.js`
-   `resources/views/storefront/`
-   `resources/views/admin/`
-   `resources/views/auth/`
-   `resources/views/layouts/`
-   `resources/views/partials/`
-   `resources/languages/en.php`
-   `resources/languages/hi.php`
-   `resources/templates/invoice.php`
-   `tests/run_tests.php`
-   `README.md`
-   `DEPLOYMENT_INSTRUCTIONS.md`
-   `.env.example`
-   a `.env` file in the supplied archive

The archive also contains product image assets and a SQL backup. Treat
those assets and data as private project materials.

## Immediate repository hygiene

-   Do not print `.env` values in logs, reports, screenshots, or
    terminal output.
-   Check whether `.env` contains real credentials. Report only variable
    names and whether a value appears configured; redact values.
-   Ensure `.env` is not publicly downloadable and is not included in
    public/source distributions.
-   If real secrets were exposed in an archive or shared location,
    recommend rotating them. Never copy them into this file.
-   Check `.gitignore`, file permissions, Apache/Nginx document root,
    `public/.htaccess`, root `.htaccess`, and whether `storage/`, SQL
    backups, logs, invoices, imports, and private uploads are
    web-accessible.
-   The web document root must be `public/`; private application code,
    configuration, backups, and storage must remain outside public
    access.
-   Verify that documentation matches the actual migrations, routes,
    modules, and seeders. Do not assume README claims are true without
    checking code.

## Established business context

-   Brand: Welkar.
-   Store: multi-brand watches, including established brands and
    private-label products.
-   Region: India only.
-   Currency: INR.
-   Business email supplied by owner: `welkarstore@gmail.com`.
-   Selling range target: ₹1,000--₹10,000 (not a hard validation limit
    unless owner confirms).
-   Inventory: stock is held by the business; this is not a marketplace
    or dropshipping platform.
-   Main categories: Men's, Women's, Unisex, Luxury, Sports watches.
-   Default language: English; Hindi also supported; missing Hindi
    content should fall back to English.
-   Design: premium, minimal, white-led, preserve existing identity.
-   Stack: Core PHP, Bootstrap, MySQL, vanilla JavaScript.
-   Deployment target: shared hosting, with staging before production.
-   GST registered; invoice/tax handling required. GST rules and product
    rates must be confirmed against applicable classifications; never
    assume every watch or charge always uses one tax treatment.
-   Customer authentication: phone OTP and supported email/password flow
    as implemented; secure both.
-   Admin MFA: email-based challenge intended; actual delivery and safe
    handling must be verified.
-   Notifications intended: email, SMS, WhatsApp; Gmail SMTP intended
    for email; provider availability must be explicitly configured.
-   Payment methods intended: UPI, credit/debit cards, net banking, COD;
    Razorpay preferred; test mode first.
-   COD allowed across configured service area, subject to owner
    settings.
-   Stock reservation is intended during checkout; define expiry/release
    behavior and prevent overselling.
-   Cancellation intended before dispatch; return/refund and warranty
    rules must be owner-approved and configurable.
-   Shipping: admin-configurable free-shipping threshold; ₹60 fee below
    threshold unless settings say otherwise; delivery eligibility
    checked at checkout.
-   Courier integration is deferred until selected provider and
    credentials are supplied.
-   POS means on-site/business analytics only; there is no physical
    retail store assumed.

## Existing website references

Use `https://www.welkar.in/` as a business-content reference only when
network access is available and owner permits it. Verify the actual live
site rather than assuming it matches the ZIP. Do not overwrite local
data or assets based only on live-site content.

Reference-inspired experiences: - "Pick Your Perfect Dial" interaction
concept: `https://sylvi.in/` - Live animated watch product presentation
concept:
`https://sylvi.in/collections/all/products/aura-white-silver-steel`

Use the interaction concept, not another brand's protected artwork,
logo, text, or product assets. Use genuine Welkar assets or assets the
business is authorized to use.

------------------------------------------------------------------------

# 2. PHASE 1 --- FULL READ-ONLY AUDIT

**Do not modify files in this phase.** Do not run migrations or seeders
against any non-disposable database. Do not upload, delete, or change
products. Do not make live gateway calls.

## 2.1 Repository inventory

1.  Record the project root and exact file tree.
2.  Check `git status`, current branch, uncommitted changes, and recent
    history if Git is present. Never reset or discard existing work.
3.  Identify PHP version, loaded extensions, MySQL/MariaDB version, web
    server, document root, file permissions, environment configuration,
    and availability of a browser automation tool.
4.  Check dependencies and confirm the app does not require a Node.js
    runtime in production.
5.  List controllers, services, middleware, route definitions, views,
    JS, CSS, SQL migrations, seeders, tests, and scripts.
6.  Compare README claims with actual implementation.
7.  Inspect every route in `config/routes.php` and map it to controller
    method, service methods, templates, SQL tables, middleware, and user
    role.
8.  Inventory all database tables, columns, indexes, foreign keys,
    unique constraints, enum/status fields, triggers, and migration
    order. Compare the migration-built schema with
    `database/welkar_production_backup.sql` without importing the backup
    into a live database.
9.  Inventory static assets, image dimensions and sizes, duplicate
    images, missing references, unused JS/CSS, and all external
    requests.
10. Identify features implemented only in the UI, only in the backend,
    or only as schema/supporting tables.

## 2.2 Evidence format

For each finding create an entry:

-   ID, e.g. `SEC-001`, `PAY-001`, `UI-001`, `DB-001`.
-   Category and severity: Critical / High / Medium / Low /
    Informational.
-   Status: Verified / Likely / Not tested / Blocked.
-   Exact file path, class/method, route, and relevant code/query
    location.
-   Observed behavior or code evidence.
-   Expected behavior.
-   Impact on customers, data, revenue, security, accessibility, SEO, or
    operations.
-   Reproduction steps or test required.
-   Root cause, not just symptoms.
-   Minimal repair recommendation.
-   Regression risks and dependencies.
-   Acceptance criteria and test ID.
-   Whether the fix can be made safely without owner input.

Do not state a vulnerability is exploitable merely because a pattern
exists. Validate reachable routes, middleware order, request handling,
configuration, and surrounding code where feasible. Do not downgrade a
high-impact risk just because runtime testing is unavailable.

## 2.3 Initial findings to revalidate, not blindly assume

Earlier source inspection indicated the following candidate issues.
Re-open the current files and verify each before changing it:

1.  `app/Controllers/Storefront/CheckoutController.php` calls
    `RazorpayAdapter::recordSuccess()` with a synthetic payment ID
    immediately after order creation. If still present, treat it as a
    critical payment-integrity defect and remove this behavior.
2.  `app/Services/Payments/RazorpayAdapter.php` appears to generate a
    mock/local-style Razorpay order ID rather than create a real order
    through the official API. Verify adapter behavior, credentials,
    signature verification, idempotency and webhook processing.
3.  `app/Services/Auth/CustomerAuthService.php` returns `debug_otp` and
    appears to allow an existing account without a password hash to set
    one without sufficient proof of account ownership. Verify controller
    response serialization and all account-linking branches.
4.  Admin MFA service/controller/view may expose debug MFA codes or
    retain them in session. Remove production exposure and implement
    actual delivery or a safe test-mode-only mechanism.
5.  `app/Services/Analytics/AnalyticsService.php` may contain a
    duplicate alias in the low-stock query and malformed order-date
    condition in top-selling-product reporting. Confirm with MySQL
    integration tests.
6.  `config/routes.php` may omit explicit CSRF middleware from review
    submission, helpful-vote and wishlist-toggle POST routes. Check
    global router behavior and middleware application before concluding.
    Protect every state-changing browser request.
7.  `/order/success/{order_number}` may expose order details using a
    guessable order number without verifying ownership or a secure
    confirmation token. Verify exact behavior and protect sensitive
    data.
8.  README default admin credentials may be documented or seeded.
    Require a one-time setup/change flow and ensure no known default
    credential can be used in production.
9.  Test execution may be blocked when `pdo_mysql` is unavailable. Check
    environment first; install/enable the extension in the test
    environment or use a compatible disposable environment. Do not
    weaken application code just to hide an environment problem.
10. README may claim admin modules that have no matching
    routes/controllers. Map every claim to implementation and mark it
    honestly.
11. Migration/backup table counts and migration instructions may be
    inconsistent. Verify exact schema diffs and correct documentation.
12. Root `.env` is present in the archive. Check for secrets without
    revealing them; recommend rotation if actual secrets were included
    in shared artifacts.

These are audit leads, not permission to make assumptions. Record actual
verification results.

------------------------------------------------------------------------

# 3. ROUTE-BY-ROUTE STOREFRONT AUDIT

Use the current route map as the starting point. Inspect all other
routes discovered in code too.

## 3.1 Global layout, navigation and shared components

Audit: - Storefront layout and page shell. - Welkar logo variant and
correct contrast/background usage. - Desktop header, mobile navigation
drawer, dropdowns/mega-menu. - Men's, Women's, Unisex, Luxury, Sports,
Brands, New Arrivals, Best Sellers and Offers links where supported by
real catalog data. - Search input/modal and autocomplete. - Account,
wishlist and cart controls; cart count must reflect actual cart state. -
English/Hindi switch, persistence and fallback. - Footer, contact links,
social links, newsletter, policy links and payment/shipping claims. -
Toasts, alerts, modals, skeletons, loading indicators, empty states, 404
and server-error pages. - All buttons, anchors, forms, icons and
interactive surfaces. - Focus indicators, keyboard navigation, touch
targets, semantic HTML and screen-reader names. - No dead `href="#"`,
inert buttons, duplicate IDs, console errors or JS exceptions. -
Consistent spacing, type scale, buttons, inputs, cards, borders, shadows
and responsive breakpoints. - Sticky header behavior and layout shift. -
Reduced-motion preference and graceful JS-disabled behavior.

Routes to inspect include `/`, `/catalog`, `/search`,
`/collections/{slug}`, `/product/{slug}`, `/about`, `/about-us`,
`/page/{slug}`, `/lang/{locale}`, `/api/search/autocomplete`,
`/wishlist`, `/cart`, `/checkout`, `/track-order`, customer account
routes, order-success route, and any hidden/legacy routes.

## 3.2 Homepage `/`

Audit all sections in `resources/views/storefront/home.php` and the
global layout: - Hero/banner and desktop/mobile imagery. - Shop by
category. - Pick Your Perfect Dial. - Featured products, best sellers,
new arrivals, brands, collections, offers, trending/most viewed,
benefits/trust, reviews/testimonials, About Welkar, newsletter, recently
viewed and footer. - Each section's data source, ordering, publish
status, admin editability, empty state, and responsive behavior. -
Product links, category links, banners, CTA destinations, image loading,
layout shift and lazy loading. - Ensure "best sellers", "trending",
review counts and sales claims use actual data and are not fabricated. -
Test homepage with no products, one product, many products, missing
image, unpublished item and disabled section. - Do not add a section or
redesign the page simply because another store has it; implement the
requirements in the current design system.

## 3.3 Catalog, search and collections

For `/catalog`, `/search`, `/collections/{slug}` and autocomplete: -
Search by watch name, brand, model, category, SKU where appropriate. -
Filters: brand, price, gender, category, movement, dial color, case
color, strap material/color, water resistance, discount, stock
availability and dial type. - Sort: relevance, newest, price
ascending/descending, actual popularity/best sellers, and rating only
when based on genuine reviews. - Filter chips, clear-all, result count,
pagination/load-more, mobile filter drawer and state persistence in
query parameters. - Input normalization, SQL parameter binding,
escaping, Unicode, case-insensitive behavior and reasonable result
limits. - Invalid slug/filters, zero results, no active variants,
archived products and inactive categories. - Correct price, discount,
compare-at price, variant stock and availability in every card. - Avoid
N+1 queries, unbounded queries, duplicate rows from joins and
inconsistent filter counts. - Autocomplete keyboard navigation,
debounce, loading, no-result and network-failure states. - Confirm
search endpoints do not expose private product data or permit SQL
injection.

## 3.4 Product detail `/product/{slug}`

Audit `resources/views/storefront/product.php`, ProductController,
CatalogService and live-dial JS/CSS: - Title, brand, model, SKU, selling
price, compare-at price, valid discount and stock status. - Gallery,
primary image, thumbnails, zoom/lightbox, swipe on mobile, optional
video, alt text and missing-image fallback. - Variants: selection
updates image, SKU, price, stock, availability and selected option
consistently. - Quantity selector obeys minimum, maximum and stock. -
Add-to-cart, Buy Now, wishlist and comparison behavior. - Delivery
estimate shown at the correct point in the journey; do not require a
pincode while merely browsing if the intended flow only checks at
checkout. - Description, features, specs, materials, movement, water
resistance, warranty, country of origin and package contents only when
supplied. - Shipping/return/exchange/warranty content from
owner-approved editable policy records. - Reviews, moderation,
verified-purchase status and helpful voting. - Related products,
recently viewed, breadcrumbs, canonical URL, Open Graph and Product
structured data. - Variant unavailable, quantity invalid, stale price,
stock changed, session expired, network failure and duplicate click
behavior. - No claims such as "waterproof", "genuine", "lifetime
warranty", "limited stock" or verified discounts unless the source data
supports them.

## 3.5 Pick Your Perfect Dial

This feature must remain integrated with the existing Welkar design: -
Dial-style cards should be based on the supported catalog values. -
Candidate styles include Roman, Stick, Analog, Analog-Digital, Digital,
Chronograph, Automatic, Classic, Minimalist, No-Index-Marker and Moon
Phase only where applicable. - Card click opens catalog results with a
real dial-type filter. - Preserve query state and show correct result
counts. - Hide empty dial types by default. - If configurable, admin can
manage visibility, labels, images, destination/filter and order. - Use
optimized, authorized assets; do not copy Sylvi product imagery or
branding. - Test desktop grid, mobile horizontal scroll/grid, keyboard
navigation, hover/focus, touch behavior, image loading and empty
types. - Confirm card styles match actual database filter semantics; do
not merely link to a generic catalog.

## 3.6 Live animated watch image

Review `public/assets/js/live-dial.js`,
`public/assets/css/live-dial.css`, product view and product-image
records: - Animation is a visual time simulation, not a physical
mechanism. - Only enable for products with compatible genuine assets and
configured hand layers. - Use transparent hour/minute/second hand assets
or documented layer positions; do not destructively remove hands from a
normal photo. - Support pivot coordinates, scaling, rotation offset and
product-level enable/disable. - Use local device time unless an explicit
product requirement says otherwise. - Smooth seconds hand only when
performance permits. - Ensure the hands align at all breakpoints, zoom
levels, image aspect ratios and gallery transitions. - Preserve normal
gallery navigation. - Provide static image fallback when assets, config,
or JS are unavailable. - Respect `prefers-reduced-motion`; avoid high
CPU use, runaway animation loops and layout shifts. - Prevent animation
overlay from blocking image controls or pointer/touch interactions. -
Admin should eventually preview alignment before publishing. - Test
analog, digital, skeleton and unsupported products separately.

## 3.7 Cart `/cart`

Audit CartController and CartService: - Add, update, remove, clear/empty
state, coupon apply/remove, quantity and variant identity. - Cart can
distinguish the same product in different variants. - Prices are
revalidated against current server-side pricing; never trust
browser-provided totals. - Stock is rechecked when relevant and at
checkout. - Show product image, title, selected variant, SKU where
appropriate, unit price, quantity, line total, discount, shipping, tax
estimate and total. - Shipping default is ₹60 below the configured
free-shipping threshold; use the current admin setting, not a hardcoded
assumption. - Coupon validation: start/end time, active state, eligible
products/brands/categories, minimum spend, usage limit, per-customer
limit, stacking rule, discount cap, rounding and refund treatment. -
Correctly recalculate when products, quantity, coupon, shipping address,
stock, or price changes. - Protect all cart mutation endpoints with CSRF
and input validation. - Handle stale cart, invalid variant,
deleted/archived product, out-of-stock item and session expiry. - Make
totals consistent with checkout and final invoice.

## 3.8 Checkout and order lifecycle

Audit CheckoutController, OrderService, PricingService,
InventoryService, CartService, invoice services and payment adapter: -
Address validation, Indian phone number validation, required delivery
fields, state code normalization and delivery eligibility. - Shipping
fee and free-shipping threshold from settings. - Correct GST calculation
based on configured product tax classification and place-of-supply
rules; do not blindly assume one universal rate. - Use decimal-safe
monetary calculations and a documented rounding policy. - Immutable
order-item snapshots: name, SKU, variant, unit price, discount, taxable
amount, tax rate/tax components, shipping, and total at order time. -
Idempotent order creation; double click/retry must not create duplicate
orders or deduct stock twice. - Transactional stock
reservation/deduction with row locks or equivalent concurrency safety. -
Define reservation expiry and release after payment failure/timeout; do
not reserve indefinitely. - Valid order-state machine; block illegal
transitions (e.g. cancelled to shipped without authorized correction). -
COD orders are not marked as paid before collection. - Cancellation only
when allowed by current policy and state; refund/reversal and inventory
restock are consistent. - Order confirmation must not expose customer
data based solely on a guessable order number. - Tracking endpoint
requires adequate order-number plus verified phone/token ownership
validation and should avoid revealing whether arbitrary orders exist. -
Email/SMS/WhatsApp notifications are only "sent" if the provider
confirms; use queued/retryable behavior where available. - Invoice
number allocation must be concurrency-safe and legally/configurably
correct. - Test gateway decline, timeout, duplicate callback, late
webhook, partial failure and database failure after gateway success.

## 3.9 Customer account and authentication

Audit: - `/account/login`, `/account/email-auth`, `/account/send-otp`,
`/account/verify-otp`, `/account`, cancellation, invoice and logout
routes. - OTP entropy, expiry, attempt count, one-time use, hash-at-rest
where appropriate, rate limiting by phone/IP/device, resend cooldown,
abuse monitoring and provider response handling. - Never return OTP in
production API/HTML/session/logs. Test-mode OTP may be exposed only
behind an explicit local/test-only configuration that is impossible to
enable accidentally in production. - Confirm phone ownership before
account creation/linking. - Confirm email ownership before marking email
verified. - Password hash algorithm and rehash policy; never store
plaintext passwords. - Existing account without a password: do not
permit a new password to be set unless the user proves ownership through
verified OTP, email reset token or equivalent challenge. - Login error
messaging should limit account enumeration. - Session ID regeneration on
login, logout invalidation, secure/HttpOnly/SameSite cookies, HTTPS
enforcement in production and idle/absolute expiry. - Rate limits,
lockout strategy, CSRF, password reset, session fixation and account
linking. - All account pages and invoices must enforce ownership on the
server. - Verify logout is protected against CSRF or uses a safe POST
logout flow. - Ensure personal data is not exposed in URLs, analytics,
logs or third-party tracking.

## 3.10 Reviews, helpful votes and wishlist

-   Review submission, rating range, length limits, moderation status,
    spam throttling, duplicate submission and verified-purchase checks.
-   Helpful vote must prevent repeated votes and abuse; use server-side
    identity or safe anonymous vote strategy.
-   Ensure all state-changing endpoints have CSRF protection.
-   Wishlist guest/session and authenticated behavior, variant identity,
    duplicate handling, removal, move-to-cart, stock checks and login
    merge.
-   Test deleted/unavailable products and concurrent changes.
-   Ensure review labels and testimonials are genuine and not
    fabricated.

## 3.11 CMS, about, policies and localization

-   About/company profile data source and owner editing workflow.
-   Generic CMS pages, slug resolution, draft/published state, SEO
    title/description, canonical URL and safe HTML rendering.
-   Sanitize editor content to prevent stored XSS; do not permit
    arbitrary script injection.
-   Privacy, terms, shipping, cancellation, return/refund, warranty,
    contact and FAQ pages must contain owner-approved, legally
    appropriate content.
-   Do not invent GSTIN, legal entity name, registered address, warranty
    promise, return window or business hours.
-   English default and Hindi fallback; missing keys/content should fall
    back safely.
-   Language switch should allow only supported locale values and must
    not create an open redirect.
-   Confirm translated forms, error messages, dates, currency, labels,
    navigation, invoice and account flows.
-   Newsletter requires explicit consent, safe unsubscribe and duplicate
    prevention if enabled.

------------------------------------------------------------------------

# 4. ADMIN PORTAL --- PAGE-BY-PAGE AND BUSINESS-FEATURE AUDIT

The admin portal must manage the same authoritative records displayed by
the storefront. A separate hardcoded/demo data source is not acceptable.

## 4.1 Authentication, roles and audit logs

-   Admin login, MFA challenge, logout, session expiry, failed-login
    limits and credential change/reset.
-   Remove default credentials from production and require secure
    initial setup.
-   Email MFA must use an actual configured delivery adapter or show a
    clear unconfigured/test-mode state. Do not leak codes through JSON,
    HTML, flash messages, debug session keys or logs.
-   Owner role and future staff roles must follow least privilege.
-   Permission checks must run on every protected controller/service
    action, not only hide menu items.
-   Add or verify admin user management, role/permission UI,
    status/disable controls, password reset and MFA reset process.
-   Record audit log for sign-in attempts, product/price/stock changes,
    order-state changes, refund decisions, coupon/settings changes,
    imports, permission changes and credential/config changes.
-   Audit records should include actor, action, entity, timestamp,
    request/correlation ID and redacted before/after data; never store
    secrets or excessive customer PII.
-   Protect admin routes from CSRF, IDOR, brute force and privilege
    escalation.

## 4.2 Dashboard and business analytics

Audit dashboard tiles and SQL against source tables: - Orders, gross
sales, net revenue, discounts, shipping, taxes, refunds, payment status,
COD due, cost/profit where cost data is reliable. - Date-range filters
and timezone semantics. - Daily/weekly/monthly charts; comparison
period; empty data state. - Low-stock and out-of-stock products. -
Top-selling brands/products, slow movers and product conversion. -
Customer cohorts/repeat purchases and new customers. - Abandoned carts
with time threshold and recoverable status. - Marketing/campaign
attribution; separate first-party attributed orders from external
platform-reported results. - Visitors, product views, add-to-cart,
checkout starts, checkout completion and conversion rates only when
tracking exists. - Every metric must have documented formula and data
source. - Fix SQL errors and test with MySQL; no fake numbers or
hardcoded chart arrays. - Avoid SQL injection through date filters, slow
aggregate queries, timezone inconsistencies and double counting from
joins. - Distinguish gross merchandise value, revenue, profit, tax
collected and payment receipts; do not call them all "sales".

## 4.3 Products, variants and inventory

Required admin workflows: - Create/edit/archive products; avoid
irreversible delete where orders refer to products. - Brand/model, slug,
SKU/barcode, cost price, selling price, compare-at price, discounts,
active/published state, categories, gender, dial type/color, case
color/material, strap material/color, movement, water resistance,
warranty, country of origin, description, key features and package
information. - Multi-image gallery, primary image, order, alt text, safe
upload, delete/update and file cleanup. - Variants with unique
SKU/barcode, price overrides, attributes, stock, reserved stock and
active state. - SKU generation with uniqueness checks and manual
override policy. - Stock adjustment reason, actor, timestamp,
before/after values and stock ledger. - Low-stock threshold and
notifications. - No negative stock; prevent overselling under concurrent
checkouts. - Product archive behavior for existing orders, reviews,
wishlists and SEO. - Product preview, draft/publish and safe slug
changes/redirects. - Admin preview and calibration for live watch
animation. - Keep product prices and inventory synchronized with
catalog, cart, checkout, search, analytics and import/export.

## 4.4 Brand and category management

The original requirement includes full brand management. Verify whether
the app actually has: - Brand CRUD, logo, description, slug, SEO fields,
active/published status and sort order. - Category CRUD, parent-child
hierarchy if required, slug uniqueness, image, SEO, active state and
safe deletion/merge behavior. - Product association and validation
against referenced brands/categories. - Storefront brand listing, brand
page, filters, product counts and navigation. - If a module is missing,
add it using the existing controller/service/view style, CSRF,
validation, permissions, migrations only where needed, and tests.

## 4.5 Orders, fulfillment, cancellations, returns and refunds

-   Search by order number, date, customer, phone/email where permitted,
    payment status, fulfillment status and shipping reference.
-   Order detail shows immutable line snapshots, address, tax, payment
    events, stock reservations, invoices, notes and audit history.
-   Valid state machine with transition rules and reasons.
-   Cancellation before dispatch according to configured policy.
-   Return request workflow: request, reason, evidence if required,
    eligibility/window, approval/rejection, item condition, return
    shipment, inspection, restocking decision and status history.
-   Refund workflow: amount validation, partial/full refund, payment
    gateway reference, idempotency, retries, reconciliation and customer
    notification.
-   Never label a refund complete until confirmed by gateway or
    explicitly reconciled.
-   Support manual COD refund records with approval/audit trail.
-   Credit notes/tax adjustment handling where applicable.
-   Returns/refunds must update inventory and analytics correctly.
-   Do not invent a return window or warranty promise; make
    owner-approved policy configurable.

## 4.6 Customers and CRM

-   Customer profiles, orders, addresses, verified contact channels,
    marketing consent and support notes where appropriate.
-   Search, filters, pagination, export permissions and privacy
    controls.
-   Do not expose passwords, OTPs, tokens or unnecessary personal data.
-   Account status, customer support history and consent/unsubscribe
    status.
-   Data retention, deletion/anonymization policy where legally
    appropriate.
-   Newsletter list and campaign consent must be distinct from
    transactional messages.

## 4.7 Offers, coupons and campaigns

-   Coupon CRUD, validity dates/timezone, active status, usage limits,
    per-customer limit, eligible products/brands/categories, minimum
    cart value, fixed/percentage discount, cap, stacking rules and
    refund behavior.
-   Campaign name, landing URL, UTM values, schedule, banner, audience,
    budget and platform reference if used.
-   No fake scarcity, false compare-at pricing, fabricated sale counts
    or invalid discount claims.
-   Validate coupon rules in shared PricingService and test tampering.
-   Display clear campaign state and whether an external provider is
    connected.

## 4.8 Homepage, banners and CMS management

The original scope expects homepage management. Verify whether there is
a complete manager; do not assume the CMS page editor covers banners or
homepage sections. - Banner CRUD with desktop/mobile asset, heading,
description, CTA, URL, sort order, schedule and publish state. -
Homepage section enable/disable, ordering, title, source/collection,
product count and empty behavior. - Pick Your Perfect Dial card
configuration. - Testimonials/reviews selection should respect
moderation and authenticity. - Preview before publish and validation for
broken links/assets. - Avoid arbitrary code/script fields.

## 4.9 Product import/export

-   CSV/Excel-compatible import/export template and data dictionary.
-   UTF-8/BOM handling, comma/quote/newline correctness, field mapping
    and required columns.
-   Dry-run/preview before commit; row-level errors with line number,
    SKU, field and reason.
-   Transaction/batch strategy; safe rollback or explicitly documented
    partial success.
-   Duplicate SKU/model/slug handling; brand/category resolution;
    variants and images; price/stock validation.
-   Do not allow uploaded paths, executable files, remote URL fetching
    or unrestricted filesystem writes.
-   Formula-injection mitigation for CSV export fields beginning with
    `=`, `+`, `-`, `@`, tab or carriage return as appropriate.
-   File size/row limits, MIME and extension validation, private
    storage, cleanup and access checks.
-   Import logs and idempotency; prevent accidental inventory resets
    when updating catalog-only fields.
-   Test invalid files, huge files, duplicate records, partial errors,
    malicious spreadsheet values and rollback.

## 4.10 Settings, company profile and operational controls

-   Store identity, contact information, GST settings, invoice
    prefix/sequence, state/place-of-supply settings, currency, timezone,
    shipping threshold/fee, delivery eligibility, COD, cancellation and
    approved policies.
-   Email SMTP status, SMS/OTP provider, WhatsApp provider, Razorpay
    test/live mode, webhook status, GA4/Meta settings and courier
    status.
-   Secrets should be masked, not echoed back; store safely and never
    expose in client-side JS or public API.
-   Setting keys must be allowlisted and values validated by type/range.
-   Provide "Test connection" actions where safe and supported; show
    actual result, not a static green status.
-   Company profile, founders and milestones must use accurate
    owner-supplied content and safe uploads.
-   Audit all configuration changes and require owner-level permissions
    for secrets/payment changes.

## 4.11 Cart abandonment, POS-style analytics and marketing hub

No physical retail POS is assumed. This module means business analytics
and campaign insight: - Detect abandoned carts using a documented
threshold and privacy-respecting session/customer association. -
Separate anonymous carts from known customer carts; do not send
marketing messages without appropriate consent/legal basis. -
Recoverable cart links must be opaque, expiring and safe; never expose
customer details in URL. - Product performance: views, add-to-cart,
checkout starts, orders, revenue, refund/cancellation rates and stockout
impact. - Ad spend versus orders/revenue must only be shown when
ad-spend data is imported or provider-connected. - Show tracking status,
last successful sync, attribution model, missing credentials and sync
errors. - Meta/GA4/Google Ads integrations must be explicit,
permissioned, server/client event deduplicated where appropriate,
privacy-aware and tested in sandbox/test events. - Clearly distinguish
"platform reported" from "store attributed" results. - No claim that
Meta is connected merely because a Pixel ID field exists. - Campaign
performance should identify high spend/low orders and low spend/high
orders only with comparable, source-backed data.

------------------------------------------------------------------------

# 5. BACKEND, ROUTING, ARCHITECTURE AND DATABASE AUDIT

## 5.1 Routing and controller/service separation

Inspect: - `config/routes.php`, Router, Request, Response, View, helpers
and front controller. - HTTP method constraints, path parameter
handling, 404/405 behavior, trailing slash normalization and route
collisions. - Middleware order and whether middleware truly wraps the
handler. - CSRF coverage for all browser-originated state-changing
requests. - Auth middleware, permission middleware, ownership checks and
admin-only methods. - Input validation at request boundaries; domain
rules in services. - Templates should not perform business-critical
database mutations or trust hidden fields. - Error handling must return
safe user messages and log a correlation ID, not raw secrets or stack
traces. - Avoid duplicate business logic across admin/storefront
controllers. - Use consistent transaction boundaries and service
return/error patterns.

## 5.2 Database schema and migration safety

-   Compare migrations 001, 002 and 003 with the backup schema.
-   Check every referenced table/column, data type, nullability,
    default, index, foreign key and unique constraint.
-   Verify money columns use appropriate DECIMAL precision, not
    floating-point storage.
-   Verify UTC/local timezone strategy and date columns.
-   Ensure order-item snapshots do not depend on mutable current product
    data.
-   Check unique constraints for SKU, slug, invoice sequence, gateway
    IDs, idempotency keys and external event IDs.
-   Verify FK delete behavior does not erase historical
    order/invoice/payment records.
-   Index frequent joins and filters; avoid redundant or missing
    indexes.
-   Add migration history/version tracking if not present.
-   Migrations must be ordered, documented, tested on a disposable copy
    and have rollback/recovery notes.
-   Do not directly alter a live database without a verified backup and
    owner authorization.
-   Seeders must be idempotent or clearly documented as one-time
    operations; never rerun sample product seeders on live data blindly.
-   Never treat the backup as the migration source of truth without
    comparing both.

## 5.3 Inventory and concurrency

-   Define one authoritative inventory model; explain available,
    reserved, sold, returned and damaged quantities.
-   Use database transactions and row-level locking or equivalent safe
    atomic updates.
-   Ensure stock ledger entries and current balances reconcile.
-   Reserve stock only at the intended checkout stage and release on
    expiry/failure/cancellation according to rules.
-   Prevent negative stock and double deduction on repeated
    requests/webhooks.
-   Run concurrent tests simulating two buyers for one remaining item.
-   Audit all admin manual adjustments and imports.
-   Ensure variant-level inventory is consistent across product cards,
    PDP, cart, checkout and reports.

## 5.4 Pricing, GST, coupons, shipping and invoices

-   Centralize all calculations in PricingService/related domain
    services.
-   Never trust price, tax, discount, shipping or total supplied by
    browser.
-   Define rounding precision and when tax is calculated.
-   Confirm product tax classifications/rates with owner/accountant; no
    blanket legal/tax assumptions.
-   Correctly calculate intra-state/inter-state tax components from
    validated seller and delivery states where relevant.
-   Clarify whether displayed price is exclusive or inclusive of GST,
    and ensure cart, checkout, invoices and product pages agree.
-   Confirm discount basis and taxable value; prevent discounts being
    applied twice.
-   Free-shipping threshold and below-threshold ₹60 fee are
    configurable.
-   Invoice numbering must be unique and concurrency-safe; format/year
    reset rules must be confirmed by owner/accountant.
-   Invoice should show correct seller legal name/address/GSTIN only
    when supplied, customer/order data, line items, HSN/SAC where
    applicable, taxable values, taxes, discounts, shipping, payment
    details and required declarations.
-   Invoice access must enforce ownership/admin permissions.
-   Refund/credit-note handling must reconcile totals and reports.
-   Add tests for rounding edges, zero/negative values, fractional
    quantities if disallowed, coupon expiry, threshold boundary, state
    codes and tax edge cases.

## 5.5 Integrations and webhook design

For Razorpay and future messaging/analytics providers: - Keep
provider-specific logic behind adapters. - Use credentials from
environment/config, never hardcode. - Use HTTPS, signature verification,
idempotency, replay protection where supported and strict
amount/currency/order matching. - Treat browser redirect/callback
parameters as untrusted. - Webhooks must verify the raw request body
using the configured secret and official signature scheme; avoid
parsing/re-encoding JSON before signature validation. - Persist provider
event IDs and processing status; repeated delivery must not repeat stock
deductions/refunds/order transitions. - Return appropriate HTTP statuses
and log redacted failures. - Handle events arriving before/after
redirect and out of order. - Payment state transitions should be
monotonic or explicitly reconcile exceptions. - Use test keys and
provider sandbox. Do not send live charges in development. -
Email/SMS/WhatsApp adapters must distinguish queued, sent, delivered,
failed and unconfigured where provider supports it. - Secrets must never
be included in logs, HTML, client bundles or admin response JSON.

------------------------------------------------------------------------

# 6. SECURITY AUDIT AND REQUIRED CONTROLS

Perform a threat-model-based review, not just a checklist. Consider
anonymous shoppers, malicious customers, compromised admin credentials,
bot traffic, malicious uploads, leaked URLs, webhook spoofing, database
compromise and misconfigured shared hosting.

## 6.1 Application security

-   SQL injection: PDO prepared statements for all values; allowlist
    dynamic column names/sort direction.
-   XSS: context-aware output escaping in HTML attributes/text/URLs;
    sanitize any rich CMS content; protect JS/JSON contexts.
-   CSRF: all state-changing browser routes; no reliance solely on
    SameSite cookies.
-   IDOR: ownership checks for orders, invoices, addresses, returns,
    reviews, uploaded files and account resources.
-   Authentication: secure password hashes, account verification, OTP
    security, MFA, rate limits, session rotation, safe recovery.
-   Authorization: role/permission checks on every sensitive action and
    service boundary.
-   SSRF: no unrestricted remote image/import URL fetching.
-   File uploads: allowlisted extensions, MIME and magic-byte
    validation, random server names, image re-encoding where practical,
    file size limits, no PHP execution in upload folders, path traversal
    prevention and private storage for non-public files.
-   Open redirects: allowlist local return destinations.
-   Mass assignment: allowlist accepted form fields.
-   Deserialization/eval/shell calls: avoid unsafe patterns; validate
    any external data.
-   Brute force and enumeration: throttle login, OTP, tracking and
    recovery endpoints.
-   Business logic: price tampering, coupon abuse, stock race, duplicate
    order, repeated webhook, refund overrun and privilege escalation.
-   Headers: appropriate CSP, HSTS in HTTPS production,
    X-Content-Type-Options, Referrer-Policy, frame protections,
    Permissions-Policy; ensure CSP is compatible with actual assets
    before enforcing.
-   Cookies: Secure, HttpOnly, SameSite and appropriate lifetime.
-   Error handling: no debug mode or stack traces in production.
-   Logging: redacted, access controlled, rotation, no
    credentials/OTP/payment secrets/full card data.
-   Dependencies: inventory and patch known vulnerable packages if any;
    do not add dependencies without justification.

## 6.2 Infrastructure and shared hosting

-   `public/` is the only web document root.
-   Deny HTTP access to `.env`, `.git`, SQL dumps, logs, `storage/`,
    scripts, private uploads, composer/config files and backups.
-   Ensure uploads cannot execute PHP or other scripts.
-   Use least-privilege database user and filesystem permissions.
-   Force HTTPS; disable directory listing.
-   Disable production display_errors; log errors privately.
-   Ensure backup files are encrypted or stored privately and
    restore-tested.
-   Add backup retention and off-site copy strategy where feasible.
-   Validate cron/scheduled tasks and file permissions.
-   Document staging versus production environment separation.
-   Confirm secrets are not in ZIPs, repository history, browser source,
    screenshots or test reports.
-   If a real secret is found in shared materials, do not print it;
    notify owner to rotate it.

## 6.3 Privacy and tracking

-   Minimize personal data and retention.
-   Do not send phone, email, address, order details or other PII to
    analytics providers in URLs or event payloads unless explicitly
    supported and legally appropriate.
-   Record consent and unsubscribe state for marketing communications.
-   Separate transactional messages from marketing consent.
-   Redact PII from logs and error telemetry.
-   Ensure tracking behaves according to the site's privacy/consent
    policy and applicable requirements.
-   Provide accurate privacy/cookie information; do not fabricate
    compliance claims.

------------------------------------------------------------------------

# 7. FRONTEND DESIGN, RESPONSIVENESS, ANIMATIONS, ACCESSIBILITY AND PERFORMANCE

## 7.1 Preserve the visual identity

-   Start from `public/assets/css/welkar.css` and existing view markup.
-   Preserve current logo assets and established premium white-led
    visual style.
-   Prefer targeted CSS/layout fixes over wholesale replacement.
-   Do not introduce a second competing design system.
-   Reuse existing components/classes and consolidate duplicated styles
    only when safe.
-   Keep product images genuine, sharp, correctly cropped and
    consistently proportioned.
-   Ensure visual hierarchy for price, discount, variant, stock, CTA,
    shipping and trust content.
-   No invented badges, false countdowns, fake stock levels or fake
    testimonials.
-   Do not change copy/policies without business approval.

## 7.2 Responsive test matrix

Test at minimum: - Small phone: 320--360 CSS px. - Common phone:
375--430 CSS px. - Tablet portrait/landscape: roughly 768--1024 CSS
px. - Laptop: 1280 CSS px. - Desktop/wide: 1440--1920 CSS px. - Browser
zoom 200% and text resizing. - Long product names, large prices, Hindi
text, missing images and large filter counts.

Verify: - No horizontal overflow. - Header/menu/search/cart fit. -
Product grid columns and image aspect ratios. - Catalog filters work on
touch and keyboard. - Gallery swipe does not block scrolling. - Product
variant controls and quantity selector are tappable. - Cart and checkout
forms remain usable with mobile keyboards. - Admin tables are responsive
or have safe horizontal scrolling. - Modals fit viewport and support
keyboard focus. - Sticky elements do not obscure content. -
Reduced-motion mode works.

## 7.3 Motion and animation

-   Audit `live-dial.js`, `live-dial.css`, `welkar-motion.js`,
    `card-7-tilt.js`, and `logo-cloud-beam.js`.
-   Confirm event listeners are cleaned up when components are
    removed/re-rendered.
-   Avoid excessive animation loops, scroll listeners and layout
    reads/writes.
-   Pause offscreen animations where practical.
-   Disable or simplify motion for reduced-motion preference.
-   Ensure animations do not interfere with click/touch, text selection,
    screen readers or image gallery.
-   Check animation alignment, transform origins and device pixel
    ratios.
-   Provide static fallback and avoid hiding important content until
    animation scripts load.
-   Respect low-power/mobile devices and avoid expensive effects if they
    harm scrolling.

## 7.4 Accessibility

Target WCAG 2.2 AA practices where feasible: - Semantic landmarks and
heading order. - Keyboard-only navigation. - Visible focus state. -
Correct labels and errors for form fields. - Contrast checks for text,
icons, disabled states and focus rings. - Accessible dialogs, menus,
carousel controls and filter drawers. - Alt text for informative images;
decorative images use empty alt. - Status announcements for cart,
variant, search and form results. - No color-only status
communication. - Reduced motion. - Touch targets and mobile zoom. -
Accessible validation summary and error focus. - Correct language
attributes for English/Hindi content.

## 7.5 SEO and performance

-   Unique title/description and canonical URL for each indexable page.
-   Valid product structured data based on real product data; price,
    currency, availability, rating and review count must match visible
    content.
-   Sitemap must include valid published URLs only and be updated when
    relevant.
-   Robots rules must not accidentally block important public pages or
    expose private routes.
-   Open Graph images and social metadata.
-   Proper 404/410 and redirect handling for removed/renamed products.
-   Image dimensions, responsive sources where supported, modern formats
    where practical, lazy-load below fold and prioritize LCP hero image.
-   Minimize CLS, avoid blocking scripts and reduce unused CSS/JS only
    after measuring.
-   Cache static assets with versioning; never cache private
    customer/admin responses publicly.
-   Avoid duplicate database queries and large unbounded catalog
    queries.
-   Use browser performance tools or Lighthouse where available; report
    measured values and test environment.
-   Ensure font loading and third-party scripts do not block core
    checkout interactions.

------------------------------------------------------------------------

# 8. TESTING PLAN --- DO NOT SKIP

Testing must run in a safe local or staging environment with disposable
test data. Never test destructive flows against production
orders/customers.

## 8.1 Environment and baseline

1.  Record `php -v`, loaded extensions and database version.
2.  Verify required PDO MySQL extension and
    session/JSON/fileinfo/mbstring/OpenSSL support as used.
3.  Configure local `.env` from `.env.example`; never copy live
    credentials.
4.  Create a disposable test database and least-privilege test user.
5.  Apply migrations in documented order, then seed only into the
    disposable database.
6.  Capture migration logs and schema validation.
7.  Run `php -l` on every PHP file.
8.  Run `php tests/run_tests.php`; record every result and exact
    blocker.
9.  Fix the environment if the test runner is blocked, rather than
    falsely reporting tests as failed application behavior.
10. Start the app with the supported local server and inspect
    logs/browser console.

## 8.2 Unit and integration tests

Test at least: - GST/tax calculations and rounding. - Shipping threshold
boundary and admin settings. - Coupon eligibility, expiry, limits,
stacking and discount caps. - Product/variant lookup and filtering. -
SKU uniqueness and import validation. - Stock adjustment ledger
reconciliation. - Stock reservation, expiry/release, oversell prevention
and cancellation restock. - Order placement, duplicate submission and
idempotency. - Invoice sequence under concurrent allocation. - Customer
authentication, OTP expiry/reuse/throttling and account linking. - Admin
login/MFA, role/permission enforcement and session security. - CSRF on
all state-changing routes. - Order/invoice ownership checks. - Review
verified-purchase logic and helpful-vote duplication. - CSV
formula-injection sanitization and import rollback. - CMS sanitization
and sitemap output. - Analytics SQL, filters, date ranges, attribution
and aggregation correctness. - Webhook signature verification, duplicate
delivery and invalid payloads. - Settings allowlist and secret
masking. - Image upload validation and path traversal resistance.

## 8.3 End-to-end browser journeys

Use Playwright/Cypress or available browser automation if possible. If
unavailable, provide a repeatable manual checklist and mark browser
automation as blocked, not passed.

1.  Anonymous visitor opens home, uses search, filters catalog, opens
    product and selects variant.
2.  Adds product to cart, changes quantity, applies/removes valid and
    invalid coupon.
3.  Starts checkout, enters address and sees shipping/tax calculation.
4.  Completes COD order in test environment and receives a correct
    confirmation.
5.  Completes Razorpay test payment only through real test-mode flow
    with verified callback/webhook.
6.  Simulates payment failure, timeout, retry and duplicate webhook;
    order/payment/stock remain consistent.
7.  Customer logs in through OTP/email, views own order and invoice,
    cancels an eligible order.
8.  Customer A cannot see Customer B's order, address, invoice or
    account information.
9.  Admin logs in with MFA, creates product/variant, uploads image,
    adjusts stock, publishes product and verifies storefront update.
10. Admin updates order status/tracking and customer view reflects the
    correct state.
11. Admin imports CSV in dry-run, fixes errors, commits valid rows and
    verifies resulting products.
12. Admin changes homepage/CMS/coupon/settings and the public site
    reflects changes safely.
13. Admin views analytics after known test events and metrics reconcile
    to the database.
14. Test Hindi fallback, mobile layout, empty states, 404, validation
    errors and server errors.

## 8.4 Security tests

-   Attempt SQL injection in search, filter, sort, login and admin
    inputs.
-   Attempt reflected/stored XSS in product fields, reviews, CMS
    content, imports and filenames.
-   Submit missing/invalid CSRF tokens to every state-changing browser
    route.
-   Attempt IDOR with another user's order/invoice/address.
-   Attempt admin endpoints as guest/customer and low-privilege admin.
-   Attempt repeated OTP/MFA guesses, resends, expired/reused codes and
    enumeration.
-   Tamper with price, tax, coupon, shipping, variant and total in
    requests.
-   Submit oversized/malformed uploads, fake MIME, double extension,
    path traversal and executable files.
-   Replay or forge payment webhook/callbacks and duplicate event IDs.
-   Check public access to `.env`, SQL backup, logs, invoices, storage
    and source files.
-   Verify no secrets/PII appear in browser source, API output, error
    pages or logs.
-   Use safe test payloads only; do not attack third-party systems.

## 8.5 Test evidence report

For each test: - Test ID and requirement. - Environment/build/commit. -
Steps and fixture data. - Expected result. - Actual result. -
PASS/FAIL/BLOCKED/NOT RUN. - Relevant log or screenshot reference with
secrets/PII redacted. - Defect ID and retest outcome. - Do not label an
unexecuted test as passed.

------------------------------------------------------------------------

# 9. MISSING BUSINESS-MANAGEMENT FEATURES TO VERIFY AND COMPLETE

Create a gap matrix with `Required feature`, `Existing evidence`, `Gap`,
`Dependencies`, `Priority`, `Plan`, and `Acceptance test`.

Explicitly verify and, if missing, implement in the existing
architecture: - Brand CRUD and storefront brand pages. - Full category
editing and deletion/merge safeguards. - Homepage banner and section
management. - Configurable Pick Your Perfect Dial cards. - Admin users,
roles, permissions and audit-log viewer. - Return/refund request and
processing lifecycle. - Email/SMS/WhatsApp provider setup and delivery
status. - Abandoned-cart detection and recovery. - Product performance
and campaign attribution. - Meta/GA4/Google Ads integration status and
event validation. - CSV import/export with dry run, detailed errors and
safe commit. - Product archive/restore and SEO redirect handling. -
Stock reservation expiry/release and reconciliation. - Searchable
order/payment event history. - Owner-approved editable
shipping/return/warranty policies. - Low-stock alerts. - Date-wise
sales/revenue/order reports and top/low-selling products. - Ad spend
versus order performance when actual source data exists. - Store
backup/restore and operational health checks. - Newsletter
consent/unsubscribe if newsletter is enabled. - Admin change audit log
and error/failed-job monitoring.

Do not build a physical point-of-sale workflow unless the owner later
requests it. "POS" in this project refers to business/website analytics.

------------------------------------------------------------------------

# 10. IMPLEMENTATION RULES AFTER PHASE 1 APPROVAL

## 10.1 Repair strategy

-   Group defects by root cause and dependency, not only by screen.
-   Fix critical payment/security issues first.
-   Prefer the smallest change that fully resolves the issue and adds
    regression coverage.
-   Avoid broad refactors while fixing a specific defect.
-   Preserve route names and public URLs where practical; if a route
    changes, add a safe redirect and update internal links/sitemap.
-   Keep backward compatibility with current data and existing product
    assets.
-   Add migrations only when required; never edit an already-applied
    production migration in place. Create a new migration for schema
    changes.
-   Ensure migration and seed behavior is documented.
-   Use transactions for multi-table changes.
-   Add logs that are useful but do not expose sensitive information.
-   Add tests with each defect fix.
-   Run all relevant tests after each change group and full regression
    before completion.
-   Review diffs for unintended changes, generated files, secrets and
    formatting churn.
-   Do not make broad CSS rewrites or replace the design framework.

## 10.2 Priority definitions

-   **P0 Critical:** payment fraud/data exposure/account takeover/remote
    code execution or severe data corruption risk. Mitigate before live
    use.
-   **P1 High:** broken checkout/order/inventory, major SQL/runtime
    error, authorization bypass or core feature failure.
-   **P2 Medium:** important missing workflow, responsive/accessibility
    issue, reporting inaccuracies or degraded usability.
-   **P3 Low:** cosmetic polish, minor performance improvement or
    documentation cleanup without material risk.

## 10.3 Repair acceptance requirements

A defect is complete only when: 1. Root cause is identified. 2.
Code/data/configuration is corrected. 3. A regression test is added or a
justified manual test is documented. 4. Test is run and result recorded.
5. Relevant related journeys are retested. 6. Documentation/checklist is
updated. 7. No new secrets or debug bypasses are introduced. 8. Any
remaining limitations are stated.

------------------------------------------------------------------------

# 11. REQUIRED DELIVERABLES

At the end of the work, produce:

1.  `AIWelkar.md` --- this master instruction and requirements file,
    updated if audit discovers important differences.
2.  `AIWelkar-audit-report.md` --- complete page-by-page and
    feature-by-feature findings with evidence, severity, status, file
    paths and repro steps.
3.  `AIWelkar-requirements-matrix.md` --- each requested feature with
    current state and acceptance criteria.
4.  `AIWelkar-repair-plan.md` --- prioritized defect plan with
    dependencies and estimated complexity.
5.  `AIWelkar-test-report.md` --- actual tests run, environment,
    results, failures, fixes and blockers.
6.  Updated `README.md` and deployment guide aligned with actual
    implementation.
7.  Safe migrations and seeders if required.
8.  `.env.example` with all needed variables documented and no real
    secrets.
9.  Product import template, data dictionary, validation/error format
    and sample file.
10. Payment, OTP/SMS/WhatsApp, email, analytics, GST/invoice and hosting
    setup instructions.
11. Backup, restore, staging deployment and rollback instructions.
12. Known limitations and list of owner-supplied information required.
13. Final release checklist showing each item as `PASS`, `FAIL`,
    `BLOCKED`, or `NOT RUN`.
14. A concise list of changed files and the reason each was changed.

Do not create fake evidence or mark all checklist rows complete merely
to finish the task.

------------------------------------------------------------------------

# 12. OWNER-SUPPLIED INPUTS --- NEVER INVENT

Record missing values and ask the owner only when necessary: - Legal
entity name, registered address and GSTIN. - Correct product tax
classifications/rates and accountant-approved invoice rules. - Verified
return, cancellation, exchange, refund and warranty policies. - Actual
stock/product catalog and cost prices. - Production domain/hosting
configuration and backup access. - Razorpay test credentials first; live
credentials only after explicit authorization. - SMTP app password and
email sender settings. - SMS/OTP and WhatsApp provider/credentials. -
Meta Pixel/Conversions API, GA4, Google Ads IDs and permissions. -
Courier provider/API credentials when selected. - Free-shipping
threshold and delivery serviceability rules. - Admin accounts and role
definitions. - Consent/privacy/retention policy decisions. - Approved
product imagery and brand assets.

Never fabricate a GSTIN, payment credential, email password, API key,
courier account, tax rule, legal policy, rating or order record.

------------------------------------------------------------------------

# 13. FINAL ACCEPTANCE CHECKLIST

The project is not production-ready merely because pages render or PHP
syntax passes.

## Storefront

-   [ ] All public routes mapped and tested.
-   [ ] Homepage sections use real/admin-managed data where intended.
-   [ ] Catalog search/filter/sort/pagination work correctly.
-   [ ] Product variants, stock, price and images stay synchronized.
-   [ ] Pick Your Perfect Dial filters actual catalog results.
-   [ ] Live dial animation is aligned, performant, accessible and has
    static fallback.
-   [ ] Cart/coupon/shipping/tax totals match checkout.
-   [ ] Checkout, COD and test-mode gateway flow work.
-   [ ] Account, OTP, orders, tracking and invoices enforce ownership.
-   [ ] Reviews, wishlist, language switch, CMS and policies work.
-   [ ] Mobile/tablet/desktop and reduced-motion states pass.

## Backend and database

-   [ ] All routes mapped to controllers/services/tables.
-   [ ] Migrations build a clean database.
-   [ ] Schema and backup differences are documented.
-   [ ] Transactions, constraints, indexes and idempotency are
    validated.
-   [ ] Stock cannot oversell under concurrent requests.
-   [ ] Tax/pricing/coupons/invoices pass edge-case tests.
-   [ ] Errors are handled and logged safely.

## Admin

-   [ ] Authentication, MFA, roles and permissions are secure.
-   [ ] Products, variants, images, brands, categories and stock are
    manageable.
-   [ ] Orders, shipping, cancellation, returns and refunds have valid
    workflows.
-   [ ] Import/export safely validates and reports row-level errors.
-   [ ] Homepage/CMS/marketing/settings are connected to the storefront.
-   [ ] Analytics reports reconcile with database source data.
-   [ ] Admin audit logs record sensitive changes.
-   [ ] Integrations show real connected/unconfigured/error states.

## Security and operations

-   [ ] No debug OTP/MFA/payment-success bypass.
-   [ ] CSRF, XSS, SQL injection, IDOR, upload and auth tests pass.
-   [ ] No known default admin password in production.
-   [ ] Secrets are private and have not been exposed in distributable
    artifacts.
-   [ ] HTTPS, headers, cookies, permissions and public document root
    are correct.
-   [ ] `.env`, backups, logs, invoices and private storage are not
    public.
-   [ ] Payment webhooks are verified and idempotent.
-   [ ] Backups and restore have been tested.
-   [ ] Staging deployment and rollback are documented.
-   [ ] Live payment activation has explicit owner approval.

## Evidence and sign-off

-   [ ] All tests have real recorded outcomes.
-   [ ] Critical/high findings are fixed or explicitly accepted as
    blockers by the owner.
-   [ ] Requirements matrix and audit report are updated.
-   [ ] No unnecessary redesign or unapproved business-policy changes.
-   [ ] Owner-supplied dependencies are listed.
-   [ ] Final deployment recommendation is evidence-based.

------------------------------------------------------------------------

# 14. START HERE --- EXECUTION PROMPT

**Begin by auditing the actual existing Welkar repository in read-only
mode. Do not modify any files in the first pass.**

1.  Inspect the full repository and map all pages, routes, controllers,
    services, database tables, migrations, JS/CSS assets, admin modules
    and tests.
2.  Revalidate all initial findings listed in this file against the
    current source.
3.  Produce the complete route/page-by-page and feature-by-feature
    audit, including frontend design/responsiveness/animations,
    backend/database connectivity, admin gaps, security, payments and
    production readiness.
4.  For each finding, provide evidence, exact file path, severity,
    test/reproduction, impact and minimal fix.
5.  Build a requirements matrix showing what exists, what works based on
    actual tests, what is incomplete, what is missing and what is
    blocked by environment or owner-supplied credentials.
6.  Write the audit report and prioritized repair plan before editing.
7.  Once the read-only audit is complete, continue with the smallest
    safe changes in the priority order, preserving the existing design
    and architecture.
8.  After every repair group, run relevant tests, fix regressions, and
    record the actual result.
9.  Do not enable live payment processing, invent business information,
    erase existing data, redesign unnecessarily, or claim untested
    functionality works.
10. Finish with the deliverables and acceptance checklist defined above.

**Success means a reliable, secure, maintainable Welkar store whose
existing foundation is preserved, whose frontend and admin share
consistent real data, whose payment/order/inventory behavior is correct,
and whose readiness is demonstrated by tests---not by assumptions.**
