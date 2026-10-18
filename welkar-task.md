# WELKAR --- MASTER WEBSITE IMPLEMENTATION TASK

## `welkar-task.md`

**Purpose:** This document is the single source of truth for building
the complete Welkar e-commerce website, backend, admin portal, product
importer, and analytics/advertising intelligence system in Antigravity
IDE.

**Brand:** Welkar\
**Market:** India only\
**Currency:** INR (₹)\
**Primary language:** English\
**Secondary language:** Hindi, with English fallback\
**Business model:** Multi-brand watch store, including established
brands and Welkar private-label watches\
**Stack:** Core PHP 8.3+, MySQL 8.0+, Bootstrap 5, HTML, CSS,
JavaScript\
**Deployment target:** Shared hosting, with a staging environment before
launch\
**Existing website reference:** https://www.welkar.in/\
**Design/feature references:** https://sylvi.in/ and
https://sylvi.in/collections/all/products/aura-white-silver-steel

------------------------------------------------------------------------

# 1. MASTER INSTRUCTIONS FOR ANTIGRAVITY

Act as a senior full-stack PHP engineer, database architect, UI/UX
designer, e-commerce specialist, analytics engineer, security engineer,
and QA engineer.

Build a complete, production-oriented Welkar e-commerce platform from
the ground up in a dedicated project folder. Do not stop after creating
mockups, static pages, a dashboard shell, or a database schema.
Implement the real connections between the storefront, backend services,
database, admin portal, product importer, order system, inventory,
analytics, and integrations.

## Mandatory working rules

1.  **Inspect the environment first.** Check the available PHP version,
    required PHP extensions, MySQL/MariaDB version, web server, document
    root, permissions, and deployment constraints. Report
    incompatibilities. Do not silently assume that the environment meets
    PHP 8.3+ and MySQL 8.0+ requirements.
2.  **Use the requested stack.** Use Core PHP without Laravel. Use PDO
    for database access. Use Bootstrap 5, HTML, CSS, and JavaScript. Do
    not require a Node.js server in production. A build tool may be used
    only if it is genuinely helpful and production assets can be
    deployed to shared hosting without a Node runtime.
3.  **Build a modular application.** Separate controllers, services,
    repositories, validation, authorization, templates, integrations,
    and database migrations. Avoid a single huge PHP file and avoid
    duplicating business logic across the storefront, admin portal, and
    analytics.
4.  **Create a functional application.** Every visible button, filter,
    form, chart, import/export action, and navigation link must work or
    be clearly disabled with an explanation. Do not use fake
    functionality or fabricated metrics.
5.  **Use a central database.** Storefront, admin portal, product
    importer, order processing, inventory, customer data, and analytics
    must be connected to the same authoritative data model.
6.  **Build the complete scope before final acceptance testing.** Work
    in logical modules internally, but finish the full system, then
    execute a comprehensive test plan, fix issues, rerun affected tests,
    and document results.
7.  **Use realistic sample data first.** Seed sample brands, categories,
    watches, variants, specifications, and orders for local/staging
    development. Clearly label sample data. Provide templates for
    importing real catalog data. Never represent seed data as real
    business results.
8.  **Do not invent business/legal credentials.** Do not fabricate the
    legal entity name, GSTIN, registered address, tax rates, payment
    keys, provider credentials, or return/warranty terms. Provide
    configuration fields and mark them as owner-supplied.
9.  **External integrations must be honest.** If credentials or
    permissions are unavailable, implement a safe adapter/configuration
    screen and test mode where possible. Mark the integration as
    unconfigured rather than claiming it is connected.
10. **Preserve the established requirements.** Do not remove requested
    features without recording the reason and getting approval.
11. **Keep a project requirements checklist.** Mark each requirement as
    Not Started, In Progress, Implemented, Tested, or Blocked. Do not
    mark an item Tested without running a test.
12. **Deliver documentation.** Include setup instructions, migrations,
    seeders, `.env.example`, sample import files, integration setup
    guides, staging/live deployment instructions, backup/restore
    instructions, test results, known limitations, and a launch
    checklist.

------------------------------------------------------------------------

# 2. BUSINESS CONTEXT AND CURRENT WEBSITE REFERENCE

Welkar is an India-focused online watch store that sells established
brands and private-label watches. The current site at
https://www.welkar.in/ is the reference for the existing business, brand
content, product catalog, imagery, and current customer-facing
information.

Review the current website before changing or migrating content. Collect
and document what is actually available, including: - Brand wording,
logo, colors, typography, and existing visual identity. - Product names,
images, prices, descriptions, specifications, and available variants. -
Contact details and business hours. - Existing navigation, footer,
policies, shipping messages, and return/exchange messaging. - Existing
product gallery, ratings/reviews, stock messaging, quantity selection,
and cart behavior. - Existing pages and SEO metadata where available.

Treat the existing site as a source of reference information, not as
proof that the new platform already has a feature. Verify details on the
live website and preserve accurate existing information. Do not silently
turn current marketing statements into legally binding policy rules
without owner confirmation.

A previous review observed Home, Catalog, Contact, product search,
product cards, prices, add-to-cart, cart summary, checkout entry,
brand/about content, contact information, product galleries,
ratings/reviews, stock status, quantity selection, warranty messaging,
shipping messaging, and return/exchange messaging. Re-check the live
site before implementation because content may change.

A non-watch item may appear in the existing catalog. Do not
automatically import irrelevant products into the final watch-only
catalog without confirming that they belong.

## Business requirements already established

-   Brand: Welkar.
-   Market: India only.
-   Currency: INR (₹).
-   Business email provided: `welkarstore@gmail.com`.
-   Business is GST-registered.
-   Target selling price range: ₹1,000--₹10,000.
-   Inventory is owned by the business.
-   Categories: Men's, Women's, Unisex, Luxury, Sports watches.
-   Premium, minimalist, white-led design.
-   English is the default language; Hindi is also supported. Missing
    Hindi content falls back to English.
-   Shared-hosting deployment is required.
-   A staging environment must be used before launch.
-   The owner initially operates the admin portal; design role-based
    access for future staff.
-   No physical store is currently operated.
-   **Do not build physical-store POS billing counters, cashier shifts,
    cash drawers, or a physical-store POS workflow as a requirement.**
    The earlier POS discussion was clarified: the intended need is
    website traffic, product-performance, sales, and advertising
    analytics---not physical-store billing.
-   Build an analytics/marketing intelligence area that can compare
    visitors, product engagement, orders, revenue, and advertising spend
    when the relevant data integrations are configured.

## Existing settings and defaults

