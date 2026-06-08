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
 * Exchange a refresh token for a new access token within the Resource Owner
 * Password Credentials grant flow.
 */
final class GetPasswordRefreshTokenRequest extends Request implements HasBody
{
    use AcceptsJson;
    use HasFormBody;

    protected Method $method = Method::POST;

    /**
     * Allow the OAuth config's absolute token endpoint to override the
     * connector base URL, mirroring how Saloon treats its built-in
     * `GetRefreshTokenRequest`.
     */
    public ?bool $allowBaseUrlOverride = true;

    public function __construct(
        protected OAuthConfig $oauthConfig,
        protected string $refreshToken,
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
     *     refresh_token: string,
     * }
     */
    public function defaultBody(): array
    {
        return [
            'grant_type' => 'refresh_token',
            'client_id' => $this->oauthConfig->getClientId(),
            'client_secret' => $this->oauthConfig->getClientSecret(),
            'refresh_token' => $this->refreshToken,
        ];
    }
}
