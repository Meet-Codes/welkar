<?php

declare(strict_types=1);

namespace App\Services\Company;

use App\Support\Database;

class CompanyProfileService
{
    /**
     * Retrieve the verified company profile narrative with safe default fallbacks.
     */
    public static function getProfile(): array
    {
        try {
            $profile = Database::fetch("SELECT * FROM company_profile WHERE id = 1 LIMIT 1");
        } catch (\Throwable $e) {
            $profile = null;
        }

        if (!$profile) {
            return [
                'id' => 1,
                'brand_name' => 'WELKAR',
                'tagline' => 'Precision, Heritage, and Trust—Since 2002.',
                'founding_year' => '2002',
                'hero_title' => 'Precision, Heritage, and Trust—Since 2002.',
                'hero_intro' => 'WELKAR is a watch brand founded in 2002, shaped by an appreciation for design, technical thinking, and the importance of trust. Our story began with two founders bringing complementary perspectives together, united by a vision to build a brand around precision, heritage, and lasting style.',
                'story_title' => 'Our Story',
                'story_intro' => 'WELKAR began in 2002 with the coming together of two complementary perspectives. Mr. Sumit Singh brought an artistic outlook, while Mr. Nevil Sharma brought a technical approach. Together, they established the foundation of a brand shaped by the ideas of precision, heritage, and trust.',
                'story_content' => "Our company story reflects the importance of bringing creative vision and technical thinking together. These principles continue to inform the identity WELKAR seeks to build and the experience it aims to provide its customers.\n\nFrom the outset, our goal has been to create an authentic horological destination where design integrity meets reliable functionality.",
                'philosophy_title' => 'Our Philosophy',
                'philosophy_intro' => 'Guided by a genuine passion for watchmaking, our philosophy centers on timeless elegance, mechanical reliability, rigorous attention to detail, and collaboration with skilled craftspeople.',
                'approach_watches_title' => 'Our Approach to Watches',
                'approach_watches_content' => "We believe a watch is more than an instrument for telling time; it is a personal statement of style and character. Our approach harmonizes distinctive visual aesthetics with everyday comfort and practical utility.\n\nEvery piece in our catalog is selected with keen attention to dial balance, tactile presence, case proportions, and clear technical specifications—giving customers the transparent knowledge they need to choose a timepiece they will be proud to wear for years to come.",
                'quality_title' => 'Our Approach to Quality',
                'quality_content' => "Our commitment to quality begins with clarity, honesty, and verified specifications. Rather than making unsubstantiated claims, we focus on diligent product selection, transparent technical descriptions, and dependable customer information.\n\nWe specify movement calibers, casing materials, and water-resistance metrics straightforwardly, ensuring every customer receives honest value, accessible care guidance, and reliable service.",
                'mission_statement' => 'To offer watches that bring together thoughtful design, practical considerations, and a sense of lasting style, while giving customers clear information and a brand experience they can feel confident about.',
                'vision_statement' => 'To build WELKAR into a watch brand recognised for its identity, enduring design philosophy, and commitment to customer trust.',
                'commitment_title' => 'Our Commitment to Customers',
                'commitment_content' => 'We believe in building lasting relationships grounded in transparency and respect. That means providing comprehensive product information, upfront pricing, responsive customer support, accessible policy terms, and straightforward post-purchase assistance.',
                'journey_ahead_title' => 'The Journey Ahead',
                'journey_ahead_content' => 'Our journey continues with the same principles that define our identity: precision, heritage, and trust. We aim to keep building a thoughtful brand experience, communicate clearly with our customers, and offer watches people can feel proud to wear.',
                'home_summary' => 'Founded in 2002 by Mr. Sumit Singh and Mr. Nevil Sharma, WELKAR unites artistic design with technical perspective to craft watches defined by precision, heritage, and trust.',
                'footer_summary' => 'Founded in 2002, WELKAR unites creative vision and technical precision to deliver timepieces built on heritage and trust.',
                'pdp_summary' => 'Founded in 2002, WELKAR creates timepieces balancing thoughtful design, practical utility, and transparent quality.',
                'collection_summary' => "Thoughtfully curated timepieces reflecting WELKAR's heritage of precision, balanced proportions, and timeless styling.",
                'seo_title' => 'About Welkar | Precision, Heritage, and Trust—Since 2002',
                'seo_description' => 'Learn the story of WELKAR, founded in 2002 by Mr. Sumit Singh and Mr. Nevil Sharma on the principles of precision, heritage, and lasting trust.',
            ];
        }

        return $profile;
    }

