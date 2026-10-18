<?php

namespace App\Support;

class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            $sessionsDir = dirname(__DIR__, 2) . '/storage/sessions';
            if (!is_dir($sessionsDir)) {
                @mkdir($sessionsDir, 0777, true);
            }
            if (!headers_sent()) {
                if (is_dir($sessionsDir) && is_writable($sessionsDir)) {
                    @ini_set('session.save_path', $sessionsDir);
                }
                $lifetime = (int)Env::get('SESSION_LIFETIME', 86400); // 24 hours
                @ini_set('session.gc_maxlifetime', (string)$lifetime);

                if (php_sapi_name() !== 'cli') {
                    session_set_cookie_params([
                        'lifetime' => $lifetime,
                        'path' => '/',
                        'domain' => '',
                        'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
                        'httponly' => true,
                        'samesite' => 'Lax'
                    ]);
                }
            }
            if (session_status() !== PHP_SESSION_ACTIVE && !headers_sent()) {
                @session_start();
            }
        }

        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    public static function getId(): string
    {
        self::start();
        $id = session_id();
        if (empty($id)) {
            $id = self::get('session_unique_id');
            if (!$id) {
                $id = bin2hex(random_bytes(16));
                self::set('session_unique_id', $id);
            }
        }
        return $id;
    }

    public static function id(): string
    {
        return self::getId();
    }

    public static function csrfToken(): string
    {
        self::start();
        return $_SESSION['_csrf_token'];
    }

    public static function validateCsrf(?string $token): bool
    {
        self::start();
        if (empty($token)) {
            return false;
        }

        $validTokens = [];
        if (!empty($_SESSION['_csrf_token'])) {
            $validTokens[] = $_SESSION['_csrf_token'];
        }
        if (!empty($_SESSION['_prev_csrf_token'])) {
            $validTokens[] = $_SESSION['_prev_csrf_token'];
        }
        if (!empty($_SESSION['_csrf_tokens']) && is_array($_SESSION['_csrf_tokens'])) {
            $validTokens = array_merge($validTokens, $_SESSION['_csrf_tokens']);
        }

        foreach ($validTokens as $valid) {
            if (hash_equals($valid, (string)$token)) {
                return true;
            }
        }

        return false;
    }

    public static function regenerate(): void
    {
        self::start();
        $oldToken = $_SESSION['_csrf_token'] ?? null;
        if (session_status() === PHP_SESSION_ACTIVE) {
            @session_regenerate_id(true);
        }
        $newToken = bin2hex(random_bytes(32));
        $_SESSION['_csrf_token'] = $newToken;
        if ($oldToken) {
            $_SESSION['_prev_csrf_token'] = $oldToken;
            if (!isset($_SESSION['_csrf_tokens']) || !is_array($_SESSION['_csrf_tokens'])) {
                $_SESSION['_csrf_tokens'] = [];
            }
            $_SESSION['_csrf_tokens'][] = $oldToken;
            if (count($_SESSION['_csrf_tokens']) > 10) {
                array_shift($_SESSION['_csrf_tokens']);
            }
        }
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    public static function set(string $key, mixed $value): void
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function remove(string $key): void
    {
        self::start();
        unset($_SESSION[$key]);
    }

    public static function destroy(): void
    {
        self::start();
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }

    public static function flash(string $key, mixed $value = null): mixed
    {
        self::start();
        if ($value !== null) {
            $_SESSION['_flash'][$key] = $value;
            return null;
        }

        $val = $_SESSION['_flash'][$key] ?? null;
        unset($_SESSION['_flash'][$key]);
        return $val;
    }

    public static function hasFlash(string $key): bool
    {
        self::start();
        return isset($_SESSION['_flash'][$key]);
    }
}
