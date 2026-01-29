<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\RateLimit;

use Saloon\RateLimitPlugin\Contracts\RateLimitStore;
use Saloon\RateLimitPlugin\Stores\MemoryStore;

/**
 * Configuration for rate limiting behavior.
 *
 * Simpro's API limit is 10 requests per second per base URL.
 */
final readonly class RateLimitConfig
{
    /**
     * Simpro's default rate limit: 10 requests per second.
     */
    public const int DEFAULT_REQUESTS_PER_SECOND = 10;

    public function __construct(
        public ?RateLimitStore $store = null,
        public RateLimitBehavior $behavior = RateLimitBehavior::Sleep,
        public int $requestsPerSecond = self::DEFAULT_REQUESTS_PER_SECOND,
    ) {}

    /**
     * Create a default configuration with sleep behavior and memory store.
     */
    public static function default(): self
    {
        return new self;
    }

    /**
     * Create a configuration with a custom store.
     */
    public static function withStore(RateLimitStore $store): self
    {
        return new self(store: $store);
    }

    /**
     * Create a configuration that throws exceptions instead of sleeping.
     *
     * Useful for queue jobs where you want to release the job back onto the queue.
     */
    public static function throwing(): self
    {
        return new self(behavior: RateLimitBehavior::Throw);
    }

    /**
     * Get the rate limit store, defaulting to MemoryStore.
     */
    public function getStore(): RateLimitStore
    {
        return $this->store ?? new MemoryStore;
    }

    /**
     * Check if the behavior is to sleep when rate limited.
     */
    public function shouldSleep(): bool
    {
        return $this->behavior === RateLimitBehavior::Sleep;
    }
}
