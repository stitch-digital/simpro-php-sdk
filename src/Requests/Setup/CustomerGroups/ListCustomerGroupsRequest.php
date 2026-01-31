<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CustomerGroups;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Setup\CustomerGroupListItem;

/**
 * List all customer groups.
 */
final class ListCustomerGroupsRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/customerGroups/";
    }

    /**
     * @return array<CustomerGroupListItem>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();

        return array_map(
            fn (array $item) => CustomerGroupListItem::fromArray($item),
            $data
        );
    }
}
