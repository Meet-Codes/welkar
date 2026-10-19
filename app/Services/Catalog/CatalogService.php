<?php

namespace App\Services\Catalog;

use App\Support\Database;

class CatalogService
{
    public static function getDialTypes(): array
    {
        return Database::query(
            "SELECT p.dial_type, COUNT(p.id) AS product_count,
                    COALESCE(MIN(pi.image_url), '/assets/images/dial-styles/classic.jpg') AS sample_image
             FROM products p
             LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_primary = 1
             WHERE p.is_active = 1 AND p.dial_type IS NOT NULL AND p.dial_type != ''
             GROUP BY p.dial_type
             ORDER BY product_count DESC"
        );
    }

    public static function getCategories(): array
    {
        return Database::query(
            "SELECT c.*, COUNT(pc.product_id) AS product_count
             FROM categories c
             LEFT JOIN product_categories pc ON c.id = pc.category_id
             WHERE c.is_active = 1
             GROUP BY c.id
             ORDER BY c.sort_order ASC, c.name ASC"
        );
    }

    public static function getBrands(): array
    {
        return Database::query(
            "SELECT b.*, COUNT(p.id) AS product_count
             FROM brands b
             LEFT JOIN products p ON b.id = p.brand_id AND p.is_active = 1
             WHERE b.is_active = 1
             GROUP BY b.id
             ORDER BY b.is_featured DESC, b.name ASC"
        );
    }

    public static function getFeaturedProducts(int $limit = 6): array
    {
        return self::getProductsList("WHERE p.is_active = 1 AND p.is_featured = 1", [], "ORDER BY p.id DESC", $limit);
    }

    public static function getBestSellers(int $limit = 6): array
    {
        return self::getProductsList("WHERE p.is_active = 1", [], "ORDER BY p.id ASC", $limit);
    }

    public static function getNewArrivals(int $limit = 6): array
    {
        return self::getProductsList("WHERE p.is_active = 1", [], "ORDER BY p.created_at DESC", $limit);
    }

    public static function getFilteredProducts(array $filters = [], int $page = 1, int $perPage = 12): array
    {
        $where = ["p.is_active = 1"];
        $params = [];

        if (!empty($filters['search'])) {
            $s = '%' . trim($filters['search']) . '%';
            $where[] = "(p.name LIKE ? OR p.model_number LIKE ? OR b.name LIKE ? OR p.base_sku LIKE ? OR p.dial_type LIKE ?)";
            $params[] = $s;
            $params[] = $s;
            $params[] = $s;
            $params[] = $s;
            $params[] = $s;
        }

        if (!empty($filters['category'])) {
            $where[] = "EXISTS (SELECT 1 FROM product_categories pc JOIN categories c ON pc.category_id = c.id WHERE pc.product_id = p.id AND c.slug = ?)";
            $params[] = $filters['category'];
        }

        if (!empty($filters['brand'])) {
            $where[] = "b.slug = ?";
            $params[] = $filters['brand'];
        }

        if (!empty($filters['dial_type'])) {
            $where[] = "p.dial_type = ?";
            $params[] = $filters['dial_type'];
        }

        if (!empty($filters['gender'])) {
            $where[] = "p.gender = ?";
            $params[] = $filters['gender'];
        }

        if (!empty($filters['min_price'])) {
            $where[] = "p.base_price >= ?";
            $params[] = (float)$filters['min_price'];
        }

        if (!empty($filters['max_price'])) {
            $where[] = "p.base_price <= ?";
            $params[] = (float)$filters['max_price'];
        }

        if (!empty($filters['movement'])) {
            $where[] = "p.movement = ?";
            $params[] = $filters['movement'];
        }

        if (!empty($filters['strap_material'])) {
            $where[] = "p.strap_material = ?";
            $params[] = $filters['strap_material'];
        }

        if (!empty($filters['is_discounted'])) {
            $where[] = "p.compare_at_price > p.base_price";
        }

        $whereClause = "WHERE " . implode(" AND ", $where);

        // Sorting
        $orderBy = match ($filters['sort'] ?? 'featured') {
            'price_asc' => "ORDER BY p.base_price ASC",
            'price_desc' => "ORDER BY p.base_price DESC",
            'newest' => "ORDER BY p.created_at DESC",
            'name_asc' => "ORDER BY p.name ASC",
            default => "ORDER BY p.is_featured DESC, p.id DESC",
        };

        // Total count
        $countSql = "SELECT COUNT(DISTINCT p.id) AS total
                     FROM products p
                     JOIN brands b ON p.brand_id = b.id
                     {$whereClause}";
        $totalRow = Database::fetch($countSql, $params);
        $totalItems = (int)($totalRow['total'] ?? 0);

        // Pagination
        $offset = max(0, ($page - 1) * $perPage);
        $products = self::getProductsList($whereClause, $params, $orderBy, $perPage, $offset);

        return [
            'products' => $products,
            'total' => $totalItems,
            'page' => $page,
            'per_page' => $perPage,
            'last_page' => (int)ceil($totalItems / $perPage),
        ];
    }

