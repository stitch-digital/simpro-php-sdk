<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Customers;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Customers\CustomerCompanyListItem;

final class ListCompanyCustomersRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/customers/companies/";
    }

    /**
     * @return array<CustomerCompanyListItem>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();

        return array_map(
            fn (array $item) => CustomerCompanyListItem::fromArray($item),
            $data
        );
    }
}
