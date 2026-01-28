<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\CurrentUser;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\CurrentUser\CurrentUser;

/**
 * Get the currently authenticated user.
 */
final class GetCurrentUserRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/api/v1.0/currentUser/';
    }

    public function createDtoFromResponse(Response $response): CurrentUser
    {
        return CurrentUser::fromResponse($response);
    }
}
