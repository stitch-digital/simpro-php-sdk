<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Concerns;

use Saloon\CachePlugin\Contracts\Cacheable;
use Saloon\CachePlugin\Contracts\Driver;
use Saloon\CachePlugin\Http\Middleware\CacheMiddleware;
use Saloon\CachePlugin\Traits\HasCaching;
use Saloon\Enums\PipeOrder;
use Saloon\Http\PendingRequest;
use Simpro\PhpSdk\Simpro\Cache\CacheConfig;

/**
 * Provides response caching for Simpro API connectors.
 *
 * This trait wraps Saloon's HasCaching with Simpro-specific defaults.
 * Cache keys are prefixed with the hostname for multi-tenant isolation.
 *
 * Caching is opt-in and disabled by default. Users must provide a CacheConfig
 * to enable caching.
 */
trait HasSimproCaching
{
    use HasCaching {
        bootHasCaching as private saloonBootHasCaching;
    }

    /**
     * The cache configuration.
     */
    private ?CacheConfig $cacheConfig = null;

    /**
     * Set the cache configuration.
     */
    public function setCacheConfig(?CacheConfig $config): void
    {
        $this->cacheConfig = $config;
    }

    /**
     * Get the cache configuration.
     */
    public function getCacheConfig(): ?CacheConfig
    {
        return $this->cacheConfig;
    }

    /**
     * Check if caching is enabled.
     */
    public function hasCaching(): bool
    {
        return $this->cacheConfig !== null;
    }

    /**
     * Boot the caching plugin.
     *
     * This overrides Saloon's bootHasCaching to skip caching when not configured.
     */
    public function bootHasCaching(PendingRequest $pendingRequest): void
    {
        // If caching is not configured, skip the caching middleware entirely
        if (! $this->hasCaching()) {
            return;
        }

        // If caching has been explicitly disabled on the request, skip
        if (! $this->cachingEnabled) {
            return;
        }

        // If the request method is not cacheable, skip
        if (! in_array($pendingRequest->getMethod(), $this->getCacheableMethods(), true)) {
            return;
        }

        $request = $pendingRequest->getRequest();

        // Use request-level caching config if the request implements Cacheable,
        // otherwise use the connector-level config (this trait is on the connector)
        $cacheDriver = $request instanceof Cacheable
            ? $request->resolveCacheDriver()
            : $this->resolveCacheDriver();

        $cacheExpiryInSeconds = $request instanceof Cacheable
            ? $request->cacheExpiryInSeconds()
            : $this->cacheExpiryInSeconds();

        $pendingRequest->middleware()->onRequest(function (PendingRequest $middlewarePendingRequest) use ($cacheDriver, $cacheExpiryInSeconds) {
            return call_user_func(
                new CacheMiddleware($cacheDriver, $cacheExpiryInSeconds, $this->cacheKey($middlewarePendingRequest), $this->invalidateCache),
                $middlewarePendingRequest
            );
        }, order: PipeOrder::FIRST);
    }

    /**
     * Resolve the cache driver.
     *
     * Required by the Cacheable interface.
     *
     * @throws \RuntimeException If caching is not configured.
     */
    public function resolveCacheDriver(): Driver
    {
        if ($this->cacheConfig === null) {
            throw new \RuntimeException('Caching is not configured. Provide a CacheConfig to enable caching.');
        }

        return $this->cacheConfig->getDriver();
    }

    /**
     * Get the cache expiry in seconds.
     *
     * Required by the Cacheable interface.
     */
    public function cacheExpiryInSeconds(): int
    {
        if ($this->cacheConfig === null) {
            return CacheConfig::DEFAULT_EXPIRY_SECONDS;
        }

        return $this->cacheConfig->expiryInSeconds;
    }

    /**
     * Generate the cache key with hostname-based prefix for multi-tenant isolation.
     *
     * Cache key format: simpro:{hostname}[:userPrefix]:{hash}
     */
    protected function cacheKey(PendingRequest $pendingRequest): ?string
    {
        if (! $this->hasCaching()) {
            return null;
        }

        $baseUrl = $this->resolveBaseUrl();
        $host = parse_url($baseUrl, PHP_URL_HOST);
        $prefix = $host ? 'simpro:'.$host : 'simpro';

        // Add user-configured prefix if provided
        if ($this->cacheConfig?->getKeyPrefix() !== null) {
            $prefix .= ':'.$this->cacheConfig->getKeyPrefix();
        }

        // Generate a hash from the request URL, headers, and query parameters
        $hash = md5(
            $pendingRequest->getUrl().
            json_encode($pendingRequest->headers()->all()).
            json_encode($pendingRequest->query()->all())
        );

        return $prefix.':'.$hash;
    }
}
