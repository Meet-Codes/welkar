<?php

namespace App\Support;

class Config
{
    private static array $items = [];

    public static function load(string $key, string $filePath): void
    {
        if (file_exists($filePath)) {
            self::$items[$key] = require $filePath;
        }
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $segments = explode('.', $key);
        $file = array_shift($segments);

        if (!isset(self::$items[$file])) {
            $path = __DIR__ . '/../../config/' . $file . '.php';
            if (file_exists($path)) {
                self::$items[$file] = require $path;
            } else {
                return $default;
            }
        }

        $current = self::$items[$file];
        foreach ($segments as $segment) {
            if (!is_array($current) || !array_key_exists($segment, $current)) {
                return $default;
            }
            $current = $current[$segment];
        }

        return $current;
    }

    public static function set(string $key, mixed $value): void
    {
        $segments = explode('.', $key);
        $file = array_shift($segments);

        if (!isset(self::$items[$file])) {
            $path = __DIR__ . '/../../config/' . $file . '.php';
            if (file_exists($path)) {
                self::$items[$file] = require $path;
            } else {
                self::$items[$file] = [];
            }
        }

        $current = &self::$items[$file];
        foreach ($segments as $segment) {
            if (!isset($current[$segment]) || !is_array($current[$segment])) {
                $current[$segment] = [];
            }
            $current = &$current[$segment];
        }

        $current = $value;
    }
}
