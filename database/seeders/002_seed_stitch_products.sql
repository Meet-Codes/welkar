-- 002_seed_stitch_products.sql
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Ensure Categories exist
INSERT INTO `categories` (`id`, `parent_id`, `name`, `slug`, `description`, `image_url`, `sort_order`, `is_active`) VALUES
(1, NULL, 'Men\'s Watches', 'mens-watches', 'Distinguished chronographs, automatics, and dress watches crafted for men.', '/assets/images/categories/cat-men.jpg', 1, 1),
(2, NULL, 'Women\'s Watches', 'womens-watches', 'Elegant, minimalist, and statement timepieces tailored for women.', '/assets/images/categories/cat-women.jpg', 2, 1),
(3, NULL, 'Unisex Watches', 'unisex-watches', 'Versatile and modern silhouettes suited for any wrist.', '/assets/images/categories/cat-unisex.jpg', 3, 1),
(4, NULL, 'Luxury Watches', 'luxury-watches', 'Sapphire crystal, mechanical automatic movements, and premium stainless steel.', '/assets/images/categories/cat-luxury.jpg', 4, 1),
(5, NULL, 'Sports Watches', 'sports-watches', 'Rugged, water-resistant, shockproof watches engineered for endurance.', '/assets/images/categories/cat-sports.jpg', 5, 1),
(6, NULL, 'Kids & Youth', 'kids-and-youth', 'Shock-resistant and vibrant everyday sports watches engineered for young explorers.', '/assets/images/categories/cat-youth.jpg', 6, 1),
(7, NULL, 'Travel & Organizers', 'travel-organizers', 'High-frequency welded transparent cosmetic washbags and modular waterproof pouches.', '/assets/images/categories/cat-travel.jpg', 7, 1)
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `slug`=VALUES(`slug`), `is_active`=1;

