-- Welkar Seeder 003: Verified Company Profile Data
-- Source of Truth: Precision, Heritage, and Trust—Since 2002

INSERT INTO `company_profile` (
  `id`, `brand_name`, `tagline`, `founding_year`, `hero_title`, `hero_intro`,
  `story_title`, `story_intro`, `story_content`,
  `philosophy_title`, `philosophy_intro`,
  `approach_watches_title`, `approach_watches_content`,
  `quality_title`, `quality_content`,
  `mission_statement`, `vision_statement`,
  `commitment_title`, `commitment_content`,
  `journey_ahead_title`, `journey_ahead_content`,
  `home_summary`, `footer_summary`, `pdp_summary`, `collection_summary`,
  `seo_title`, `seo_description`
) VALUES (
  1,
  'WELKAR',
  'Precision, Heritage, and Trust—Since 2002.',
  '2002',
  'Precision, Heritage, and Trust—Since 2002.',
  'WELKAR is a watch brand founded in 2002, shaped by an appreciation for design, technical thinking, and the importance of trust. Our story began with two founders bringing complementary perspectives together, united by a vision to build a brand around precision, heritage, and lasting style.',
  'Our Story',
  'WELKAR began in 2002 with the coming together of two complementary perspectives. Mr. Sumit Singh brought an artistic outlook, while Mr. Nevil Sharma brought a technical approach. Together, they established the foundation of a brand shaped by the ideas of precision, heritage, and trust.',
  'Our company story reflects the importance of bringing creative vision and technical thinking together. These principles continue to inform the identity WELKAR seeks to build and the experience it aims to provide its customers.\n\nFrom the outset, our goal has been to create an authentic horological destination where design integrity meets reliable functionality. By combining artistic expression with structured problem-solving, WELKAR continues to offer timepieces designed to complement daily life with poise and dependability.',
  'Our Philosophy',
  'Guided by a genuine passion for watchmaking, our philosophy centers on timeless elegance, mechanical reliability, rigorous attention to detail, and collaboration with skilled craftspeople.',
  'Our Approach to Watches',
  'We believe a watch is more than an instrument for telling time; it is a personal statement of style and character. Our approach harmonizes distinctive visual aesthetics with everyday comfort and practical utility.\n\nEvery piece in our catalog is selected with keen attention to dial balance, tactile presence, case proportions, and clear technical specifications—giving customers the transparent knowledge they need to choose a timepiece they will be proud to wear for years to come.',
  'Our Approach to Quality',
  'Our commitment to quality begins with clarity, honesty, and verified specifications. Rather than making unsubstantiated claims, we focus on diligent product selection, transparent technical descriptions, and dependable customer information.\n\nWe specify movement calibers, casing materials, and water-resistance metrics straightforwardly, ensuring every customer receives honest value, accessible care guidance, and reliable service.',
  'To offer watches that bring together thoughtful design, practical considerations, and a sense of lasting style, while giving customers clear information and a brand experience they can feel confident about.',
  'To build WELKAR into a watch brand recognised for its identity, enduring design philosophy, and commitment to customer trust.',
  'Our Commitment to Customers',
  'We believe in building lasting relationships grounded in transparency and respect. That means providing comprehensive product information, upfront pricing, responsive customer support, accessible policy terms, and straightforward post-purchase assistance.',
  'The Journey Ahead',
  'Our journey continues with the same principles that define our identity: precision, heritage, and trust. We aim to keep building a thoughtful brand experience, communicate clearly with our customers, and offer watches people can feel proud to wear.',
  'Founded in 2002 by Mr. Sumit Singh and Mr. Nevil Sharma, WELKAR unites artistic design with technical perspective to craft watches defined by precision, heritage, and trust.',
  'Founded in 2002, WELKAR unites creative vision and technical precision to deliver timepieces built on heritage and trust.',
  'Founded in 2002, WELKAR creates timepieces balancing thoughtful design, practical utility, and transparent quality.',
  'Thoughtfully curated timepieces reflecting WELKAR\'s heritage of precision, balanced proportions, and timeless styling.',
  'About WELKAR | Precision, Heritage, and Trust—Since 2002',
  'Discover WELKAR, an Indian watch brand founded in 2002 by Mr. Sumit Singh and Mr. Nevil Sharma, defined by precision, heritage, and customer trust.'
)
ON DUPLICATE KEY UPDATE
  `brand_name`=VALUES(`brand_name`),
  `tagline`=VALUES(`tagline`),
  `founding_year`=VALUES(`founding_year`),
  `hero_title`=VALUES(`hero_title`),
  `hero_intro`=VALUES(`hero_intro`),
  `story_title`=VALUES(`story_title`),
  `story_intro`=VALUES(`story_intro`),
  `story_content`=VALUES(`story_content`),
  `philosophy_title`=VALUES(`philosophy_title`),
  `philosophy_intro`=VALUES(`philosophy_intro`),
  `approach_watches_title`=VALUES(`approach_watches_title`),
  `approach_watches_content`=VALUES(`approach_watches_content`),
  `quality_title`=VALUES(`quality_title`),
  `quality_content`=VALUES(`quality_content`),
  `mission_statement`=VALUES(`mission_statement`),
  `vision_statement`=VALUES(`vision_statement`),
  `commitment_title`=VALUES(`commitment_title`),
  `commitment_content`=VALUES(`commitment_content`),
  `journey_ahead_title`=VALUES(`journey_ahead_title`),
  `journey_ahead_content`=VALUES(`journey_ahead_content`),
  `home_summary`=VALUES(`home_summary`),
  `footer_summary`=VALUES(`footer_summary`),
  `pdp_summary`=VALUES(`pdp_summary`),
  `collection_summary`=VALUES(`collection_summary`),
  `seo_title`=VALUES(`seo_title`),
  `seo_description`=VALUES(`seo_description`);

