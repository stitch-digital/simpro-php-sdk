<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\RateLimit;

/**
 * Defines the behavior when a rate limit is reached.
 */
enum RateLimitBehavior: string
{
    /**
     * Wait and retry automatically when the limit is reached.
     */
    case Sleep = 'sleep';

    /**
     * Throw a RateLimitReachedException when the limit is reached.
     */
    case Throw = 'throw';
}
