<?php

namespace App\Services\Pricing;

use App\Support\Config;

class PricingService
{
    public static function calculate(
        array $items,
        ?array $coupon = null,
        string $deliveryState = 'Maharashtra'
    ): array {
        $subtotal = 0.0;
        $totalItems = 0;

        foreach ($items as $item) {
            $qty = (int)($item['quantity'] ?? 1);
            $price = (float)($item['price'] ?? $item['unit_price'] ?? 0);
            $subtotal += ($price * $qty);
            $totalItems += $qty;
        }

        // Coupon discount calculation
        $discountAmount = 0.0;
        $couponApplied = null;

        if ($coupon && !empty($coupon['code'])) {
            $minSpend = (float)($coupon['min_order_value'] ?? 0);
            if ($subtotal >= $minSpend) {
                if ($coupon['discount_type'] === 'percentage') {
                    $pct = (float)$coupon['discount_value'];
                    $discountAmount = ($subtotal * $pct) / 100.0;
                    if (!empty($coupon['max_discount_value'])) {
                        $discountAmount = min($discountAmount, (float)$coupon['max_discount_value']);
                    }
                } else {
                    $discountAmount = min((float)$coupon['discount_value'], $subtotal);
                }
                $couponApplied = $coupon['code'];
            }
        }

        $discountedSubtotal = max(0.0, $subtotal - $discountAmount);

        // Shipping calculation
        $shippingThreshold = (float)Config::get('app.shipping.free_threshold', 1499.00);
        $defaultShippingFee = (float)Config::get('app.shipping.default_fee', 60.00);

        $shippingFee = ($subtotal >= $shippingThreshold || $subtotal == 0) ? 0.0 : $defaultShippingFee;

        // GST Calculation (18% standard rate on watches)
        // Seller state is Maharashtra (State code 27)
        $sellerState = Config::get('app.business.state', 'Maharashtra');
        $gstRate = (float)Config::get('app.business.default_gst_rate', 18.00);

        // Calculate tax on the discounted taxable value
        $taxableValue = $discountedSubtotal;
        $taxAmount = round(($taxableValue * $gstRate) / 100.0, 2);

        $isInterState = (strcasecmp(trim($deliveryState), trim($sellerState)) !== 0);

        if ($isInterState) {
            $cgst = 0.0;
            $sgst = 0.0;
            $igst = $taxAmount;
        } else {
            $halfRate = $gstRate / 2.0;
            $cgst = round(($taxableValue * $halfRate) / 100.0, 2);
            $sgst = round(($taxableValue * $halfRate) / 100.0, 2);
            $igst = 0.0;
            // Balance rounding if any
            $taxAmount = $cgst + $sgst;
        }

        $grandTotal = round($discountedSubtotal + $shippingFee + $taxAmount, 2);

        return [
            'subtotal' => round($subtotal, 2),
            'discount_amount' => round($discountAmount, 2),
            'coupon_code' => $couponApplied,
            'discounted_subtotal' => round($discountedSubtotal, 2),
            'shipping_fee' => round($shippingFee, 2),
            'free_shipping_threshold' => $shippingThreshold,
            'needs_for_free_shipping' => max(0.0, round($shippingThreshold - $subtotal, 2)),
            'taxable_value' => round($taxableValue, 2),
            'gst_rate' => $gstRate,
            'tax_amount' => $taxAmount,
            'cgst' => $cgst,
            'sgst' => $sgst,
            'igst' => $igst,
            'is_inter_state' => $isInterState,
            'total_amount' => $grandTotal,
            'total_items' => $totalItems,
        ];
    }
}
