<?php

namespace App\Services\Invoices;

use App\Support\Database;
use App\Support\Config;

class InvoiceService
{
    public static function generateForOrder(int $orderId): array
    {
        return Database::transaction(function ($pdo) use ($orderId) {
            // Check if invoice already exists
            $existing = Database::fetch("SELECT * FROM invoices WHERE order_id = ?", [$orderId]);
            if ($existing) {
                return $existing;
            }

            $order = Database::fetch("SELECT * FROM orders WHERE id = ?", [$orderId]);
            if (!$order) {
                throw new \Exception("Order #{$orderId} not found.");
            }

            // Allocate next sequential invoice number
            $fiscalYear = date('Y') . '-' . substr((string)((int)date('Y') + 1), -2);
            $prefix = Config::get('app.invoice_prefix', 'WEL');

            $seq = Database::fetch(
                "SELECT * FROM invoice_sequences WHERE fiscal_year = ? FOR UPDATE",
                [$fiscalYear]
            );

            if (!$seq) {
                Database::execute(
                    "INSERT INTO invoice_sequences (fiscal_year, prefix, current_number) VALUES (?, ?, 1001)",
                    [$fiscalYear, $prefix]
                );
                $invoiceNum = "{$prefix}-{$fiscalYear}-1001";
            } else {
                $nextNum = (int)$seq['current_number'] + 1;
                Database::execute(
                    "UPDATE invoice_sequences SET current_number = ? WHERE id = ?",
                    [$nextNum, $seq['id']]
                );
                $invoiceNum = sprintf("%s-%s-%04d", $seq['prefix'], $fiscalYear, $nextNum);
            }

            // Calculate GST breakdown from order items
            $items = Database::query("SELECT * FROM order_items WHERE order_id = ?", [$orderId]);
            $totalTaxable = 0.0;
            $totalCgst = 0.0;
            $totalSgst = 0.0;
            $totalIgst = 0.0;

            $sellerState = Config::get('app.business.state', 'Maharashtra');
            $isInterState = (strcasecmp(trim($order['shipping_state']), trim($sellerState)) !== 0);

            foreach ($items as $item) {
                $lineTaxable = (float)$item['subtotal'] - (float)$item['discount_share'];
                $lineTax = (float)$item['tax_amount'];
                $totalTaxable += $lineTaxable;

                if ($isInterState) {
                    $totalIgst += $lineTax;
                } else {
                    $totalCgst += round($lineTax / 2.0, 2);
                    $totalSgst += round($lineTax / 2.0, 2);
                }
            }

            $grandTotal = (float)$order['total_amount'];

            $invoiceId = Database::insert(
                "INSERT INTO invoices (
                    order_id, invoice_number, invoice_date, total_taxable,
                    total_cgst, total_sgst, total_igst, grand_total, created_at
                ) VALUES (?, ?, CURDATE(), ?, ?, ?, ?, ?, NOW())",
                [
                    $orderId, $invoiceNum, $totalTaxable,
                    $totalCgst, $totalSgst, $totalIgst, $grandTotal
                ]
            );

            return Database::fetch("SELECT * FROM invoices WHERE id = ?", [$invoiceId]);
        });
    }

    public static function getInvoiceHtml(int $invoiceId): string
    {
        $invoice = Database::fetch("SELECT * FROM invoices WHERE id = ?", [$invoiceId]);
        if (!$invoice) {
            return "<h3>Invoice not found.</h3>";
        }

        $order = Database::fetch("SELECT * FROM orders WHERE id = ?", [$invoice['order_id']]);
        $items = Database::query("SELECT * FROM order_items WHERE order_id = ?", [$order['id']]);
        $address = Database::fetch("SELECT * FROM order_addresses WHERE order_id = ? AND type = 'shipping'", [$order['id']]);

        ob_start();
        include __DIR__ . '/../../../resources/templates/invoice.php';
        return ob_get_clean();
    }
}