-   Razorpay: test mode first. Do not activate live payments until the
    owner configures and verifies live credentials.
-   Customer login: phone OTP, with optional password login.
-   Admin MFA: email-based MFA.
-   Email: Gmail SMTP adapter.
-   SMS/OTP and WhatsApp: provider not yet chosen; build adapters and
    configuration fields, leave disabled until credentials/templates are
    supplied.
-   Shipping: default shipping fee ₹60 below a configurable
    free-shipping threshold. The threshold must be editable in the admin
    portal.
-   Courier: provider not selected; support manual tracking initially
    and allow later integration.
-   COD: enabled for eligible orders; the owner must be able to
    configure eligibility/restrictions.
-   Cancellation: customer cancellation allowed before dispatch, subject
    to configured rules. After dispatch, route requests to admin
    handling.
-   GST: show product prices exclusive of GST and add applicable GST at
    checkout. Tax rates and HSN classifications must be configurable and
    validated by the owner/accountant.
-   Invoices: PDF invoices with sequential numbering and tax breakdown;
    do not invent legal entity name, GSTIN, or registered address.
-   Return/warranty policy: not finalized; provide editable
    settings/pages and configurable workflows.
-   Stock reservations: reserve stock at checkout/order creation
    according to explicit expiry rules; release on
    failed/expired/cancelled eligible orders.
-   Backups: manual backup strategy with database, uploads, invoice
    files, configuration, and restore documentation.
-   Product data: sample catalog first, then import real products.
-   Product reviews: verified-purchase review capability with
    moderation; testimonials may also be managed manually.
-   Product importer: individual product management plus bulk
    CSV/Excel-compatible import/export, with preview, validation,
    duplicate detection, and row-level errors.

------------------------------------------------------------------------

# 3. TECHNICAL ARCHITECTURE

## Required stack

-   PHP 8.3+.
-   MySQL 8.0+ (or a compatible database only if explicitly verified and
    documented).
-   PDO with prepared statements.
-   Bootstrap 5.
-   HTML, CSS, vanilla JavaScript unless a small, justified library is
    needed.
-   No Laravel.
-   Shared-hosting-compatible deployment.
-   Use Composer packages only when useful and compatible with the
    hosting environment. Document all dependencies.

## Suggested project structure

``` text
welkar/
├── app/
│   ├── Controllers/
│   │   ├── Storefront/
│   │   ├── Admin/
│   │   ├── Auth/
│   │   └── Webhooks/
│   ├── Services/
│   │   ├── Catalog/
│   │   ├── Cart/
│   │   ├── Pricing/
│   │   ├── Tax/
│   │   ├── Orders/
│   │   ├── Payments/
│   │   ├── Inventory/
│   │   ├── ImportExport/
│   │   ├── Analytics/
│   │   ├── Marketing/
│   │   ├── Notifications/
│   │   └── Integrations/
│   ├── Repositories/
│   ├── Middleware/
│   ├── Policies/
│   ├── Validators/
│   ├── Support/
│   └── Views/
├── config/
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
│   ├── index.php
│   ├── assets/
│   └── robots.txt
├── resources/
│   ├── views/
│   ├── languages/
│   └── templates/
├── storage/
│   ├── logs/
│   ├── private/
│   ├── invoices/
│   ├── imports/
│   └── backups/
├── tests/
├── scripts/
├── .env.example
├── .gitignore
└── README.md
```

Adapt this structure if hosting constraints require a different
arrangement, but keep private files outside the public web root. If the
host forces the project into a particular folder, protect all
configuration, logs, backups, exports, and private uploads with server
rules and application authorization.

## Architecture rules

-   Use one public entry point/router where practical.
-   Keep configuration separate from business logic.
-   Keep secrets out of source control.
-   Use centralized authorization and validation.
-   Use database migrations and repeatable seeders.
-   Use reusable services for price calculations, coupons, tax, stock
    reservations, order creation, payments, refunds, and analytics.
-   Use transactions for all multi-step financial/inventory operations.
-   Use database indexes, foreign keys, unique constraints, and suitable
    locking.
-   Use `DECIMAL` for money; never use floating-point arithmetic for
    monetary totals.
-   Use immutable order-item snapshots for product name, SKU, price,
    discounts, tax, and other purchase-time values.
-   Use UTC timestamps in storage and display appropriate India-local
    time in the UI.
-   Implement pagination and server-side filtering for large tables.
-   Do not store card numbers or CVV.
-   Never expose secret tokens, OTPs, passwords, private logs, invoices,
    or exports without authorization.

------------------------------------------------------------------------

# 4. STOREFRONT --- COMPLETE FRONTEND REQUIREMENTS

The storefront must be premium, minimalist, responsive, fast,
accessible, and designed around watches. It must feel like a trustworthy
premium watch boutique, not a generic template.

## 4.1 Global design system

-   White-led layout, clean typography, generous whitespace, restrained
    borders/shadows, and premium product photography.
-   Reuse the existing Welkar branding and verify colors/logo from the
    live site.
-   Consistent spacing, buttons, input fields, cards, badges, modal
    styles, alerts, and empty states.
-   Responsive desktop, tablet, and mobile layouts.
-   Accessible keyboard navigation, visible focus states, alt text,
    readable contrast, and reduced-motion support.
-   Consistent loading, error, success, and empty states.
-   Do not copy another brand's logo, proprietary assets, exact copy, or
    copyrighted imagery. Reference the interaction and layout concept,
    then use Welkar branding and authorized product assets.

## 4.2 Header and navigation

Implement: - Welkar logo. - Main navigation and dropdown/mega-menu where
appropriate. - Men's, Women's, Unisex, Luxury, Sports, Brands, New
Arrivals, Best Sellers, and Offers links where supported by catalog
data. - Search icon/input with live autocomplete. - Account/login
entry. - Wishlist entry. - Cart icon with live item count. - Language
switch: English/Hindi. - Mobile navigation drawer. - Sticky header only
if it does not harm usability or page performance.

All links must lead to real routes. Menu content should be manageable
through admin settings where appropriate.

## 4.3 Homepage

Build a configurable homepage with these components:

1.  **Hero carousel/banner**
    -   Desktop and mobile assets.
    -   Heading, description, CTA, destination, ordering, schedule, and
        publish status.
    -   Editable through admin.
2.  **Shop by category**
    -   Men's, Women's, Unisex, Luxury, Sports.
3.  **Pick Your Perfect Dial**
    -   Reference: https://sylvi.in/
    -   Image-based dial-style cards such as Roman, Stick, Analog,
        Analog-Digital, Digital, Chronograph, Automatic, Classic,
        Minimalist, No-Index-Marker, and Moon Phase, only where relevant
        to the catalog.
    -   Responsive grid or horizontal scroll.
    -   Each card opens catalog results filtered by that dial style.
    -   Owner can manage heading, visibility, card images, labels,
        destination/filter, and order in admin.
    -   Hide empty dial types by default.
