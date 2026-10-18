# WELKAR MASTER PRODUCTION DEPLOYMENT & SEARCH CONSOLE CHECKLIST

**Target Environment**: Production (Apache / cPanel / Shared or VPS Hosting)  
**Production Domain**: https://www.welkar.in  
**Prepared For**: Welkar System Administrators & Store Owners

---

## 1. Pre-Deployment Staging Checklist

Before pushing changes to the live production server, execute these checks:

- [x] **Automated Test Suite**: Run `php tests/run_tests.php` and verify all 28 tests output `[PASS]` with 0 failures (including Razorpay SDK v2.9.3 & Facebook Business SDK CAPI).
- [x] **Database FAQs**: Run `php scripts/seed_accurate_product_faqs.php` to ensure the `product_faqs` table contains model-specific horological FAQs.
- [x] **Sitemap Generation**: Run `php scripts/generate_sitemap.php` to ensure `public/sitemap.xml` reflects the latest products, brands, and collections.
- [x] **Robots.txt Domain**: Verify `public/robots.txt` points to `https://www.welkar.in/sitemap.xml` (no localhost or staging domains).
- [x] **About Us Exclusions**: Confirm `/about` and `/about-us` views contain NO FAQ accordions.
- [x] **Environment Variables**: Verify `.env` on production has `APP_ENV=production`, `APP_DEBUG=false`, and `APP_URL=https://www.welkar.in`.
- [x] **Security Safeguards**: Ensure `.env`, `storage/`, and `database/` are inaccessible from the web root via `.htaccess`.

---

## 2. Production Deployment Steps

### Step 1: Upload Archive or Pull Code
1. Upload `welkar_production_website.zip` to the hosting account.
2. Extract the files into the deployment directory.
3. Ensure the web server document root points to the `public/` directory (or use the root `.htaccess` which rewrites traffic to `/public/index.php`).

### Step 2: Database Synchronization
Run database updates for `product_faqs` using the CLI or phpMyAdmin:
```bash
php scripts/seed_accurate_product_faqs.php
```

### Step 3: File Permissions
Ensure the appropriate storage permissions:
```bash
chmod -R 755 public/
chmod -R 775 storage/
chmod 644 .env
```

### Step 4: Verification of Public Endpoints
Inspect HTTP status codes on production using `curl` or browser DevTools:
1. `GET https://www.welkar.in/robots.txt` -> HTTP 200 (Text plain)
2. `GET https://www.welkar.in/sitemap.xml` -> HTTP 200 (XML)
3. `GET https://www.welkar.in/llms.txt` -> HTTP 200 (Text markdown)
4. `GET https://www.welkar.in/catalog` -> HTTP 200 (HTML with canonical)
5. `GET https://www.welkar.in/search?q=chrono` -> HTTP 200 (HTML with `<meta name="robots" content="noindex, follow">`)

---

## 3. Google Search Console (GSC) Setup Guide

### 3.1 Domain Property Verification
1. Log in to [Google Search Console](https://search.google.com/search-console).
2. Choose **Domain** property type and enter `welkar.in` (this covers `https://www.welkar.in`, `https://welkar.in`, and subdomains).
3. Copy the supplied DNS TXT verification record.
4. Add the TXT record to your domain DNS manager (Cloudflare, GoDaddy, Namecheap, etc.):
   - **Type**: TXT
   - **Name / Host**: `@`
   - **Value**: `google-site-verification=...`
5. Click **Verify** in Search Console.

### 3.2 Sitemap Submission
1. In Search Console, navigate to **Index** > **Sitemaps**.
2. Enter the sitemap path: `sitemap.xml` (full URL: `https://www.welkar.in/sitemap.xml`).
3. Click **Submit**.
4. Verify the status updates to **Success** with 35 discovered URLs.

### 3.3 Priority URL Inspection & Indexing Request
Submit key canonical URLs for immediate priority crawling via the URL Inspection tool:
- `https://www.welkar.in/` (Homepage)
- `https://www.welkar.in/catalog` (Catalog Hub)
- `https://www.welkar.in/collections/luxury-watches` (New Luxury Collection)
- `https://www.welkar.in/collections/sports-watches` (New Sports Collection)
- `https://www.welkar.in/brands` (Brand Portfolio)
- `https://www.welkar.in/about` (Brand Entity Story)

### 3.4 Monitoring & Enhancement Audits
1. **Rich Results Test**: Validate product URLs on [Google Rich Results Test](https://search.google.com/test/rich-results) to confirm valid `Product` and `BreadcrumbList` schema without critical warnings.
2. **PageSpeed Insights**: Benchmark mobile and desktop Core Web Vitals (LCP < 2.5s, CLS < 0.1).
3. **Core Web Vitals Report**: Monitor real-user field data in GSC under **Experience** > **Core Web Vitals**.

---

## 4. Rollback Plan

If an unexpected production hosting issue occurs:
1. **Code Rollback**: Replace changed files with the previous backup archive:
   - `git checkout HEAD~1` (if Git-based deployment) or re-extract pre-deployment backup zip.
2. **Database Rollback**: In the event of FAQ formatting rollback, execute:
   ```bash
   php scripts/inspect_products.php
   ```
3. **Cache Invalidation**: Clear Cloudflare/CDN cache for HTML and sitemap routes.

---

## 5. Owner-Action Summary (External Credentials Required)

The following actions require account credentials owned exclusively by the business owner and cannot be executed automatically:
1. **Google Search Console Ownership**: Add the DNS TXT record to the domain registrar to verify `welkar.in`.
2. **Google Merchant Center**: If linking Google Shopping free product listings, connect feed pointing to `https://www.welkar.in/sitemap.xml`.
3. **Google Analytics 4**: If deploying GA4 measurement ID (`G-XXXXXXXXXX`), add the measurement ID in admin settings under `/admin/settings`.
4. **Google Business Profile**: Claim official business listing for Welkar Watch Co. matching the entity details in `public/llms.txt`.
