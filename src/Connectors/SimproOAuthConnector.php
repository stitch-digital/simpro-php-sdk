<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Connectors;

use Saloon\Helpers\OAuth2\OAuthConfig;
use Saloon\Traits\OAuth2\AuthorizationCodeGrant;
use Simpro\PhpSdk\Simpro\RateLimit\RateLimitConfig;

/**
 * Simpro OAuth connector using Authorization Code Grant flow.
 *
 * This connector is suitable for web applications that can redirect users
 * to Simpro's OAuth authorization page and handle the callback.
 *
 * @example
 * ```php
 * $connector = new SimproOAuthConnector(
 *     baseUrl: 'https://example.simprosuite.com',
 *     clientId: 'your-client-id',
 *     clientSecret: 'your-client-secret',
 *     redirectUri: 'https://yourapp.com/oauth/callback'
 * );
 *
 * // Get authorization URL to redirect user
 * $authUrl = $connector->getAuthorizationUrl();
 *
 * // In your callback handler, exchange code for token
 * $authenticator = $connector->getAccessToken($code);
 * $connector->authenticate($authenticator);
 *
 * // Make authenticated requests
 * $clients = $connector->clients()->list();
 * ```
 */
final class SimproOAuthConnector extends AbstractSimproConnector
{
    use AuthorizationCodeGrant;

    /**
     * Constructor
     *
     * @param  string  $baseUrl  The base URL of your Simpro instance (e.g., 'https://example.simprosuite.com')
     * @param  string  $clientId  Your OAuth client ID
     * @param  string  $clientSecret  Your OAuth client secret
     * @param  string  $redirectUri  The callback URL for OAuth (must match your registered OAuth app)
     * @param  array<string>  $scopes  Optional OAuth scopes (default: empty array)
     * @param  int  $requestTimeout  Request timeout in seconds (default: 10)
     * @param  RateLimitConfig|null  $rateLimitConfig  Rate limit configuration (default: 10 req/sec with sleep)
     */
    public function __construct(
        string $baseUrl,
        private readonly string $clientId,
        private readonly string $clientSecret,
        private readonly string $redirectUri,
        private readonly array $scopes = [],
        int $requestTimeout = 10,
        ?RateLimitConfig $rateLimitConfig = null,
    ) {
        parent::__construct($baseUrl, $requestTimeout, $rateLimitConfig);
    }

    /**
     * Configure OAuth2 settings for Simpro.
     */
    protected function defaultOauthConfig(): OAuthConfig
    {
        $baseUrl = $this->resolveBaseUrl();

        return OAuthConfig::make()
            ->setClientId($this->clientId)
            ->setClientSecret($this->clientSecret)
            ->setRedirectUri($this->redirectUri)
            ->setDefaultScopes($this->scopes)
            ->setAuthorizeEndpoint($baseUrl.'/oauth2/login?client_id='.$this->clientId)
            ->setTokenEndpoint($baseUrl.'/oauth2/token')
            ->setUserEndpoint('user');
    }
}
