<?php

use App\Support\Config;
use App\Support\Env;
use App\Support\Session;

if (!function_exists('e')) {
    function e(?string $value): string {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8', false);
    }
}

if (!function_exists('format_currency')) {
    function format_currency(float|int|string|null $amount): string {
        $num = (float)($amount ?? 0);
        return '₹' . number_format($num, 2);
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string {
        return Session::csrfToken();
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string {
        return '<input type="hidden" name="_csrf_token" value="' . e(csrf_token()) . '">';
    }
}

if (!function_exists('config')) {
    function config(string $key, mixed $default = null): mixed {
        return Config::get($key, $default);
    }
}

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed {
        return Env::get($key, $default);
    }
}

if (!function_exists('url')) {
    function url(string $path = ''): string {
        $baseUrl = rtrim(Config::get('app.url', 'http://localhost:8000'), '/');
        $cleanPath = '/' . ltrim($path, '/');
        return $cleanPath === '/' ? $baseUrl : $baseUrl . $cleanPath;
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string {
        return url('assets/' . ltrim($path, '/'));
    }
}

if (!function_exists('__')) {
    function __(string $key, array $replace = []): string {
        static $translations = [];
        $locale = Session::get('locale', Config::get('app.locale', 'en'));

        if (!isset($translations[$locale])) {
            $langFile = __DIR__ . '/../../resources/languages/' . $locale . '.php';
            if (file_exists($langFile)) {
                $translations[$locale] = require $langFile;
            } else {
                $fallbackFile = __DIR__ . '/../../resources/languages/en.php';
                $translations[$locale] = file_exists($fallbackFile) ? require $fallbackFile : [];
            }
        }

        $text = $translations[$locale][$key] ?? $key;
        foreach ($replace as $k => $v) {
            $text = str_replace(':' . $k, (string)$v, $text);
        }
        return $text;
    }
}

if (!function_exists('auth_user')) {
    function auth_user(): ?array {
        return Session::get('customer_user');
    }
}

if (!function_exists('admin_user')) {
    function admin_user(): ?array {
        return Session::get('admin_user');
    }
}

if (!function_exists('old')) {
    function old(string $key, mixed $default = ''): mixed {
        $old = Session::get('_old_input', []);
        return $old[$key] ?? $default;
    }
}

if (!function_exists('flash')) {
    function flash(string $key, mixed $value = null): mixed {
        if ($value !== null) {
            return Session::flash($key, $value);
        }
        return Session::flash($key);
    }
}
