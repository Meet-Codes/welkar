<?php

namespace App\Controllers\Admin;

use App\Support\Request;
use App\Support\View;
use App\Support\Response;
use App\Support\Session;
use App\Support\Database;

class CategoryController
{
    public function index(Request $request): string
    {
        $categories = Database::query(
            "SELECT c.*, COUNT(pc.product_id) AS products_count
             FROM categories c
             LEFT JOIN product_categories pc ON c.id = pc.category_id
             GROUP BY c.id ORDER BY c.sort_order ASC, c.name ASC"
        );

        $collections = Database::query(
            "SELECT cl.*, COUNT(pcl.product_id) AS products_count
             FROM collections cl
             LEFT JOIN product_collections pcl ON cl.id = pcl.collection_id
             GROUP BY cl.id ORDER BY cl.id ASC"
        );

        return View::render('admin.categories.index', [
            'title' => 'Categories & Curated Collections | Welkar Admin',
            'categories' => $categories,
            'collections' => $collections,
        ], 'layouts.admin');
    }

    private static function handleFileUpload(string $fieldName): ?string
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

        $uploadDir = __DIR__ . '/../../../public/uploads/categories';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = 'category_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $destPath = $uploadDir . '/' . $fileName;

        if (move_uploaded_file($file['tmp_name'], $destPath)) {
            return '/uploads/categories/' . $fileName;
        }

        return null;
    }

    public function storeCategory(Request $request): void
    {
        $name = trim((string)$request->input('name'));
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $name));
        $desc = trim((string)$request->input('description'));
        $imageUrl = trim((string)$request->input('image_url'));

        $uploaded = self::handleFileUpload('image_file');
        if ($uploaded) {
            $imageUrl = $uploaded;
        }

        Database::execute(
            "INSERT INTO categories (name, slug, description, image_url, is_active) VALUES (?, ?, ?, ?, 1)",
            [$name, $slug, $desc, $imageUrl]
        );

        Session::flash('success', "Category '{$name}' created.");
        Response::redirect('/admin/categories');
    }

    public function updateCategory(Request $request, string $id): void
    {
        $catId = (int)$id;
        $existing = Database::fetch("SELECT * FROM categories WHERE id = ?", [$catId]);
        $name = trim((string)$request->input('name'));
        $slug = trim((string)$request->input('slug')) ?: strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $name));
        $desc = trim((string)$request->input('description'));
        $imageUrl = trim((string)$request->input('image_url', $existing['image_url'] ?? ''));
        $isActive = $request->input('is_active') ? 1 : 0;

        $uploaded = self::handleFileUpload('image_file');
        if ($uploaded) {
            $imageUrl = $uploaded;
        }

        Database::execute(
            "UPDATE categories SET name = ?, slug = ?, description = ?, image_url = ?, is_active = ? WHERE id = ?",
            [$name, $slug, $desc, $imageUrl, $isActive, $catId]
        );

        Session::flash('success', "Category '{$name}' updated.");
        Response::redirect('/admin/categories');
    }

    public function deleteCategory(Request $request, string $id): void
    {
        $catId = (int)$id;
        $category = Database::fetch("SELECT * FROM categories WHERE id = ?", [$catId]);
        if (!$category) {
            Session::flash('error', 'Category not found.');
            Response::redirect('/admin/categories');
        }

        $linkedProducts = (int)Database::fetch(
            "SELECT COUNT(*) AS total FROM product_categories WHERE category_id = ?",
            [$catId]
        )['total'];

        if ($linkedProducts > 0) {
            Session::flash('error', "Cannot delete category '{$category['name']}' because {$linkedProducts} product(s) are mapped to it. Please reassign products first.");
            Response::redirect('/admin/categories');
        }

        Database::execute("DELETE FROM categories WHERE id = ?", [$catId]);
        Session::flash('success', "Category '{$category['name']}' deleted successfully.");
        Response::redirect('/admin/categories');
    }
}
