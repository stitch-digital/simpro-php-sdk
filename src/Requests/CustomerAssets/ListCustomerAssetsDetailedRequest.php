<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\CustomerAssets;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\CustomerAssets\CustomerAssetListDetailedItem;

final class ListCustomerAssetsDetailedRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/customerAssets/";
    }

    /**
     * @return array<string, string>
     */
    protected function defaultQuery(): array
    {
        return [
            'columns' => implode(',', [
                'ID',
                'AssetType',
                'DisplayOrder',
                'CustomerContract',
                'StartDate',
                'LastTest',
                'Archived',
                'CustomFields',
                'ParentID',
                'DateModified',
                'Site',
            ]),
        ];
    }

    /**
     * @return array<CustomerAssetListDetailedItem>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            fn (array $item) => CustomerAssetListDetailedItem::fromArray($item),
            $response->json()
        );
    }
}
