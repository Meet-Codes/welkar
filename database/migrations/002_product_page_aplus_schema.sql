-- Welkar Migration 002: Amazon A+ Style Product Page Modules & Complete Review System
-- Designed for MariaDB 10.11+ / MySQL 8.0+ (InnoDB, UTF8MB4)

SET FOREIGN_KEY_CHECKS = 0;

-- 1. PRODUCT A+ PAGE MODULES
CREATE TABLE IF NOT EXISTS `product_page_modules` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `module_type` VARCHAR(60) NOT NULL COMMENT 'banner, hotspots, stories, lifestyle, video, comparison, faq, spec',
  `title` VARCHAR(255) NULL,
  `subtitle` TEXT NULL,
  `content_json` LONGTEXT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_enabled` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_ppm_prod` (`product_id`, `is_enabled`, `sort_order`),
  CONSTRAINT `fk_ppm_prod` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. PRODUCT HOTSPOTS (Visual Horological Anatomy)
CREATE TABLE IF NOT EXISTS `product_hotspots` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `x_pos_percent` DECIMAL(5,2) NOT NULL DEFAULT 50.00,
  `y_pos_percent` DECIMAL(5,2) NOT NULL DEFAULT 50.00,
  `title` VARCHAR(150) NOT NULL,
  `description` TEXT NOT NULL,
  `image_url` VARCHAR(1000) NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  INDEX `idx_ph_prod` (`product_id`),
  CONSTRAINT `fk_ph_prod` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. PRODUCT COMPARISON MATRIX
CREATE TABLE IF NOT EXISTS `product_comparison_rows` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `feature_name` VARCHAR(150) NOT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `product_comparison_values` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `row_id` BIGINT UNSIGNED NOT NULL,
  `value` VARCHAR(255) NOT NULL,
  UNIQUE KEY `uk_pcv` (`product_id`, `row_id`),
  CONSTRAINT `fk_pcv_prod` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_pcv_row` FOREIGN KEY (`row_id`) REFERENCES `product_comparison_rows` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. PRODUCT-SPECIFIC & HOROLOGY FAQS
CREATE TABLE IF NOT EXISTS `product_faqs` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `product_id` BIGINT UNSIGNED NULL COMMENT 'NULL indicates global watch FAQ',
  `question` VARCHAR(255) NOT NULL,
  `answer` TEXT NOT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  INDEX `idx_pfaq_prod` (`product_id`),
  CONSTRAINT `fk_pfaq_prod` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. REVIEW HELPFUL VOTES (Duplicate Prevention)
CREATE TABLE IF NOT EXISTS `review_votes` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `review_id` BIGINT UNSIGNED NOT NULL,
  `session_id` VARCHAR(120) NOT NULL,
  `ip_address` VARCHAR(45) NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `uk_rv` (`review_id`, `session_id`),
  CONSTRAINT `fk_rv_rev` FOREIGN KEY (`review_id`) REFERENCES `reviews` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
