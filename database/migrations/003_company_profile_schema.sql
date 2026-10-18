-- Welkar Migration 003: Company Profile, Founders, Values & Milestones
-- Designed for MariaDB 12+ / MySQL 8.0+ (InnoDB, UTF8MB4)

SET FOREIGN_KEY_CHECKS = 0;

-- 1. General Company Profile & Brand Narrative
CREATE TABLE IF NOT EXISTS `company_profile` (
  `id` INT UNSIGNED PRIMARY KEY DEFAULT 1,
  `brand_name` VARCHAR(100) NOT NULL DEFAULT 'WELKAR',
  `tagline` VARCHAR(255) NOT NULL DEFAULT 'Precision, Heritage, and Trust—Since 2002.',
  `founding_year` VARCHAR(10) NOT NULL DEFAULT '2002',
  `hero_title` VARCHAR(255) NOT NULL DEFAULT 'Precision, Heritage, and Trust—Since 2002.',
  `hero_intro` TEXT NOT NULL,
  `story_title` VARCHAR(255) NOT NULL DEFAULT 'Our Story',
  `story_intro` TEXT NOT NULL,
  `story_content` LONGTEXT NOT NULL,
  `philosophy_title` VARCHAR(255) NOT NULL DEFAULT 'Our Philosophy',
  `philosophy_intro` TEXT NOT NULL,
  `approach_watches_title` VARCHAR(255) NOT NULL DEFAULT 'Our Approach to Watches',
  `approach_watches_content` LONGTEXT NOT NULL,
  `quality_title` VARCHAR(255) NOT NULL DEFAULT 'Our Approach to Quality',
  `quality_content` LONGTEXT NOT NULL,
  `mission_statement` TEXT NOT NULL,
  `vision_statement` TEXT NOT NULL,
  `commitment_title` VARCHAR(255) NOT NULL DEFAULT 'Our Commitment to Customers',
  `commitment_content` LONGTEXT NOT NULL,
  `journey_ahead_title` VARCHAR(255) NOT NULL DEFAULT 'The Journey Ahead',
  `journey_ahead_content` TEXT NOT NULL,
  `home_summary` TEXT NOT NULL,
  `footer_summary` TEXT NOT NULL,
  `pdp_summary` TEXT NOT NULL,
  `collection_summary` TEXT NOT NULL,
  `seo_title` VARCHAR(255) NOT NULL DEFAULT 'About Welkar | Precision, Heritage, and Trust—Since 2002',
  `seo_description` VARCHAR(255) NOT NULL DEFAULT 'Learn the story of WELKAR, founded in 2002 by Mr. Sumit Singh and Mr. Nevil Sharma on the principles of precision, heritage, and lasting trust.',
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Founders Information
CREATE TABLE IF NOT EXISTS `company_founders` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `designation` VARCHAR(150) NOT NULL DEFAULT 'Co-Founder',
  `perspective` VARCHAR(150) NOT NULL,
  `bio` TEXT NOT NULL,
  `image_url` VARCHAR(500) NULL,
  `image_alt` VARCHAR(255) NULL,
  `display_order` INT NOT NULL DEFAULT 0,
  `is_published` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Core Values
CREATE TABLE IF NOT EXISTS `company_values` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `tagline` VARCHAR(200) NOT NULL,
  `description` TEXT NOT NULL,
  `icon_class` VARCHAR(100) NOT NULL DEFAULT 'bi-check-circle',
  `display_order` INT NOT NULL DEFAULT 0,
  `is_published` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Authentic Verified Milestones
CREATE TABLE IF NOT EXISTS `company_milestones` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `year_date` VARCHAR(50) NOT NULL,
  `title` VARCHAR(200) NOT NULL,
  `description` TEXT NOT NULL,
  `image_url` VARCHAR(500) NULL,
  `display_order` INT NOT NULL DEFAULT 0,
  `is_published` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
