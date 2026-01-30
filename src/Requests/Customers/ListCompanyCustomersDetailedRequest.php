<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Customers;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Customers\CustomerCompanyListDetailedItem;

/**
 * Request to list company customers with all available columns.
 *
 * Returns detailed CustomerCompanyListDetailedItem DTOs with full nested data structures.
 * Uses the columns parameter to request all available fields in a single request.
 */
final class ListCompanyCustomersDetailedRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int|string $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/customers/companies/";
    }

    /**
     * @return array<string, string>
     */
    protected function defaultQuery(): array
    {
        return [
            'columns' => implode(',', [
                'ID',
                'CompanyName',
                'Phone',
                'Address',
                'BillingAddress',
                'CustomerType',
                'Tags',
                'AmountOwing',
                'Profile',
                'Banking',
                'Archived',
                'Sites',
                'Contracts',
                'Contacts',
                'ResponseTimes',
                'CustomFields',
                'Email',
                'DateModified',
                'DateCreated',
                '_href',
            ]),
        ];
    }

    /**
     * @return array<CustomerCompanyListDetailedItem>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();

        return array_map(
            fn (array $item) => CustomerCompanyListDetailedItem::fromArray($item),
            $data
        );
    }
}
