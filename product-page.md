# Product Page Specification — Premium Amazon A+ Style & Horological Reviews
**Project:** Welkar — Premium Watch E-Commerce Platform (`INR ₹`)  
**File purpose:** Master implementation specification for Antigravity IDE  
**Status:** Build-ready requirements; calibrated for Swiss & Japanese horology standards  
**Primary goal:** Make every Welkar timepiece detail page feel like a premium, Amazon A+-inspired luxury horology showcase, followed by a complete, trustworthy customer review and verification section. This is an original luxury watch experience inspired by the *structure, depth, and conversion flow* of Amazon Premium A+ content—tailored exclusively for Welkar Timepieces with no external branding or assets.

---

## 1. Instructions to Antigravity IDE

Treat this file as the master implementation brief for the Welkar Product Detail Page (PDP) system.

### Source-of-Truth Files and Constraints
1. [`map.md`](file:///e:/welkar/map.md) is the source of truth for the website's route mappings, navigation, and information architecture.
2. [`design.md`](file:///e:/welkar/design.md) and [`public/assets/css/welkar.css`](file:///e:/welkar/public/assets/css/welkar.css) are the sources of truth for the visual design system:
   - **Palette**: Midnight Sapphire (`#0A1128`), Rich Royal Navy (`#0F1A36`), Swiss Champagne Gold (`#C59B3F` / `#DFB75C`), Crisp Cool Alabaster (`#F5F7FB`), and Midnight Charcoal (`#0D1527`).
   - **Typography**: *DM Serif Display* (display headings & titles) + *Inter* (body, metrics & controls).
   - **Spacing & Radii**: 8-point rhythm scale (`--space-1` through `--space-12`), 6px subtle radii (`--radius-sm`), and soft luxury elevation shadows.
3. Preserve all existing storefront and checkout functionality. Do not break cart sessions, GST calculation, Indian shipping threshold rules, sequential tax invoicing, or admin authentication.
4. Maintain the Core PHP 8.2+ dependency-free MVC architecture with PDO database transactions on `welkar_db`.
5. Product listings must be completely data-driven and manageable through the Welkar Admin Portal. No hardcoded product details or static reviews.
6. Absolute honesty & integrity: Never fabricate watch specs, water depth certifications, fake countdown stock tickers, fake customer ratings, or artificial reviews.
7. Support the proprietary **Welkar Live Animated Watch Dial simulation** on eligible analog timepieces alongside traditional photography.

---

## 2. Page Experience and Module Hierarchy (15 Modules)

Every Welkar timepiece product page renders the following 15 modules in structured, conversion-optimized order. Each module is toggleable and reorderable per product via the admin portal:

### Module 1 — Breadcrumbs
- **Hierarchy**: `Home` > `Collections` > `{Gender / Category}` > `{Timepiece Model Name}`
- **Behavior**: Working semantic links, Schema.org BreadcrumbList microdata, and clean responsive wrapping.

### Module 2 — Luxury Horology Hero / Primary Product Detail
- **Desktop Layout**: 
  - **Left (55%)**: Vertical multi-angle thumbnail gallery, high-res zoomable primary viewport, and toggleable **"Live Sweeping Hands Mode"** for calibrated analog watches.
  - **Right (45%)**: Brand line (`WELKAR ATELIER`), timepiece model name (H1), model/SKU, genuine star rating summary, live price & compare-at savings badge, dial & strap variant selector chips, live inventory stock status, quantity stepper, and **"Add to Cart"** + **"Buy with COD / UPI"** actions.
  - **Trust Badges**: Official 1-Year Atelier Guarantee, 30M Hydrostatic Water Resistance, Insured Pan-India Transit, and 7-Day Doorstep Replacement.

### Module 3 — Key Pillars / Feature Strip
- 4 concise horological badges:
  1. *316L Surgical Stainless Steel*
  2. *Japanese Caliber Movement (+/-15s/mo)*
  3. *30M Hydrostatic Sealed Submersion*
  4. *Scratch-Resistant Hardened Crystal*

### Module 4 — Premium A+ Wide Banner
- Full-bleed 1464×600px desktop canvas (with mobile art direction) showcasing the watch in an editorial setting (e.g. boardroom executive, weekend regatta, or evening black-tie gala).
- HTML-rendered headline, body copy, and subtle champagne gold divider.

### Module 5 — Interactive Hotspot / Horological Anatomy
- Interactive watch macro photograph featuring animated gold pulsator hotspot markers:
  - Hotspot 1: *Double-Gasket Fluted Crown (Silicone O-Rings)*
  - Hotspot 2: *Luminescent Baton Hands & Applied Indices*
  - Hotspot 3: *Brushed & Polished Bezel Chamfering*
  - Hotspot 4: *Solid Link Butterfly Deployant Clasp*
- Clicking any hotspot opens a floating detail card explaining the craftsmanship.

### Module 6 — Lifestyle Carousel ("On The Wrist")
- 4 to 6 curated lifestyle wristshots showing the watch on diverse wrist sizes (5.5" to 7.5").
- Touch-swipeable on mobile with thumbnail indicators and keyboard arrow support.

### Module 7 — Feature Stories (Alternating Grid)
- 3 deep-dive craftsmanship stories with alternating image/copy alignment:
  - Story 1: *The 316L Cold-Forging Process*
  - Story 2: *Hydrostatic Pressure Chamber Submersion Testing*
  - Story 3: *Precision Laser-Engraved Serial Backplates*

### Module 8 — Technical Horological Specifications
- Two-column technical specification matrix:
  - Case Diameter, Case Thickness, Lug-to-Lug Distance, Lug Width
  - Dial Glass, Caliber Movement, Battery Life / Power Reserve
  - Water Depth Rating, Clasp Mechanism, Weight (grams)
  - Box Contents & Serial Authentication Card

### Module 9 — Atelier Craftsmanship Video (Optional)
- 16:9 embedded demonstration clip showing the watch sweep movement, case finishing, and water submersion test with play/pause controls and poster fallback.

### Module 10 — Product Family Comparison Chart
- Responsive comparison matrix comparing the current timepiece against 2 to 3 related Welkar models across Case Size, Movement, Strap Material, Water Resistance, and Price.

### Module 11 — Product-Specific FAQs & Q&A
- 4 to 6 curated questions covering strap resizing, battery replacement, water resistance safety, and shipping timeline.

### Module 12 — Review Summary & Rating Distribution
- Verified average star rating (e.g., 4.9 ★) and total approved review count.
- Interactive star breakdown bars (5-star down to 1-star percentage).
- Verified buyer photo gallery strip showing actual customer wrist photos.
- Filter chips: *"All Reviews"*, *"Verified Purchases"*, *"With Photos"*, and Star Rating filters.

### Module 13 — Complete Customer Reviews & Moderation
- Customer review cards containing:
  - Star rating, verified purchase badge, reviewer name & location, review date.
  - Review title, full comment, variant purchased (e.g., *Dial: Midnight Black / Strap: Silver Steel*).
  - Customer uploaded photos.
  - Helpful voting button (`Was this helpful? Yes (14)`).
  - Official Welkar Atelier response (if provided by admin).
- **Review Submission Modal**: Star rating input, title, text, optional photo uploads, and customer name.

### Module 14 — Curated Recommendations ("Complete The Atelier")
- Carousel of 4 related timepieces or leather travel pouches that complement the selected watch.

### Module 15 — Sticky Purchase Reminder
- Non-intrusive floating bottom bar appearing once the user scrolls past the main hero, showing the timepiece thumbnail, name, active variant, price, and instant Add-to-Cart button.

---

## 3. Database Schema Model (Migration 002)

To support dynamic A+ modules and complete review moderation while preserving existing tables:

1. `product_page_modules`:
   - `id`, `product_id`, `module_type`, `title`, `subtitle`, `content_json`, `sort_order`, `is_enabled`, `created_at`, `updated_at`
2. `product_hotspots`:
   - `id`, `module_id`, `product_id`, `x_pos_percent`, `y_pos_percent`, `title`, `description`, `sort_order`
3. `product_comparison_matrix`:
   - `id`, `product_id`, `compared_product_id`, `feature_name`, `feature_value`, `sort_order`
4. `product_faqs`:
   - `id`, `product_id` (nullable for global), `question`, `answer`, `sort_order`, `is_active`
5. Extended `reviews` table:
   - Ensure support for `title`, `comment`, `rating`, `is_verified_purchase`, `variant_id`, `images_json`, `helpful_votes`, `admin_response`, `status` (`pending`, `approved`, `rejected`)

---

## 4. Admin Portal Product Page Builder (10 Tabs)

Add the **"A+ Page Studio & Reviews"** tab inside [`app/Controllers/Admin/ProductController.php`](file:///e:/welkar/app/Controllers/Admin/ProductController.php):
1. **Basic Details & Horology Meta**: Model, caliber, dial classification, base pricing, GST rate.
2. **Variants & Combinations**: Dial color, strap type, SKU, individual pricing.
3. **Media & Dial Calibration**: Multi-angle image uploads + Live Sweeping Hand coordinate markers.
4. **A+ Module Studio**: Drag-and-drop ordering, toggle modules, edit headlines, upload banners.
5. **Hotspot Mapper**: Interactive point-and-click image canvas to position hotspot pins.
6. **Horology Specs**: Key/value attributes table.
7. **Comparison Matrix**: Choose comparable timepieces and enter row values.
8. **Curated FAQs**: Reorderable Q&A entries.
9. **Review Moderation Queue**: Moderate pending customer reviews, verify purchase linkage, view photos, approve/reject, and write official atelier replies.
10. **SEO & Social Graph**: Structured JSON-LD preview, OpenGraph preview, and canonical URLs.

---

## 5. Phased Implementation Plan

- **Phase 1: Database & Model Services** — Create migration `002_product_page_aplus_schema.sql`, add service methods in `CatalogService` and `ReviewService`.
- **Phase 2: Storefront PDP Template & 15 Modules** — Refactor `resources/views/storefront/product.php` into modular sub-views under `resources/views/storefront/pdp/`.
- **Phase 3: Interactive Components & JavaScript** — Implement interactive hotspot clicks, lifestyle swipe carousel, thumbnail gallery switcher, live sweeping hands simulator toggle, and review submission modal.
- **Phase 4: Admin Page Studio & Review Queue** — Enhance admin product edit screens to manage all A+ modules and reviews.
- **Phase 5: Automated Testing & Verification** — Update test suite in `tests/run_tests.php` to validate review calculations, verified purchase derivations, and module rendering.
