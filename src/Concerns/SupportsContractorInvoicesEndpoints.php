<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Concerns;

use Simpro\PhpSdk\Simpro\Resources\ContractorInvoiceResource;

trait SupportsContractorInvoicesEndpoints
{
    public function contractorInvoices(int $companyId = 0): ContractorInvoiceResource
    {
        return new ContractorInvoiceResource($this, $companyId);
    }
}
