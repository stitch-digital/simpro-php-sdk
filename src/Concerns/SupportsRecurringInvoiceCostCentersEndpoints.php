<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Concerns;

use Simpro\PhpSdk\Simpro\Resources\RecurringInvoiceCostCenterResource;

trait SupportsRecurringInvoiceCostCentersEndpoints
{
    public function recurringInvoiceCostCenters(int $companyId = 0): RecurringInvoiceCostCenterResource
    {
        return new RecurringInvoiceCostCenterResource($this, $companyId);
    }
}
