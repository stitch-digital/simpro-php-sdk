<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Concerns;

use Simpro\PhpSdk\Simpro\Resources\Setup\PrebuildLibraryResource;

trait SupportsPrebuildsEndpoints
{
    public function prebuilds(int $companyId = 0): PrebuildLibraryResource
    {
        return new PrebuildLibraryResource($this, $companyId);
    }
}
