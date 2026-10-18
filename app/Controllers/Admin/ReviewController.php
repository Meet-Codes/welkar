<?php

namespace App\Controllers\Admin;

use App\Support\Request;
use App\Support\View;
use App\Support\Response;
use App\Support\Session;
use App\Support\Database;

class ReviewController
{
    public function index(Request $request): string
    {
        $status = $request->query('status');
        $params = [];
        $where = [];

        if ($status && in_array($status, ['pending', 'approved', 'rejected'])) {
            $where[] = "r.status = ?";
            $params[] = $status;
        }

        $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

        $reviews = Database::fetchAll(
            "SELECT r.*, p.name AS product_name, p.slug AS product_slug
             FROM reviews r
             JOIN products p ON r.product_id = p.id
             {$whereClause}
             ORDER BY r.id DESC",
            $params
        );

        return View::render('admin.reviews.index', [
            'title' => 'Customer Reviews Moderation | Admin',
            'reviews' => $reviews,
            'currentStatus' => $status,
        ], 'layouts.admin');
    }

    public function updateStatus(Request $request, string $id): void
    {
        $status = (string)$request->input('status');
        if (in_array($status, ['pending', 'approved', 'rejected'])) {
            Database::query("UPDATE reviews SET status = ? WHERE id = ?", [$status, (int)$id]);
            Session::flash('success', "Review #{$id} status updated to {$status}.");
        } else {
            Session::flash('error', "Invalid review status specified.");
        }

        Response::redirect('/admin/reviews');
    }

    public function update(Request $request, string $id): void
    {
        $reviewId = (int)$id;
        $success = \App\Services\Reviews\ReviewService::updateReview($reviewId, [
            'author_name' => $request->input('author_name'),
            'avatar_url' => $request->input('avatar_url'),
            'rating' => (int)$request->input('rating', 5),
            'title' => $request->input('title'),
            'comment' => $request->input('comment'),
            'status' => $request->input('status', 'approved'),
            'is_verified_purchase' => $request->input('is_verified_purchase', 1),
            'wristshot_url' => $request->input('wristshot_url'),
        ]);

        if ($success) {
            Session::flash('success', "Review #{$reviewId} updated successfully.");
        } else {
            Session::flash('error', "Could not update review #{$reviewId}.");
        }

        Response::redirect('/admin/reviews');
    }

    public function destroy(Request $request, string $id): void
    {
        Database::query("DELETE FROM reviews WHERE id = ?", [(int)$id]);
        Session::flash('info', "Review #{$id} deleted successfully.");
        Response::redirect('/admin/reviews');
    }
}
