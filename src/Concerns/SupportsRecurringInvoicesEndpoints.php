<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Concerns;

use Simpro\PhpSdk\Simpro\Resources\RecurringInvoiceResource;

trait SupportsRecurringInvoicesEndpoints
{
    public function recurringInvoices(int $companyId = 0): RecurringInvoiceResource
    {
        return new RecurringInvoiceResource($this, $companyId);
    }
}
