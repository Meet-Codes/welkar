<?php

namespace App\Services\Marketing;

use App\Support\Config;
use App\Support\Request;
use FacebookAds\Api;
use FacebookAds\Logger\NullLogger;
use FacebookAds\Object\ServerSide\ActionSource;
use FacebookAds\Object\ServerSide\Content;
use FacebookAds\Object\ServerSide\CustomData;
use FacebookAds\Object\ServerSide\DeliveryCategory;
use FacebookAds\Object\ServerSide\Event;
use FacebookAds\Object\ServerSide\EventRequest;
use FacebookAds\Object\ServerSide\UserData;

class MetaConversionsService
{
    private static ?Api $apiInstance = null;

    /**
     * Get setting from store_settings table or fallback to Config
     */
    public static function getSetting(string $key, string $default = ''): string
    {
        try {
            $row = \App\Support\Database::fetch("SELECT setting_value FROM store_settings WHERE setting_key = ? LIMIT 1", [$key]);
            if ($row && !empty($row['setting_value'])) {
                return (string)$row['setting_value'];
            }
        } catch (\Throwable $e) {}
        
        $configKey = match ($key) {
            'meta_pixel_id' => 'pixel_id',
            'meta_conversions_api_token' => 'access_token',
            'meta_test_event_code' => 'test_event_code',
            default => $key,
        };
        return (string)Config::get("app.meta.{$configKey}", $default);
    }

    /**
     * Check if Meta Pixel & CAPI credentials are configured
     */
    public static function isConfigured(): bool
    {
        $pixelId = self::getPixelId();
        $token = self::getSetting('meta_conversions_api_token', (string)Config::get('app.meta.access_token', ''));
        return !empty($pixelId) && !empty($token);
    }

    /**
     * Get configured Pixel ID
     */
    public static function getPixelId(): string
    {
        return self::getSetting('meta_pixel_id', (string)Config::get('app.meta.pixel_id', ''));
    }

