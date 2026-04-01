<?php
declare(strict_types=1);

namespace Sangho;

/**
 * Static facade — Sangho::setApiKey() + Resource::method() style.
 *
 * Usage:
 *   Sangho::setApiKey('sk_test_xxx');
 *   $customers = \Sangho\Resource\Customers::all(['status' => 'active']);
 */
class Sangho
{
    private static ?string $apiKey  = null;
    private static string  $baseUrl = 'https://api.sangho.com/v1';
    private static int     $timeout = 30;

    private static ?SanghoClient $instance = null;

    public static function setApiKey(string $key): void
    {
        self::$apiKey   = $key;
        self::$instance = null; // reset on key change
    }

    public static function setBaseUrl(string $url): void
    {
        self::$baseUrl  = $url;
        self::$instance = null;
    }

    public static function setTimeout(int $seconds): void
    {
        self::$timeout  = $seconds;
        self::$instance = null;
    }

    public static function client(): SanghoClient
    {
        if (self::$apiKey === null) {
            throw new \RuntimeException(
                'Sangho API key not set. Call Sangho::setApiKey("sk_…") first.'
            );
        }
        return self::$instance ??= new SanghoClient(self::$apiKey, self::$baseUrl, self::$timeout);
    }
}
