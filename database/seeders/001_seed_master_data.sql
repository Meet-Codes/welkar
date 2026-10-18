-- Welkar Master Seed Data

SET FOREIGN_KEY_CHECKS = 0;

-- 1. Roles & Permissions
INSERT INTO `roles` (`id`, `name`, `slug`, `description`) VALUES
(1, 'Owner', 'owner', 'Full administrative authority and system settings control'),
(2, 'Manager', 'manager', 'Operational management, orders, catalog and customer handling'),
(3, 'Catalog Manager', 'catalog_manager', 'Products, inventory, collections and bulk importer'),
(4, 'Marketing Manager', 'marketing_manager', 'Campaigns, banners, coupons and advertising reports'),
(5, 'Support Agent', 'support_agent', 'Order lookup, customer support and return reviews'),
(6, 'Analyst', 'analyst', 'Read-only business analytics and performance reports')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`) VALUES
(1, 'View Dashboard', 'dashboard.view', 'Dashboard', 'View executive KPIs and operational summaries'),
(2, 'View Orders', 'orders.view', 'Orders', 'View customer orders and status details'),
(3, 'Update Order Status', 'orders.update_status', 'Orders', 'Advance or modify order status and tracking'),
(4, 'Cancel Orders', 'orders.cancel', 'Orders', 'Cancel eligible customer orders'),
(5, 'Request Refunds', 'refunds.request', 'Orders', 'Initiate payment refund flow'),
(6, 'Approve Refunds', 'refunds.approve', 'Orders', 'Authorize and finalize payment refunds'),
(7, 'Create Products', 'products.create', 'Catalog', 'Add new watch models and variants'),
(8, 'Update Products', 'products.update', 'Catalog', 'Edit product details, prices and assets'),
(9, 'Archive Products', 'products.archive', 'Catalog', 'Unpublish or archive products'),
(10, 'View Inventory', 'inventory.view', 'Inventory', 'View stock levels across warehouses'),
(11, 'Adjust Inventory', 'inventory.adjust', 'Inventory', 'Perform manual stock adjustments'),
(12, 'Run Bulk Imports', 'imports.run', 'Catalog', 'Upload and process bulk product CSV files'),
(13, 'View Customers', 'customers.view', 'Customers', 'View customer CRM profiles and orders'),
(14, 'Export Customer Data', 'customers.export', 'Customers', 'Download authorized customer lists'),
(15, 'Manage Coupons', 'coupons.manage', 'Promotions', 'Create and toggle discount coupon codes'),
(16, 'Manage Campaigns', 'campaigns.manage', 'Promotions', 'Configure promotional discount campaigns'),
(17, 'Manage Banners', 'banners.manage', 'Promotions', 'Update homepage hero and promotional banners'),
(18, 'View Analytics', 'analytics.view', 'Analytics', 'Access first-party traffic and sales analytics'),
(19, 'Export Analytics', 'analytics.export', 'Analytics', 'Export analytics reports as CSV'),
(20, 'Manage Ad Integrations', 'ads_integrations.manage', 'Marketing', 'Configure Meta, Google Ads and Pixel tracking'),
(21, 'Manage Settings', 'settings.manage', 'Settings', 'Update business, GST, invoice and payment configurations'),
(22, 'Manage Staff Roles', 'users.manage_roles', 'Settings', 'Create staff accounts and assign permissions'),
(23, 'View Audit Logs', 'audit_logs.view', 'Settings', 'Inspect administrative action audit trail')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- Assign all permissions to Owner role
DELETE FROM `role_permissions` WHERE `role_id` = 1;
INSERT INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 1, `id` FROM `permissions`;

-- Default Admin Account (Username: admin, Email: welkarstore@gmail.com, Pass: admin@welkar123)
INSERT INTO `admin_users` (`id`, `username`, `email`, `password_hash`, `full_name`, `role_slug`, `status`, `mfa_enabled`)
VALUES (1, 'admin', 'welkarstore@gmail.com', '$2y$10$UYsRfclK2vnTHWmryFHrLO3zfZQJzPOLOsu4Gq9xehbRg9YtUHs7q', 'Welkar Store Admin', 'owner', 'active', 0)
ON DUPLICATE KEY UPDATE `role_slug`='owner', `password_hash`=VALUES(`password_hash`);

INSERT INTO `admin_user_roles` (`admin_user_id`, `role_id`) VALUES (1, 1)
ON DUPLICATE KEY UPDATE `role_id`=1;

-- 2. Default Inventory Location
INSERT INTO `inventory_locations` (`id`, `name`, `code`, `is_default`, `is_active`)
VALUES (1, 'Primary Central Fulfillment Warehouse', 'MUM-WH-01', 1, 1)
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- 3. Brands
INSERT INTO `brands` (`id`, `name`, `slug`, `logo_url`, `description`, `is_featured`, `is_active`) VALUES
(1, 'Welkar', 'welkar', '/assets/images/brands/welkar.png', 'Welkar Signature Timepieces: Engineered for modern precision and classic Indian elegance.', 1, 1),
(2, 'Titan', 'titan', '/assets/images/brands/titan.png', 'Iconic craftsmanship with contemporary aesthetics.', 1, 1),
(3, 'Casio', 'casio', '/assets/images/brands/casio.png', 'Renowned durability and innovative digital-analog performance.', 1, 1),
(4, 'Fossil', 'fossil', '/assets/images/brands/fossil.png', 'Vintage authentic American style with modern watchmaking.', 1, 1),
(5, 'Timex', 'timex', '/assets/images/brands/timex.png', 'True heritage watchmaking since 1854.', 1, 1)
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- 4. Categories
INSERT INTO `categories` (`id`, `parent_id`, `name`, `slug`, `description`, `image_url`, `sort_order`, `is_active`) VALUES
(1, NULL, 'Men\'s Watches', 'mens-watches', 'Distinguished chronographs, automatics, and dress watches crafted for men.', '/assets/images/categories/cat-men.jpg', 1, 1),
(2, NULL, 'Women\'s Watches', 'womens-watches', 'Elegant, minimalist, and statement timepieces tailored for women.', '/assets/images/categories/cat-women.jpg', 2, 1),
(3, NULL, 'Unisex Watches', 'unisex-watches', 'Versatile and modern silhouettes suited for any wrist.', '/assets/images/categories/cat-unisex.jpg', 3, 1),
(4, NULL, 'Luxury Watches', 'luxury-watches', 'Sapphire crystal, mechanical automatic movements, and premium stainless steel.', '/assets/images/categories/cat-luxury.jpg', 4, 1),
(5, NULL, 'Sports Watches', 'sports-watches', 'Rugged, water-resistant, shockproof watches engineered for endurance.', '/assets/images/categories/cat-sports.jpg', 5, 1)
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- 5. Curated Collections
INSERT INTO `collections` (`id`, `name`, `slug`, `description`, `banner_image`, `is_active`) VALUES
(1, 'Best Sellers', 'best-sellers', 'India\'s most loved watches, backed by verified buyer satisfaction.', '/assets/images/collections/best-sellers.jpg', 1),
(2, 'New Arrivals', 'new-arrivals', 'Fresh drops featuring our latest dial designs and premium straps.', '/assets/images/collections/new-arrivals.jpg', 1),
(3, 'Pick Your Perfect Dial', 'pick-your-perfect-dial', 'Explore watches categorized by unique dial styles from Roman numerals to Chronographs.', '/assets/images/collections/dial-styles.jpg', 1),
(4, 'Executive Steel', 'executive-steel', 'Solid stainless steel mesh and link bracelets for boardroom elegance.', '/assets/images/collections/steel.jpg', 1)
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- 6. Sample Products (Within ₹1,000 - ₹10,000 target range)
INSERT INTO `products` (
  `id`, `brand_id`, `name`, `slug`, `model_number`, `base_sku`, `short_description`, `description`,
  `gender`, `dial_type`, `movement`, `case_material`, `case_size_mm`, `case_color`,
  `strap_material`, `strap_color`, `water_resistance`, `warranty_years`, `country_of_origin`,
  `base_price`, `cost_price`, `compare_at_price`,
  `is_live_dial_animated`, `dial_base_image`, `dial_hour_hand_image`, `dial_minute_hand_image`, `dial_second_hand_image`, `dial_center_x`, `dial_center_y`,
  `hsn_code`, `gst_rate`, `is_featured`, `is_active`, `meta_title`, `meta_description`
) VALUES
(
  1, 1, 'Welkar Aura White Silver Steel', 'welkar-aura-white-silver-steel', 'W-AUR-01', 'WLK-AUR-SLV-01',
  'Signature minimalist analog watch with surgical-grade stainless steel bracelet.',
  '<p>The <strong>Welkar Aura White Silver Steel</strong> combines clean minimalist design with uncompromising horological precision. Built with a pristine white sunray dial, sleek stick markers, and an ultra-durable surgical grade 316L stainless steel case and bracelet, it delivers an effortless presence from business meetings to weekend dinners.</p><ul><li>316L Surgical Grade Stainless Steel</li><li>Scratch-resistant mineral glass</li><li>Water resistant to 30 meters</li><li>Japanese quartz movement</li></ul>',
  'Men', 'Minimalist Stick', 'Japanese Quartz', '316L Stainless Steel', 41, 'Silver',
  'Stainless Steel Link', 'Silver', '30m / 3 ATM', 2, 'India',
  3499.00, 1850.00, 4999.00,
  1, '/assets/images/products/aura-dial-base.png', '/assets/images/products/aura-hand-hour.png', '/assets/images/products/aura-hand-minute.png', '/assets/images/products/aura-hand-second.png', 50, 50,
  '9102', 18.00, 1, 1, 'Welkar Aura White Silver Steel - Minimalist Watch', 'Shop Welkar Aura White Silver Steel watch with Japanese quartz movement.'
),
(
  2, 1, 'Welkar Chrono Heritage Obsidian Black', 'welkar-chrono-heritage-obsidian-black', 'W-CRN-02', 'WLK-CRN-BLK-02',
  'Dual-subdial racing chronograph with genuine calfskin leather strap.',
  '<p>Engineered for those who value bold performance. The <strong>Welkar Chrono Heritage</strong> features a high-contrast matte obsidian black dial, dual chronometer dials, tachymeter bezel, and hand-stitched premium leather strap.</p>',
  'Men', 'Chronograph', 'Quartz Chronograph', 'Matte Black Alloy', 44, 'Black',
  'Genuine Leather', 'Black', '50m / 5 ATM', 2, 'India',
  4299.00, 2200.00, 5999.00,
  0, NULL, NULL, NULL, NULL, 50, 50,
  '9102', 18.00, 1, 1, 'Welkar Chrono Heritage Obsidian Black Watch', 'Buy Welkar Chrono Heritage racing watch with genuine leather strap.'
),
(
  3, 1, 'Welkar Royale Classic Roman Gold', 'welkar-royale-classic-roman-gold', 'W-ROY-03', 'WLK-ROY-GLD-03',
  'Timeless dress watch with embossed Roman dial indices and champagne finish.',
  '<p>Graceful proportions and classic aesthetics. The <strong>Welkar Royale Classic</strong> presents grand Roman numerals over a delicately guilloched champagne dial, encased in warm polished gold-tone steel with an Italian brown leather band.</p>',
  'Unisex', 'Classic Roman', 'Japanese Quartz', 'Gold-plated Stainless Steel', 39, 'Gold',
  'Genuine Leather', 'Brown', '30m / 3 ATM', 1, 'India',
  2899.00, 1450.00, 3999.00,
  0, NULL, NULL, NULL, NULL, 50, 50,
  '9102', 18.00, 1, 1, 'Welkar Royale Classic Roman Gold Dress Watch', 'Timeless Roman numerals dress watch in champagne gold by Welkar.'
),
(
  4, 2, 'Titan Neo Splash Sport Chrono', 'titan-neo-splash-sport-chrono', 'T-NEO-88', 'TTN-NEO-8812',
  'Sporty high-durability watch with blue sunray dial and silicone strap.',
  '<p>A modern daily driver built by Titan. The Neo Splash features energetic orange accents, date display, and flexible waterproof silicone strap for active lifestyles.</p>',
  'Men', 'Analog', 'Quartz', 'Polished Steel', 43, 'Silver',
  'Silicone Rubber', 'Navy Blue', '50m / 5 ATM', 2, 'India',
  3795.00, 2500.00, 4495.00,
  0, NULL, NULL, NULL, NULL, 50, 50,
  '9102', 18.00, 0, 1, 'Titan Neo Splash Sport Watch', 'Shop Titan Neo Splash watch at Welkar.'
),
(
  5, 3, 'Casio Vintage Digital Electro-Luminescence', 'casio-vintage-digital-el', 'A168WA', 'CSO-A168-SLV',
  'Iconic 80s retro digital watch with illuminated display and stainless steel band.',
  '<p>The indisputable icon of retro fashion. Featuring Casio\'s signature Illuminator backlight, 1/100-second stopwatch, daily alarm, and auto-calendar.</p>',
  'Unisex', 'Digital', 'Digital Quartz', 'Resin Chrome Plated', 38, 'Silver',
  'Stainless Steel Mesh', 'Silver', 'Water Resistant', 2, 'India',
  2695.00, 1800.00, 2995.00,
  0, NULL, NULL, NULL, NULL, 50, 50,
  '9102', 18.00, 1, 1, 'Casio Vintage Digital Watch - Retro Steel', 'Buy authentic Casio Vintage digital watch with Illuminator on Welkar.'
),
(
  6, 1, 'Welkar Celestial Moon Phase Rose Gold', 'welkar-celestial-moon-phase-rose-gold', 'W-CEL-06', 'WLK-CEL-RSG-06',
  'Astronomy-inspired dial with working moon-phase complication and starry dial.',
  '<p>Capture the poetry of time. The <strong>Welkar Celestial</strong> exhibits a functioning lunar aperture at 6 o\'clock tracking the waxing and waning moon against a midnight blue aventurine dial.</p>',
  'Women', 'Moon Phase', 'Multi-function Quartz', 'Rose Gold Stainless Steel', 36, 'Rose Gold',
  'Genuine Leather', 'Midnight Blue', '30m / 3 ATM', 2, 'India',
  5499.00, 2900.00, 7499.00,
  0, NULL, NULL, NULL, NULL, 50, 50,
  '9102', 18.00, 1, 1, 'Welkar Celestial Moon Phase Watch', 'Elegant Rose Gold Moon Phase watch for women by Welkar.'
),
(
  7, 4, 'Fossil Townsman Automatic Skeleton', 'fossil-townsman-automatic-skeleton', 'ME3110', 'FSL-TWN-SKL-07',
  'Self-winding mechanical watch revealing intricate internal gearwork.',
  '<p>No battery needed. Powered by the natural motion of your wrist, the Townsman Skeleton exposes the heartbeat of mechanical watchmaking through double-sided exhibition crystals.</p>',
  'Men', 'Automatic', 'Automatic Mechanical', 'Brushed Stainless Steel', 44, 'Silver',
  'Genuine Leather', 'Dark Brown', '50m / 5 ATM', 2, 'India',
  9495.00, 6800.00, 13995.00,
  0, NULL, NULL, NULL, NULL, 50, 50,
  '9102', 18.00, 1, 1, 'Fossil Townsman Automatic Skeleton Watch', 'Luxury self-winding automatic mechanical watch on Welkar.'
)
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- 7. Product Variants
INSERT INTO `product_variants` (`id`, `product_id`, `sku`, `barcode`, `variant_name`, `dial_color`, `strap_color`, `price`, `compare_at_price`, `cost_price`, `is_active`) VALUES
(1, 1, 'WLK-AUR-SLV-WHT', '890123456701', 'Silver Steel / White Dial', 'White', 'Silver', 3499.00, 4999.00, 1850.00, 1),
(2, 1, 'WLK-AUR-SLV-BLK', '890123456702', 'Silver Steel / Onyx Black Dial', 'Black', 'Silver', 3499.00, 4999.00, 1850.00, 1),
(3, 1, 'WLK-AUR-GLD-WHT', '890123456703', 'Yellow Gold / Champagne Dial', 'Champagne', 'Gold', 3799.00, 5299.00, 1950.00, 1),
(4, 2, 'WLK-CRN-BLK-LTH', '890123456704', 'Obsidian Black / Black Leather', 'Black', 'Black', 4299.00, 5999.00, 2200.00, 1),
(5, 3, 'WLK-ROY-GLD-BRN', '890123456705', 'Champagne Gold / Brown Leather', 'Champagne', 'Brown', 2899.00, 3999.00, 1450.00, 1),
(6, 4, 'TTN-NEO-NVY-BLU', '890123456706', 'Navy Blue / Silicone Sport', 'Navy Blue', 'Navy Blue', 3795.00, 4495.00, 2500.00, 1),
(7, 5, 'CSO-A168-SLV-DIG', '890123456707', 'Classic Steel Retro Display', 'Grey LCD', 'Silver', 2695.00, 2995.00, 1800.00, 1),
(8, 6, 'WLK-CEL-RSG-NVY', '890123456708', 'Rose Gold / Midnight Blue', 'Midnight Blue', 'Midnight Blue', 5499.00, 7499.00, 2900.00, 1),
(9, 7, 'FSL-TWN-SKL-BRN', '890123456709', 'Skeleton Silver / Dark Brown Leather', 'Skeleton Open', 'Dark Brown', 9495.00, 13995.00, 6800.00, 1)
ON DUPLICATE KEY UPDATE `sku`=VALUES(`sku`);

-- 8. Product Categories Mapping
INSERT INTO `product_categories` (`product_id`, `category_id`) VALUES
(1, 1), -- Welkar Aura in Men's
(1, 3), -- Welkar Aura in Unisex
(2, 1), -- Chrono in Men's
(2, 5), -- Chrono in Sports
(3, 1), -- Royale in Men's
(3, 3), -- Royale in Unisex
(4, 1), -- Titan Neo in Men's
(4, 5), -- Titan Neo in Sports
(5, 1), -- Casio in Men's
(5, 2), -- Casio in Women's
(5, 3), -- Casio in Unisex
(6, 2), -- Moon Phase in Women's
(6, 4), -- Moon Phase in Luxury
(7, 1), -- Fossil in Men's
(7, 4)  -- Fossil in Luxury
ON DUPLICATE KEY UPDATE `category_id`=VALUES(`category_id`);

-- 9. Product Collections Mapping
INSERT INTO `product_collections` (`product_id`, `collection_id`) VALUES
(1, 1), (1, 2), (1, 3), (1, 4),
(2, 1), (2, 3),
(3, 1), (3, 3),
(4, 1),
(5, 1),
(6, 2), (6, 3),
(7, 4)
ON DUPLICATE KEY UPDATE `collection_id`=VALUES(`collection_id`);

-- 10. Product Images
INSERT INTO `product_images` (`id`, `product_id`, `variant_id`, `image_url`, `is_primary`, `sort_order`) VALUES
(1, 1, 1, '/assets/images/products/aura-white-silver-1.jpg', 1, 1),
(2, 1, 1, '/assets/images/products/aura-white-silver-2.jpg', 0, 2),
(3, 1, 2, '/assets/images/products/aura-black-silver-1.jpg', 0, 3),
(4, 2, 4, '/assets/images/products/chrono-black-1.jpg', 1, 1),
(5, 3, 5, '/assets/images/products/royale-roman-1.jpg', 1, 1),
(6, 4, 6, '/assets/images/products/titan-neo-1.jpg', 1, 1),
(7, 5, 7, '/assets/images/products/casio-vintage-1.jpg', 1, 1),
(8, 6, 8, '/assets/images/products/celestial-moon-1.jpg', 1, 1),
(9, 7, 9, '/assets/images/products/fossil-skeleton-1.jpg', 1, 1)
ON DUPLICATE KEY UPDATE `image_url`=VALUES(`image_url`);

-- 11. Specifications
INSERT INTO `product_specifications` (`product_id`, `spec_name`, `spec_value`, `sort_order`) VALUES
(1, 'Dial Window Material', 'Anti-reflective Sapphire Mineral Crystal', 1),
(1, 'Case Diameter', '41 mm', 2),
(1, 'Case Thickness', '8.5 mm Ultra-Slim', 3),
(1, 'Band Width', '20 mm Quick Release', 4),
(1, 'Clasp Type', 'Double Butterfly Deployment Clasp', 5),
(1, 'Movement Origin', 'Japan Miyota Calibre', 6),
(2, 'Subdials', '60-Minute Counter, 24-Hour Display', 1),
(2, 'Case Diameter', '44 mm', 2),
(2, 'Luminescence', 'Super-LumiNova Hands & Indexes', 3),
(3, 'Dial Pattern', 'Classical Sunray Guilloche', 1),
(3, 'Numerals', 'Applied Roman Numerals', 2),
(6, 'Complication', 'Real Lunar Moon Phase Mechanism', 1),
(7, 'Jewels', '21 Synthetic Rubies', 1),
(7, 'Power Reserve', 'Approximately 42 Hours', 2)
ON DUPLICATE KEY UPDATE `spec_value`=VALUES(`spec_value`);

-- 12. Initial Stock
INSERT INTO `inventory_stock` (`variant_id`, `location_id`, `quantity`, `reserved_quantity`, `low_stock_threshold`) VALUES
(1, 1, 45, 0, 5),
(2, 1, 30, 0, 5),
(3, 1, 25, 0, 5),
(4, 1, 38, 0, 5),
(5, 1, 50, 0, 8),
(6, 1, 28, 0, 5),
(7, 1, 65, 0, 10),
(8, 1, 18, 0, 4),
(9, 1, 12, 0, 3)
ON DUPLICATE KEY UPDATE `quantity`=VALUES(`quantity`);

-- 13. Coupons
INSERT INTO `coupons` (`id`, `code`, `description`, `discount_type`, `discount_value`, `min_order_value`, `max_discount_value`, `usage_limit_total`, `usage_limit_per_user`, `is_first_order_only`, `is_active`) VALUES
(1, 'WELKAR10', '10% instant discount on orders above ₹1,999', 'percentage', 10.00, 1999.00, 1000.00, 1000, 1, 0, 1),
(2, 'WELCOME500', 'Flat ₹500 off on your first order above ₹2,999', 'fixed', 500.00, 2999.00, 500.00, 500, 1, 1, 1)
ON DUPLICATE KEY UPDATE `discount_value`=VALUES(`discount_value`);

-- 14. Promotional Banners
INSERT INTO `banners` (`id`, `title`, `subtitle`, `image_desktop`, `image_mobile`, `link_url`, `button_text`, `display_order`, `is_active`) VALUES
(1, 'Pure Precision. Timeless Steel.', 'Discover the Welkar Aura Minimalist Collection crafted with Japanese Quartz movement.', '/assets/images/banners/hero-aura-desk.jpg', '/assets/images/banners/hero-aura-mob.jpg', '/product/welkar-aura-white-silver-steel', 'Explore Aura Series', 1, 1),
(2, 'Pick Your Perfect Dial', 'From Classic Roman to High-Precision Chronographs. Find your aesthetic.', '/assets/images/banners/hero-dials-desk.jpg', '/assets/images/banners/hero-dials-mob.jpg', '/catalog?filter=dial_types', 'Shop By Dial Style', 2, 1)
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`);

