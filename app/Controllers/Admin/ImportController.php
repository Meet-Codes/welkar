<?php

namespace App\Controllers\Admin;

use App\Support\Request;
use App\Support\View;
use App\Support\Response;
use App\Support\Session;
use App\Support\Database;
use App\Services\ImportExport\ProductImportService;

class ImportController
{
    public function index(Request $request): string
    {
        $jobs = Database::query(
            "SELECT ij.*, au.username
             FROM import_jobs ij
             JOIN admin_users au ON ij.admin_user_id = au.id
             ORDER BY ij.id DESC LIMIT 20"
        );

        return View::render('admin.importer.index', [
            'title' => 'Bulk Product Importer & Data Feed | Welkar Admin',
            'jobs' => $jobs,
        ], 'layouts.admin');
    }

    public function downloadTemplate(Request $request): void
    {
        $csv = ProductImportService::getTemplateCsv();
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="welkar_products_import_template.csv"');
        echo $csv;
        exit;
    }

    public function upload(Request $request): void
    {
        $file = $request->file('csv_file');
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            Session::flash('error', 'Please select a valid CSV file to upload.');
            Response::redirect('/admin/importer');
        }

        $destDir = __DIR__ . '/../../../storage/imports';
        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }

        $filename = 'import_' . date('Ymd_His') . '_' . basename($file['name']);
        $targetPath = $destDir . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            Session::flash('error', 'Failed to store uploaded file.');
            Response::redirect('/admin/importer');
        }

        $mode = $request->input('mode', 'upsert');
        $admin = admin_user();

        $result = ProductImportService::processImport($targetPath, $file['name'], $admin['id'], $mode);

        if ($result['success']) {
            Session::flash(
                'success',
                "Import completed! Successfully imported: {$result['imported']} rows, Failed: {$result['failed']} rows."
            );
        } else {
            Session::flash('error', 'Import failed: ' . ($result['message'] ?? 'Unknown error'));
        }

        Response::redirect('/admin/importer');
    }
}
