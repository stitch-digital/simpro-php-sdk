<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\Labor\PlantRates;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Setup\PlantRate;

/**
 * List all plant rates with full details.
 */
final class ListDetailedPlantRatesRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    /**
     * All columns available for plant rates.
     */
    private const DETAILED_COLUMNS = [
        'ID',
        'Name',
        'CostRate',
        'Markup',
        'TaxCode',
        'AddToAllCustomers',
        'Plant',
    ];

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/labor/plantRates/";
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
     * @return array<PlantRate>
     */
    public function createDtoFromResponse(Response $response): array
    {
        /** @var array<int, array<string, mixed>> $data */
        $data = $response->json();

        return array_map(
            fn (array $item): PlantRate => PlantRate::fromArray($item),
            $data
        );
    }
}
