<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro;

use DateTimeImmutable;
use Exception;
use InvalidArgumentException;
use Saloon\Contracts\Authenticator;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\HasPagination;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Saloon\Traits\Plugins\HasTimeout;
use Simpro\PhpSdk\Simpro\Concerns\SupportsClientsEndpoints;
use Simpro\PhpSdk\Simpro\Exceptions\ValidationException;
use Simpro\PhpSdk\Simpro\Paginators\SimproPaginator;
use Simpro\PhpSdk\Simpro\Requests\Auth\GetAccessTokenRequest;
use Simpro\PhpSdk\Simpro\Requests\Auth\RefreshAccessTokenRequest;
use Throwable;

final class Simpro extends \Saloon\Http\Connector implements HasPagination
{
    use AcceptsJson;
    use AlwaysThrowOnErrors;
    use HasTimeout;
    use SupportsClientsEndpoints;

    /**
     * Cached authenticator instance.
     */
    private static ?SimproAuthenticator $cachedAuthenticator = null;

    /**
     * Request timeout in seconds.
     */
    private int $requestTimeout;

    /**
     * Constructor
     */
    public function __construct(
        private string $baseUrl,
        private string $username,
        private string $password,
        private string $clientId,
        private string $clientSecret,
        int $requestTimeout = 10
    ) {
        $this->requestTimeout = $requestTimeout;
    }

    /**
     * Create a new Simpro instance.
     *
     * @param  mixed  ...$arguments  Arguments: baseUrl (string), username (string), password (string), clientId (string), clientSecret (string), requestTimeout (int, optional, default: 10)
     */
    public static function make(mixed ...$arguments): static
    {
        return new self(
            $arguments[0] ?? throw new InvalidArgumentException('baseUrl is required'),
            $arguments[1] ?? throw new InvalidArgumentException('username is required'),
            $arguments[2] ?? throw new InvalidArgumentException('password is required'),
            $arguments[3] ?? throw new InvalidArgumentException('clientId is required'),
            $arguments[4] ?? throw new InvalidArgumentException('clientSecret is required'),
            $arguments[5] ?? 10
        );
    }

    /**
     * Get the request timeout.
     */
    public function getRequestTimeout(): float
    {
        return (float) $this->requestTimeout;
    }

    /**
     * The Base URL of the API.
     */
    public function resolveBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * Get an access token using the password grant.
     */
    public function getAccessToken(): SimproAuthenticator
    {
        $request = new GetAccessTokenRequest(
            $this->username,
            $this->password,
            $this->clientId,
            $this->clientSecret
        );

        $response = $this->send($request);

        $data = $response->json();

        $accessToken = $data['access_token'] ?? '';
        $refreshToken = $data['refresh_token'] ?? null;
        $expiresIn = $data['expires_in'] ?? null;

        $expiresAt = null;
        if ($expiresIn !== null) {
            $expiresAt = (new DateTimeImmutable)->modify(sprintf('+%s seconds', $expiresIn));
        }

        return new SimproAuthenticator($accessToken, $refreshToken, $expiresAt);
    }

    /**
     * Refresh an access token using a refresh token.
     */
    public function refreshAccessToken(string $refreshToken): SimproAuthenticator
    {
        $request = new RefreshAccessTokenRequest(
            $refreshToken,
            $this->clientId,
            $this->clientSecret
        );

        $response = $this->send($request);

        $data = $response->json();

        $accessToken = $data['access_token'] ?? '';
        $newRefreshToken = $data['refresh_token'] ?? $refreshToken; // Use existing if not provided
        $expiresIn = $data['expires_in'] ?? null;

        $expiresAt = null;
        if ($expiresIn !== null) {
            $expiresAt = (new DateTimeImmutable)->modify(sprintf('+%s seconds', $expiresIn));
        }

        return new SimproAuthenticator($accessToken, $newRefreshToken, $expiresAt);
    }

    /**
     * Paginate a request.
     */
    public function paginate(Request $request): SimproPaginator
    {
        return new SimproPaginator($this, $request);
    }

    /**
     * Get request exception for error handling.
     */
    public function getRequestException(Response $response, ?Throwable $senderException): ?Throwable
    {
        if ($response->status() === 422) {
            return new ValidationException($response);
        }

        // Let Saloon handle other exceptions
        return null;
    }

    /**
     * Default authenticator with automatic token management.
     */
    protected function defaultAuth(): Authenticator
    {
        if (! self::$cachedAuthenticator instanceof SimproAuthenticator || self::$cachedAuthenticator->hasExpired()) {
            self::$cachedAuthenticator = $this->getOrRefreshToken();
        }

        return self::$cachedAuthenticator;
    }

    /**
     * Get or refresh the access token automatically.
     */
    private function getOrRefreshToken(): SimproAuthenticator
    {
        // Try to refresh if we have a refreshable token that expired
        if (self::$cachedAuthenticator?->isRefreshable() && self::$cachedAuthenticator->hasExpired()) {
            $refreshToken = self::$cachedAuthenticator->getRefreshToken();
            if ($refreshToken !== null) {
                try {
                    return $this->refreshAccessToken($refreshToken);
                } catch (Exception) {
                    // If refresh fails, fall through to get a new token
                }
            }
        }

        // Get a new access token
        return $this->getAccessToken();
    }
}