4.  **Featured products**.
5.  **Best sellers** based on real order data; use explicitly labeled
    sample/demo data only in development.
6.  **New arrivals**.
7.  **Shop by brand**.
8.  **Trending/most viewed** only when actual tracking data exists.
9.  **Offers and promotional campaigns**.
10. **Featured collections**.
11. **Product benefits/trust section**, with only verified statements.
12. **Customer reviews/testimonials**, with moderation and correct
    attribution.
13. **About Welkar**.
14. **Newsletter subscription**, with consent and unsubscribe handling.
15. **Recently viewed products**, where suitable and privacy-compliant.
16. **Footer** with navigation, contact, social links, policies,
    newsletter, and payment/shipping information that is actually
    configured.

Each section should be configurable and have a graceful empty state. Do
not display invented ratings, sale counts, scarcity messages, visitor
counts, or claims.

## 4.4 Product listing/catalog page

Features: - Search by watch name, brand, model, category, SKU where
appropriate. - Live autocomplete for watch name, brand, model, and
category. - Filters: brand, price, gender, category, movement, dial
color, case color, strap material, strap color, water resistance,
discount, availability, and dial type. - Sort: relevance, newest, price
low-to-high, price high-to-low, popularity/best sellers when backed by
actual data, and rating when verified reviews exist. - Filter chips,
clear-all, result count, pagination or accessible load-more. -
Responsive filter drawer on mobile. - Product cards with image, brand,
name, price, compare-at price, discount where valid, rating/review count
if real, stock status, wishlist, and quick add/view when applicable. -
Preserve filter state in URL query parameters where practical. - Avoid
slow unbounded database queries.

## 4.5 Product detail page

Features: - Product title, brand, model, SKU where appropriate, price,
compare-at price, valid discount, and availability. - Image gallery,
zoom/lightbox, thumbnails, swipe on mobile, optional video. - Variant
selector for supported options such as dial/case/strap color or other
product variations. - Variant changes update image, SKU, price, stock,
and selected option consistently. - Quantity selector with stock
limits. - Add to cart and Buy Now. - Wishlist. - Product comparison. -
Delivery information at checkout; no mandatory pincode check while
browsing. - Description, key features, specifications, dimensions,
materials, movement, water resistance, warranty, country of origin, and
package information where supplied. - Shipping, return, exchange, and
warranty information sourced from editable, owner-approved policies. -
Reviews and verified-purchase labels where genuinely verified. - Related
products, similar products, recently viewed products. - SEO metadata,
canonical URL, structured product data, Open Graph. - Accessible status
messages for cart and variant updates.

### Live animated watch image --- reference-inspired feature

Reference:
https://sylvi.in/collections/all/products/aura-white-silver-steel

For supported analog watches, the first product image may use a
real-time visual animation: - Base dial image with transparent,
separately aligned hour, minute, and second hands. - Configurable pivot
coordinates, scaling, and rotation offsets. - Hands synchronized to the
visitor's local device time. - Smooth seconds-hand movement where
practical. - Normal product-gallery navigation remains available. -
Enable/disable per product in admin. - Admin preview for alignment
before publishing. - Static product-photo fallback when compatible
assets are unavailable. - Do not attempt destructive automatic removal
of hands from a normal photograph. - Use genuine Welkar product assets
or assets the business is authorized to use. - Respect reduced-motion
settings and avoid excessive CPU usage. - Digital, skeleton, and other
watch types may use a suitable static or product-specific
presentation. - State internally that this is a visual time simulation,
not a representation of a physical watch mechanism.

## 4.6 Cart

Implement a fully functional variant-aware cart: - Add, remove, and
update quantity. - Correctly identify each selected product variant. -
Show product thumbnail, name, selected variant, SKU where appropriate,
unit price, quantity, line total, discount, and estimated tax
treatment. - Coupon input with clear success/error feedback. - Shipping
estimate and configurable free-shipping progress indicator. - Default
shipping fee ₹60 below the admin-configured free-shipping threshold. -
Cart subtotal, discount, shipping, applicable GST, and estimated
total. - Stock revalidation on every relevant update and at checkout. -
Handle price changes since the product was added. - Empty cart state
with return-to-shopping link. - Persist carts for authenticated
customers and use secure guest-cart handling. - Merge guest and
authenticated carts carefully after login. - Track cart events for
analytics without storing unnecessary personal information. - Do not
trust browser-supplied prices or totals.

## 4.7 Wishlist and product comparison

-   Wishlist for logged-in customers; optionally preserve guest
    selections with safe local/session behavior.
-   Add/remove items and move to cart.
-   Comparison list with sensible item limits.
-   Compare price, brand, movement, case/dial/strap details, water
    resistance, warranty, dimensions, and availability.
-   Handle unavailable/discontinued products gracefully.
-   Analytics events for wishlist and comparison use.

## 4.8 Authentication and customer account

-   Phone OTP login.
-   Optional password login with secure password hashing and reset
    process.
-   Email/phone verification status.
-   Rate limiting and OTP expiry/attempt limits.
-   Customer dashboard.
-   Profile editing.
-   Address book.
-   Order history and order detail.
-   Invoice download with authorization.
-   Cancellation requests before dispatch where allowed.
-   Return/refund request flow according to configured policy.
-   Wishlist and product comparison.
-   Review submission for eligible purchases.
-   Communication preferences and marketing opt-out.
-   Account/session security.
-   Customer can only access their own records; enforce ownership on the
    server.

## 4.9 Checkout

-   Contact information and delivery address.
-   Indian address fields and pincode validation format.
-   Delivery serviceability only if supported by a configured service;
    otherwise clearly state that serviceability is not verified.
-   Order summary with product/variant, quantity, discount, shipping,
    GST, and total.
-   Coupon validation on the server.
-   GST/tax calculation using configured product tax data.
-   Payment methods: Razorpay-supported prepaid methods and COD for
    eligible orders.
-   Terms/policy acknowledgement where legally appropriate.
-   Order confirmation page.
-   Prevent double submission and duplicate orders.
-   Create order/payment records idempotently.
-   Reserve stock according to configured expiry rules.
-   Never consider a payment successful merely because the browser
    returns to a success URL.

## 4.10 Payment, orders, shipping, cancellation and refunds

-   Razorpay test mode first.
-   Server-side payment creation and verification.
-   Validate webhook signatures.
-   Deduplicate webhook events and handle retries.
-   Track payment attempts and payment state separately from order
    status.
