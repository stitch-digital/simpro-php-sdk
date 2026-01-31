<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Customers\Individuals;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Customers\CustomerIndividual;

/**
 * Request to list individual customers with all available columns.
 *
 * Returns detailed CustomerIndividual DTOs with full nested data structures.
 * Uses the columns parameter to request all available fields in a single request.
 */
final class ListIndividualCustomersDetailedRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/customers/individuals/";
    }

    /**
     * @return array<string, string>
     */
    protected function defaultQuery(): array
    {
        return [
            'columns' => implode(',', [
                'ID',
                'Title',
                'GivenName',
                'FamilyName',
                'PreferredTechs',
                'Phone',
                'DoNotCall',
                'AltPhone',
                'Address',
                'BillingAddress',
                'CustomerType',
                'Tags',
                'AmountOwing',
                'Rates',
                'Profile',
                'Banking',
                'Archived',
                'Sites',
                'CustomFields',
                'Email',
                'DateModified',
                'DateCreated',
                'CellPhone',
            ]),
        ];
    }

    /**
     * @return array<CustomerIndividual>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();

        return array_map(
            fn (array $item) => CustomerIndividual::fromArray($item),
            $data
        );
    }
}
