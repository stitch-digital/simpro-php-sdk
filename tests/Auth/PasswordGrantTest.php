<?php

declare(strict_types=1);

use Saloon\Exceptions\Request\RequestException;
use Saloon\Helpers\OAuth2\OAuthConfig;
use Saloon\Http\Auth\AccessTokenAuthenticator;
use Saloon\Http\Connector;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Traits\OAuth2\HasOAuthConfig;
use Simpro\PhpSdk\Simpro\Auth\PasswordGrant;

final class PasswordGrantTestConnector extends Connector
{
    use HasOAuthConfig;
    use PasswordGrant;

    public function __construct(
        private readonly string $baseUrl,
        private readonly string $clientId,
        private readonly string $clientSecret,
    ) {}

    public function resolveBaseUrl(): string
    {
        return $this->baseUrl;
    }

    protected function defaultOauthConfig(): OAuthConfig
    {
        return OAuthConfig::make()
            ->setClientId($this->clientId)
            ->setClientSecret($this->clientSecret)
            ->setTokenEndpoint($this->baseUrl.'/oauth2/token')
            ->setAllowBaseUrlOverride();
    }
}

test('it exchanges username and password for an access token', function () {
    $connector = new PasswordGrantTestConnector(
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
        'user@example.com',
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
        'username' => 'user@example.com',
        'password' => 's3cret',
    ]);
});

test('it refreshes an access token using the refresh grant', function () {
    $connector = new PasswordGrantTestConnector(
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

test('it throws InvalidArgumentException when refreshing without a refresh token', function () {
    $connector = new PasswordGrantTestConnector(
        baseUrl: 'https://test.simprosuite.com',
        clientId: 'test-client-id',
        clientSecret: 'test-client-secret',
    );

    $authenticator = new AccessTokenAuthenticator(
        'old-access-token',
        null,
        new DateTimeImmutable('-1 hour'),
    );

    expect(fn () => $connector->refreshAccessToken($authenticator))
        ->toThrow(InvalidArgumentException::class);
});

test('it defaults expiresAt to one hour from now when expires_in is omitted', function () {
    $connector = new PasswordGrantTestConnector(
        baseUrl: 'https://test.simprosuite.com',
        clientId: 'test-client-id',
        clientSecret: 'test-client-secret',
    );

    $mockClient = new MockClient([
        MockResponse::make([
            'access_token' => 'test-access-token',
            'refresh_token' => 'test-refresh-token',
            'token_type' => 'Bearer',
        ], 200),
    ]);

    $connector->withMockClient($mockClient);

    $before = new DateTimeImmutable;

    $authenticator = $connector->getAccessTokenViaPassword(
        'user@example.com',
        's3cret',
    );

    $expiresAt = $authenticator->getExpiresAt();

    expect($expiresAt)->not->toBeNull()
        ->and($authenticator->hasNotExpired())->toBeTrue();

    $expected = $before->getTimestamp() + 3600;
    $actual = $expiresAt->getTimestamp();

    expect(abs($actual - $expected))->toBeLessThanOrEqual(5);
});

test('it throws when the token endpoint returns 4xx', function () {
    $connector = new PasswordGrantTestConnector(
        baseUrl: 'https://test.simprosuite.com',
        clientId: 'test-client-id',
        clientSecret: 'test-client-secret',
    );

    $mockClient = new MockClient([
        MockResponse::make([
            'error' => 'invalid_grant',
        ], 400),
    ]);

    $connector->withMockClient($mockClient);

    expect(fn () => $connector->getAccessTokenViaPassword(
        'user@example.com',
        'wrong-password',
    ))->toThrow(RequestException::class);
});
