<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Auth;

use DateTimeImmutable;
use InvalidArgumentException;
use Saloon\Http\Auth\AccessTokenAuthenticator;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Auth\Requests\GetPasswordAccessTokenRequest;
use Simpro\PhpSdk\Simpro\Auth\Requests\GetPasswordRefreshTokenRequest;

/**
 * Adds OAuth 2.0 Resource Owner Password Credentials grant support to a Saloon
 * connector (RFC 6749 §4.3).
 *
 * Consuming connectors MUST also `use Saloon\Traits\OAuth2\HasOAuthConfig;`
 * which provides the cached `oauthConfig()` accessor and the abstract
 * `defaultOauthConfig()` contract this trait relies on. We deliberately do not
 * `use HasOAuthConfig;` from within this trait because Saloon's grant traits
 * follow the same convention of leaving the host class responsible for
 * composing the OAuth config trait.
 *
 * @mixin \Saloon\Traits\OAuth2\HasOAuthConfig
 * @mixin \Saloon\Http\Connector
 *
 * @phpstan-ignore trait.unused
 */
trait PasswordGrant
{
    /**
     * Exchange a resource owner's username and password for an access token.
     */
    public function getAccessTokenViaPassword(
        string $username,
        string $password,
    ): AccessTokenAuthenticator {
        $response = $this->send(new GetPasswordAccessTokenRequest(
            $this->oauthConfig(),
            $username,
            $password,
        ));

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
            throw new InvalidArgumentException('Cannot refresh access token: no refresh token present.');
        }

        $response = $this->send(new GetPasswordRefreshTokenRequest(
            $this->oauthConfig(),
            $refreshToken,
        ));

        return $this->createAuthenticatorFromResponse($response);
    }

    private function createAuthenticatorFromResponse(Response $response): AccessTokenAuthenticator
    {
        $response->throw();

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
}