-- 15. Verified Reviews
INSERT INTO `reviews` (`product_id`, `author_name`, `rating`, `title`, `comment`, `is_verified_purchase`, `status`) VALUES
(1, 'Aditya Sharma', 5, 'Exceptional build quality', 'I was amazed by the weight and feel of the surgical steel. The dial is clean and catches the light beautifully. Fast delivery to Pune.', 1, 'approved'),
(1, 'Rohan Mehta', 5, 'Minimalist perfection', 'Looks much more expensive than ₹3,499. The butterfly clasp is solid. Highly recommend Welkar.', 1, 'approved'),
(2, 'Vikram Deshmukh', 5, 'Stunning chronograph', 'The obsidian black finish is matte and doesn\'t pick up fingerprints. Leather strap feels soft straight out of the box.', 1, 'approved'),
(5, 'Neha Patel', 5, 'Pure nostalgia!', 'Original Casio vintage steel with bright backlight. 100% authentic piece received in mint condition.', 1, 'approved')
ON DUPLICATE KEY UPDATE `comment`=VALUES(`comment`);

-- 16. Testimonials
INSERT INTO `testimonials` (`id`, `author_name`, `author_location`, `quote`, `rating`, `watch_model`, `is_active`, `display_order`) VALUES
(1, 'Kabir Singhania', 'Mumbai, MH', 'Welkar has redefined accessible luxury watch shopping in India. The Aura watch on my wrist gets compliments in every boardroom meeting.', 5, 'Welkar Aura Minimalist Steel', 1, 1),
(2, 'Ananya Roy', 'Bengaluru, KA', 'Ordered the Celestial Moon Phase for my anniversary. The packaging, warranty card, and sheer dial detailing blew us away.', 5, 'Welkar Celestial Moon Phase', 1, 2),
(3, 'Sameer Khan', 'New Delhi, DL', 'Safe delivery, genuine invoice with GST breakdown, and clear tracking from order to doorstep. Excellent customer care.', 5, 'Welkar Chrono Heritage', 1, 3)
ON DUPLICATE KEY UPDATE `quote`=VALUES(`quote`);

