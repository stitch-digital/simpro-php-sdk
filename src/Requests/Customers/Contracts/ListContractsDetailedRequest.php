<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Customers\Contracts;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Customers\Contracts\Contract;

/**
 * Request to list contracts with all available columns.
 *
 * Returns detailed Contract DTOs with full nested data structures.
 * Uses the columns parameter to request all available fields in a single request.
 */
final class ListContractsDetailedRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
        private readonly int|string $customerId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/customers/{$this->customerId}/contracts/";
    }

    /**
     * @return array<string, string>
     */
    protected function defaultQuery(): array
    {
        return [
            'columns' => implode(',', [
                'ID',
                'Name',
                'StartDate',
                'EndDate',
                'ContractNo',
                'Value',
                'Notes',
                'Email',
                'Archived',
                'Expired',
                'PricingTier',
                'Markup',
                'Rates',
                'CustomFields',
                'ServiceLevels',
            ]),
        ];
    }

    /**
     * @return array<Contract>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();

        return array_map(
            fn (array $item) => Contract::fromArray($item),
            $data
        );
    }
}
