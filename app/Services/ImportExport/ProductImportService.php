<?php

namespace App\Services\ImportExport;

use App\Support\Database;

class ProductImportService
{
    public static function getTemplateCsv(): string
    {
        $headers = [
            'Product Name', 'Brand', 'Category', 'Base SKU', 'Variant SKU',
            'Selling Price', 'Compare At Price', 'Cost Price', 'Stock Quantity',
            'Gender', 'Dial Type', 'Movement', 'Case Material', 'Case Size mm',
            'Strap Material', 'Strap Color', 'Water Resistance', 'Warranty Years',
            'HSN Code', 'GST Rate', 'Primary Image URL', 'Short Description'
        ];

        $sampleRow = [
            'Welkar Vantage Chrono', 'Welkar', "Men's Watches", 'WLK-VNT-01', 'WLK-VNT-SLV-01',
            '3999.00', '5499.00', '2100.00', '50',
            'Men', 'Chronograph', 'Quartz Chrono', 'Stainless Steel', '43',
            'Stainless Steel Mesh', 'Silver', '50m / 5 ATM', '2',
            '9102', '18.00', '/assets/images/products/placeholder.jpg', 'Modern luxury sports chronograph.'
        ];

        $fp = fopen('php://temp', 'r+');
        fputcsv($fp, $headers);
        fputcsv($fp, $sampleRow);
        rewind($fp);
        $csv = stream_get_contents($fp);
        fclose($fp);

        return $csv;
    }

    public static function validateAndPreview(string $filePath): array
    {
        if (!file_exists($filePath)) {
            return ['success' => false, 'message' => 'Uploaded file not found.'];
        }

        $handle = fopen($filePath, 'r');
        $headers = fgetcsv($handle);
        if (!$headers) {
            fclose($handle);
            return ['success' => false, 'message' => 'Empty or invalid CSV file.'];
        }

        $rows = [];
        $errors = [];
        $rowIndex = 1;

        while (($data = fgetcsv($handle)) !== false) {
            $rowIndex++;
            if (empty(array_filter($data))) {
                continue;
            }

            $rowMap = @array_combine($headers, $data);
            if (!$rowMap) {
                $errors[] = ["row" => $rowIndex, "error" => "Column count mismatch."];
                continue;
            }

            // Validations
            $rowErrors = [];
            if (empty($rowMap['Product Name'])) $rowErrors[] = "Missing 'Product Name'";
            if (empty($rowMap['Brand'])) $rowErrors[] = "Missing 'Brand'";
            if (empty($rowMap['Base SKU'])) $rowErrors[] = "Missing 'Base SKU'";
            if (empty($rowMap['Variant SKU'])) $rowErrors[] = "Missing 'Variant SKU'";
            if (!is_numeric($rowMap['Selling Price'] ?? '')) $rowErrors[] = "Invalid 'Selling Price'";
            if (!is_numeric($rowMap['Stock Quantity'] ?? '')) $rowErrors[] = "Invalid 'Stock Quantity'";

            $rows[] = [
                'row_index' => $rowIndex,
                'data' => $rowMap,
                'is_valid' => empty($rowErrors),
                'errors' => $rowErrors,
            ];

            if (!empty($rowErrors)) {
                $errors[] = ["row" => $rowIndex, "error" => implode("; ", $rowErrors)];
            }
        }
        fclose($handle);

        return [
            'success' => true,
            'total_rows' => count($rows),
            'valid_rows' => count(array_filter($rows, fn($r) => $r['is_valid'])),
            'error_count' => count($errors),
            'errors' => $errors,
            'preview' => array_slice($rows, 0, 10),
        ];
    }

    public static function processImport(
        string $filePath,
        string $filename,
        int $adminUserId,
        string $mode = 'upsert'
    ): array {
        $preview = self::validateAndPreview($filePath);
        if (!$preview['success']) {
            return $preview;
        }

        $jobId = Database::insert(
            "INSERT INTO import_jobs (
                admin_user_id, filename, file_path, mode,
                total_rows, status, created_at
            ) VALUES (?, ?, ?, ?, ?, 'processing', NOW())",
            [$adminUserId, $filename, $filePath, $mode, $preview['total_rows']]
        );

        $imported = 0;
        $skipped = 0;
        $failed = 0;
        $failedDetails = [];

        $handle = fopen($filePath, 'r');
        $headers = fgetcsv($handle);
        $rowIndex = 1;

        while (($data = fgetcsv($handle)) !== false) {
            $rowIndex++;
            if (empty(array_filter($data))) continue;
            $rowMap = @array_combine($headers, $data);
            if (!$rowMap) continue;

            try {
                Database::transaction(function () use ($rowMap, $mode) {
                    // Find or create Brand
                    $brandName = trim($rowMap['Brand']);
                    $brand = Database::fetch("SELECT * FROM brands WHERE name = ?", [$brandName]);
                    if (!$brand) {
                        $brandSlug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $brandName));
                        $brandId = Database::insert(
                            "INSERT INTO brands (name, slug, is_active) VALUES (?, ?, 1)",
                            [$brandName, $brandSlug]
                        );
                    } else {
                        $brandId = $brand['id'];
                    }

                    // Product
                    $prodName = trim($rowMap['Product Name']);
                    $baseSku = trim($rowMap['Base SKU']);
                    $prodSlug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $prodName)) . '-' . strtolower(substr($baseSku, -4));

