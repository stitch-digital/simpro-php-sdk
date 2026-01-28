<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Concerns;

use Simpro\PhpSdk\Simpro\Resources\InvoiceResource;

trait SupportsInvoicesEndpoints
{
    public function invoices(int|string $companyId = 0): InvoiceResource
    {
        return new InvoiceResource($this, $companyId);
    }
}