-- 2. Insert Stitch Horological Modernity Products
INSERT INTO `products` (
  `id`, `brand_id`, `name`, `slug`, `model_number`, `base_sku`, `short_description`, `description`,
  `gender`, `dial_type`, `movement`, `case_material`, `case_size_mm`, `case_color`,
  `strap_material`, `strap_color`, `water_resistance`, `warranty_years`, `country_of_origin`,
  `base_price`, `cost_price`, `compare_at_price`,
  `is_live_dial_animated`, `dial_base_image`, `hsn_code`, `gst_rate`, `is_featured`, `is_active`, `meta_title`, `meta_description`
) VALUES
(
  11, 1, 'Welkar Stealth Square Dial Analog Quartz', 'welkar-stealth-square-dial-analog-quartz', 'W-STLTH-SQ', 'WLK-STLTH-SQ-01',
  'Deep black sunburst face with precision quartz movement, rounded square bezel, and link bracelet.',
  '<p>The <strong>Welkar Stealth Square Dial</strong> is engineered with a surgical-grade 316L black PVD stainless steel chassis, high-contrast luminescent baton indices, and a Japanese quartz movement tested under strict hydro-pressure standards.</p>',
  'Men', 'Square Dial', 'Japanese Quartz 3-Hand', '316L Black PVD Steel', 42, 'Black',
  'Stainless Steel Link', 'Matte Black', '30m / 3 ATM', 2, 'India',
  899.00, 420.00, 1499.00,
  0, NULL, '9102', 18.00, 1, 1, 'Welkar Stealth Square Dial - Men\'s Luxury Watch', 'Buy Welkar Stealth Square Dial Watch.'
),
(
  12, 1, 'Welkar Silver Quartz Analog - Black Dial with Day & Date', 'welkar-silver-quartz-analog-day-date', 'W-SLV-DD', 'WLK-SLV-DD-02',
  'High-polish silver bezel featuring dual day and date calendar aperture with luminous indices and multi-link band.',
  '<p>The <strong>Welkar Silver Quartz Analog</strong> pairs everyday executive poise with practical day-date complications and a scratch-resistant Hardlex crystal face.</p>',
  'Men', 'Round Chrono', 'Dual Day-Date Quartz', '316L Silver Brushed Steel', 42, 'Silver',
  'Solid Stainless Steel', 'Silver', '30m / 3 ATM', 2, 'India',
  799.00, 380.00, 1299.00,
  0, NULL, '9102', 18.00, 1, 1, 'Welkar Silver Quartz Analog Day-Date Watch', 'Shop Welkar Silver Quartz Day-Date.'
),
(
  13, 1, 'Welkar Hydro-Lab Submersion Master Edition', 'welkar-hydro-lab-submersion-master', 'W-HYD-SUB', 'WLK-HYD-SUB-03',
  'Engineered for aquatic resilience with specialized double-gasket water seal, scratch-hardened mineral crystal.',
  '<p>The <strong>Welkar Hydro-Lab Submersion Master</strong> undergoes simulated 30M hydrostatic immersion verification to protect the internal precision Japanese caliber from water splashes, rain, and pressure fluctuations.</p>',
  'Men', 'Round Chrono', 'Japanese Quartz 30M', '316L Solid Steel Alloy', 44, 'Black',
  '316L Steel Link', 'Black', '30m / 3 ATM', 2, 'India',
  999.00, 480.00, 1699.00,
  0, NULL, '9102', 18.00, 1, 1, 'Welkar Hydro-Lab Submersion Master Watch', 'Official Hydro-Pressure Submersion Watch.'
),
(
  14, 1, 'Welkar Sunray Teal Dial Silver Metal Strap', 'welkar-sunray-teal-dial-silver-metal-strap', 'W-SUN-TEAL', 'WLK-SUN-TEAL-04',
  'Brushed circular stainless bezel housing a deep charcoal/teal sunburst face with micro-chamfered silver indices.',
  '<p>The <strong>Welkar Sunray Teal Dial</strong> catches natural lighting with shimmering metallic refraction, paired with an ergonomic multi-link bracelet.</p>',
  'Men', 'Round Chrono', 'Japanese Quartz', 'Stainless Steel', 42, 'Silver',
  'Solid Stainless Steel', 'Silver', '30m / 3 ATM', 2, 'India',
  899.00, 420.00, 1449.00,
  0, NULL, '9102', 18.00, 1, 1, 'Welkar Sunray Teal Dial Watch', 'Shop Welkar Sunray Teal Dial.'
),
(
  15, 1, 'Welkar Executive Steel Chrono Dial with Stainless Steel Back', 'welkar-executive-steel-chrono-dial', 'W-EXEC-CHR', 'WLK-EXEC-CHR-05',
  'Laser engraved steel back plate, butterfly security clasp, and multi-subdial aesthetic chronograph styling.',
  '<p>Designed for boardroom refinement. Built with a double-push safety clasp and polished 316L case back plate.</p>',
  'Men', 'Round Chrono', 'Quartz Chronograph', '316L Stainless Steel', 44, 'Silver',
  'Stainless Steel Link', 'Silver', '30m / 3 ATM', 2, 'India',
  849.00, 400.00, 1399.00,
  0, NULL, '9102', 18.00, 1, 1, 'Welkar Executive Steel Chrono Watch', 'Executive Chrono Watch by Welkar.'
),
(
  16, 1, 'Welkar Black-Out Sport Racer with Tachymeter Bezel', 'welkar-black-out-sport-racer', 'W-BLK-RCR', 'WLK-BLK-RCR-06',
  'Motorsport-inspired tachymeter bezel, crimson second hand accent, and impact-shielded casing.',
  '<p>Aggressive track styling featuring black PVD finish and vibrant racing accents.</p>',
  'Men', 'Round Chrono', 'High-Impact Quartz', 'Matte Black PVD', 44, 'Black',
  'Perforated Silicone', 'Black', '50m / 5 ATM', 2, 'India',
  949.00, 460.00, 1599.00,
  0, NULL, '9102', 18.00, 1, 1, 'Welkar Black-Out Sport Racer', 'Shop Black-Out Sport Racer.'
),
(
  17, 1, 'Welkar Women\'s Rose Gold Petite Milanese Mesh Watch', 'welkar-womens-rose-gold-petite-milanese-mesh', 'W-WMN-RSG', 'WLK-WMN-RSG-07',
  'Woven magnetic mesh band with scratch-proof Hardlex crystal and sunray blush champagne dial.',
  '<p>The <strong>Welkar Petite Milanese Rose Gold</strong> weighs beneath 48 grams for featherlight all-day comfort. Features an adjustable magnetic mesh clasp and hypoallergenic ion plating.</p>',
  'Women', 'Minimalist Stick', 'Japanese Quartz Petite', 'Rose Gold PVD Steel', 28, 'Rose Gold',
  'Milanese Magnetic Mesh', 'Rose Gold', '30m / 3 ATM', 2, 'India',
  849.00, 400.00, 1399.00,
  0, NULL, '9102', 18.00, 1, 1, 'Welkar Women\'s Rose Gold Petite Milanese Watch', 'Shop Women\'s Petite Milanese Watch.'
),
(
  18, 1, 'Welkar Women\'s Champagne Sunray Dial Crystal Edition', 'welkar-womens-champagne-sunray-dial-crystal', 'W-WMN-GLD', 'WLK-WMN-GLD-08',
  'Twelve pavé crystal accents set into a warm champagne dial with jewellery link bracelet.',
  '<p>Lustrous elegance tailored for celebratory moments and formal attire. Enclosed in a velvet presentation gift casket.</p>',
  'Women', 'Minimalist Stick', 'Japanese Quartz', 'Champagne Gold Steel', 30, 'Gold',
  'Linked Jewelry Bracelet', 'Champagne Gold', '30m / 3 ATM', 2, 'India',
  899.00, 430.00, 1499.00,
  0, NULL, '9102', 18.00, 1, 1, 'Welkar Women\'s Champagne Sunray Crystal Watch', 'Champagne Crystal Edition Watch.'
),
(
  19, 1, 'Welkar Grace Silver Linked Bracelet Watch', 'welkar-grace-silver-linked-bracelet-watch', 'W-WMN-SLV', 'WLK-WMN-SLV-09',
  'Lustrous pearl white dial housed in mirror-finished hypoallergenic stainless steel linked jewelry bracelet.',
  '<p>Understated classic glamour with effortless daily versatility.</p>',
  'Women', 'Minimalist Stick', 'Japanese Quartz', 'Pure Silver Tone Steel', 28, 'Silver',
  'Linked Jewelry Bracelet', 'Silver', '30m / 3 ATM', 2, 'India',
  799.00, 370.00, 1249.00,
  0, NULL, '9102', 18.00, 1, 1, 'Welkar Grace Silver Linked Watch', 'Grace Silver Linked Bracelet Watch.'
),
(
  20, 1, 'Welkar Youth Dual-Display Chrono Sport Watch - Arctic Blue', 'welkar-youth-dual-display-chrono-sport-arctic-blue', 'W-YTH-BLU', 'WLK-YTH-BLU-10',
  'Arctic Blue casing with 1/100s split lap timer, EL backlight, and 50M swim-proof dual digital-analog display.',
  '<p>Built inside the Welkar Hydro-Pressure Lab with shock-absorbent composite case, food-grade silicone band, and shatter-proof mineral crystal.</p>',
  'Kids', 'Round Chrono', 'Dual Digital-Analog 50M', 'Drop-Safe Resin', 38, 'Blue',
  'Flexible TPU Resin', 'Arctic Blue', '50m / 5 ATM', 2, 'India',
  599.00, 260.00, 999.00,
  0, NULL, '9102', 18.00, 1, 1, 'Welkar Youth Chrono Sport Watch - Arctic Blue', 'Youth Dual-Display Sport Watch.'
),
(
  21, 1, 'Welkar Junior Shock-Guard Digital LED Watch - Blaze Orange', 'welkar-junior-shock-guard-digital-led-blaze-orange', 'W-YTH-ORG', 'WLK-YTH-ORG-11',
  'Blaze Orange reinforced bumper, daily alarm chime, 7-color ambient night light.',
  '<p>Engineered for junior playground adventures with shatter-resistant casing and non-toxic skin-safe silicone strap.</p>',
  'Kids', 'Round Chrono', 'Digital LED Quartz', 'Shock-Guard Resin', 36, 'Orange',
  'Shock Silicone Rubber', 'Blaze Orange', '50m / 5 ATM', 2, 'India',
  499.00, 220.00, 849.00,
  0, NULL, '9102', 18.00, 1, 1, 'Welkar Junior Shock-Guard Watch', 'Junior Digital Watch in Blaze Orange.'
),
(
  22, 1, 'Welkar Stealth Square Monolith Watch', 'welkar-stealth-square-monolith-watch', 'W-UNI-MONO', 'WLK-UNI-MONO-12',
  'All-black dial with precision silver needle accents, flush crown, and monolithic 7.2mm ultra-slim profile.',
  '<p>Gender-neutral 39mm architectural balance calibrated for wrists from 5.5" to 7.5".</p>',
  'Unisex', 'Square Dial', 'Ultra-Slim Quartz', '316L Solid Steel PVD', 39, 'Black',
  'Quick Release Mesh', 'Matte Black', '30m / 3 ATM', 2, 'India',
  899.00, 420.00, 1499.00,
  0, NULL, '9102', 18.00, 1, 1, 'Welkar Stealth Square Monolith Watch', 'Unisex Minimalist Square Watch.'
),
(
  23, 1, 'Welkar Mono Bauhaus 38mm Minimalist Watch', 'welkar-mono-bauhaus-38mm-minimalist-watch', 'W-UNI-BAU', 'WLK-UNI-BAU-13',
  'Matte white clean dial with surgical black hands, numberless face, and quick-release mesh band.',
  '<p>Purity of form and essential function. 38mm case diameter designed to sit flush under French cuffs or knitwear.</p>',
  'Unisex', 'Minimalist Stick', 'Ultra-Slim Quartz', 'Stainless Steel', 38, 'Silver',
  'Quick Release Mesh', 'Silver', '30m / 3 ATM', 2, 'India',
  799.00, 380.00, 1299.00,
  0, NULL, '9102', 18.00, 1, 1, 'Welkar Mono Bauhaus 38mm Watch', 'Minimalist Bauhaus Watch by Welkar.'
),
(
  24, 1, 'Welkar 3-Piece Transparent Travel Washbag & Storage Set', 'welkar-3-piece-transparent-travel-washbag-set', 'W-TRV-BAG', 'WLK-TRV-BAG-14',
  'Waterproof pastel washbag trio with sturdy handles and clear windows for cosmetics, medicine, and watches.',
  '<p>Complete travel kit with Welkar active timepiece protective storage pouches. Includes Large (30x21x10cm), Medium (26x16x7cm), and Small (21x12x7cm) modular compartments.</p>',
  'Unisex', 'Travel Essential', 'Modular Pouch', 'High-Frequency Welded TPU', 0, 'Pastel Blue',
  'Waterproof TPU', 'Pastel Blue', 'Waterproof', 1, 'India',
  699.00, 280.00, 999.00,
  0, NULL, '4202', 18.00, 1, 1, 'Welkar 3-Piece Transparent Travel Washbag Set', 'Travel Storage Organizer Set.'
)
ON DUPLICATE KEY UPDATE
  `name`=VALUES(`name`), `slug`=VALUES(`slug`), `base_price`=VALUES(`base_price`),
  `compare_at_price`=VALUES(`compare_at_price`), `short_description`=VALUES(`short_description`),
  `description`=VALUES(`description`), `gender`=VALUES(`gender`), `dial_type`=VALUES(`dial_type`),
  `movement`=VALUES(`movement`), `is_featured`=1, `is_active`=1;