-   COD order state remains unpaid until collection is confirmed.
-   Order states should cover pending payment, confirmed, processing,
    shipped, delivered, cancelled, refund pending, partially refunded,
    and refunded, with return states handled separately as needed.
-   Manual courier/tracking first; adapter interface for future courier
    integration.
-   Customer cancellation allowed before dispatch under configured
    rules.
-   After-dispatch requests go to admin review.
-   Refund eligibility, remaining refundable balance, approval, gateway
    processing/reconciliation, and audit trail.
-   Do not automatically restock returned items until the return is
    inspected and an explicit restock decision is made.
-   PDF tax invoices and applicable credit-note records.
-   Email notifications through configured SMTP; SMS/WhatsApp remain
    optional adapters until configured.

## 4.11 Supporting pages

Build: - About Us. - Contact Us. - FAQs. - Shipping and delivery. -
Returns and exchanges. - Warranty. - Privacy policy. - Terms and
conditions. - Payment information. - Order tracking. - Search results. -
Offers. - Login/register/OTP verification. - Customer
dashboard/profile/address book. - Cart. - Checkout. - Order
confirmation. - 404 and error pages. - Newsletter
confirmation/unsubscribe pages where appropriate.

Policy content must be editable in admin. Use current website content as
a reference, but flag policies that require owner confirmation.

## 4.12 SEO and accessibility

-   Editable SEO title and meta description.
-   Canonical URLs.
-   XML sitemap.
-   robots.txt.
-   Product/category structured data using accurate values.
-   Open Graph metadata.
-   Clean slugs and redirect support for changed URLs.
-   Pagination metadata where appropriate.
-   Noindex private account, cart, checkout, and admin pages.
-   Optimized images, responsive dimensions, alt text.
-   Semantic HTML, keyboard operation, visible focus, and accessible
    form errors.
-   Monitor Core Web Vitals and avoid unnecessary scripts.

------------------------------------------------------------------------

# 5. ADMIN PORTAL --- COMPLETE REQUIREMENTS

Build a protected admin portal connected to the same database and
business services as the storefront. Use a clean, professional,
responsive layout with a sidebar, top navigation, search where useful,
date filters, status badges, tables, pagination, confirmation dialogs,
toast messages, and proper loading/empty/error states.

The admin portal initially serves the owner. Prepare for future staff
roles using granular role-based access control (RBAC).

## Module 1 --- Dashboard

Date ranges: today, yesterday, last 7 days, last 30 days, this month,
previous month, custom range.

Cards and widgets: - Visitors and sessions when analytics integration is
available. - Orders, gross sales, net sales, average order value. -
Discounts, refunds, cancellations. - GST and shipping breakdown. -
Estimated gross profit when cost data is available. - Online orders and
revenue. - Conversion rate when denominator and attribution are
defined. - New/returning customers where trackable. - Top products,
brands, categories. - Low-stock and out-of-stock alerts. - Cart
abandonment and recovery. - Advertising spend, attributed orders,
revenue, ROAS and CPA where data is available. - Recent orders and
operational alerts. - Integration health and failed events.

Do not display fake values. If tracking is not connected, show "Not
connected" or "No data yet" and explain how to configure it.

## Module 2 --- Orders

-   Search by order number, customer, phone, SKU, tracking number.
-   Filter by date, channel, payment method, payment state, fulfilment
    state, refund/return state.
-   Order detail with item snapshots, addresses, payment history, tax,
    shipment, invoice, status history, notes, returns and refunds.
-   Update allowed statuses with validation.
-   Manual tracking details.
-   Cancellation, return, and refund workflows.
-   Export filtered data only for authorized users.
-   Audit sensitive changes.
-   Separate order state from payment state.

## Module 3 --- Cart Abandonment

-   Incomplete cart/checkouts, products, quantities, value, and last
    activity.
-   Guest/account classification where available.
-   Configurable abandonment threshold.
-   Reminder schedules and channel adapters for email, SMS, and
    WhatsApp.
-   Optional recovery coupons.
-   Reminder status and recovery-attribution reporting.
-   Suppress reminders after purchase, opt-out, or ineligible state.
-   Prevent duplicate reminders and respect consent and rate limits.
-   Secure, expiring recovery links.
-   Clearly distinguish observed cart value from recovered revenue.

## Module 4 --- Reports & Business Analytics

Reports: - Daily/monthly/yearly/custom sales. - Gross sales, discounts,
refunds, shipping, GST, net sales. - Orders and average order value. -
Product/variant/brand/category sales. - Customer acquisition and repeat
purchase where data supports it. - Inventory quantities and valuation. -
Payment methods and payment failures. - Coupon/campaign results. -
Returns, cancellations, refunds. - Advertising performance. - CSV and
Excel-compatible exports. - Filters, pagination, definitions, and
permission checks.

Clearly distinguish revenue, cash collected, tax, cost of goods sold,
and profit. Profit is an estimate unless all relevant cost and expense
data is available.

## Module 5 --- Products

-   Create, view, edit, publish, archive.
-   Brand, model, SKU, barcode, categories, collections.
-   Selling price, cost price, compare-at price, discount.
-   Variants with unique SKU and stock.
-   Product images, image ordering, optional video.
-   Dial/case/strap colors and materials.
-   Movement, case size/material, water resistance, warranty, country of
    origin, weight/dimensions.
-   Description, key features, specifications.
-   GST/HSN configuration.
-   English/Hindi translations.
-   SEO metadata.
-   Stock threshold and stock movement history.
-   Bulk importer/exporter.
-   Safe image handling.
-   Product-specific live watch animation asset management and preview.

## Module 6 --- Categories and Collections

-   Category/subcategory CRUD.
-   Men's, Women's, Unisex, Luxury, Sports.
-   Dial-type categories/attributes where appropriate.
-   Brand and curated collection management.
-   Images, descriptions, slugs, translations, SEO.
-   Featured state, ordering, publish status.
-   Homepage placements.
-   Prevent unsafe deletion of categories used by products.

## Module 7 --- Product Performance

Track real events: - Product impressions/views. - Search terms and
result clicks. - Add-to-cart/remove-from-cart. - Wishlist/comparison
actions. - Checkout starts and completed purchases. - Units sold,
revenue, discounts, returns, cancellations.

Metrics: - Product views and unique visitors where measurable. -
Add-to-cart rate. - Checkout conversion and purchase conversion. -
Revenue per view. - Units and net sales. - Return/cancellation rate. -
Advertising spend and attributed results when connected.

