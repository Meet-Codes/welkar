-- Welkar PostgreSQL Schema & Data Migration Dump
-- Converted for PostgreSQL 14+ / Neon / Render Hosted PostgreSQL
-- Character Set: UTF8

SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET escape_string_warning = off;

-- 1. Schema reset (clean recreate)
DROP SCHEMA IF EXISTS public CASCADE;
CREATE SCHEMA public;
GRANT ALL ON SCHEMA public TO public;

-- Table structure for: abandoned_cart_reminders
CREATE TABLE "abandoned_cart_reminders" (
  "id" BIGSERIAL PRIMARY KEY,
  "cart_id" BIGINT NOT NULL,
  "user_id" BIGINT DEFAULT NULL,
  "email" varchar(191) DEFAULT NULL,
  "phone" varchar(20) DEFAULT NULL,
  "reminder_step" INTEGER DEFAULT 1,
  "token" varchar(64) NOT NULL,
  "status" VARCHAR(50) DEFAULT 'pending',
  "sent_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT NULL,
  "recovered_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT NULL,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT "uq_abandoned_cart_reminders_token" UNIQUE ("token")
);

-- Table structure for: ad_integrations
CREATE TABLE "ad_integrations" (
  "id" SERIAL PRIMARY KEY,
  "provider" varchar(50) NOT NULL,
  "account_id" varchar(100) DEFAULT NULL,
  "is_connected" SMALLINT DEFAULT 0,
  "credentials_json" TEXT DEFAULT NULL,
  "last_synced_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT NULL,
  "updated_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT "uq_ad_integrations_provider" UNIQUE ("provider")
);

