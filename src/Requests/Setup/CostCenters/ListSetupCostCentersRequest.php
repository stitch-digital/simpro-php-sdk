<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CostCenters;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Setup\SetupCostCenterListItem;

/**
 * List all cost centers.
 */
final class ListSetupCostCentersRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/accounts/costCenters/";
    }

    /**
     * @return array<SetupCostCenterListItem>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();

        return array_map(
            fn (array $item) => SetupCostCenterListItem::fromArray($item),
            $data
        );
    }
}
