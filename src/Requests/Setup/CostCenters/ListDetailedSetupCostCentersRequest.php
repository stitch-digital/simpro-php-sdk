<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Requests\Setup\CostCenters;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Simpro\PhpSdk\Simpro\Data\Setup\SetupCostCenter;

/**
 * List all cost centers with full details.
 */
final class ListDetailedSetupCostCentersRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    /**
     * All columns available for setup cost centers.
     */
    private const DETAILED_COLUMNS = [
        'ID',
        'Name',
        'IncomeAccountNo',
        'ExpenseAccountNo',
        'AccrualRevAccountNo',
        'DeferralRevAccountNo',
        'MonthlySalesBudget',
        'MonthlyExpenditureBudget',
        'Archived',
        'IsMembershipCostCenter',
        'Rates',
    ];

    public function __construct(
        private readonly int $companyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1.0/companies/{$this->companyId}/setup/accounts/costCenters/";
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
     * @return array<SetupCostCenter>
     */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();

        return array_map(
            fn (array $item): SetupCostCenter => SetupCostCenter::fromArray($item),
            $data
        );
    }
}
