<?php

namespace App\Controllers\Admin;

use App\Support\Request;
use App\Support\View;
use App\Support\Response;
use App\Support\Session;
use App\Support\Database;

class PageController
{
    public function index(Request $request): string
    {
        $pages = Database::fetchAll("SELECT * FROM content_pages ORDER BY id ASC");

        return View::render('admin.pages.index', [
            'title' => 'Content Pages CMS | Admin',
            'pages' => $pages,
        ], 'layouts.admin');
    }

    public function edit(Request $request, string $id): string
    {
        $page = Database::fetch("SELECT * FROM content_pages WHERE id = ? LIMIT 1", [(int)$id]);
        if (!$page) {
            Session::flash('error', 'Page not found.');
            Response::redirect('/admin/pages');
        }

        return View::render('admin.pages.edit', [
            'title' => 'Edit Page: ' . $page['title'] . ' | Admin CMS',
            'page' => $page,
        ], 'layouts.admin');
    }

    public function update(Request $request, string $id): void
    {
        $title = trim((string)$request->input('title'));
        $slug = trim((string)$request->input('slug'));
        $contentHtml = (string)$request->input('content_html');
        $metaTitle = trim((string)$request->input('meta_title'));
        $metaDesc = trim((string)$request->input('meta_description'));
        $isPublished = (int)$request->input('is_published', 1);

        if (empty($title) || empty($slug)) {
            Session::flash('error', 'Page title and slug are required fields.');
            Response::redirect("/admin/pages/{$id}/edit");
            return;
        }

        Database::query(
            "UPDATE content_pages 
             SET title = ?, slug = ?, content_html = ?, meta_title = ?, meta_description = ?, is_published = ? 
             WHERE id = ?",
            [$title, $slug, $contentHtml, $metaTitle, $metaDesc, $isPublished, (int)$id]
        );

        Session::flash('success', "Page '{$title}' updated successfully.");
        Response::redirect('/admin/pages');
    }
}
