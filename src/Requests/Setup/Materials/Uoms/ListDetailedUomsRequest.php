<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\Materials\Uoms;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Setup\Uom;

/**
 * List all units of measurement with full details.
 */
final class ListDetailedUomsRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    /**
     * All columns available for units of measurement.
     */
    private const DETAILED_COLUMNS = [
        'ID',
        'Name',
        'WholeNoOnly',
    ];

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/materials/uoms/";
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
     * @return array<Uom>
     */
    public function createDtoFromResponse(Response $response): array
    {
        /** @var array<int, array<string, mixed>> $data */
        $data = $response->json();

        return array_map(
            fn (array $item): Uom => Uom::fromArray($item),
            $data
        );
    }
}
