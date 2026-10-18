<?php

declare(strict_types=1);

namespace App\Services\Mail;

use App\Support\Database;

class MailService
{
    /**
     * Send review invitation email to customer upon order delivery
     */
    public static function sendDeliveryReviewInvitation(int $orderId): bool
    {
        $order = Database::fetch("SELECT * FROM orders WHERE id = ?", [$orderId]);
        if (!$order) {
            return false;
        }

        $recipientEmail = $order['guest_email'];
        if (empty($recipientEmail) && !empty($order['user_id'])) {
            $user = Database::fetch("SELECT email, full_name FROM users WHERE id = ?", [$order['user_id']]);
            $recipientEmail = $user['email'] ?? null;
        }

        if (empty($recipientEmail)) {
            return false;
        }

        $items = Database::query(
            "SELECT oi.*, p.name AS product_name, p.slug AS product_slug,
                    COALESCE(pi.image_url, '/assets/images/products/placeholder.jpg') AS product_image
             FROM order_items oi
             JOIN products p ON oi.product_id = p.id
             LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_primary = 1
             WHERE oi.order_id = ?",
            [$orderId]
        );

        if (empty($items)) {
            return false;
        }

        $customerName = !empty($order['shipping_name']) ? $order['shipping_name'] : 'Valued Collector';
        $orderNumber = $order['order_number'];

        $productCardsHtml = '';
        foreach ($items as $item) {
            $reviewUrl = "http://localhost:8000/product/{$item['product_slug']}?order_number=" . urlencode($orderNumber) . "#reviews-section";
            $productCardsHtml .= '
                <div style="border: 1px solid #E1E7F0; border-radius: 8px; padding: 16px; margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between; background: #FFFFFF;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <img src="' . htmlspecialchars($item['product_image']) . '" alt="' . htmlspecialchars($item['product_name']) . '" style="width: 60px; height: 60px; object-fit: contain; border-radius: 4px; border: 1px solid #EDF2F7;">
                        <div>
                            <strong style="color: #0A1128; font-size: 14px; display: block;">' . htmlspecialchars($item['product_name']) . '</strong>
                            <span style="color: #718096; font-size: 12px;">Order #' . htmlspecialchars($orderNumber) . ' &bull; Delivered</span>
                        </div>
                    </div>
                    <div>
                        <a href="' . htmlspecialchars($reviewUrl) . '" style="background: #0A1128; color: #DFB75C; text-decoration: none; padding: 8px 16px; border-radius: 4px; font-weight: bold; font-size: 12px; display: inline-block;">
                            Rate & Review &rarr;
                        </a>
                    </div>
                </div>
            ';
        }

        $subject = "Your Welkar Timepiece Has Arrived! Share Your Review & Wristshot";
        $body = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>' . htmlspecialchars($subject) . '</title>
        </head>
        <body style="font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Helvetica, Arial, sans-serif; background-color: #F8FAFC; margin: 0; padding: 24px; color: #1A202C;">
            <div style="max-width: 600px; margin: 0 auto; background: #FFFFFF; border-radius: 12px; overflow: hidden; border: 1px solid #E2E8F0; box-shadow: 0 4px 12px rgba(10,17,40,0.06);">
                <div style="background: #0A1128; padding: 32px 24px; text-align: center; border-bottom: 3px solid #C59B3F;">
                    <h1 style="color: #FFFFFF; font-family: Georgia, serif; margin: 0; font-size: 24px; letter-spacing: 1.5px;">WELKAR</h1>
                    <p style="color: #DFB75C; font-size: 12px; margin: 4px 0 0 0; text-transform: uppercase; letter-spacing: 1px;">Horological Distinction &bull; Bangalore Chamber</p>
                </div>
                <div style="padding: 32px 24px;">
                    <h2 style="color: #0A1128; font-size: 18px; margin-top: 0;">Dear ' . htmlspecialchars($customerName) . ',</h2>
                    <p style="color: #4A5568; line-height: 1.6; font-size: 14px;">
                        Our courier partner has confirmed the successful doorstep delivery of your order <strong>#' . htmlspecialchars($orderNumber) . '</strong>.
                    </p>
                    <p style="color: #4A5568; line-height: 1.6; font-size: 14px;">
                        As a member of the Welkar collector circle, your feedback on case finishing, wrist presence, and accuracy helps fellow horology enthusiasts make confident choices.
                    </p>
                    <div style="margin: 24px 0;">
                        ' . $productCardsHtml . '
                    </div>
                    <div style="background: #FFFDF9; border: 1px solid #F6E0B5; border-radius: 8px; padding: 14px; margin-top: 20px;">
                        <p style="color: #744210; font-size: 12px; margin: 0; line-height: 1.5;">
                            <strong>Tip:</strong> Upload a wristshot photo with your review to receive priority feature status on our official homepage!
                        </p>
                    </div>
                </div>
                <div style="background: #F8FAFC; padding: 16px 24px; border-top: 1px solid #E2E8F0; text-align: center; font-size: 12px; color: #A0AEC0;">
                    &copy; ' . date('Y') . ' Welkar Watches. All rights reserved. &bull; Stamped Serial Warranty Registered
                </div>
            </div>
        </body>
        </html>
        ';

        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=utf-8',
            'From: Welkar Atelier <concierge@welkar.in>',
            'Reply-To: welkarstore@gmail.com',
            'X-Mailer: PHP/' . phpversion()
        ];

        // Attempt PHP mail()
        @mail($recipientEmail, $subject, $body, implode("\r\n", $headers));

        // Always log to audit_logs for traceability
        Database::execute(
            "INSERT INTO audit_logs (user_type, user_id, action, entity_type, entity_id, new_values, ip_address, created_at)
             VALUES ('system', NULL, 'sent_delivery_review_invitation', 'order', ?, ?, '127.0.0.1', NOW())",
            [$orderId, json_encode(['email' => $recipientEmail, 'order_number' => $orderNumber, 'items_count' => count($items)])]
        );

        return true;
    }
}
