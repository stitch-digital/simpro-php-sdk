<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Quotes\CostCenters;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Quotes\CostCenters\QuoteCostCenterListItem;

/**
 * List all quote cost centres for a company (across all quotes).
 *
 * Endpoint: GET /api/v1.0/companies/{companyId}/quoteCostCenters/
 */
final class ListAllQuoteCostCentersRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/quoteCostCenters/";
    }

    /**
     * @return array<string, string>
     */
    protected function defaultQuery(): array
    {
        return [
            'display' => 'all',
            'columns' => implode(',', [
                'ID',
                'CostCenter',
                'Name',
                'Quote',
                'Section',
                'DisplayOrder',
                'Total',
                'DateModified',
            ]),
        ];
    }

    /**
     * @return array<QuoteCostCenterListItem>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();

        return array_map(
            fn (array $item) => QuoteCostCenterListItem::fromArray($item),
            $data,
        );
    }
}
