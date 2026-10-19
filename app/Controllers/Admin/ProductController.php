<?php

namespace App\Controllers\Admin;

use App\Support\Request;
use App\Support\View;
use App\Support\Response;
use App\Support\Session;
use App\Support\Database;
use App\Services\Inventory\InventoryService;

class ProductController
{
    public function index(Request $request): string
    {
        $products = Database::query(
            "SELECT p.*, b.name AS brand_name,
                    COALESCE(pi.image_url, '/assets/images/products/placeholder.jpg') AS primary_image,
                    COALESCE(SUM(s.quantity), 0) AS total_stock,
                    COUNT(DISTINCT pv.id) AS variants_count
             FROM products p
             JOIN brands b ON p.brand_id = b.id
             LEFT JOIN product_variants pv ON p.id = pv.product_id
             LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_primary = 1
             LEFT JOIN inventory_stock s ON pv.id = s.variant_id
             GROUP BY p.id, b.name, pi.image_url
             ORDER BY p.id DESC"
        );

        return View::render('admin.products.index', [
            'title' => 'Product Catalog Management | Welkar Admin',
            'products' => $products,
        ], 'layouts.admin');
    }

    public function create(Request $request): string
    {
        $brands = Database::query("SELECT * FROM brands WHERE is_active = 1 ORDER BY name ASC");
        $categories = Database::query("SELECT * FROM categories WHERE is_active = 1 ORDER BY name ASC");

        return View::render('admin.products.create', [
            'title' => 'Add New Watch | Welkar Admin',
            'brands' => $brands,
            'categories' => $categories,
        ], 'layouts.admin');
    }

