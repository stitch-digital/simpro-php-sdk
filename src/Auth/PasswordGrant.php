<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Auth;

use DateTimeImmutable;
use RuntimeException;
use Saloon\Enums\Method;
use Saloon\Helpers\OAuth2\OAuthConfig;
use Saloon\Http\Auth\AccessTokenAuthenticator;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasFormBody;

trait PasswordGrant
{
    /**
     * Exchange a resource owner's username and password for an access token.
     */
    public function getAccessTokenViaPassword(
        string $username,
        string $password,
    ): AccessTokenAuthenticator {
        $config = $this->resolveOauthConfig();

        $response = $this->send($this->createTokenRequest([
            'grant_type' => 'password',
            'client_id' => $config->getClientId(),
            'client_secret' => $config->getClientSecret(),
            'username' => $username,
            'password' => $password,
        ]));

        return $this->createAuthenticatorFromResponse($response);
    }

    /**
     * Exchange a refresh token for a new access token.
     */
    public function refreshAccessToken(
        AccessTokenAuthenticator $authenticator,
    ): AccessTokenAuthenticator {
        $refreshToken = $authenticator->getRefreshToken();

        if ($refreshToken === null || $refreshToken === '') {
            throw new RuntimeException('Cannot refresh access token: no refresh token present.');
        }

        $config = $this->resolveOauthConfig();

        $response = $this->send($this->createTokenRequest([
            'grant_type' => 'refresh_token',
            'client_id' => $config->getClientId(),
            'client_secret' => $config->getClientSecret(),
            'refresh_token' => $refreshToken,
        ]));

        return $this->createAuthenticatorFromResponse($response);
    }

    protected function resolveOauthConfig(): OAuthConfig
    {
        return $this->defaultOauthConfig();
    }

    /**
     * @param  array<string, string>  $body
     */
    private function createTokenRequest(array $body): Request
    {
        $endpoint = $this->resolveOauthConfig()->getTokenEndpoint();

        return new class($endpoint, $body) extends Request
        {
            use HasFormBody;

            protected Method $method = Method::POST;

            /** @param array<string, string> $body */
            public function __construct(
                private readonly string $endpoint,
                private readonly array $body,
            ) {}

            public function resolveEndpoint(): string
            {
                return $this->endpoint;
            }

            protected function defaultBody(): array
            {
                return $this->body;
            }
        };
    }

    private function createAuthenticatorFromResponse(Response $response): AccessTokenAuthenticator
    {
        /** @var array{access_token: string, refresh_token?: string, expires_in?: int} $data */
        $data = $response->json();

        $expiresAt = isset($data['expires_in'])
            ? new DateTimeImmutable('+'.$data['expires_in'].' seconds')
            : null;

        return new AccessTokenAuthenticator(
            accessToken: $data['access_token'],
            refreshToken: $data['refresh_token'] ?? null,
            expiresAt: $expiresAt,
        );
    }

    abstract protected function defaultOauthConfig(): OAuthConfig;
}
