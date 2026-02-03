<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\Materials\PurchasingStages;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Setup\PurchasingStage;

/**
 * List all purchasing stages with full details.
 */
final class ListDetailedPurchasingStagesRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    /**
     * All columns available for purchasing stages.
     */
    private const DETAILED_COLUMNS = [
        'ID',
        'Name',
        'Archived',
    ];

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/materials/purchasingStages/";
    }

    /**
     * @return array<string, string>
     */
    protected function defaultQuery(): array
    {
        return [
            'columns' => implode(',', self::DETAILED_COLUMNS),
        ];
    }

    /**
     * @return array<PurchasingStage>
     */
    public function createDtoFromResponse(Response $response): array
    {
        /** @var array<int, array<string, mixed>> $data */
        $data = $response->json();

        return array_map(
            fn (array $item): PurchasingStage => PurchasingStage::fromArray($item),
            $data
        );
    }
}
