<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\BusinessGroups;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Setup\BusinessGroupListItem;

/**
 * List all business groups.
 */
final class ListBusinessGroupsRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/accounts/businessGroups/";
    }

    /**
     * @return array<BusinessGroupListItem>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();

        return array_map(
            fn (array $item) => BusinessGroupListItem::fromArray($item),
            $data
        );
    }
}
