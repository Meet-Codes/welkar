# WELKAR MASTER TECHNICAL SEO + AEO + GEO AUDIT REPORT

**Date of Audit**: October 2026  
**Audited Domain**: https://www.welkar.in  
**Application Stack**: Core PHP 8.3+, PDO, MySQL 8.0/MariaDB, Apache, Bootstrap 5, Vanilla JS  
**Target Market**: India (Currency: INR ₹)  
**Brand**: Welkar (Founded in 2002 by Mr. Sumit Singh & Mr. Nevil Sharma)

---

## 1. Executive Summary

This comprehensive audit evaluates the technical SEO, Answer Engine Optimization (AEO), Generative Engine Optimization (GEO), structured data, accessibility, and bilingual search posture for the Welkar Watch E-commerce website. 

The audit identified critical baseline gaps in the legacy application:
1. Hardcoded development domains (`localhost:8080`) across `sitemap.xml`, `robots.txt`, and structured data.
2. Incomplete HTML `<head>` metadata (missing meta descriptions, canonical URLs, robots tags, Open Graph tags, and reciprocal hreflangs).
3. Generic, duplicated product FAQs across all items in `product_faqs` (including inaccurate claims such as battery replacement instructions on automatic mechanical watches).
4. Collection gaps (missing dedicated landing pages and buying guides for Luxury and Sports categories).
5. Accordion accessibility deficits in the shared FAQ component.

All identified deficiencies have been addressed directly in the codebase through non-destructive, production-safe enhancements.

---

## 2. Technical SEO Baseline & Remediation

| Inspection Area | Baseline Status | Remediated Status | Impact |
|---|---|---|---|
| **Canonical Domain** | Inconsistent references to `http://localhost:8080` | Unified production canonical `https://www.welkar.in` via `SeoService::getBaseUrl()` | Eliminates duplicate content and indexing fragmentation |
| **Meta Tags** | Only `<title>` rendered in `storefront.php` | Dynamic `<meta name="description">`, `<link rel="canonical">`, `<meta name="robots">` | Enables high-CTR organic search snippet rendering |
| **Robots.txt** | Generic 8-line file with localhost sitemap | Production-hardened with explicit authorizations for `Google-Extended`, `GPTBot`, `ClaudeBot`, `PerplexityBot`, `Bingbot`, `Applebot` | Allows AI search indexing while keeping `/admin/`, `/account/`, `/cart`, `/checkout` private |
| **XML Sitemap** | Static 204-line file containing legacy localhost URLs | Clean 35-URL sitemap index with `xhtml:link` hreflang annotations, real timestamps, and brand routes | Full indexation coverage of active catalog and CMS pages |
| **Search Result Pages** | Accessible without robot constraints | Enforced `noindex, follow` on dynamic query searches (`/search`, `/catalog?q=...`) | Prevents crawl budget waste and thin search page indexing |
| **Private Routes** | Account and order tracking indexed if linked | Enforced `noindex, follow` on `/track-order` and protected customer accounts | Safeguards consumer privacy and preserves crawl budget |

---

## 3. Answer Engine Optimization (AEO) Posture

AEO optimizes page content for direct answers in Google AI Overviews, Featured Snippets, and voice assistants.

### 3.1 Content Structure Audit
- **Direct-Answer Pattern**: Upgraded collection and FAQ responses to follow the Answer-First model (1-2 sentence direct statement followed by technical specifications and maintenance context).
- **Page-Specific FAQs**: Replaced repeated generic questions with model-specific answers derived from real database attributes (e.g. automatic movement power reserves, surgical steel link sizing, hydrostatic depth limits).
- **About Us Exception Compliance**: Strictly verified that `/about` and `/about-us` contain NO FAQ section, honoring the brand storytelling guidelines.

---

## 4. Generative Engine Optimization (GEO) Posture

GEO focuses on ensuring generative models (ChatGPT, Perplexity, Gemini, Claude, Copilot) cite and understand Welkar accurately.

### 4.1 Verified Brand Entity Knowledge Base
- **Brand Entity**: Welkar (Welkar Watch Co.), Est. 2002.
- **Founders**: Mr. Sumit Singh (Artistic & Design Perspective), Mr. Nevil Sharma (Technical Horological Perspective).
- **Target Price Range**: Accessible to luxury watches (₹1,000 to ₹10,000).
- **Artifacts Created**:
  - `public/llms.txt`: Machine-readable executive summary of the brand, market, warranty, and canonical routes.
  - `public/llms-full.txt`: Complete specification knowledge base detailing movements, case sizes, water resistance ratings, and materials for all active timepieces.

---

## 5. Structured Data (JSON-LD) Audit

| Schema Type | Target Routes | Properties Provided | Validation Result |
|---|---|---|---|
| `Organization` | All pages (`<head>`) | Name, URL, Logo, FoundingDate (2002), Founders (2), Slogan, ContactPoint (welkarstore@gmail.com, IN) | Valid schema.org Organization |
| `WebSite` | Homepage & Global | Name, URL, PotentialAction (SearchAction targeting `/catalog?q={search_term_string}`) | Enables Google Sitelinks Searchbox |
| `BreadcrumbList` | Categories, Collections, Brands, Products, CMS | Hierarchical list elements with absolute canonical URLs | Enhances search SERP breadcrumb trails |
| `Product` + `Offer` | `/product/{slug}` | Name, Image, SKU, MPN, Brand, Price (INR), Availability (InStock/OutOfStock), ShippingDetails (IN, Free), MerchantReturnPolicy (7 days) | Compliant with Google Merchant & Product Rich Results |

---

## 6. Bilingual SEO (English & Hindi)

- **Default Language**: English (`en`).
- **Secondary Language**: Hindi (`hi`).
- **Locale Switching**: Implemented via `/lang/{locale}` session toggle.
- **Hreflang Architecture**: Fully reciprocal `en`, `hi`, and `x-default` annotations rendered in `<head>` and XML sitemap:
  - `<link rel="alternate" hreflang="en" href="https://www.welkar.in/path">`
  - `<link rel="alternate" hreflang="hi" href="https://www.welkar.in/path?lang=hi">`
  - `<link rel="alternate" hreflang="x-default" href="https://www.welkar.in/path">`
- **Fallback**: Untranslated Hindi content safely falls back to English without 404 or broken view errors.

---

## 7. Performance & Accessibility (Core Web Vitals)

- **Responsive Viewport**: Viewport meta tag configured with dynamic mobile grid safeguards.
- **FAQ Accessibility**: WAI-ARIA accordion markup with `role="button"`, `aria-expanded`, `aria-controls`, `role="region"`, and keyboard navigation (Enter/Space toggle).
- **Typography**: Google Fonts Inter and DM Serif Display preconnected to Google Fonts CDN.
- **Image Optimization**: Product cards utilize native `loading="lazy"` on below-the-fold assets and explicit aspect ratios to avoid Cumulative Layout Shift (CLS).