-- 17. Content Pages (Legal, Policies, About)
INSERT INTO `content_pages` (`slug`, `locale`, `title`, `content_html`, `meta_title`, `meta_description`, `is_published`) VALUES
(
  'about-us', 'en', 'About Welkar',
  '<h2>The Welkar Philosophy</h2><p>Founded with a passion for horological balance, Welkar is an Indian curated timepiece destination. We curate established global watchmakers alongside our private-label Welkar timepieces designed for modern Indian lifestyles.</p><p>Every watch in our collection is backed by an authorized manufacturer warranty, verified specifications, and strict quality assurance.</p>',
  'About Welkar - Premium Watch Destination', 'Learn about Welkar timepieces, our craftsmanship, and heritage.', 1
),
(
  'shipping-policy', 'en', 'Shipping & Delivery Policy',
  '<h2>Domestic Shipping Across India</h2><p>Welkar delivers to serviceable pincodes across India via insured courier partners. Orders are dispatched within 24 to 48 business hours.</p><ul><li>Standard Delivery: 3 to 6 business days.</li><li>Free shipping on all prepaid and COD orders over ₹1,499.</li><li>A flat shipping fee of ₹60 applies to orders below ₹1,499.</li><li>Real-time tracking links are provided via email upon dispatch.</li></ul>',
  'Shipping Policy - Welkar Watches', 'Domestic shipping terms, delivery timelines, and courier tracking.', 1
),
(
  'returns-exchanges', 'en', 'Returns & Replacement Policy',
  '<h2>Hassle-Free 7-Day Returns</h2><p>We want you to love your timepiece. If you receive a damaged, defective, or incorrect watch, you may initiate a return or exchange request within 7 calendar days of delivery from your account dashboard.</p><p>Returned watches must be unused, in original packaging with protective films, tags, and invoice intact. Once verified by our inspection team, refunds are processed within 5-7 working days to the original payment source.</p>',
  'Returns & Exchanges - Welkar', '7-day return and exchange policy for Welkar watches.', 1
),
(
  'warranty', 'en', 'Warranty Information',
  '<h2>Official Warranty Coverage</h2><p>All Welkar private-label watches carry a 1 to 2-year manufacturer warranty against internal movement defects. Partner brands (Titan, Casio, Fossil, Timex) include their official brand warranty cards honored at authorized service centers across India.</p>',
  'Warranty Terms - Welkar Timepieces', 'Official watch movement warranty terms and service coverage.', 1
),
(
  'privacy-policy', 'en', 'Privacy Policy',
  '<h2>Your Privacy Matters</h2><p>Welkar respects your personal privacy. We collect minimal customer information strictly necessary to process orders, comply with Indian tax regulations, and provide transactional shipping updates. We never store credit or debit card numbers on our servers.</p>',
  'Privacy Policy - Welkar', 'How Welkar collects, uses, and protects customer data.', 1
),
(
  'terms-conditions', 'en', 'Terms & Conditions',
  '<h2>Terms of Service</h2><p>By placing an order on Welkar.in, you agree to our standard terms of sale. Prices are in Indian Rupees (INR) and applicable Goods and Services Tax (GST) is calculated at checkout based on the delivery destination.</p>',
  'Terms & Conditions - Welkar', 'Terms and conditions governing online purchases on Welkar.', 1
),
(
  'faqs', 'en', 'Frequently Asked Questions',
  '<h2>Frequently Asked Questions</h2><h4>Are all watches original?</h4><p>Yes. Every timepiece sold on Welkar is 100% authentic, brand-new, and sourced either directly from our private manufacturing facility or authorized brand distributors.</p><h4>Can I pay via Cash on Delivery (COD)?</h4><p>Yes, Cash on Delivery is available for eligible orders up to ₹5,000.</p><h4>How does the Live Watch feature work?</h4><p>On supported models, our live watch view synchronizes the hour, minute, and second hands on the dial with your current local device time in real-time.</p>',
  'Frequently Asked Questions - Welkar', 'Frequently asked questions regarding orders, authenticity, and shipping.', 1
)
ON DUPLICATE KEY UPDATE `content_html`=VALUES(`content_html`);

