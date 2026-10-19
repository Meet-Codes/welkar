<?php

namespace App\Controllers\Admin;

use App\Support\Request;
use App\Support\View;
use App\Support\Response;
use App\Support\Session;
use App\Support\Database;

class SettingController
{
    public function index(Request $request): string
    {
        $rawSettings = Database::query("SELECT * FROM store_settings");
        $settings = [];
        foreach ($rawSettings as $s) {
            $settings[$s['setting_key']] = $s['setting_value'];
        }

        return View::render('admin.settings.index', [
            'title' => 'Store & Business Settings | Welkar Admin',
            'settings' => $settings,
        ], 'layouts.admin');
    }

    public function update(Request $request): void
    {
        $keys = [
            'site_name', 'business_legal_name', 'business_gstin', 'business_state',
            'business_state_code', 'business_email', 'business_phone',
            'default_shipping_fee', 'free_shipping_threshold', 'cod_enabled',
            'cod_max_amount', 'razorpay_mode', 'razorpay_key_id', 'razorpay_key_secret',
            'razorpay_webhook_secret', 'meta_pixel_id', 'meta_conversions_api_token', 'meta_test_event_code',
            'default_gst_rate', 'invoice_prefix'
        ];

        foreach ($keys as $k) {
            $val = $request->input($k);
            if ($val !== null) {
                $trimmedVal = trim((string)$val);
                $existing = Database::fetch("SELECT id FROM store_settings WHERE setting_key = ? LIMIT 1", [$k]);
                if ($existing) {
                    Database::execute("UPDATE store_settings SET setting_value = ? WHERE setting_key = ?", [$trimmedVal, $k]);
                } else {
                    Database::execute("INSERT INTO store_settings (setting_key, setting_value) VALUES (?, ?)", [$k, $trimmedVal]);
                }
            }
        }

        Session::flash('success', 'Store settings updated successfully.');
        Response::redirect('/admin/settings');
    }
}