-- Founders Seed Data (Equal Visual Importance, Verified Facts Only)
DELETE FROM `company_founders`;
INSERT INTO `company_founders` (`id`, `name`, `designation`, `perspective`, `bio`, `image_url`, `image_alt`, `display_order`, `is_published`) VALUES
(
  1,
  'Mr. Sumit Singh',
  'Co-Founder',
  'Artistic Perspective',
  'Mr. Sumit Singh is one of WELKAR\'s founders. His artistic perspective represents the creative side of the founding partnership and the importance of visual character, design expression, and attention to detail in the brand\'s identity.',
  NULL,
  'Mr. Sumit Singh, Co-Founder of WELKAR',
  1,
  1
),
(
  2,
  'Mr. Nevil Sharma',
  'Co-Founder',
  'Technical Perspective',
  'Mr. Nevil Sharma is one of WELKAR\'s founders. His technical perspective represents the problem-solving and technical thinking that complement the brand\'s artistic outlook.',
  NULL,
  'Mr. Nevil Sharma, Co-Founder of WELKAR',
  2,
  1
);

-- Core Values Seed Data (5 Editorial Blocks)
DELETE FROM `company_values`;
INSERT INTO `company_values` (`id`, `name`, `tagline`, `description`, `icon_class`, `display_order`, `is_published`) VALUES
(
  1,
  'Precision',
  'Attention to Detail & Accuracy',
  'We prioritize careful attention to product specifications, honest presentation, and transparent documentation across every interaction.',
  'bi-bullseye',
  1,
  1
),
(
  2,
  'Heritage',
  'Respect for our 2002 Origin',
  'Our journey began in 2002. That history forms an essential part of our brand identity, guiding our evolution while honoring our founding roots.',
  'bi-hourglass-split',
  2,
  1
),
(
  3,
  'Trust',
  'Honest Communication & Transparency',
  'We earn customer confidence through truthful product descriptions, clear policies, and dependable after-sales care.',
  'bi-shield-check',
  3,
  1
),
(
  4,
  'Timelessness',
  'Enduring Style Over Passing Fads',
  'We appreciate classic horological aesthetics and create watches designed to gracefully accompany our customers through every season of life.',
  'bi-compass',
  4,
  1
),
(
  5,
  'Responsibility',
  'Fair & Accountable Customer Relations',
  'We commit to communicating responsibly, addressing customer needs with integrity, and treating every patron fairly.',
  'bi-handshake',
  5,
  1
);

-- Authentic Milestones (Only Verified Milestones, No Fabricated Events)
DELETE FROM `company_milestones`;
INSERT INTO `company_milestones` (`id`, `year_date`, `title`, `description`, `image_url`, `display_order`, `is_published`) VALUES
(
  1,
  '2002',
  'Founding of WELKAR',
  'Mr. Sumit Singh and Mr. Nevil Sharma establish WELKAR, bringing artistic vision and technical problem-solving together to create a brand founded on precision, heritage, and trust.',
  NULL,
  1,
  1
);

-- Update Content Pages slug 'about-us' to align with master facts
UPDATE `content_pages`
SET
  `title` = 'About WELKAR — Precision, Heritage, and Trust—Since 2002',
  `meta_title` = 'About WELKAR | Precision, Heritage, and Trust—Since 2002',
  `meta_description` = 'Discover the founding story of WELKAR, established in 2002 by Mr. Sumit Singh and Mr. Nevil Sharma, guided by artistic design, technical perspective, and trust.',
  `content_html` = '<h2>Precision, Heritage, and Trust—Since 2002</h2><p>WELKAR is a watch brand founded in 2002, shaped by an appreciation for design, technical thinking, and the importance of trust. Our story began with two founders—Mr. Sumit Singh and Mr. Nevil Sharma—bringing complementary artistic and technical perspectives together.</p><p><a href=\"/about\" class=\"btn btn-sm btn-outline-dark mt-2\">Read the full interactive company profile &rarr;</a></p>'
WHERE `slug` = 'about-us';
