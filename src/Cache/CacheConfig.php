<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Cache;

use Illuminate\Contracts\Cache\Repository;
use League\Flysystem\Filesystem;
use Psr\SimpleCache\CacheInterface;
use Saloon\CachePlugin\Contracts\Driver;
use Saloon\CachePlugin\Drivers\FlysystemDriver;
use Saloon\CachePlugin\Drivers\LaravelCacheDriver;
use Saloon\CachePlugin\Drivers\PsrCacheDriver;

/**
 * Configuration for response caching behavior.
 *
 * Caching is opt-in and disabled by default. Users must provide a CacheConfig
 * to enable caching on a connector.
 */
final readonly class CacheConfig
{
    /**
     * Default cache expiry: 5 minutes.
     */
    public const int DEFAULT_EXPIRY_SECONDS = 300;

    public function __construct(
        public Driver $driver,
        public int $expiryInSeconds = self::DEFAULT_EXPIRY_SECONDS,
        public ?string $keyPrefix = null,
    ) {}

    /**
     * Create a configuration with a PSR-16 cache implementation.
     */
    public static function psr16(
        CacheInterface $cache,
        int $expiryInSeconds = self::DEFAULT_EXPIRY_SECONDS,
        ?string $keyPrefix = null,
    ): self {
        return new self(
            driver: new PsrCacheDriver($cache),
            expiryInSeconds: $expiryInSeconds,
            keyPrefix: $keyPrefix,
        );
    }

    /**
     * Create a configuration with a Laravel cache store.
     */
    public static function laravel(
        Repository $cache,
        int $expiryInSeconds = self::DEFAULT_EXPIRY_SECONDS,
        ?string $keyPrefix = null,
    ): self {
        return new self(
            driver: new LaravelCacheDriver($cache),
            expiryInSeconds: $expiryInSeconds,
            keyPrefix: $keyPrefix,
        );
    }

    /**
     * Create a configuration with a Flysystem filesystem.
     */
    public static function flysystem(
        Filesystem $filesystem,
        int $expiryInSeconds = self::DEFAULT_EXPIRY_SECONDS,
        ?string $keyPrefix = null,
    ): self {
        return new self(
            driver: new FlysystemDriver($filesystem),
            expiryInSeconds: $expiryInSeconds,
            keyPrefix: $keyPrefix,
        );
    }

    /**
     * Get the cache driver.
     */
    public function getDriver(): Driver
    {
        return $this->driver;
    }

    /**
     * Get the custom key prefix.
     */
    public function getKeyPrefix(): ?string
    {
        return $this->keyPrefix;
    }
}
