<?php

namespace App\Support;

class Autoloader
{
    private static string $baseDir;

    public static function register(string $baseDir): void
    {
        self::$baseDir = rtrim($baseDir, '/\\');

        // 1. App namespace autoloader
        spl_autoload_register(function (string $class) {
            $prefix = 'App\\';
            $base_dir = self::$baseDir . '/app/';

            $len = strlen($prefix);
            if (strncmp($prefix, $class, $len) !== 0) {
                return;
            }

            $relative_class = substr($class, $len);
            $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

            if (file_exists($file)) {
                require $file;
            }
        });

        // 2. Razorpay PHP SDK autoloader registration
        $razorpayCandidates = [
            self::$baseDir . '/razorpay-php-2.9.3/razorpay-php-2.9.3/Razorpay.php',
            self::$baseDir . '/razorpay-php-2.9.3/Razorpay.php',
            self::$baseDir . '/packages/razorpay/Razorpay.php',
        ];
        foreach ($razorpayCandidates as $razorpayFile) {
            if (file_exists($razorpayFile)) {
                require_once $razorpayFile;
                break;
            }
        }

        // 3. Meta / Facebook Business PHP SDK PSR-4 autoloader
        spl_autoload_register(function (string $class) {
            $prefix = 'FacebookAds\\';
            $len = strlen($prefix);
            if (strncmp($prefix, $class, $len) !== 0) {
                return;
            }

            $fbCandidates = [
                self::$baseDir . '/facebook-php-business-sdk-main/facebook-php-business-sdk-main/src/FacebookAds/',
                self::$baseDir . '/facebook-php-business-sdk-main/src/FacebookAds/',
                self::$baseDir . '/packages/facebook-sdk/src/FacebookAds/',
            ];

            $relative_class = substr($class, $len);
            $relPath = str_replace('\\', '/', $relative_class) . '.php';

            foreach ($fbCandidates as $fbDir) {
                if (file_exists($fbDir . $relPath)) {
                    require $fbDir . $relPath;
                    return;
                }
            }
        });
    }
}
