<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Connectors;

use Saloon\Contracts\Authenticator;
use Saloon\Http\Auth\TokenAuthenticator;
use Simpro\PhpSdk\Simpro\RateLimit\RateLimitConfig;

/**
 * Simpro API Key connector using token-based authentication.
 *
 * This connector is suitable for server-to-server integrations where
 * you have a static API key for authentication.
 *
 * @example
 * ```php
 * $connector = new SimproApiKeyConnector(
 *     baseUrl: 'https://example.simprosuite.com/api/v1.0',
 *     apiKey: 'your-api-key'
 * );
 *
 * // Make authenticated requests
 * $clients = $connector->clients()->list();
 * ```
 */
final class SimproApiKeyConnector extends AbstractSimproConnector
{
    /**
     * Constructor
     *
     * @param  string  $baseUrl  The base URL of your Simpro API (e.g., 'https://example.simprosuite.com/api/v1.0')
     * @param  string  $apiKey  Your API key for authentication
     * @param  int  $requestTimeout  Request timeout in seconds (default: 10)
     * @param  RateLimitConfig|null  $rateLimitConfig  Rate limit configuration (default: 10 req/sec with sleep)
     */
    public function __construct(
        string $baseUrl,
        private readonly string $apiKey,
        int $requestTimeout = 10,
        ?RateLimitConfig $rateLimitConfig = null,
    ) {
        parent::__construct($baseUrl, $requestTimeout, $rateLimitConfig);
    }

    /**
     * Authenticate requests using the API key.
     */
    protected function defaultAuth(): Authenticator
    {
        return new TokenAuthenticator($this->apiKey);
    }
}
