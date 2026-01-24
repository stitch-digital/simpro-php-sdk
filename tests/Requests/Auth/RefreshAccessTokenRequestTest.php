<?php

declare(strict_types=1);

use Saloon\Exceptions\Request\ClientException;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Requests\Auth\RefreshAccessTokenRequest;

it('refreshes an access token successfully', function () {
    MockClient::global([
        RefreshAccessTokenRequest::class => MockResponse::fixture('refresh_access_token'),
    ]);

    $request = new RefreshAccessTokenRequest(
        'test_refresh_token',
        'test_client_id',
        'test_client_secret'
    );

    $response = $this->sdk->send($request);
    $data = $response->json();

    expect($data['access_token'])->toBe('new_access_token_12345')
        ->and($data['refresh_token'])->toBe('new_refresh_token_67890')
        ->and($data['expires_in'])->toBe(3600);
});

it('creates authenticator with refreshed token', function () {
    MockClient::global([
        RefreshAccessTokenRequest::class => MockResponse::fixture('refresh_access_token'),
    ]);

    $authenticator = $this->sdk->refreshAccessToken('test_refresh_token');

    expect($authenticator->accessToken)->toBe('new_access_token_12345')
        ->and($authenticator->refreshToken)->toBe('new_refresh_token_67890')
        ->and($authenticator->expiresAt)->not->toBeNull();
});

it('handles refresh token errors', function () {
    MockClient::destroyGlobal();
    MockClient::global([
        RefreshAccessTokenRequest::class => MockResponse::make(
            body: ['error' => 'invalid_grant'],
            status: 400
        ),
    ]);

    expect(fn () => $this->sdk->refreshAccessToken('invalid_token'))
        ->toThrow(ClientException::class);
});
