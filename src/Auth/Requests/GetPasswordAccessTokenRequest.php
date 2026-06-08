<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Auth\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Helpers\OAuth2\OAuthConfig;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasFormBody;
use Saloon\Traits\Plugins\AcceptsJson;

/**
 * Exchange a resource owner's username and password for an access token via
 * the OAuth 2.0 Resource Owner Password Credentials grant (RFC 6749 §4.3).
 */
final class GetPasswordAccessTokenRequest extends Request implements HasBody
{
    use AcceptsJson;
    use HasFormBody;

    protected Method $method = Method::POST;

    /**
     * Allow the OAuth config's absolute token endpoint to override the
     * connector base URL, mirroring how Saloon treats its built-in
     * `GetAccessTokenRequest`.
     */
    public ?bool $allowBaseUrlOverride = true;

    public function __construct(
        protected OAuthConfig $oauthConfig,
        protected string $username,
        protected string $password,
    ) {}

    public function resolveEndpoint(): string
    {
        return $this->oauthConfig->getTokenEndpoint();
    }

    /**
     * @return array{
     *     grant_type: string,
     *     client_id: string,
     *     client_secret: string,
     *     username: string,
     *     password: string,
     * }
     */
    public function defaultBody(): array
    {
        return [
            'grant_type' => 'password',
            'client_id' => $this->oauthConfig->getClientId(),
            'client_secret' => $this->oauthConfig->getClientSecret(),
            'username' => $this->username,
            'password' => $this->password,
        ];
    }
}
