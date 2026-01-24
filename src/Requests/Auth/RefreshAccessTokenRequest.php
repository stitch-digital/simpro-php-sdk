<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Auth;

use Saloon\Contracts\Authenticator;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Auth\BasicAuthenticator;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasFormBody;
use Saloon\Traits\Plugins\AcceptsJson;

final class RefreshAccessTokenRequest extends Request implements HasBody
{
    use AcceptsJson;
    use HasFormBody;

    /**
     * Define the method that the request will use.
     */
    protected Method $method = Method::POST;

    /**
     * Constructor
     */
    public function __construct(
        private string $refreshToken,
        private string $clientId,
        private string $clientSecret
    ) {
        //
    }

    /**
     * Define the endpoint for the request.
     */
    public function resolveEndpoint(): string
    {
        return '/api/oauth2/token/';
    }

    /**
     * Register the default data.
     *
     * @return array{
     *     grant_type: string,
     *     refresh_token: string,
     * }
     */
    protected function defaultBody(): array
    {
        return [
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->refreshToken,
        ];
    }

    /**
     * Default authenticator used for Basic Auth with client credentials.
     */
    protected function defaultAuth(): Authenticator
    {
        return new BasicAuthenticator(
            $this->clientId,
            $this->clientSecret
        );
    }
}