    /**
     * Retrieve all published founders ordered by display order.
     */
    public static function getFounders(bool $publishedOnly = true): array
    {
        try {
            $sql = "SELECT * FROM company_founders";
            if ($publishedOnly) {
                $sql .= " WHERE is_published = 1";
            }
            $sql .= " ORDER BY display_order ASC, id ASC";

            $rows = Database::fetchAll($sql);
            if (!empty($rows)) {
                return $rows;
            }
        } catch (\Throwable $e) {
            // Safe fallback
        }

        return [
            [
                'id' => 1,
                'name' => 'Mr. Sumit Singh',
                'designation' => 'Co-Founder',
                'perspective' => 'Artistic Perspective',
                'bio' => "Mr. Sumit Singh is one of WELKAR's founders. His artistic perspective represents the creative side of the founding partnership and the importance of visual character, design expression, and attention to detail in the brand's identity.",
                'image_url' => null,
                'image_alt' => 'Mr. Sumit Singh, Co-Founder of WELKAR',
                'display_order' => 1,
                'is_published' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Mr. Nevil Sharma',
                'designation' => 'Co-Founder',
                'perspective' => 'Technical Perspective',
                'bio' => "Mr. Nevil Sharma is one of WELKAR's founders. His technical perspective represents the problem-solving and technical thinking that complement the brand's artistic outlook.",
                'image_url' => null,
                'image_alt' => 'Mr. Nevil Sharma, Co-Founder of WELKAR',
                'display_order' => 2,
                'is_published' => 1,
            ]
        ];
    }

    /**
     * Retrieve all published core values.
     */
    public static function getValues(bool $publishedOnly = true): array
    {
        try {
            $sql = "SELECT * FROM company_values";
            if ($publishedOnly) {
                $sql .= " WHERE is_published = 1";
            }
            $sql .= " ORDER BY display_order ASC, id ASC";

            $rows = Database::fetchAll($sql);
            if (!empty($rows)) {
                return $rows;
            }
        } catch (\Throwable $e) {
            // Safe fallback
        }

        return [
            ['id' => 1, 'name' => 'Precision', 'tagline' => 'Attention to Detail & Accuracy', 'description' => 'We prioritize careful attention to product specifications, honest presentation, and transparent documentation across every interaction.', 'icon_class' => 'bi-bullseye', 'display_order' => 1, 'is_published' => 1],
            ['id' => 2, 'name' => 'Heritage', 'tagline' => 'Respect for our 2002 Origin', 'description' => 'Our journey began in 2002. That history forms an essential part of our brand identity, guiding our evolution while honoring our founding roots.', 'icon_class' => 'bi-hourglass-split', 'display_order' => 2, 'is_published' => 1],
            ['id' => 3, 'name' => 'Trust', 'tagline' => 'Honest Communication & Transparency', 'description' => 'We earn customer confidence through truthful product descriptions, clear policies, and dependable after-sales care.', 'icon_class' => 'bi-shield-check', 'display_order' => 3, 'is_published' => 1],
            ['id' => 4, 'name' => 'Timelessness', 'tagline' => 'Enduring Style Over Passing Fads', 'description' => 'We appreciate classic horological aesthetics and create watches designed to gracefully accompany our customers through every season of life.', 'icon_class' => 'bi-compass', 'display_order' => 4, 'is_published' => 1],
            ['id' => 5, 'name' => 'Responsibility', 'tagline' => 'Fair & Accountable Customer Relations', 'description' => 'We commit to communicating responsibly, addressing customer needs with integrity, and treating every patron fairly.', 'icon_class' => 'bi-handshake', 'display_order' => 5, 'is_published' => 1],
        ];
    }

    /**
     * Retrieve all authentic verified milestones.
     */
    public static function getMilestones(bool $publishedOnly = true): array
    {
        try {
            $sql = "SELECT * FROM company_milestones";
            if ($publishedOnly) {
                $sql .= " WHERE is_published = 1";
            }
            $sql .= " ORDER BY display_order ASC, year_date ASC";

            $rows = Database::fetchAll($sql);
            if (!empty($rows)) {
                return $rows;
            }
        } catch (\Throwable $e) {
            // Safe fallback
        }

        return [
            [
                'id' => 1,
                'year_date' => '2002',
                'title' => 'Founding of WELKAR',
                'description' => 'Mr. Sumit Singh and Mr. Nevil Sharma establish WELKAR, bringing artistic vision and technical problem-solving together to create a brand founded on precision, heritage, and trust.',
                'image_url' => null,
                'display_order' => 1,
                'is_published' => 1,
            ]
        ];
    }

