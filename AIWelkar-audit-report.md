# AIWelkar Audit Report

**Project:** Welkar — Premium Watch E-Commerce Platform  
**Target Market:** India (INR ₹)  
**Architecture:** Core PHP 8.3+, MySQL 8.0+ / MariaDB, Bootstrap 5, Vanilla JS  
**Audit Date:** 2026-10-16  
**Auditor:** Senior Software & Security Reviewer  

---

## 1. Executive Summary

A comprehensive read-only audit of the existing Welkar repository was conducted in accordance with [`AIWelkar.md`](file:///e:/welkar/AIWelkar.md). The audit inspected storefront routes, controllers, middleware, authentication flows, payment gateway integration, database schema (75 tables), admin portal controllers, assets, and security posture.

The audit verified **6 Critical/High Security & Payment defects (P0/P1)** and identified **6 Missing Business Management Workflows (P2)** that are fully supported by database tables but lacked complete administrative controllers, routes, and views.

---

## 2. Verified Vulnerabilities & Defect Ledger

### DEFECT SEC-001: Synthetic Payment Capture & Instant Order Success Bypass
- **Severity:** P0 Critical
- **Category:** Payment Integrity & Financial Fraud
- **Status:** Verified
- **Location:** [`app/Controllers/Storefront/CheckoutController.php`](file:///e:/welkar/app/Controllers/Storefront/CheckoutController.php#L100-L103) (`process()` method)
- **Code Evidence:**
  ```php
  if ($paymentMethod === 'razorpay') {
      $razorpayData = RazorpayAdapter::createOrder($order['id'], (float)$order['total_amount']);
      // Mock success in local test mode
      RazorpayAdapter::recordSuccess($order['id'], 'pay_test_' . bin2hex(random_bytes(6)), $razorpayData['razorpay_order_id']);
  }
  Response::redirect('/order/success/' . $order['order_number']);
  ```
- **Observed Behavior:** When a customer selects Razorpay at checkout, the controller immediately generates a fake payment ID (`pay_test_...`), marks the order as paid and captured, and redirects to order confirmation without actually collecting payment or waiting for webhook/gateway signature confirmation.
- **Expected Behavior:** Razorpay orders must return the Razorpay Order ID and key to the frontend checkout modal. Payment capture must only occur after cryptographic verification of `razorpay_payment_id`, `razorpay_order_id`, and `razorpay_signature` or through verified server-to-server webhooks.
- **Impact:** High financial loss; anyone can place unpaid orders and have them marked as confirmed/paid.
- **Root Cause:** Hardcoded mock logic left active in the storefront controller.
- **Minimal Fix:** Remove the immediate `recordSuccess()` call. Render the client-side checkout modal or payment pending state; verify gateway signature upon callback or webhook.
- **Safe Without Owner Input:** Yes.

---

### DEFECT SEC-002: Webhook Signature Verification Bypass & Missing Order State Update
- **Severity:** P0 Critical
- **Category:** Payment Webhooks & Authorization
- **Status:** Verified
- **Location:** [`app/Services/Payments/RazorpayAdapter.php`](file:///e:/welkar/app/Services/Payments/RazorpayAdapter.php#L69-L87) (`handleWebhook()` method)
- **Code Evidence:**
  ```php
  public static function handleWebhook(array $payload, ?string $signature): array {
      // Signature is accepted but NEVER validated with hash_hmac against webhook secret!
      // No order or payment status is updated!
  ```
- **Observed Behavior:** The webhook endpoint receives the signature header, but completely ignores it. Anyone can POST arbitrary fake `payment.captured` payloads to `/webhooks/razorpay` without secret validation. Furthermore, the webhook does not actually update the order or payment status.
- **Expected Behavior:** Verify HMAC-SHA256 signature using `hash_hmac('sha256', $rawBody, $webhookSecret)`. If signature matches, transition order and payment states atomically in a database transaction with idempotency.
- **Impact:** Spoofed payment confirmations, potential replay attacks, or failure to fulfill legitimate orders paid asynchronously.
- **Root Cause:** Incomplete webhook implementation stub.
- **Minimal Fix:** Validate HMAC-SHA256 signature over raw request body. Process `payment.captured`, `payment.failed`, and `refund.processed` events atomically.
- **Safe Without Owner Input:** Yes.

---

### DEFECT SEC-003: Customer Account Takeover via Unverified Password Setting
- **Severity:** P0 Critical
- **Category:** Authentication & Account Security
- **Status:** Verified
- **Location:** [`app/Services/Auth/CustomerAuthService.php`](file:///e:/welkar/app/Services/Auth/CustomerAuthService.php#L100-L104) (`loginOrRegisterWithEmail()`)
- **Code Evidence:**
  ```php
  if ($user) {
      if (!empty($user['password_hash'])) {
          if (!password_verify($password, $user['password_hash'])) {
              return ['success' => false, 'message' => 'Incorrect password...'];
          }
      } else {
          // First time setting password
          $hash = password_hash($password, PASSWORD_BCRYPT);
          Database::execute("UPDATE users SET password_hash = ? WHERE id = ?", [$hash, $user['id']]);
      }
  }
  ```
- **Observed Behavior:** If an existing customer registered via Phone OTP or guest checkout with an email address, their account has `password_hash = NULL`. Any attacker who enters that victim's email address on `/account/email-auth` can supply a new password and immediately take over the victim's account, order history, addresses, and saved data without verifying email or phone OTP.
- **Expected Behavior:** An account without a password must prove identity via Phone OTP or email verification challenge before setting an initial password.
- **Impact:** Critical customer account takeover and PII exposure.
- **Root Cause:** Missing proof-of-ownership challenge for passwordless accounts.
- **Minimal Fix:** If `$user['password_hash']` is null, require phone OTP or verification challenge before setting password.
- **Safe Without Owner Input:** Yes.

---

### DEFECT SEC-004: Debug OTP & MFA Code Leakage in Responses, Session & UI
- **Severity:** P0 Critical
- **Category:** Authentication & Information Disclosure
- **Status:** Verified
- **Location:**
  - [`app/Services/Auth/CustomerAuthService.php`](file:///e:/welkar/app/Services/Auth/CustomerAuthService.php#L31) (`sendOtp()`)
  - [`app/Services/Auth/AdminAuthService.php`](file:///e:/welkar/app/Services/Auth/AdminAuthService.php#L32-L34) (`attempt()`)
  - [`resources/views/auth/admin_mfa.php`](file:///e:/welkar/resources/views/auth/admin_mfa.php#L9-L13)
- **Code Evidence:**
  - `CustomerAuthService`: `'debug_otp' => $otp` returned in JSON/response array.
  - `AdminAuthService`: `Session::set('debug_mfa_code', $code); return ['debug_code' => $code];`
  - `admin_mfa.php`: Displays `Dev Mode MFA Code: <?= e($debugCode) ?>` right above input field!
- **Observed Behavior:** One-time passwords and admin MFA codes are leaked in response arrays and displayed on screen in HTML.
- **Expected Behavior:** In production or non-local environment, OTP and MFA codes must NEVER be returned to the client or displayed in the UI. In local test mode, restrict to secure backend logging.
- **Impact:** Complete bypass of 2FA/MFA security controls.
- **Root Cause:** Developer test conveniences left unshielded by environment checks.
- **Minimal Fix:** Strip `debug_otp` and `debug_code` when `APP_ENV === 'production'` or `APP_DEBUG === 'false'`. Remove the hardcoded code display from `admin_mfa.php`.
- **Safe Without Owner Input:** Yes.

---

### DEFECT SEC-005: IDOR & Customer PII Exposure on Order Confirmation
- **Severity:** P1 High
- **Category:** Insecure Direct Object Reference (IDOR)
- **Status:** Verified
- **Location:** [`app/Controllers/Storefront/CheckoutController.php`](file:///e:/welkar/app/Controllers/Storefront/CheckoutController.php#L112-L130) (`success()`)
- **Code Evidence:**
  ```php
  public function success(Request $request, string $orderNumber): string {
      $order = Database::fetch("SELECT * FROM orders WHERE order_number = ?", [$orderNumber]);
      ...
      return View::render('storefront.order_success', [...]);
  }
  ```
- **Observed Behavior:** Order numbers are sequential/predictable (`WEL-2026-1001`, `WEL-2026-1002`). Any anonymous user who enters `/order/success/{order_number}` can view the full name, shipping address, phone number, email address, ordered items, and invoice of any customer.
- **Expected Behavior:** Access to the success page must verify either:
  1. The authenticated customer owns the order (`user_id === auth_user()['id']`), or
  2. The session contains a cryptographic confirmation token set during checkout (`Session::get('placed_order_token') === $order['order_hash']`).
- **Impact:** Mass customer privacy violation and order harvesting.
- **Root Cause:** Absence of ownership or token verification on the order success route.
- **Minimal Fix:** Validate that the order belongs to the logged-in user or matches the session checkout order token.
- **Safe Without Owner Input:** Yes.

---

### DEFECT SEC-006: State-Changing Routes Missing CSRF Middleware
- **Severity:** P1 High
- **Category:** Cross-Site Request Forgery (CSRF)
- **Status:** Verified
- **Location:** [`config/routes.php`](file:///e:/welkar/config/routes.php#L47-L48) & [`config/routes.php`](file:///e:/welkar/config/routes.php#L58)
- **Code Evidence:**
  ```php
  $router->post('/product/{slug}/review', [ProductController::class, 'submitReview']);
  $router->post('/api/reviews/{id}/vote', [ProductController::class, 'voteHelpful']);
  $router->post('/wishlist/toggle', [WishlistController::class, 'toggle']);
  ```
- **Observed Behavior:** These three state-changing POST endpoints do not attach `CsrfMiddleware::class`.
- **Expected Behavior:** All browser-originated state-changing requests must be wrapped with CSRF verification.
- **Impact:** Attackers can submit fake reviews, manipulate helpful vote counts, or alter user wishlists via cross-site requests.
- **Root Cause:** Omission of middleware in route declarations.
- **Minimal Fix:** Attach `[CsrfMiddleware::class]` to all three routes in `config/routes.php`.
- **Safe Without Owner Input:** Yes.

---

### DEFECT DB-001: SQL Syntax Error & Non-Standard Group-By in Analytics
- **Severity:** P1 High
- **Category:** Database Query Error
- **Status:** Verified
- **Location:** [`app/Services/Analytics/AnalyticsService.php`](file:///e:/welkar/app/Services/Analytics/AnalyticsService.php#L70) & [`#L107-L115`](file:///e:/welkar/app/Services/Analytics/AnalyticsService.php#L107-L115)
- **Code Evidence:**
  ```php
  $whereClause = ($days === 0) ? "DATE(created_at) = CURDATE()" : "created_at >= DATE_SUB(NOW(), INTERVAL {$days} DAY)";
  ...
  // In top products:
  JOIN orders o ON oi.order_id = o.id
  WHERE o.status != 'cancelled' AND o.{$whereClause}
  ```
- **Observed Behavior:** When `$days === 0` (filtering for "Today"), `o.{$whereClause}` produces `o.DATE(created_at) = CURDATE()`, which causes a fatal SQL syntax error in MySQL/MariaDB. Additionally, `oi.product_name` is selected without inclusion in `GROUP BY oi.product_id, oi.sku`, violating `ONLY_FULL_GROUP_BY`.
- **Expected Behavior:** Queries must use clean table-aliased conditions: `DATE(o.created_at) = CURDATE()` and include all non-aggregated columns in `GROUP BY`.
- **Impact:** Admin dashboard crashes with an uncaught SQL error when filtering by "Today".
- **Root Cause:** Invalid string interpolation of table alias prefix on SQL function calls.
- **Minimal Fix:** Normalize table prefix in date filters and standardize `GROUP BY`.
- **Safe Without Owner Input:** Yes.

---

## 3. Business Management Workflow Gaps

| Module | Required Feature (AIWelkar.md §9) | Current Implementation Status | Gap & Plan |
|---|---|---|---|
| **Brands** | Brand CRUD, logo, slug, storefront brand page (`/brands`, `/brand/{slug}`) | Database table `brands` exists with seed data; no admin controller or storefront brand view | Implement `BrandController.php` (Admin & Storefront), brand routes, and admin brand management |
| **Categories** | Full category editing, deletion safeguards, merge protection | Only `CategoryController::storeCategory` exists | Add category edit/update, delete check (prevent delete if products exist) |
| **Banners / CMS** | Homepage hero banner CRUD, desktop/mobile image, schedule | Database tables `banners` & `banner_translations` exist; no admin controller | Implement `BannerController.php` in admin with banner list, add, edit, and toggle |
| **Staff & Roles** | Admin users CRUD, roles, permissions, audit log viewer | Database tables `admin_users`, `roles`, `permissions`, `audit_logs` exist; no admin UI | Implement `StaffController.php` & `AuditLogController.php` for role & security governance |
| **Orders / Returns** | Return & refund lifecycle, status tracking, restock toggle | Database tables `returns`, `refunds` exist; admin order view only has tracking | Implement return request review, approval/rejection, and refund recording in `OrderController.php` |
| **Cart Recovery** | Abandoned cart detection & recoverable link generator | Database table `abandoned_cart_reminders` exists; dashboard has no actionable UI | Add abandoned cart viewer with recovery link generator in admin marketing |

---

## 4. Conclusion & Next Action

All identified defects have been verified directly in the active source code. Repairs will be executed sequentially according to the prioritized repair plan without unnecessary redesign or structural refactoring.
