<?php

declare(strict_types=1);

use Saloon\Enums\Method;
use Saloon\Helpers\OAuth2\OAuthConfig;
use Saloon\Http\Auth\AccessTokenAuthenticator;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;
use Simpro\PhpSdk\Simpro\Connectors\SimproOAuthConnector;

// Create a dummy request for testing
final class DummyOAuthRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/test';
    }
}

test('it can be instantiated with OAuth credentials', function () {
    $connector = new SimproOAuthConnector(
        baseUrl: 'https://test.simprosuite.com',
        clientId: 'test-client-id',
        clientSecret: 'test-client-secret',
        redirectUri: 'https://app.test/callback'
    );

    expect($connector->resolveBaseUrl())->toBe('https://test.simprosuite.com');
});

test('it can be instantiated with custom scopes and timeout', function () {
    $connector = new SimproOAuthConnector(
        baseUrl: 'https://test.simprosuite.com',
        clientId: 'test-client-id',
        clientSecret: 'test-client-secret',
        redirectUri: 'https://app.test/callback',
        scopes: ['read', 'write'],
        requestTimeout: 30
    );

    expect($connector->getRequestTimeout())->toBe(30.0);
});

test('it generates authorization URL', function () {
    $connector = new SimproOAuthConnector(
        baseUrl: 'https://test.simprosuite.com',
        clientId: 'test-client-id',
        clientSecret: 'test-client-secret',
        redirectUri: 'https://app.test/callback'
    );

    $authUrl = $connector->getAuthorizationUrl();

    expect($authUrl)
        ->toContain('https://test.simprosuite.com/oauth2/login')
        ->toContain('client_id=test-client-id');
});

test('it configures OAuth endpoints correctly', function () {
    $connector = new SimproOAuthConnector(
        baseUrl: 'https://test.simprosuite.com',
        clientId: 'test-client-id',
        clientSecret: 'test-client-secret',
        redirectUri: 'https://app.test/callback'
    );

    $config = $connector->oauthConfig();

    expect($config)->toBeInstanceOf(OAuthConfig::class)
        ->and($config->getClientId())->toBe('test-client-id')
        ->and($config->getClientSecret())->toBe('test-client-secret')
        ->and($config->getRedirectUri())->toBe('https://app.test/callback')
        ->and($config->getAuthorizeEndpoint())->toContain('https://test.simprosuite.com/oauth2/login')
        ->and($config->getTokenEndpoint())->toBe('https://test.simprosuite.com/oauth2/token');
});

test('it exchanges code for access token', function () {
    $connector = new SimproOAuthConnector(
        baseUrl: 'https://test.simprosuite.com',
        clientId: 'test-client-id',
        clientSecret: 'test-client-secret',
        redirectUri: 'https://app.test/callback'
    );

    $mockClient = new MockClient([
        MockResponse::make([
            'access_token' => 'test-access-token',
            'refresh_token' => 'test-refresh-token',
            'expires_in' => 3600,
            'token_type' => 'Bearer',
        ], 200),
    ]);

    $connector->withMockClient($mockClient);

    $authenticator = $connector->getAccessToken('test-authorization-code');

    expect($authenticator)->toBeInstanceOf(AccessTokenAuthenticator::class)
        ->and($authenticator->getAccessToken())->toBe('test-access-token')
        ->and($authenticator->getRefreshToken())->toBe('test-refresh-token')
        ->and($authenticator->hasNotExpired())->toBeTrue();
});

test('it refreshes access token', function () {
    $connector = new SimproOAuthConnector(
        baseUrl: 'https://test.simprosuite.com',
        clientId: 'test-client-id',
        clientSecret: 'test-client-secret',
        redirectUri: 'https://app.test/callback'
    );

    $mockClient = new MockClient([
        MockResponse::make([
            'access_token' => 'new-access-token',
            'refresh_token' => 'new-refresh-token',
            'expires_in' => 3600,
            'token_type' => 'Bearer',
        ], 200),
    ]);

    $connector->withMockClient($mockClient);

    $oldAuthenticator = new AccessTokenAuthenticator(
        'old-access-token',
        'old-refresh-token',
        new DateTimeImmutable('-1 hour')
    );

    $newAuthenticator = $connector->refreshAccessToken($oldAuthenticator);

    expect($newAuthenticator)->toBeInstanceOf(AccessTokenAuthenticator::class)
        ->and($newAuthenticator->getAccessToken())->toBe('new-access-token')
        ->and($newAuthenticator->getRefreshToken())->toBe('new-refresh-token')
        ->and($newAuthenticator->hasNotExpired())->toBeTrue();
});

test('it can authenticate connector with token', function () {
    $connector = new SimproOAuthConnector(
        baseUrl: 'https://test.simprosuite.com',
        clientId: 'test-client-id',
        clientSecret: 'test-client-secret',
        redirectUri: 'https://app.test/callback'
    );

    $authenticator = new AccessTokenAuthenticator(
        'test-access-token',
        'test-refresh-token',
        new DateTimeImmutable('+1 hour')
    );

    $connector->authenticate($authenticator);

    $mockClient = new MockClient([
        MockResponse::make(['data' => []], 200),
    ]);

    $connector->withMockClient($mockClient);
    $response = $connector->send(new DummyOAuthRequest);

    expect($response->getPendingRequest()->headers()->get('Authorization'))
        ->toBe('Bearer test-access-token');
});

test('it includes default scopes in OAuth config', function () {
    $connector = new SimproOAuthConnector(
        baseUrl: 'https://test.simprosuite.com',
        clientId: 'test-client-id',
        clientSecret: 'test-client-secret',
        redirectUri: 'https://app.test/callback',
        scopes: ['read', 'write']
    );

    $config = $connector->oauthConfig();

    expect($config->getDefaultScopes())->toBe(['read', 'write']);
});