    /**
     * Update the core profile narrative.
     */
    public static function updateProfile(array $data): bool
    {
        $fields = [
            'brand_name', 'tagline', 'founding_year', 'hero_title', 'hero_intro',
            'story_title', 'story_intro', 'story_content',
            'philosophy_title', 'philosophy_intro',
            'approach_watches_title', 'approach_watches_content',
            'quality_title', 'quality_content',
            'mission_statement', 'vision_statement',
            'commitment_title', 'commitment_content',
            'journey_ahead_title', 'journey_ahead_content',
            'home_summary', 'footer_summary', 'pdp_summary', 'collection_summary',
            'seo_title', 'seo_description'
        ];

        $sets = [];
        $params = [];

        foreach ($fields as $f) {
            if (isset($data[$f])) {
                $sets[] = "{$f} = ?";
                $params[] = trim((string)$data[$f]);
            }
        }

        if (empty($sets)) {
            return false;
        }

        $params[] = 1; // ID = 1
        $sql = "UPDATE company_profile SET " . implode(', ', $sets) . " WHERE id = ?";
        Database::execute($sql, $params);

        return true;
    }

    /**
     * Update or insert a founder profile.
     */
    public static function saveFounder(array $data, ?int $id = null): int
    {
        $name = trim((string)($data['name'] ?? ''));
        $designation = trim((string)($data['designation'] ?? 'Co-Founder'));
        $perspective = trim((string)($data['perspective'] ?? ''));
        $bio = trim((string)($data['bio'] ?? ''));
        $imageUrl = !empty($data['image_url']) ? trim((string)$data['image_url']) : null;
        $imageAlt = !empty($data['image_alt']) ? trim((string)$data['image_alt']) : null;
        $order = (int)($data['display_order'] ?? 0);
        $isPublished = isset($data['is_published']) ? (int)$data['is_published'] : 1;

        if ($id && $id > 0) {
            Database::execute(
                "UPDATE company_founders 
                 SET name = ?, designation = ?, perspective = ?, bio = ?, image_url = ?, image_alt = ?, display_order = ?, is_published = ? 
                 WHERE id = ?",
                [$name, $designation, $perspective, $bio, $imageUrl, $imageAlt, $order, $isPublished, $id]
            );
            return $id;
        }

        Database::execute(
            "INSERT INTO company_founders (name, designation, perspective, bio, image_url, image_alt, display_order, is_published) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
            [$name, $designation, $perspective, $bio, $imageUrl, $imageAlt, $order, $isPublished]
        );

        $lastId = Database::fetch("SELECT LAST_INSERT_ID() as id");
        return (int)($lastId['id'] ?? 0);
    }

    /**
     * Update or insert a milestone.
     */
    public static function saveMilestone(array $data, ?int $id = null): int
    {
        $yearDate = trim((string)($data['year_date'] ?? ''));
        $title = trim((string)($data['title'] ?? ''));
        $desc = trim((string)($data['description'] ?? ''));
        $imageUrl = !empty($data['image_url']) ? trim((string)$data['image_url']) : null;
        $order = (int)($data['display_order'] ?? 0);
        $isPublished = isset($data['is_published']) ? (int)$data['is_published'] : 1;

        if ($id && $id > 0) {
            Database::execute(
                "UPDATE company_milestones 
                 SET year_date = ?, title = ?, description = ?, image_url = ?, display_order = ?, is_published = ? 
                 WHERE id = ?",
                [$yearDate, $title, $desc, $imageUrl, $order, $isPublished, $id]
            );
            return $id;
        }

        Database::execute(
            "INSERT INTO company_milestones (year_date, title, description, image_url, display_order, is_published) 
             VALUES (?, ?, ?, ?, ?, ?)",
            [$yearDate, $title, $desc, $imageUrl, $order, $isPublished]
        );

        $lastId = Database::fetch("SELECT LAST_INSERT_ID() as id");
        return (int)($lastId['id'] ?? 0);
    }

    /**
     * Delete a milestone.
     */
    public static function deleteMilestone(int $id): bool
    {
        Database::execute("DELETE FROM company_milestones WHERE id = ?", [$id]);
        return true;
    }
}