Define denominators, attribution windows, bot filtering where feasible,
event deduplication, and data availability. Never fabricate analytics.

## Module 8 --- Customer Data / CRM

-   Customer profiles and verified contact status.
-   Order history, lifetime spend, average order value.
-   Saved addresses, wishlist, product interests.
-   Reviews, return history and support notes where implemented.
-   New, returning, inactive, and high-value segments.
-   Purchase/category/brand-based segments.
-   Consent and communication preferences.
-   Customer status and account restrictions.
-   Restricted exports with audit logs.
-   Never expose plaintext passwords, OTPs, session secrets, or payment
    credentials.
-   Respect data minimization and applicable privacy obligations.

## Module 9 --- Offer Campaigns

-   Campaign name, description, status, schedule.
-   Eligible/excluded products, brands, categories, collections.
-   Percentage/fixed discount, minimum spend, maximum discount.
-   Priority and stacking rules.
-   Landing-page association and banner association.
-   Channel applicability: online.
-   Campaign performance.
-   Prevent invalid or incompatible discount stacking.
-   Revalidate all rules server-side at cart/checkout.

## Module 10 --- Coupons

-   Percentage/fixed amount.
-   Minimum spend and maximum discount.
-   Start/end time.
-   Global and per-customer usage limits.
-   Eligible/excluded products/categories/brands.
-   First-order-only rule.
-   Active, paused, expired, disabled.
-   Redemption history and reports.
-   Transaction-safe validation and redemption.
-   Optional online-only/channel restriction; no physical POS
    requirement.

## Module 11 --- Promotional Banners

-   Homepage hero carousel and promotional cards.
-   Desktop/mobile images.
-   English/Hindi text.
-   CTA label and destination.
-   Schedule, ordering, publish/unpublish.
-   Impression/click tracking.
-   Link validation and safe upload validation.
-   Expired/unpublished campaigns must not be displayed.

## Module 12 --- Social Media & Campaign Hub

Focus on website marketing analytics and Meta Ads, not physical POS.

-   Meta Business connection status.
-   Ad account selection where authorized.
-   Campaign/ad set/ad performance where the API permits.
-   Spend, impressions, reach, clicks, reported conversions.
-   UTM tracking.
-   Meta Pixel setup.
-   Conversions API where configured and supported.
-   Landing-page and order attribution.
-   Spend versus orders/revenue.
-   ROAS, CPA, CPC, CPM and conversion rate where definitions/data
    permit.
-   Token expiry/revocation and API error handling.
-   Secure token storage.
-   Deduplicate browser/server events.
-   Distinguish Meta-reported results from store-attributed results.
-   Do not claim campaign creation/editing/publishing unless the
    connected API permissions and tested endpoints support it.
-   Clearly show unavailable metrics when no valid integration exists.

## Module 13 --- Website Analytics Intelligence (replaces physical POS)

This module is required because the business has no physical store. Do
not implement physical POS billing counters or cash-register
functionality.

### Traffic and visitor analysis

-   Total visitors/users and sessions, based on the connected analytics
    provider's definitions.
-   Page views, landing pages, exit pages, and traffic trends.
-   Traffic sources: direct, organic search, referral, social, paid
    campaigns, and other available source categories.
-   Device category, browser, and operating system where available.
-   Geographic breakdown such as country, state, or city only when the
    analytics provider supplies it reliably and privacy rules permit it.
-   New versus returning visitors where available.
-   Search terms from onsite search.
-   Product/category page engagement.
-   Date comparisons and custom ranges.

Do not promise exact residential location or identify an anonymous
visitor's personal identity. IP-derived geography can be inaccurate and
must not be presented as exact.

### Product analytics

-   Most viewed products.
-   High views but low add-to-cart.
-   High add-to-cart but low checkout completion.
-   High sales/conversion products.
-   Low views and low sales.
-   Low sales despite strong traffic.
-   Products with high returns/cancellations.
-   Products frequently searched but not purchased.
-   Variant-level performance where events and transactions identify
    variants.

### Advertising efficiency and product opportunity analysis

-   Product/campaign/ad-level spend when available.
-   Orders, revenue, and conversions attributed to ads.
-   ROAS = attributed revenue divided by ad spend, using a clearly
    stated attribution method.
-   CPA = ad spend divided by attributed conversions.
-   CPC = spend divided by clicks.
-   CPM = spend per 1,000 impressions.
-   Identify high spend with low orders/conversions.
-   Identify low spend with comparatively high orders/conversions.
-   Identify high traffic but low conversion.
-   Identify low traffic but strong conversion.
-   Compare performance by product, brand, campaign, channel, date, and
    landing page when data permits.
-   Provide configurable thresholds and explain the data behind every
    alert.
-   Avoid declaring an ad "wasted" without sufficient spend,
    attribution, and conversion data.
-   Show sample size, date range, attribution source, and data freshness
    alongside recommendations.

### Analytics integration strategy

-   Build a provider adapter for Google Analytics 4 (GA4) and support
    its reporting/API integration when credentials and permissions are
    supplied.
-   Support Google Ads reporting through its authorized API when
    credentials and account access are supplied.
-   Support Meta Ads through the relevant authorized API.
-   Use UTM conventions to connect ad clicks to website sessions and
    orders.
-   Store first-party commerce events and order facts in Welkar's
    database.
-   Deduplicate events and document attribution rules.
-   Respect consent and applicable privacy rules.
-   If an integration is not configured, show setup instructions and
    "Not connected" rather than invented data.
-   Do not assume all platforms attribute orders identically. Show each
    platform's reported results separately from the store's own
    attributed results.

## Module 14 --- Store Settings

-   Business contact information and approved branding.
-   Seller/legal entity details and GST configuration.
-   Invoice templates, prefixes, sequence rules, and PDF generation.
-   Razorpay test/live configuration and webhook health.
-   COD eligibility.
-   Shipping fee, free-shipping threshold, shipping text, manual
    tracking.
-   Gmail SMTP configuration/test.
-   SMS/OTP and WhatsApp provider adapters.
-   Language defaults and translations.
-   Homepage section and content settings.
-   Editable policy pages.
-   SEO defaults, sitemap and social links.
-   Admin MFA and security settings.
-   Audit log viewer.
-   Backup/restore documentation and operational checks.
-   Mask secrets after saving; never send them back to the browser in
    full.

------------------------------------------------------------------------

# 6. PRODUCT IMPORTER --- DETAILED REQUIREMENTS

Build a dedicated Product Importer in the admin portal. It must be a
real data-import tool, not a decorative upload button.

## Import workflow

