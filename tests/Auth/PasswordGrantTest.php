<?php

declare(strict_types=1);

use Saloon\Http\Auth\AccessTokenAuthenticator;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Connectors\SimproPasswordGrantConnector;

test('it exchanges username and password for an access token', function () {
    $connector = new SimproPasswordGrantConnector(
        baseUrl: 'https://test.simprosuite.com',
        clientId: 'test-client-id',
        clientSecret: 'test-client-secret',
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

    $authenticator = $connector->getAccessTokenViaPassword(
        'meredith.jones@ssecltd.co.uk',
        's3cret',
    );

    expect($authenticator)->toBeInstanceOf(AccessTokenAuthenticator::class)
        ->and($authenticator->getAccessToken())->toBe('test-access-token')
        ->and($authenticator->getRefreshToken())->toBe('test-refresh-token')
        ->and($authenticator->hasNotExpired())->toBeTrue();

    $sentRequest = $mockClient->getLastPendingRequest();
    $body = $sentRequest->body()->all();

    expect($body)->toMatchArray([
        'grant_type' => 'password',
        'client_id' => 'test-client-id',
        'client_secret' => 'test-client-secret',
        'username' => 'meredith.jones@ssecltd.co.uk',
        'password' => 's3cret',
    ]);
});

test('it refreshes an access token using the refresh grant', function () {
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

    $old = new AccessTokenAuthenticator(
        'old-access-token',
        'old-refresh-token',
        new DateTimeImmutable('-1 hour'),
    );

    $new = $connector->refreshAccessToken($old);

    expect($new->getAccessToken())->toBe('new-access-token')
        ->and($new->getRefreshToken())->toBe('new-refresh-token')
        ->and($new->hasNotExpired())->toBeTrue();

    $body = $mockClient->getLastPendingRequest()->body()->all();

    expect($body)->toMatchArray([
        'grant_type' => 'refresh_token',
        'client_id' => 'test-client-id',
        'client_secret' => 'test-client-secret',
        'refresh_token' => 'old-refresh-token',
    ]);
});
