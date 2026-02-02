<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\AccountingCategories;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Setup\AccountingCategory;

/**
 * List accounting categories with full details.
 *
 * Uses the columns parameter to request all available fields,
 * returning full AccountingCategory DTOs instead of list items.
 */
final class ListDetailedAccountingCategoriesRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    private const DETAILED_COLUMNS = [
        'ID',
        'Name',
        'Ref',
        'Archived',
    ];

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/accounts/accCategories/";
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return [
            'columns' => implode(',', self::DETAILED_COLUMNS),
        ];
    }

    /**
     * @return array<int, AccountingCategory>
     */
    public function createDtoFromResponse(Response $response): array
    {
        /** @var array<int, array<string, mixed>> $data */
        $data = $response->json();

        return array_map(
            fn (array $item): AccountingCategory => AccountingCategory::fromArray($item),
            $data
        );
    }
}
