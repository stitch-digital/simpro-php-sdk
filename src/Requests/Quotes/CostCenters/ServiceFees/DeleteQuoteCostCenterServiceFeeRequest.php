<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters\ServiceFees;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class DeleteQuoteCostCenterServiceFeeRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $quoteId,
        private readonly int|string $sectionId,
        private readonly int|string $costCenterId,
        private readonly int|string $serviceFeeId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/quotes/{$this->quoteId}/sections/{$this->sectionId}/costCenters/{$this->costCenterId}/serviceFees/{$this->serviceFeeId}";
    }
}
