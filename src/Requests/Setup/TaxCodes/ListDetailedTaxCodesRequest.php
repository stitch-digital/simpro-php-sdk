<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\TaxCodes;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Setup\DetailedTaxCode;

/**
 * List all tax codes with full details.
 */
final class ListDetailedTaxCodesRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    /**
     * All columns available for tax codes.
     */
    private const DETAILED_COLUMNS = [
        'ID',
        'Code',
        'Name',
        'Rate',
        'ReverseTaxEnabled',
        'IsPartIncomeDefault',
        'IsLaborIncomeDefault',
        'Archived',
        'DateModified',
        '_href',
    ];

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/accounts/taxCodes/";
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
     * @return array<DetailedTaxCode>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();

        return array_map(
            fn (array $item): DetailedTaxCode => DetailedTaxCode::fromArray($item),
            $data
        );
    }
}
