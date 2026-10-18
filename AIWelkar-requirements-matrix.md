# AIWelkar Requirements Matrix

**Project:** Welkar Watch E-Commerce Platform  
**Target Market:** India (INR ₹)  
**Status Standard:** `Not Started`, `In Progress`, `Implemented`, `Tested`, `Blocked`, `Not Applicable`  
**Date:** 2026-10-16  

---

## Storefront & Customer Experience

| Req ID | Feature / Specification | Implementation Status | Test Status | Notes |
|---|---|---|---|---|
| SF-001 | Responsive Storefront Layout & Header | Implemented | Tested | Bootstrap 5, mobile navigation, search modal, cart drawer |
| SF-002 | Homepage Sections (Hero, Featured, Categories) | Implemented | Tested | Dynamic database queries with fallback |
| SF-003 | "Pick Your Perfect Dial" Architecture Cards | Implemented | Tested | Filters catalog by dial style (Roman, Stick, Chronograph, etc.) |
| SF-004 | Live Animated Watch Simulation (Aura White Silver) | Implemented | Tested | Real-time synchronized hands with static photo fallback |
| SF-005 | Product Catalog & Faceted Search | Implemented | Tested | Filter by brand, gender, movement, price, availability |
| SF-006 | Product Detail Page (PDP) & Image Zoom | Implemented | Tested | Gallery, specifications, FAQs, comparison table |
| SF-007 | Shopping Cart & Coupon Engine | Implemented | Tested | Stock re-validation, discount caps, threshold calculations |
| SF-008 | GST Calculation Engine (Intra/Inter-State) | Implemented | Tested | Maharashtra (CGST+SGST) vs Other States (IGST) |
| SF-009 | Free Shipping Threshold (₹1,499) & ₹60 Fee | Implemented | Tested | Dynamic calculation in Cart & Checkout |
| SF-010 | Cash on Delivery (COD) & Razorpay Checkout | Implemented | In Progress | Removing synthetic success mock; securing Razorpay flow |
| SF-011 | Order Success Page IDOR Protection | In Progress | Not Tested | Verifying order ownership / session token |
| SF-012 | Customer Phone OTP & Email Authentication | Implemented | In Progress | Removing debug_otp leak and securing password set |
| SF-013 | Customer Account Dashboard & Order Tracking | Implemented | Tested | Orders list, invoice download, cancel order |
| SF-014 | Wishlist Management & Session Merge | Implemented | Tested | Guest session & authenticated wishlist |
| SF-015 | Reviews & Helpful Voting | Implemented | In Progress | Adding explicit CSRF middleware to POST routes |
| SF-016 | Multi-Language Support (English / Hindi) | Implemented | Tested | Language toggle (`/lang/en`, `/lang/hi`) with en fallback |
| SF-017 | Static CMS Pages (About, Terms, Privacy, FAQs) | Implemented | Tested | Dynamic CMS routing with slug resolution |

---

## Admin Portal & Business Management

| Req ID | Feature / Specification | Implementation Status | Test Status | Notes |
|---|---|---|---|---|
| AD-001 | Admin Authentication & Email MFA | Implemented | In Progress | Removing debug MFA code exposure |
| AD-002 | Business Dashboard KPIs & Sales Reports | Implemented | In Progress | Fixing SQL query syntax for 'Today' date filter |
| AD-003 | Product Catalog Management (CRUD) | Implemented | Tested | Create, edit, variant management, image gallery |
| AD-004 | Inventory Stock Ledger & Adjustments | Implemented | Tested | Ledger movements, reason tracking, balance checks |
| AD-005 | Category Management & Safeguards | In Progress | Not Tested | Adding category edit & safe delete checks |
| AD-006 | Brand Management (Admin & Storefront) | In Progress | Not Tested | Adding BrandController, admin views, storefront brand pages |
| AD-007 | Order Processing & Tracking Updates | Implemented | Tested | Status transitions, tracking number assignment |
| AD-008 | Returns & Refunds Lifecycle | In Progress | Not Tested | Adding return processing & refund ledger in admin |
| AD-009 | Staff Roles & Permissions | In Progress | Not Tested | Adding staff user management & audit log viewer |
| AD-010 | Coupon & Marketing Campaign Manager | Implemented | Tested | Create discount codes, usage limits, date ranges |
| AD-011 | Abandoned Cart Viewer & Recovery | In Progress | Not Tested | Adding recovery link generator in marketing module |
| AD-012 | Bulk CSV Product Importer & Dry-Run | Implemented | Tested | Formula injection protection, row validation |
| AD-013 | Customer CRM & Order History | Implemented | Tested | View registered users, contact info, order counts |
| AD-014 | Store Settings & GST Details | Implemented | Tested | Legal entity, GSTIN, shipping threshold, secrets |
| AD-015 | Company Profile, Founders & Milestones | Implemented | Tested | Founder bios, company history, brand values |

---

## Security, Operations & Infrastructure

| Req ID | Feature / Specification | Implementation Status | Test Status | Notes |
|---|---|---|---|---|
| SEC-001 | SQL Injection Defense | Implemented | Tested | 100% PDO prepared statements across services |
| SEC-002 | XSS Defense & Output Escaping | Implemented | Tested | Context-aware `e()` escaping across views |
| SEC-003 | CSRF Protection | In Progress | Not Tested | Adding middleware to review, vote, and wishlist routes |
| SEC-004 | Webhook Signature Verification | In Progress | Not Tested | Implementing HMAC-SHA256 verification in RazorpayAdapter |
| SEC-005 | Sequential GST Tax Invoicing (WEL-2026-XXXX) | Implemented | Tested | Concurrency-safe invoice sequence counter |
| SEC-006 | Clean Production Packaging (.zip) | Implemented | Tested | 14.1 MB clean build, UTF-8 SQL backup, forward slashes |
