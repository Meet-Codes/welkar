<?php

namespace App\Controllers\Storefront;

use App\Support\Request;
use App\Support\Response;
use App\Support\Session;

class LocaleController
{
    public function switch(Request $request, string $locale): void
    {
        if (in_array($locale, ['en', 'hi'])) {
            Session::set('locale', $locale);
        }
        Response::redirect($_SERVER['HTTP_REFERER'] ?? '/');
    }
}
