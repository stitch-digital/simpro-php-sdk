<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Concerns;

use Simpro\PhpSdk\Simpro\Resources\QuoteResource;

trait SupportsQuotesEndpoints
{
    public function quotes(int $companyId = 0): QuoteResource
    {
        return new QuoteResource($this, $companyId);
    }
}
