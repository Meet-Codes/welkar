<?php

declare(strict_types=1);

namespace App\Services\Reviews;

use App\Support\Database;
use App\Support\Session;

class ReviewService
{
    /**
     * Get review summary stats including distribution and averages
     */
    public static function getProductReviewStats(int $productId): array
    {
        $stats = [
            'total' => 0,
            'average' => 5.0,
            'stars' => [
                5 => ['count' => 0, 'percent' => 0],
                4 => ['count' => 0, 'percent' => 0],
                3 => ['count' => 0, 'percent' => 0],
                2 => ['count' => 0, 'percent' => 0],
                1 => ['count' => 0, 'percent' => 0],
            ],
            'photos' => []
        ];

        $counts = Database::fetchAll(
            "SELECT rating, COUNT(id) as count 
             FROM reviews 
             WHERE product_id = ? AND status = 'approved' 
             GROUP BY rating",
            [$productId]
        );

        $total = 0;
        $weightedSum = 0;

        foreach ($counts as $c) {
            $r = (int)$c['rating'];
            $cnt = (int)$c['count'];
            if (isset($stats['stars'][$r])) {
                $stats['stars'][$r]['count'] = $cnt;
            }
            $total += $cnt;
            $weightedSum += ($r * $cnt);
        }

        $stats['total'] = $total;
        if ($total > 0) {
            $stats['average'] = round($weightedSum / $total, 1);
            foreach ($stats['stars'] as $r => &$data) {
                $data['percent'] = round(($data['count'] / $total) * 100);
            }
        }

        // Fetch customer uploaded photos
        $photoRows = Database::fetchAll(
            "SELECT id, images_json, author_name, rating 
             FROM reviews 
             WHERE product_id = ? AND status = 'approved' AND images_json IS NOT NULL AND images_json != ''
             ORDER BY helpful_count DESC, id DESC LIMIT 12",
            [$productId]
        );

        foreach ($photoRows as $pr) {
            $imgs = json_decode($pr['images_json'], true);
            if (is_array($imgs)) {
                foreach ($imgs as $imgUrl) {
                    $stats['photos'][] = [
                        'review_id' => $pr['id'],
                        'url' => $imgUrl,
                        'author' => $pr['author_name'],
                        'rating' => $pr['rating']
                    ];
                }
            }
        }

        return $stats;
    }

    /**
     * Upload an image file for reviews (avatars or wristshots)
     */
    public static function uploadReviewFile(string $fieldName, string $folder = 'wristshots'): ?string
    {
        if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $file = $_FILES[$fieldName];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);

        if (!in_array($mime, $allowedTypes, true)) {
            return null;
        }

