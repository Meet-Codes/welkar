<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Support\Request;
use App\Support\View;
use App\Support\Response;
use App\Support\Session;
use App\Services\Company\CompanyProfileService;

class CompanyProfileController
{
    public function index(Request $request): string
    {
        $profile = CompanyProfileService::getProfile();
        $founders = CompanyProfileService::getFounders(false);
        $values = CompanyProfileService::getValues(false);
        $milestones = CompanyProfileService::getMilestones(false);

        return View::render('admin.company_profile.index', [
            'title' => 'Company Profile & Brand Storytelling | Welkar Admin',
            'profile' => $profile,
            'founders' => $founders,
            'values' => $values,
            'milestones' => $milestones,
        ], 'layouts.admin');
    }

    public function updateProfile(Request $request): void
    {
        $fields = [
            'brand_name', 'tagline', 'founding_year', 'hero_title', 'hero_intro',
            'story_title', 'story_intro', 'story_content',
            'philosophy_title', 'philosophy_intro',
            'approach_watches_title', 'approach_watches_content',
            'quality_title', 'quality_content',
            'mission_statement', 'vision_statement',
            'commitment_title', 'commitment_content',
            'journey_ahead_title', 'journey_ahead_content',
            'home_summary', 'footer_summary', 'pdp_summary', 'collection_summary',
            'seo_title', 'seo_description'
        ];

        $data = [];
        foreach ($fields as $field) {
            $val = $request->input($field);
            if ($val !== null) {
                $data[$field] = trim((string)$val);
            }
        }

        CompanyProfileService::updateProfile($data);

        Session::flash('success', 'Company profile and brand storytelling content updated successfully.');
        Response::redirect('/admin/company-profile');
    }

    public function saveFounder(Request $request): void
    {
        $id = $request->input('founder_id') ? (int)$request->input('founder_id') : null;
        $name = trim((string)$request->input('name', ''));
        $designation = trim((string)$request->input('designation', 'Co-Founder'));
        $perspective = trim((string)$request->input('perspective', ''));
        $bio = trim((string)$request->input('bio', ''));
        $imageUrl = trim((string)$request->input('image_url', ''));
        $imageAlt = trim((string)$request->input('image_alt', ''));
        $order = (int)$request->input('display_order', 0);
        $isPublished = (int)$request->input('is_published', 1);

        if (empty($name)) {
            Session::flash('error', 'Founder full name is required.');
            Response::redirect('/admin/company-profile#founders');
            return;
        }

        CompanyProfileService::saveFounder([
            'name' => $name,
            'designation' => $designation,
            'perspective' => $perspective,
            'bio' => $bio,
            'image_url' => !empty($imageUrl) ? $imageUrl : null,
            'image_alt' => !empty($imageAlt) ? $imageAlt : null,
            'display_order' => $order,
            'is_published' => $isPublished,
        ], $id);

        Session::flash('success', 'Founder profile saved successfully.');
        Response::redirect('/admin/company-profile#founders');
    }

    public function saveMilestone(Request $request): void
    {
        $id = $request->input('milestone_id') ? (int)$request->input('milestone_id') : null;
        $yearDate = trim((string)$request->input('year_date', ''));
        $title = trim((string)$request->input('title', ''));
        $description = trim((string)$request->input('description', ''));
        $imageUrl = trim((string)$request->input('image_url', ''));
        $order = (int)$request->input('display_order', 0);
        $isPublished = (int)$request->input('is_published', 1);

        if (empty($yearDate) || empty($title)) {
            Session::flash('error', 'Milestone year/date and title are required.');
            Response::redirect('/admin/company-profile#milestones');
            return;
        }

        CompanyProfileService::saveMilestone([
            'year_date' => $yearDate,
            'title' => $title,
            'description' => $description,
            'image_url' => !empty($imageUrl) ? $imageUrl : null,
            'display_order' => $order,
            'is_published' => $isPublished,
        ], $id);

        Session::flash('success', 'Milestone entry saved successfully.');
        Response::redirect('/admin/company-profile#milestones');
    }

    public function deleteMilestone(Request $request, string $id): void
    {
        CompanyProfileService::deleteMilestone((int)$id);
        Session::flash('success', 'Milestone removed.');
        Response::redirect('/admin/company-profile#milestones');
    }
}