-- Table structure for: admin_mfa_challenges
CREATE TABLE "admin_mfa_challenges" (
  "id" BIGSERIAL PRIMARY KEY,
  "admin_user_id" INTEGER NOT NULL,
  "code" varchar(10) NOT NULL,
  "expires_at" TIMESTAMP WITHOUT TIME ZONE NOT NULL,
  "verified_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT NULL,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for: admin_user_roles
CREATE TABLE "admin_user_roles" (
  "admin_user_id" INTEGER NOT NULL,
  "role_id" INTEGER NOT NULL,
  PRIMARY KEY ("admin_user_id", "role_id")
);

-- Table structure for: admin_users
CREATE TABLE "admin_users" (
  "id" SERIAL PRIMARY KEY,
  "username" varchar(60) NOT NULL,
  "email" varchar(191) NOT NULL,
  "password_hash" varchar(255) NOT NULL,
  "full_name" varchar(150) NOT NULL,
  "role_slug" varchar(50) NOT NULL DEFAULT 'manager',
  "status" VARCHAR(50) DEFAULT 'active',
  "mfa_enabled" SMALLINT DEFAULT 0,
  "last_login_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT NULL,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  "updated_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT "uq_admin_users_username" UNIQUE ("username"),
  CONSTRAINT "uq_admin_users_email" UNIQUE ("email")
);

-- Table structure for: analytics_events
CREATE TABLE "analytics_events" (
  "id" BIGSERIAL PRIMARY KEY,
  "event_name" varchar(80) NOT NULL,
  "session_id" varchar(120) NOT NULL,
  "user_id" BIGINT DEFAULT NULL,
  "page_url" varchar(255) NOT NULL,
  "referrer" varchar(255) DEFAULT NULL,
  "ip_address" varchar(45) DEFAULT NULL,
  "user_agent" varchar(255) DEFAULT NULL,
  "utm_source" varchar(80) DEFAULT NULL,
  "utm_medium" varchar(80) DEFAULT NULL,
  "utm_campaign" varchar(120) DEFAULT NULL,
  "utm_content" varchar(120) DEFAULT NULL,
  "utm_term" varchar(120) DEFAULT NULL,
  "event_data" TEXT DEFAULT NULL,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for: analytics_integrations
CREATE TABLE "analytics_integrations" (
  "id" SERIAL PRIMARY KEY,
  "provider" varchar(50) NOT NULL,
  "is_connected" SMALLINT DEFAULT 0,
  "credentials_json" TEXT DEFAULT NULL,
  "last_synced_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT NULL,
  "updated_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT "uq_analytics_integrations_provider" UNIQUE ("provider")
);

-- Table structure for: audit_logs
CREATE TABLE "audit_logs" (
  "id" BIGSERIAL PRIMARY KEY,
  "user_type" VARCHAR(50) NOT NULL DEFAULT 'system',
  "user_id" BIGINT DEFAULT NULL,
  "action" varchar(100) NOT NULL,
  "entity_type" varchar(80) NOT NULL,
  "entity_id" varchar(100) DEFAULT NULL,
  "old_values" TEXT DEFAULT NULL,
  "new_values" TEXT DEFAULT NULL,
  "ip_address" varchar(45) DEFAULT NULL,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for: banner_translations
CREATE TABLE "banner_translations" (
  "id" SERIAL PRIMARY KEY,
  "banner_id" INTEGER NOT NULL,
  "locale" varchar(10) NOT NULL,
  "title" varchar(150) NOT NULL,
  "subtitle" varchar(255) DEFAULT NULL,
  "button_text" varchar(80) DEFAULT NULL,
  CONSTRAINT "uq_banner_translations_uk_bt" UNIQUE ("banner_id","locale")
);

-- Table structure for: banners
CREATE TABLE "banners" (
  "id" SERIAL PRIMARY KEY,
  "title" varchar(150) NOT NULL,
  "subtitle" varchar(255) DEFAULT NULL,
  "image_desktop" varchar(255) NOT NULL,
  "image_mobile" varchar(255) DEFAULT NULL,
  "link_url" varchar(255) NOT NULL DEFAULT '/catalog',
  "button_text" varchar(80) NOT NULL DEFAULT 'Explore Collection',
  "display_order" INTEGER DEFAULT 0,
  "starts_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT NULL,
  "ends_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT NULL,
  "is_active" SMALLINT DEFAULT 1,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for: brands
CREATE TABLE "brands" (
  "id" SERIAL PRIMARY KEY,
  "name" varchar(120) NOT NULL,
  "slug" varchar(140) NOT NULL,
  "logo_url" text DEFAULT NULL,
  "description" text DEFAULT NULL,
  "is_featured" SMALLINT DEFAULT 0,
  "is_active" SMALLINT DEFAULT 1,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT "uq_brands_slug" UNIQUE ("slug")
);

-- Table structure for: campaigns
CREATE TABLE "campaigns" (
  "id" SERIAL PRIMARY KEY,
  "name" varchar(150) NOT NULL,
  "slug" varchar(160) NOT NULL,
  "description" text DEFAULT NULL,
  "discount_type" VARCHAR(50) NOT NULL,
  "discount_value" NUMERIC(10,2) NOT NULL,
  "min_spend" NUMERIC(10,2) DEFAULT 0.00,
  "max_discount" NUMERIC(10,2) DEFAULT NULL,
  "starts_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT NULL,
  "ends_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT NULL,
  "is_active" SMALLINT DEFAULT 1,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT "uq_campaigns_slug" UNIQUE ("slug")
);

-- Table structure for: cart_events
CREATE TABLE "cart_events" (
  "id" BIGSERIAL PRIMARY KEY,
  "cart_id" BIGINT NOT NULL,
  "event_type" varchar(60) NOT NULL,
  "session_id" varchar(120) NOT NULL,
  "payload" TEXT DEFAULT NULL,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for: cart_items
CREATE TABLE "cart_items" (
  "id" BIGSERIAL PRIMARY KEY,
  "cart_id" BIGINT NOT NULL,
  "variant_id" BIGINT NOT NULL,
  "quantity" INTEGER NOT NULL DEFAULT 1,
  "unit_price" NUMERIC(10,2) NOT NULL,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  "updated_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT "uq_cart_items_uk_ci_cart_var" UNIQUE ("cart_id","variant_id")
);

-- Table structure for: carts
CREATE TABLE "carts" (
  "id" BIGSERIAL PRIMARY KEY,
  "session_id" varchar(120) NOT NULL,
  "user_id" BIGINT DEFAULT NULL,
  "coupon_code" varchar(50) DEFAULT NULL,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  "updated_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT "uq_carts_session_id" UNIQUE ("session_id")
);

-- Table structure for: categories
CREATE TABLE "categories" (
  "id" SERIAL PRIMARY KEY,
  "parent_id" INTEGER DEFAULT NULL,
  "name" varchar(120) NOT NULL,
  "slug" varchar(140) NOT NULL,
  "description" text DEFAULT NULL,
  "image_url" text DEFAULT NULL,
  "sort_order" INTEGER DEFAULT 0,
  "is_active" SMALLINT DEFAULT 1,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT "uq_categories_slug" UNIQUE ("slug")
);

-- Table structure for: collections
CREATE TABLE "collections" (
  "id" SERIAL PRIMARY KEY,
  "name" varchar(150) NOT NULL,
  "slug" varchar(160) NOT NULL,
  "description" text DEFAULT NULL,
  "banner_image" varchar(255) DEFAULT NULL,
  "is_active" SMALLINT DEFAULT 1,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT "uq_collections_slug" UNIQUE ("slug")
);

-- Table structure for: company_founders
CREATE TABLE "company_founders" (
  "id" SERIAL PRIMARY KEY,
  "name" varchar(150) NOT NULL,
  "designation" varchar(150) NOT NULL DEFAULT 'Co-Founder',
  "perspective" varchar(150) NOT NULL,
  "bio" text NOT NULL,
  "image_url" varchar(500) DEFAULT NULL,
  "image_alt" varchar(255) DEFAULT NULL,
  "display_order" INTEGER NOT NULL DEFAULT 0,
  "is_published" SMALLINT NOT NULL DEFAULT 1,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  "updated_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for: company_milestones
CREATE TABLE "company_milestones" (
  "id" SERIAL PRIMARY KEY,
  "year_date" varchar(50) NOT NULL,
  "title" varchar(200) NOT NULL,
  "description" text NOT NULL,
  "image_url" varchar(500) DEFAULT NULL,
  "display_order" INTEGER NOT NULL DEFAULT 0,
  "is_published" SMALLINT NOT NULL DEFAULT 1,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for: company_profile
CREATE TABLE "company_profile" (
  "id" INTEGER NOT NULL DEFAULT 1,
  "brand_name" varchar(100) NOT NULL DEFAULT 'WELKAR',
  "tagline" varchar(255) NOT NULL DEFAULT 'Precision, Heritage, and Trust—Since 2002.',
  "founding_year" varchar(10) NOT NULL DEFAULT '2002',
  "hero_title" varchar(255) NOT NULL DEFAULT 'Precision, Heritage, and Trust—Since 2002.',
  "hero_intro" text NOT NULL,
  "story_title" varchar(255) NOT NULL DEFAULT 'Our Story',
  "story_intro" text NOT NULL,
  "story_content" TEXT NOT NULL,
  "philosophy_title" varchar(255) NOT NULL DEFAULT 'Our Philosophy',
  "philosophy_intro" text NOT NULL,
  "approach_watches_title" varchar(255) NOT NULL DEFAULT 'Our Approach to Watches',
  "approach_watches_content" TEXT NOT NULL,
  "quality_title" varchar(255) NOT NULL DEFAULT 'Our Approach to Quality',
  "quality_content" TEXT NOT NULL,
  "mission_statement" text NOT NULL,
  "vision_statement" text NOT NULL,
  "commitment_title" varchar(255) NOT NULL DEFAULT 'Our Commitment to Customers',
  "commitment_content" TEXT NOT NULL,
  "journey_ahead_title" varchar(255) NOT NULL DEFAULT 'The Journey Ahead',
  "journey_ahead_content" text NOT NULL,
  "home_summary" text NOT NULL,
  "footer_summary" text NOT NULL,
  "pdp_summary" text NOT NULL,
  "collection_summary" text NOT NULL,
  "seo_title" varchar(255) NOT NULL DEFAULT 'About Welkar | Precision, Heritage, and Trust—Since 2002',
  "seo_description" varchar(255) NOT NULL DEFAULT 'Learn the story of WELKAR, founded in 2002 by Mr. Sumit Singh and Mr. Nevil Sharma on the principles of precision, heritage, and lasting trust.',
  "updated_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY ("id")
);

-- Table structure for: company_values
CREATE TABLE "company_values" (
  "id" SERIAL PRIMARY KEY,
  "name" varchar(100) NOT NULL,
  "tagline" varchar(200) NOT NULL,
  "description" text NOT NULL,
  "icon_class" varchar(100) NOT NULL DEFAULT 'bi-check-circle',
  "display_order" INTEGER NOT NULL DEFAULT 0,
  "is_published" SMALLINT NOT NULL DEFAULT 1,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for: consent_records
CREATE TABLE "consent_records" (
  "id" BIGSERIAL PRIMARY KEY,
  "user_id" BIGINT DEFAULT NULL,
  "consent_type" varchar(80) NOT NULL,
  "granted" SMALLINT NOT NULL,
  "ip_address" varchar(45) DEFAULT NULL,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for: content_pages
CREATE TABLE "content_pages" (
  "id" SERIAL PRIMARY KEY,
  "slug" varchar(100) NOT NULL,
  "locale" varchar(10) NOT NULL DEFAULT 'en',
  "title" varchar(200) NOT NULL,
  "content_html" TEXT NOT NULL,
  "meta_title" varchar(200) DEFAULT NULL,
  "meta_description" varchar(255) DEFAULT NULL,
  "is_published" SMALLINT DEFAULT 1,
  "updated_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT "uq_content_pages_uk_page_slug_loc" UNIQUE ("slug","locale")
);

-- Table structure for: coupon_redemptions
CREATE TABLE "coupon_redemptions" (
  "id" BIGSERIAL PRIMARY KEY,
  "coupon_id" INTEGER NOT NULL,
  "user_id" BIGINT DEFAULT NULL,
  "order_id" BIGINT NOT NULL,
  "discount_applied" NUMERIC(10,2) NOT NULL,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for: coupons
CREATE TABLE "coupons" (
  "id" SERIAL PRIMARY KEY,
  "code" varchar(50) NOT NULL,
  "description" varchar(255) DEFAULT NULL,
  "discount_type" VARCHAR(50) NOT NULL DEFAULT 'percentage',
  "discount_value" NUMERIC(10,2) NOT NULL,
  "min_order_value" NUMERIC(10,2) NOT NULL DEFAULT 0.00,
  "max_discount_value" NUMERIC(10,2) DEFAULT NULL,
  "usage_limit_total" INTEGER DEFAULT NULL,
  "usage_limit_per_user" INTEGER DEFAULT 1,
  "usage_count" INTEGER DEFAULT 0,
  "is_first_order_only" SMALLINT DEFAULT 0,
  "starts_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT NULL,
  "expires_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT NULL,
  "is_active" SMALLINT DEFAULT 1,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT "uq_coupons_code" UNIQUE ("code")
);

-- Table structure for: credit_notes
CREATE TABLE "credit_notes" (
  "id" BIGSERIAL PRIMARY KEY,
  "invoice_id" BIGINT NOT NULL,
  "credit_note_number" varchar(60) NOT NULL,
  "amount" NUMERIC(10,2) NOT NULL,
  "reason" varchar(255) NOT NULL,
  "file_path" varchar(255) DEFAULT NULL,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT "uq_credit_notes_credit_note_number" UNIQUE ("credit_note_number")
);

-- Table structure for: customer_addresses
CREATE TABLE "customer_addresses" (
  "id" BIGSERIAL PRIMARY KEY,
  "user_id" BIGINT NOT NULL,
  "full_name" varchar(150) NOT NULL,
  "phone" varchar(20) NOT NULL,
  "address_line1" varchar(255) NOT NULL,
  "address_line2" varchar(255) DEFAULT NULL,
  "city" varchar(100) NOT NULL,
  "state" varchar(100) NOT NULL,
  "state_code" varchar(10) NOT NULL,
  "pincode" varchar(10) NOT NULL,
  "is_default" SMALLINT DEFAULT 0,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  "updated_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for: customer_segments
CREATE TABLE "customer_segments" (
  "id" SERIAL PRIMARY KEY,
  "name" varchar(100) NOT NULL,
  "slug" varchar(100) NOT NULL,
  "criteria_json" TEXT DEFAULT NULL,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT "uq_customer_segments_slug" UNIQUE ("slug")
);

-- Table structure for: daily_analytics_summaries
CREATE TABLE "daily_analytics_summaries" (
  "id" BIGSERIAL PRIMARY KEY,
  "summary_date" date NOT NULL,
  "visitors_count" INTEGER DEFAULT 0,
  "sessions_count" INTEGER DEFAULT 0,
  "page_views" INTEGER DEFAULT 0,
  "add_to_cart_count" INTEGER DEFAULT 0,
  "checkouts_count" INTEGER DEFAULT 0,
  "orders_count" INTEGER DEFAULT 0,
  "gross_revenue" NUMERIC(12,2) DEFAULT 0.00,
  "net_revenue" NUMERIC(12,2) DEFAULT 0.00,
  "ad_spend" NUMERIC(12,2) DEFAULT 0.00,
  CONSTRAINT "uq_daily_analytics_summaries_summary_date" UNIQUE ("summary_date")
);

-- Table structure for: import_job_rows
CREATE TABLE "import_job_rows" (
  "id" BIGSERIAL PRIMARY KEY,
  "import_job_id" BIGINT NOT NULL,
  "row_index" INTEGER NOT NULL,
  "raw_data" TEXT NOT NULL,
  "status" VARCHAR(50) NOT NULL,
  "error_message" text DEFAULT NULL,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for: import_jobs
CREATE TABLE "import_jobs" (
  "id" BIGSERIAL PRIMARY KEY,
  "admin_user_id" INTEGER NOT NULL,
  "filename" varchar(255) NOT NULL,
  "file_path" varchar(255) NOT NULL,
  "mode" VARCHAR(50) DEFAULT 'upsert',
  "total_rows" INTEGER DEFAULT 0,
  "imported_rows" INTEGER DEFAULT 0,
  "skipped_rows" INTEGER DEFAULT 0,
  "failed_rows" INTEGER DEFAULT 0,
  "status" VARCHAR(50) DEFAULT 'pending',
  "error_report_path" varchar(255) DEFAULT NULL,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  "completed_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT NULL
);

-- Table structure for: inventory_locations
CREATE TABLE "inventory_locations" (
  "id" SERIAL PRIMARY KEY,
  "name" varchar(100) NOT NULL,
  "code" varchar(40) NOT NULL,
  "is_default" SMALLINT DEFAULT 1,
  "is_active" SMALLINT DEFAULT 1,
  CONSTRAINT "uq_inventory_locations_code" UNIQUE ("code")
);

-- Table structure for: inventory_movements
CREATE TABLE "inventory_movements" (
  "id" BIGSERIAL PRIMARY KEY,
  "variant_id" BIGINT NOT NULL,
  "location_id" INTEGER NOT NULL,
  "change_qty" INTEGER NOT NULL,
  "balance_after" INTEGER NOT NULL,
  "reason" varchar(100) NOT NULL,
  "reference_type" varchar(60) DEFAULT NULL,
  "reference_id" varchar(100) DEFAULT NULL,
  "created_by" varchar(100) DEFAULT NULL,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for: inventory_reservations
CREATE TABLE "inventory_reservations" (
  "id" BIGSERIAL PRIMARY KEY,
  "variant_id" BIGINT NOT NULL,
  "session_id" varchar(120) NOT NULL,
  "order_id" BIGINT DEFAULT NULL,
  "quantity" INTEGER NOT NULL,
  "expires_at" TIMESTAMP WITHOUT TIME ZONE NOT NULL,
  "status" VARCHAR(50) DEFAULT 'active',
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for: inventory_stock
CREATE TABLE "inventory_stock" (
  "id" BIGSERIAL PRIMARY KEY,
  "variant_id" BIGINT NOT NULL,
  "location_id" INTEGER NOT NULL,
  "quantity" INTEGER NOT NULL DEFAULT 0,
  "reserved_quantity" INTEGER NOT NULL DEFAULT 0,
  "low_stock_threshold" INTEGER NOT NULL DEFAULT 5,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  "updated_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT "uq_inventory_stock_uk_stock_var_loc" UNIQUE ("variant_id","location_id")
);

-- Table structure for: invoice_sequences
CREATE TABLE "invoice_sequences" (
  "id" SERIAL PRIMARY KEY,
  "fiscal_year" varchar(10) NOT NULL,
  "prefix" varchar(20) NOT NULL DEFAULT 'WEL',
  "current_number" INTEGER NOT NULL DEFAULT 1000,
  CONSTRAINT "uq_invoice_sequences_fiscal_year" UNIQUE ("fiscal_year")
);

-- Table structure for: invoices
CREATE TABLE "invoices" (
  "id" BIGSERIAL PRIMARY KEY,
  "order_id" BIGINT NOT NULL,
  "invoice_number" varchar(60) NOT NULL,
  "invoice_date" date NOT NULL,
  "file_path" varchar(255) DEFAULT NULL,
  "total_taxable" NUMERIC(10,2) NOT NULL,
  "total_cgst" NUMERIC(10,2) NOT NULL DEFAULT 0.00,
  "total_sgst" NUMERIC(10,2) NOT NULL DEFAULT 0.00,
  "total_igst" NUMERIC(10,2) NOT NULL DEFAULT 0.00,
  "grand_total" NUMERIC(10,2) NOT NULL,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT "uq_invoices_order_id" UNIQUE ("order_id"),
  CONSTRAINT "uq_invoices_invoice_number" UNIQUE ("invoice_number")
);

-- Table structure for: notification_logs
CREATE TABLE "notification_logs" (
  "id" BIGSERIAL PRIMARY KEY,
  "channel" VARCHAR(50) NOT NULL,
  "recipient" varchar(191) NOT NULL,
  "subject" varchar(255) DEFAULT NULL,
  "body" text NOT NULL,
  "status" VARCHAR(50) DEFAULT 'sent',
  "error_message" text DEFAULT NULL,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for: order_addresses
CREATE TABLE "order_addresses" (
  "id" BIGSERIAL PRIMARY KEY,
  "order_id" BIGINT NOT NULL,
  "type" VARCHAR(50) DEFAULT 'shipping',
  "full_name" varchar(150) NOT NULL,
  "phone" varchar(20) NOT NULL,
  "address_line1" varchar(255) NOT NULL,
  "address_line2" varchar(255) DEFAULT NULL,
  "city" varchar(100) NOT NULL,
  "state" varchar(100) NOT NULL,
  "state_code" varchar(10) NOT NULL,
  "pincode" varchar(10) NOT NULL,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for: order_items
CREATE TABLE "order_items" (
  "id" BIGSERIAL PRIMARY KEY,
  "order_id" BIGINT NOT NULL,
  "product_id" BIGINT NOT NULL,
  "variant_id" BIGINT NOT NULL,
  "product_name" varchar(200) NOT NULL,
  "variant_name" varchar(120) NOT NULL,
  "sku" varchar(100) NOT NULL,
  "unit_price" NUMERIC(10,2) NOT NULL,
  "quantity" INTEGER NOT NULL,
  "subtotal" NUMERIC(10,2) NOT NULL,
  "discount_share" NUMERIC(10,2) NOT NULL DEFAULT 0.00,
  "tax_rate" NUMERIC(5,2) NOT NULL DEFAULT 18.00,
  "tax_amount" NUMERIC(10,2) NOT NULL,
  "total" NUMERIC(10,2) NOT NULL,
  "snapshot_data" TEXT DEFAULT NULL,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for: order_status_history
CREATE TABLE "order_status_history" (
  "id" BIGSERIAL PRIMARY KEY,
  "order_id" BIGINT NOT NULL,
  "old_status" varchar(50) DEFAULT NULL,
  "new_status" varchar(50) NOT NULL,
  "comment" text DEFAULT NULL,
  "changed_by" varchar(100) NOT NULL DEFAULT 'system',
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for: orders
CREATE TABLE "orders" (
  "id" BIGSERIAL PRIMARY KEY,
  "order_number" varchar(50) NOT NULL,
  "user_id" BIGINT DEFAULT NULL,
  "guest_email" varchar(191) DEFAULT NULL,
  "guest_phone" varchar(20) NOT NULL,
  "status" VARCHAR(50) DEFAULT 'pending',
  "payment_status" VARCHAR(50) DEFAULT 'unpaid',
  "fulfilment_status" VARCHAR(50) DEFAULT 'unfulfilled',
  "currency" varchar(10) NOT NULL DEFAULT 'INR',
  "subtotal" NUMERIC(10,2) NOT NULL,
  "discount_amount" NUMERIC(10,2) NOT NULL DEFAULT 0.00,
  "shipping_fee" NUMERIC(10,2) NOT NULL DEFAULT 0.00,
  "tax_amount" NUMERIC(10,2) NOT NULL DEFAULT 0.00,
  "total_amount" NUMERIC(10,2) NOT NULL,
  "payment_method" varchar(50) NOT NULL DEFAULT 'cod',
  "coupon_code" varchar(50) DEFAULT NULL,
  "notes" text DEFAULT NULL,
  "shipping_state" varchar(100) NOT NULL,
  "is_cancelled" SMALLINT DEFAULT 0,
  "cancelled_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT NULL,
  "cancel_reason" varchar(255) DEFAULT NULL,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  "updated_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT "uq_orders_order_number" UNIQUE ("order_number")
);

-- Table structure for: otp_challenges
CREATE TABLE "otp_challenges" (
  "id" BIGSERIAL PRIMARY KEY,
  "identifier" varchar(191) NOT NULL,
  "otp_code" varchar(10) NOT NULL,
  "type" varchar(30) NOT NULL DEFAULT 'login',
  "attempts" INTEGER DEFAULT 0,
  "max_attempts" INTEGER DEFAULT 5,
  "expires_at" TIMESTAMP WITHOUT TIME ZONE NOT NULL,
  "verified_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT NULL,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for: payment_events
CREATE TABLE "payment_events" (
  "id" BIGSERIAL PRIMARY KEY,
  "payment_id" BIGINT NOT NULL,
  "event_name" varchar(80) NOT NULL,
  "payload" TEXT DEFAULT NULL,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for: payments
CREATE TABLE "payments" (
  "id" BIGSERIAL PRIMARY KEY,
  "order_id" BIGINT NOT NULL,
  "gateway" varchar(50) NOT NULL,
  "transaction_id" varchar(100) DEFAULT NULL,
  "gateway_order_id" varchar(100) DEFAULT NULL,
  "amount" NUMERIC(10,2) NOT NULL,
  "currency" varchar(10) NOT NULL DEFAULT 'INR',
  "status" VARCHAR(50) DEFAULT 'pending',
  "payment_method" varchar(50) DEFAULT NULL,
  "gateway_payload" TEXT DEFAULT NULL,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  "updated_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for: permissions
CREATE TABLE "permissions" (
  "id" SERIAL PRIMARY KEY,
  "name" varchar(100) NOT NULL,
  "slug" varchar(80) NOT NULL,
  "module" varchar(60) NOT NULL,
  "description" varchar(255) DEFAULT NULL,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT "uq_permissions_slug" UNIQUE ("slug")
);

-- Table structure for: product_categories
CREATE TABLE "product_categories" (
  "product_id" BIGINT NOT NULL,
  "category_id" INTEGER NOT NULL,
  PRIMARY KEY ("product_id", "category_id")
);

-- Table structure for: product_collections
CREATE TABLE "product_collections" (
  "product_id" BIGINT NOT NULL,
  "collection_id" INTEGER NOT NULL,
  PRIMARY KEY ("product_id", "collection_id")
);

-- Table structure for: product_comparison_rows
CREATE TABLE "product_comparison_rows" (
  "id" BIGSERIAL PRIMARY KEY,
  "feature_name" varchar(150) NOT NULL,
  "sort_order" INTEGER NOT NULL DEFAULT 0,
  "is_active" SMALLINT NOT NULL DEFAULT 1
);

-- Table structure for: product_comparison_values
CREATE TABLE "product_comparison_values" (
  "id" BIGSERIAL PRIMARY KEY,
  "product_id" BIGINT NOT NULL,
  "row_id" BIGINT NOT NULL,
  "value" varchar(255) NOT NULL,
  CONSTRAINT "uq_product_comparison_values_uk_pcv" UNIQUE ("product_id","row_id")
);

-- Table structure for: product_comparisons
CREATE TABLE "product_comparisons" (
  "id" BIGSERIAL PRIMARY KEY,
  "session_id" varchar(120) NOT NULL,
  "product_id" BIGINT NOT NULL,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT "uq_product_comparisons_uk_cmp" UNIQUE ("session_id","product_id")
);

-- Table structure for: product_faqs
CREATE TABLE "product_faqs" (
  "id" BIGSERIAL PRIMARY KEY,
  "product_id" BIGINT DEFAULT NULL COMMENT 'NULL indicates global watch FAQ',
  "question" varchar(255) NOT NULL,
  "answer" text NOT NULL,
  "sort_order" INTEGER NOT NULL DEFAULT 0,
  "is_active" SMALLINT NOT NULL DEFAULT 1
);

-- Table structure for: product_hotspots
CREATE TABLE "product_hotspots" (
  "id" BIGSERIAL PRIMARY KEY,
  "product_id" BIGINT NOT NULL,
  "x_pos_percent" NUMERIC(5,2) NOT NULL DEFAULT 50.00,
  "y_pos_percent" NUMERIC(5,2) NOT NULL DEFAULT 50.00,
  "title" varchar(150) NOT NULL,
  "description" text NOT NULL,
  "image_url" varchar(1000) DEFAULT NULL,
  "sort_order" INTEGER NOT NULL DEFAULT 0,
  "is_active" SMALLINT NOT NULL DEFAULT 1
);

-- Table structure for: product_images
CREATE TABLE "product_images" (
  "id" BIGSERIAL PRIMARY KEY,
  "product_id" BIGINT NOT NULL,
  "variant_id" BIGINT DEFAULT NULL,
  "image_url" text NOT NULL,
  "is_primary" SMALLINT DEFAULT 0,
  "sort_order" INTEGER DEFAULT 0,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for: product_page_modules
CREATE TABLE "product_page_modules" (
  "id" BIGSERIAL PRIMARY KEY,
  "product_id" BIGINT NOT NULL,
  "module_type" varchar(60) NOT NULL COMMENT 'banner, hotspots, stories, lifestyle, video, comparison, faq, spec',
  "title" varchar(255) DEFAULT NULL,
  "subtitle" text DEFAULT NULL,
  "content_json" TEXT DEFAULT NULL,
  "sort_order" INTEGER NOT NULL DEFAULT 0,
  "is_enabled" SMALLINT NOT NULL DEFAULT 1,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  "updated_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for: product_specifications
CREATE TABLE "product_specifications" (
  "id" BIGSERIAL PRIMARY KEY,
  "product_id" BIGINT NOT NULL,
  "spec_name" varchar(100) NOT NULL,
  "spec_value" varchar(255) NOT NULL,
  "sort_order" INTEGER DEFAULT 0
);

-- Table structure for: product_translations
CREATE TABLE "product_translations" (
  "id" BIGSERIAL PRIMARY KEY,
  "product_id" BIGINT NOT NULL,
  "locale" varchar(10) NOT NULL,
  "name" varchar(200) NOT NULL,
  "short_description" text DEFAULT NULL,
  "description" TEXT DEFAULT NULL,
  CONSTRAINT "uq_product_translations_uk_prod_trans" UNIQUE ("product_id","locale")
);

-- Table structure for: product_variants
CREATE TABLE "product_variants" (
  "id" BIGSERIAL PRIMARY KEY,
  "product_id" BIGINT NOT NULL,
  "sku" varchar(100) NOT NULL,
  "barcode" varchar(60) DEFAULT NULL,
  "variant_name" varchar(120) NOT NULL,
  "dial_color" varchar(50) DEFAULT 'White',
  "strap_color" varchar(50) DEFAULT 'Silver',
  "price" NUMERIC(10,2) NOT NULL,
  "compare_at_price" NUMERIC(10,2) DEFAULT NULL,
  "cost_price" NUMERIC(10,2) DEFAULT NULL,
  "is_active" SMALLINT DEFAULT 1,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  "updated_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT "uq_product_variants_sku" UNIQUE ("sku")
);

-- Table structure for: products
CREATE TABLE "products" (
  "id" BIGSERIAL PRIMARY KEY,
  "brand_id" INTEGER NOT NULL,
  "name" varchar(200) NOT NULL,
  "slug" varchar(220) NOT NULL,
  "model_number" varchar(100) DEFAULT NULL,
  "base_sku" varchar(100) NOT NULL,
  "short_description" text DEFAULT NULL,
  "description" TEXT DEFAULT NULL,
  "gender" varchar(20) DEFAULT 'Unisex',
  "dial_type" varchar(60) NOT NULL DEFAULT 'Analog',
  "movement" varchar(80) NOT NULL DEFAULT 'Quartz',
  "case_material" varchar(80) NOT NULL DEFAULT 'Stainless Steel',
  "case_size_mm" INTEGER DEFAULT 42,
  "case_color" varchar(50) DEFAULT 'Silver',
  "strap_material" varchar(80) DEFAULT 'Stainless Steel',
  "strap_color" varchar(50) DEFAULT 'Silver',
  "water_resistance" varchar(50) DEFAULT '30m / 3 ATM',
  "warranty_years" INTEGER DEFAULT 1,
  "country_of_origin" varchar(60) DEFAULT 'India',
  "base_price" NUMERIC(10,2) NOT NULL,
  "cost_price" NUMERIC(10,2) DEFAULT NULL,
  "compare_at_price" NUMERIC(10,2) DEFAULT NULL,
  "is_live_dial_animated" SMALLINT DEFAULT 0,
  "dial_base_image" varchar(255) DEFAULT NULL,
  "dial_hour_hand_image" varchar(255) DEFAULT NULL,
  "dial_minute_hand_image" varchar(255) DEFAULT NULL,
  "dial_second_hand_image" varchar(255) DEFAULT NULL,
  "dial_center_x" INTEGER DEFAULT 50,
  "dial_center_y" INTEGER DEFAULT 50,
  "hsn_code" varchar(20) DEFAULT '9102',
  "gst_rate" NUMERIC(5,2) DEFAULT 18.00,
  "is_featured" SMALLINT DEFAULT 0,
  "is_active" SMALLINT DEFAULT 1,
  "meta_title" varchar(200) DEFAULT NULL,
  "meta_description" varchar(255) DEFAULT NULL,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  "updated_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT "uq_products_slug" UNIQUE ("slug"),
  CONSTRAINT "uq_products_base_sku" UNIQUE ("base_sku")
);

-- Table structure for: refunds
CREATE TABLE "refunds" (
  "id" BIGSERIAL PRIMARY KEY,
  "order_id" BIGINT NOT NULL,
  "payment_id" BIGINT DEFAULT NULL,
  "amount" NUMERIC(10,2) NOT NULL,
  "reason" varchar(255) NOT NULL,
  "gateway_refund_id" varchar(100) DEFAULT NULL,
  "status" VARCHAR(50) DEFAULT 'pending',
  "processed_by" varchar(100) DEFAULT NULL,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for: return_items
CREATE TABLE "return_items" (
  "id" BIGSERIAL PRIMARY KEY,
  "return_id" BIGINT NOT NULL,
  "order_item_id" BIGINT NOT NULL,
  "quantity" INTEGER NOT NULL DEFAULT 1,
  "reason" varchar(255) DEFAULT NULL,
  "condition_notes" text DEFAULT NULL
);

-- Table structure for: returns
CREATE TABLE "returns" (
  "id" BIGSERIAL PRIMARY KEY,
  "order_id" BIGINT NOT NULL,
  "user_id" BIGINT DEFAULT NULL,
  "status" VARCHAR(50) DEFAULT 'requested',
  "reason" varchar(255) NOT NULL,
  "comments" text DEFAULT NULL,
  "is_restocked" SMALLINT DEFAULT 0,
  "restocked_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT NULL,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  "updated_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for: review_votes
CREATE TABLE "review_votes" (
  "id" BIGSERIAL PRIMARY KEY,
  "review_id" BIGINT NOT NULL,
  "session_id" varchar(120) NOT NULL,
  "ip_address" varchar(45) DEFAULT NULL,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT "uq_review_votes_uk_rv" UNIQUE ("review_id","session_id")
);

-- Table structure for: reviews
CREATE TABLE "reviews" (
  "id" BIGSERIAL PRIMARY KEY,
  "product_id" BIGINT NOT NULL,
  "user_id" BIGINT DEFAULT NULL,
  "order_id" BIGINT DEFAULT NULL,
  "variant_id" BIGINT DEFAULT NULL,
  "author_name" varchar(120) NOT NULL,
  "avatar_url" varchar(255) DEFAULT NULL,
  "rating" SMALLINT NOT NULL,
  "title" varchar(150) DEFAULT NULL,
  "comment" text NOT NULL,
  "is_verified_purchase" SMALLINT DEFAULT 0,
  "status" VARCHAR(50) DEFAULT 'approved',
  "helpful_count" INTEGER NOT NULL DEFAULT 0,
  "images_json" text DEFAULT NULL,
  "admin_response" text DEFAULT NULL,
  "admin_responded_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT NULL,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for: role_permissions
CREATE TABLE "role_permissions" (
  "role_id" INTEGER NOT NULL,
  "permission_id" INTEGER NOT NULL,
  PRIMARY KEY ("role_id", "permission_id")
);

-- Table structure for: roles
CREATE TABLE "roles" (
  "id" SERIAL PRIMARY KEY,
  "name" varchar(100) NOT NULL,
  "slug" varchar(60) NOT NULL,
  "description" varchar(255) DEFAULT NULL,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT "uq_roles_slug" UNIQUE ("slug")
);

-- Table structure for: shipping_shipments
CREATE TABLE "shipping_shipments" (
  "id" BIGSERIAL PRIMARY KEY,
  "order_id" BIGINT NOT NULL,
  "courier_name" varchar(100) NOT NULL,
  "tracking_number" varchar(100) NOT NULL,
  "tracking_url" varchar(255) DEFAULT NULL,
  "dispatched_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT NULL,
  "estimated_delivery_at" date DEFAULT NULL,
  "delivered_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT NULL,
  "notes" text DEFAULT NULL,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  "updated_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for: store_settings
CREATE TABLE "store_settings" (
  "id" SERIAL PRIMARY KEY,
  "setting_key" varchar(100) NOT NULL,
  "setting_value" TEXT DEFAULT NULL,
  "group_name" varchar(50) NOT NULL DEFAULT 'general',
  "updated_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT "uq_store_settings_setting_key" UNIQUE ("setting_key")
);

-- Table structure for: support_messages
CREATE TABLE "support_messages" (
  "id" BIGSERIAL PRIMARY KEY,
  "ticket_id" BIGINT NOT NULL,
  "sender_type" VARCHAR(50) NOT NULL,
  "sender_name" varchar(100) NOT NULL,
  "message" text NOT NULL,
  "created_at" TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for: support_tickets
CREATE TABLE "support_tickets" (
  "id" BIGSERIAL PRIMARY KEY,
  "ticket_number" varchar(30) NOT NULL,
  "user_id" BIGINT DEFAULT NULL,
  "order_id" BIGINT DEFAULT NULL,
  "order_number" varchar(50) DEFAULT NULL,
  "customer_name" varchar(100) NOT NULL,
  "customer_phone" varchar(25) NOT NULL,
  "customer_email" varchar(120) DEFAULT NULL,
  "category" varchar(50) NOT NULL DEFAULT 'general',
  "subject" varchar(255) NOT NULL,
  "status" VARCHAR(50) NOT NULL DEFAULT 'open',
  "priority" VARCHAR(50) NOT NULL DEFAULT 'medium',
  "admin_notes" text DEFAULT NULL,
  "last_message_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT NULL,
  "created_at" TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "updated_at" TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT "uq_support_tickets_ticket_number" UNIQUE ("ticket_number")
);

-- Table structure for: system_logs
CREATE TABLE "system_logs" (
  "id" BIGSERIAL PRIMARY KEY,
  "level" varchar(20) NOT NULL DEFAULT 'info',
  "message" text NOT NULL,
  "context" TEXT DEFAULT NULL,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for: testimonials
CREATE TABLE "testimonials" (
  "id" SERIAL PRIMARY KEY,
  "author_name" varchar(120) NOT NULL,
  "author_location" varchar(100) DEFAULT NULL,
  "quote" text NOT NULL,
  "rating" SMALLINT DEFAULT 5,
  "watch_model" varchar(100) DEFAULT NULL,
  "is_active" SMALLINT DEFAULT 1,
  "display_order" INTEGER DEFAULT 0,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for: users
CREATE TABLE "users" (
  "id" BIGSERIAL PRIMARY KEY,
  "phone" varchar(20) DEFAULT NULL,
  "email" varchar(191) DEFAULT NULL,
  "password_hash" varchar(255) DEFAULT NULL,
  "full_name" varchar(150) NOT NULL,
  "is_phone_verified" SMALLINT DEFAULT 0,
  "is_email_verified" SMALLINT DEFAULT 0,
  "status" VARCHAR(50) DEFAULT 'active',
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  "updated_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT "uq_users_phone" UNIQUE ("phone"),
  CONSTRAINT "uq_users_email" UNIQUE ("email")
);

-- Table structure for: utm_sessions
CREATE TABLE "utm_sessions" (
  "id" BIGSERIAL PRIMARY KEY,
  "session_id" varchar(120) NOT NULL,
  "utm_source" varchar(80) DEFAULT NULL,
  "utm_medium" varchar(80) DEFAULT NULL,
  "utm_campaign" varchar(120) DEFAULT NULL,
  "utm_content" varchar(120) DEFAULT NULL,
  "utm_term" varchar(120) DEFAULT NULL,
  "first_seen_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for: webhook_events
CREATE TABLE "webhook_events" (
  "id" BIGSERIAL PRIMARY KEY,
  "gateway" varchar(50) NOT NULL,
  "event_id" varchar(100) NOT NULL,
  "event_type" varchar(100) NOT NULL,
  "payload" TEXT NOT NULL,
  "status" VARCHAR(50) DEFAULT 'pending',
  "processed_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT NULL,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT "uq_webhook_events_uk_whe" UNIQUE ("gateway","event_id")
);

-- Table structure for: wishlist_items
CREATE TABLE "wishlist_items" (
  "id" BIGSERIAL PRIMARY KEY,
  "wishlist_id" BIGINT NOT NULL,
  "product_id" BIGINT NOT NULL,
  "variant_id" BIGINT DEFAULT NULL,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT "uq_wishlist_items_uk_wli" UNIQUE ("wishlist_id","product_id","variant_id")
);

-- Table structure for: wishlists
CREATE TABLE "wishlists" (
  "id" BIGSERIAL PRIMARY KEY,
  "user_id" BIGINT DEFAULT NULL,
  "session_id" varchar(120) DEFAULT NULL,
  "created_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- 2. Seed Data
INSERT INTO "admin_user_roles" VALUES (1,1);
INSERT INTO "admin_users" VALUES (1,'admin','welkarstore@gmail.com','$2y$10$UYsRfclK2vnTHWmryFHrLO3zfZQJzPOLOsu4Gq9xehbRg9YtUHs7q','Welkar Store Admin','owner','active',0,'2026-10-18 20:00:15','2026-10-12 16:57:27','2026-10-18 20:00:15');
INSERT INTO "analytics_events" VALUES (1,'view_item','26af4e70d1ffc055c2193f0de6c17950',NULL,'/product/fossil-townsman-automatic-skeleton','http://localhost:8080/admin/products/7/edit','127.0.0.1','Mozilla/5.0 (Windows NT 10.0;
INSERT INTO "audit_logs" VALUES (1,'system',NULL,'sent_delivery_review_invitation','order','12',NULL,'{\"email\":\"test@buyer.com\",\"order_number\":\"WEL-ORD-20261012-BEA5AB\",\"items_count\":1}','127.0.0.1','2026-10-17 18:43:17'),
(2,'system',NULL,'sent_delivery_review_invitation','order','12',NULL,'{\"email\":\"test@buyer.com\",\"order_number\":\"WEL-ORD-20261012-BEA5AB\",\"items_count\":1}','127.0.0.1','2026-10-17 18:44:08');
INSERT INTO "banners" VALUES (3,'The Midnight Sale','Exceptional Timepieces. Special Offers.','/uploads/banners/banner_1792221466_d6e3a284.jpg','/uploads/banners/banner_1792221466_d6e3a284.jpg','/collections/offers','Shop Offers',1,NULL,NULL,1,'2026-10-17 12:35:44'),
(4,'Make Every Entrance Count','Discover the Women’s Collection','/uploads/banners/hero-banner-2.jpg','/uploads/banners/hero-banner-2.jpg','/collections/womens-watches','Explore Women’s',2,NULL,NULL,1,'2026-10-17 12:35:44'),
(5,'Different Looks. Same Standard.','One Collection. Every Individual.','/uploads/banners/hero-banner-3.jpg','/uploads/banners/hero-banner-3.jpg','/collections/unisex-minimalist','Discover Unisex',3,NULL,NULL,1,'2026-10-17 12:35:44'),
(6,'Precision You Can Feel','Discover the Men’s Collection','/uploads/banners/hero-banner-4.jpg','/uploads/banners/hero-banner-4.jpg','/collections/mens-watches','Explore Men’s',4,NULL,NULL,1,'2026-10-17 12:35:44');
INSERT INTO "brands" VALUES (1,'Welkar','welkar','/assets/images/brands/welkar.png','Welkar Signature Timepieces: Engineered for modern precision and classic Indian elegance.',1,1,'2026-10-12 16:57:27'),
(2,'Titan','titan','/assets/images/brands/titan.png','Iconic craftsmanship with contemporary aesthetics.',1,1,'2026-10-12 16:57:27'),
(3,'Casio','casio','/assets/images/brands/casio.png','Renowned durability and innovative digital-analog performance.',1,1,'2026-10-12 16:57:27'),
(4,'Fossil','fossil','/assets/images/brands/fossil.png','Vintage authentic American style with modern watchmaking.',1,1,'2026-10-12 16:57:27'),
(5,'Timex','timex','/assets/images/brands/timex.png','True heritage watchmaking since 1854.',1,1,'2026-10-12 16:57:27');
INSERT INTO "cart_items" VALUES (41,117,13,1,999.00,'2026-10-15 19:07:30','2026-10-15 19:07:30'),
(43,189,21,1,499.00,'2026-10-17 21:17:58','2026-10-17 21:17:58');
INSERT INTO "carts" VALUES (1,'test_sess_d968447ff2b3',1,NULL,'2026-10-12 17:13:54','2026-10-12 17:13:54'),
(2,'test_sess_c531e99841e8',1,NULL,'2026-10-12 17:14:20','2026-10-12 17:14:20'),
(3,'test_sess_d823fd5d96a3',1,NULL,'2026-10-12 17:14:38','2026-10-12 17:14:38'),
(4,'test_sess_8e602200555c',2,NULL,'2026-10-12 17:14:55','2026-10-12 17:14:55'),
(5,'4fc10b65fb041bb372ee9e07ec2eb7b4',NULL,NULL,'2026-10-12 17:16:20','2026-10-12 17:16:20'),
(6,'6a0928ac98dab4a9446265f49fe2414c',NULL,NULL,'2026-10-12 17:17:17','2026-10-12 17:17:17'),
(7,'test_sess_e0fa8ad4c9f8',3,NULL,'2026-10-12 17:17:32','2026-10-12 17:17:32'),
(8,'54f0347bb82248e119d0fad4c10cac07',NULL,NULL,'2026-10-12 17:19:19','2026-10-12 17:19:19'),
(9,'49f65655df1f7de2255b9bd06dc65b8b',NULL,NULL,'2026-10-12 17:19:26','2026-10-12 17:19:26'),
(10,'26af4e70d1ffc055c2193f0de6c17950',NULL,NULL,'2026-10-12 17:19:37','2026-10-12 17:19:37'),
(11,'bdffc4fc54cb18854c08013c7f999191',NULL,NULL,'2026-10-12 17:37:07','2026-10-12 17:37:07'),
(12,'f9ff580f91f1e97ccf422870546e4f53',NULL,NULL,'2026-10-12 17:37:52','2026-10-12 17:37:52'),
(13,'test_sess_2776af79f06a',4,NULL,'2026-10-12 17:37:59','2026-10-12 17:37:59'),
(14,'70b00db384f677e2d96479bbe1d64411',NULL,NULL,'2026-10-12 17:47:06','2026-10-12 17:47:06'),
(15,'44cfac12d4dc7633eb9f57ff6bb78b60',NULL,NULL,'2026-10-12 17:47:06','2026-10-12 17:47:06'),
(16,'5e2bc315a7511b244686dcedecb9967a',NULL,NULL,'2026-10-12 17:47:06','2026-10-12 17:47:06'),
(17,'test_sess_04975bfe57bf',5,NULL,'2026-10-12 17:47:20','2026-10-12 17:47:20'),
(18,'test_sess_cf5189ac9407',6,NULL,'2026-10-12 17:57:04','2026-10-12 17:57:04'),
(19,'test_sess_22098470b78a',7,NULL,'2026-10-12 18:15:12','2026-10-12 18:15:12'),
(20,'85277fd12563831a28d29ef6c75bcf39',NULL,NULL,'2026-10-12 18:27:28','2026-10-12 18:27:28'),
(21,'test_sess_eeabd59dfbec',8,NULL,'2026-10-12 18:37:33','2026-10-12 18:37:33'),
(22,'test_sess_73c5057550a0',9,NULL,'2026-10-12 18:38:23','2026-10-12 18:38:23'),
(23,'test_sess_8500413779dc',10,NULL,'2026-10-12 18:39:44','2026-10-12 18:39:44'),
(24,'656c7bb8d88ffb864f67cff24e5a8f5d',NULL,NULL,'2026-10-12 18:40:41','2026-10-12 18:40:41'),
(25,'88eb26f1c62d8eaffb82b82edf75f5c3',NULL,NULL,'2026-10-12 19:13:54','2026-10-12 19:13:54'),
(26,'c6df89b4bb22bce6dae68ec2f0ae7185',NULL,NULL,'2026-10-12 19:13:54','2026-10-12 19:13:54'),
(27,'589e02309c1e3d00a55430b0b214f653',NULL,NULL,'2026-10-12 19:13:54','2026-10-12 19:13:54'),
(28,'test_sess_f68010260277',11,NULL,'2026-10-12 19:14:05','2026-10-12 19:14:05'),
(29,'712e9f4c339a699ba9d9e55b1660d9a7',NULL,NULL,'2026-10-12 19:26:33','2026-10-12 19:26:33'),
(30,'test_sess_ef33be22497e',12,NULL,'2026-10-12 19:30:22','2026-10-12 19:30:22'),
(31,'test_sess_2bce4ad1b065',13,NULL,'2026-10-12 19:36:35','2026-10-12 19:36:35'),
(32,'9aa5188142d5e7176891ab806979d839',NULL,NULL,'2026-10-12 19:37:13','2026-10-12 19:37:13'),
(33,'72f61c898a725b4337fa08abc65adf6f',NULL,NULL,'2026-10-12 19:37:27','2026-10-12 19:37:27'),
(34,'922e07cb803b1bdf16f8221f0b2da9d5',NULL,NULL,'2026-10-12 19:37:35','2026-10-12 19:37:35'),
(35,'adfb45c3fa3b881bd3f3cd74afad3d20',NULL,NULL,'2026-10-12 19:37:47','2026-10-12 19:37:47'),
(36,'test_sess_89083a13b84e',14,NULL,'2026-10-12 19:37:53','2026-10-12 19:37:53'),
(37,'8a14ccb1a4d89632166b9d6608cf4e46',NULL,NULL,'2026-10-12 19:43:17','2026-10-12 19:43:17'),
(38,'test_sess_d14c8f45ab74',15,NULL,'2026-10-12 19:43:27','2026-10-12 19:43:27'),
(39,'test_sess_f2f9990ccf39',16,NULL,'2026-10-13 09:24:43','2026-10-13 09:24:43'),
(40,'4a2f97ea84db6284fea992d76c9f0153',NULL,NULL,'2026-10-13 09:25:11','2026-10-13 09:25:11'),
(41,'54a6d197aa3eb656bf069ca70a475ca5',NULL,NULL,'2026-10-13 09:25:44','2026-10-13 09:25:44'),
(42,'988992e5625817fc6214ec0b10538bb1',NULL,NULL,'2026-10-13 09:26:03','2026-10-13 09:26:03'),
(43,'69cc2d622be434a26aae7a8454cfa550',NULL,NULL,'2026-10-13 09:26:10','2026-10-13 09:26:10'),
(44,'a931b0e9afc4bcc60b17028621db202c',NULL,NULL,'2026-10-13 09:26:16','2026-10-13 09:26:16'),
(45,'2704c5a22191e59033cfd33ce10c6b7a',NULL,NULL,'2026-10-13 09:26:34','2026-10-13 09:26:34'),
(46,'test_sess_ac9c4fbc0960',17,NULL,'2026-10-13 09:26:41','2026-10-13 09:26:41'),
(47,'bdcf4d492d38658b4907fa8f700a89f4',NULL,NULL,'2026-10-13 09:26:55','2026-10-13 09:26:55'),
(48,'9c61cc025403a18bdd9bf7b83b500823',NULL,NULL,'2026-10-13 09:26:55','2026-10-13 09:26:55'),
(49,'7fc3e015871ec94fbb95220a83d25403',NULL,NULL,'2026-10-13 09:26:55','2026-10-13 09:26:55'),
(50,'36097a19361662ffb92fa9e55ded469f',NULL,NULL,'2026-10-13 09:27:12','2026-10-13 09:27:12'),
(51,'7612e22ee06517c7a0c4001e337f1fba',NULL,NULL,'2026-10-13 09:39:45','2026-10-13 09:39:45'),
(52,'test_sess_a17e9651d4db',18,NULL,'2026-10-13 09:39:53','2026-10-13 09:39:53'),
(53,'bee0cdbac5460b279abed05604e74531',NULL,NULL,'2026-10-13 09:49:26','2026-10-13 09:49:26'),
(54,'test_sess_a5e534f0038e',19,NULL,'2026-10-13 09:49:34','2026-10-13 09:49:34'),
(55,'test_sess_2a70ab7f97de',20,NULL,'2026-10-13 10:40:12','2026-10-13 10:40:12'),
(56,'test_sess_699bfa5f1cbd',21,NULL,'2026-10-13 10:48:03','2026-10-13 10:48:03'),
(57,'test_sess_38fcfb311b7a',22,NULL,'2026-10-13 10:48:37','2026-10-13 10:48:37'),
(58,'1ba137a5c3f81c1d6ce91df0a740b617',NULL,NULL,'2026-10-13 10:50:54','2026-10-13 10:50:54'),
(59,'test_sess_3d41a304be16',23,NULL,'2026-10-13 10:50:58','2026-10-13 10:50:58'),
(60,'16cebd1b7c3198cca54ce8372c3b639c',NULL,NULL,'2026-10-13 10:51:03','2026-10-13 10:51:03'),
(61,'84610f053f98674ad985c5a03c81a4cd',NULL,NULL,'2026-10-13 10:51:07','2026-10-13 10:51:07'),
(62,'0616eda858d938babd6069915732a6ec',NULL,NULL,'2026-10-13 10:51:11','2026-10-13 10:51:11'),
(63,'223fad5d1543d055415f7681b2b7d83e',NULL,NULL,'2026-10-13 10:51:23','2026-10-13 10:51:23'),
(64,'test_sess_176cbe9f3317',24,NULL,'2026-10-13 10:51:51','2026-10-13 10:51:51'),
(65,'dc1c404b0106ff285e717d215daa05c3',NULL,NULL,'2026-10-13 11:02:44','2026-10-13 11:02:44'),
(66,'d7a1e4b14e981f05c41702607b0b8d72',NULL,NULL,'2026-10-13 11:02:50','2026-10-13 11:02:50'),
(67,'b154d863fd0d01a0b1b73dc2e754d08b',NULL,NULL,'2026-10-13 11:02:50','2026-10-13 11:02:50'),
(68,'b250e898b43c2301e6aff33f6b8b62e6',NULL,NULL,'2026-10-13 11:03:43','2026-10-13 11:03:43'),
(69,'test_sess_16c506732391',25,NULL,'2026-10-13 11:03:48','2026-10-13 11:03:48'),
(70,'a73e6ca42dd332415ec08faf43401212',NULL,NULL,'2026-10-13 11:37:28','2026-10-13 11:37:28'),
(71,'7a624d59d4be8c28e92ca20b1c93df22',NULL,NULL,'2026-10-13 11:37:28','2026-10-13 11:37:28'),
(72,'482b93bf8cc1f3fa0aa98c4157f11b95',NULL,NULL,'2026-10-13 11:37:38','2026-10-13 11:37:38'),
(73,'b005ef5f14ece444253bfab0668de93c',NULL,NULL,'2026-10-13 11:37:46','2026-10-13 11:37:46'),
(74,'test_sess_e3556daa853e',26,NULL,'2026-10-13 11:37:53','2026-10-13 11:37:53'),
(75,'b6752b8bacc3905172afcfaf9b044f76',NULL,NULL,'2026-10-13 11:44:45','2026-10-13 11:44:45'),
(76,'test_sess_7d56349e7af6',27,NULL,'2026-10-13 11:44:52','2026-10-13 11:44:52'),
(77,'79f5b694e148dfd3575ee6c9ac79993e',NULL,NULL,'2026-10-13 11:54:02','2026-10-13 11:54:02'),
(78,'9cd6e5fa117e469a72da0e90bf851477',NULL,NULL,'2026-10-13 11:54:07','2026-10-13 11:54:07'),
(79,'test_sess_4fe164317602',28,NULL,'2026-10-13 11:54:13','2026-10-13 11:54:13'),
(80,'dda96d208d19f0df5e269f68bf77068d',NULL,NULL,'2026-10-13 11:55:51','2026-10-13 11:55:51'),
(81,'fc84af818c385d700dba79221839ea55',NULL,NULL,'2026-10-13 12:00:20','2026-10-13 12:00:20'),
(82,'8111980da9bb07c7beb393299ed15d7b',NULL,NULL,'2026-10-13 12:00:28','2026-10-13 12:00:28'),
(83,'test_sess_4fd0a0ddc895',29,NULL,'2026-10-13 12:00:35','2026-10-13 12:00:35'),
(84,'264f8e4f729cef0a14a52298bab0e9ed',NULL,NULL,'2026-10-13 12:10:27','2026-10-13 12:10:27'),
(85,'2b8e33f6938d565c8772c211a1bb372e',NULL,NULL,'2026-10-13 12:10:32','2026-10-13 12:10:32'),
(86,'8286f2f1126c4422cba7236fb5b6f416',NULL,NULL,'2026-10-13 12:10:39','2026-10-13 12:10:39'),
(87,'test_sess_f2e8a8053751',30,NULL,'2026-10-13 12:10:44','2026-10-13 12:10:44'),
(88,'8c3a4ab2aa5daf2f0d0e21ba2afca9ca',NULL,NULL,'2026-10-13 12:22:19','2026-10-13 12:22:19'),
(89,'test_sess_302b2031c3f3',31,NULL,'2026-10-13 12:22:26','2026-10-13 12:22:26'),
(90,'cd9a9f2d2801e676d53b541b9bb6f586',NULL,NULL,'2026-10-13 12:24:41','2026-10-13 12:24:41'),
(91,'test_sess_1785c633d892',32,NULL,'2026-10-13 12:24:48','2026-10-13 12:24:48'),
(92,'3bc207b5fd684432d31d31fe09d1c208',NULL,NULL,'2026-10-13 12:37:13','2026-10-13 12:37:13'),
(93,'test_sess_9a33099cfb34',33,NULL,'2026-10-13 12:37:22','2026-10-13 12:37:22'),
(94,'test_sess_de907c6b0b88',34,NULL,'2026-10-13 15:09:57','2026-10-13 15:09:57'),
(95,'4cb04f954ffcd17ad60aa4123fe52ce6',NULL,NULL,'2026-10-13 15:11:10','2026-10-13 15:11:10'),
(96,'c5d2be0dababd4d0d9a98434776534ea',NULL,NULL,'2026-10-13 15:11:17','2026-10-13 15:11:17'),
(97,'4ee02d4bdfe04a5f17fa4b2fc577d080',NULL,NULL,'2026-10-13 15:15:23','2026-10-13 15:15:23'),
(98,'16f10885f3c5f516a4dfafb123524e48',NULL,NULL,'2026-10-13 15:15:26','2026-10-13 15:15:26'),
(99,'5a6fb09884b9e920bd1b3e0dedea70d0',NULL,NULL,'2026-10-13 15:16:48','2026-10-13 15:16:48'),
(100,'c952eab6fac7f6089ab5973d0c1bbcb4',NULL,NULL,'2026-10-13 19:08:53','2026-10-13 19:08:53'),
(101,'f2edd2b4c6a2780b70beefd5201fa1d9',NULL,NULL,'2026-10-15 16:19:59','2026-10-15 16:19:59'),
(102,'f5ed5f9f772d05b2d778c5b2e0ed0561',NULL,NULL,'2026-10-15 16:20:28','2026-10-15 16:20:28'),
(103,'d7bd1829a89c7f978580e555c3c1355e',NULL,NULL,'2026-10-15 16:31:17','2026-10-15 16:31:17'),
(104,'e5ee3f8c1b9e0996f466f4ea7ff1cfeb',NULL,NULL,'2026-10-15 16:59:24','2026-10-15 16:59:24'),
(105,'565720f24c3e34e4db6fc3020e40a291',NULL,NULL,'2026-10-15 16:59:31','2026-10-15 16:59:31'),
(106,'5804f2c7c584c0a5ab8fab8b124c8e31',NULL,NULL,'2026-10-15 16:59:37','2026-10-15 16:59:37'),
(107,'083832e7edbea162bfea3fc76c2ca1e9',NULL,NULL,'2026-10-15 16:59:48','2026-10-15 16:59:48'),
(108,'fd996d4c4f915a3695bb441aaa1692df',NULL,NULL,'2026-10-15 16:59:54','2026-10-15 16:59:54'),
(109,'5e020afdd9c2e363afb2873a85ab75c8',NULL,NULL,'2026-10-15 17:00:01','2026-10-15 17:00:01'),
(110,'c9c3d2b5ed5829b10c7136dc60d68a9c',NULL,NULL,'2026-10-15 17:26:50','2026-10-15 17:26:50'),
(111,'14b4ea9e7d94cd8c06986eb8d0d5b7ec',NULL,NULL,'2026-10-15 17:28:06','2026-10-15 17:28:06'),
(112,'1db65f6e96abb614788bace1d2a2152d',NULL,NULL,'2026-10-15 17:28:39','2026-10-15 17:28:39'),
(113,'d2561198835a940ec86231c545f3f7c7',NULL,NULL,'2026-10-15 17:31:42','2026-10-15 17:31:42'),
(114,'613261a116da35f3dfeb46dfe65d07eb',NULL,NULL,'2026-10-15 17:33:15','2026-10-15 17:33:15'),
(115,'9a25e9d5c0cf8265b607ef7f8a6254bb',NULL,NULL,'2026-10-15 17:33:42','2026-10-15 17:33:42'),
(116,'0037da1614767f998500fc226111606c',NULL,NULL,'2026-10-15 18:56:21','2026-10-15 18:56:21'),
(117,'deee85ecf919bd2e1a38ebac30d45c2f',NULL,NULL,'2026-10-15 18:56:53','2026-10-15 18:56:53'),
(118,'b2a186271fe0e973c5d930cea41251bd',NULL,NULL,'2026-10-15 23:19:33','2026-10-15 23:19:33'),
(119,'d616d134c0825ed4caa94bff0748c405',NULL,NULL,'2026-10-15 23:22:26','2026-10-15 23:22:26'),
(120,'0476789d33ed994ccb44c3c91c16995e',NULL,NULL,'2026-10-15 23:43:51','2026-10-15 23:43:51'),
(121,'7b9d1c895418b6e0a6e29804772e8fe5',NULL,NULL,'2026-10-15 23:52:48','2026-10-15 23:52:48'),
(122,'428ea53660ed4bca10fe4377252d1c90',NULL,NULL,'2026-10-15 23:52:59','2026-10-15 23:52:59'),
(123,'3705ea1bc614613dfa4916948572c1c5',NULL,NULL,'2026-10-15 23:53:09','2026-10-15 23:53:09'),
(124,'191b41b8c1a8442a961a2ba06641fc06',NULL,NULL,'2026-10-15 23:53:21','2026-10-15 23:53:21'),
(125,'d7920fd58e9d03b55cfc9c86e9609b4c',NULL,NULL,'2026-10-15 23:53:36','2026-10-15 23:53:36'),
(126,'8d30f944c0bfca5cf862b2986b570e4e',NULL,NULL,'2026-10-15 23:54:01','2026-10-15 23:54:01'),
(127,'f28cdf1f12396b5a4136108cb388a73f',NULL,NULL,'2026-10-15 23:54:11','2026-10-15 23:54:11'),
(128,'b608b36d1f68bfcddcc8fabefb8a9590',NULL,NULL,'2026-10-15 23:55:02','2026-10-15 23:55:02'),
(129,'a80c9f7fb6ea3ce561aef592ed9ae373',NULL,NULL,'2026-10-16 00:09:21','2026-10-16 00:09:21'),
(130,'5a9b4a40e1ee0ef164196a357a3c89bc',NULL,NULL,'2026-10-16 00:13:03','2026-10-16 00:13:03'),
(131,'7c6f2c1273c7c21e28988ce35882710a',NULL,NULL,'2026-10-16 10:19:11','2026-10-16 10:19:11'),
(132,'48ae920124753b9006ec1ad370ac1070',NULL,NULL,'2026-10-16 10:20:05','2026-10-16 10:20:05'),
(133,'885e5a8f837ead0dba062da7c33a9e04',NULL,NULL,'2026-10-16 10:34:16','2026-10-16 10:34:16'),
(134,'6f1bb9a706a55da7dd5d48f1b986dd85',NULL,NULL,'2026-10-16 11:48:49','2026-10-16 11:48:49'),
(135,'666919a8d4d60c01b8f4a8a79010d732',NULL,NULL,'2026-10-16 11:48:49','2026-10-16 11:48:49'),
(136,'dedf9f5069ee1f54dc0da3e957912ff3',NULL,NULL,'2026-10-16 11:49:00','2026-10-16 11:49:00'),
(137,'d324b03f1fd279ff9a1c39c2be4218b6',NULL,NULL,'2026-10-16 11:49:00','2026-10-16 11:49:00'),
(138,'1c1aae5bd72a8b62536b3106498a0b66',NULL,NULL,'2026-10-16 11:49:11','2026-10-16 11:49:11'),
(139,'6a495801c34c4aea8c01fb6a1e6b46b3',NULL,NULL,'2026-10-16 11:49:11','2026-10-16 11:49:11'),
(140,'138d13aedea6426d430ac1297c067a24',NULL,NULL,'2026-10-16 11:49:16','2026-10-16 11:49:16'),
(141,'62b8b5be5f5ff5fa6b629299ed189f24',NULL,NULL,'2026-10-16 11:49:16','2026-10-16 11:49:16'),
(142,'410b30b48b1e43a79f1120ee300048aa',NULL,NULL,'2026-10-16 11:50:46','2026-10-16 11:50:46'),
(143,'9325d61fc724c042df590279ca2b6dc3',NULL,NULL,'2026-10-16 11:51:24','2026-10-16 11:51:24'),
(144,'070b26b3097da9a2f65da4296122838a',NULL,NULL,'2026-10-16 11:51:24','2026-10-16 11:51:24'),
(145,'22fcf8fa758dea952e362f159df61685',NULL,NULL,'2026-10-16 15:48:24','2026-10-16 15:48:24'),
(146,'fcd579cc3b83bbccf1bb87bd94b26bd2',NULL,NULL,'2026-10-16 16:02:19','2026-10-16 16:02:19'),
(147,'3809674101852a888ee8ee7874a6db95',NULL,NULL,'2026-10-16 16:45:44','2026-10-16 16:45:44'),
(148,'d86017f8ffeeb7cb1a005036e2380cf4',NULL,NULL,'2026-10-16 18:14:46','2026-10-16 18:14:46'),
(149,'1743f2fa2285f5eb5d7e15de6cc89893',NULL,NULL,'2026-10-16 19:17:01','2026-10-16 19:17:01'),
(150,'e4902ff882721079bcc23f0c159def41',NULL,NULL,'2026-10-17 10:05:11','2026-10-17 10:05:11'),
(151,'3e074e1b0f5ce5ca63ee8a27b7bb641f',NULL,NULL,'2026-10-17 10:05:42','2026-10-17 10:05:42'),
(152,'33a60a84aa5bd2146610cb541649368b',NULL,NULL,'2026-10-17 10:17:43','2026-10-17 10:17:43'),
(153,'baa1a99f9fe4cb1225c32b6830390a78',NULL,NULL,'2026-10-17 10:17:54','2026-10-17 10:17:54'),
(154,'e8fc5796a734d60926b755d16b6bada1',NULL,NULL,'2026-10-17 10:18:16','2026-10-17 10:18:16'),
(155,'798e9a1d1c12dde4d446a429fb284019',NULL,NULL,'2026-10-17 12:42:52','2026-10-17 12:42:52'),
(156,'9e2e289e7e431a9012e8f548c680e10f',NULL,NULL,'2026-10-17 12:54:24','2026-10-17 12:54:24'),
(157,'9bb5327f04a539cc0d5cfcf4f3ce956f',NULL,NULL,'2026-10-17 13:10:50','2026-10-17 13:10:50'),
(158,'2f4c5016e3b984f000b8a74426addf81',NULL,NULL,'2026-10-17 13:10:56','2026-10-17 13:10:56'),
(159,'ebfab0bb64d82c26802f1f9d17c22ded',NULL,NULL,'2026-10-17 13:11:03','2026-10-17 13:11:03'),
(160,'00c62a7696c8416c829c798c40ed5ea7',NULL,NULL,'2026-10-17 16:09:36','2026-10-17 16:09:36'),
(161,'b0e0dfae9aa31fedc4c7403f4f6905ea',NULL,NULL,'2026-10-17 16:10:22','2026-10-17 16:10:22'),
(162,'ceeeb791c5c8ecd94b630115dd455973',NULL,NULL,'2026-10-17 16:10:44','2026-10-17 16:10:44'),
(163,'6922253dcb59f3fd11382a82974fea11',NULL,NULL,'2026-10-17 16:24:32','2026-10-17 16:24:32'),
(164,'601be02c3212069f5cd5f3cea564516c',NULL,NULL,'2026-10-17 16:24:48','2026-10-17 16:24:48'),
(165,'7c26a9e4d98998fbd22e4ff34d0cb7f1',NULL,NULL,'2026-10-17 16:29:55','2026-10-17 16:29:55'),
(166,'3c9b1d03fe4f9568aa9e5f99601dcdae',NULL,NULL,'2026-10-17 16:46:38','2026-10-17 16:46:38'),
(167,'c586df0a3c7d5f881bc2bd09a18151f9',NULL,NULL,'2026-10-17 16:46:47','2026-10-17 16:46:47'),
(168,'0aaf1bf9e2c331bd9f87119d93312c95',NULL,NULL,'2026-10-17 17:44:28','2026-10-17 17:44:28'),
(169,'2a07fe913ed46156a884978670cf71b0',NULL,NULL,'2026-10-17 17:44:36','2026-10-17 17:44:36'),
(170,'a2498df9bbfb630fbe65de947029c7da',NULL,NULL,'2026-10-17 17:45:46','2026-10-17 17:45:46'),
(171,'2bd3c646b6ee712f12e7c0e3387af1f9',NULL,NULL,'2026-10-17 18:05:56','2026-10-17 18:05:56'),
(172,'69672c6327800bcca9f7cf5ae0fd19a2',NULL,NULL,'2026-10-17 18:06:06','2026-10-17 18:06:06'),
(173,'706d48cfa1019660e2a117abf8074ff3',NULL,NULL,'2026-10-17 18:12:03','2026-10-17 18:12:03'),
(174,'4a966733d8aaa0131e6a81d9bd12338c',NULL,NULL,'2026-10-17 18:44:34','2026-10-17 18:44:34'),
(175,'80b697fbe6b3c47ecdacc83916cc9f6f',NULL,NULL,'2026-10-17 18:44:35','2026-10-17 18:44:35'),
(176,'29ff15cfe42f7adcc186c77f8ecfd12e',NULL,NULL,'2026-10-17 19:27:09','2026-10-17 19:27:09'),
(177,'ced4243e2a49b41d6735a6ee657a6937',NULL,NULL,'2026-10-17 19:27:09','2026-10-17 19:27:09'),
(178,'685aedd8c548e26a356f8ee1692890e3',NULL,NULL,'2026-10-17 19:27:09','2026-10-17 19:27:09'),
(179,'b696924410650e4ad7e5cd059aa91645',NULL,NULL,'2026-10-17 19:27:09','2026-10-17 19:27:09'),
(180,'796562e7fca64344479c2758c5eaa10f',NULL,NULL,'2026-10-17 19:27:10','2026-10-17 19:27:10'),
(181,'1898a2595a95b3964f59200dbfa2ae1a',NULL,NULL,'2026-10-17 19:27:33','2026-10-17 19:27:33'),
(182,'80d2ba4eab14167dc10797c0d065efaf',NULL,NULL,'2026-10-17 19:27:33','2026-10-17 19:27:33'),
(183,'81f65e03e94ad9e11180d87d3d742486',NULL,NULL,'2026-10-17 19:27:33','2026-10-17 19:27:33'),
(184,'c84efde295ae63de15e6c52a662f309c',NULL,NULL,'2026-10-17 19:27:33','2026-10-17 19:27:33'),
(185,'1a76049dc2536f5848e3a86d48b7bfb1',NULL,NULL,'2026-10-17 19:32:51','2026-10-17 19:32:51'),
(186,'3ce8837c9d6dc4fa5c4b1de454b17f75',NULL,NULL,'2026-10-17 19:44:45','2026-10-17 19:44:45'),
(187,'77b984077d23298417e86a9449c7235c',NULL,NULL,'2026-10-17 19:44:46','2026-10-17 19:44:46'),
(188,'2aac03c6c12c2a02c836e4f0dcf9f4da',NULL,NULL,'2026-10-17 19:45:57','2026-10-17 19:45:57'),
(189,'efb107e81dac99b9e50f99351476d8e4',NULL,NULL,'2026-10-17 20:24:50','2026-10-17 20:24:50'),
(190,'3631cb5cb6b28270960b352d4723b12e',NULL,NULL,'2026-10-17 20:24:50','2026-10-17 20:24:50'),
(191,'ed25797e439c71efd260c4ebd18b34ee',NULL,NULL,'2026-10-17 20:24:51','2026-10-17 20:24:51'),
(192,'30d8251722b4c53c3a94c2a4dcc1f1e4',NULL,NULL,'2026-10-17 21:01:15','2026-10-17 21:01:15'),
(193,'ddb90a8372097e97c3f4fba17e334e46',NULL,NULL,'2026-10-17 21:01:22','2026-10-17 21:01:22'),
(194,'1aefcd3720223cd504486e99c1952326',NULL,NULL,'2026-10-17 21:01:23','2026-10-17 21:01:23'),
(195,'4f5eacda40d3e9e30f2a56de2e704c7d',NULL,NULL,'2026-10-17 21:01:23','2026-10-17 21:01:23'),
(196,'14a0c050bf42758ac09dfe4ffc0c518b',NULL,NULL,'2026-10-17 21:01:23','2026-10-17 21:01:23'),
(197,'e9cf2cdc9f46b9510f1be184c0bc7f4b',NULL,NULL,'2026-10-17 21:36:04','2026-10-17 21:36:04'),
(198,'75237029608c867428095cc4daccc2a6',NULL,NULL,'2026-10-17 21:36:04','2026-10-17 21:36:04'),
(199,'1fe61451aa46ad09a5755f1c68492a86',NULL,NULL,'2026-10-17 21:36:23','2026-10-17 21:36:23'),
(200,'efac05a2eda83b725f51ac44ddbae875',NULL,NULL,'2026-10-17 21:36:24','2026-10-17 21:36:24'),
(201,'107d24658ac7d2b7e7c597f847cabdb8',NULL,NULL,'2026-10-18 10:02:15','2026-10-18 10:02:15'),
(202,'4ddcabe5a00f550a88fc1fe0f22c06d7',NULL,NULL,'2026-10-18 11:21:49','2026-10-18 11:21:49'),
(203,'22c63c51fe66d5b8fb8a7124338e9d7c',NULL,NULL,'2026-10-18 11:22:08','2026-10-18 11:22:08'),
(204,'79e61beee30c173c6a38805d80860fb9',NULL,NULL,'2026-10-18 11:40:31','2026-10-18 11:40:31'),
(205,'246935a515c4c8e53b9111c2201b19ff',NULL,NULL,'2026-10-18 11:41:16','2026-10-18 11:41:16'),
(206,'c0bbd1310d57e017de4c54b2d4ecc367',NULL,NULL,'2026-10-18 11:56:16','2026-10-18 11:56:16'),
(207,'11dd4ea8d17d5d2424e24b5dba1078f9',NULL,NULL,'2026-10-18 12:09:55','2026-10-18 12:09:55'),
(208,'2d93ae76b856b54543884543e957e285',NULL,NULL,'2026-10-18 14:29:24','2026-10-18 14:29:24'),
(209,'f975ca8dc9f1bb19f5a75f765d082901',NULL,NULL,'2026-10-18 14:30:45','2026-10-18 14:30:45'),
(210,'c1a6fa940b128167d44926a484db4bb0',NULL,NULL,'2026-10-18 15:05:58','2026-10-18 15:05:58'),
(211,'2c5b084ad45ba45e33fe2ad06b38bd3e',NULL,NULL,'2026-10-18 16:15:34','2026-10-18 16:15:34'),
(212,'1359ef534f9d6905ea8b0cd82fbe08d1',NULL,NULL,'2026-10-18 16:19:20','2026-10-18 16:19:20'),
(213,'ac013e29c84e3e7df612e49007d3ff55',NULL,NULL,'2026-10-18 16:52:01','2026-10-18 16:52:01'),
(214,'4104433eee23459306f2ceb08d86605c',NULL,NULL,'2026-10-18 18:45:37','2026-10-18 18:45:37'),
(215,'3412ad4af4e3d21fa057d18d2c33df0a',NULL,NULL,'2026-10-18 19:12:22','2026-10-18 19:12:22'),
(216,'6fd9fadade3eda5a5b6b6abcd1c22a82',NULL,NULL,'2026-10-18 19:12:30','2026-10-18 19:12:30'),
(217,'9e01831a5d26f743a922597a48f3b69c',NULL,NULL,'2026-10-18 19:12:36','2026-10-18 19:12:36'),
(218,'11b2a29df5711b27687797bd5bbf893b',NULL,NULL,'2026-10-18 19:12:55','2026-10-18 19:12:55'),
(219,'728380c26789fc1d5e62b71497be1cbc',NULL,NULL,'2026-10-18 19:13:03','2026-10-18 19:13:03'),
(220,'921c4a08fad6b756515e35fa4e3a5282',NULL,NULL,'2026-10-18 19:48:02','2026-10-18 19:48:02'),
(221,'88cb6e97d5c18cfdb05611380f55dfec',NULL,NULL,'2026-10-18 19:48:08','2026-10-18 19:48:08'),
(222,'c7984719c011d8b98316085075b4e1c3',NULL,NULL,'2026-10-18 19:59:58','2026-10-18 19:59:58');
INSERT INTO "categories" VALUES (1,NULL,'Men\'s Watches','mens-watches','Distinguished chronographs, automatics, and dress watches crafted for men.','/assets/images/categories/cat-men.jpg',1,1,'2026-10-12 16:57:27'),
(2,NULL,'Women\'s Watches','womens-watches','Elegant, minimalist, and statement timepieces tailored for women.','/assets/images/categories/cat-women.jpg',2,1,'2026-10-12 16:57:27'),
(3,NULL,'Unisex Watches','unisex-watches','Versatile and modern silhouettes suited for any wrist.','/assets/images/categories/cat-unisex.jpg',3,1,'2026-10-12 16:57:27'),
(4,NULL,'Luxury Watches','luxury-watches','Sapphire crystal, mechanical automatic movements, and premium stainless steel.','/assets/images/categories/cat-luxury.jpg',4,1,'2026-10-12 16:57:27'),
(5,NULL,'Sports Watches','sports-watches','Rugged, water-resistant, shockproof watches engineered for endurance.','/assets/images/categories/cat-sports.jpg',5,1,'2026-10-12 16:57:27'),
(6,NULL,'Kids & Youth','kids-and-youth','Shock-resistant and vibrant everyday sports watches engineered for young explorers.','/assets/images/categories/cat-youth.jpg',6,1,'2026-10-13 10:38:42'),
(7,NULL,'Travel & Organizers','travel-organizers','High-frequency welded transparent cosmetic washbags and modular waterproof pouches.','/assets/images/categories/cat-travel.jpg',7,1,'2026-10-13 10:38:42');
INSERT INTO "collections" VALUES (1,'Best Sellers','best-sellers','India\'s most loved watches, backed by verified buyer satisfaction.','/assets/images/collections/best-sellers.jpg',1,'2026-10-12 16:57:27'),
(2,'New Arrivals','new-arrivals','Fresh drops featuring our latest dial designs and premium straps.','/assets/images/collections/new-arrivals.jpg',1,'2026-10-12 16:57:27'),
(3,'Pick Your Perfect Dial','pick-your-perfect-dial','Explore watches categorized by unique dial styles from Roman numerals to Chronographs.','/assets/images/collections/dial-styles.jpg',1,'2026-10-12 16:57:27'),
(4,'Executive Steel','executive-steel','Solid stainless steel mesh and link bracelets for boardroom elegance.','/assets/images/collections/steel.jpg',1,'2026-10-12 16:57:27');
INSERT INTO "company_founders" VALUES (1,'Mr. Sumit Singh','Co-Founder','Artistic Perspective','Mr. Sumit Singh is one of WELKAR\'s founders. His artistic perspective represents the creative side of the founding partnership and the importance of visual character, design expression, and attention to detail in the brand\'s identity.',NULL,'Mr. Sumit Singh, Co-Founder of WELKAR',1,1,'2026-10-15 23:41:17','2026-10-15 23:41:17'),
(2,'Mr. Nevil Sharma','Co-Founder','Technical Perspective','Mr. Nevil Sharma is one of WELKAR\'s founders. His technical perspective represents the problem-solving and technical thinking that complement the brand\'s artistic outlook.',NULL,'Mr. Nevil Sharma, Co-Founder of WELKAR',2,1,'2026-10-15 23:41:17','2026-10-15 23:41:17');
INSERT INTO "company_milestones" VALUES (1,'2002','Founding of WELKAR','Mr. Sumit Singh and Mr. Nevil Sharma establish WELKAR, bringing artistic vision and technical problem-solving together to create a brand founded on precision, heritage, and trust.',NULL,1,1,'2026-10-15 23:41:17');
INSERT INTO "company_profile" VALUES (1,'WELKAR','Precision, Heritage, and Trust—Since 2002.','2002','Precision, Heritage, and Trust—Since 2002.','WELKAR is a watch brand founded in 2002, shaped by an appreciation for design, technical thinking, and the importance of trust. Our story began with two founders bringing complementary perspectives together, united by a vision to build a brand around precision, heritage, and lasting style.','Our Story','WELKAR began in 2002 with the coming together of two complementary perspectives. Mr. Sumit Singh brought an artistic outlook, while Mr. Nevil Sharma brought a technical approach. Together, they established the foundation of a brand shaped by the ideas of precision, heritage, and trust.','Our company story reflects the importance of bringing creative vision and technical thinking together. These principles continue to inform the identity WELKAR seeks to build and the experience it aims to provide its customers.\n\nFrom the outset, our goal has been to create an authentic horological destination where design integrity meets reliable functionality. By combining artistic expression with structured problem-solving, WELKAR continues to offer timepieces designed to complement daily life with poise and dependability.','Our Philosophy','Guided by a genuine passion for watchmaking, our philosophy centers on timeless elegance, mechanical reliability, rigorous attention to detail, and collaboration with skilled craftspeople.','Our Approach to Watches','We believe a watch is more than an instrument for telling time;
INSERT INTO "company_values" VALUES (1,'Precision','Attention to Detail & Accuracy','We prioritize careful attention to product specifications, honest presentation, and transparent documentation across every interaction.','bi-bullseye',1,1,'2026-10-15 23:41:17'),
(2,'Heritage','Respect for our 2002 Origin','Our journey began in 2002. That history forms an essential part of our brand identity, guiding our evolution while honoring our founding roots.','bi-hourglass-split',2,1,'2026-10-15 23:41:17'),
(3,'Trust','Honest Communication & Transparency','We earn customer confidence through truthful product descriptions, clear policies, and dependable after-sales care.','bi-shield-check',3,1,'2026-10-15 23:41:17'),
(4,'Timelessness','Enduring Style Over Passing Fads','We appreciate classic horological aesthetics and create watches designed to gracefully accompany our customers through every season of life.','bi-compass',4,1,'2026-10-15 23:41:17'),
(5,'Responsibility','Fair & Accountable Customer Relations','We commit to communicating responsibly, addressing customer needs with integrity, and treating every patron fairly.','bi-handshake',5,1,'2026-10-15 23:41:17');
INSERT INTO "content_pages" VALUES (1,'about-us','en','About WELKAR — Precision, Heritage, and Trust—Since 2002','<h2>Precision, Heritage, and Trust—Since 2002</h2><p>WELKAR is a watch brand founded in 2002, shaped by an appreciation for design, technical thinking, and the importance of trust. Our story began with two founders—Mr. Sumit Singh and Mr. Nevil Sharma—bringing complementary artistic and technical perspectives together.</p><p><a href=\"/about\" class=\"btn btn-sm btn-outline-dark mt-2\">Read the full interactive company profile &rarr;
INSERT INTO "coupons" VALUES (1,'WELKAR10','10% instant discount on orders above ???1,999','percentage',10.00,1999.00,1000.00,1000,1,0,0,NULL,NULL,1,'2026-10-12 16:57:27'),
(2,'WELCOME500','Flat ???500 off on your first order above ???2,999','fixed',500.00,2999.00,500.00,500,1,0,1,NULL,NULL,1,'2026-10-12 16:57:27');
INSERT INTO "inventory_locations" VALUES (1,'Primary Central Fulfillment Warehouse','MUM-WH-01',1,1);
INSERT INTO "inventory_movements" VALUES (37,5,1,10,60,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-12 19:14:05'),
(38,5,1,-10,50,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-12 19:14:05'),
(39,5,1,-1,49,'Order Fulfilment','order','WEL-ORD-20261012-BEA5AB','storefront_checkout','2026-10-12 19:14:05'),
(40,5,1,10,59,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-12 19:30:22'),
(41,5,1,-10,49,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-12 19:30:22'),
(42,5,1,-1,48,'Order Fulfilment','order','WEL-ORD-20261012-A7B3DB','storefront_checkout','2026-10-12 19:30:22'),
(43,5,1,10,58,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-12 19:36:35'),
(44,5,1,-10,48,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-12 19:36:35'),
(45,5,1,-1,47,'Order Fulfilment','order','WEL-ORD-20261012-4BDA80','storefront_checkout','2026-10-12 19:36:35'),
(46,5,1,10,57,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-12 19:37:53'),
(47,5,1,-10,47,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-12 19:37:53'),
(48,5,1,-1,46,'Order Fulfilment','order','WEL-ORD-20261012-FC6942','storefront_checkout','2026-10-12 19:37:53'),
(49,5,1,10,56,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-12 19:43:27'),
(50,5,1,-10,46,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-12 19:43:27'),
(51,5,1,-1,45,'Order Fulfilment','order','WEL-ORD-20261012-71649A','storefront_checkout','2026-10-12 19:43:27'),
(52,5,1,10,55,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-13 09:24:43'),
(53,5,1,-10,45,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-13 09:24:43'),
(54,5,1,-1,44,'Order Fulfilment','order','WEL-ORD-20261013-904146','storefront_checkout','2026-10-13 09:24:43'),
(55,5,1,10,54,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-13 09:26:41'),
(56,5,1,-10,44,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-13 09:26:41'),
(57,5,1,-1,43,'Order Fulfilment','order','WEL-ORD-20261013-3AB9BF','storefront_checkout','2026-10-13 09:26:41'),
(58,5,1,10,53,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-13 09:39:53'),
(59,5,1,-10,43,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-13 09:39:53'),
(60,5,1,-1,42,'Order Fulfilment','order','WEL-ORD-20261013-17D301','storefront_checkout','2026-10-13 09:39:53'),
(61,5,1,10,52,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-13 09:49:34'),
(62,5,1,-10,42,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-13 09:49:34'),
(63,5,1,-1,41,'Order Fulfilment','order','WEL-ORD-20261013-D3D433','storefront_checkout','2026-10-13 09:49:34'),
(64,5,1,10,51,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-13 10:40:12'),
(65,5,1,-10,41,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-13 10:40:12'),
(66,5,1,-1,40,'Order Fulfilment','order','WEL-ORD-20261013-796AEE','storefront_checkout','2026-10-13 10:40:12'),
(67,5,1,10,50,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-13 10:48:03'),
(68,5,1,-10,40,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-13 10:48:03'),
(69,5,1,-1,39,'Order Fulfilment','order','WEL-ORD-20261013-7B5B7D','storefront_checkout','2026-10-13 10:48:03'),
(70,5,1,10,49,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-13 10:48:37'),
(71,5,1,-10,39,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-13 10:48:37'),
(72,5,1,-1,38,'Order Fulfilment','order','WEL-ORD-20261013-C91290','storefront_checkout','2026-10-13 10:48:37'),
(73,5,1,10,48,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-13 10:50:58'),
(74,5,1,-10,38,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-13 10:50:58'),
(75,5,1,-1,37,'Order Fulfilment','order','WEL-ORD-20261013-32F781','storefront_checkout','2026-10-13 10:50:58'),
(76,5,1,10,47,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-13 10:51:51'),
(77,5,1,-10,37,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-13 10:51:51'),
(78,5,1,-1,36,'Order Fulfilment','order','WEL-ORD-20261013-77B7BB','storefront_checkout','2026-10-13 10:51:51'),
(79,5,1,10,46,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-13 11:03:48'),
(80,5,1,-10,36,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-13 11:03:48'),
(81,5,1,-1,35,'Order Fulfilment','order','WEL-ORD-20261013-BDA138','storefront_checkout','2026-10-13 11:03:48'),
(82,5,1,10,45,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-13 11:37:53'),
(83,5,1,-10,35,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-13 11:37:53'),
(84,5,1,-1,34,'Order Fulfilment','order','WEL-ORD-20261013-46F71C','storefront_checkout','2026-10-13 11:37:53'),
(85,5,1,10,44,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-13 11:44:52'),
(86,5,1,-10,34,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-13 11:44:52'),
(87,5,1,-1,33,'Order Fulfilment','order','WEL-ORD-20261013-2FA804','storefront_checkout','2026-10-13 11:44:52'),
(88,5,1,10,43,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-13 11:54:13'),
(89,5,1,-10,33,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-13 11:54:13'),
(90,5,1,-1,32,'Order Fulfilment','order','WEL-ORD-20261013-6DFA11','storefront_checkout','2026-10-13 11:54:13'),
(91,5,1,10,42,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-13 12:00:35'),
(92,5,1,-10,32,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-13 12:00:35'),
(93,5,1,-1,31,'Order Fulfilment','order','WEL-ORD-20261013-E5DE2A','storefront_checkout','2026-10-13 12:00:35'),
(94,5,1,10,41,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-13 12:10:44'),
(95,5,1,-10,31,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-13 12:10:44'),
(96,5,1,-1,30,'Order Fulfilment','order','WEL-ORD-20261013-B32965','storefront_checkout','2026-10-13 12:10:44'),
(97,5,1,10,40,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-13 12:22:26'),
(98,5,1,-10,30,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-13 12:22:26'),
(99,5,1,-1,29,'Order Fulfilment','order','WEL-ORD-20261013-A3501B','storefront_checkout','2026-10-13 12:22:26'),
(100,5,1,10,39,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-13 12:24:48'),
(101,5,1,-10,29,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-13 12:24:48'),
(102,5,1,-1,28,'Order Fulfilment','order','WEL-ORD-20261013-088404','storefront_checkout','2026-10-13 12:24:48'),
(103,5,1,10,38,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-13 12:37:22'),
(104,5,1,-10,28,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-13 12:37:22'),
(105,5,1,-1,27,'Order Fulfilment','order','WEL-ORD-20261013-E4A721','storefront_checkout','2026-10-13 12:37:22'),
(106,5,1,10,37,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-13 15:09:57'),
(107,5,1,-10,27,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-13 15:09:57'),
(108,5,1,-1,26,'Order Fulfilment','order','WEL-ORD-20261013-25E20E','storefront_checkout','2026-10-13 15:09:57'),
(109,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-15 16:18:52'),
(110,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-15 16:18:52'),
(111,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-15 16:39:36'),
(112,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-15 16:39:36'),
(113,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-15 17:00:09'),
(114,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-15 17:00:09'),
(115,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-15 17:33:49'),
(116,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-15 17:33:49'),
(117,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-15 18:55:32'),
(118,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-15 18:55:32'),
(119,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-15 23:19:11'),
(120,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-15 23:19:11'),
(121,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-15 23:41:28'),
(122,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-15 23:41:28'),
(123,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-15 23:52:16'),
(124,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-15 23:52:16'),
(125,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-15 23:55:38'),
(126,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-15 23:55:38'),
(127,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-16 00:13:10'),
(128,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-16 00:13:10'),
(129,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-16 10:18:26'),
(130,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-16 10:18:26'),
(131,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-16 10:34:23'),
(132,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-16 10:34:23'),
(133,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-16 11:48:11'),
(134,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-16 11:48:11'),
(135,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-16 15:47:52'),
(136,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-16 15:47:52'),
(137,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-16 16:19:46'),
(138,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-16 16:19:46'),
(139,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-16 16:27:12'),
(140,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-16 16:27:12'),
(141,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-16 17:32:23'),
(142,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-16 17:32:23'),
(143,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-16 17:50:20'),
(144,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-16 17:50:20'),
(145,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-16 17:51:11'),
(146,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-16 17:51:11'),
(147,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-16 17:51:44'),
(148,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-16 17:51:44'),
(149,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-16 17:53:12'),
(150,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-16 17:53:12'),
(151,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-16 18:04:43'),
(152,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-16 18:04:43'),
(153,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-16 18:09:30'),
(154,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-16 18:09:30'),
(155,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-16 18:13:10'),
(156,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-16 18:13:10'),
(157,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-16 19:13:19'),
(158,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-16 19:13:19'),
(159,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-16 19:25:50'),
(160,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-16 19:25:50'),
(161,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-16 19:26:57'),
(162,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-16 19:26:57'),
(163,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-16 19:30:11'),
(164,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-16 19:30:11'),
(165,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-16 19:31:29'),
(166,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-16 19:31:29'),
(167,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-17 10:05:05'),
(168,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-17 10:05:05'),
(169,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-17 10:17:31'),
(170,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-17 10:17:31'),
(171,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-17 12:42:30'),
(172,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-17 12:42:30'),
(173,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-17 12:44:35'),
(174,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-17 12:44:35'),
(175,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-17 12:53:46'),
(176,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-17 12:53:46'),
(177,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-17 13:00:24'),
(178,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-17 13:00:24'),
(179,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-17 13:08:51'),
(180,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-17 13:08:51'),
(181,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-17 13:09:52'),
(182,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-17 13:09:52'),
(183,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-17 21:20:27'),
(184,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-17 21:20:27'),
(185,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-17 21:32:10'),
(186,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-17 21:32:10'),
(187,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-17 21:34:56'),
(188,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-17 21:34:56'),
(189,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-18 14:29:04'),
(190,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-18 14:29:04'),
(191,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-18 15:06:04'),
(192,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-18 15:06:04'),
(193,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-18 16:11:01'),
(194,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-18 16:11:01'),
(195,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-18 16:12:13'),
(196,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-18 16:12:13'),
(197,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-18 16:14:26'),
(198,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-18 16:14:26'),
(199,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-18 16:22:31'),
(200,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-18 16:22:31'),
(201,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-18 16:50:39'),
(202,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-18 16:50:39'),
(203,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-18 17:16:57'),
(204,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-18 17:16:57'),
(205,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-18 17:21:35'),
(206,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-18 17:21:35'),
(207,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-18 18:03:01'),
(208,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-18 18:03:01'),
(209,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-18 18:12:50'),
(210,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-18 18:12:50'),
(211,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-18 19:12:05'),
(212,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-18 19:12:05'),
(213,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-18 19:13:12'),
(214,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-18 19:13:12'),
(215,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-18 19:46:20'),
(216,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-18 19:46:20'),
(217,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-18 19:55:12'),
(218,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-18 19:55:12'),
(219,5,1,10,36,'Test Inventory Audit','manual_adjustment',NULL,'test_runner','2026-10-18 20:00:15'),
(220,5,1,-10,26,'Test Inventory Revert','manual_adjustment',NULL,'test_runner','2026-10-18 20:00:15');
INSERT INTO "inventory_stock" VALUES (5,5,1,26,0,8,'2026-10-12 16:57:27','2026-10-18 20:00:15'),
(6,6,1,28,0,5,'2026-10-12 16:57:27','2026-10-12 16:57:27'),
(9,9,1,12,0,3,'2026-10-12 16:57:27','2026-10-12 16:57:27'),
(34,12,1,48,0,5,'2026-10-13 11:03:30','2026-10-13 11:03:30'),
(35,13,1,35,0,5,'2026-10-13 11:03:30','2026-10-13 11:03:30'),
(36,14,1,52,0,5,'2026-10-13 11:03:30','2026-10-13 11:03:30'),
(37,15,1,40,0,5,'2026-10-13 11:03:30','2026-10-13 11:03:30'),
(38,16,1,30,0,5,'2026-10-13 11:03:30','2026-10-13 11:03:30'),
(42,20,1,55,0,5,'2026-10-13 11:03:30','2026-10-13 11:03:30'),
(43,21,1,40,0,5,'2026-10-13 11:03:30','2026-10-13 11:03:30');
INSERT INTO "invoice_sequences" VALUES (1,'2026-27','WEL',1035);
INSERT INTO "invoices" VALUES (1,2,'WEL-2026-27-1002','2026-10-12',NULL,3499.00,314.91,314.91,0.00,4128.82,'2026-10-12 17:14:20'),
(2,3,'WEL-2026-27-1003','2026-10-12',NULL,3499.00,314.91,314.91,0.00,4128.82,'2026-10-12 17:14:55'),
(3,4,'WEL-2026-27-1004','2026-10-12',NULL,3499.00,314.91,314.91,0.00,4128.82,'2026-10-12 17:17:32'),
(4,5,'WEL-2026-27-1005','2026-10-12',NULL,3499.00,314.91,314.91,0.00,4128.82,'2026-10-12 17:37:59'),
(5,6,'WEL-2026-27-1006','2026-10-12',NULL,3499.00,314.91,314.91,0.00,4128.82,'2026-10-12 17:47:20'),
(6,7,'WEL-2026-27-1007','2026-10-12',NULL,3499.00,314.91,314.91,0.00,4128.82,'2026-10-12 17:57:04'),
(7,8,'WEL-2026-27-1008','2026-10-12',NULL,3499.00,314.91,314.91,0.00,4128.82,'2026-10-12 18:15:12'),
(8,9,'WEL-2026-27-1009','2026-10-12',NULL,3499.00,314.91,314.91,0.00,4128.82,'2026-10-12 18:37:33'),
(9,10,'WEL-2026-27-1010','2026-10-12',NULL,3499.00,314.91,314.91,0.00,4128.82,'2026-10-12 18:38:23'),
(10,11,'WEL-2026-27-1011','2026-10-12',NULL,3499.00,314.91,314.91,0.00,4128.82,'2026-10-12 18:39:44'),
(11,12,'WEL-2026-27-1012','2026-10-12',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-12 19:14:05'),
(12,13,'WEL-2026-27-1013','2026-10-12',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-12 19:30:22'),
(13,14,'WEL-2026-27-1014','2026-10-12',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-12 19:36:35'),
(14,15,'WEL-2026-27-1015','2026-10-12',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-12 19:37:53'),
(15,16,'WEL-2026-27-1016','2026-10-12',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-12 19:43:27'),
(16,17,'WEL-2026-27-1017','2026-10-13',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-13 09:24:43'),
(17,18,'WEL-2026-27-1018','2026-10-13',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-13 09:26:41'),
(18,19,'WEL-2026-27-1019','2026-10-13',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-13 09:39:53'),
(19,20,'WEL-2026-27-1020','2026-10-13',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-13 09:49:34'),
(20,21,'WEL-2026-27-1021','2026-10-13',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-13 10:40:12'),
(21,22,'WEL-2026-27-1022','2026-10-13',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-13 10:48:03'),
(22,23,'WEL-2026-27-1023','2026-10-13',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-13 10:48:37'),
(23,24,'WEL-2026-27-1024','2026-10-13',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-13 10:50:58'),
(24,25,'WEL-2026-27-1025','2026-10-13',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-13 10:51:51'),
(25,26,'WEL-2026-27-1026','2026-10-13',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-13 11:03:48'),
(26,27,'WEL-2026-27-1027','2026-10-13',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-13 11:37:53'),
(27,28,'WEL-2026-27-1028','2026-10-13',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-13 11:44:52'),
(28,29,'WEL-2026-27-1029','2026-10-13',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-13 11:54:13'),
(29,30,'WEL-2026-27-1030','2026-10-13',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-13 12:00:35'),
(30,31,'WEL-2026-27-1031','2026-10-13',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-13 12:10:44'),
(31,32,'WEL-2026-27-1032','2026-10-13',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-13 12:22:26'),
(32,33,'WEL-2026-27-1033','2026-10-13',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-13 12:24:48'),
(33,34,'WEL-2026-27-1034','2026-10-13',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-13 12:37:22'),
(34,35,'WEL-2026-27-1035','2026-10-13',NULL,2899.00,260.91,260.91,0.00,3420.82,'2026-10-13 15:09:57');
INSERT INTO "order_addresses" VALUES (2,2,'shipping','Test Buyer','9999988888','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-12 17:14:20'),
(3,3,'shipping','Test Buyer','9999285660','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-12 17:14:55'),
(4,4,'shipping','Test Buyer','9999812649','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-12 17:17:32'),
(5,5,'shipping','Test Buyer','9999275854','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-12 17:37:59'),
(6,6,'shipping','Test Buyer','9999353224','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-12 17:47:20'),
(7,7,'shipping','Test Buyer','9999774483','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-12 17:57:04'),
(8,8,'shipping','Test Buyer','9999301523','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-12 18:15:12'),
(9,9,'shipping','Test Buyer','9999722284','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-12 18:37:33'),
(10,10,'shipping','Test Buyer','9999639282','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-12 18:38:23'),
(11,11,'shipping','Test Buyer','9999477659','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-12 18:39:44'),
(12,12,'shipping','Test Buyer','9999164076','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-12 19:14:05'),
(13,13,'shipping','Test Buyer','9999284334','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-12 19:30:22'),
(14,14,'shipping','Test Buyer','9999985943','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-12 19:36:35'),
(15,15,'shipping','Test Buyer','9999357922','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-12 19:37:53'),
(16,16,'shipping','Test Buyer','9999295716','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-12 19:43:27'),
(17,17,'shipping','Test Buyer','9999150644','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-13 09:24:43'),
(18,18,'shipping','Test Buyer','9999494762','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-13 09:26:41'),
(19,19,'shipping','Test Buyer','9999559018','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-13 09:39:53'),
(20,20,'shipping','Test Buyer','9999568560','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-13 09:49:34'),
(21,21,'shipping','Test Buyer','9999295815','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-13 10:40:12'),
(22,22,'shipping','Test Buyer','9999379252','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-13 10:48:03'),
(23,23,'shipping','Test Buyer','9999752850','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-13 10:48:37'),
(24,24,'shipping','Test Buyer','9999999823','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-13 10:50:58'),
(25,25,'shipping','Test Buyer','9999138211','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-13 10:51:51'),
(26,26,'shipping','Test Buyer','9999474776','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-13 11:03:48'),
(27,27,'shipping','Test Buyer','9999982305','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-13 11:37:53'),
(28,28,'shipping','Test Buyer','9999856096','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-13 11:44:52'),
(29,29,'shipping','Test Buyer','9999168002','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-13 11:54:13'),
(30,30,'shipping','Test Buyer','9999300888','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-13 12:00:35'),
(31,31,'shipping','Test Buyer','9999628748','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-13 12:10:44'),
(32,32,'shipping','Test Buyer','9999931876','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-13 12:22:26'),
(33,33,'shipping','Test Buyer','9999400102','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-13 12:24:48'),
(34,34,'shipping','Test Buyer','9999679620','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-13 12:37:22'),
(35,35,'shipping','Test Buyer','9999364294','Flat 101, Test Residency',NULL,'Mumbai','Maharashtra','27','400001','2026-10-13 15:09:57');
INSERT INTO "order_items" VALUES (2,2,1,1,'Welkar Aura White Silver Steel','Silver Steel / White Dial','WLK-AUR-SLV-WHT',3499.00,1,3499.00,0.00,18.00,629.82,4128.82,'{\"product_id\":1,\"product_name\":\"Welkar Aura White Silver Steel\",\"variant_id\":1,\"variant_name\":\"Silver Steel \\/ White Dial\",\"sku\":\"WLK-AUR-SLV-WHT\",\"dial_color\":\"White\",\"strap_color\":\"Silver\",\"unit_price\":3499,\"quantity\":1}','2026-10-12 17:14:20'),
(3,3,1,1,'Welkar Aura White Silver Steel','Silver Steel / White Dial','WLK-AUR-SLV-WHT',3499.00,1,3499.00,0.00,18.00,629.82,4128.82,'{\"product_id\":1,\"product_name\":\"Welkar Aura White Silver Steel\",\"variant_id\":1,\"variant_name\":\"Silver Steel \\/ White Dial\",\"sku\":\"WLK-AUR-SLV-WHT\",\"dial_color\":\"White\",\"strap_color\":\"Silver\",\"unit_price\":3499,\"quantity\":1}','2026-10-12 17:14:55'),
(4,4,1,1,'Welkar Aura White Silver Steel','Silver Steel / White Dial','WLK-AUR-SLV-WHT',3499.00,1,3499.00,0.00,18.00,629.82,4128.82,'{\"product_id\":1,\"product_name\":\"Welkar Aura White Silver Steel\",\"variant_id\":1,\"variant_name\":\"Silver Steel \\/ White Dial\",\"sku\":\"WLK-AUR-SLV-WHT\",\"dial_color\":\"White\",\"strap_color\":\"Silver\",\"unit_price\":3499,\"quantity\":1}','2026-10-12 17:17:32'),
(5,5,1,1,'Welkar Aura White Silver Steel','Silver Steel / White Dial','WLK-AUR-SLV-WHT',3499.00,1,3499.00,0.00,18.00,629.82,4128.82,'{\"product_id\":1,\"product_name\":\"Welkar Aura White Silver Steel\",\"variant_id\":1,\"variant_name\":\"Silver Steel \\/ White Dial\",\"sku\":\"WLK-AUR-SLV-WHT\",\"dial_color\":\"White\",\"strap_color\":\"Silver\",\"unit_price\":3499,\"quantity\":1}','2026-10-12 17:37:59'),
(6,6,1,1,'Welkar Aura White Silver Steel','Silver Steel / White Dial','WLK-AUR-SLV-WHT',3499.00,1,3499.00,0.00,18.00,629.82,4128.82,'{\"product_id\":1,\"product_name\":\"Welkar Aura White Silver Steel\",\"variant_id\":1,\"variant_name\":\"Silver Steel \\/ White Dial\",\"sku\":\"WLK-AUR-SLV-WHT\",\"dial_color\":\"White\",\"strap_color\":\"Silver\",\"unit_price\":3499,\"quantity\":1}','2026-10-12 17:47:20'),
(7,7,1,1,'Welkar Aura White Silver Steel','Silver Steel / White Dial','WLK-AUR-SLV-WHT',3499.00,1,3499.00,0.00,18.00,629.82,4128.82,'{\"product_id\":1,\"product_name\":\"Welkar Aura White Silver Steel\",\"variant_id\":1,\"variant_name\":\"Silver Steel \\/ White Dial\",\"sku\":\"WLK-AUR-SLV-WHT\",\"dial_color\":\"White\",\"strap_color\":\"Silver\",\"unit_price\":3499,\"quantity\":1}','2026-10-12 17:57:04'),
(8,8,1,1,'Welkar Aura White Silver Steel','Silver Steel / White Dial','WLK-AUR-SLV-WHT',3499.00,1,3499.00,0.00,18.00,629.82,4128.82,'{\"product_id\":1,\"product_name\":\"Welkar Aura White Silver Steel\",\"variant_id\":1,\"variant_name\":\"Silver Steel \\/ White Dial\",\"sku\":\"WLK-AUR-SLV-WHT\",\"dial_color\":\"White\",\"strap_color\":\"Silver\",\"unit_price\":3499,\"quantity\":1}','2026-10-12 18:15:12'),
(9,9,1,1,'Welkar Aura White Silver Steel','Silver Steel / White Dial','WLK-AUR-SLV-WHT',3499.00,1,3499.00,0.00,18.00,629.82,4128.82,'{\"product_id\":1,\"product_name\":\"Welkar Aura White Silver Steel\",\"variant_id\":1,\"variant_name\":\"Silver Steel \\/ White Dial\",\"sku\":\"WLK-AUR-SLV-WHT\",\"dial_color\":\"White\",\"strap_color\":\"Silver\",\"unit_price\":3499,\"quantity\":1}','2026-10-12 18:37:33'),
(10,10,1,1,'Welkar Aura White Silver Steel','Silver Steel / White Dial','WLK-AUR-SLV-WHT',3499.00,1,3499.00,0.00,18.00,629.82,4128.82,'{\"product_id\":1,\"product_name\":\"Welkar Aura White Silver Steel\",\"variant_id\":1,\"variant_name\":\"Silver Steel \\/ White Dial\",\"sku\":\"WLK-AUR-SLV-WHT\",\"dial_color\":\"White\",\"strap_color\":\"Silver\",\"unit_price\":3499,\"quantity\":1}','2026-10-12 18:38:23'),
(11,11,1,1,'Welkar Aura White Silver Steel','Silver Steel / White Dial','WLK-AUR-SLV-WHT',3499.00,1,3499.00,0.00,18.00,629.82,4128.82,'{\"product_id\":1,\"product_name\":\"Welkar Aura White Silver Steel\",\"variant_id\":1,\"variant_name\":\"Silver Steel \\/ White Dial\",\"sku\":\"WLK-AUR-SLV-WHT\",\"dial_color\":\"White\",\"strap_color\":\"Silver\",\"unit_price\":3499,\"quantity\":1}','2026-10-12 18:39:44'),
(12,12,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-12 19:14:05'),
(13,13,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-12 19:30:22'),
(14,14,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-12 19:36:35'),
(15,15,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-12 19:37:53'),
(16,16,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-12 19:43:27'),
(17,17,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-13 09:24:43'),
(18,18,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-13 09:26:41'),
(19,19,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-13 09:39:53'),
(20,20,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-13 09:49:34'),
(21,21,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-13 10:40:12'),
(22,22,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-13 10:48:03'),
(23,23,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-13 10:48:37'),
(24,24,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-13 10:50:58'),
(25,25,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-13 10:51:51'),
(26,26,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-13 11:03:48'),
(27,27,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-13 11:37:53'),
(28,28,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-13 11:44:52'),
(29,29,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-13 11:54:13'),
(30,30,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-13 12:00:35'),
(31,31,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-13 12:10:44'),
(32,32,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-13 12:22:26'),
(33,33,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-13 12:24:48'),
(34,34,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-13 12:37:22'),
(35,35,3,5,'Welkar Royale Classic Roman Gold','Champagne Gold / Brown Leather','WLK-ROY-GLD-BRN',2899.00,1,2899.00,0.00,18.00,521.82,3420.82,'{\"product_id\":3,\"product_name\":\"Welkar Royale Classic Roman Gold\",\"variant_id\":5,\"variant_name\":\"Champagne Gold \\/ Brown Leather\",\"sku\":\"WLK-ROY-GLD-BRN\",\"dial_color\":\"Champagne\",\"strap_color\":\"Brown\",\"unit_price\":2899,\"quantity\":1}','2026-10-13 15:09:57');
INSERT INTO "order_status_history" VALUES (2,2,NULL,'pending','Order placed by customer','customer','2026-10-12 17:14:20'),
(3,3,NULL,'pending','Order placed by customer','customer','2026-10-12 17:14:55'),
(4,4,NULL,'pending','Order placed by customer','customer','2026-10-12 17:17:32'),
(5,5,NULL,'pending','Order placed by customer','customer','2026-10-12 17:37:59'),
(6,6,NULL,'pending','Order placed by customer','customer','2026-10-12 17:47:20'),
(7,7,NULL,'pending','Order placed by customer','customer','2026-10-12 17:57:04'),
(8,8,NULL,'pending','Order placed by customer','customer','2026-10-12 18:15:12'),
(9,9,NULL,'pending','Order placed by customer','customer','2026-10-12 18:37:33'),
(10,10,NULL,'pending','Order placed by customer','customer','2026-10-12 18:38:23'),
(11,11,NULL,'pending','Order placed by customer','customer','2026-10-12 18:39:44'),
(12,12,NULL,'pending','Order placed by customer','customer','2026-10-12 19:14:05'),
(13,13,NULL,'pending','Order placed by customer','customer','2026-10-12 19:30:22'),
(14,14,NULL,'pending','Order placed by customer','customer','2026-10-12 19:36:35'),
(15,15,NULL,'pending','Order placed by customer','customer','2026-10-12 19:37:53'),
(16,16,NULL,'pending','Order placed by customer','customer','2026-10-12 19:43:27'),
(17,17,NULL,'pending','Order placed by customer','customer','2026-10-13 09:24:43'),
(18,18,NULL,'pending','Order placed by customer','customer','2026-10-13 09:26:41'),
(19,19,NULL,'pending','Order placed by customer','customer','2026-10-13 09:39:53'),
(20,20,NULL,'pending','Order placed by customer','customer','2026-10-13 09:49:34'),
(21,21,NULL,'pending','Order placed by customer','customer','2026-10-13 10:40:12'),
(22,22,NULL,'pending','Order placed by customer','customer','2026-10-13 10:48:03'),
(23,23,NULL,'pending','Order placed by customer','customer','2026-10-13 10:48:37'),
(24,24,NULL,'pending','Order placed by customer','customer','2026-10-13 10:50:58'),
(25,25,NULL,'pending','Order placed by customer','customer','2026-10-13 10:51:51'),
(26,26,NULL,'pending','Order placed by customer','customer','2026-10-13 11:03:48'),
(27,27,NULL,'pending','Order placed by customer','customer','2026-10-13 11:37:53'),
(28,28,NULL,'pending','Order placed by customer','customer','2026-10-13 11:44:52'),
(29,29,NULL,'pending','Order placed by customer','customer','2026-10-13 11:54:13'),
(30,30,NULL,'pending','Order placed by customer','customer','2026-10-13 12:00:35'),
(31,31,NULL,'pending','Order placed by customer','customer','2026-10-13 12:10:44'),
(32,32,NULL,'pending','Order placed by customer','customer','2026-10-13 12:22:26'),
(33,33,NULL,'pending','Order placed by customer','customer','2026-10-13 12:24:48'),
(34,34,NULL,'pending','Order placed by customer','customer','2026-10-13 12:37:22'),
(35,35,NULL,'pending','Order placed by customer','customer','2026-10-13 15:09:57'),
(36,12,'shipped','delivered','Order delivered safely to doorstep','admin','2026-10-17 18:43:15'),
(37,12,'shipped','delivered','Order delivered safely to doorstep','admin','2026-10-17 18:44:06');
INSERT INTO "orders" VALUES (2,'WEL-ORD-20261012-DADFB1',1,'test@buyer.com','9999988888','pending','unpaid','unfulfilled','INR',3499.00,0.00,0.00,629.82,4128.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-12 17:14:20','2026-10-12 17:14:20'),
(3,'WEL-ORD-20261012-C2D4C8',2,'test@buyer.com','9999285660','pending','unpaid','unfulfilled','INR',3499.00,0.00,0.00,629.82,4128.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-12 17:14:55','2026-10-12 17:14:55'),
(4,'WEL-ORD-20261012-801EA2',3,'test@buyer.com','9999812649','pending','unpaid','unfulfilled','INR',3499.00,0.00,0.00,629.82,4128.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-12 17:17:32','2026-10-12 17:17:32'),
(5,'WEL-ORD-20261012-EBC16C',4,'test@buyer.com','9999275854','pending','unpaid','unfulfilled','INR',3499.00,0.00,0.00,629.82,4128.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-12 17:37:59','2026-10-12 17:37:59'),
(6,'WEL-ORD-20261012-5EF8F7',5,'test@buyer.com','9999353224','pending','unpaid','unfulfilled','INR',3499.00,0.00,0.00,629.82,4128.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-12 17:47:20','2026-10-12 17:47:20'),
(7,'WEL-ORD-20261012-68DE02',6,'test@buyer.com','9999774483','pending','unpaid','unfulfilled','INR',3499.00,0.00,0.00,629.82,4128.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-12 17:57:04','2026-10-12 17:57:04'),
(8,'WEL-ORD-20261012-AC96B2',7,'test@buyer.com','9999301523','pending','unpaid','unfulfilled','INR',3499.00,0.00,0.00,629.82,4128.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-12 18:15:12','2026-10-12 18:15:12'),
(9,'WEL-ORD-20261012-F2D688',8,'test@buyer.com','9999722284','pending','unpaid','unfulfilled','INR',3499.00,0.00,0.00,629.82,4128.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-12 18:37:33','2026-10-12 18:37:33'),
(10,'WEL-ORD-20261012-40E075',9,'test@buyer.com','9999639282','pending','unpaid','unfulfilled','INR',3499.00,0.00,0.00,629.82,4128.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-12 18:38:23','2026-10-12 18:38:23'),
(11,'WEL-ORD-20261012-1AB934',10,'test@buyer.com','9999477659','pending','unpaid','unfulfilled','INR',3499.00,0.00,0.00,629.82,4128.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-12 18:39:44','2026-10-12 18:39:44'),
(12,'WEL-ORD-20261012-BEA5AB',11,'test@buyer.com','9999164076','delivered','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-12 19:14:05','2026-10-17 18:44:06'),
(13,'WEL-ORD-20261012-A7B3DB',12,'test@buyer.com','9999284334','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-12 19:30:22','2026-10-12 19:30:22'),
(14,'WEL-ORD-20261012-4BDA80',13,'test@buyer.com','9999985943','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-12 19:36:35','2026-10-12 19:36:35'),
(15,'WEL-ORD-20261012-FC6942',14,'test@buyer.com','9999357922','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-12 19:37:53','2026-10-12 19:37:53'),
(16,'WEL-ORD-20261012-71649A',15,'test@buyer.com','9999295716','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-12 19:43:27','2026-10-12 19:43:27'),
(17,'WEL-ORD-20261013-904146',16,'test@buyer.com','9999150644','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-13 09:24:43','2026-10-13 09:24:43'),
(18,'WEL-ORD-20261013-3AB9BF',17,'test@buyer.com','9999494762','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-13 09:26:41','2026-10-13 09:26:41'),
(19,'WEL-ORD-20261013-17D301',18,'test@buyer.com','9999559018','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-13 09:39:53','2026-10-13 09:39:53'),
(20,'WEL-ORD-20261013-D3D433',19,'test@buyer.com','9999568560','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-13 09:49:34','2026-10-13 09:49:34'),
(21,'WEL-ORD-20261013-796AEE',20,'test@buyer.com','9999295815','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-13 10:40:12','2026-10-13 10:40:12'),
(22,'WEL-ORD-20261013-7B5B7D',21,'test@buyer.com','9999379252','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-13 10:48:03','2026-10-13 10:48:03'),
(23,'WEL-ORD-20261013-C91290',22,'test@buyer.com','9999752850','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-13 10:48:37','2026-10-13 10:48:37'),
(24,'WEL-ORD-20261013-32F781',23,'test@buyer.com','9999999823','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-13 10:50:58','2026-10-13 10:50:58'),
(25,'WEL-ORD-20261013-77B7BB',24,'test@buyer.com','9999138211','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-13 10:51:51','2026-10-13 10:51:51'),
(26,'WEL-ORD-20261013-BDA138',25,'test@buyer.com','9999474776','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-13 11:03:48','2026-10-13 11:03:48'),
(27,'WEL-ORD-20261013-46F71C',26,'test@buyer.com','9999982305','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-13 11:37:53','2026-10-13 11:37:53'),
(28,'WEL-ORD-20261013-2FA804',27,'test@buyer.com','9999856096','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-13 11:44:52','2026-10-13 11:44:52'),
(29,'WEL-ORD-20261013-6DFA11',28,'test@buyer.com','9999168002','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-13 11:54:13','2026-10-13 11:54:13'),
(30,'WEL-ORD-20261013-E5DE2A',29,'test@buyer.com','9999300888','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-13 12:00:35','2026-10-13 12:00:35'),
(31,'WEL-ORD-20261013-B32965',30,'test@buyer.com','9999628748','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-13 12:10:44','2026-10-13 12:10:44'),
(32,'WEL-ORD-20261013-A3501B',31,'test@buyer.com','9999931876','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-13 12:22:26','2026-10-13 12:22:26'),
(33,'WEL-ORD-20261013-088404',32,'test@buyer.com','9999400102','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-13 12:24:48','2026-10-13 12:24:48'),
(34,'WEL-ORD-20261013-E4A721',33,'test@buyer.com','9999679620','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-13 12:37:22','2026-10-13 12:37:22'),
(35,'WEL-ORD-20261013-25E20E',34,'test@buyer.com','9999364294','pending','unpaid','unfulfilled','INR',2899.00,0.00,0.00,521.82,3420.82,'cod',NULL,NULL,'Maharashtra',0,NULL,NULL,'2026-10-13 15:09:57','2026-10-13 15:09:57');
INSERT INTO "otp_challenges" VALUES (1,'8511115597','429478','login',0,5,'2026-10-15 16:51:00',NULL,'2026-10-15 16:41:00'),
(2,'8511115597','636249','login',0,5,'2026-10-15 16:51:38',NULL,'2026-10-15 16:41:38'),
(3,'8511115597','560709','login',0,5,'2026-10-15 16:51:57',NULL,'2026-10-15 16:41:57');
INSERT INTO "payments" VALUES (2,2,'razorpay',NULL,'order_8d21419fa6fe71',1499.00,'INR','pending',NULL,NULL,'2026-10-17 21:22:40','2026-10-17 21:22:40'),
(3,2,'razorpay',NULL,'order_7cceb6cbc95083',1899.00,'INR','pending',NULL,NULL,'2026-10-17 21:34:56','2026-10-17 21:34:56'),
(4,2,'razorpay',NULL,'order_9cbda494b262a0',1899.00,'INR','pending',NULL,NULL,'2026-10-18 14:29:05','2026-10-18 14:29:05'),
(5,2,'razorpay',NULL,'order_388ec8da183a0a',1899.00,'INR','pending',NULL,NULL,'2026-10-18 15:06:04','2026-10-18 15:06:04'),
(6,2,'razorpay',NULL,'order_735f0359795dbe',1899.00,'INR','pending','razorpay',NULL,'2026-10-18 16:11:01','2026-10-18 16:11:01'),
(7,2,'razorpay',NULL,'order_b3e1fffb923649',1899.00,'INR','pending','razorpay',NULL,'2026-10-18 16:12:13','2026-10-18 16:12:13'),
(8,2,'razorpay',NULL,'order_32c46ae83d7ceb',1899.00,'INR','pending','razorpay',NULL,'2026-10-18 16:14:26','2026-10-18 16:14:26'),
(9,2,'razorpay',NULL,'order_3f6d87d1042232',1899.00,'INR','pending','razorpay',NULL,'2026-10-18 16:22:31','2026-10-18 16:22:31'),
(10,2,'razorpay',NULL,'order_e78d4c60185f08',1899.00,'INR','pending','razorpay',NULL,'2026-10-18 16:50:39','2026-10-18 16:50:39'),
(11,2,'razorpay',NULL,'order_aa6bc62cb3751f',1899.00,'INR','pending','razorpay',NULL,'2026-10-18 17:16:57','2026-10-18 17:16:57'),
(12,2,'razorpay',NULL,'order_764b25449a52d9',1899.00,'INR','pending','razorpay',NULL,'2026-10-18 17:21:35','2026-10-18 17:21:35'),
(13,2,'razorpay',NULL,'order_6915d1b22459ac',1899.00,'INR','pending','razorpay',NULL,'2026-10-18 18:03:01','2026-10-18 18:03:01'),
(14,2,'razorpay',NULL,'order_c9f0e80f943685',1899.00,'INR','pending','razorpay',NULL,'2026-10-18 18:12:50','2026-10-18 18:12:50'),
(15,2,'razorpay',NULL,'order_adf29384aa1f13',1899.00,'INR','pending','razorpay',NULL,'2026-10-18 19:12:05','2026-10-18 19:12:05'),
(16,2,'razorpay',NULL,'order_edc27185185ce1',1899.00,'INR','pending','razorpay',NULL,'2026-10-18 19:13:12','2026-10-18 19:13:12'),
(17,2,'razorpay',NULL,'order_ad89935f4e4565',1899.00,'INR','pending','razorpay',NULL,'2026-10-18 19:46:20','2026-10-18 19:46:20'),
(18,2,'razorpay',NULL,'order_b5b1ba368033a9',1899.00,'INR','pending','razorpay',NULL,'2026-10-18 19:55:12','2026-10-18 19:55:12'),
(19,2,'razorpay',NULL,'order_8022c3c8165f98',1899.00,'INR','pending','razorpay',NULL,'2026-10-18 20:00:15','2026-10-18 20:00:15');
INSERT INTO "permissions" VALUES (1,'View Dashboard','dashboard.view','Dashboard','View executive KPIs and operational summaries','2026-10-12 16:57:27'),
(2,'View Orders','orders.view','Orders','View customer orders and status details','2026-10-12 16:57:27'),
(3,'Update Order Status','orders.update_status','Orders','Advance or modify order status and tracking','2026-10-12 16:57:27'),
(4,'Cancel Orders','orders.cancel','Orders','Cancel eligible customer orders','2026-10-12 16:57:27'),
(5,'Request Refunds','refunds.request','Orders','Initiate payment refund flow','2026-10-12 16:57:27'),
(6,'Approve Refunds','refunds.approve','Orders','Authorize and finalize payment refunds','2026-10-12 16:57:27'),
(7,'Create Products','products.create','Catalog','Add new watch models and variants','2026-10-12 16:57:27'),
(8,'Update Products','products.update','Catalog','Edit product details, prices and assets','2026-10-12 16:57:27'),
(9,'Archive Products','products.archive','Catalog','Unpublish or archive products','2026-10-12 16:57:27'),
(10,'View Inventory','inventory.view','Inventory','View stock levels across warehouses','2026-10-12 16:57:27'),
(11,'Adjust Inventory','inventory.adjust','Inventory','Perform manual stock adjustments','2026-10-12 16:57:27'),
(12,'Run Bulk Imports','imports.run','Catalog','Upload and process bulk product CSV files','2026-10-12 16:57:27'),
(13,'View Customers','customers.view','Customers','View customer CRM profiles and orders','2026-10-12 16:57:27'),
(14,'Export Customer Data','customers.export','Customers','Download authorized customer lists','2026-10-12 16:57:27'),
(15,'Manage Coupons','coupons.manage','Promotions','Create and toggle discount coupon codes','2026-10-12 16:57:27'),
(16,'Manage Campaigns','campaigns.manage','Promotions','Configure promotional discount campaigns','2026-10-12 16:57:27'),
(17,'Manage Banners','banners.manage','Promotions','Update homepage hero and promotional banners','2026-10-12 16:57:27'),
(18,'View Analytics','analytics.view','Analytics','Access first-party traffic and sales analytics','2026-10-12 16:57:27'),
(19,'Export Analytics','analytics.export','Analytics','Export analytics reports as CSV','2026-10-12 16:57:27'),
(20,'Manage Ad Integrations','ads_integrations.manage','Marketing','Configure Meta, Google Ads and Pixel tracking','2026-10-12 16:57:27'),
(21,'Manage Settings','settings.manage','Settings','Update business, GST, invoice and payment configurations','2026-10-12 16:57:27'),
(22,'Manage Staff Roles','users.manage_roles','Settings','Create staff accounts and assign permissions','2026-10-12 16:57:27'),
(23,'View Audit Logs','audit_logs.view','Settings','Inspect administrative action audit trail','2026-10-12 16:57:27');
INSERT INTO "product_categories" VALUES (3,1),
(4,1),
(7,1),
(12,1),
(13,1),
(14,1),
(15,1),
(16,1),
(7,4),
(14,4),
(15,4),
(12,5),
(13,5),
(16,5),
(20,5),
(21,5),
(20,6);
INSERT INTO "product_collections" VALUES (3,1),
(4,1),
(3,3),
(7,4);
INSERT INTO "product_comparison_rows" VALUES (1,'Case Diameter',1,1),
(2,'Movement Caliber',2,1),
(3,'Dial Glass',3,1),
(4,'Water Resistance',4,1),
(5,'Case & Strap Material',5,1),
(6,'Atelier Warranty',6,1);
INSERT INTO "product_comparison_values" VALUES (1,3,1,'42mm Standard'),
(2,3,2,'Japanese Quartz (+/-15s/mo)'),
(3,3,3,'Hardened Mineral Crystal'),
(4,3,4,'30M / 3 ATM (Hydro-Tested)'),
(5,3,5,'316L Surgical Stainless Steel'),
(6,3,6,'1 Year Mechanism Warranty'),
(7,4,1,'42mm Standard'),
(8,4,2,'Japanese Quartz (+/-15s/mo)'),
(9,4,3,'Hardened Mineral Crystal'),
(10,4,4,'30M / 3 ATM (Hydro-Tested)'),
(11,4,5,'316L Surgical Stainless Steel'),
(12,4,6,'1 Year Mechanism Warranty'),
(13,7,1,'42mm Standard'),
(14,7,2,'Japanese Quartz (+/-15s/mo)'),
(15,7,3,'Hardened Mineral Crystal'),
(16,7,4,'30M / 3 ATM (Hydro-Tested)'),
(17,7,5,'316L Surgical Stainless Steel'),
(18,7,6,'1 Year Mechanism Warranty'),
(19,12,1,'42mm Standard'),
(20,12,2,'Japanese Quartz (+/-15s/mo)'),
(21,12,3,'Hardened Mineral Crystal'),
(22,12,4,'30M / 3 ATM (Hydro-Tested)'),
(23,12,5,'316L Surgical Stainless Steel'),
(24,12,6,'1 Year Mechanism Warranty'),
(25,13,1,'42mm Standard'),
(26,13,2,'Japanese Quartz (+/-15s/mo)'),
(27,13,3,'Hardened Mineral Crystal'),
(28,13,4,'30M / 3 ATM (Hydro-Tested)'),
(29,13,5,'316L Surgical Stainless Steel'),
(30,13,6,'1 Year Mechanism Warranty'),
(31,14,1,'42mm Standard'),
(32,14,2,'Japanese Quartz (+/-15s/mo)'),
(33,14,3,'Hardened Mineral Crystal'),
(34,14,4,'30M / 3 ATM (Hydro-Tested)'),
(35,14,5,'316L Surgical Stainless Steel'),
(36,14,6,'1 Year Mechanism Warranty'),
(37,15,1,'42mm Standard'),
(38,15,2,'Japanese Quartz (+/-15s/mo)'),
(39,15,3,'Hardened Mineral Crystal'),
(40,15,4,'30M / 3 ATM (Hydro-Tested)'),
(41,15,5,'316L Surgical Stainless Steel'),
(42,15,6,'1 Year Mechanism Warranty'),
(43,16,1,'42mm Standard'),
(44,16,2,'Japanese Quartz (+/-15s/mo)'),
(45,16,3,'Hardened Mineral Crystal'),
(46,16,4,'30M / 3 ATM (Hydro-Tested)'),
(47,16,5,'316L Surgical Stainless Steel'),
(48,16,6,'1 Year Mechanism Warranty'),
(49,20,1,'42mm Standard'),
(50,20,2,'Japanese Quartz (+/-15s/mo)'),
(51,20,3,'Hardened Mineral Crystal'),
(52,20,4,'30M / 3 ATM (Hydro-Tested)'),
(53,20,5,'316L Surgical Stainless Steel'),
(54,20,6,'1 Year Mechanism Warranty'),
(55,21,1,'42mm Standard'),
(56,21,2,'Japanese Quartz (+/-15s/mo)'),
(57,21,3,'Hardened Mineral Crystal'),
(58,21,4,'30M / 3 ATM (Hydro-Tested)'),
(59,21,5,'316L Surgical Stainless Steel'),
(60,21,6,'1 Year Mechanism Warranty');
INSERT INTO "product_faqs" VALUES (41,3,'What movement powers the Welkar Royale Classic?','It is powered by a high-precision Japanese Quartz caliber offering +/-15 seconds per month accuracy and an authentic SR626SW silver-oxide battery with 2 to 3 years lifespan.',1,1),
(42,3,'How should I care for the genuine leather strap?','To preserve the rich brown finish, avoid saturating the leather in water and allow it to air-dry naturally away from direct heat if exposed to moisture.',2,1),
(43,3,'What is the case size and wrist fit for this model?','It features a refined 39mm case diameter and an ultra-slim profile, designed to fit comfortably under dress cuffs for both men and unisex styling on wrists from 5.5 to 7.5 inches.',3,1),
(44,3,'Is this watch water-resistant?','Yes, it is rated at 30M (3 ATM) with dual silicone gasket seals, protecting against rain, hand-washing, and accidental splashes. It is not intended for swimming.',4,1),
(45,4,'Can I wear the Titan Neo Splash Sport Chrono while swimming?','Yes. With a 50M (5 ATM) water resistance rating and a navy blue silicone strap, it is tested safe for shallow recreational surface swimming and showers. Do not press chrono pushers underwater.',1,1),
(46,4,'What functions do the chronograph sub-dials provide?','The multi-function dial features precision 1/10th second stopwatch timing, split lap measurement, and a 24-hour time indicator.',2,1),
(47,4,'How durable is the silicone strap during sports?','The sweat-resistant, hypoallergenic silicone rubber band is engineered for active movement, gym workouts, and humid Indian weather.',3,1),
(48,4,'What warranty coverage is included?','It includes a 2-Year official movement and manufacturing defect warranty supported nationwide.',4,1),
(49,7,'Does the Fossil Townsman Automatic require a battery replacement?','No. It operates on a self-winding automatic mechanical caliber that is wound by the natural kinetic motion of your wrist. It has zero battery.',1,1),
(50,7,'How do I wind or restart the watch if it has stopped?','If left unworn past its ~40-hour power reserve, rotate the crown clockwise 20 to 30 times in the neutral position to manually jumpstart the balance wheel, then set the time.',2,1),
(51,7,'What is visible through the skeleton exhibition dial?','The open-heart skeleton front and exhibition caseback reveal the oscillating balance wheel, escapement gear train, and jeweled bearings in active operation.',3,1),
(52,7,'What is the water resistance of this automatic timepiece?','It is pressure-rated to 50M (5 ATM), offering protection against rain, splashes, and brief water immersion. Avoid submerging the leather strap.',4,1),
(53,12,'How does the bilingual day-and-date complication work?','Pull the crown to position 1 to set the date (clockwise) and day of the week (counter-clockwise). Pull to position 2 to set the time.',1,1),
(54,12,'Can the stainless steel bracelet links be resized?','Yes. All solid 316L link bracelets feature inner directional arrows and can be easily adjusted with a link pin tool at home or by any local jeweler.',2,1),
(55,12,'What is the water resistance rating?','It is sealed to 30M (3 ATM) hydrostatic pressure with silicone crown gaskets, resilient against handwashing, sudden rain, and humidity.',3,1),
(56,12,'What warranty is provided?','It comes with Welkar\'s 2-Year movement warranty with complimentary doorstep repair or exchange pickup across India.',4,1),
(57,13,'What makes the Hydro-Lab Submersion Master resilient?','It features black PVD-coated 316L surgical stainless steel, double O-ring silicone crown gaskets, and individually verified pressure-chamber sealing.',1,1),
(58,13,'Does the black PVD coating scratch or peel?','We use high-density physical vapor deposition (PVD) bonding that fuses molecularly with the steel, providing exceptional resistance to daily scratches and sweat erosion.',2,1),
(59,13,'Can I wear this model for water activities?','It is certified for 30M (3 ATM) everyday water protection against heavy rain and splashes. For dedicated swimming, we recommend 50M-rated sports models.',3,1),
(60,13,'What is the battery lifespan and replacement procedure?','The Japanese quartz movement runs on an SR626SW silver-oxide cell for 2 to 3 years and can be replaced in minutes by any watchmaker.',4,1),
(61,14,'What is special about the sunray teal dial?','The dial features an iridescent sunburst micro-texture that reflects ambient light in shifting shades of deep teal and oceanic emerald.',1,1),
(62,14,'How do I adjust the stainless steel link strap?','The bracelet includes removable links secured by standard split-pins. Push pins in the direction of the stamped arrows to size the band to your wrist.',2,1),
(63,14,'What caliber powers this watch?','It runs on a Japanese three-hand quartz caliber with shock-cushioned mounting for precise, hassle-free timekeeping.',3,1),
(64,14,'Is it protected against water?','Yes, it is rated at 30M (3 ATM) for rain, accidental submersions, and handwashing.',4,1),
(65,15,'How do the chronograph pushers operate?','The top 2 o\'clock pusher starts and stops the stopwatch timer, while the lower 4 o\'clock pusher resets the sub-dial hands to zero.',1,1),
(66,15,'Is this timepiece suited for formal and boardroom attire?','Yes, the 44mm brushed steel bezel, applied polished baton indices, and classic multi-link steel bracelet complement business suits and executive wear.',2,1),
(67,15,'What warranty applies to this chronograph?','All Welkar executive chronographs are covered under our 2-Year movement warranty with pan-India insured transit.',3,1),
(68,15,'Can I swim with this watch?','The watch is sealed to 30M (3 ATM) for rain and splashes. Chronograph pushers should never be actuated in wet conditions.',4,1),
(69,16,'How is the tachymeter bezel used?','The calibrated outer bezel allows you to measure speed based on travel time over a fixed distance (e.g., 1 kilometer) using the chronograph second hand.',1,1),
(70,16,'Is this watch safe for swimming and intense workouts?','Yes. It is pressure-tested to 50M (5 ATM) water resistance and equipped with a breathable perforated silicone strap that wicks away sweat and dries quickly.',2,1),
(71,16,'What movement powers the Sport Racer?','A high-impact quartz caliber engineered with shock-absorbent movement holders to withstand vibrations from running, cycling, or motorsport.',3,1),
(72,16,'What is the case finish?','The 44mm case is finished in non-reflective matte black PVD, giving it an understated stealth aesthetic.',4,1),
(73,20,'Can this youth watch be worn while swimming?','Yes. The Arctic Blue Youth watch is sealed to 50M (5 ATM) hydrostatic depth, making it fully safe for recreational pool swimming, water sports, and showers.',1,1),
(74,20,'How does the dual digital-analog display work?','It simultaneously displays traditional analog clock hands and a high-contrast digital screen with 12/24 hour time, date, stopwatch, and hourly chime.',2,1),
(75,20,'What material is used for the strap and casing?','The watch uses impact-resistant drop-safe resin with a soft, non-toxic TPU resin band designed for junior wrist sizes (ages 10-17).',3,1),
(76,20,'Does it have a backlight for night visibility?','Yes, an integrated EL backlight illuminates the digital display at the push of the upper left button.',4,1),
(77,21,'What age group is the Junior Shock-Guard designed for?','It is ergonomically scaled with a 36mm case and soft silicone strap for children aged 5 to 11 years.',1,1),
(78,21,'Is this watch drop-resistant and playground safe?','Yes, the reinforced bumper bezel absorbs shocks and drops onto hard surfaces, while the mineral-acrylic crystal resists shattering.',2,1),
(79,21,'Can a child wear this watch in the swimming pool or bath?','Yes. It is sealed to 50M (5 ATM) water resistance, safe for swimming and daily baths. Do not press buttons underwater.',3,1),
(80,21,'What features does the digital display include?','It features clear digital time, daily alarm, date calendar, split-second stopwatch, and vibrant multi-color LED night backlight.',4,1);
INSERT INTO "product_hotspots" VALUES (1,3,48.50,22.00,'Luminescent Indices & Hands','High-phosphorescence compound coatings ensure optimal low-light legibility across executive suites or evening galas.',NULL,1,1),
(2,3,76.00,50.00,'Double-Gasket Fluted Crown','Silicone dual O-ring construction guarantees tight hydrostatic sealing resistant to splashes, humidity, and atmospheric pressure changes.',NULL,2,1),
(3,3,50.00,78.00,'Solid 316L Link Bracelet','Individually chamfered and micro-brushed surgical steel links with smooth dual-button butterfly deployant clasp.',NULL,3,1),
(4,3,28.00,45.00,'Polished Bezel Chamfer','Mirror-finished angled chamfers catch and refract natural sunlight, emphasizing architectural depth and wrist presence.',NULL,4,1),
(5,4,48.50,22.00,'Luminescent Indices & Hands','High-phosphorescence compound coatings ensure optimal low-light legibility across executive suites or evening galas.',NULL,1,1),
(6,4,76.00,50.00,'Double-Gasket Fluted Crown','Silicone dual O-ring construction guarantees tight hydrostatic sealing resistant to splashes, humidity, and atmospheric pressure changes.',NULL,2,1),
(7,4,50.00,78.00,'Solid 316L Link Bracelet','Individually chamfered and micro-brushed surgical steel links with smooth dual-button butterfly deployant clasp.',NULL,3,1),
(8,4,28.00,45.00,'Polished Bezel Chamfer','Mirror-finished angled chamfers catch and refract natural sunlight, emphasizing architectural depth and wrist presence.',NULL,4,1),
(9,7,48.50,22.00,'Luminescent Indices & Hands','High-phosphorescence compound coatings ensure optimal low-light legibility across executive suites or evening galas.',NULL,1,1),
(10,7,76.00,50.00,'Double-Gasket Fluted Crown','Silicone dual O-ring construction guarantees tight hydrostatic sealing resistant to splashes, humidity, and atmospheric pressure changes.',NULL,2,1),
(11,7,50.00,78.00,'Solid 316L Link Bracelet','Individually chamfered and micro-brushed surgical steel links with smooth dual-button butterfly deployant clasp.',NULL,3,1),
(12,7,28.00,45.00,'Polished Bezel Chamfer','Mirror-finished angled chamfers catch and refract natural sunlight, emphasizing architectural depth and wrist presence.',NULL,4,1),
(13,12,48.50,22.00,'Luminescent Indices & Hands','High-phosphorescence compound coatings ensure optimal low-light legibility across executive suites or evening galas.',NULL,1,1),
(14,12,76.00,50.00,'Double-Gasket Fluted Crown','Silicone dual O-ring construction guarantees tight hydrostatic sealing resistant to splashes, humidity, and atmospheric pressure changes.',NULL,2,1),
(15,12,50.00,78.00,'Solid 316L Link Bracelet','Individually chamfered and micro-brushed surgical steel links with smooth dual-button butterfly deployant clasp.',NULL,3,1),
(16,12,28.00,45.00,'Polished Bezel Chamfer','Mirror-finished angled chamfers catch and refract natural sunlight, emphasizing architectural depth and wrist presence.',NULL,4,1),
(17,13,48.50,22.00,'Luminescent Indices & Hands','High-phosphorescence compound coatings ensure optimal low-light legibility across executive suites or evening galas.',NULL,1,1),
(18,13,76.00,50.00,'Double-Gasket Fluted Crown','Silicone dual O-ring construction guarantees tight hydrostatic sealing resistant to splashes, humidity, and atmospheric pressure changes.',NULL,2,1),
(19,13,50.00,78.00,'Solid 316L Link Bracelet','Individually chamfered and micro-brushed surgical steel links with smooth dual-button butterfly deployant clasp.',NULL,3,1),
(20,13,28.00,45.00,'Polished Bezel Chamfer','Mirror-finished angled chamfers catch and refract natural sunlight, emphasizing architectural depth and wrist presence.',NULL,4,1),
(21,14,48.50,22.00,'Luminescent Indices & Hands','High-phosphorescence compound coatings ensure optimal low-light legibility across executive suites or evening galas.',NULL,1,1),
(22,14,76.00,50.00,'Double-Gasket Fluted Crown','Silicone dual O-ring construction guarantees tight hydrostatic sealing resistant to splashes, humidity, and atmospheric pressure changes.',NULL,2,1),
(23,14,50.00,78.00,'Solid 316L Link Bracelet','Individually chamfered and micro-brushed surgical steel links with smooth dual-button butterfly deployant clasp.',NULL,3,1),
(24,14,28.00,45.00,'Polished Bezel Chamfer','Mirror-finished angled chamfers catch and refract natural sunlight, emphasizing architectural depth and wrist presence.',NULL,4,1),
(25,15,48.50,22.00,'Luminescent Indices & Hands','High-phosphorescence compound coatings ensure optimal low-light legibility across executive suites or evening galas.',NULL,1,1),
(26,15,76.00,50.00,'Double-Gasket Fluted Crown','Silicone dual O-ring construction guarantees tight hydrostatic sealing resistant to splashes, humidity, and atmospheric pressure changes.',NULL,2,1),
(27,15,50.00,78.00,'Solid 316L Link Bracelet','Individually chamfered and micro-brushed surgical steel links with smooth dual-button butterfly deployant clasp.',NULL,3,1),
(28,15,28.00,45.00,'Polished Bezel Chamfer','Mirror-finished angled chamfers catch and refract natural sunlight, emphasizing architectural depth and wrist presence.',NULL,4,1),
(29,16,48.50,22.00,'Luminescent Indices & Hands','High-phosphorescence compound coatings ensure optimal low-light legibility across executive suites or evening galas.',NULL,1,1),
(30,16,76.00,50.00,'Double-Gasket Fluted Crown','Silicone dual O-ring construction guarantees tight hydrostatic sealing resistant to splashes, humidity, and atmospheric pressure changes.',NULL,2,1),
(31,16,50.00,78.00,'Solid 316L Link Bracelet','Individually chamfered and micro-brushed surgical steel links with smooth dual-button butterfly deployant clasp.',NULL,3,1),
(32,16,28.00,45.00,'Polished Bezel Chamfer','Mirror-finished angled chamfers catch and refract natural sunlight, emphasizing architectural depth and wrist presence.',NULL,4,1),
(33,20,48.50,22.00,'Luminescent Indices & Hands','High-phosphorescence compound coatings ensure optimal low-light legibility across executive suites or evening galas.',NULL,1,1),
(34,20,76.00,50.00,'Double-Gasket Fluted Crown','Silicone dual O-ring construction guarantees tight hydrostatic sealing resistant to splashes, humidity, and atmospheric pressure changes.',NULL,2,1),
(35,20,50.00,78.00,'Solid 316L Link Bracelet','Individually chamfered and micro-brushed surgical steel links with smooth dual-button butterfly deployant clasp.',NULL,3,1),
(36,20,28.00,45.00,'Polished Bezel Chamfer','Mirror-finished angled chamfers catch and refract natural sunlight, emphasizing architectural depth and wrist presence.',NULL,4,1),
(37,21,48.50,22.00,'Luminescent Indices & Hands','High-phosphorescence compound coatings ensure optimal low-light legibility across executive suites or evening galas.',NULL,1,1),
(38,21,76.00,50.00,'Double-Gasket Fluted Crown','Silicone dual O-ring construction guarantees tight hydrostatic sealing resistant to splashes, humidity, and atmospheric pressure changes.',NULL,2,1),
(39,21,50.00,78.00,'Solid 316L Link Bracelet','Individually chamfered and micro-brushed surgical steel links with smooth dual-button butterfly deployant clasp.',NULL,3,1),
(40,21,28.00,45.00,'Polished Bezel Chamfer','Mirror-finished angled chamfers catch and refract natural sunlight, emphasizing architectural depth and wrist presence.',NULL,4,1);
INSERT INTO "product_images" VALUES (11,7,NULL,'/uploads/products/watch_7_1791808086_0a3d2ee4.jpeg',1,1,'2026-10-12 17:58:06'),
(12,7,NULL,'/uploads/products/watch_7_1791808086_6508f920.jpeg',0,2,'2026-10-12 17:58:06'),
(13,7,NULL,'/uploads/products/watch_7_1791808086_9409774d.jpeg',0,3,'2026-10-12 17:58:06'),
(14,7,NULL,'/uploads/products/watch_7_1791808086_1e032462.jpeg',0,4,'2026-10-12 17:58:06'),
(15,7,NULL,'/uploads/products/watch_7_1791808086_95aa2375.jpeg',0,5,'2026-10-12 17:58:06'),
(16,7,NULL,'/uploads/products/watch_7_1791808086_3c4b07d7.jpeg',0,6,'2026-10-12 17:58:06'),
(17,7,NULL,'/uploads/products/watch_7_1791808086_ef63d5f4.jpeg',0,7,'2026-10-12 17:58:06'),
(30,4,NULL,'/uploads/products/watch_4_1791810624_2b947d25.jpg',0,1,'2026-10-12 18:40:24'),
(31,4,NULL,'/uploads/products/watch_4_1791810624_f3eee104.jpg',0,2,'2026-10-12 18:40:24'),
(32,4,NULL,'/uploads/products/watch_4_1791810624_f24e81ac.jpeg',0,3,'2026-10-12 18:40:24'),
(33,4,NULL,'/uploads/products/watch_4_1791810624_d1f9ce04.jpeg',0,4,'2026-10-12 18:40:24'),
(34,4,NULL,'/uploads/products/watch_4_1791810624_8e75dcf4.jpeg',0,5,'2026-10-12 18:40:24'),
(35,4,NULL,'/uploads/products/watch_4_1791810624_eef53220.jpeg',1,6,'2026-10-12 18:40:24'),
(36,4,NULL,'/uploads/products/watch_4_1791810624_0865dd47.jpeg',0,7,'2026-10-12 18:40:24'),
(37,4,NULL,'/uploads/products/watch_4_1791810624_27d53ad8.jpg',0,8,'2026-10-12 18:40:24'),
(38,4,NULL,'/uploads/products/watch_4_1791810624_b2d5bec6.jpg',0,9,'2026-10-12 18:40:24'),
(46,3,NULL,'/uploads/products/watch_3_1791810745_c35caf6c.jpeg',1,2,'2026-10-12 18:42:25'),
(47,3,NULL,'/uploads/products/watch_3_1791810745_b338dbce.jpeg',0,3,'2026-10-12 18:42:25'),
(48,3,NULL,'/uploads/products/watch_3_1791810745_b0737142.jpg',0,4,'2026-10-12 18:42:25'),
(49,3,NULL,'/uploads/products/watch_3_1791810745_0a852667.jpg',0,5,'2026-10-12 18:42:25'),
(50,3,NULL,'/uploads/products/watch_3_1791810745_e52f2897.jpeg',0,6,'2026-10-12 18:42:25'),
(51,3,NULL,'/uploads/products/watch_3_1791810745_d2d67c40.jpeg',0,7,'2026-10-12 18:42:25'),
(52,3,NULL,'/uploads/products/watch_3_1791810745_6ae2c137.jpg',0,8,'2026-10-12 18:42:25'),
(82,12,NULL,'https://images.unsplash.com/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=1200&q=80',1,1,'2026-10-13 11:03:30'),
(83,13,NULL,'https://images.unsplash.com/photo-1614164185128-e4ec99c436d7?auto=format&fit=crop&w=1200&q=80',1,1,'2026-10-13 11:03:30'),
(84,14,NULL,'https://images.unsplash.com/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=1200&q=80',1,1,'2026-10-13 11:03:30'),
(85,15,NULL,'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=1200&q=80',1,1,'2026-10-13 11:03:30'),
(86,16,NULL,'https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?auto=format&fit=crop&w=1200&q=80',1,1,'2026-10-13 11:03:30'),
(90,20,NULL,'https://images.unsplash.com/photo-1542496658-e33a6d0d50f6?auto=format&fit=crop&w=1200&q=80',1,1,'2026-10-13 11:03:30'),
(91,21,NULL,'https://images.unsplash.com/photo-1546868871-7041f2a55e12?auto=format&fit=crop&w=1200&q=80',1,1,'2026-10-13 11:03:30');
INSERT INTO "product_page_modules" VALUES (1,3,'banner','Editorial Wide Banner',NULL,'{\"headline\":\"CALIBRATED FOR HOROLOGICAL DISTINCTION\",\"body\":\"Engineered with solid 316L surgical stainless steel, dual-layer anti-reflective crystal, and precision Japanese movements for executive confidence on every wrist.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=1600&q=80\",\"badge\":\"THE ATELIER COLLECTION\"}',1,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(2,3,'stories','Craftsmanship Feature Stories',NULL,'{\"stories\":[{\"eyebrow\":\"COLD-FORGED STEEL\",\"title\":\"316L Surgical Alloy Engineering\",\"text\":\"We forge our cases from medical-grade 316L alloy rather than standard zinc or alloy metals. This provides exceptional resistance to perspiration corrosion and retains a brilliant mirror chamfer for decades.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"Hypoallergenic nickel-safe skin contact\",\"High-density micro-milled link tolerancing\",\"Scratch-resistant satin-brushed flank\"]},{\"eyebrow\":\"HYDROSTATIC LAB\",\"title\":\"Double-Gasket Silicone Pressure Seals\",\"text\":\"Before any watch leaves our testing chamber, it undergoes negative and positive pressure immersion cycles. The fluted crown and caseback utilize compression O-rings engineered to repel moisture under all everyday conditions.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"30M (3 ATM) certified submersion standards\",\"Threaded compression caseback\",\"Rain and humidity resilience\"]},{\"eyebrow\":\"AUTHENTICITY & SERIALS\",\"title\":\"Sequential Stamped Collector Cards\",\"text\":\"Every Welkar watch is laser-inscribed with a unique serial number that matches the stamped holographic warranty card in your presentation box. Your warranty is automatically registered in our active atelier database.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"Holographic tamper-evident verification\",\"Digital warranty concierge registry\",\"Complimentary pan-India service pickup\"]}]}',2,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(3,3,'lifestyle','Lifestyle Carousel',NULL,'{\"title\":\"On The Wrist \\u2014 Collector Styling\",\"subtitle\":\"Real-world wrist presence photographed across diverse formal and casual settings.\",\"images\":[{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Executive Office & Cuff Profile (7.0\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Milanese Evening Grace (5.8\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Weekend Chrono Casual (6.5\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1539874701005-786335a09b30?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Minimalist Monolith Architecture (6.8\\\" Wrist)\"}]}',3,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(4,4,'banner','Editorial Wide Banner',NULL,'{\"headline\":\"CALIBRATED FOR HOROLOGICAL DISTINCTION\",\"body\":\"Engineered with solid 316L surgical stainless steel, dual-layer anti-reflective crystal, and precision Japanese movements for executive confidence on every wrist.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=1600&q=80\",\"badge\":\"THE ATELIER COLLECTION\"}',1,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(5,4,'stories','Craftsmanship Feature Stories',NULL,'{\"stories\":[{\"eyebrow\":\"COLD-FORGED STEEL\",\"title\":\"316L Surgical Alloy Engineering\",\"text\":\"We forge our cases from medical-grade 316L alloy rather than standard zinc or alloy metals. This provides exceptional resistance to perspiration corrosion and retains a brilliant mirror chamfer for decades.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"Hypoallergenic nickel-safe skin contact\",\"High-density micro-milled link tolerancing\",\"Scratch-resistant satin-brushed flank\"]},{\"eyebrow\":\"HYDROSTATIC LAB\",\"title\":\"Double-Gasket Silicone Pressure Seals\",\"text\":\"Before any watch leaves our testing chamber, it undergoes negative and positive pressure immersion cycles. The fluted crown and caseback utilize compression O-rings engineered to repel moisture under all everyday conditions.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"30M (3 ATM) certified submersion standards\",\"Threaded compression caseback\",\"Rain and humidity resilience\"]},{\"eyebrow\":\"AUTHENTICITY & SERIALS\",\"title\":\"Sequential Stamped Collector Cards\",\"text\":\"Every Welkar watch is laser-inscribed with a unique serial number that matches the stamped holographic warranty card in your presentation box. Your warranty is automatically registered in our active atelier database.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"Holographic tamper-evident verification\",\"Digital warranty concierge registry\",\"Complimentary pan-India service pickup\"]}]}',2,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(6,4,'lifestyle','Lifestyle Carousel',NULL,'{\"title\":\"On The Wrist \\u2014 Collector Styling\",\"subtitle\":\"Real-world wrist presence photographed across diverse formal and casual settings.\",\"images\":[{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Executive Office & Cuff Profile (7.0\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Milanese Evening Grace (5.8\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Weekend Chrono Casual (6.5\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1539874701005-786335a09b30?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Minimalist Monolith Architecture (6.8\\\" Wrist)\"}]}',3,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(7,7,'banner','Editorial Wide Banner',NULL,'{\"headline\":\"CALIBRATED FOR HOROLOGICAL DISTINCTION\",\"body\":\"Engineered with solid 316L surgical stainless steel, dual-layer anti-reflective crystal, and precision Japanese movements for executive confidence on every wrist.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=1600&q=80\",\"badge\":\"THE ATELIER COLLECTION\"}',1,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(8,7,'stories','Craftsmanship Feature Stories',NULL,'{\"stories\":[{\"eyebrow\":\"COLD-FORGED STEEL\",\"title\":\"316L Surgical Alloy Engineering\",\"text\":\"We forge our cases from medical-grade 316L alloy rather than standard zinc or alloy metals. This provides exceptional resistance to perspiration corrosion and retains a brilliant mirror chamfer for decades.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"Hypoallergenic nickel-safe skin contact\",\"High-density micro-milled link tolerancing\",\"Scratch-resistant satin-brushed flank\"]},{\"eyebrow\":\"HYDROSTATIC LAB\",\"title\":\"Double-Gasket Silicone Pressure Seals\",\"text\":\"Before any watch leaves our testing chamber, it undergoes negative and positive pressure immersion cycles. The fluted crown and caseback utilize compression O-rings engineered to repel moisture under all everyday conditions.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"30M (3 ATM) certified submersion standards\",\"Threaded compression caseback\",\"Rain and humidity resilience\"]},{\"eyebrow\":\"AUTHENTICITY & SERIALS\",\"title\":\"Sequential Stamped Collector Cards\",\"text\":\"Every Welkar watch is laser-inscribed with a unique serial number that matches the stamped holographic warranty card in your presentation box. Your warranty is automatically registered in our active atelier database.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"Holographic tamper-evident verification\",\"Digital warranty concierge registry\",\"Complimentary pan-India service pickup\"]}]}',2,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(9,7,'lifestyle','Lifestyle Carousel',NULL,'{\"title\":\"On The Wrist \\u2014 Collector Styling\",\"subtitle\":\"Real-world wrist presence photographed across diverse formal and casual settings.\",\"images\":[{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Executive Office & Cuff Profile (7.0\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Milanese Evening Grace (5.8\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Weekend Chrono Casual (6.5\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1539874701005-786335a09b30?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Minimalist Monolith Architecture (6.8\\\" Wrist)\"}]}',3,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(10,12,'banner','Editorial Wide Banner',NULL,'{\"headline\":\"CALIBRATED FOR HOROLOGICAL DISTINCTION\",\"body\":\"Engineered with solid 316L surgical stainless steel, dual-layer anti-reflective crystal, and precision Japanese movements for executive confidence on every wrist.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=1600&q=80\",\"badge\":\"THE ATELIER COLLECTION\"}',1,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(11,12,'stories','Craftsmanship Feature Stories',NULL,'{\"stories\":[{\"eyebrow\":\"COLD-FORGED STEEL\",\"title\":\"316L Surgical Alloy Engineering\",\"text\":\"We forge our cases from medical-grade 316L alloy rather than standard zinc or alloy metals. This provides exceptional resistance to perspiration corrosion and retains a brilliant mirror chamfer for decades.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"Hypoallergenic nickel-safe skin contact\",\"High-density micro-milled link tolerancing\",\"Scratch-resistant satin-brushed flank\"]},{\"eyebrow\":\"HYDROSTATIC LAB\",\"title\":\"Double-Gasket Silicone Pressure Seals\",\"text\":\"Before any watch leaves our testing chamber, it undergoes negative and positive pressure immersion cycles. The fluted crown and caseback utilize compression O-rings engineered to repel moisture under all everyday conditions.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"30M (3 ATM) certified submersion standards\",\"Threaded compression caseback\",\"Rain and humidity resilience\"]},{\"eyebrow\":\"AUTHENTICITY & SERIALS\",\"title\":\"Sequential Stamped Collector Cards\",\"text\":\"Every Welkar watch is laser-inscribed with a unique serial number that matches the stamped holographic warranty card in your presentation box. Your warranty is automatically registered in our active atelier database.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"Holographic tamper-evident verification\",\"Digital warranty concierge registry\",\"Complimentary pan-India service pickup\"]}]}',2,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(12,12,'lifestyle','Lifestyle Carousel',NULL,'{\"title\":\"On The Wrist \\u2014 Collector Styling\",\"subtitle\":\"Real-world wrist presence photographed across diverse formal and casual settings.\",\"images\":[{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Executive Office & Cuff Profile (7.0\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Milanese Evening Grace (5.8\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Weekend Chrono Casual (6.5\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1539874701005-786335a09b30?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Minimalist Monolith Architecture (6.8\\\" Wrist)\"}]}',3,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(13,13,'banner','Editorial Wide Banner',NULL,'{\"headline\":\"CALIBRATED FOR HOROLOGICAL DISTINCTION\",\"body\":\"Engineered with solid 316L surgical stainless steel, dual-layer anti-reflective crystal, and precision Japanese movements for executive confidence on every wrist.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=1600&q=80\",\"badge\":\"THE ATELIER COLLECTION\"}',1,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(14,13,'stories','Craftsmanship Feature Stories',NULL,'{\"stories\":[{\"eyebrow\":\"COLD-FORGED STEEL\",\"title\":\"316L Surgical Alloy Engineering\",\"text\":\"We forge our cases from medical-grade 316L alloy rather than standard zinc or alloy metals. This provides exceptional resistance to perspiration corrosion and retains a brilliant mirror chamfer for decades.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"Hypoallergenic nickel-safe skin contact\",\"High-density micro-milled link tolerancing\",\"Scratch-resistant satin-brushed flank\"]},{\"eyebrow\":\"HYDROSTATIC LAB\",\"title\":\"Double-Gasket Silicone Pressure Seals\",\"text\":\"Before any watch leaves our testing chamber, it undergoes negative and positive pressure immersion cycles. The fluted crown and caseback utilize compression O-rings engineered to repel moisture under all everyday conditions.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"30M (3 ATM) certified submersion standards\",\"Threaded compression caseback\",\"Rain and humidity resilience\"]},{\"eyebrow\":\"AUTHENTICITY & SERIALS\",\"title\":\"Sequential Stamped Collector Cards\",\"text\":\"Every Welkar watch is laser-inscribed with a unique serial number that matches the stamped holographic warranty card in your presentation box. Your warranty is automatically registered in our active atelier database.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"Holographic tamper-evident verification\",\"Digital warranty concierge registry\",\"Complimentary pan-India service pickup\"]}]}',2,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(15,13,'lifestyle','Lifestyle Carousel',NULL,'{\"title\":\"On The Wrist \\u2014 Collector Styling\",\"subtitle\":\"Real-world wrist presence photographed across diverse formal and casual settings.\",\"images\":[{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Executive Office & Cuff Profile (7.0\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Milanese Evening Grace (5.8\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Weekend Chrono Casual (6.5\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1539874701005-786335a09b30?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Minimalist Monolith Architecture (6.8\\\" Wrist)\"}]}',3,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(16,14,'banner','Editorial Wide Banner',NULL,'{\"headline\":\"CALIBRATED FOR HOROLOGICAL DISTINCTION\",\"body\":\"Engineered with solid 316L surgical stainless steel, dual-layer anti-reflective crystal, and precision Japanese movements for executive confidence on every wrist.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=1600&q=80\",\"badge\":\"THE ATELIER COLLECTION\"}',1,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(17,14,'stories','Craftsmanship Feature Stories',NULL,'{\"stories\":[{\"eyebrow\":\"COLD-FORGED STEEL\",\"title\":\"316L Surgical Alloy Engineering\",\"text\":\"We forge our cases from medical-grade 316L alloy rather than standard zinc or alloy metals. This provides exceptional resistance to perspiration corrosion and retains a brilliant mirror chamfer for decades.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"Hypoallergenic nickel-safe skin contact\",\"High-density micro-milled link tolerancing\",\"Scratch-resistant satin-brushed flank\"]},{\"eyebrow\":\"HYDROSTATIC LAB\",\"title\":\"Double-Gasket Silicone Pressure Seals\",\"text\":\"Before any watch leaves our testing chamber, it undergoes negative and positive pressure immersion cycles. The fluted crown and caseback utilize compression O-rings engineered to repel moisture under all everyday conditions.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"30M (3 ATM) certified submersion standards\",\"Threaded compression caseback\",\"Rain and humidity resilience\"]},{\"eyebrow\":\"AUTHENTICITY & SERIALS\",\"title\":\"Sequential Stamped Collector Cards\",\"text\":\"Every Welkar watch is laser-inscribed with a unique serial number that matches the stamped holographic warranty card in your presentation box. Your warranty is automatically registered in our active atelier database.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"Holographic tamper-evident verification\",\"Digital warranty concierge registry\",\"Complimentary pan-India service pickup\"]}]}',2,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(18,14,'lifestyle','Lifestyle Carousel',NULL,'{\"title\":\"On The Wrist \\u2014 Collector Styling\",\"subtitle\":\"Real-world wrist presence photographed across diverse formal and casual settings.\",\"images\":[{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Executive Office & Cuff Profile (7.0\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Milanese Evening Grace (5.8\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Weekend Chrono Casual (6.5\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1539874701005-786335a09b30?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Minimalist Monolith Architecture (6.8\\\" Wrist)\"}]}',3,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(19,15,'banner','Editorial Wide Banner',NULL,'{\"headline\":\"CALIBRATED FOR HOROLOGICAL DISTINCTION\",\"body\":\"Engineered with solid 316L surgical stainless steel, dual-layer anti-reflective crystal, and precision Japanese movements for executive confidence on every wrist.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=1600&q=80\",\"badge\":\"THE ATELIER COLLECTION\"}',1,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(20,15,'stories','Craftsmanship Feature Stories',NULL,'{\"stories\":[{\"eyebrow\":\"COLD-FORGED STEEL\",\"title\":\"316L Surgical Alloy Engineering\",\"text\":\"We forge our cases from medical-grade 316L alloy rather than standard zinc or alloy metals. This provides exceptional resistance to perspiration corrosion and retains a brilliant mirror chamfer for decades.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"Hypoallergenic nickel-safe skin contact\",\"High-density micro-milled link tolerancing\",\"Scratch-resistant satin-brushed flank\"]},{\"eyebrow\":\"HYDROSTATIC LAB\",\"title\":\"Double-Gasket Silicone Pressure Seals\",\"text\":\"Before any watch leaves our testing chamber, it undergoes negative and positive pressure immersion cycles. The fluted crown and caseback utilize compression O-rings engineered to repel moisture under all everyday conditions.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"30M (3 ATM) certified submersion standards\",\"Threaded compression caseback\",\"Rain and humidity resilience\"]},{\"eyebrow\":\"AUTHENTICITY & SERIALS\",\"title\":\"Sequential Stamped Collector Cards\",\"text\":\"Every Welkar watch is laser-inscribed with a unique serial number that matches the stamped holographic warranty card in your presentation box. Your warranty is automatically registered in our active atelier database.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"Holographic tamper-evident verification\",\"Digital warranty concierge registry\",\"Complimentary pan-India service pickup\"]}]}',2,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(21,15,'lifestyle','Lifestyle Carousel',NULL,'{\"title\":\"On The Wrist \\u2014 Collector Styling\",\"subtitle\":\"Real-world wrist presence photographed across diverse formal and casual settings.\",\"images\":[{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Executive Office & Cuff Profile (7.0\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Milanese Evening Grace (5.8\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Weekend Chrono Casual (6.5\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1539874701005-786335a09b30?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Minimalist Monolith Architecture (6.8\\\" Wrist)\"}]}',3,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(22,16,'banner','Editorial Wide Banner',NULL,'{\"headline\":\"CALIBRATED FOR HOROLOGICAL DISTINCTION\",\"body\":\"Engineered with solid 316L surgical stainless steel, dual-layer anti-reflective crystal, and precision Japanese movements for executive confidence on every wrist.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=1600&q=80\",\"badge\":\"THE ATELIER COLLECTION\"}',1,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(23,16,'stories','Craftsmanship Feature Stories',NULL,'{\"stories\":[{\"eyebrow\":\"COLD-FORGED STEEL\",\"title\":\"316L Surgical Alloy Engineering\",\"text\":\"We forge our cases from medical-grade 316L alloy rather than standard zinc or alloy metals. This provides exceptional resistance to perspiration corrosion and retains a brilliant mirror chamfer for decades.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"Hypoallergenic nickel-safe skin contact\",\"High-density micro-milled link tolerancing\",\"Scratch-resistant satin-brushed flank\"]},{\"eyebrow\":\"HYDROSTATIC LAB\",\"title\":\"Double-Gasket Silicone Pressure Seals\",\"text\":\"Before any watch leaves our testing chamber, it undergoes negative and positive pressure immersion cycles. The fluted crown and caseback utilize compression O-rings engineered to repel moisture under all everyday conditions.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"30M (3 ATM) certified submersion standards\",\"Threaded compression caseback\",\"Rain and humidity resilience\"]},{\"eyebrow\":\"AUTHENTICITY & SERIALS\",\"title\":\"Sequential Stamped Collector Cards\",\"text\":\"Every Welkar watch is laser-inscribed with a unique serial number that matches the stamped holographic warranty card in your presentation box. Your warranty is automatically registered in our active atelier database.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"Holographic tamper-evident verification\",\"Digital warranty concierge registry\",\"Complimentary pan-India service pickup\"]}]}',2,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(24,16,'lifestyle','Lifestyle Carousel',NULL,'{\"title\":\"On The Wrist \\u2014 Collector Styling\",\"subtitle\":\"Real-world wrist presence photographed across diverse formal and casual settings.\",\"images\":[{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Executive Office & Cuff Profile (7.0\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Milanese Evening Grace (5.8\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Weekend Chrono Casual (6.5\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1539874701005-786335a09b30?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Minimalist Monolith Architecture (6.8\\\" Wrist)\"}]}',3,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(25,20,'banner','Editorial Wide Banner',NULL,'{\"headline\":\"CALIBRATED FOR HOROLOGICAL DISTINCTION\",\"body\":\"Engineered with solid 316L surgical stainless steel, dual-layer anti-reflective crystal, and precision Japanese movements for executive confidence on every wrist.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=1600&q=80\",\"badge\":\"THE ATELIER COLLECTION\"}',1,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(26,20,'stories','Craftsmanship Feature Stories',NULL,'{\"stories\":[{\"eyebrow\":\"COLD-FORGED STEEL\",\"title\":\"316L Surgical Alloy Engineering\",\"text\":\"We forge our cases from medical-grade 316L alloy rather than standard zinc or alloy metals. This provides exceptional resistance to perspiration corrosion and retains a brilliant mirror chamfer for decades.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"Hypoallergenic nickel-safe skin contact\",\"High-density micro-milled link tolerancing\",\"Scratch-resistant satin-brushed flank\"]},{\"eyebrow\":\"HYDROSTATIC LAB\",\"title\":\"Double-Gasket Silicone Pressure Seals\",\"text\":\"Before any watch leaves our testing chamber, it undergoes negative and positive pressure immersion cycles. The fluted crown and caseback utilize compression O-rings engineered to repel moisture under all everyday conditions.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"30M (3 ATM) certified submersion standards\",\"Threaded compression caseback\",\"Rain and humidity resilience\"]},{\"eyebrow\":\"AUTHENTICITY & SERIALS\",\"title\":\"Sequential Stamped Collector Cards\",\"text\":\"Every Welkar watch is laser-inscribed with a unique serial number that matches the stamped holographic warranty card in your presentation box. Your warranty is automatically registered in our active atelier database.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=800&q=80\",\"bullets\":[\"Holographic tamper-evident verification\",\"Digital warranty concierge registry\",\"Complimentary pan-India service pickup\"]}]}',2,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(27,20,'lifestyle','Lifestyle Carousel',NULL,'{\"title\":\"On The Wrist \\u2014 Collector Styling\",\"subtitle\":\"Real-world wrist presence photographed across diverse formal and casual settings.\",\"images\":[{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Executive Office & Cuff Profile (7.0\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Milanese Evening Grace (5.8\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Weekend Chrono Casual (6.5\\\" Wrist)\"},{\"url\":\"https:\\/\\/images.unsplash.com\\/photo-1539874701005-786335a09b30?auto=format&fit=crop&w=600&q=80\",\"caption\":\"Minimalist Monolith Architecture (6.8\\\" Wrist)\"}]}',3,1,'2026-10-15 17:21:52','2026-10-15 17:21:52'),
(28,21,'banner','Editorial Wide Banner',NULL,'{\"headline\":\".\",\"body\":\".\",\"image_url\":\"\\/uploads\\/banners\\/banners_1792239763_8115cae9.png\"}',1,1,'2026-10-15 17:21:52','2026-10-17 17:52:43'),
(29,21,'stories','Craftsmanship Feature Stories',NULL,'{\"stories\":[{\"eyebrow\":\"COLD-FORGED STEEL\",\"title\":\"316L Surgical Alloy Engineering\",\"text\":\"We forge our cases from medical-grade 316L alloy rather than standard zinc or alloy metals. This provides exceptional resistance to perspiration corrosion and retains a brilliant mirror chamfer for decades.\",\"image_url\":\"\\/uploads\\/stories\\/stories_1792240903_247351f0.jpg\",\"bullets\":[\"Hypoallergenic nickel-safe skin contact\",\"High-density micro-milled link tolerancing\",\"Scratch-resistant satin-brushed flank\"]},{\"eyebrow\":\"HYDROSTATIC LAB\",\"title\":\"Double-Gasket Silicone Pressure Seals\",\"text\":\"Before any watch leaves our testing chamber, it undergoes negative and positive pressure immersion cycles. The fluted crown and caseback utilize compression O-rings engineered to repel moisture under all everyday conditions.\",\"image_url\":\"\\/uploads\\/stories\\/stories_1792240912_6093cca3.jpg\",\"bullets\":[\"30M (3 ATM) certified submersion standards\",\"Threaded compression caseback\",\"Rain and humidity resilience\"]},{\"eyebrow\":\"AUTHENTICITY & SERIALS\",\"title\":\"Sequential Stamped Collector Cards\",\"text\":\"Every Welkar watch is laser-inscribed with a unique serial number that matches the stamped holographic warranty card in your presentation box. Your warranty is automatically registered in our active atelier database.\",\"image_url\":\"\\/uploads\\/stories\\/stories_1792240917_ad8cb218.jpg\",\"bullets\":[\"Holographic tamper-evident verification\",\"Digital warranty concierge registry\",\"Complimentary pan-India service pickup\"]}]}',2,1,'2026-10-15 17:21:52','2026-10-17 18:11:57'),
(30,21,'lifestyle','Lifestyle Carousel',NULL,'{\"title\":\"On The Wrist\",\"images\":[{\"url\":\"\\/uploads\\/lifestyle\\/lifestyle_1792239967_f463fdcc.jpg\",\"caption\":\"Executive Cuff Profile\",\"subcaption\":\"\"},{\"url\":\"\\/uploads\\/lifestyle\\/lifestyle_1792239980_131359b0.jpg\",\"caption\":\"Milanese Mesh Grace\",\"subcaption\":\"\"},{\"url\":\"\\/uploads\\/lifestyle\\/lifestyle_1792239991_ad7db83c.jpg\",\"caption\":\"Weekend Chrono Casual\",\"subcaption\":\"\"},{\"url\":\"\\/uploads\\/lifestyle\\/lifestyle_1792240004_90318d15.jpg\",\"caption\":\"Monolith Bauhaus Modern\",\"subcaption\":\"\"}]}',3,1,'2026-10-15 17:21:52','2026-10-17 17:56:44');
INSERT INTO "product_specifications" VALUES (10,3,'Dial Pattern','Classical Sunray Guilloche',1),
(11,3,'Numerals','Applied Roman Numerals',2),
(13,7,'Jewels','21 Synthetic Rubies',1),
(14,7,'Power Reserve','Approximately 42 Hours',2);
INSERT INTO "product_variants" VALUES (5,3,'WLK-ROY-GLD-BRN','890123456705','Champagne Gold / Brown Leather','Champagne','Brown',2899.00,3999.00,1450.00,1,'2026-10-12 16:57:27','2026-10-12 16:57:27'),
(6,4,'TTN-NEO-NVY-BLU','890123456706','Navy Blue / Silicone Sport','Navy Blue','Navy Blue',3795.00,4495.00,2500.00,1,'2026-10-12 16:57:27','2026-10-12 16:57:27'),
(9,7,'FSL-TWN-SKL-BRN','890123456709','Skeleton Silver / Dark Brown Leather','Skeleton Open','Dark Brown',9495.00,13995.00,6800.00,1,'2026-10-12 16:57:27','2026-10-12 16:57:27'),
(12,12,'WLK-SLV-DD-02',NULL,'Classic Silver / Black','Black','Silver',799.00,1299.00,380.00,1,'2026-10-13 11:03:30','2026-10-13 11:03:30'),
(13,13,'WLK-HYD-SUB-03',NULL,'Hydro-Lab 30M Edition','Black','Black',999.00,1699.00,480.00,1,'2026-10-13 11:03:30','2026-10-13 11:03:30'),
(14,14,'WLK-SUN-TEAL-04',NULL,'Sunray Teal Steel','Teal','Silver',899.00,1449.00,420.00,1,'2026-10-13 11:03:30','2026-10-13 11:03:30'),
(15,15,'WLK-EXEC-CHR-05',NULL,'Executive Steel Chrono','Black','Silver',849.00,1399.00,400.00,1,'2026-10-13 11:03:30','2026-10-13 11:03:30'),
(16,16,'WLK-BLK-RCR-06',NULL,'Black-Out Sport Racer','Black','Black',949.00,1599.00,460.00,1,'2026-10-13 11:03:30','2026-10-13 11:03:30'),
(20,20,'WLK-YTH-BLU-10',NULL,'Arctic Blue Active','Blue','Arctic Blue',599.00,999.00,260.00,1,'2026-10-13 11:03:30','2026-10-13 11:03:30'),
(21,21,'WLK-YTH-ORG-11',NULL,'Blaze Orange Shock','Orange','Blaze Orange',499.00,849.00,220.00,1,'2026-10-13 11:03:30','2026-10-13 11:03:30');
INSERT INTO "products" VALUES (3,1,'Welkar Royale Classic Roman Gold','welkar-royale-classic-roman-gold','W-ROY-03','WLK-ROY-GLD-03','Timeless dress watch with embossed Roman dial indices and champagne finish.','<p>Graceful proportions and classic aesthetics. The <strong>Welkar Royale Classic</strong> presents grand Roman numerals over a delicately guilloched champagne dial, encased in warm polished gold-tone steel with an Italian brown leather band.</p>','Unisex','Classic Roman','Japanese Quartz','Gold-plated Stainless Steel',39,'Gold','Genuine Leather','Brown','30m / 3 ATM',1,'India',2899.00,1450.00,3999.00,1,'/uploads/products/watch_3_1791810745_c35caf6c.jpeg',NULL,NULL,NULL,50,50,'9102',18.00,1,1,'Welkar Royale Classic Roman Gold Dress Watch','Timeless Roman numerals dress watch in champagne gold by Welkar.','2026-10-12 16:57:27','2026-10-15 17:32:51'),
(4,2,'Titan Neo Splash Sport Chrono','titan-neo-splash-sport-chrono','T-NEO-88','TTN-NEO-8812','Sporty high-durability watch with blue sunray dial and silicone strap.','<p>A modern daily driver built by Titan. The Neo Splash features energetic orange accents, date display, and flexible waterproof silicone strap for active lifestyles.</p>','Men','Analog','Quartz','Polished Steel',43,'Silver','Silicone Rubber','Navy Blue','50m / 5 ATM',2,'India',3795.00,2500.00,4495.00,0,NULL,NULL,NULL,NULL,50,50,'9102',18.00,0,1,'Titan Neo Splash Sport Watch','Shop Titan Neo Splash watch at Welkar.','2026-10-12 16:57:27','2026-10-12 18:40:37'),
(7,4,'Fossil Townsman Automatic Skeleton','fossil-townsman-automatic-skeleton','ME3110','FSL-TWN-SKL-07','Self-winding mechanical watch revealing intricate internal gearwork.','<p>No battery needed. Powered by the natural motion of your wrist, the Townsman Skeleton exposes the heartbeat of mechanical watchmaking through double-sided exhibition crystals.</p>','Men','Automatic','Automatic Mechanical','Brushed Stainless Steel',44,'Silver','Genuine Leather','Dark Brown','50m / 5 ATM',2,'India',9495.00,6800.00,13995.00,0,NULL,NULL,NULL,NULL,50,50,'9102',18.00,1,1,'Fossil Townsman Automatic Skeleton Watch','Luxury self-winding automatic mechanical watch on Welkar.','2026-10-12 16:57:27','2026-10-12 17:59:35'),
(12,1,'Welkar Silver Quartz Analog - Black Dial with Day & Date','welkar-silver-quartz-analog-day-date','W-SLV-DD','WLK-SLV-DD-02','High-polish silver bezel featuring dual day and date calendar aperture with luminous indices and multi-link band.','<p>The <strong>Welkar Silver Quartz Analog</strong> pairs everyday executive poise with practical day-date complications and a scratch-resistant Hardlex crystal face.</p>','Men','Round Chrono','Dual Day-Date Quartz','316L Silver Brushed Steel',42,'Silver','Solid Stainless Steel','Silver','30m / 3 ATM',2,'India',799.00,380.00,1299.00,1,'/uploads/products/watch_3_1791810745_c35caf6c.jpeg',NULL,NULL,NULL,50,50,'9102',18.00,1,1,'Welkar Silver Quartz Analog Day-Date Watch','Shop Welkar Silver Quartz Day-Date.','2026-10-13 10:39:08','2026-10-15 17:32:51'),
(13,1,'Welkar Hydro-Lab Submersion Master Edition','welkar-hydro-lab-submersion-master','W-HYD-SUB','WLK-HYD-SUB-03','Engineered for aquatic resilience with specialized double-gasket water seal, scratch-hardened mineral crystal.','<p>The <strong>Welkar Hydro-Lab Submersion Master</strong> undergoes simulated 30M hydrostatic immersion verification to protect the internal precision Japanese caliber from water splashes, rain, and pressure fluctuations.</p>','Men','Round Chrono','Japanese Quartz 30M','316L Solid Steel Alloy',44,'Black','316L Steel Link','Black','30m / 3 ATM',2,'India',999.00,480.00,1699.00,0,NULL,NULL,NULL,NULL,50,50,'9102',18.00,1,1,'Welkar Hydro-Lab Submersion Master Watch','Official Hydro-Pressure Submersion Watch.','2026-10-13 10:39:08','2026-10-13 10:39:08'),
(14,1,'Welkar Sunray Teal Dial Silver Metal Strap','welkar-sunray-teal-dial-silver-metal-strap','W-SUN-TEAL','WLK-SUN-TEAL-04','Brushed circular stainless bezel housing a deep charcoal/teal sunburst face with micro-chamfered silver indices.','<p>The <strong>Welkar Sunray Teal Dial</strong> catches natural lighting with shimmering metallic refraction, paired with an ergonomic multi-link bracelet.</p>','Men','Round Chrono','Japanese Quartz','Stainless Steel',42,'Silver','Solid Stainless Steel','Silver','30m / 3 ATM',2,'India',899.00,420.00,1449.00,0,NULL,NULL,NULL,NULL,50,50,'9102',18.00,1,1,'Welkar Sunray Teal Dial Watch','Shop Welkar Sunray Teal Dial.','2026-10-13 11:03:30','2026-10-13 11:03:30'),
(15,1,'Welkar Executive Steel Chrono Dial with Stainless Steel Back','welkar-executive-steel-chrono-dial','W-EXEC-CHR','WLK-EXEC-CHR-05','Laser engraved steel back plate, butterfly security clasp, and multi-subdial aesthetic chronograph styling.','<p>Designed for boardroom refinement. Built with a double-push safety clasp and polished 316L case back plate.</p>','Men','Round Chrono','Quartz Chronograph','316L Stainless Steel',44,'Silver','Stainless Steel Link','Silver','30m / 3 ATM',2,'India',849.00,400.00,1399.00,0,NULL,NULL,NULL,NULL,50,50,'9102',18.00,1,1,'Welkar Executive Steel Chrono Watch','Executive Chrono Watch by Welkar.','2026-10-13 10:39:08','2026-10-13 10:39:08'),
(16,1,'Welkar Black-Out Sport Racer with Tachymeter Bezel','welkar-black-out-sport-racer','W-BLK-RCR','WLK-BLK-RCR-06','Motorsport-inspired tachymeter bezel, crimson second hand accent, and impact-shielded casing.','<p>Aggressive track styling featuring black PVD finish and vibrant racing accents.</p>','Men','Round Chrono','High-Impact Quartz','Matte Black PVD',44,'Black','Perforated Silicone','Black','50m / 5 ATM',2,'India',949.00,460.00,1599.00,0,NULL,NULL,NULL,NULL,50,50,'9102',18.00,1,1,'Welkar Black-Out Sport Racer','Shop Black-Out Sport Racer.','2026-10-13 10:39:08','2026-10-13 10:39:08'),
(20,1,'Welkar Youth Dual-Display Chrono Sport Watch - Arctic Blue','welkar-youth-dual-display-chrono-sport-arctic-blue','W-YTH-BLU','WLK-YTH-BLU-10','Arctic Blue casing with 1/100s split lap timer, EL backlight, and 50M swim-proof dual digital-analog display.','<p>Built inside the Welkar Hydro-Pressure Lab with shock-absorbent composite case, food-grade silicone band, and shatter-proof mineral crystal.</p>','Kids','Round Chrono','Dual Digital-Analog 50M','Drop-Safe Resin',38,'Blue','Flexible TPU Resin','Arctic Blue','50m / 5 ATM',2,'India',599.00,260.00,999.00,0,NULL,NULL,NULL,NULL,50,50,'9102',18.00,1,1,'Welkar Youth Chrono Sport Watch - Arctic Blue','Youth Dual-Display Sport Watch.','2026-10-13 10:39:08','2026-10-13 10:55:20'),
(21,1,'Welkar Junior Shock-Guard Digital LED Watch - Blaze Orange','welkar-junior-shock-guard-digital-led-blaze-orange','W-YTH-ORG','WLK-YTH-ORG-11','Blaze Orange reinforced bumper, daily alarm chime, 7-color ambient night light.','<p>Engineered for junior playground adventures with shatter-resistant casing and non-toxic skin-safe silicone strap.</p>','Kids','Round Chrono','Digital LED Quartz','Shock-Guard Resin',36,'Orange','Shock Silicone Rubber','Blaze Orange','50m / 5 ATM',2,'India',499.00,220.00,849.00,0,NULL,NULL,NULL,NULL,50,50,'9102',18.00,1,1,'Welkar Junior Shock-Guard Watch','Junior Digital Watch in Blaze Orange.','2026-10-13 11:03:30','2026-10-17 17:49:47');
INSERT INTO "review_votes" VALUES (2,5,'jqujtdle2mdr2qc171q0qkjugj','::1','2026-10-15 17:33:15'),
(3,5,'12o2uigm9v44jm8lbd2s1deefp','::1','2026-10-15 17:33:42');
INSERT INTO "reviews" VALUES (5,3,NULL,NULL,NULL,'Arjun Singhania',NULL,5,'Exceeded all expectations — looks like a ₹15,000 Swiss piece','The finishing on the 316L steel links is shockingly good. Mirror chamfers catch the light beautifully and the sweeping hands are dead accurate. Came in a gorgeous magnetic hard-shell box with serial card.',1,'approved',20,'[\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80\"]','Thank you for your appreciation, Arjun! Enjoy the timepiece in good health.',NULL,'2026-10-15 17:21:52'),
(6,3,NULL,NULL,NULL,'Pooja Deshmukh',NULL,5,'Delicate, featherlight, and perfectly sized','Ordered via COD to Pune. Received it within 2 days in secure tamper-proof packaging. The dial has a subtle sunburst texture that looks stunning in daytime.',1,'approved',12,NULL,'We are delighted to hear you love the dial texture, Pooja! Welcome to the Welkar Atelier.',NULL,'2026-10-15 17:21:52'),
(7,3,NULL,NULL,NULL,'Rohan Mehta',NULL,4,'Substantial wrist presence, very comfortable clasp','The butterfly clasp clicks with a reassuring solid snap. Only needed to remove two links which took 5 minutes with the included guidance. Great value.',1,'approved',7,NULL,NULL,NULL,'2026-10-15 17:21:52'),
(8,4,NULL,NULL,NULL,'Arjun Singhania',NULL,5,'Exceeded all expectations — looks like a ₹15,000 Swiss piece','The finishing on the 316L steel links is shockingly good. Mirror chamfers catch the light beautifully and the sweeping hands are dead accurate. Came in a gorgeous magnetic hard-shell box with serial card.',1,'approved',18,'[\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80\"]','Thank you for your appreciation, Arjun! Enjoy the timepiece in good health.',NULL,'2026-10-15 17:21:52'),
(9,4,NULL,NULL,NULL,'Pooja Deshmukh',NULL,5,'Delicate, featherlight, and perfectly sized','Ordered via COD to Pune. Received it within 2 days in secure tamper-proof packaging. The dial has a subtle sunburst texture that looks stunning in daytime.',1,'approved',12,NULL,'We are delighted to hear you love the dial texture, Pooja! Welcome to the Welkar Atelier.',NULL,'2026-10-15 17:21:52'),
(10,4,NULL,NULL,NULL,'Rohan Mehta',NULL,4,'Substantial wrist presence, very comfortable clasp','The butterfly clasp clicks with a reassuring solid snap. Only needed to remove two links which took 5 minutes with the included guidance. Great value.',1,'approved',7,NULL,NULL,NULL,'2026-10-15 17:21:52'),
(11,7,NULL,NULL,NULL,'Arjun Singhania',NULL,5,'Exceeded all expectations — looks like a ₹15,000 Swiss piece','The finishing on the 316L steel links is shockingly good. Mirror chamfers catch the light beautifully and the sweeping hands are dead accurate. Came in a gorgeous magnetic hard-shell box with serial card.',1,'approved',18,'[\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80\"]','Thank you for your appreciation, Arjun! Enjoy the timepiece in good health.',NULL,'2026-10-15 17:21:52'),
(12,7,NULL,NULL,NULL,'Pooja Deshmukh',NULL,5,'Delicate, featherlight, and perfectly sized','Ordered via COD to Pune. Received it within 2 days in secure tamper-proof packaging. The dial has a subtle sunburst texture that looks stunning in daytime.',1,'approved',12,NULL,'We are delighted to hear you love the dial texture, Pooja! Welcome to the Welkar Atelier.',NULL,'2026-10-15 17:21:52'),
(13,7,NULL,NULL,NULL,'Rohan Mehta',NULL,4,'Substantial wrist presence, very comfortable clasp','The butterfly clasp clicks with a reassuring solid snap. Only needed to remove two links which took 5 minutes with the included guidance. Great value.',1,'approved',7,NULL,NULL,NULL,'2026-10-15 17:21:52'),
(14,12,NULL,NULL,NULL,'Arjun Singhania',NULL,5,'Exceeded all expectations — looks like a ₹15,000 Swiss piece','The finishing on the 316L steel links is shockingly good. Mirror chamfers catch the light beautifully and the sweeping hands are dead accurate. Came in a gorgeous magnetic hard-shell box with serial card.',1,'approved',18,'[\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80\"]','Thank you for your appreciation, Arjun! Enjoy the timepiece in good health.',NULL,'2026-10-15 17:21:52'),
(15,12,NULL,NULL,NULL,'Pooja Deshmukh',NULL,5,'Delicate, featherlight, and perfectly sized','Ordered via COD to Pune. Received it within 2 days in secure tamper-proof packaging. The dial has a subtle sunburst texture that looks stunning in daytime.',1,'approved',12,NULL,'We are delighted to hear you love the dial texture, Pooja! Welcome to the Welkar Atelier.',NULL,'2026-10-15 17:21:52'),
(16,12,NULL,NULL,NULL,'Rohan Mehta',NULL,4,'Substantial wrist presence, very comfortable clasp','The butterfly clasp clicks with a reassuring solid snap. Only needed to remove two links which took 5 minutes with the included guidance. Great value.',1,'approved',7,NULL,NULL,NULL,'2026-10-15 17:21:52'),
(17,13,NULL,NULL,NULL,'Arjun Singhania',NULL,5,'Exceeded all expectations — looks like a ₹15,000 Swiss piece','The finishing on the 316L steel links is shockingly good. Mirror chamfers catch the light beautifully and the sweeping hands are dead accurate. Came in a gorgeous magnetic hard-shell box with serial card.',1,'approved',18,'[\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80\"]','Thank you for your appreciation, Arjun! Enjoy the timepiece in good health.',NULL,'2026-10-15 17:21:52'),
(18,13,NULL,NULL,NULL,'Pooja Deshmukh',NULL,5,'Delicate, featherlight, and perfectly sized','Ordered via COD to Pune. Received it within 2 days in secure tamper-proof packaging. The dial has a subtle sunburst texture that looks stunning in daytime.',1,'approved',12,NULL,'We are delighted to hear you love the dial texture, Pooja! Welcome to the Welkar Atelier.',NULL,'2026-10-15 17:21:52'),
(19,13,NULL,NULL,NULL,'Rohan Mehta',NULL,4,'Substantial wrist presence, very comfortable clasp','The butterfly clasp clicks with a reassuring solid snap. Only needed to remove two links which took 5 minutes with the included guidance. Great value.',1,'approved',7,NULL,NULL,NULL,'2026-10-15 17:21:52'),
(20,14,NULL,NULL,NULL,'Arjun Singhania',NULL,5,'Exceeded all expectations — looks like a ₹15,000 Swiss piece','The finishing on the 316L steel links is shockingly good. Mirror chamfers catch the light beautifully and the sweeping hands are dead accurate. Came in a gorgeous magnetic hard-shell box with serial card.',1,'approved',18,'[\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80\"]','Thank you for your appreciation, Arjun! Enjoy the timepiece in good health.',NULL,'2026-10-15 17:21:52'),
(21,14,NULL,NULL,NULL,'Pooja Deshmukh',NULL,5,'Delicate, featherlight, and perfectly sized','Ordered via COD to Pune. Received it within 2 days in secure tamper-proof packaging. The dial has a subtle sunburst texture that looks stunning in daytime.',1,'approved',12,NULL,'We are delighted to hear you love the dial texture, Pooja! Welcome to the Welkar Atelier.',NULL,'2026-10-15 17:21:52'),
(22,14,NULL,NULL,NULL,'Rohan Mehta',NULL,4,'Substantial wrist presence, very comfortable clasp','The butterfly clasp clicks with a reassuring solid snap. Only needed to remove two links which took 5 minutes with the included guidance. Great value.',1,'approved',7,NULL,NULL,NULL,'2026-10-15 17:21:52'),
(23,15,NULL,NULL,NULL,'Arjun Singhania',NULL,5,'Exceeded all expectations — looks like a ₹15,000 Swiss piece','The finishing on the 316L steel links is shockingly good. Mirror chamfers catch the light beautifully and the sweeping hands are dead accurate. Came in a gorgeous magnetic hard-shell box with serial card.',1,'approved',18,'[\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80\"]','Thank you for your appreciation, Arjun! Enjoy the timepiece in good health.',NULL,'2026-10-15 17:21:52'),
(24,15,NULL,NULL,NULL,'Pooja Deshmukh',NULL,5,'Delicate, featherlight, and perfectly sized','Ordered via COD to Pune. Received it within 2 days in secure tamper-proof packaging. The dial has a subtle sunburst texture that looks stunning in daytime.',1,'approved',12,NULL,'We are delighted to hear you love the dial texture, Pooja! Welcome to the Welkar Atelier.',NULL,'2026-10-15 17:21:52'),
(25,15,NULL,NULL,NULL,'Rohan Mehta',NULL,4,'Substantial wrist presence, very comfortable clasp','The butterfly clasp clicks with a reassuring solid snap. Only needed to remove two links which took 5 minutes with the included guidance. Great value.',1,'approved',7,NULL,NULL,NULL,'2026-10-15 17:21:52'),
(26,16,NULL,NULL,NULL,'Arjun Singhania',NULL,5,'Exceeded all expectations — looks like a ₹15,000 Swiss piece','The finishing on the 316L steel links is shockingly good. Mirror chamfers catch the light beautifully and the sweeping hands are dead accurate. Came in a gorgeous magnetic hard-shell box with serial card.',1,'approved',18,'[\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80\"]','Thank you for your appreciation, Arjun! Enjoy the timepiece in good health.',NULL,'2026-10-15 17:21:52'),
(27,16,NULL,NULL,NULL,'Pooja Deshmukh',NULL,5,'Delicate, featherlight, and perfectly sized','Ordered via COD to Pune. Received it within 2 days in secure tamper-proof packaging. The dial has a subtle sunburst texture that looks stunning in daytime.',1,'approved',12,NULL,'We are delighted to hear you love the dial texture, Pooja! Welcome to the Welkar Atelier.',NULL,'2026-10-15 17:21:52'),
(28,16,NULL,NULL,NULL,'Rohan Mehta',NULL,4,'Substantial wrist presence, very comfortable clasp','The butterfly clasp clicks with a reassuring solid snap. Only needed to remove two links which took 5 minutes with the included guidance. Great value.',1,'approved',7,NULL,NULL,NULL,'2026-10-15 17:21:52'),
(29,20,NULL,NULL,NULL,'Arjun Singhania',NULL,5,'Exceeded all expectations — looks like a ₹15,000 Swiss piece','The finishing on the 316L steel links is shockingly good. Mirror chamfers catch the light beautifully and the sweeping hands are dead accurate. Came in a gorgeous magnetic hard-shell box with serial card.',1,'approved',18,'[\"https:\\/\\/images.unsplash.com\\/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80\"]','Thank you for your appreciation, Arjun! Enjoy the timepiece in good health.',NULL,'2026-10-15 17:21:52'),
(30,20,NULL,NULL,NULL,'Pooja Deshmukh',NULL,5,'Delicate, featherlight, and perfectly sized','Ordered via COD to Pune. Received it within 2 days in secure tamper-proof packaging. The dial has a subtle sunburst texture that looks stunning in daytime.',1,'approved',12,NULL,'We are delighted to hear you love the dial texture, Pooja! Welcome to the Welkar Atelier.',NULL,'2026-10-15 17:21:52'),
(31,20,NULL,NULL,NULL,'Rohan Mehta',NULL,4,'Substantial wrist presence, very comfortable clasp','The butterfly clasp clicks with a reassuring solid snap. Only needed to remove two links which took 5 minutes with the included guidance. Great value.',1,'approved',7,NULL,NULL,NULL,'2026-10-15 17:21:52'),
(32,21,NULL,NULL,NULL,'Arjun Singhania','/uploads/reviews/avatars/rev_avatars_1792243051_e485ae37.jpg',5,'Exceeded all expectations — looks like a ₹15,000 Swiss piece','The finishing on the 316L steel links is shockingly good. Mirror chamfers catch the light beautifully and the sweeping hands are dead accurate. Came in a gorgeous magnetic hard-shell box with serial card.',1,'approved',18,'[\"\\/uploads\\/reviews\\/wristshots\\/rev_wristshots_1792243051_6732ce4f.jpg\"]','Thank you for your appreciation, Arjun! Enjoy the timepiece in good health.',NULL,'2026-10-15 17:21:52'),
(33,21,NULL,NULL,NULL,'Pooja Deshmukh','/uploads/reviews/avatars/rev_avatars_1792243039_edac62d2.jpg',5,'Delicate, featherlight, and perfectly sized','Ordered via COD to Pune. Received it within 2 days in secure tamper-proof packaging. The dial has a subtle sunburst texture that looks stunning in daytime.',1,'approved',12,'[\"\\/uploads\\/reviews\\/wristshots\\/rev_wristshots_1792243039_e24a3efb.jpg\"]','We are delighted to hear you love the dial texture, Pooja! Welcome to the Welkar Atelier.',NULL,'2026-10-15 17:21:52'),
(34,21,NULL,NULL,NULL,'Rohan Mehta','/uploads/reviews/avatars/rev_avatars_1792243030_dc2a397f.jpg',4,'Substantial wrist presence, very comfortable clasp','The butterfly clasp clicks with a reassuring solid snap. Only needed to remove two links which took 5 minutes with the included guidance. Great value.',1,'approved',7,NULL,NULL,NULL,'2026-10-15 17:21:52'),
(35,3,NULL,NULL,NULL,'Kavita Chawla',NULL,5,'Exquisite finish and weight','The gold bezel and polished indices reflect light gorgeously. The butterfly clasp feels solid and secure on the wrist.',0,'approved',0,NULL,NULL,NULL,'2026-10-15 17:33:42'),
(37,3,11,12,NULL,'Harshvardhan K.','',5,'Exquisite finish and weight','Received this watch yesterday via Blue Dart. The sunburst dial and steel link finishing are stunning in person.',1,'approved',0,'[\"https:\\/\\/images.unsplash.com\\/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=600&q=80\"]',NULL,NULL,'2026-10-17 18:44:08');
INSERT INTO "role_permissions" VALUES (1,1),
(1,2),
(1,3),
(1,4),
(1,5),
(1,6),
(1,7),
(1,8),
(1,9),
(1,10),
(1,11),
(1,12),
(1,13),
(1,14),
(1,15),
(1,16),
(1,17),
(1,18),
(1,19),
(1,20),
(1,21),
(1,22),
(1,23);
INSERT INTO "roles" VALUES (1,'Owner','owner','Full administrative authority and system settings control','2026-10-12 16:57:26'),
(2,'Manager','manager','Operational management, orders, catalog and customer handling','2026-10-12 16:57:26'),
(3,'Catalog Manager','catalog_manager','Products, inventory, collections and bulk importer','2026-10-12 16:57:26'),
(4,'Marketing Manager','marketing_manager','Campaigns, banners, coupons and advertising reports','2026-10-12 16:57:26'),
(5,'Support Agent','support_agent','Order lookup, customer support and return reviews','2026-10-12 16:57:26'),
(6,'Analyst','analyst','Read-only business analytics and performance reports','2026-10-12 16:57:26');
INSERT INTO "store_settings" VALUES (1,'site_name','Welkar','general','2026-10-12 16:57:27'),
(2,'business_legal_name','Welkar','legal','2026-10-13 11:07:16'),
(3,'business_gstin','24LOJYK3087M','legal','2026-10-13 11:07:55'),
(4,'business_state','Gujarat','legal','2026-10-13 11:07:16'),
(5,'business_state_code','24','legal','2026-10-13 11:07:16'),
(6,'business_email','welkarstore@gmail.com','general','2026-10-12 16:57:27'),
(7,'business_phone','+91 98765 43210','general','2026-10-12 16:57:27'),
(8,'default_shipping_fee','150','shipping','2026-10-13 11:07:16'),
(9,'free_shipping_threshold','1499.00','shipping','2026-10-12 16:57:27'),
(10,'cod_enabled','1','payment','2026-10-12 16:57:27'),
(11,'cod_max_amount','5000.00','payment','2026-10-12 16:57:27'),
(12,'razorpay_mode','test','payment','2026-10-12 16:57:27'),
(13,'razorpay_key_id','rzp_test_placeholder_key','payment','2026-10-12 16:57:27'),
(14,'razorpay_key_secret','','payment','2026-10-12 16:57:27'),
(15,'default_gst_rate','18.00','tax','2026-10-12 16:57:27'),
(16,'invoice_prefix','WEL','invoice','2026-10-12 16:57:27');
INSERT INTO "support_messages" VALUES (1,1,'customer','Arjun Verma','I would like to request an exchange for dial color black to silver steel.','2026-10-18 16:14:41'),
(2,1,'system','Welkar Concierge Bot','Hello Arjun Verma, thank you for reaching out to Welkar Horological Support. Your ticket #TKT-2026-61845 regarding \'Return or Exchange Request\' has been logged. Our concierge support team is reviewing your inquiry and will update you directly here.','2026-10-18 16:14:41'),
(3,1,'customer','Arjun Verma','Also, can you please tell me how long the pickup will take?','2026-10-18 16:15:00'),
(4,1,'admin','Welkar Store Admin','Hello Arjun, we have arranged courier pickup for tomorrow. Please keep the watch in its original packaging.','2026-10-18 16:21:51');
INSERT INTO "support_tickets" VALUES (1,'TKT-2026-61845',NULL,NULL,'WEL-2026-1001','Arjun Verma','9876543210','arjun@example.com','return_exchange','Return or Exchange Request','in_progress','medium',NULL,'2026-10-18 16:21:51','2026-10-18 16:14:41','2026-10-18 16:21:51');
INSERT INTO "testimonials" VALUES (1,'Kabir Singhania','Mumbai, MH','Welkar has redefined accessible luxury watch shopping in India. The Aura watch on my wrist gets compliments in every boardroom meeting.',5,'Welkar Aura Minimalist Steel',1,1,'2026-10-12 16:57:27'),
(2,'Ananya Roy','Bengaluru, KA','Ordered the Celestial Moon Phase for my anniversary. The packaging, warranty card, and sheer dial detailing blew us away.',5,'Welkar Celestial Moon Phase',1,2,'2026-10-12 16:57:27'),
(3,'Sameer Khan','New Delhi, DL','Safe delivery, genuine invoice with GST breakdown, and clear tracking from order to doorstep. Excellent customer care.',5,'Welkar Chrono Heritage',1,3,'2026-10-12 16:57:27');
INSERT INTO "users" VALUES (1,'9999988888',NULL,NULL,'Test Buyer',1,0,'active','2026-10-12 17:13:54','2026-10-12 17:13:54'),
(2,'9999285660',NULL,NULL,'Test Buyer',1,0,'active','2026-10-12 17:14:55','2026-10-12 17:14:55'),
(3,'9999812649',NULL,NULL,'Test Buyer',1,0,'active','2026-10-12 17:17:32','2026-10-12 17:17:32'),
(4,'9999275854',NULL,NULL,'Test Buyer',1,0,'active','2026-10-12 17:37:59','2026-10-12 17:37:59'),
(5,'9999353224',NULL,NULL,'Test Buyer',1,0,'active','2026-10-12 17:47:20','2026-10-12 17:47:20'),
(6,'9999774483',NULL,NULL,'Test Buyer',1,0,'active','2026-10-12 17:57:04','2026-10-12 17:57:04'),
(7,'9999301523',NULL,NULL,'Test Buyer',1,0,'active','2026-10-12 18:15:12','2026-10-12 18:15:12'),
(8,'9999722284',NULL,NULL,'Test Buyer',1,0,'active','2026-10-12 18:37:33','2026-10-12 18:37:33'),
(9,'9999639282',NULL,NULL,'Test Buyer',1,0,'active','2026-10-12 18:38:23','2026-10-12 18:38:23'),
(10,'9999477659',NULL,NULL,'Test Buyer',1,0,'active','2026-10-12 18:39:44','2026-10-12 18:39:44'),
(11,'9999164076',NULL,NULL,'Test Buyer',1,0,'active','2026-10-12 19:14:05','2026-10-12 19:14:05'),
(12,'9999284334',NULL,NULL,'Test Buyer',1,0,'active','2026-10-12 19:30:22','2026-10-12 19:30:22'),
(13,'9999985943',NULL,NULL,'Test Buyer',1,0,'active','2026-10-12 19:36:35','2026-10-12 19:36:35'),
(14,'9999357922',NULL,NULL,'Test Buyer',1,0,'active','2026-10-12 19:37:53','2026-10-12 19:37:53'),
(15,'9999295716',NULL,NULL,'Test Buyer',1,0,'active','2026-10-12 19:43:27','2026-10-12 19:43:27'),
(16,'9999150644',NULL,NULL,'Test Buyer',1,0,'active','2026-10-13 09:24:43','2026-10-13 09:24:43'),
(17,'9999494762',NULL,NULL,'Test Buyer',1,0,'active','2026-10-13 09:26:41','2026-10-13 09:26:41'),
(18,'9999559018',NULL,NULL,'Test Buyer',1,0,'active','2026-10-13 09:39:53','2026-10-13 09:39:53'),
(19,'9999568560',NULL,NULL,'Test Buyer',1,0,'active','2026-10-13 09:49:34','2026-10-13 09:49:34'),
(20,'9999295815',NULL,NULL,'Test Buyer',1,0,'active','2026-10-13 10:40:12','2026-10-13 10:40:12'),
(21,'9999379252',NULL,NULL,'Test Buyer',1,0,'active','2026-10-13 10:48:03','2026-10-13 10:48:03'),
(22,'9999752850',NULL,NULL,'Test Buyer',1,0,'active','2026-10-13 10:48:37','2026-10-13 10:48:37'),
(23,'9999999823',NULL,NULL,'Test Buyer',1,0,'active','2026-10-13 10:50:58','2026-10-13 10:50:58'),
(24,'9999138211',NULL,NULL,'Test Buyer',1,0,'active','2026-10-13 10:51:51','2026-10-13 10:51:51'),
(25,'9999474776',NULL,NULL,'Test Buyer',1,0,'active','2026-10-13 11:03:48','2026-10-13 11:03:48'),
(26,'9999982305',NULL,NULL,'Test Buyer',1,0,'active','2026-10-13 11:37:53','2026-10-13 11:37:53'),
(27,'9999856096',NULL,NULL,'Test Buyer',1,0,'active','2026-10-13 11:44:52','2026-10-13 11:44:52'),
(28,'9999168002',NULL,NULL,'Test Buyer',1,0,'active','2026-10-13 11:54:13','2026-10-13 11:54:13'),
(29,'9999300888',NULL,NULL,'Test Buyer',1,0,'active','2026-10-13 12:00:35','2026-10-13 12:00:35'),
(30,'9999628748',NULL,NULL,'Test Buyer',1,0,'active','2026-10-13 12:10:44','2026-10-13 12:10:44'),
(31,'9999931876',NULL,NULL,'Test Buyer',1,0,'active','2026-10-13 12:22:26','2026-10-13 12:22:26'),
(32,'9999400102',NULL,NULL,'Test Buyer',1,0,'active','2026-10-13 12:24:48','2026-10-13 12:24:48'),
(33,'9999679620',NULL,NULL,'Test Buyer',1,0,'active','2026-10-13 12:37:22','2026-10-13 12:37:22'),
(34,'9999364294',NULL,NULL,'Test Buyer',1,0,'active','2026-10-13 15:09:57','2026-10-13 15:09:57');
INSERT INTO "wishlists" VALUES (1,NULL,'dbdfd791332d6ab06ae5a2a611e0f5a7','2026-10-15 16:18:52'),
(2,NULL,'tvviniqa8tnc4ulv9258n9jinn','2026-10-15 16:19:59'),
(3,NULL,'td3tijoet4bgrfrn38g6qdq9m6','2026-10-15 16:20:28'),
(4,NULL,'0qgjt9de61f1vocrp5s1ecvqt5','2026-10-15 16:29:16'),
(5,NULL,'3v4n9k1d3fkci10fq7s2hds828','2026-10-15 16:31:17'),
(6,NULL,'5d97afb8dce42d218291bd7dfaa2819b','2026-10-15 16:39:36'),
(7,NULL,'mg1b4ubs4ugahtelnnad54lgbd','2026-10-15 16:59:24'),
(8,NULL,'2vfku3s7g35t8i06mrrq4b9vif','2026-10-15 16:59:31'),
(9,NULL,'psknkfg2ki7a48lm1qepvojp45','2026-10-15 16:59:37'),
(10,NULL,'vjuhmhk2jl3q9vbf26rfq0k6m0','2026-10-15 16:59:48'),
(11,NULL,'stfq5r6nt172gick1nkhvt1oh7','2026-10-15 16:59:54'),
(12,NULL,'euj6tnl2ci6jb3boploq08iplh','2026-10-15 17:00:01'),
(13,NULL,'b91fde279fb8914bc02869d06f42c9a1','2026-10-15 17:00:09'),
(14,NULL,'3s3f15eo044upvgeso4kigs80r','2026-10-15 17:26:50'),
(15,NULL,'9v60jju4f38r6bj12383oaf1g8','2026-10-15 17:28:06'),
(16,NULL,'i1oq2hs2k5s58ff7q6c06nilha','2026-10-15 17:28:39'),
(17,NULL,'2gl0fu7qtsefah15ed6ulmf7v0','2026-10-15 17:31:42'),
(18,NULL,'n87r44j2n2bk4gug01tmj1fek1','2026-10-15 17:33:15'),
(19,NULL,'scmdh2pco97em0hgqpnst7s9e0','2026-10-15 17:33:42'),
(20,NULL,'a3d70da0657fa52922b179b09d774800','2026-10-15 17:33:49'),
(21,NULL,'84b1cdc38fe9800364a30364d027f0db','2026-10-15 18:55:33'),
(22,NULL,'o183u3no73nss09jc2o5pjifct','2026-10-15 18:56:21'),
(23,NULL,'m7de6hm4uksbthelk9b4esoor4','2026-10-15 18:56:53'),
(24,NULL,'b73rkrt52t7ikfmtrlipn527vf','2026-10-15 18:58:06'),
(25,NULL,'b11fbcfd593baeef452b033ddec3b3bb','2026-10-15 23:19:11'),
(26,NULL,'tjglpju17r5sss1ieqqdkqqlii','2026-10-15 23:19:33'),
(27,NULL,'rmner3icafau1aomutuc7s0jq2','2026-10-15 23:22:26'),
(28,NULL,'b801e58fd67538c65ac6cb14ca82286f','2026-10-15 23:41:28'),
(29,NULL,'qkennjjcovmlu13omqojdnhvfd','2026-10-15 23:43:50'),
(30,NULL,'aa1b801a378013cc6cb5a5c7881cf4c2','2026-10-15 23:52:16'),
(31,NULL,'2dn9op3e2ah06ted3uchspb594','2026-10-15 23:52:48'),
(32,NULL,'u12r32u4lfehnu3gagvc3qd59e','2026-10-15 23:52:59'),
(33,NULL,'sl6g94va7na4lhjnuckibfodgp','2026-10-15 23:53:09'),
(34,NULL,'8fapsokvu52a90tjee8dsf7819','2026-10-15 23:53:21'),
(35,NULL,'p2iip6nvjoup3aoqq9k2367d8g','2026-10-15 23:53:36'),
(36,NULL,'jv64kbr1vq3je96kppenirrq9s','2026-10-15 23:54:01'),
(37,NULL,'f75nififmt29aqk0qv08n0ufqu','2026-10-15 23:54:11'),
(38,NULL,'t9r2vft92kttfb4eibjchingko','2026-10-15 23:55:02'),
(39,NULL,'c8e88a0733ada998b94152f434304579','2026-10-15 23:55:39'),
(40,NULL,'3cd4hnl3veil7up0ccfjkflq39','2026-10-16 00:09:21'),
(41,NULL,'a35ibl2lfpmvps6ehfkdj5q098','2026-10-16 00:13:03'),
(42,NULL,'9cbde38c2f042f42822735f27e876785','2026-10-16 00:13:10'),
(43,NULL,'76d925674bd33929a119e21a5d4a7c4f','2026-10-16 10:18:26'),
(44,NULL,'28dvnkvhcehfdo28ctbmgp4lv9','2026-10-16 10:19:11'),
(45,NULL,'qponajbnluqrod4qv9gcfqedu5','2026-10-16 10:20:05'),
(46,NULL,'rs8bthoneu1j49idh0it3ief97','2026-10-16 10:34:16'),
(47,NULL,'0820a2eadf7a5409ff59161b954c5dc7','2026-10-16 10:34:23'),
(48,NULL,'81179878433d597129a5de23aa9f480e','2026-10-16 11:48:11'),
(49,NULL,'6atm1si6u49i2t0knotsqh9r0v','2026-10-16 11:48:49'),
(50,NULL,'v1ldi65tf03jrku4e6drptbf82','2026-10-16 11:48:49'),
(51,NULL,'229ipg8rs7p1gkd3jqj0i1ul5k','2026-10-16 11:49:00'),
(52,NULL,'mk0b5d62sgv0bod1musi7eijrs','2026-10-16 11:49:00'),
(53,NULL,'m9lo5trj3j2r5dohv4v3b08jce','2026-10-16 11:49:11'),
(54,NULL,'eu60aa2ekn1kbrlfv20ovu04m4','2026-10-16 11:49:11'),
(55,NULL,'anvokrjtd5pf6qojjfvkf0eiga','2026-10-16 11:49:16'),
(56,NULL,'rep33co1ioja27t84earnmr7an','2026-10-16 11:49:16'),
(57,NULL,'heqi8t6kpm0qqnai32n7dac3gc','2026-10-16 11:50:46'),
(58,NULL,'or3tqkafi770j5kvqagkgdgs1k','2026-10-16 11:51:24'),
(59,NULL,'emukhprabijg2igo09pus1b4b4','2026-10-16 11:51:24'),
(60,NULL,'705365c2f60fdae3294b180151cfb7e8','2026-10-16 15:47:52'),
(61,NULL,'vmu1u2jc688bccc1kib2rvb6pq','2026-10-16 15:48:24'),
(62,NULL,'i42gfdj7o0pdqu0ff9tho82kbl','2026-10-16 16:02:19'),
(63,NULL,'abcde911fa689fb4b65eac4cd5d54d05','2026-10-16 16:19:46'),
(64,NULL,'9e68d6bb2821104f3f032851482297c2','2026-10-16 16:27:12'),
(65,NULL,'fhtnrm54hbdgnrrdftnbkcamkc','2026-10-16 16:45:44'),
(66,NULL,'374e351f1be3005c38731ffce51e39a3','2026-10-16 17:32:23'),
(67,NULL,'b6c9bb8b41b34efa8012fe9b7bd36f68','2026-10-16 17:50:20'),
(68,NULL,'04e559b9e912bd1b90107db6e42e75cf','2026-10-16 17:51:11'),
(69,NULL,'e639761bb2d4d8e99149264377306667','2026-10-16 17:51:44'),
(70,NULL,'c18fa389c5d7a1670bcbf19b700ab22d','2026-10-16 17:53:12'),
(71,NULL,'4926f4ef1dc62dc6aa132544cda2693f','2026-10-16 18:04:43'),
(72,NULL,'f4c209aade84f1d73b1fb656f1d3bb2a','2026-10-16 18:09:30'),
(73,NULL,'ec6c3e4ca1ba446c9ed440ef7417d64f','2026-10-16 18:13:10'),
(74,NULL,'lan8bidl2lomj09sisoblv38h1','2026-10-16 18:14:46'),
(75,NULL,'0bd3e6069bd31f5709ce4068bff4d1af','2026-10-16 19:13:19'),
(76,NULL,'vcob9jkp5ho45s6ub7drn29via','2026-10-16 19:17:01'),
(77,NULL,'bf7cbefcf5eeaa300a21b31a4d08391f','2026-10-16 19:25:50'),
(78,NULL,'412d85ebfc114d83a02a5a61155af710','2026-10-16 19:26:57'),
(79,NULL,'ee0e70cd3678c5c78eab657c28e73e19','2026-10-16 19:30:11'),
(80,NULL,'259c5e9f1a2460f3a8bcfc6cc805bb22','2026-10-16 19:31:29'),
(81,NULL,'0ce51f32f03cddd682bda8ba5cc8152b','2026-10-17 10:05:05'),
(82,NULL,'47jbp7nnhk0ch9cohp61keevk4','2026-10-17 10:05:11'),
(83,NULL,'5tvbp702u9dq02d2kpihkisc4q','2026-10-17 10:05:42'),
(84,NULL,'8290a6d7bef725c95ce3343dd9d758f2','2026-10-17 10:17:31'),
(85,NULL,'ht6sqh5hqgbds7hehpubvd0t4n','2026-10-17 10:17:43'),
(86,NULL,'e0jojbb1pp466abcq1mjj96j09','2026-10-17 10:17:54'),
(87,NULL,'nf8kcn2df8sq76nj1gombuqq3k','2026-10-17 10:18:16'),
(88,NULL,'5b14b0accbcdb7c5bd8e43e5a56c6cc4','2026-10-17 12:42:30'),
(89,NULL,'htik8vv7h8sq7obkpe6jjul8ug','2026-10-17 12:42:52'),
(90,NULL,'84c072fe4ccd5a6e9256e973570ce023','2026-10-17 12:44:35'),
(91,NULL,'el5gfmcn5lil76ke5pbpk58hg2','2026-10-17 12:47:20'),
(92,NULL,'a01c32074fb699bcc4ace24bfe06eef0','2026-10-17 12:53:46'),
(93,NULL,'696qci3f8g46bv1kl9kk0bv78a','2026-10-17 12:54:24'),
(94,NULL,'313f3084acf0bcc58ab5f422a5565999','2026-10-17 13:00:24'),
(95,NULL,'8889d4f4435d2111b1dbecfce6f612be','2026-10-17 13:08:51'),
(96,NULL,'46bdf600e84405eed681b9d2a0138d18','2026-10-17 13:09:52'),
(97,NULL,'vop2cr4l1npunvnp5s3q6s4g5s','2026-10-17 13:10:50'),
(98,NULL,'524rgg1271b8gc9jd51is0ejdr','2026-10-17 13:10:56'),
(99,NULL,'n182i853jakigjrpgfpr581bqm','2026-10-17 13:11:03'),
(100,NULL,'nnn7m0m5nog2lvqbbeck4k65c9','2026-10-17 16:09:36'),
(101,NULL,'o1i6u9j7f341glqob1i41ng5vr','2026-10-17 16:10:22'),
(102,NULL,'le0sahfd2l7ionqpe9njavpu6f','2026-10-17 16:10:44'),
(103,NULL,'0j0npc0k39qjk0sq8qq6bajn17','2026-10-17 16:11:07'),
(104,NULL,'mf343bfpb1g007egimf0jijg4d','2026-10-17 16:24:32'),
(105,NULL,'0uacqnp64jhtsf2qfu85ahvujf','2026-10-17 16:24:48'),
(106,NULL,'1fk9oc4dj67sljhok9aj3ge28b','2026-10-17 16:29:55'),
(107,NULL,'5jdgfbjtqebhhpo6c4v0gma7u2','2026-10-17 16:46:38'),
(108,NULL,'mtuu2a4isci06eakvvqaracr9q','2026-10-17 16:46:47'),
(109,NULL,'8sdkst06o25l1ragsoc6hki2q8','2026-10-17 17:44:28'),
(110,NULL,'b747fgkdgmimj8qih9v14r4pov','2026-10-17 17:44:36'),
(111,NULL,'j9nts44qsint121e6cpfferggl','2026-10-17 17:45:46'),
(112,NULL,'tmmajpcg0e266v06970v140n3r','2026-10-17 18:05:56'),
(113,NULL,'7i5f4n31vue7cn76re3bk5du88','2026-10-17 18:06:06'),
(114,NULL,'479amq0ardvg6mn1ku1eesjq5k','2026-10-17 18:12:03'),
(115,NULL,'630ovq395vo87e5jh6pgou4cq7','2026-10-17 18:44:34'),
(116,NULL,'9ncs4s74obg6juocbmc0n422hs','2026-10-17 18:44:35'),
(117,NULL,'04cc8d3a6c498e711a10d7620aa2a62b','2026-10-17 21:20:27'),
(118,NULL,'d9ead14bbfe537ce4b9b12ab3172455c','2026-10-17 21:32:10'),
(119,NULL,'37bc89076cf7551d1d4d50e5e54a0f5c','2026-10-17 21:34:56'),
(120,NULL,'m690ceapc9dgdj4ak8g0b8hmau','2026-10-18 11:23:58'),
(121,NULL,'effaee2108355aecfd87ea765e0ced9f','2026-10-18 14:29:05'),
(122,NULL,'2f488d8232f6ec27a3831b35bdb6cf91','2026-10-18 15:06:04'),
(123,NULL,'13d7cf7ff41433f53dbaf4770dd32149','2026-10-18 16:11:01'),
(124,NULL,'14ad982aea0288426751c1291ff50628','2026-10-18 16:12:13'),
(125,NULL,'0012745fa18abd6fcb7be59848d5c77f','2026-10-18 16:14:26'),
(126,NULL,'fa94eb25a748becd3171f0f87b257fc4','2026-10-18 16:22:31'),
(127,NULL,'c29801031ac25dc636dac2dbd659a4e2','2026-10-18 16:50:39'),
(128,NULL,'3606531375703dc6f52e727cb067c09f','2026-10-18 17:16:57'),
(129,NULL,'0cea9b535fb54286c78ce8d5530d3594','2026-10-18 17:21:35'),
(130,NULL,'fc7a176321bf52bf3b08c2c3df5b4b88','2026-10-18 18:03:01'),
(131,NULL,'3b347bc59e8b11dccbfafc5c6a2da093','2026-10-18 18:12:50'),
(132,NULL,'dc090cf89fc012adba1bd3c10f6435a8','2026-10-18 19:12:05'),
(133,NULL,'2996b5318d895ef5827caccc34b46fac','2026-10-18 19:13:12'),
(134,NULL,'3fa8f0d0c0cdfc1a04f7888e003f271d','2026-10-18 19:46:20'),
(135,NULL,'7f3cc2dc40220556bacfb1cfb87a6927','2026-10-18 19:55:12'),
(136,NULL,'ca552e7516dfef524ba276e8370e4814','2026-10-18 20:00:15');

-- 3. Synchronize Serial Primary Key Sequences
SELECT setval(pg_get_serial_sequence('"abandoned_cart_reminders"', 'id'), COALESCE(MAX(id), 1)) FROM "abandoned_cart_reminders";
SELECT setval(pg_get_serial_sequence('"ad_integrations"', 'id'), COALESCE(MAX(id), 1)) FROM "ad_integrations";
SELECT setval(pg_get_serial_sequence('"admin_mfa_challenges"', 'id'), COALESCE(MAX(id), 1)) FROM "admin_mfa_challenges";
SELECT setval(pg_get_serial_sequence('"admin_users"', 'id'), COALESCE(MAX(id), 1)) FROM "admin_users";
SELECT setval(pg_get_serial_sequence('"analytics_events"', 'id'), COALESCE(MAX(id), 1)) FROM "analytics_events";
SELECT setval(pg_get_serial_sequence('"analytics_integrations"', 'id'), COALESCE(MAX(id), 1)) FROM "analytics_integrations";
SELECT setval(pg_get_serial_sequence('"audit_logs"', 'id'), COALESCE(MAX(id), 1)) FROM "audit_logs";
SELECT setval(pg_get_serial_sequence('"banner_translations"', 'id'), COALESCE(MAX(id), 1)) FROM "banner_translations";
SELECT setval(pg_get_serial_sequence('"banners"', 'id'), COALESCE(MAX(id), 1)) FROM "banners";
SELECT setval(pg_get_serial_sequence('"brands"', 'id'), COALESCE(MAX(id), 1)) FROM "brands";
SELECT setval(pg_get_serial_sequence('"campaigns"', 'id'), COALESCE(MAX(id), 1)) FROM "campaigns";
SELECT setval(pg_get_serial_sequence('"cart_events"', 'id'), COALESCE(MAX(id), 1)) FROM "cart_events";
SELECT setval(pg_get_serial_sequence('"cart_items"', 'id'), COALESCE(MAX(id), 1)) FROM "cart_items";
SELECT setval(pg_get_serial_sequence('"carts"', 'id'), COALESCE(MAX(id), 1)) FROM "carts";
SELECT setval(pg_get_serial_sequence('"categories"', 'id'), COALESCE(MAX(id), 1)) FROM "categories";
SELECT setval(pg_get_serial_sequence('"collections"', 'id'), COALESCE(MAX(id), 1)) FROM "collections";
SELECT setval(pg_get_serial_sequence('"company_founders"', 'id'), COALESCE(MAX(id), 1)) FROM "company_founders";
SELECT setval(pg_get_serial_sequence('"company_milestones"', 'id'), COALESCE(MAX(id), 1)) FROM "company_milestones";
SELECT setval(pg_get_serial_sequence('"company_values"', 'id'), COALESCE(MAX(id), 1)) FROM "company_values";
SELECT setval(pg_get_serial_sequence('"consent_records"', 'id'), COALESCE(MAX(id), 1)) FROM "consent_records";
SELECT setval(pg_get_serial_sequence('"content_pages"', 'id'), COALESCE(MAX(id), 1)) FROM "content_pages";
SELECT setval(pg_get_serial_sequence('"coupon_redemptions"', 'id'), COALESCE(MAX(id), 1)) FROM "coupon_redemptions";
SELECT setval(pg_get_serial_sequence('"coupons"', 'id'), COALESCE(MAX(id), 1)) FROM "coupons";
SELECT setval(pg_get_serial_sequence('"credit_notes"', 'id'), COALESCE(MAX(id), 1)) FROM "credit_notes";
SELECT setval(pg_get_serial_sequence('"customer_addresses"', 'id'), COALESCE(MAX(id), 1)) FROM "customer_addresses";
SELECT setval(pg_get_serial_sequence('"customer_segments"', 'id'), COALESCE(MAX(id), 1)) FROM "customer_segments";
SELECT setval(pg_get_serial_sequence('"daily_analytics_summaries"', 'id'), COALESCE(MAX(id), 1)) FROM "daily_analytics_summaries";
SELECT setval(pg_get_serial_sequence('"import_job_rows"', 'id'), COALESCE(MAX(id), 1)) FROM "import_job_rows";
SELECT setval(pg_get_serial_sequence('"import_jobs"', 'id'), COALESCE(MAX(id), 1)) FROM "import_jobs";
SELECT setval(pg_get_serial_sequence('"inventory_locations"', 'id'), COALESCE(MAX(id), 1)) FROM "inventory_locations";
SELECT setval(pg_get_serial_sequence('"inventory_movements"', 'id'), COALESCE(MAX(id), 1)) FROM "inventory_movements";
SELECT setval(pg_get_serial_sequence('"inventory_reservations"', 'id'), COALESCE(MAX(id), 1)) FROM "inventory_reservations";
SELECT setval(pg_get_serial_sequence('"inventory_stock"', 'id'), COALESCE(MAX(id), 1)) FROM "inventory_stock";
SELECT setval(pg_get_serial_sequence('"invoice_sequences"', 'id'), COALESCE(MAX(id), 1)) FROM "invoice_sequences";
SELECT setval(pg_get_serial_sequence('"invoices"', 'id'), COALESCE(MAX(id), 1)) FROM "invoices";
SELECT setval(pg_get_serial_sequence('"notification_logs"', 'id'), COALESCE(MAX(id), 1)) FROM "notification_logs";
SELECT setval(pg_get_serial_sequence('"order_addresses"', 'id'), COALESCE(MAX(id), 1)) FROM "order_addresses";
SELECT setval(pg_get_serial_sequence('"order_items"', 'id'), COALESCE(MAX(id), 1)) FROM "order_items";
SELECT setval(pg_get_serial_sequence('"order_status_history"', 'id'), COALESCE(MAX(id), 1)) FROM "order_status_history";
SELECT setval(pg_get_serial_sequence('"orders"', 'id'), COALESCE(MAX(id), 1)) FROM "orders";
SELECT setval(pg_get_serial_sequence('"otp_challenges"', 'id'), COALESCE(MAX(id), 1)) FROM "otp_challenges";
SELECT setval(pg_get_serial_sequence('"payment_events"', 'id'), COALESCE(MAX(id), 1)) FROM "payment_events";
SELECT setval(pg_get_serial_sequence('"payments"', 'id'), COALESCE(MAX(id), 1)) FROM "payments";
SELECT setval(pg_get_serial_sequence('"permissions"', 'id'), COALESCE(MAX(id), 1)) FROM "permissions";
SELECT setval(pg_get_serial_sequence('"product_comparison_rows"', 'id'), COALESCE(MAX(id), 1)) FROM "product_comparison_rows";
SELECT setval(pg_get_serial_sequence('"product_comparison_values"', 'id'), COALESCE(MAX(id), 1)) FROM "product_comparison_values";
SELECT setval(pg_get_serial_sequence('"product_comparisons"', 'id'), COALESCE(MAX(id), 1)) FROM "product_comparisons";
SELECT setval(pg_get_serial_sequence('"product_faqs"', 'id'), COALESCE(MAX(id), 1)) FROM "product_faqs";
SELECT setval(pg_get_serial_sequence('"product_hotspots"', 'id'), COALESCE(MAX(id), 1)) FROM "product_hotspots";
SELECT setval(pg_get_serial_sequence('"product_images"', 'id'), COALESCE(MAX(id), 1)) FROM "product_images";
SELECT setval(pg_get_serial_sequence('"product_page_modules"', 'id'), COALESCE(MAX(id), 1)) FROM "product_page_modules";
SELECT setval(pg_get_serial_sequence('"product_specifications"', 'id'), COALESCE(MAX(id), 1)) FROM "product_specifications";
SELECT setval(pg_get_serial_sequence('"product_translations"', 'id'), COALESCE(MAX(id), 1)) FROM "product_translations";
SELECT setval(pg_get_serial_sequence('"product_variants"', 'id'), COALESCE(MAX(id), 1)) FROM "product_variants";
SELECT setval(pg_get_serial_sequence('"products"', 'id'), COALESCE(MAX(id), 1)) FROM "products";
SELECT setval(pg_get_serial_sequence('"refunds"', 'id'), COALESCE(MAX(id), 1)) FROM "refunds";
SELECT setval(pg_get_serial_sequence('"return_items"', 'id'), COALESCE(MAX(id), 1)) FROM "return_items";
SELECT setval(pg_get_serial_sequence('"returns"', 'id'), COALESCE(MAX(id), 1)) FROM "returns";
SELECT setval(pg_get_serial_sequence('"review_votes"', 'id'), COALESCE(MAX(id), 1)) FROM "review_votes";
SELECT setval(pg_get_serial_sequence('"reviews"', 'id'), COALESCE(MAX(id), 1)) FROM "reviews";
SELECT setval(pg_get_serial_sequence('"roles"', 'id'), COALESCE(MAX(id), 1)) FROM "roles";
SELECT setval(pg_get_serial_sequence('"shipping_shipments"', 'id'), COALESCE(MAX(id), 1)) FROM "shipping_shipments";
SELECT setval(pg_get_serial_sequence('"store_settings"', 'id'), COALESCE(MAX(id), 1)) FROM "store_settings";
SELECT setval(pg_get_serial_sequence('"support_messages"', 'id'), COALESCE(MAX(id), 1)) FROM "support_messages";
SELECT setval(pg_get_serial_sequence('"support_tickets"', 'id'), COALESCE(MAX(id), 1)) FROM "support_tickets";
SELECT setval(pg_get_serial_sequence('"system_logs"', 'id'), COALESCE(MAX(id), 1)) FROM "system_logs";
SELECT setval(pg_get_serial_sequence('"testimonials"', 'id'), COALESCE(MAX(id), 1)) FROM "testimonials";
SELECT setval(pg_get_serial_sequence('"users"', 'id'), COALESCE(MAX(id), 1)) FROM "users";
SELECT setval(pg_get_serial_sequence('"utm_sessions"', 'id'), COALESCE(MAX(id), 1)) FROM "utm_sessions";
SELECT setval(pg_get_serial_sequence('"webhook_events"', 'id'), COALESCE(MAX(id), 1)) FROM "webhook_events";
SELECT setval(pg_get_serial_sequence('"wishlist_items"', 'id'), COALESCE(MAX(id), 1)) FROM "wishlist_items";
SELECT setval(pg_get_serial_sequence('"wishlists"', 'id'), COALESCE(MAX(id), 1)) FROM "wishlists";

-- 4. Secondary Indexes
CREATE INDEX "idx_abandoned_cart_reminders_fk_acr_cart" ON "abandoned_cart_reminders" ("cart_id");
CREATE INDEX "idx_admin_mfa_challenges_fk_mfa_admin" ON "admin_mfa_challenges" ("admin_user_id");
CREATE INDEX "idx_admin_user_roles_fk_aur_role" ON "admin_user_roles" ("role_id");
CREATE INDEX "idx_analytics_events_idx_ae_name" ON "analytics_events" ("event_name");
CREATE INDEX "idx_analytics_events_idx_ae_sess" ON "analytics_events" ("session_id");
CREATE INDEX "idx_analytics_events_idx_ae_created" ON "analytics_events" ("created_at");
CREATE INDEX "idx_audit_logs_idx_al_action" ON "audit_logs" ("action");
CREATE INDEX "idx_audit_logs_idx_al_entity" ON "audit_logs" ("entity_type","entity_id");
CREATE INDEX "idx_cart_items_fk_ci_var" ON "cart_items" ("variant_id");
CREATE INDEX "idx_carts_idx_cart_session" ON "carts" ("session_id");
CREATE INDEX "idx_carts_idx_cart_user" ON "carts" ("user_id");
CREATE INDEX "idx_categories_fk_cat_parent" ON "categories" ("parent_id");
CREATE INDEX "idx_coupon_redemptions_fk_cr_coupon" ON "coupon_redemptions" ("coupon_id");
CREATE INDEX "idx_credit_notes_fk_cn_inv" ON "credit_notes" ("invoice_id");
CREATE INDEX "idx_customer_addresses_idx_addr_user" ON "customer_addresses" ("user_id");
CREATE INDEX "idx_import_job_rows_fk_ijr_job" ON "import_job_rows" ("import_job_id");
CREATE INDEX "idx_inventory_movements_fk_mov_var" ON "inventory_movements" ("variant_id");
CREATE INDEX "idx_inventory_reservations_idx_res_exp" ON "inventory_reservations" ("expires_at","status");
CREATE INDEX "idx_inventory_reservations_fk_res_var" ON "inventory_reservations" ("variant_id");
CREATE INDEX "idx_inventory_stock_fk_stock_loc" ON "inventory_stock" ("location_id");
CREATE INDEX "idx_order_addresses_fk_oa_order" ON "order_addresses" ("order_id");
CREATE INDEX "idx_order_items_fk_oi_order" ON "order_items" ("order_id");
CREATE INDEX "idx_order_status_history_fk_osh_order" ON "order_status_history" ("order_id");
CREATE INDEX "idx_orders_idx_order_user" ON "orders" ("user_id");
CREATE INDEX "idx_orders_idx_order_status" ON "orders" ("status");
CREATE INDEX "idx_otp_challenges_idx_otp_ident" ON "otp_challenges" ("identifier","type");
CREATE INDEX "idx_payment_events_fk_pe_pay" ON "payment_events" ("payment_id");
CREATE INDEX "idx_payments_idx_pay_order" ON "payments" ("order_id");
CREATE INDEX "idx_product_categories_fk_pc_cat" ON "product_categories" ("category_id");
CREATE INDEX "idx_product_collections_fk_pcoll_coll" ON "product_collections" ("collection_id");
CREATE INDEX "idx_product_comparison_values_fk_pcv_row" ON "product_comparison_values" ("row_id");
CREATE INDEX "idx_product_comparisons_fk_cmp_prod" ON "product_comparisons" ("product_id");
CREATE INDEX "idx_product_faqs_idx_pfaq_prod" ON "product_faqs" ("product_id");
CREATE INDEX "idx_product_hotspots_idx_ph_prod" ON "product_hotspots" ("product_id");
CREATE INDEX "idx_product_images_fk_img_prod" ON "product_images" ("product_id");
CREATE INDEX "idx_product_images_fk_img_var" ON "product_images" ("variant_id");
CREATE INDEX "idx_product_page_modules_idx_ppm_prod" ON "product_page_modules" ("product_id","is_enabled","sort_order");
CREATE INDEX "idx_product_specifications_fk_spec_prod" ON "product_specifications" ("product_id");
CREATE INDEX "idx_product_variants_fk_var_prod" ON "product_variants" ("product_id");
CREATE INDEX "idx_products_idx_prod_brand" ON "products" ("brand_id");
CREATE INDEX "idx_products_idx_prod_dial" ON "products" ("dial_type");
CREATE INDEX "idx_products_idx_prod_gender" ON "products" ("gender");
CREATE INDEX "idx_refunds_fk_ref_order" ON "refunds" ("order_id");
CREATE INDEX "idx_return_items_fk_ri_ret" ON "return_items" ("return_id");
CREATE INDEX "idx_returns_fk_ret_order" ON "returns" ("order_id");
CREATE INDEX "idx_reviews_idx_rev_prod" ON "reviews" ("product_id");
CREATE INDEX "idx_role_permissions_fk_rp_perm" ON "role_permissions" ("permission_id");
CREATE INDEX "idx_shipping_shipments_fk_ship_order" ON "shipping_shipments" ("order_id");
CREATE INDEX "idx_support_messages_idx_ticket" ON "support_messages" ("ticket_id");
CREATE INDEX "idx_support_tickets_idx_status" ON "support_tickets" ("status");
CREATE INDEX "idx_support_tickets_idx_phone" ON "support_tickets" ("customer_phone");
CREATE INDEX "idx_support_tickets_idx_order" ON "support_tickets" ("order_number");
CREATE INDEX "idx_users_idx_users_phone" ON "users" ("phone");
CREATE INDEX "idx_users_idx_users_email" ON "users" ("email");
CREATE INDEX "idx_utm_sessions_idx_utms_sess" ON "utm_sessions" ("session_id");
CREATE INDEX "idx_wishlist_items_fk_wli_prod" ON "wishlist_items" ("product_id");
CREATE INDEX "idx_wishlists_idx_wl_user" ON "wishlists" ("user_id");
CREATE INDEX "idx_wishlists_idx_wl_session" ON "wishlists" ("session_id");

-- 5. Foreign Key Constraints
ALTER TABLE "abandoned_cart_reminders" ADD CONSTRAINT "fk_acr_cart" FOREIGN KEY ("cart_id") REFERENCES "carts" ("id") ON DELETE CASCADE;
ALTER TABLE "admin_mfa_challenges" ADD CONSTRAINT "fk_mfa_admin" FOREIGN KEY ("admin_user_id") REFERENCES "admin_users" ("id") ON DELETE CASCADE;
ALTER TABLE "admin_user_roles" ADD CONSTRAINT "fk_aur_admin" FOREIGN KEY ("admin_user_id") REFERENCES "admin_users" ("id") ON DELETE CASCADE;
ALTER TABLE "admin_user_roles" ADD CONSTRAINT "fk_aur_role" FOREIGN KEY ("role_id") REFERENCES "roles" ("id") ON DELETE CASCADE;
ALTER TABLE "banner_translations" ADD CONSTRAINT "fk_bt_banner" FOREIGN KEY ("banner_id") REFERENCES "banners" ("id") ON DELETE CASCADE;
ALTER TABLE "cart_items" ADD CONSTRAINT "fk_ci_cart" FOREIGN KEY ("cart_id") REFERENCES "carts" ("id") ON DELETE CASCADE;
ALTER TABLE "cart_items" ADD CONSTRAINT "fk_ci_var" FOREIGN KEY ("variant_id") REFERENCES "product_variants" ("id") ON DELETE CASCADE;
ALTER TABLE "categories" ADD CONSTRAINT "fk_cat_parent" FOREIGN KEY ("parent_id") REFERENCES "categories" ("id") ON DELETE SET NULL;
ALTER TABLE "coupon_redemptions" ADD CONSTRAINT "fk_cr_coupon" FOREIGN KEY ("coupon_id") REFERENCES "coupons" ("id") ON DELETE CASCADE;
ALTER TABLE "credit_notes" ADD CONSTRAINT "fk_cn_inv" FOREIGN KEY ("invoice_id") REFERENCES "invoices" ("id") ON DELETE CASCADE;
ALTER TABLE "customer_addresses" ADD CONSTRAINT "fk_addr_user" FOREIGN KEY ("user_id") REFERENCES "users" ("id") ON DELETE CASCADE;
ALTER TABLE "import_job_rows" ADD CONSTRAINT "fk_ijr_job" FOREIGN KEY ("import_job_id") REFERENCES "import_jobs" ("id") ON DELETE CASCADE;
ALTER TABLE "inventory_movements" ADD CONSTRAINT "fk_mov_var" FOREIGN KEY ("variant_id") REFERENCES "product_variants" ("id") ON DELETE CASCADE;
ALTER TABLE "inventory_reservations" ADD CONSTRAINT "fk_res_var" FOREIGN KEY ("variant_id") REFERENCES "product_variants" ("id") ON DELETE CASCADE;
ALTER TABLE "inventory_stock" ADD CONSTRAINT "fk_stock_loc" FOREIGN KEY ("location_id") REFERENCES "inventory_locations" ("id") ON DELETE CASCADE;
ALTER TABLE "inventory_stock" ADD CONSTRAINT "fk_stock_var" FOREIGN KEY ("variant_id") REFERENCES "product_variants" ("id") ON DELETE CASCADE;
ALTER TABLE "invoices" ADD CONSTRAINT "fk_inv_order" FOREIGN KEY ("order_id") REFERENCES "orders" ("id") ON DELETE CASCADE;
ALTER TABLE "order_addresses" ADD CONSTRAINT "fk_oa_order" FOREIGN KEY ("order_id") REFERENCES "orders" ("id") ON DELETE CASCADE;
ALTER TABLE "order_items" ADD CONSTRAINT "fk_oi_order" FOREIGN KEY ("order_id") REFERENCES "orders" ("id") ON DELETE CASCADE;
ALTER TABLE "order_status_history" ADD CONSTRAINT "fk_osh_order" FOREIGN KEY ("order_id") REFERENCES "orders" ("id") ON DELETE CASCADE;
ALTER TABLE "payment_events" ADD CONSTRAINT "fk_pe_pay" FOREIGN KEY ("payment_id") REFERENCES "payments" ("id") ON DELETE CASCADE;
ALTER TABLE "payments" ADD CONSTRAINT "fk_pay_order" FOREIGN KEY ("order_id") REFERENCES "orders" ("id") ON DELETE CASCADE;
ALTER TABLE "product_categories" ADD CONSTRAINT "fk_pc_cat" FOREIGN KEY ("category_id") REFERENCES "categories" ("id") ON DELETE CASCADE;
ALTER TABLE "product_categories" ADD CONSTRAINT "fk_pc_prod" FOREIGN KEY ("product_id") REFERENCES "products" ("id") ON DELETE CASCADE;
ALTER TABLE "product_collections" ADD CONSTRAINT "fk_pcoll_coll" FOREIGN KEY ("collection_id") REFERENCES "collections" ("id") ON DELETE CASCADE;
ALTER TABLE "product_collections" ADD CONSTRAINT "fk_pcoll_prod" FOREIGN KEY ("product_id") REFERENCES "products" ("id") ON DELETE CASCADE;
ALTER TABLE "product_comparison_values" ADD CONSTRAINT "fk_pcv_prod" FOREIGN KEY ("product_id") REFERENCES "products" ("id") ON DELETE CASCADE;
ALTER TABLE "product_comparison_values" ADD CONSTRAINT "fk_pcv_row" FOREIGN KEY ("row_id") REFERENCES "product_comparison_rows" ("id") ON DELETE CASCADE;
ALTER TABLE "product_comparisons" ADD CONSTRAINT "fk_cmp_prod" FOREIGN KEY ("product_id") REFERENCES "products" ("id") ON DELETE CASCADE;
ALTER TABLE "product_faqs" ADD CONSTRAINT "fk_pfaq_prod" FOREIGN KEY ("product_id") REFERENCES "products" ("id") ON DELETE CASCADE;
ALTER TABLE "product_hotspots" ADD CONSTRAINT "fk_ph_prod" FOREIGN KEY ("product_id") REFERENCES "products" ("id") ON DELETE CASCADE;
ALTER TABLE "product_images" ADD CONSTRAINT "fk_img_prod" FOREIGN KEY ("product_id") REFERENCES "products" ("id") ON DELETE CASCADE;
ALTER TABLE "product_images" ADD CONSTRAINT "fk_img_var" FOREIGN KEY ("variant_id") REFERENCES "product_variants" ("id") ON DELETE SET NULL;
ALTER TABLE "product_page_modules" ADD CONSTRAINT "fk_ppm_prod" FOREIGN KEY ("product_id") REFERENCES "products" ("id") ON DELETE CASCADE;
ALTER TABLE "product_specifications" ADD CONSTRAINT "fk_spec_prod" FOREIGN KEY ("product_id") REFERENCES "products" ("id") ON DELETE CASCADE;
ALTER TABLE "product_translations" ADD CONSTRAINT "fk_pt_prod" FOREIGN KEY ("product_id") REFERENCES "products" ("id") ON DELETE CASCADE;
ALTER TABLE "product_variants" ADD CONSTRAINT "fk_var_prod" FOREIGN KEY ("product_id") REFERENCES "products" ("id") ON DELETE CASCADE;
ALTER TABLE "products" ADD CONSTRAINT "fk_prod_brand" FOREIGN KEY ("brand_id") REFERENCES "brands" ("id") ON DELETE CASCADE;
ALTER TABLE "refunds" ADD CONSTRAINT "fk_ref_order" FOREIGN KEY ("order_id") REFERENCES "orders" ("id") ON DELETE CASCADE;
ALTER TABLE "return_items" ADD CONSTRAINT "fk_ri_ret" FOREIGN KEY ("return_id") REFERENCES "returns" ("id") ON DELETE CASCADE;
ALTER TABLE "returns" ADD CONSTRAINT "fk_ret_order" FOREIGN KEY ("order_id") REFERENCES "orders" ("id") ON DELETE CASCADE;
ALTER TABLE "review_votes" ADD CONSTRAINT "fk_rv_rev" FOREIGN KEY ("review_id") REFERENCES "reviews" ("id") ON DELETE CASCADE;
ALTER TABLE "reviews" ADD CONSTRAINT "fk_rev_prod" FOREIGN KEY ("product_id") REFERENCES "products" ("id") ON DELETE CASCADE;
ALTER TABLE "role_permissions" ADD CONSTRAINT "fk_rp_perm" FOREIGN KEY ("permission_id") REFERENCES "permissions" ("id") ON DELETE CASCADE;
ALTER TABLE "role_permissions" ADD CONSTRAINT "fk_rp_role" FOREIGN KEY ("role_id") REFERENCES "roles" ("id") ON DELETE CASCADE;
ALTER TABLE "shipping_shipments" ADD CONSTRAINT "fk_ship_order" FOREIGN KEY ("order_id") REFERENCES "orders" ("id") ON DELETE CASCADE;
ALTER TABLE "wishlist_items" ADD CONSTRAINT "fk_wli_prod" FOREIGN KEY ("product_id") REFERENCES "products" ("id") ON DELETE CASCADE;
ALTER TABLE "wishlist_items" ADD CONSTRAINT "fk_wli_wl" FOREIGN KEY ("wishlist_id") REFERENCES "wishlists" ("id") ON DELETE CASCADE;

-- Migration complete