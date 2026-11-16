<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\OneOffs;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\OneOffs\OneOffListItem;

final class ListOneOffsRequest extends Request implements Paginatable
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
        return "/api/v1.0/companies/{$this->companyId}/recurringInvoices/{$this->recurringInvoiceId}/sections/{$this->sectionId}/costCenters/{$this->costCenterId}/oneOffs/";
    }

    /**
     * @return array<OneOffListItem>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            fn (array $item) => OneOffListItem::fromArray($item),
            $response->json()
        );
    }
}