    private static function getProductsList(
        string $whereClause,
        array $params,
        string $orderBy,
        int $limit = 12,
        int $offset = 0
    ): array {
        $groupConcat = Database::isPgsql()
            ? "STRING_AGG(DISTINCT c.slug, ',')"
            : "GROUP_CONCAT(DISTINCT c.slug SEPARATOR ',')";

        $sql = "SELECT p.*, b.name AS brand_name, b.slug AS brand_slug,
                       COALESCE(pi.image_url, '/assets/images/products/placeholder.jpg') AS primary_image,
                       COALESCE(SUM(s.quantity), 0) AS total_stock,
                       MIN(pv.id) AS default_variant_id,
                       MIN(pv.price) AS display_price,
                       COALESCE(AVG(r.rating), 5.0) AS avg_rating,
                       COUNT(r.id) AS review_count,
                       {$groupConcat} AS category_slugs
                FROM products p
                JOIN brands b ON p.brand_id = b.id
                LEFT JOIN product_variants pv ON p.id = pv.product_id AND pv.is_active = 1
                LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_primary = 1
                LEFT JOIN inventory_stock s ON pv.id = s.variant_id
                LEFT JOIN reviews r ON p.id = r.product_id AND r.status = 'approved'
                LEFT JOIN product_categories pc ON p.id = pc.product_id
                LEFT JOIN categories c ON pc.category_id = c.id
                {$whereClause}
                GROUP BY p.id, b.name, b.slug, pi.image_url
                {$orderBy}
                LIMIT {$limit} OFFSET {$offset}";

        return Database::query($sql, $params);
    }

    public static function getProductBySlug(string $slug): ?array
    {
        $product = Database::fetch(
            "SELECT p.*, b.name AS brand_name, b.slug AS brand_slug, b.logo_url AS brand_logo
             FROM products p
             JOIN brands b ON p.brand_id = b.id
             WHERE p.slug = ? AND p.is_active = 1",
            [$slug]
        );

        if (!$product) {
            return null;
        }

        // Images
        $images = Database::query(
            "SELECT * FROM product_images WHERE product_id = ? ORDER BY is_primary DESC, sort_order ASC",
            [$product['id']]
        );

        // Variants with stock
        $variants = Database::query(
            "SELECT pv.*, COALESCE(s.quantity, 0) AS stock_quantity
             FROM product_variants pv
             LEFT JOIN inventory_stock s ON pv.id = s.variant_id
             WHERE pv.product_id = ? AND pv.is_active = 1
             ORDER BY pv.price ASC, pv.id ASC",
            [$product['id']]
        );

        // Specifications
        $specifications = Database::query(
            "SELECT * FROM product_specifications WHERE product_id = ? ORDER BY sort_order ASC",
            [$product['id']]
        );

        // Reviews
        $reviews = Database::query(
            "SELECT * FROM reviews WHERE product_id = ? AND status = 'approved' ORDER BY helpful_count DESC, created_at DESC",
            [$product['id']]
        );

        // Review Stats (Distribution, Averages, Customer Photos)
        $reviewStats = \App\Services\Reviews\ReviewService::getProductReviewStats((int)$product['id']);

        // Related products
        $related = Database::query(
            "SELECT p.*, b.name AS brand_name,
                    COALESCE(pi.image_url, '/assets/images/products/placeholder.jpg') AS primary_image,
                    MIN(pv.id) AS default_variant_id
             FROM products p
             JOIN brands b ON p.brand_id = b.id
             LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_primary = 1
             LEFT JOIN product_variants pv ON p.id = pv.product_id
             WHERE p.is_active = 1 AND (p.brand_id = ? OR p.dial_type = ?) AND p.id != ?
             GROUP BY p.id, b.name, pi.image_url
             LIMIT 4",
            [$product['brand_id'], $product['dial_type'], $product['id']]
        );

        // A+ Modules
        $modules = Database::query(
            "SELECT * FROM product_page_modules WHERE product_id = ? AND is_enabled = 1 ORDER BY sort_order ASC",
            [$product['id']]
        );

        // Hotspots (Anatomy markers)
        $hotspots = Database::query(
            "SELECT * FROM product_hotspots WHERE product_id = ? AND is_active = 1 ORDER BY sort_order ASC",
            [$product['id']]
        );

        // Curated FAQs
        $faqs = Database::query(
            "SELECT * FROM product_faqs WHERE (product_id = ? OR product_id IS NULL) AND is_active = 1 ORDER BY sort_order ASC",
            [$product['id']]
        );

        // Comparison Matrix
        $relatedIds = array_column($related, 'id');
        $comparison = \App\Services\Reviews\ReviewService::getComparisonMatrix((int)$product['id'], $relatedIds);

        return [
            'product' => $product,
            'images' => $images,
            'variants' => $variants,
            'specifications' => $specifications,
            'reviews' => $reviews,
            'review_stats' => $reviewStats,
            'related' => $related,
            'modules' => $modules,
            'hotspots' => $hotspots,
            'faqs' => $faqs,
            'comparison' => $comparison,
        ];
    }

    public static function searchAutocomplete(string $term, int $limit = 6): array
    {
        $termClean = '%' . trim($term) . '%';
        return Database::query(
            "SELECT p.id, p.name, p.slug, p.base_price, p.dial_type, b.name AS brand_name,
                    COALESCE(pi.image_url, '/assets/images/products/placeholder.jpg') AS image_url
             FROM products p
             JOIN brands b ON p.brand_id = b.id
             LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_primary = 1
             WHERE p.is_active = 1 AND (p.name LIKE ? OR p.dial_type LIKE ? OR b.name LIKE ? OR p.base_sku LIKE ?)
             LIMIT {$limit}",
            [$termClean, $termClean, $termClean, $termClean]
        );
    }
}