1.  Download a CSV/Excel-compatible template.
2.  Upload a supported file.
3.  Select or confirm the column mapping.
4.  Preview rows and mapped fields.
5.  Validate required fields, numeric formats, prices, tax
    configuration, SKUs, barcodes, category/brand references, and
    variant relationships.
6.  Display row-level errors and warnings.
7.  Allow the user to download an error report.
8.  Choose whether valid rows can be imported when other rows fail, or
    require an all-or-nothing import. Make the selected behavior
    explicit.
9.  Show a final summary before committing.
10. Run the import with progress/status tracking appropriate to hosting
    limits.
11. Report created, updated, skipped, and failed rows.
12. Preserve an import job log for auditing and troubleshooting.

## Required fields and optional fields

At minimum, support: - Product name. - Brand. - Category. - Product type
or variant grouping. - SKU. - Barcode. - Selling price. - Cost price, if
available. - Compare-at price. - Stock quantity and stock location. -
Product description. - Gender. - Dial type and dial color. - Case
color/material/size. - Strap material/color. - Movement. - Water
resistance. - Warranty. - Country of origin. - GST/HSN configuration
where supplied. - Image URLs or a documented image-upload mapping. -
Publish status. - SEO fields. - English/Hindi content.

## Import safety

-   Enforce SKU uniqueness.
-   Do not silently overwrite prices or stock.
-   Provide explicit create-only, update-existing, and upsert modes.
-   For inventory imports, distinguish absolute stock replacement from
    stock adjustment; require the user to select the intended mode.
-   Validate image URLs and file types safely.
-   Protect against CSV formula injection in exports.
-   Do not trust spreadsheet formulas as calculated business values.
-   Use transactions/batches that work within shared-hosting time and
    memory limits.
-   Keep an audit trail of who imported which file, when, and with what
    result.
-   Never import executable content.
-   Include sample CSV files and a data dictionary in the project.

------------------------------------------------------------------------

# 7. DATABASE STRUCTURE

Design the final normalized schema and implement it through migrations.
The following is a required logical coverage list; adapt exact column
types and relationships to the implementation.

## Identity, authentication, and permissions

-   `users`
-   `customer_addresses`
-   `admin_users`
-   `roles`
-   `permissions`
-   `role_permissions`
-   `admin_user_roles`
-   `otp_challenges`
-   `admin_mfa_challenges`
-   `sessions` if database-backed
-   `consent_records`

## Catalog and inventory

-   `brands`
-   `categories`
-   `collections`
-   `products`
-   `product_translations`
-   `product_variants`
-   `product_images`
-   `product_specifications`
-   `product_categories`
-   `product_collections`
-   `inventory_locations` for logical inventory location support, even
    if initially one online warehouse
-   `inventory_stock`
-   `inventory_movements`
-   `inventory_reservations`
-   `stock_adjustment_reasons`
-   `import_jobs`
-   `import_job_rows` or equivalent detailed import results

## Shopping and promotions

-   `carts`
-   `cart_items`
-   `cart_events`
-   `wishlists`
-   `wishlist_items`
-   `product_comparisons`
-   `coupons`
-   `coupon_redemptions`
-   `campaigns`
-   `campaign_products`
-   `campaign_categories`
-   `banners`
-   `banner_translations`
-   `price_rules` if needed

## Orders, payments, and documents

-   `orders`
-   `order_items`
-   `order_addresses`
-   `order_status_history`
-   `payments`
-   `payment_events`
-   `refunds`
-   `returns`
-   `return_items`
-   `invoices`
-   `invoice_sequences`
-   `credit_notes` where applicable
-   `shipping_shipments`
-   `webhook_events`

## Analytics and marketing

-   `analytics_events`
-   `daily_analytics_summaries` if needed for performance
-   `customer_segments`
-   `customer_segment_memberships` if needed
-   `reviews`
-   `testimonials`
-   `notification_templates`
-   `notification_logs`
-   `abandoned_cart_reminders`
-   `analytics_integrations`
-   `ad_integrations`
-   `marketing_attributions`
-   `utm_sessions` or equivalent attribution records
-   `content_pages`
-   `store_settings`
-   `audit_logs`
-   `system_logs`

## Important relationships and integrity

-   One product may have multiple variants.
-   Every sellable variant has a unique SKU and its own inventory
    identity.
-   A product can be linked to multiple categories/collections if the
    catalog model allows it.
-   One order has multiple order items and may have multiple payment
    attempts.
-   One order can have shipment, return, refund, invoice, and
    credit-note records as appropriate.
-   Product details in an order are immutable snapshots.
-   Stock movements must identify their source and actor/system process.
-   Cart and analytics events must use stable event IDs or deduplication
    keys where needed.
-   Campaign attribution must link to defined sessions/events/orders
    without duplicating revenue.
-   Deleting a catalog record must never destroy historical
    order/invoice records.
-   Use foreign keys and indexes for commonly filtered/joined columns.
-   Use transactions and unique constraints to protect invoice
    numbering, SKU uniqueness, coupon limits, payment events, and stock
    deductions.

------------------------------------------------------------------------

# 8. PERMISSIONS AND SECURITY

Initially the owner is the primary administrator, but implement
role-based access control so future staff can be added safely.

Suggested roles: - Owner: all permissions. - Manager: operational
permissions explicitly granted by the owner. - Catalog manager:
products, categories, inventory, imports. - Marketing manager:
campaigns, banners, marketing reports, authorized integration scopes. -
Support agent: restricted orders/customer support. - Analyst: read-only
approved reports.

Suggested permissions: - `dashboard.view` - `orders.view` -
`orders.update_status` - `orders.cancel` - `refunds.request` -
`refunds.approve` - `products.create` - `products.update` -
`products.archive` - `inventory.view` - `inventory.adjust` -
`imports.run` - `customers.view` - `customers.export` -
`coupons.manage` - `campaigns.manage` - `banners.manage` -
`analytics.view` - `analytics.export` - `ads_integrations.manage` -
`settings.manage` - `users.manage_roles` - `audit_logs.view`

Security requirements: - Server-side permission checks on every
protected route/action. - Password hashing using PHP's secure password
APIs. - OTP expiry, attempt limits, and rate limiting. - Email-based
admin MFA. - Secure session IDs, cookie flags, session regeneration
after login, and logout invalidation. - CSRF protection. - Output
escaping and XSS protection. - PDO prepared statements. - Protection
against IDOR: customers can only access their own data. - Safe
file/image upload validation and private file storage. - Webhook
signature verification and idempotency. - Rate limiting on login, OTP,
search, and abuse-prone endpoints. - Redact secrets from logs. - Never
expose raw OTPs, passwords, API secrets, access tokens, or payment
credentials. - Audit important actions, including product/price/stock
changes, imports, order status changes, refunds, settings changes,
exports, and role changes. - Store integration credentials in
environment configuration or encrypted storage, with minimal access. -
Use HTTPS in staging/live. - Avoid leaking stack traces or SQL errors to
visitors. - Document security assumptions and any hosting limitations.

