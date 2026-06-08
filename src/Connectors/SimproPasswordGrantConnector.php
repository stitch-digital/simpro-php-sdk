<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Connectors;

use Saloon\Helpers\OAuth2\OAuthConfig;
use Saloon\Traits\OAuth2\HasOAuthConfig;
use Simpro\PhpSdk\Simpro\Auth\PasswordGrant;
use Simpro\PhpSdk\Simpro\Cache\CacheConfig;
use Simpro\PhpSdk\Simpro\RateLimit\RateLimitConfig;

/**
 * Simpro OAuth connector using the Resource Owner Password Grant flow.
 *
 * Intended for unattended actors (e.g. digital workers, service accounts)
 * where the application holds the user's credentials directly and exchanges
 * them for an access token without any human-in-the-loop redirect step.
 *
 * @example
 * ```php
 * $connector = new SimproPasswordGrantConnector(
 *     baseUrl: 'https://example.simprosuite.com',
 *     clientId: 'your-client-id',
 *     clientSecret: 'your-client-secret',
 * );
 *
 * // Exchange the resource owner's credentials for an access token
 * $authenticator = $connector->getAccessTokenViaPassword('user@example.com', 'password');
 * $connector->authenticate($authenticator);
 *
 * // Make authenticated requests
 * $clients = $connector->clients()->list();
 * ```
 */
final class SimproPasswordGrantConnector extends AbstractSimproConnector
{
    use HasOAuthConfig;
    use PasswordGrant;

    /**
     * Constructor
     *
     * @param  string  $baseUrl  The base URL of your Simpro instance (e.g., 'https://example.simprosuite.com')
     * @param  string  $clientId  Your OAuth client ID
     * @param  string  $clientSecret  Your OAuth client secret
     * @param  int  $requestTimeout  Request timeout in seconds (default: 10)
     * @param  RateLimitConfig|null  $rateLimitConfig  Rate limit configuration (default: 10 req/sec with sleep)
     * @param  CacheConfig|null  $cacheConfig  Cache configuration (default: disabled)
     */
    public function __construct(
        string $baseUrl,
        private readonly string $clientId,
        private readonly string $clientSecret,
        int $requestTimeout = 10,
        ?RateLimitConfig $rateLimitConfig = null,
        ?CacheConfig $cacheConfig = null,
    ) {
        parent::__construct($baseUrl, $requestTimeout, $rateLimitConfig, $cacheConfig);
    }

    /**
     * Configure OAuth2 settings for Simpro.
     */
    protected function defaultOauthConfig(): OAuthConfig
    {
        return OAuthConfig::make()
            ->setClientId($this->clientId)
            ->setClientSecret($this->clientSecret)
            ->setTokenEndpoint($this->resolveBaseUrl().'/oauth2/token');
    }
}
