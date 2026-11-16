<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\OneOffs;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\OneOffs\OneOffItem;

final class GetOneOffRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $recurringInvoiceId,
        private readonly int|string $sectionId,
        private readonly int|string $costCenterId,
        private readonly int|string $oneOffId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/recurringInvoices/{$this->recurringInvoiceId}/sections/{$this->sectionId}/costCenters/{$this->costCenterId}/oneOffs/{$this->oneOffId}";
    }

    public function createDtoFromResponse(Response $response): OneOffItem
    {
        return OneOffItem::fromResponse($response);
    }
}