------------------------------------------------------------------------

# 9. ANALYTICS DATA MODEL AND DEFINITIONS

Analytics must be connected to real data. Implement first-party events
and optional provider integrations.

## First-party events

Where applicable, track: - `page_view` - `view_item_list` -
`select_item` - `view_item` - `search` - `add_to_cart` -
`remove_from_cart` - `view_cart` - `begin_checkout` -
`add_shipping_info` - `add_payment_info` - `purchase` - `refund` -
`add_to_wishlist` - `add_to_comparison` - `banner_impression` -
`banner_click`

Use event IDs, timestamps, product/variant IDs, session IDs,
campaign/UTM fields, and other minimum necessary fields. Avoid storing
unnecessary personal data in analytics events. Do not put phone numbers,
email addresses, passwords, or payment credentials into event payloads.

## Metric definitions

Document formulas and limitations in the admin interface or reporting
documentation: - **Visitors/users:** Use the selected analytics
provider's definition; do not assume different providers count users
identically. - **Sessions:** Use provider/sessionization rules. -
**Conversion rate:** Define whether it means orders divided by sessions,
visitors, or another denominator. - **Add-to-cart rate:** Define the
numerator and denominator explicitly. - **ROAS:** Attributed revenue
divided by ad spend for the chosen attribution model and date range. -
**CPA:** Ad spend divided by attributed conversions. - **CPC:** Ad spend
divided by clicks. - **CPM:** Ad spend divided by impressions,
multiplied by 1,000. - **Net sales:** Define how discounts,
cancellations, returns, shipping and taxes are treated. - **Estimated
gross profit:** Net product sales less known cost of goods sold, with
tax/discount treatment documented. Do not label it net profit unless all
relevant operating costs are included.

Show: - Date range. - Source/integration. - Last updated time. -
Attribution method. - Data completeness or warnings. - Sample size where
useful.

## Geographic analytics and privacy

-   Report country/state/city only when the configured analytics source
    supplies it.
-   Explain that city-level location is approximate and may be
    unavailable or inaccurate.
-   Never claim to know the exact address or identity of anonymous
    visitors.
-   Respect applicable consent/privacy obligations and provider terms.
-   Avoid collecting unnecessary personal or sensitive data.

------------------------------------------------------------------------

# 10. EXTERNAL INTEGRATIONS

Use adapter interfaces so external services can be replaced without
rewriting core commerce logic.

## Razorpay

-   Test mode first.
-   Server-side order/payment creation.
-   Payment verification.
-   Webhook signature verification.
-   Event idempotency and retries.
-   Refund handling where supported.
-   Clear test/live status.
-   No card data storage.
-   Live mode requires owner configuration and verification.

## Gmail SMTP

-   Email verification/account notifications where required.
-   Order confirmation, invoice, shipment, cancellation, and refund
    notifications.
-   Admin MFA.
-   Abandoned-cart messages only when eligible.
-   Test-email function.
-   Delivery/error logging with secret redaction.

## SMS/OTP

-   Provider not chosen.
-   Implement an adapter and configuration UI.
-   Keep disabled until credentials and templates are configured.
-   Enforce expiry, rate limits, attempt limits, and abuse protection.

## WhatsApp

-   Provider not chosen.
-   Use approved templates and an authorized business API/provider.
-   Respect consent and opt-out.
-   Keep disabled until credentials/templates are verified.

## Google Analytics 4

-   Optional authorized integration for reporting users/sessions,
    traffic sources, geography, landing pages, and engagement where
    available.
-   Provide setup instructions and connection status.
-   Do not fabricate results when disconnected.

## Google Ads

-   Optional authorized API integration for spend, impressions, clicks,
    conversions and campaign reporting.
-   Credentials and account access required.
-   Clearly separate Google Ads reported conversions from first-party
    store-attributed purchases.

## Meta Ads / Pixel / Conversions API

-   Optional authorized integration.
-   Token/account status, reporting, event deduplication, and error
    handling.
-   Credentials and permissions required.
-   Never claim unsupported API functionality.

## Shipping

-   Manual tracking initially.
-   Adapter for future courier integration.
-   Do not claim live delivery serviceability unless a provider supports
    and verifies it.

------------------------------------------------------------------------

# 11. GST, FINANCIAL RECORDS, AND INVOICES

-   Display prices exclusive of GST and add applicable GST at checkout
    as required by the established business setting.
-   Tax rules and HSN classification must be configurable and verified
    by the owner/accountant.
-   Handle intra-state/inter-state tax treatment based on verified
    business and delivery details.
-   Store taxable value, rate, tax components, discount, and tax basis
    as purchase-time snapshots.
-   Use `DECIMAL` for monetary amounts and explicit rounding rules.
-   Generate PDF invoices with sequential numbering and tax breakdown.
-   Use safe, concurrency-resistant invoice sequence allocation.
-   Preserve issued invoices and correction history.
-   Record refund and return amounts against original transactions.
-   Create credit notes where applicable after accounting/legal review.
-   Distinguish an ordinary receipt from a legally required tax invoice.
-   Do not invent legal entity name, GSTIN, registered address, tax
    rates, or legal claims.
-   Validate production invoice behavior with a qualified accountant
    before launch.

------------------------------------------------------------------------

# 12. BACKUP, LOGGING, AND OPERATIONS

Provide a manual backup strategy and documentation for: - MySQL
database. - Product images and uploads. - Generated invoices/credit
notes. - Private import/export files that need retention. - Required
configuration and deployment metadata, excluding unsafe secret exposure
in backups. - Restore procedure and post-restore checks.

Additional requirements: - Logs must not be publicly accessible. -
Redact secrets and unnecessary personal data. - Log application errors,
failed integrations, payment webhook outcomes, import jobs, and
security-relevant actions. - Rotate/limit logs according to hosting
capabilities. - Document shared-hosting limitations and any manual
operational steps. - Provide a backup verification/restore checklist. Do
not claim a restore was tested unless it was actually performed.

------------------------------------------------------------------------

# 13. TESTING, FIXING, AND ACCEPTANCE

Build the complete system, then run comprehensive tests. Fix issues and
rerun the relevant tests. Do not simply write tests and claim success.

## 13.1 Functional tests

### Storefront

