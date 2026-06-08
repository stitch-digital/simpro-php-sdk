<?php

declare(strict_types=1);

use Saloon\Helpers\OAuth2\OAuthConfig;
use Saloon\Http\Auth\AccessTokenAuthenticator;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Connectors\SimproPasswordGrantConnector;

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