    /**
     * Get or initialize Facebook Ads API instance
     */
    public static function getApi(): ?Api
    {
        if (self::$apiInstance !== null) {
            return self::$apiInstance;
        }

        $token = self::getSetting('meta_conversions_api_token', (string)Config::get('app.meta.access_token', ''));
        if (empty($token)) {
            return null;
        }

        try {
            self::$apiInstance = Api::init(null, null, $token);
            self::$apiInstance->setLogger(new NullLogger());
            return self::$apiInstance;
        } catch (\Throwable $e) {
            error_log("Meta Ads API Init Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Build UserData object with SHA-256 normalized PII and browser context
     */
    public static function buildUserData(array $data = [], ?Request $request = null): UserData
    {
        $userData = new UserData();

        // 1. Email (hash sha256 of lowercase trimmed)
        if (!empty($data['email'])) {
            $email = strtolower(trim((string)$data['email']));
            $userData->setEmail(hash('sha256', $email));
        }

        // 2. Phone (hash sha256 of normalized digits)
        if (!empty($data['phone'])) {
            $phone = preg_replace('/[^0-9]/', '', (string)$data['phone']);
            if (strlen($phone) === 10) {
                $phone = '91' . $phone; // default India prefix
            }
            $userData->setPhone(hash('sha256', $phone));
        }

        // 3. Name & Location
        if (!empty($data['first_name'])) {
            $userData->setFirstName(hash('sha256', strtolower(trim((string)$data['first_name']))));
        }
        if (!empty($data['last_name'])) {
            $userData->setLastName(hash('sha256', strtolower(trim((string)$data['last_name']))));
        }
        if (!empty($data['city'])) {
            $userData->setCity(hash('sha256', strtolower(trim((string)$data['city']))));
        }
        if (!empty($data['state'])) {
            $userData->setState(hash('sha256', strtolower(trim((string)$data['state']))));
        }
        if (!empty($data['pincode']) || !empty($data['zip'])) {
            $zip = trim((string)($data['pincode'] ?? $data['zip']));
            $userData->setZip(hash('sha256', $zip));
        }
        $userData->setCountryCode(hash('sha256', 'in'));

        // 4. IP & User Agent
        $ip = $request ? $request->ip() : ($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1');
        $userAgent = $request ? $request->userAgent() : ($_SERVER['HTTP_USER_AGENT'] ?? 'Mozilla/5.0');
        $userData->setClientIpAddress($ip);
        $userData->setClientUserAgent(substr($userAgent, 0, 450));

        // 5. Facebook Click ID (_fbc) and Browser ID (_fbp)
        $fbp = $_COOKIE['_fbp'] ?? null;
        $fbc = $_COOKIE['_fbc'] ?? null;
        if ($fbp) {
            $userData->setFbp($fbp);
        }
        if ($fbc) {
            $userData->setFbc($fbc);
        }

        return $userData;
    }

    /**
     * Send Conversions API Event to Meta Graph API
     */
    public static function sendEvent(
        string $eventName,
        UserData $userData,
        ?CustomData $customData = null,
        ?string $eventSourceUrl = null,
        ?string $eventId = null
    ): array {
        if (!self::isConfigured()) {
            return [
                'status' => 'skipped',
                'message' => 'Meta Pixel ID or Access Token not configured in .env',
                'event_id' => $eventId,
            ];
        }

        $api = self::getApi();
        if (!$api) {
            return [
                'status' => 'error',
                'message' => 'Failed to initialize Meta API instance.',
                'event_id' => $eventId,
            ];
        }

        $pixelId = self::getPixelId();
        $generatedEventId = $eventId ?: ('welkar_evt_' . bin2hex(random_bytes(8)) . '_' . time());

        $event = (new Event())
            ->setEventName($eventName)
            ->setEventTime(time())
            ->setEventId($generatedEventId)
            ->setUserData($userData)
            ->setActionSource(ActionSource::WEBSITE);

        if ($eventSourceUrl) {
            $event->setEventSourceUrl($eventSourceUrl);
        }

        if ($customData) {
            $event->setCustomData($customData);
        }

        try {
            $request = (new EventRequest($pixelId))->setEvents([$event]);

            // If test event code is configured, attach for Facebook Test Events Console
            $testCode = self::getSetting('meta_test_event_code', (string)Config::get('app.meta.test_event_code', ''));
            if (!empty($testCode)) {
                $request->setTestEventCode($testCode);
            }

            $response = $request->execute();

            return [
                'status' => 'success',
                'event_id' => $generatedEventId,
                'events_received' => $response->getEventsReceived(),
                'fbtrace_id' => $response->getFbtraceId(),
            ];
        } catch (\Throwable $e) {
            error_log("Meta CAPI Execution Error [{$eventName}]: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'event_id' => $generatedEventId,
            ];
        }
    }

    /**
     * Track Product View (ViewContent)
     */
    public static function trackViewContent(array $product, ?Request $request = null, ?string $eventId = null): array
    {
        $userData = self::buildUserData([], $request);

        $content = (new Content())
            ->setProductId((string)($product['id'] ?? $product['sku'] ?? ''))
            ->setTitle($product['name'] ?? 'Timepiece')
            ->setItemPrice((float)($product['sale_price'] ?? $product['base_price'] ?? 0))
            ->setQuantity(1);

        $customData = (new CustomData())
            ->setContents([$content])
            ->setContentName($product['name'] ?? 'Timepiece')
            ->setContentCategory($product['dial_shape'] ?? $product['gender'] ?? 'Luxury Watches')
            ->setContentIds([(string)($product['id'] ?? '')])
            ->setContentType('product')
            ->setValue((float)($product['sale_price'] ?? $product['base_price'] ?? 0))
            ->setCurrency('INR');

        $url = $request ? $request->fullUrl() : (Config::get('app.url') . '/product/' . ($product['slug'] ?? ''));

        return self::sendEvent('ViewContent', $userData, $customData, $url, $eventId);
    }

    /**
     * Track Add to Cart (AddToCart)
     */
    public static function trackAddToCart(array $item, ?Request $request = null, ?string $eventId = null): array
    {
        $userData = self::buildUserData([], $request);

        $price = (float)($item['price'] ?? 0);
        $qty = (int)($item['quantity'] ?? 1);

        $content = (new Content())
            ->setProductId((string)($item['product_id'] ?? $item['sku'] ?? ''))
            ->setTitle($item['product_name'] ?? 'Timepiece')
            ->setItemPrice($price)
            ->setQuantity($qty);

        $customData = (new CustomData())
            ->setContents([$content])
            ->setContentName($item['product_name'] ?? 'Timepiece')
            ->setContentIds([(string)($item['product_id'] ?? '')])
            ->setContentType('product')
            ->setValue($price * $qty)
            ->setCurrency('INR');

        $url = $request ? $request->fullUrl() : (Config::get('app.url') . '/cart');

        return self::sendEvent('AddToCart', $userData, $customData, $url, $eventId);
    }

    /**
     * Track Initiate Checkout (InitiateCheckout)
     */
    public static function trackInitiateCheckout(array $cartSummary, array $items = [], ?Request $request = null, ?string $eventId = null): array
    {
        $userData = self::buildUserData([], $request);

        $contents = [];
        $contentIds = [];
        foreach ($items as $it) {
            $contents[] = (new Content())
                ->setProductId((string)($it['product_id'] ?? ''))
                ->setItemPrice((float)($it['price'] ?? 0))
                ->setQuantity((int)($it['quantity'] ?? 1));
            $contentIds[] = (string)($it['product_id'] ?? '');
        }

        $customData = (new CustomData())
            ->setContents($contents)
            ->setContentIds($contentIds)
            ->setContentType('product')
            ->setNumItems((int)($cartSummary['total_items'] ?? count($items)))
            ->setValue((float)($cartSummary['total_amount'] ?? 0))
            ->setCurrency('INR');

        $url = $request ? $request->fullUrl() : (Config::get('app.url') . '/checkout');

        return self::sendEvent('InitiateCheckout', $userData, $customData, $url, $eventId);
    }

    /**
     * Track Purchase (Purchase)
     */
    public static function trackPurchase(array $order, array $items = [], array $customerData = [], ?Request $request = null, ?string $eventId = null): array
    {
        $userData = self::buildUserData($customerData, $request);

        $contents = [];
        $contentIds = [];
        foreach ($items as $it) {
            $contents[] = (new Content())
                ->setProductId((string)($it['product_id'] ?? $it['sku'] ?? ''))
                ->setTitle($it['product_name'] ?? 'Welkar Timepiece')
                ->setItemPrice((float)($it['unit_price'] ?? $it['price'] ?? 0))
                ->setQuantity((int)($it['quantity'] ?? 1))
                ->setDeliveryCategory(DeliveryCategory::HOME_DELIVERY);
            $contentIds[] = (string)($it['product_id'] ?? $it['sku'] ?? '');
        }

        $customData = (new CustomData())
            ->setContents($contents)
            ->setContentIds($contentIds)
            ->setContentType('product')
            ->setOrderId($order['order_number'] ?? (string)($order['id'] ?? ''))
            ->setNumItems(count($items))
            ->setValue((float)($order['total_amount'] ?? 0))
            ->setCurrency('INR');

        $url = $request ? $request->fullUrl() : (Config::get('app.url') . '/order/success/' . ($order['order_number'] ?? ''));

        return self::sendEvent('Purchase', $userData, $customData, $url, $eventId);
    }
}
