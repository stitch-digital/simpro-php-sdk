<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Concerns;

use Simpro\PhpSdk\Simpro\Resources\InfoResource;

trait SupportsInfoEndpoints
{
    public function info(): InfoResource
    {
        return new InfoResource($this);
    }
}
