<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\AssetTypes;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Setup\AssetType;

/**
 * List asset types with full details.
 *
 * Uses the columns parameter to request all available fields,
 * returning full AssetType DTOs instead of list items.
 */
final class ListDetailedAssetTypesRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    private const DETAILED_COLUMNS = [
        'ID',
        'Name',
        'Reference',
        'RegType',
        'JobCostCenter',
        'QuoteCostCenter',
        'DefaultTechnician',
        'Description',
        'Archived',
        'ServiceLevels',
    ];

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/assetTypes/";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return [
            'columns' => implode(',', self::DETAILED_COLUMNS),
            'display' => 'all',
        ];
    }

    /**
     * @return array<AssetType>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();

        return array_map(
            fn (array $item) => AssetType::fromArray($item),
            $data
        );
    }
}
