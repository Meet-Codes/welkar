<?php

namespace App\Support;

class View
{
    private static array $shared = [];

    public static function share(string $key, mixed $value): void
    {
        self::$shared[$key] = $value;
    }

    public static function render(string $view, array $data = [], ?string $layout = 'layouts/storefront'): string
    {
        $viewFile = __DIR__ . '/../../resources/views/' . str_replace('.', '/', $view) . '.php';
        if (!file_exists($viewFile)) {
            throw new \Exception("View [{$view}] not found at {$viewFile}");
        }

        $allData = array_merge(self::$shared, $data);
        extract($allData);

        ob_start();
        include $viewFile;
        $content = ob_get_clean();

        if ($layout === null) {
            return $content;
        }

        $layoutFile = __DIR__ . '/../../resources/views/' . str_replace('.', '/', $layout) . '.php';
        if (!file_exists($layoutFile)) {
            return $content;
        }

        ob_start();
        include $layoutFile;
        return ob_get_clean();
    }
}
