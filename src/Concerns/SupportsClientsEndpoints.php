<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Concerns;

use Simpro\PhpSdk\Simpro\Resources\ClientResource;

/**
 * @mixin \Simpro\PhpSdk\Simpro\Simpro
 */
trait SupportsClientsEndpoints
{
    public function clients(): ClientResource
    {
        return new ClientResource($this);
    }
}