-- 18. Store Settings
INSERT INTO `store_settings` (`setting_key`, `setting_value`, `group_name`) VALUES
('site_name', 'Welkar', 'general'),
('business_legal_name', 'Welkar Timepieces Private Limited', 'legal'),
('business_gstin', '', 'legal'),
('business_state', 'Maharashtra', 'legal'),
('business_state_code', '27', 'legal'),
('business_email', 'welkarstore@gmail.com', 'general'),
('business_phone', '+91 98765 43210', 'general'),
('default_shipping_fee', '60.00', 'shipping'),
('free_shipping_threshold', '1499.00', 'shipping'),
('cod_enabled', '1', 'payment'),
('cod_max_amount', '5000.00', 'payment'),
('razorpay_mode', 'test', 'payment'),
('razorpay_key_id', 'rzp_test_placeholder_key', 'payment'),
('razorpay_key_secret', '', 'payment'),
('default_gst_rate', '18.00', 'tax'),
('invoice_prefix', 'WEL', 'invoice')
ON DUPLICATE KEY UPDATE `setting_value`=VALUES(`setting_value`);

-- 19. Invoice Sequence Initializer
INSERT INTO `invoice_sequences` (`id`, `fiscal_year`, `prefix`, `current_number`)
VALUES (1, '2026-27', 'WEL', 1001)
ON DUPLICATE KEY UPDATE `prefix`=VALUES(`prefix`);

SET FOREIGN_KEY_CHECKS = 1;
