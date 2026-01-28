<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Concerns;

use Simpro\PhpSdk\Simpro\Resources\CurrentUserResource;

/**
 * Trait for connectors that support the CurrentUser endpoint.
 */
trait SupportsCurrentUserEndpoints
{
    /**
     * Access the current user resource.
     */
    public function currentUser(): CurrentUserResource
    {
        return new CurrentUserResource($this);
    }
}