-   All routes and navigation work.
-   Homepage sections render from configured content.
-   Pick Your Perfect Dial filters catalog results correctly.
-   Product search/autocomplete works.
-   Catalog filters and sorting return correct results.
-   Variant changes update SKU, price, stock, and images.
-   Live watch animation works for supported products and falls back
    safely.
-   Cart quantity/coupon/totals are correct.
-   Wishlist/comparison behave correctly.
-   Customer authentication and account ownership work.
-   Checkout validates addresses, stock, tax, shipping, coupons, and
    payment state.
-   Order confirmation and email notifications work in configured/test
    environments.
-   Policy pages and language fallback work.

### Admin portal

-   Each module loads and is permission-protected.
-   Product/category/brand CRUD works.
-   Stock adjustments produce ledger and audit entries.
-   Product importer preview, validation, create/update modes, and error
    reports work.
-   Orders, cancellations, returns and refunds follow valid transitions.
-   Coupons and campaigns enforce eligibility and limits.
-   Banners schedule/publish correctly.
-   Analytics displays real data or explicit disconnected/empty states.
-   Settings are persisted securely.
-   Export permissions and CSV formula-injection protections work.

### Payments and stock

-   Valid and invalid gateway events.
-   Duplicate webhook delivery.
-   Failed/cancelled/expired payment behavior.
-   Double submission.
-   Concurrent stock deduction and reservation expiry.
-   Refund amount limits and retries.
-   COD remains unpaid until collection is recorded.
-   Order, payment and inventory state remain consistent after errors.

### Analytics and advertising

-   Events have stable IDs and avoid duplicate purchase counting.
-   UTM attribution persists through checkout according to documented
    rules.
-   Spend and conversions use compatible date ranges and stated
    attribution.
-   Disconnected integrations do not show invented values.
-   High-spend/low-sales and low-spend/high-sales alerts show the
    supporting numbers and data limitations.
-   Geographic data is labeled approximate and only shown when
    available.

## 13.2 Security tests

Test at minimum: - SQL injection. - XSS. - CSRF. - IDOR/customer record
access. - Unauthorized admin routes/actions. - Authentication/OTP rate
limiting. - Session fixation and logout behavior. - File upload
validation. - Secret/log leakage. - Webhook forgery and
replay/deduplication. - CSV formula injection. - Concurrent
coupon/inventory/invoice operations. - Sensitive exports and
file-download authorization.

## 13.3 UI and responsive tests

Test supported desktop, tablet, and mobile sizes: - Header and
navigation. - Product cards and galleries. - Dial section. - Live watch
animation. - Cart and checkout. - Admin tables, forms, filters, charts
and importer. - Analytics dashboards and date filters. - Accessibility
keyboard focus and reduced motion. - Loading, empty, error, success, and
confirmation states.

## 13.4 Performance tests

-   Inspect slow queries and add suitable indexes.
-   Avoid loading the full catalog in memory.
-   Paginate large admin tables.
-   Optimize images and lazy-load non-critical media.
-   Avoid excessive analytics requests.
-   Keep animation code lightweight.
-   Check caching and cache invalidation for catalog/content where
    applicable.
-   Record any limitations imposed by shared hosting.

## 13.5 Test reporting

Create a test report containing: - Test name and purpose. -
Environment. - Actual result. - Pass/fail/blocked. - Evidence or logs
where safe. - Defect and fix. - Retest result. - Known limitation.

Never mark an unexecuted test as passed. Never claim the site is
production-ready while critical payment, security, inventory, or invoice
tests remain unresolved.

------------------------------------------------------------------------

# 14. DEPLOYMENT PLAN

1.  Inspect the environment and confirm PHP/database compatibility.
2.  Create the dedicated project folder and source structure.
3.  Create `.env.example` and local/staging configuration instructions.
4.  Implement database migrations and seeders.
5.  Build with sample catalog data.
6.  Connect storefront, admin, order services, inventory, importer and
    analytics.
7.  Configure available integrations in test mode.
8.  Run the complete functional/security/responsive test plan.
9.  Fix issues and rerun failed/affected tests.
10. Deploy to staging.
11. Ask the owner to verify catalog, policies, business/legal details,
    GST settings, contact information, and payment behavior.
12. Configure live credentials only after explicit owner action.
13. Perform a controlled live-readiness checklist.
14. Provide shared-hosting deployment and rollback instructions.
15. Document backups, restore steps, credentials setup, and ongoing
    maintenance.

Never deploy live payment credentials or enable production payments
without owner authorization.

------------------------------------------------------------------------

# 15. REQUIRED DELIVERABLES

At completion, provide:

1.  Complete source code for storefront, backend, admin portal,
    importer, and analytics.
2.  Database migrations and seeders.
3.  `.env.example` with explanations and no real secrets.
4.  Realistic sample catalog and test data, clearly labeled.
5.  Product CSV/Excel-compatible import template and data dictionary.
6.  Import error-report format and sample file.
7.  Setup and architecture README.
8.  Admin portal usage guide.
9.  Razorpay test/live setup guide.
10. Gmail SMTP, SMS/OTP, and WhatsApp setup instructions.
11. GA4, Google Ads, and Meta integration setup instructions.
12. GST/invoice configuration checklist.
13. Shared-hosting deployment guide.
14. Backup and restore guide.
15. Automated/manual test suite and actual test report.
16. Known limitations and unconfigured integrations list.
17. Final acceptance checklist with each requirement's status.
18. A clear list of owner-supplied information still required before
    launch.

------------------------------------------------------------------------

# 16. DEFINITION OF DONE

The project is not complete merely because pages look correct.

The build is complete only when: - The storefront is responsive and
connected to real backend services. - Product catalog, variants, stock,
cart, coupons, checkout, and orders work together. - The admin portal
manages the same live data used by the storefront. - Product imports
validate, preview, and report results safely. - Analytics uses real
tracked events and order data. - Advertising analytics clearly indicates
whether integrations are connected and separates platform-reported
results from store attribution. - Pick Your Perfect Dial is configurable
and filters the catalog correctly. - Supported product pages show
aligned real-time visual watch animation with a safe static fallback. -
Security controls and server-side permissions are tested. - Payments,
inventory, tax, and order state remain consistent under failures and
concurrent requests. - Required tests are executed, defects are fixed,
and retests are documented. - Staging and shared-hosting deployment
documentation is complete. - No legal, tax, payment, or analytics
credentials have been fabricated. - Any remaining blockers are listed
honestly.

**Start by inspecting the environment and the existing Welkar website.
Then implement the full system according to this document. Do not stop
at planning. Maintain a requirements checklist and report actual
progress, tests, failures, fixes, and unresolved dependencies.**
