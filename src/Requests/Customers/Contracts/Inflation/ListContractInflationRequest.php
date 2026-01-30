<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Customers\Contracts\Inflation;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Customers\Contracts\ContractInflationListItem;

final class ListContractInflationRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int|string $companyId,
        private readonly int|string $customerId,
        private readonly int|string $contractId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/customers/{$this->customerId}/contracts/{$this->contractId}/inflation/";
    }

    /**
     * @return array<ContractInflationListItem>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();

        return array_map(
            fn (array $item) => ContractInflationListItem::fromArray($item),
            $data
        );
    }
}
