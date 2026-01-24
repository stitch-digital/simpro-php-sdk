<?php

declare(strict_types=1);

use Saloon\Exceptions\Request\ClientException;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Requests\Auth\GetAccessTokenRequest;

it('retrieves an access token successfully', function () {
    MockClient::global([
        GetAccessTokenRequest::class => MockResponse::fixture('get_access_token'),
    ]);

    $request = new GetAccessTokenRequest(
        'test_user',
        'test_password',
        'test_client_id',
        'test_client_secret'
    );

    $response = $this->sdk->send($request);
    $data = $response->json();

    expect($data['access_token'])->toBe('test_access_token_12345')
        ->and($data['refresh_token'])->toBe('test_refresh_token_67890')
        ->and($data['expires_in'])->toBe(3600);
});

it('creates authenticator with correct properties', function () {
    MockClient::global([
        GetAccessTokenRequest::class => MockResponse::fixture('get_access_token'),
    ]);

    $authenticator = $this->sdk->getAccessToken();

    expect($authenticator->accessToken)->toBe('test_access_token_12345')
        ->and($authenticator->refreshToken)->toBe('test_refresh_token_67890')
        ->and($authenticator->expiresAt)->not->toBeNull()
        ->and($authenticator->hasNotExpired())->toBeTrue();
});

it('handles authentication errors', function () {
    MockClient::destroyGlobal();
    MockClient::global([
        GetAccessTokenRequest::class => MockResponse::make(
            body: ['error' => 'invalid_client'],
            status: 401
        ),
    ]);

    expect(fn () => $this->sdk->getAccessToken())
        ->toThrow(ClientException::class);
});
