<?php

namespace App\Controllers\Admin;

use App\Support\Request;
use App\Support\View;
use App\Support\Response;
use App\Support\Session;
use App\Support\Database;

class BrandController
{
    public function index(Request $request): string
    {
        $brands = Database::query(
            "SELECT b.*, COUNT(p.id) AS product_count 
             FROM brands b 
             LEFT JOIN products p ON b.id = p.brand_id 
             GROUP BY b.id 
             ORDER BY b.is_featured DESC, b.name ASC"
        );

        return View::render('admin.brands.index', [
            'title' => 'Brand Portfolio Management | Welkar Admin',
            'brands' => $brands,
        ], 'layouts.admin');
    }

    private static function handleFileUpload(string $fieldName): ?string
    {
        if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $file = $_FILES[$fieldName];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/svg+xml'];
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
            'image/svg+xml' => 'svg',
            default      => 'png',
        };

        $uploadDir = __DIR__ . '/../../../public/uploads/brands';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = 'brand_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $destPath = $uploadDir . '/' . $fileName;

        if (move_uploaded_file($file['tmp_name'], $destPath)) {
            return '/uploads/brands/' . $fileName;
        }

        return null;
    }

    public function store(Request $request): void
    {
        $name = trim((string)$request->input('name'));
        $slug = trim((string)$request->input('slug')) ?: strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $name));
        $description = trim((string)$request->input('description'));
        $logoUrl = trim((string)$request->input('logo_url'));
        $isFeatured = $request->input('is_featured') ? 1 : 0;
        $isActive = $request->input('is_active') ? 1 : 0;

        $uploaded = self::handleFileUpload('logo_file');
        if ($uploaded) {
            $logoUrl = $uploaded;
        }

        if (empty($name)) {
            Session::flash('error', 'Brand name is required.');
            Response::redirect('/admin/brands');
        }

        // Check unique slug
        $exists = Database::fetch("SELECT id FROM brands WHERE slug = ?", [$slug]);
        if ($exists) {
            $slug .= '-' . time();
        }

        Database::execute(
            "INSERT INTO brands (name, slug, logo_url, description, is_featured, is_active, created_at)
             VALUES (?, ?, ?, ?, ?, ?, NOW())",
            [$name, $slug, $logoUrl, $description, $isFeatured, $isActive]
        );

        // Audit log
        $admin = admin_user();
        Database::execute(
            "INSERT INTO audit_logs (user_type, user_id, action, entity_type, entity_id, new_values, ip_address, created_at)
             VALUES ('admin', ?, 'create_brand', 'brand', LAST_INSERT_ID(), ?, ?, NOW())",
            [$admin['id'] ?? null, json_encode(['name' => $name, 'slug' => $slug]), $request->ip()]
        );

        Session::flash('success', "Brand '{$name}' created successfully.");
        Response::redirect('/admin/brands');
    }

    public function update(Request $request, string $id): void
    {
        $brandId = (int)$id;
        $brand = Database::fetch("SELECT * FROM brands WHERE id = ?", [$brandId]);
        if (!$brand) {
            Session::flash('error', 'Brand not found.');
            Response::redirect('/admin/brands');
        }

        $name = trim((string)$request->input('name'));
        $slug = trim((string)$request->input('slug')) ?: $brand['slug'];
        $description = trim((string)$request->input('description'));
        $logoUrl = trim((string)$request->input('logo_url')) ?: $brand['logo_url'];
        $isFeatured = $request->input('is_featured') ? 1 : 0;
        $isActive = $request->input('is_active') ? 1 : 0;

        $uploaded = self::handleFileUpload('logo_file');
        if ($uploaded) {
            $logoUrl = $uploaded;
        }

        Database::execute(
            "UPDATE brands SET name = ?, slug = ?, logo_url = ?, description = ?, is_featured = ?, is_active = ? WHERE id = ?",
            [$name, $slug, $logoUrl, $description, $isFeatured, $isActive, $brandId]
        );

        // Audit log
        $admin = admin_user();
        Database::execute(
            "INSERT INTO audit_logs (user_type, user_id, action, entity_type, entity_id, new_values, ip_address, created_at)
             VALUES ('admin', ?, 'update_brand', 'brand', ?, ?, ?, NOW())",
            [$admin['id'] ?? null, $brandId, json_encode(['name' => $name, 'slug' => $slug]), $request->ip()]
        );

        Session::flash('success', "Brand '{$name}' updated successfully.");
        Response::redirect('/admin/brands');
    }

    public function destroy(Request $request, string $id): void
    {
        $brandId = (int)$id;
        $brand = Database::fetch("SELECT * FROM brands WHERE id = ?", [$brandId]);
        if (!$brand) {
            Session::flash('error', 'Brand not found.');
            Response::redirect('/admin/brands');
        }

        // Check if products are attached to this brand
        $productCount = (int)Database::fetch(
            "SELECT COUNT(*) AS total FROM products WHERE brand_id = ?",
            [$brandId]
        )['total'];

        if ($productCount > 0) {
            Session::flash('error', "Cannot delete brand '{$brand['name']}' because {$productCount} product(s) are currently attached to it. Please reassign products first.");
            Response::redirect('/admin/brands');
        }

        Database::execute("DELETE FROM brands WHERE id = ?", [$brandId]);

        // Audit log
        $admin = admin_user();
        Database::execute(
            "INSERT INTO audit_logs (user_type, user_id, action, entity_type, entity_id, old_values, ip_address, created_at)
             VALUES ('admin', ?, 'delete_brand', 'brand', ?, ?, ?, NOW())",
            [$admin['id'] ?? null, $brandId, json_encode($brand), $request->ip()]
        );

        Session::flash('success', "Brand '{$brand['name']}' removed successfully.");
        Response::redirect('/admin/brands');
    }
}