        $ext = match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            'image/gif'  => 'gif',
            default      => 'jpg',
        };

        $uploadDir = __DIR__ . '/../../../public/uploads/reviews/' . $folder;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = 'rev_' . $folder . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $destPath = $uploadDir . '/' . $fileName;

        if (move_uploaded_file($file['tmp_name'], $destPath)) {
            return '/uploads/reviews/' . $folder . '/' . $fileName;
        }

        return null;
    }

    /**
     * Submit a customer review with genuine server-verified delivered purchase checking
     */
    public static function submitReview(array $data): array
    {
        $productId = (int)($data['product_id'] ?? 0);
        $authorName = trim((string)($data['author_name'] ?? ''));
        $rating = max(1, min(5, (int)($data['rating'] ?? 5)));
        $title = trim((string)($data['title'] ?? ''));
        $comment = trim((string)($data['comment'] ?? ''));
        $orderNumber = trim((string)($data['order_number'] ?? ''));
        $avatarUrl = trim((string)($data['avatar_url'] ?? ''));

        if ($productId <= 0 || empty($authorName) || empty($comment)) {
            return ['success' => false, 'message' => 'Please provide your name, star rating, and review comment.'];
        }

        // Rule 8: Only customer who purchased that product can review, and only for that particular order
        if (empty($orderNumber)) {
            return [
                'success' => false,
                'message' => 'Order Number is required. Only verified buyers who received this timepiece can submit a review.'
            ];
        }

        $order = Database::fetch(
            "SELECT o.id, o.order_number, o.status, o.user_id, o.guest_email 
             FROM orders o 
             JOIN order_items oi ON o.id = oi.order_id 
             WHERE o.order_number = ? AND oi.product_id = ?",
            [$orderNumber, $productId]
        );

        if (!$order) {
            return [
                'success' => false,
                'message' => "Order #{$orderNumber} does not match any record for this timepiece. Only verified purchasers can review."
            ];
        }

        // Rule 9: Only when customer received the product (status = 'delivered') can they review
        if ($order['status'] !== 'delivered') {
            $currentStatus = ucfirst($order['status']);
            return [
                'success' => false,
                'message' => "Your order is currently {$currentStatus}. You can submit your review once the courier marks the timepiece as Delivered."
            ];
        }

        // Prevent duplicate review for the same order and product
        $orderId = (int)$order['id'];
        $alreadyReviewed = Database::fetch(
            "SELECT id FROM reviews WHERE order_id = ? AND product_id = ?",
            [$orderId, $productId]
        );
        if ($alreadyReviewed) {
            return [
                'success' => false,
                'message' => "A review has already been submitted for this order. Thank you for your feedback!"
            ];
        }

        // Handle uploaded wristshot photo
        $uploadedWristshot = self::uploadReviewFile('review_image', 'wristshots');
        $images = [];
        if ($uploadedWristshot) {
            $images[] = $uploadedWristshot;
        } elseif (!empty($data['wristshot_url'])) {
            $images[] = trim((string)$data['wristshot_url']);
        }
        $imagesJson = !empty($images) ? json_encode($images) : null;

        // Handle avatar upload
        $uploadedAvatar = self::uploadReviewFile('avatar_file', 'avatars');
        if ($uploadedAvatar) {
            $avatarUrl = $uploadedAvatar;
        }

        // Rule 5: Customer reviews go to pending moderation before getting published
        $status = 'pending';

        Database::execute(
            "INSERT INTO reviews (product_id, author_name, avatar_url, rating, title, comment, is_verified_purchase, order_id, user_id, images_json, status, created_at) 
             VALUES (?, ?, ?, ?, ?, ?, 1, ?, ?, ?, ?, NOW())",
            [$productId, $authorName, $avatarUrl, $rating, $title, $comment, $orderId, $order['user_id'] ?? null, $imagesJson, $status]
        );

        return [
            'success' => true,
            'message' => 'Thank you! Your verified purchase review has been submitted for atelier moderation and will appear on the store and homepage once approved.'
        ];
    }

    /**
     * Admin update review
     */
    public static function updateReview(int $reviewId, array $data): bool
    {
        $existing = Database::fetch("SELECT * FROM reviews WHERE id = ?", [$reviewId]);
        if (!$existing) {
            return false;
        }

        $authorName = trim((string)($data['author_name'] ?? $existing['author_name']));
        $avatarUrl = trim((string)($data['avatar_url'] ?? $existing['avatar_url'] ?? ''));
        $rating = max(1, min(5, (int)($data['rating'] ?? $existing['rating'])));
        $title = trim((string)($data['title'] ?? $existing['title']));
        $comment = trim((string)($data['comment'] ?? $existing['comment']));
        $status = in_array($data['status'] ?? '', ['pending', 'approved', 'rejected']) ? $data['status'] : $existing['status'];
        $isVerified = isset($data['is_verified_purchase']) ? ((int)$data['is_verified_purchase'] ? 1 : 0) : (int)$existing['is_verified_purchase'];

        // Handle avatar upload
        $uploadedAvatar = self::uploadReviewFile('avatar_file', 'avatars');
        if ($uploadedAvatar) {
            $avatarUrl = $uploadedAvatar;
        }

        // Handle wristshot photo upload
        $imagesJson = $existing['images_json'];
        $uploadedWristshot = self::uploadReviewFile('review_image', 'wristshots');
        if ($uploadedWristshot) {
            $imagesJson = json_encode([$uploadedWristshot]);
        } elseif (isset($data['wristshot_url'])) {
            $url = trim((string)$data['wristshot_url']);
            $imagesJson = !empty($url) ? json_encode([$url]) : null;
        }

        Database::execute(
            "UPDATE reviews SET author_name = ?, avatar_url = ?, rating = ?, title = ?, comment = ?, status = ?, is_verified_purchase = ?, images_json = ? WHERE id = ?",
            [$authorName, $avatarUrl, $rating, $title, $comment, $status, $isVerified, $imagesJson, $reviewId]
        );

        return true;
    }

    /**
     * Admin delete review
     */
    public static function deleteReview(int $reviewId): bool
    {
        Database::execute("DELETE FROM reviews WHERE id = ?", [$reviewId]);
        return true;
    }

    /**
     * Admin add verified review directly
     */
    public static function addAdminReview(array $data): int
    {
        $productId = (int)($data['product_id'] ?? 0);
        $authorName = trim((string)($data['author_name'] ?? 'Verified Buyer'));
        $avatarUrl = trim((string)($data['avatar_url'] ?? ''));
        $rating = max(1, min(5, (int)($data['rating'] ?? 5)));
        $title = trim((string)($data['title'] ?? ''));
        $comment = trim((string)($data['comment'] ?? ''));
        $isVerified = isset($data['is_verified_purchase']) ? (int)$data['is_verified_purchase'] : 1;
        $status = in_array($data['status'] ?? '', ['pending', 'approved', 'rejected']) ? $data['status'] : 'approved';

        $uploadedAvatar = self::uploadReviewFile('avatar_file', 'avatars');
        if ($uploadedAvatar) {
            $avatarUrl = $uploadedAvatar;
        }

        $images = [];
        $uploadedWristshot = self::uploadReviewFile('review_image', 'wristshots');
        if ($uploadedWristshot) {
            $images[] = $uploadedWristshot;
        } elseif (!empty($data['wristshot_url'])) {
            $images[] = trim((string)$data['wristshot_url']);
        }
        $imagesJson = !empty($images) ? json_encode($images) : null;

        return (int)Database::insert(
            "INSERT INTO reviews (product_id, author_name, avatar_url, rating, title, comment, is_verified_purchase, images_json, status, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())",
            [$productId, $authorName, $avatarUrl, $rating, $title, $comment, $isVerified, $imagesJson, $status]
        );
    }

    /**
     * Vote a review as helpful with session-based duplicate prevention
     */
    public static function voteHelpful(int $reviewId, string $sessionId, ?string $ip = null): array
    {
        $existing = Database::fetch(
            "SELECT id FROM review_votes WHERE review_id = ? AND session_id = ?",
            [$reviewId, $sessionId]
        );

        if ($existing) {
            return ['success' => false, 'message' => 'You have already voted this review as helpful.'];
        }

        Database::execute(
            "INSERT INTO review_votes (review_id, session_id, ip_address) VALUES (?, ?, ?)",
            [$reviewId, $sessionId, $ip]
        );

        Database::execute(
            "UPDATE reviews SET helpful_count = helpful_count + 1 WHERE id = ?",
            [$reviewId]
        );

        $newCount = (int)Database::fetch("SELECT helpful_count FROM reviews WHERE id = ?", [$reviewId])['helpful_count'];

        return [
            'success' => true,
            'helpful_count' => $newCount,
            'message' => 'Thank you for your feedback!'
        ];
    }

    /**
     * Get comparison matrix comparing current product with related products
     */
    public static function getComparisonMatrix(int $currentProductId, array $relatedProductIds = []): array
    {
        $rows = Database::fetchAll("SELECT * FROM product_comparison_rows WHERE is_active = 1 ORDER BY sort_order ASC");
        if (empty($rows)) {
            return [];
        }

        // Limit comparison to current + up to 3 related
        $compareIds = array_slice(array_merge([$currentProductId], array_diff($relatedProductIds, [$currentProductId])), 0, 4);

        $products = [];
        if (!empty($compareIds)) {
            $inClause = implode(',', array_fill(0, count($compareIds), '?'));
            $prods = Database::fetchAll(
                "SELECT p.id, p.name, p.slug, p.base_price, p.compare_at_price,
                        COALESCE(pi.image_url, '/assets/images/products/placeholder.jpg') as primary_image
                 FROM products p
                 LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_primary = 1
                 WHERE p.id IN ({$inClause})",
                $compareIds
            );
            // Index by ID maintaining compareIds order
            $prodMap = [];
            foreach ($prods as $pr) {
                $prodMap[$pr['id']] = $pr;
            }
            foreach ($compareIds as $cid) {
                if (isset($prodMap[$cid])) {
                    $products[] = $prodMap[$cid];
                }
            }
        }

        // Fetch values
        $matrix = [];
        foreach ($rows as $row) {
            $rowValues = [];
            foreach ($products as $prod) {
                $val = Database::fetch(
                    "SELECT value FROM product_comparison_values WHERE product_id = ? AND row_id = ?",
                    [$prod['id'], $row['id']]
                );
                $rowValues[$prod['id']] = $val ? $val['value'] : '—';
            }
            $matrix[] = [
                'feature_name' => $row['feature_name'],
                'values' => $rowValues
            ];
        }

        return [
            'products' => $products,
            'rows' => $matrix
        ];
    }
}
