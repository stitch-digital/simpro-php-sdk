<?php

declare(strict_types=1);

use Saloon\Enums\Method;
use Saloon\Helpers\OAuth2\OAuthConfig;
use Saloon\Http\Auth\AccessTokenAuthenticator;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;
use Simpro\PhpSdk\Simpro\Connectors\SimproPasswordGrantConnector;

// Create a dummy request for testing
final class DummyPasswordGrantRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/test';
    }
}

test('it can be instantiated with client credentials', function () {
    $connector = new SimproPasswordGrantConnector(
        baseUrl: 'https://test.simprosuite.com',
        clientId: 'test-client-id',
        clientSecret: 'test-client-secret',
    );

    expect($connector->resolveBaseUrl())->toBe('https://test.simprosuite.com');
});

test('it configures OAuth endpoints correctly', function () {
    $connector = new SimproPasswordGrantConnector(
        baseUrl: 'https://test.simprosuite.com',
        clientId: 'test-client-id',
        clientSecret: 'test-client-secret',
    );

    $config = $connector->oauthConfig();

    expect($config)->toBeInstanceOf(OAuthConfig::class)
        ->and($config->getClientId())->toBe('test-client-id')
        ->and($config->getClientSecret())->toBe('test-client-secret')
        ->and($config->getTokenEndpoint())->toBe('https://test.simprosuite.com/oauth2/token');
});

test('it exchanges username and password for an access token end-to-end', function () {
    $connector = new SimproPasswordGrantConnector(
        baseUrl: 'https://test.simprosuite.com',
        clientId: 'cid',
        clientSecret: 'csec',
    );

    $mockClient = new MockClient([
        MockResponse::make([
            'access_token' => 'abc',
            'refresh_token' => 'def',
            'expires_in' => 3600,
            'token_type' => 'Bearer',
        ], 200),
    ]);

    $connector->withMockClient($mockClient);

    $authenticator = $connector->getAccessTokenViaPassword('user@example.com', 'pw');

    expect($authenticator)->toBeInstanceOf(AccessTokenAuthenticator::class)
        ->and($authenticator->getAccessToken())->toBe('abc');
});

test('it refreshes an access token', function () {
    $connector = new SimproPasswordGrantConnector(
        baseUrl: 'https://test.simprosuite.com',
        clientId: 'test-client-id',
        clientSecret: 'test-client-secret',
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
    $connector = new SimproPasswordGrantConnector(
        baseUrl: 'https://test.simprosuite.com',
        clientId: 'test-client-id',
        clientSecret: 'test-client-secret',
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
    $response = $connector->send(new DummyPasswordGrantRequest);

    expect($response->getPendingRequest()->headers()->get('Authorization'))
        ->toBe('Bearer test-access-token');
});