    public function store(Request $request): void
    {
        $name = trim((string)$request->input('name'));
        if (empty($name)) {
            Session::flash('error', "Please enter a valid Watch Name.");
            Response::redirect('/admin/products/create');
        }

        $rawPrice = preg_replace('/[^0-9.]/', '', (string)$request->input('base_price', '0'));
        $price = (float)$rawPrice;
        if ($price <= 0) {
            Session::flash('error', "Please enter a valid Selling Price greater than zero.");
            Response::redirect('/admin/products/create');
        }

        $costPrice = !empty($request->input('cost_price')) ? (float)preg_replace('/[^0-9.]/', '', (string)$request->input('cost_price')) : null;
        $comparePrice = !empty($request->input('compare_at_price')) ? (float)preg_replace('/[^0-9.]/', '', (string)$request->input('compare_at_price')) : null;

        // Auto-generate or ensure unique base SKU
        $baseSku = trim((string)$request->input('base_sku'));
        if (empty($baseSku)) {
            $baseSku = 'WLK-' . strtoupper(substr(md5(uniqid((string)mt_rand(), true)), 0, 6));
        }
        $origSku = $baseSku;
        $skuCounter = 1;
        while (Database::fetch("SELECT id FROM products WHERE base_sku = ?", [$baseSku])) {
            $baseSku = $origSku . '-' . $skuCounter;
            $skuCounter++;
        }

        // Auto-generate unique slug
        $baseSlug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $name));
        $baseSlug = trim($baseSlug, '-');
        if (empty($baseSlug)) {
            $baseSlug = 'watch-' . strtolower(substr($baseSku, -4));
        }
        $slug = $baseSlug;
        $slugCounter = 1;
        while (Database::fetch("SELECT id FROM products WHERE slug = ?", [$slug])) {
            $slug = $baseSlug . '-' . $slugCounter;
            $slugCounter++;
        }

        // Verify brand exists
        $brandId = (int)$request->input('brand_id');
        $brandCheck = Database::fetch("SELECT id FROM brands WHERE id = ?", [$brandId]);
        if (!$brandCheck) {
            $firstBrand = Database::fetch("SELECT id FROM brands WHERE is_active = 1 LIMIT 1");
            $brandId = (int)($firstBrand['id'] ?? 1);
        }

        // Resolve inventory location
        $loc = Database::fetch("SELECT id FROM inventory_locations WHERE is_default = 1 LIMIT 1")
            ?? Database::fetch("SELECT id FROM inventory_locations LIMIT 1");
        $locationId = (int)($loc['id'] ?? 1);

        $gender = (string)$request->input('gender', 'Men');
        $dialType = trim((string)$request->input('dial_type', 'Analog'));
        $movement = trim((string)$request->input('movement', 'Japanese Quartz'));
        $caseMaterial = trim((string)$request->input('case_material', 'Stainless Steel'));
        $strapMaterial = trim((string)$request->input('strap_material', 'Stainless Steel'));
        $waterResistance = trim((string)$request->input('water_resistance', '30m'));
        $stockQty = max(0, (int)$request->input('initial_stock', 10));
        $isLiveAnimated = (int)$request->input('is_live_dial_animated', 0);
        $shortDesc = trim((string)$request->input('short_description', ''));

        try {
            Database::transaction(function () use (
                $brandId, $name, $slug, $baseSku, $price, $costPrice, $comparePrice,
                $gender, $dialType, $movement, $caseMaterial, $strapMaterial,
                $waterResistance, $isLiveAnimated, $stockQty, $shortDesc, $locationId, $request
            ) {
                $productId = Database::insert(
                    "INSERT INTO products (
                        brand_id, name, slug, base_sku, short_description,
                        gender, dial_type, movement, case_material, strap_material,
                        water_resistance, base_price, cost_price, compare_at_price,
                        is_live_dial_animated, is_active, created_at
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, NOW())",
                    [
                        $brandId, $name, $slug, $baseSku, $shortDesc,
                        $gender, $dialType, $movement, $caseMaterial, $strapMaterial,
                        $waterResistance, $price, $costPrice, $comparePrice, $isLiveAnimated
                    ]
                );

                // Default Variant
                $variantSku = $baseSku . '-DEF';
                if (Database::fetch("SELECT id FROM product_variants WHERE sku = ?", [$variantSku])) {
                    $variantSku .= '-' . rand(10, 99);
                }

                $variantId = Database::insert(
                    "INSERT INTO product_variants (
                        product_id, sku, variant_name, price, compare_at_price, cost_price, is_active
                    ) VALUES (?, ?, ?, ?, ?, ?, 1)",
                    [$productId, $variantSku, $name . ' (Standard)', $price, $comparePrice, $costPrice]
                );

                // Stock
                Database::execute(
                    "INSERT INTO inventory_stock (variant_id, location_id, quantity) VALUES (?, ?, ?)",
                    [$variantId, $locationId, $stockQty]
                );

                // Process uploaded files if any
                $uploadedCount = self::processUploadedFiles($productId, $variantId, true);

                // Primary Image if provided via URL and no file was uploaded
                $imageUrl = trim((string)$request->input('image_url'));
                if (!empty($imageUrl) && $uploadedCount === 0) {
                    Database::execute(
                        "INSERT INTO product_images (product_id, variant_id, image_url, is_primary, sort_order, created_at) VALUES (?, ?, ?, 1, 1, NOW())",
                        [$productId, $variantId, $imageUrl]
                    );
                    $uploadedCount++;
                }

                // If no images at all, assign default placeholder
                if ($uploadedCount === 0) {
                    Database::execute(
                        "INSERT INTO product_images (product_id, variant_id, image_url, is_primary, sort_order, created_at) VALUES (?, ?, '/assets/images/products/placeholder.jpg', 1, 1, NOW())",
                        [$productId, $variantId]
                    );
                }

                // Category linkage
                $catId = (int)$request->input('category_id');
                if ($catId > 0 && Database::fetch("SELECT id FROM categories WHERE id = ?", [$catId])) {
                    Database::execute(
                        "INSERT INTO product_categories (product_id, category_id) VALUES (?, ?)",
                        [$productId, $catId]
                    );
                }
            });

            Session::flash('success', "Watch '{$name}' created successfully.");
            Response::redirect('/admin/products');
        } catch (\Throwable $e) {
            Session::flash('error', "Error creating watch: " . $e->getMessage());
            Response::redirect('/admin/products/create');
        }
    }

    public function edit(Request $request, string $id): string
    {
        $product = Database::fetch("SELECT * FROM products WHERE id = ?", [(int)$id]);
        if (!$product) {
            http_response_code(404);
            return View::render('errors.404', ['title' => 'Product Not Found'], 'layouts.admin');
        }

        $brands = Database::query("SELECT * FROM brands WHERE is_active = 1 ORDER BY name ASC");
        $categories = Database::query("SELECT * FROM categories WHERE is_active = 1 ORDER BY name ASC");
        $assignedCategory = Database::fetch("SELECT category_id FROM product_categories WHERE product_id = ? LIMIT 1", [$product['id']]);

        $variants = Database::query(
            "SELECT pv.*, COALESCE(s.quantity, 0) AS stock_quantity
             FROM product_variants pv
             LEFT JOIN inventory_stock s ON pv.id = s.variant_id
             WHERE pv.product_id = ?",
            [$product['id']]
        );

        $images = Database::query(
            "SELECT * FROM product_images WHERE product_id = ? ORDER BY is_primary DESC, sort_order ASC, id ASC",
            [$product['id']]
        );

        $specifications = Database::query(
            "SELECT * FROM product_specifications WHERE product_id = ? ORDER BY sort_order ASC, id ASC",
            [$product['id']]
        );

        $hotspots = Database::query(
            "SELECT * FROM product_hotspots WHERE product_id = ? ORDER BY sort_order ASC, id ASC",
            [$product['id']]
        );

        $modules = Database::query(
            "SELECT * FROM product_page_modules WHERE product_id = ? ORDER BY sort_order ASC",
            [$product['id']]
        );

        $bannerModule = null;
        $storiesModule = null;
        $lifestyleModule = null;
        foreach ($modules as $m) {
            if ($m['module_type'] === 'banner') {
                $bannerModule = json_decode($m['content_json'] ?? '{}', true);
            } elseif ($m['module_type'] === 'stories') {
                $storiesModule = json_decode($m['content_json'] ?? '{}', true);
            } elseif ($m['module_type'] === 'lifestyle') {
                $lifestyleModule = json_decode($m['content_json'] ?? '{}', true);
            }
        }

        $faqs = Database::query(
            "SELECT * FROM product_faqs WHERE product_id = ? OR product_id IS NULL ORDER BY product_id DESC, sort_order ASC, id ASC",
            [$product['id']]
        );

        $reviews = Database::query(
            "SELECT * FROM reviews WHERE product_id = ? ORDER BY id DESC",
            [$product['id']]
        );

        return View::render('admin.products.edit', [
            'title' => 'Edit ' . $product['name'] . ' | Welkar Admin',
            'product' => $product,
            'brands' => $brands,
            'categories' => $categories,
            'assignedCategoryId' => (int)($assignedCategory['category_id'] ?? 0),
            'variants' => $variants,
            'images' => $images,
            'specifications' => $specifications,
            'hotspots' => $hotspots,
            'modules' => $modules,
            'bannerModule' => $bannerModule,
            'storiesModule' => $storiesModule,
            'lifestyleModule' => $lifestyleModule,
            'faqs' => $faqs,
            'reviews' => $reviews,
        ], 'layouts.admin');
    }

    public function update(Request $request, string $id): void
    {
        $productId = (int)$id;
        $existing = Database::fetch("SELECT * FROM products WHERE id = ?", [$productId]);
        if (!$existing) {
            Session::flash('error', "Watch not found.");
            Response::redirect('/admin/products');
        }

        $name = trim((string)$request->input('name'));
        if (empty($name)) {
            Session::flash('error', "Watch Name cannot be empty.");
            Response::redirect('/admin/products/' . $productId . '/edit');
        }

        $price = (float)preg_replace('/[^0-9.]/', '', (string)$request->input('base_price'));
        $costPrice = !empty($request->input('cost_price')) ? (float)preg_replace('/[^0-9.]/', '', (string)$request->input('cost_price')) : null;
        $comparePrice = !empty($request->input('compare_at_price')) ? (float)preg_replace('/[^0-9.]/', '', (string)$request->input('compare_at_price')) : null;
        $dialType = trim((string)$request->input('dial_type', $existing['dial_type'] ?? 'Analog'));
        $isLiveAnimated = (int)$request->input('is_live_dial_animated', 0);
        $isActive = (int)$request->input('is_active', 1);

        $dialBase = $request->input('dial_base_image') ?? ($existing['dial_base_image'] ?? null);
        $dialHour = $request->input('dial_hour_hand_image') ?? ($existing['dial_hour_hand_image'] ?? null);
        $dialMin = $request->input('dial_minute_hand_image') ?? ($existing['dial_minute_hand_image'] ?? null);
        $dialSec = $request->input('dial_second_hand_image') ?? ($existing['dial_second_hand_image'] ?? null);
        $dialCenterX = $request->input('dial_center_x') !== null ? (int)$request->input('dial_center_x') : (int)($existing['dial_center_x'] ?? 50);
        $dialCenterY = $request->input('dial_center_y') !== null ? (int)$request->input('dial_center_y') : (int)($existing['dial_center_y'] ?? 50);

        Database::execute(
            "UPDATE products SET
                name = ?, base_price = ?, cost_price = ?, compare_at_price = ?,
                dial_type = ?, is_live_dial_animated = ?, is_active = ?,
                dial_base_image = ?, dial_hour_hand_image = ?, dial_minute_hand_image = ?, dial_second_hand_image = ?,
                dial_center_x = ?, dial_center_y = ?,
                updated_at = NOW()
             WHERE id = ?",
            [
                $name, $price, $costPrice, $comparePrice,
                $dialType, $isLiveAnimated, $isActive,
                $dialBase, $dialHour, $dialMin, $dialSec,
                $dialCenterX, $dialCenterY,
                $productId
            ]
        );

        // Update Category if supplied
        if ($request->input('category_id') !== null) {
            $catId = (int)$request->input('category_id');
            Database::execute("DELETE FROM product_categories WHERE product_id = ?", [$productId]);
            if ($catId > 0 && Database::fetch("SELECT id FROM categories WHERE id = ?", [$catId])) {
                Database::execute("INSERT INTO product_categories (product_id, category_id) VALUES (?, ?)", [$productId, $catId]);
            }
        }

        // Also process any newly uploaded photos or image URL in the main edit form
        $uploaded = self::processUploadedFiles($productId, null, false);
        $newUrl = trim((string)$request->input('image_url', ''));
        if (!empty($newUrl)) {
            $maxSort = (int)(Database::fetch("SELECT MAX(sort_order) AS m FROM product_images WHERE product_id = ?", [$productId])['m'] ?? 0);
            Database::execute(
                "INSERT INTO product_images (product_id, variant_id, image_url, is_primary, sort_order, created_at) VALUES (?, NULL, ?, 0, ?, NOW())",
                [$productId, $newUrl, $maxSort + 1]
            );
            $uploaded++;
        }

        // If user wants new photo to be primary
        if ($uploaded > 0 && (bool)$request->input('replace_primary_photo', false)) {
            $latest = Database::fetch("SELECT id FROM product_images WHERE product_id = ? ORDER BY id DESC LIMIT 1", [$productId]);
            if ($latest) {
                Database::execute("UPDATE product_images SET is_primary = 0 WHERE product_id = ?", [$productId]);
                Database::execute("UPDATE product_images SET is_primary = 1 WHERE id = ?", [$latest['id']]);
            }
        }

        $msg = "Watch updated successfully.";
        if ($uploaded > 0) {
            $msg .= " Added {$uploaded} new photo(s).";
        }

        Session::flash('success', $msg);
        Response::redirect('/admin/products/' . $productId . '/edit');
    }

    public function destroy(Request $request, string $id): void
    {
        $productId = (int)$id;
        $product = Database::fetch("SELECT * FROM products WHERE id = ?", [$productId]);
        if (!$product) {
            Session::flash('error', "Watch not found.");
            Response::redirect('/admin/products');
        }

        try {
            Database::transaction(function () use ($productId) {
                // 1. Get variant IDs
                $variants = Database::query("SELECT id FROM product_variants WHERE product_id = ?", [$productId]);
                $variantIds = array_column($variants, 'id');

                if (!empty($variantIds)) {
                    $inPlaceholders = implode(',', array_fill(0, count($variantIds), '?'));
                    Database::execute("DELETE FROM cart_items WHERE variant_id IN ($inPlaceholders)", $variantIds);
                    Database::execute("DELETE FROM inventory_movements WHERE variant_id IN ($inPlaceholders)", $variantIds);
                    Database::execute("DELETE FROM inventory_reservations WHERE variant_id IN ($inPlaceholders)", $variantIds);
                    Database::execute("DELETE FROM inventory_stock WHERE variant_id IN ($inPlaceholders)", $variantIds);
                }

                // 2. Remove product images & delete physical files
                $images = Database::query("SELECT image_url FROM product_images WHERE product_id = ?", [$productId]);
                foreach ($images as $img) {
                    if (str_starts_with($img['image_url'], '/uploads/products/')) {
                        $filePath = __DIR__ . '/../../../public' . $img['image_url'];
                        if (file_exists($filePath)) {
                            @unlink($filePath);
                        }
                    }
                }
                Database::execute("DELETE FROM product_images WHERE product_id = ?", [$productId]);

                // 3. Remove other relations
                Database::execute("DELETE FROM product_categories WHERE product_id = ?", [$productId]);
                Database::execute("DELETE FROM product_collections WHERE product_id = ?", [$productId]);
                Database::execute("DELETE FROM product_comparisons WHERE product_id = ?", [$productId]);
                Database::execute("DELETE FROM product_specifications WHERE product_id = ?", [$productId]);
                Database::execute("DELETE FROM product_translations WHERE product_id = ?", [$productId]);
                Database::execute("DELETE FROM reviews WHERE product_id = ?", [$productId]);
                Database::execute("DELETE FROM wishlist_items WHERE product_id = ?", [$productId]);

                // 4. Remove variants
                Database::execute("DELETE FROM product_variants WHERE product_id = ?", [$productId]);

                // 5. Finally remove product
                Database::execute("DELETE FROM products WHERE id = ?", [$productId]);
            });

            Session::flash('success', "Watch '{$product['name']}' has been permanently deleted from catalog.");
        } catch (\Throwable $e) {
            Database::execute("UPDATE products SET is_active = 0 WHERE id = ?", [$productId]);
            Session::flash('info', "Watch '{$product['name']}' has been archived and hidden from the catalog.");
        }

        Response::redirect('/admin/products');
    }

    public function adjustStock(Request $request, string $id): void
    {
        $variantId = (int)$request->input('variant_id');
        $changeQty = (int)$request->input('change_qty');
        $reason = trim((string)$request->input('reason', 'Manual Admin Stock Adjustment'));
        $admin = admin_user();

        InventoryService::adjustStock($variantId, $changeQty, $reason, $admin['username'] ?? 'admin');
        Session::flash('success', "Stock adjusted successfully.");
        Response::redirect('/admin/products/' . $id . '/edit');
    }

    public function uploadImage(Request $request, string $id): void
    {
        $productId = (int)$id;
        $product = Database::fetch("SELECT * FROM products WHERE id = ?", [$productId]);
        if (!$product) {
            Session::flash('error', "Watch not found.");
            Response::redirect('/admin/products');
        }

        $isPrimaryWanted = (bool)$request->input('is_primary', false);
        $imageUrlInput = trim((string)$request->input('image_url', ''));

        // Upload any physical files
        $uploaded = self::processUploadedFiles($productId, null, false);

        // Or insert external URL if provided
        if (!empty($imageUrlInput)) {
            $maxSort = (int)(Database::fetch(
                "SELECT MAX(sort_order) AS m FROM product_images WHERE product_id = ?",
                [$productId]
            )['m'] ?? 0);

            $newImgId = Database::insert(
                "INSERT INTO product_images (product_id, variant_id, image_url, is_primary, sort_order, created_at)
                 VALUES (?, NULL, ?, 0, ?, NOW())",
                [$productId, $imageUrlInput, $maxSort + 1]
            );

            if ($isPrimaryWanted && $newImgId) {
                Database::execute("UPDATE product_images SET is_primary = 0 WHERE product_id = ?", [$productId]);
                Database::execute("UPDATE product_images SET is_primary = 1 WHERE id = ?", [$newImgId]);
            }
            $uploaded++;
        } elseif ($isPrimaryWanted && $uploaded > 0) {
            $latest = Database::fetch(
                "SELECT id FROM product_images WHERE product_id = ? ORDER BY id DESC LIMIT 1",
                [$productId]
            );
            if ($latest) {
                Database::execute("UPDATE product_images SET is_primary = 0 WHERE product_id = ?", [$productId]);
                Database::execute("UPDATE product_images SET is_primary = 1 WHERE id = ?", [$latest['id']]);
            }
        }

        if ($uploaded > 0) {
            Session::flash('success', "{$uploaded} product image(s) uploaded successfully.");
        } else {
            Session::flash('error', "Please choose an image file to upload or enter a valid image URL.");
        }

        Response::redirect('/admin/products/' . $productId . '/edit#product-images-card');
    }

    public function updateImage(Request $request, string $id, string $imageId): void
    {
        $productId = (int)$id;
        $imgId = (int)$imageId;

        $newUrl = trim((string)$request->input('image_url', ''));
        if (!empty($newUrl)) {
            Database::execute("UPDATE product_images SET image_url = ? WHERE id = ? AND product_id = ?", [$newUrl, $imgId, $productId]);
            Session::flash('success', "Photo URL updated successfully.");
        } else {
            Session::flash('error', "Image URL cannot be empty.");
        }

        Response::redirect('/admin/products/' . $productId . '/edit#product-images-card');
    }

    public function setPrimaryImage(Request $request, string $id, string $imageId): void
    {
        $productId = (int)$id;
        $imgId = (int)$imageId;

        Database::execute("UPDATE product_images SET is_primary = 0 WHERE product_id = ?", [$productId]);
        Database::execute("UPDATE product_images SET is_primary = 1 WHERE id = ? AND product_id = ?", [$imgId, $productId]);

        Session::flash('success', "Primary watch photo updated.");
        Response::redirect('/admin/products/' . $productId . '/edit#product-images-card');
    }

    public function deleteImage(Request $request, string $id, string $imageId): void
    {
        $productId = (int)$id;
        $imgId = (int)$imageId;

        $img = Database::fetch("SELECT * FROM product_images WHERE id = ? AND product_id = ?", [$imgId, $productId]);
        if ($img) {
            if (str_starts_with($img['image_url'], '/uploads/products/')) {
                $filePath = __DIR__ . '/../../../public' . $img['image_url'];
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }

            Database::execute("DELETE FROM product_images WHERE id = ? AND product_id = ?", [$imgId, $productId]);

            if ($img['is_primary']) {
                $next = Database::fetch("SELECT id FROM product_images WHERE product_id = ? ORDER BY id ASC LIMIT 1", [$productId]);
                if ($next) {
                    Database::execute("UPDATE product_images SET is_primary = 1 WHERE id = ?", [$next['id']]);
                }
            }

            Session::flash('success', "Product image removed.");
        }

        Response::redirect('/admin/products/' . $productId . '/edit#product-images-card');
    }

    public static function processUploadedFiles(int $productId, ?int $variantId = null, bool $setPrimaryIfFirst = false): int
    {
        $uploadDir = __DIR__ . '/../../../public/uploads/products';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $insertedCount = 0;
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'avif'];

        $fileList = [];
        if (!empty($_FILES['images']) && is_array($_FILES['images']['name'])) {
            $count = count($_FILES['images']['name']);
            for ($i = 0; $i < $count; $i++) {
                if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK && !empty($_FILES['images']['tmp_name'][$i])) {
                    $fileList[] = [
                        'name' => $_FILES['images']['name'][$i],
                        'tmp_name' => $_FILES['images']['tmp_name'][$i],
                        'size' => $_FILES['images']['size'][$i],
                    ];
                }
            }
        } elseif (!empty($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $fileList[] = $_FILES['image_file'];
        } elseif (!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileList[] = $_FILES['image'];
        }

        $hasPrimary = (bool)Database::fetch(
            "SELECT id FROM product_images WHERE product_id = ? AND is_primary = 1 LIMIT 1",
            [$productId]
        );

        foreach ($fileList as $file) {
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowedExtensions)) {
                continue;
            }

            // Verify MIME type
            if (function_exists('finfo_open')) {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $file['tmp_name']);
                finfo_close($finfo);
                if (!str_starts_with($mime, 'image/')) {
                    continue;
                }
            }

            $filename = 'watch_' . $productId . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            $destination = $uploadDir . '/' . $filename;

            $success = false;
            if (is_uploaded_file($file['tmp_name'])) {
                $success = move_uploaded_file($file['tmp_name'], $destination);
            } else {
                $success = copy($file['tmp_name'], $destination);
            }

            if ($success) {
                $imageUrl = '/uploads/products/' . $filename;
                $isPrimary = (!$hasPrimary && $setPrimaryIfFirst && $insertedCount === 0) ? 1 : 0;
                if ($isPrimary) {
                    $hasPrimary = true;
                }

                $maxSort = (int)(Database::fetch(
                    "SELECT MAX(sort_order) AS m FROM product_images WHERE product_id = ?",
                    [$productId]
                )['m'] ?? 0);

                Database::execute(
                    "INSERT INTO product_images (product_id, variant_id, image_url, is_primary, sort_order, created_at)
                     VALUES (?, ?, ?, ?, ?, NOW())",
                    [$productId, $variantId, $imageUrl, $isPrimary, $maxSort + 1]
                );
                $insertedCount++;
            }
        }

        return $insertedCount;
    }

    public static function uploadSingleFile(string $fieldName, string $subfolder = 'aplus'): ?string
    {
        if (empty($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        $file = $_FILES[$fieldName];
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'avif'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            return null;
        }
        $uploadDir = __DIR__ . '/../../../public/uploads/' . $subfolder;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $filename = $subfolder . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        if (move_uploaded_file($file['tmp_name'], $uploadDir . '/' . $filename)) {
            return '/uploads/' . $subfolder . '/' . $filename;
        }
        return null;
    }

    public function updateCoreSpecifications(Request $request, string $id): void
    {
        $productId = (int)$id;
        $movement = trim((string)$request->input('movement', ''));
        $caseSize = (int)$request->input('case_size_mm', 40);
        $caseMaterial = trim((string)$request->input('case_material', '316L Surgical Stainless Steel'));
        $strapMaterial = trim((string)$request->input('strap_material', ''));
        $waterResistance = trim((string)$request->input('water_resistance', '30M (3 ATM)'));
        $country = trim((string)$request->input('country_of_origin', 'India'));
        $hsn = trim((string)$request->input('hsn_code', '91021100'));

        Database::execute(
            "UPDATE products SET movement = ?, case_size_mm = ?, case_material = ?, strap_material = ?, water_resistance = ?, country_of_origin = ?, hsn_code = ? WHERE id = ?",
            [$movement, $caseSize, $caseMaterial, $strapMaterial, $waterResistance, $country, $hsn, $productId]
        );

        Session::flash('success', "Core technical specifications updated.");
        Response::redirect('/admin/products/' . $productId . '/edit#tab-specifications');
    }

    public function saveSpecification(Request $request, string $id): void
    {
        $productId = (int)$id;
        $specId = (int)$request->input('spec_id', 0);
        $name = trim((string)$request->input('spec_name'));
        $value = trim((string)$request->input('spec_value'));

        if (empty($name) || empty($value)) {
            Session::flash('error', "Specification Name and Value cannot be empty.");
            Response::redirect('/admin/products/' . $productId . '/edit#tab-specifications');
        }

        if ($specId > 0) {
            Database::execute("UPDATE product_specifications SET spec_name = ?, spec_value = ? WHERE id = ? AND product_id = ?", [$name, $value, $specId, $productId]);
            Session::flash('success', "Specification updated.");
        } else {
            $maxSort = (int)(Database::fetch("SELECT MAX(sort_order) AS m FROM product_specifications WHERE product_id = ?", [$productId])['m'] ?? 0);
            Database::execute("INSERT INTO product_specifications (product_id, spec_name, spec_value, sort_order) VALUES (?, ?, ?, ?)", [$productId, $name, $value, $maxSort + 1]);
            Session::flash('success', "Specification added.");
        }

        Response::redirect('/admin/products/' . $productId . '/edit#tab-specifications');
    }

    public function deleteSpecification(Request $request, string $id, string $specId): void
    {
        $productId = (int)$id;
        Database::execute("DELETE FROM product_specifications WHERE id = ? AND product_id = ?", [(int)$specId, $productId]);
        Session::flash('success', "Specification removed.");
        Response::redirect('/admin/products/' . $productId . '/edit#tab-specifications');
    }

    public function saveHotspot(Request $request, string $id): void
    {
        $productId = (int)$id;
        $hotspotId = (int)$request->input('hotspot_id', 0);
        $title = trim((string)$request->input('title'));
        $desc = trim((string)$request->input('description'));
        $xPos = (float)$request->input('x_pos_percent', 50.0);
        $yPos = (float)$request->input('y_pos_percent', 50.0);

        if (empty($title) || empty($desc)) {
            Session::flash('error', "Hotspot Title and Description are required.");
            Response::redirect('/admin/products/' . $productId . '/edit#tab-hotspots');
        }

        if ($hotspotId > 0) {
            Database::execute(
                "UPDATE product_hotspots SET title = ?, description = ?, x_pos_percent = ?, y_pos_percent = ? WHERE id = ? AND product_id = ?",
                [$title, $desc, $xPos, $yPos, $hotspotId, $productId]
            );
            Session::flash('success', "Anatomy hotspot pin updated.");
        } else {
            $maxSort = (int)(Database::fetch("SELECT MAX(sort_order) AS m FROM product_hotspots WHERE product_id = ?", [$productId])['m'] ?? 0);
            Database::execute(
                "INSERT INTO product_hotspots (product_id, title, description, x_pos_percent, y_pos_percent, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, 1)",
                [$productId, $title, $desc, $xPos, $yPos, $maxSort + 1]
            );
            Session::flash('success', "New anatomy hotspot pin created.");
        }

        Response::redirect('/admin/products/' . $productId . '/edit#tab-hotspots');
    }

    public function deleteHotspot(Request $request, string $id, string $hotspotId): void
    {
        $productId = (int)$id;
        Database::execute("DELETE FROM product_hotspots WHERE id = ? AND product_id = ?", [(int)$hotspotId, $productId]);
        Session::flash('success', "Hotspot pin deleted.");
        Response::redirect('/admin/products/' . $productId . '/edit#tab-hotspots');
    }

    public function saveBannerModule(Request $request, string $id): void
    {
        $productId = (int)$id;
        $headline = trim((string)$request->input('headline'));
        $body = trim((string)$request->input('body'));
        $imageUrl = trim((string)$request->input('image_url'));

        $uploaded = self::uploadSingleFile('banner_file', 'banners');
        if ($uploaded) {
            $imageUrl = $uploaded;
        }

        $existing = Database::fetch("SELECT * FROM product_page_modules WHERE product_id = ? AND module_type = 'banner'", [$productId]);
        $currentJson = $existing ? json_decode($existing['content_json'] ?? '{}', true) : [];
        if (empty($imageUrl) && !empty($currentJson['image_url'])) {
            $imageUrl = $currentJson['image_url'];
        }

        $newJson = [
            'headline' => $headline,
            'body' => $body,
            'image_url' => $imageUrl
        ];

        if ($existing) {
            Database::execute("UPDATE product_page_modules SET content_json = ?, is_enabled = 1, updated_at = NOW() WHERE id = ?", [json_encode($newJson), $existing['id']]);
        } else {
            Database::execute("INSERT INTO product_page_modules (product_id, module_type, title, content_json, sort_order, is_enabled) VALUES (?, 'banner', 'Editorial Wide Banner', ?, 1, 1)", [$productId, json_encode($newJson)]);
        }

        Session::flash('success', "Editorial Wide Banner updated.");
        Response::redirect('/admin/products/' . $productId . '/edit#tab-banner');
    }

    public function saveStoryModule(Request $request, string $id): void
    {
        $productId = (int)$id;
        $storyIndex = $request->input('story_index');
        $eyebrow = trim((string)$request->input('eyebrow', 'HOROLOGICAL CRAFT'));
        $title = trim((string)$request->input('title'));
        $text = trim((string)$request->input('text'));
        $imageUrl = trim((string)$request->input('image_url'));
        $bulletsRaw = trim((string)$request->input('bullets'));

        $uploaded = self::uploadSingleFile('story_file', 'stories');
        if ($uploaded) {
            $imageUrl = $uploaded;
        }

        $bullets = array_values(array_filter(array_map('trim', explode("\n", $bulletsRaw))));

        $existing = Database::fetch("SELECT * FROM product_page_modules WHERE product_id = ? AND module_type = 'stories'", [$productId]);
        $currentJson = $existing ? json_decode($existing['content_json'] ?? '{}', true) : [];
        $stories = $currentJson['stories'] ?? [];

        if ($storyIndex !== null && $storyIndex !== '' && isset($stories[(int)$storyIndex])) {
            $idx = (int)$storyIndex;
            if (empty($imageUrl) && !empty($stories[$idx]['image_url'])) {
                $imageUrl = $stories[$idx]['image_url'];
            }
            if (empty($eyebrow) && !empty($stories[$idx]['eyebrow'])) {
                $eyebrow = $stories[$idx]['eyebrow'];
            }
            $stories[$idx] = [
                'eyebrow' => $eyebrow,
                'title' => $title,
                'text' => $text,
                'image_url' => $imageUrl,
                'bullets' => $bullets
            ];
            Session::flash('success', "Craftsmanship story '{$title}' updated successfully.");
        } else {
            $stories[] = [
                'eyebrow' => $eyebrow ?: 'HOROLOGICAL CRAFT',
                'title' => $title,
                'text' => $text,
                'image_url' => $imageUrl,
                'bullets' => $bullets
            ];
            Session::flash('success', "New craftsmanship story '{$title}' added successfully.");
        }

        $newJson = ['stories' => $stories];
        if ($existing) {
            Database::execute("UPDATE product_page_modules SET content_json = ?, is_enabled = 1, updated_at = NOW() WHERE id = ?", [json_encode($newJson), $existing['id']]);
        } else {
            Database::execute("INSERT INTO product_page_modules (product_id, module_type, title, content_json, sort_order, is_enabled) VALUES (?, 'stories', 'Craftsmanship Feature Stories', ?, 2, 1)", [$productId, json_encode($newJson)]);
        }

        Response::redirect('/admin/products/' . $productId . '/edit#tab-stories');
    }

    public function deleteStoryModule(Request $request, string $id, string $storyIndex): void
    {
        $productId = (int)$id;
        $idx = (int)$storyIndex;

        $existing = Database::fetch("SELECT * FROM product_page_modules WHERE product_id = ? AND module_type = 'stories'", [$productId]);
        if ($existing) {
            $json = json_decode($existing['content_json'] ?? '{}', true);
            if (!empty($json['stories']) && isset($json['stories'][$idx])) {
                array_splice($json['stories'], $idx, 1);
                Database::execute("UPDATE product_page_modules SET content_json = ?, updated_at = NOW() WHERE id = ?", [json_encode($json), $existing['id']]);
                Session::flash('success', "Craftsmanship story deleted.");
            }
        }

        Response::redirect('/admin/products/' . $productId . '/edit#tab-stories');
    }

    public function saveLifestyleModule(Request $request, string $id): void
    {
        $productId = (int)$id;
        $photoIndex = $request->input('photo_index');
        $caption = trim((string)$request->input('caption'));
        $subcaption = trim((string)$request->input('subcaption'));
        $url = trim((string)$request->input('url'));

        $uploaded = self::uploadSingleFile('lifestyle_file', 'lifestyle');
        if ($uploaded) {
            $url = $uploaded;
        }

        $existing = Database::fetch("SELECT * FROM product_page_modules WHERE product_id = ? AND module_type = 'lifestyle'", [$productId]);
        $currentJson = $existing ? json_decode($existing['content_json'] ?? '{}', true) : [];
        $images = $currentJson['images'] ?? [];

        if ($photoIndex !== null && $photoIndex !== '' && isset($images[(int)$photoIndex])) {
            $idx = (int)$photoIndex;
            if (empty($url) && !empty($images[$idx]['url'])) {
                $url = $images[$idx]['url'];
            }
            $images[$idx] = [
                'url' => $url,
                'caption' => $caption,
                'subcaption' => $subcaption
            ];
            Session::flash('success', "On The Wrist photo updated.");
        } else {
            $images[] = [
                'url' => $url,
                'caption' => $caption,
                'subcaption' => $subcaption
            ];
            Session::flash('success', "New On The Wrist photo added.");
        }

        $newJson = [
            'title' => 'On The Wrist',
            'images' => $images
        ];

        if ($existing) {
            Database::execute("UPDATE product_page_modules SET content_json = ?, is_enabled = 1, updated_at = NOW() WHERE id = ?", [json_encode($newJson), $existing['id']]);
        } else {
            Database::execute("INSERT INTO product_page_modules (product_id, module_type, title, content_json, sort_order, is_enabled) VALUES (?, 'lifestyle', 'Lifestyle Carousel', ?, 3, 1)", [$productId, json_encode($newJson)]);
        }

        Response::redirect('/admin/products/' . $productId . '/edit#tab-lifestyle');
    }

    public function deleteLifestyleModule(Request $request, string $id, string $photoIndex): void
    {
        $productId = (int)$id;
        $idx = (int)$photoIndex;

        $existing = Database::fetch("SELECT * FROM product_page_modules WHERE product_id = ? AND module_type = 'lifestyle'", [$productId]);
        if ($existing) {
            $json = json_decode($existing['content_json'] ?? '{}', true);
            if (!empty($json['images']) && isset($json['images'][$idx])) {
                array_splice($json['images'], $idx, 1);
                Database::execute("UPDATE product_page_modules SET content_json = ?, updated_at = NOW() WHERE id = ?", [json_encode($json), $existing['id']]);
                Session::flash('success', "Lifestyle photo deleted.");
            }
        }

        Response::redirect('/admin/products/' . $productId . '/edit#tab-lifestyle');
    }

    public function saveFaq(Request $request, string $id): void
    {
        $productId = (int)$id;
        $faqId = (int)$request->input('faq_id', 0);
        $question = trim((string)$request->input('question'));
        $answer = trim((string)$request->input('answer'));

        if (empty($question) || empty($answer)) {
            Session::flash('error', "FAQ Question and Answer cannot be empty.");
            Response::redirect('/admin/products/' . $productId . '/edit#tab-faqs');
        }

        if ($faqId > 0) {
            Database::execute("UPDATE product_faqs SET question = ?, answer = ? WHERE id = ?", [$question, $answer, $faqId]);
            Session::flash('success', "FAQ updated.");
        } else {
            $maxSort = (int)(Database::fetch("SELECT MAX(sort_order) AS m FROM product_faqs WHERE product_id = ?", [$productId])['m'] ?? 0);
            Database::execute("INSERT INTO product_faqs (product_id, question, answer, sort_order, is_active) VALUES (?, ?, ?, ?, 1)", [$productId, $question, $answer, $maxSort + 1]);
            Session::flash('success', "New FAQ added.");
        }

        Response::redirect('/admin/products/' . $productId . '/edit#tab-faqs');
    }

    public function deleteFaq(Request $request, string $id, string $faqId): void
    {
        $productId = (int)$id;
        Database::execute("DELETE FROM product_faqs WHERE id = ?", [(int)$faqId]);
        Session::flash('success', "FAQ deleted.");
        Response::redirect('/admin/products/' . $productId . '/edit#tab-faqs');
    }

    public function saveReview(Request $request, string $id): void
    {
        $productId = (int)$id;
        $comment = trim((string)$request->input('comment'));

        if (empty($comment)) {
            Session::flash('error', "Review comment cannot be empty.");
            Response::redirect('/admin/products/' . $productId . '/edit#tab-reviews');
            return;
        }

        \App\Services\Reviews\ReviewService::addAdminReview([
            'product_id' => $productId,
            'author_name' => $request->input('author_name', 'Verified Buyer'),
            'avatar_url' => $request->input('avatar_url'),
            'rating' => (int)$request->input('rating', 5),
            'title' => $request->input('title'),
            'comment' => $comment,
            'is_verified_purchase' => (int)$request->input('is_verified_purchase', 1),
            'status' => $request->input('status', 'approved'),
            'wristshot_url' => $request->input('wristshot_url'),
        ]);

        Session::flash('success', "New verified customer review added.");
        Response::redirect('/admin/products/' . $productId . '/edit#tab-reviews');
    }

    public function updateReview(Request $request, string $id, string $reviewId): void
    {
        $productId = (int)$id;
        $revId = (int)$reviewId;

        $comment = trim((string)$request->input('comment'));
        if (empty($comment)) {
            Session::flash('error', "Review comment cannot be empty.");
            Response::redirect('/admin/products/' . $productId . '/edit#tab-reviews');
            return;
        }

        \App\Services\Reviews\ReviewService::updateReview($revId, [
            'author_name' => $request->input('author_name'),
            'avatar_url' => $request->input('avatar_url'),
            'rating' => (int)$request->input('rating', 5),
            'title' => $request->input('title'),
            'comment' => $comment,
            'status' => $request->input('status', 'approved'),
            'is_verified_purchase' => (int)$request->input('is_verified_purchase', 1),
            'wristshot_url' => $request->input('wristshot_url'),
        ]);

        Session::flash('success', "Review #{$revId} updated successfully.");
        Response::redirect('/admin/products/' . $productId . '/edit#tab-reviews');
    }

    public function deleteReview(Request $request, string $id, string $reviewId): void
    {
        $productId = (int)$id;
        $revId = (int)$reviewId;

        \App\Services\Reviews\ReviewService::deleteReview($revId);

        Session::flash('info', "Review #{$revId} deleted successfully.");
        Response::redirect('/admin/products/' . $productId . '/edit#tab-reviews');
    }
}
