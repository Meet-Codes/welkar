<?php

namespace App\Middleware;

use App\Support\Request;
use App\Support\Session;
use App\Support\Response;

class CsrfMiddleware
{
    private array $except = [
        '/webhooks/razorpay',
        '/api/analytics/event'
    ];

    public function handle(Request $request, callable $next): mixed
    {
        $method = $request->method();
        $uri = $request->uri();

        if (in_array($method, ['POST', 'PUT', 'DELETE'])) {
            foreach ($this->except as $exempt) {
                if ($uri === $exempt || str_starts_with($uri, $exempt)) {
                    return $next();
                }
            }

            // 1. Detect if POST request payload exceeded server post_max_size limit
            $contentLength = (int)($_SERVER['CONTENT_LENGTH'] ?? 0);
            if ($method === 'POST' && empty($_POST) && $contentLength > 0) {
                $maxSize = ini_get('post_max_size') ?: '8M';
                if ($request->isAjax()) {
                    Response::json(['error' => "The uploaded file(s) exceed the server upload limit ({$maxSize})."], 413);
                }
                Session::flash('error', "The uploaded photo(s) exceed the server upload limit ({$maxSize}). Please select smaller or compressed images.");
                Response::redirect($_SERVER['HTTP_REFERER'] ?? '/admin/products');
            }

            // 2. Resolve token from POST body, Query string, or HTTP headers
            $token = $request->input('_csrf_token')
                  ?? $request->query('_csrf_token')
                  ?? $request->header('X-CSRF-TOKEN')
                  ?? $request->header('X-XSRF-TOKEN');

            if (!$token || !Session::validateCsrf($token)) {
                if ($request->isAjax()) {
                    Response::json(['error' => 'CSRF token mismatch. Please refresh the page.'], 419);
                }
                Session::flash('error', 'Session expired. Please try again.');
                Response::redirect($_SERVER['HTTP_REFERER'] ?? '/');
            }
        }

        return $next();
    }
}
