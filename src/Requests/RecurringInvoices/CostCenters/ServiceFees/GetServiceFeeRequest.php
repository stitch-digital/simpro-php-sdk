<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\ServiceFees;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\ServiceFees\ServiceFeeItem;

final class GetServiceFeeRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $recurringInvoiceId,
        private readonly int|string $sectionId,
        private readonly int|string $costCenterId,
        private readonly int|string $serviceFeeId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/recurringInvoices/{$this->recurringInvoiceId}/sections/{$this->sectionId}/costCenters/{$this->costCenterId}/serviceFees/{$this->serviceFeeId}";
    }

    public function createDtoFromResponse(Response $response): ServiceFeeItem
    {
        return ServiceFeeItem::fromResponse($response);
    }
}