                    $product = Database::fetch("SELECT * FROM products WHERE base_sku = ?", [$baseSku]);

                    if (!$product) {
                        if ($mode === 'update_only') {
                            return; // Skip
                        }
                        $productId = Database::insert(
                            "INSERT INTO products (
                                brand_id, name, slug, base_sku, short_description,
                                gender, dial_type, movement, case_material, case_size_mm,
                                strap_material, strap_color, water_resistance, warranty_years,
                                base_price, cost_price, compare_at_price, hsn_code, gst_rate, is_active
                            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)",
                            [
                                $brandId, $prodName, $prodSlug, $baseSku, $rowMap['Short Description'] ?? '',
                                $rowMap['Gender'] ?? 'Men', $rowMap['Dial Type'] ?? 'Analog',
                                $rowMap['Movement'] ?? 'Japanese Quartz', $rowMap['Case Material'] ?? 'Stainless Steel',
                                (int)($rowMap['Case Size mm'] ?? 42), $rowMap['Strap Material'] ?? 'Stainless Steel',
                                $rowMap['Strap Color'] ?? 'Silver', $rowMap['Water Resistance'] ?? '30m',
                                (int)($rowMap['Warranty Years'] ?? 1), (float)$rowMap['Selling Price'],
                                (float)($rowMap['Cost Price'] ?? 0), (float)($rowMap['Compare At Price'] ?? 0),
                                $rowMap['HSN Code'] ?? '9102', (float)($rowMap['GST Rate'] ?? 18.00)
                            ]
                        );
                    } else {
                        if ($mode === 'create_only') {
                            return; // Skip existing
                        }
                        $productId = $product['id'];
                        Database::execute(
                            "UPDATE products SET
                                name = ?, base_price = ?, cost_price = ?, compare_at_price = ?, updated_at = NOW()
                             WHERE id = ?",
                            [
                                $prodName, (float)$rowMap['Selling Price'],
                                (float)($rowMap['Cost Price'] ?? 0), (float)($rowMap['Compare At Price'] ?? 0),
                                $productId
                            ]
                        );
                    }

                    // Variant
                    $varSku = trim($rowMap['Variant SKU']);
                    $variant = Database::fetch("SELECT * FROM product_variants WHERE sku = ?", [$varSku]);

                    if (!$variant) {
                        $variantId = Database::insert(
                            "INSERT INTO product_variants (
                                product_id, sku, variant_name, price, compare_at_price, cost_price, is_active
                            ) VALUES (?, ?, ?, ?, ?, ?, 1)",
                            [
                                $productId, $varSku, $prodName . ' - ' . $varSku,
                                (float)$rowMap['Selling Price'], (float)($rowMap['Compare At Price'] ?? 0),
                                (float)($rowMap['Cost Price'] ?? 0)
                            ]
                        );
                    } else {
                        $variantId = $variant['id'];
                        Database::execute(
                            "UPDATE product_variants SET price = ?, updated_at = NOW() WHERE id = ?",
                            [(float)$rowMap['Selling Price'], $variantId]
                        );
                    }

                    // Inventory
                    $stockQty = (int)($rowMap['Stock Quantity'] ?? 0);
                    $stock = Database::fetch("SELECT * FROM inventory_stock WHERE variant_id = ? AND location_id = 1", [$variantId]);
                    if ($stock) {
                        Database::execute("UPDATE inventory_stock SET quantity = ? WHERE id = ?", [$stockQty, $stock['id']]);
                    } else {
                        Database::execute(
                            "INSERT INTO inventory_stock (variant_id, location_id, quantity) VALUES (?, 1, ?)",
                            [$variantId, $stockQty]
                        );
                    }

                    // Primary image
                    if (!empty($rowMap['Primary Image URL'])) {
                        $hasImg = Database::fetch("SELECT * FROM product_images WHERE product_id = ? AND is_primary = 1", [$productId]);
                        if (!$hasImg) {
                            Database::execute(
                                "INSERT INTO product_images (product_id, variant_id, image_url, is_primary) VALUES (?, ?, ?, 1)",
                                [$productId, $variantId, trim($rowMap['Primary Image URL'])]
                            );
                        }
                    }
                });

                $imported++;
            } catch (\Exception $e) {
                $failed++;
                $failedDetails[] = ['row' => $rowIndex, 'error' => $e->getMessage()];
            }
        }
        fclose($handle);

        Database::execute(
            "UPDATE import_jobs SET
                imported_rows = ?, skipped_rows = ?, failed_rows = ?,
                status = 'completed', completed_at = NOW()
             WHERE id = ?",
            [$imported, $skipped, $failed, $jobId]
        );

        return [
            'success' => true,
            'job_id' => $jobId,
            'imported' => $imported,
            'skipped' => $skipped,
            'failed' => $failed,
            'failed_details' => $failedDetails,
        ];
    }
}