-- 3. Product Category Associations
DELETE FROM `product_categories` WHERE `product_id` BETWEEN 11 AND 24;
INSERT INTO `product_categories` (`product_id`, `category_id`) VALUES
(11, 1), (11, 4),
(12, 1), (12, 5),
(13, 1), (13, 5),
(14, 1), (14, 4),
(15, 1), (15, 4),
(16, 1), (16, 5),
(17, 2), (17, 4),
(18, 2), (18, 4),
(19, 2),
(20, 6), (20, 5),
(21, 6), (21, 5),
(22, 3), (22, 4),
(23, 3),
(24, 7);

-- 4. Product Images
DELETE FROM `product_images` WHERE `product_id` BETWEEN 11 AND 24;
INSERT INTO `product_images` (`product_id`, `image_url`, `is_primary`, `sort_order`) VALUES
(11, 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=1200&q=80', 1, 1),
(12, 'https://images.unsplash.com/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=1200&q=80', 1, 1),
(13, 'https://images.unsplash.com/photo-1614164185128-e4ec99c436d7?auto=format&fit=crop&w=1200&q=80', 1, 1),
(14, 'https://images.unsplash.com/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=1200&q=80', 1, 1),
(15, 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=1200&q=80', 1, 1),
(16, 'https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?auto=format&fit=crop&w=1200&q=80', 1, 1),
(17, 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=1200&q=80', 1, 1),
(18, 'https://images.unsplash.com/photo-1511367461989-f85a21fda167?auto=format&fit=crop&w=1200&q=80', 1, 1),
(19, 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?auto=format&fit=crop&w=1200&q=80', 1, 1),
(20, 'https://images.unsplash.com/photo-1542496658-e33a6d0d50f6?auto=format&fit=crop&w=1200&q=80', 1, 1),
(21, 'https://images.unsplash.com/photo-1546868871-7041f2a55e12?auto=format&fit=crop&w=1200&q=80', 1, 1),
(22, 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=1200&q=80', 1, 1),
(23, 'https://images.unsplash.com/photo-1539874701005-786335a09b30?auto=format&fit=crop&w=1200&q=80', 1, 1),
(24, 'https://lh3.googleusercontent.com/aida/AEtjO1VcBT_6YsGL6dtFwfsedBCCWhiIpc71zmYV_YcHIr2CMj7ymp2MbsS2o8SUTJ9T6ZgwK41VpCoX_q5FqEIzyV4Bc8mwV2c5m5hh5Sfz_fcyTn0Y5Mn-6PbeiYh6RIciXfBis5IcUcB9gHzAOqsWuQpA5n9MRvPUSTT5rUSC3KrtIn8TJc30MCHOaoDSPV9I--_OEg2sVjX_3TuFTQtyI0LvpSqgZiQKHOqEWu9Jt8oChJYXTHW_JW_t4mmV', 1, 1);

-- 5. Product Default Variants
DELETE FROM `product_variants` WHERE `product_id` BETWEEN 11 AND 24;
INSERT INTO `product_variants` (`id`, `product_id`, `sku`, `variant_name`, `dial_color`, `strap_color`, `price`, `compare_at_price`, `cost_price`, `is_active`) VALUES
(11, 11, 'WLK-STLTH-SQ-01', 'Standard Matte Black', 'Black', 'Matte Black', 899.00, 1499.00, 420.00, 1),
(12, 12, 'WLK-SLV-DD-02', 'Classic Silver / Black', 'Black', 'Silver', 799.00, 1299.00, 380.00, 1),
(13, 13, 'WLK-HYD-SUB-03', 'Hydro-Lab 30M Edition', 'Black', 'Black', 999.00, 1699.00, 480.00, 1),
(14, 14, 'WLK-SUN-TEAL-04', 'Sunray Teal Steel', 'Teal', 'Silver', 899.00, 1449.00, 420.00, 1),
(15, 15, 'WLK-EXEC-CHR-05', 'Executive Steel Chrono', 'Black', 'Silver', 849.00, 1399.00, 400.00, 1),
(16, 16, 'WLK-BLK-RCR-06', 'Black-Out Sport Racer', 'Black', 'Black', 949.00, 1599.00, 460.00, 1),
(17, 17, 'WLK-WMN-RSG-07', 'Petite Rose Gold Mesh', 'Blush', 'Rose Gold', 849.00, 1399.00, 400.00, 1),
(18, 18, 'WLK-WMN-GLD-08', 'Champagne Sunray Crystal', 'Champagne', 'Gold', 899.00, 1499.00, 430.00, 1),
(19, 19, 'WLK-WMN-SLV-09', 'Grace Silver Linked', 'White', 'Silver', 799.00, 1249.00, 370.00, 1),
(20, 20, 'WLK-YTH-BLU-10', 'Arctic Blue Active', 'Blue', 'Arctic Blue', 599.00, 999.00, 260.00, 1),
(21, 21, 'WLK-YTH-ORG-11', 'Blaze Orange Shock', 'Orange', 'Blaze Orange', 499.00, 849.00, 220.00, 1),
(22, 22, 'WLK-UNI-MONO-12', 'Square Monolith Slim', 'Black', 'Black', 899.00, 1499.00, 420.00, 1),
(23, 23, 'WLK-UNI-BAU-13', 'Bauhaus 38mm Clean', 'White', 'Silver', 799.00, 1299.00, 380.00, 1),
(24, 24, 'WLK-TRV-BAG-14', '3-Piece Organizer Kit', 'Clear', 'Pastel Blue', 699.00, 999.00, 280.00, 1)
ON DUPLICATE KEY UPDATE `price`=VALUES(`price`), `sku`=VALUES(`sku`);

-- 6. Inventory Stock (50 units each in central warehouse)
DELETE FROM `inventory_stock` WHERE `variant_id` BETWEEN 11 AND 24;
INSERT INTO `inventory_stock` (`variant_id`, `location_id`, `quantity`, `reserved_quantity`, `low_stock_threshold`) VALUES
(11, 1, 65, 0, 5),
(12, 1, 48, 0, 5),
(13, 1, 35, 0, 5),
(14, 1, 52, 0, 5),
(15, 1, 40, 0, 5),
(16, 1, 30, 0, 5),
(17, 1, 80, 0, 5),
(18, 1, 45, 0, 5),
(19, 1, 60, 0, 5),
(20, 1, 55, 0, 5),
(21, 1, 40, 0, 5),
(22, 1, 70, 0, 5),
(23, 1, 50, 0, 5),
(24, 1, 95, 0, 5);

SET FOREIGN_KEY_CHECKS = 1;
