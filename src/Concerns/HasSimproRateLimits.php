<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Concerns;

use Saloon\RateLimitPlugin\Contracts\RateLimitStore;
use Saloon\RateLimitPlugin\Limit;
use Saloon\RateLimitPlugin\Traits\HasRateLimits;
use Simpro\PhpSdk\Simpro\RateLimit\RateLimitConfig;

/**
 * Provides rate limiting for Simpro API connectors.
 *
 * This trait wraps Saloon's HasRateLimits with Simpro-specific defaults.
 * Rate limits are tracked per base URL hostname.
 */
trait HasSimproRateLimits
{
    use HasRateLimits;

    /**
     * The rate limit configuration.
     */
    private ?RateLimitConfig $rateLimitConfig = null;

    /**
     * Set the rate limit configuration.
     */
    public function setRateLimitConfig(?RateLimitConfig $config): void
    {
        $this->rateLimitConfig = $config;
    }

    /**
     * Get the rate limit configuration.
     */
    public function getRateLimitConfig(): RateLimitConfig
    {
        return $this->rateLimitConfig ?? RateLimitConfig::default();
    }

    /**
     * Resolve the rate limits for the connector.
     *
     * @return array<int, Limit>
     */
    protected function resolveLimits(): array
    {
        $config = $this->getRateLimitConfig();
        $limit = Limit::allow($config->requestsPerSecond)->everySeconds(1);

        if ($config->shouldSleep()) {
            $limit->sleep();
        }

        return [$limit];
    }

    /**
     * Resolve the rate limit store.
     */
    protected function resolveRateLimitStore(): RateLimitStore
    {
        return $this->getRateLimitConfig()->getStore();
    }

    /**
     * Get the limiter prefix to isolate rate limits per Simpro instance.
     *
     * Uses the hostname from the base URL to ensure different Simpro
     * instances have independent rate limit tracking.
     */
    protected function getLimiterPrefix(): ?string
    {
        $baseUrl = $this->resolveBaseUrl();
        $host = parse_url($baseUrl, PHP_URL_HOST);

        return $host ? 'simpro:'.$host : 'simpro';
    }
}
