<?php

namespace App\Controllers\Admin;

use App\Support\Request;
use App\Support\View;
use App\Services\Analytics\AnalyticsService;

class DashboardController
{
    public function index(Request $request): string
    {
        $range = $request->query('range', '30d');
        $kpis = AnalyticsService::getDashboardKpis($range);

        return View::render('admin.dashboard', [
            'title' => 'Executive Dashboard | Welkar Admin',
            'range' => $range,
            'kpis' => $kpis,
            'admin' => admin_user(),
        ], 'layouts.admin');
    }
}
