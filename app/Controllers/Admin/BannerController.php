<?php

namespace App\Controllers\Admin;

use App\Support\Request;
use App\Support\View;
use App\Support\Response;
use App\Support\Session;
use App\Support\Database;

class BannerController
{
    public function index(Request $request): string
    {
        $banners = Database::query("SELECT * FROM banners ORDER BY display_order ASC, id DESC");

        return View::render('admin.banners.index', [
            'title' => 'Hero Banners & Visual Campaigns | Welkar Admin',
            'banners' => $banners,
        ], 'layouts.admin');
    }

    public function store(Request $request): void
    {
        $title = trim((string)$request->input('title'));
        $subtitle = trim((string)$request->input('subtitle'));
        $imageDesktop = trim((string)$request->input('image_desktop'));
        $imageMobile = trim((string)$request->input('image_mobile'));
        $linkUrl = trim((string)$request->input('link_url')) ?: '/catalog';
        $buttonText = trim((string)$request->input('button_text')) ?: 'Explore Collection';
        $displayOrder = (int)$request->input('display_order', 0);
        $isActive = $request->input('is_active') ? 1 : 0;

        // Check if an image file was uploaded
        $uploadedPath = self::handleFileUpload('banner_file');
        if ($uploadedPath) {
            $imageDesktop = $uploadedPath;
        }

        if (empty($imageMobile)) {
            $imageMobile = $imageDesktop;
        }

        if (empty($title) || empty($imageDesktop)) {
            Session::flash('error', 'Title and an Image (file upload or URL) are required.');
            Response::redirect('/admin/banners');
        }

        Database::execute(
            "INSERT INTO banners (title, subtitle, image_desktop, image_mobile, link_url, button_text, display_order, is_active, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())",
            [$title, $subtitle, $imageDesktop, $imageMobile, $linkUrl, $buttonText, $displayOrder, $isActive]
        );

        // Audit log
        $admin = admin_user();
        Database::execute(
            "INSERT INTO audit_logs (user_type, user_id, action, entity_type, entity_id, new_values, ip_address, created_at)
             VALUES ('admin', ?, 'create_banner', 'banner', LAST_INSERT_ID(), ?, ?, NOW())",
            [$admin['id'] ?? null, json_encode(['title' => $title, 'link' => $linkUrl]), $request->ip()]
        );

        Session::flash('success', "Hero banner '{$title}' created successfully.");
        Response::redirect('/admin/banners');
    }

    public function update(Request $request, string $id): void
    {
        $bannerId = (int)$id;
        $existing = Database::fetch("SELECT * FROM banners WHERE id = ?", [$bannerId]);
        if (!$existing) {
            Session::flash('error', "Banner not found.");
            Response::redirect('/admin/banners');
        }

        $title = trim((string)$request->input('title'));
        $subtitle = trim((string)$request->input('subtitle'));
        $imageDesktop = trim((string)$request->input('image_desktop'));
        $imageMobile = trim((string)$request->input('image_mobile'));
        $linkUrl = trim((string)$request->input('link_url')) ?: '/catalog';
        $buttonText = trim((string)$request->input('button_text')) ?: 'Explore Collection';
        $displayOrder = (int)$request->input('display_order', 0);
        $isActive = $request->input('is_active') ? 1 : 0;

        // Check if a new image file was uploaded
        $uploadedPath = self::handleFileUpload('banner_file');
        if ($uploadedPath) {
            $imageDesktop = $uploadedPath;
        } elseif (empty($imageDesktop)) {
            $imageDesktop = $existing['image_desktop'];
        }

        if (empty($imageMobile)) {
            $imageMobile = (!empty($existing['image_mobile']) && $imageDesktop === $existing['image_desktop'])
                ? $existing['image_mobile'] 
                : $imageDesktop;
        }

        Database::execute(
            "UPDATE banners SET title = ?, subtitle = ?, image_desktop = ?, image_mobile = ?, link_url = ?, button_text = ?, display_order = ?, is_active = ?
             WHERE id = ?",
            [$title, $subtitle, $imageDesktop, $imageMobile, $linkUrl, $buttonText, $displayOrder, $isActive, $bannerId]
        );

        Session::flash('success', "Banner '{$title}' updated successfully.");
        Response::redirect('/admin/banners');
    }

    public function destroy(Request $request, string $id): void
    {
        $bannerId = (int)$id;
        $banner = Database::fetch("SELECT * FROM banners WHERE id = ?", [$bannerId]);
        if ($banner && str_starts_with($banner['image_desktop'], '/uploads/banners/')) {
            $file = __DIR__ . '/../../../public' . $banner['image_desktop'];
            if (file_exists($file)) {
                @unlink($file);
            }
        }

        Database::execute("DELETE FROM banners WHERE id = ?", [$bannerId]);

        Session::flash('success', "Banner removed.");
        Response::redirect('/admin/banners');
    }

    private static function handleFileUpload(string $fieldName): ?string
    {
        if (empty($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $file = $_FILES[$fieldName];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'avif'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExtensions)) {
            return null;
        }

        $uploadDir = __DIR__ . '/../../../public/uploads/banners';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = 'banner_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $targetPath = $uploadDir . '/' . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return '/uploads/banners/' . $fileName;
        }

        return null;
    }
}
