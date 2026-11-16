<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\Labor;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\Labor\LaborListItem;

final class ListLaborRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $recurringInvoiceId,
        private readonly int|string $sectionId,
        private readonly int|string $costCenterId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/recurringInvoices/{$this->recurringInvoiceId}/sections/{$this->sectionId}/costCenters/{$this->costCenterId}/labor/";
    }

    /**
     * @return array<LaborListItem>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            fn (array $item) => LaborListItem::fromArray($item),
            $response->json()
        );
    }
}
