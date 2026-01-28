<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Resources;

use Saloon\Http\BaseResource;
use Simpro\PhpSdk\Simpro\Data\CurrentUser\CurrentUser;
use Simpro\PhpSdk\Simpro\Requests\CurrentUser\GetCurrentUserRequest;

/**
 * Resource for the current authenticated user.
 */
final class CurrentUserResource extends BaseResource
{
    /**
     * Get the currently authenticated user.
     */
    public function get(): CurrentUser
    {
        $request = new GetCurrentUserRequest;

        return $this->connector->send($request)->dto();
    }
}
