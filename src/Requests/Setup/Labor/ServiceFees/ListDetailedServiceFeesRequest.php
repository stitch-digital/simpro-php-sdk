<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\Labor\ServiceFees;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Setup\ServiceFee;

/**
 * List all service fees with full details.
 */
final class ListDetailedServiceFeesRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    /**
     * All columns available for service fees.
     */
    private const DETAILED_COLUMNS = [
        'ID',
        'Name',
        'SalesTaxCode',
        'LaborTime',
        'Price',
        'DisplayOrder',
        'Archived',
    ];

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/labor/serviceFees/";
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
     * @return array<ServiceFee>
     */
    public function createDtoFromResponse(Response $response): array
    {
        /** @var array<int, array<string, mixed>> $data */
        $data = $response->json();

        return array_map(
            fn (array $item): ServiceFee => ServiceFee::fromArray($item),
            $data
        );
    }
}
