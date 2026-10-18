<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tax Invoice - <?= e($invoice['invoice_number']) ?></title>
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; font-size: 13px; color: #222; margin: 0; padding: 30px; line-height: 1.5; }
        .invoice-box { max-width: 800px; margin: auto; border: 1px solid #ddd; padding: 25px; border-radius: 4px; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #111; padding-bottom: 15px; margin-bottom: 20px; }
        .logo { font-size: 24px; font-weight: 800; letter-spacing: 2px; text-transform: uppercase; }
        .doc-title { text-align: right; }
        .doc-title h2 { margin: 0; font-size: 20px; color: #111; }
        .parties { display: flex; justify-content: space-between; margin-bottom: 25px; }
        .party-col { width: 48%; }
        .party-col h4 { margin: 0 0 6px 0; font-size: 13px; text-transform: uppercase; color: #666; border-bottom: 1px solid #eee; padding-bottom: 4px; }
        table.items-table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        table.items-table th { background: #f8f9fa; border: 1px solid #dee2e6; padding: 8px 10px; font-size: 12px; text-align: left; }
        table.items-table td { border: 1px solid #dee2e6; padding: 8px 10px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .totals-section { display: flex; justify-content: flex-end; }
        .totals-table { width: 340px; border-collapse: collapse; }
        .totals-table td { padding: 6px 10px; }
        .totals-table tr.grand-total td { font-size: 15px; font-weight: bold; border-top: 2px solid #111; border-bottom: 2px solid #111; }
        .footer-notes { margin-top: 30px; font-size: 11px; color: #666; border-top: 1px solid #eee; padding-top: 15px; text-align: center; }
        @media print {
            body { padding: 0; }
            .invoice-box { border: none; padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #111; color: #fff; border: none; cursor: pointer; border-radius: 4px; font-weight: bold;">Print Tax Invoice</button>
    </div>

    <div class="invoice-box">
        <div class="header">
            <div>
                <div class="logo mb-1">
                    <img src="/assets/images/welkar-logo.png" alt="Welkar" style="height: 38px; width: auto; vertical-align: middle;">
                </div>
                <div style="font-size: 12px; color: #555; margin-top: 5px;">
                    <strong><?= e(config('app.business.legal_name', 'Welkar Timepieces Private Limited')) ?></strong><br>
                    State: <?= e(config('app.business.state', 'Maharashtra')) ?> (Code: <?= e(config('app.business.state_code', '27')) ?>)<br>
                    GSTIN: <?= e(config('app.business.gstin', 'To be updated by owner')) ?><br>
                    Email: <?= e(config('app.business.email', 'welkarstore@gmail.com')) ?>
                </div>
            </div>
            <div class="doc-title">
                <h2>TAX INVOICE</h2>
                <div style="margin-top: 8px; font-size: 12px;">
                    <strong>Invoice No:</strong> <?= e($invoice['invoice_number']) ?><br>
                    <strong>Invoice Date:</strong> <?= date('d M Y', strtotime($invoice['invoice_date'])) ?><br>
                    <strong>Order Ref:</strong> <?= e($order['order_number']) ?><br>
                    <strong>Payment Mode:</strong> <?= strtoupper(e($order['payment_method'])) ?>
                </div>
            </div>
        </div>

        <div class="parties">
            <div class="party-col">
                <h4>Bill To / Ship To</h4>
                <strong><?= e($address['full_name'] ?? $order['guest_phone']) ?></strong><br>
                <?= e($address['address_line1'] ?? '') ?><br>
                <?php if (!empty($address['address_line2'])): ?>
                    <?= e($address['address_line2']) ?><br>
                <?php endif; ?>
                <?= e($address['city'] ?? '') ?>, <?= e($address['state'] ?? '') ?> - <?= e($address['pincode'] ?? '') ?><br>
                Phone: <?= e($address['phone'] ?? $order['guest_phone']) ?>
            </div>
            <div class="party-col">
                <h4>Dispatch & Delivery Info</h4>
                <strong>Place of Supply:</strong> <?= e($order['shipping_state']) ?><br>
                <strong>Delivery Type:</strong> Standard Domestic Courier<br>
                <strong>Currency:</strong> INR (₹)
            </div>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 30px;">#</th>
                    <th>Item Description</th>
                    <th>HSN</th>
                    <th>SKU</th>
                    <th class="text-center">Qty</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Taxable Val</th>
                    <th class="text-right">GST Rate</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; foreach ($items as $item): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td>
                        <strong><?= e($item['product_name']) ?></strong><br>
                        <span style="font-size: 11px; color: #666;"><?= e($item['variant_name']) ?></span>
                    </td>
                    <td>9102</td>
                    <td><?= e($item['sku']) ?></td>
                    <td class="text-center"><?= (int)$item['quantity'] ?></td>
                    <td class="text-right"><?= format_currency($item['unit_price']) ?></td>
                    <td class="text-right"><?= format_currency((float)$item['subtotal'] - (float)$item['discount_share']) ?></td>
                    <td class="text-right"><?= (float)$item['tax_rate'] ?>%</td>
                    <td class="text-right"><?= format_currency($item['total']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td>Taxable Subtotal:</td>
                    <td class="text-right"><?= format_currency($invoice['total_taxable']) ?></td>
                </tr>
                <?php if ((float)$invoice['total_cgst'] > 0): ?>
                <tr>
                    <td>CGST (9%):</td>
                    <td class="text-right"><?= format_currency($invoice['total_cgst']) ?></td>
                </tr>
                <tr>
                    <td>SGST (9%):</td>
                    <td class="text-right"><?= format_currency($invoice['total_sgst']) ?></td>
                </tr>
                <?php else: ?>
                <tr>
                    <td>IGST (18%):</td>
                    <td class="text-right"><?= format_currency($invoice['total_igst']) ?></td>
                </tr>
                <?php endif; ?>
                <tr>
                    <td>Shipping Fee:</td>
                    <td class="text-right"><?= format_currency($order['shipping_fee']) ?></td>
                </tr>
                <?php if ((float)$order['discount_amount'] > 0): ?>
                <tr>
                    <td>Discount Applied:</td>
                    <td class="text-right" style="color: #28a745;">- <?= format_currency($order['discount_amount']) ?></td>
                </tr>
                <?php endif; ?>
                <tr class="grand-total">
                    <td>Grand Total (INR):</td>
                    <td class="text-right"><?= format_currency($invoice['grand_total']) ?></td>
                </tr>
            </table>
        </div>

        <div class="footer-notes">
            <p>This is a computer generated tax invoice issued under the Goods and Services Tax Act, 2017.<br>
            All disputes are subject to Mumbai, Maharashtra jurisdiction. Thank you for choosing Welkar.</p>
        </div>
    </div>
</body>
</html>
