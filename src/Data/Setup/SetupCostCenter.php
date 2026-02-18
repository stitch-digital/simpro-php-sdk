<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

use Saloon\Http\Response;

/**
 * Setup cost center DTO.
 */
final readonly class SetupCostCenter
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $incomeAccountNo = null,
        public ?string $expenseAccountNo = null,
        public ?string $accrualRevAccountNo = null,
        public ?string $deferralRevAccountNo = null,
        public ?float $monthlySalesBudget = null,
        public ?float $monthlyExpenditureBudget = null,
        public bool $archived = false,
        public bool $isMembershipCostCenter = false,
        public ?CostCenterRates $rates = null,
    ) {}

    public static function fromResponse(Response $response): self
    {
        $data = $response->json();

        return self::fromArray($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            name: $data['Name'] ?? '',
            incomeAccountNo: $data['IncomeAccountNo'] ?? null,
            expenseAccountNo: $data['ExpenseAccountNo'] ?? null,
            accrualRevAccountNo: $data['AccrualRevAccountNo'] ?? null,
            deferralRevAccountNo: $data['DeferralRevAccountNo'] ?? null,
            monthlySalesBudget: isset($data['MonthlySalesBudget']) ? (float) $data['MonthlySalesBudget'] : null,
            monthlyExpenditureBudget: isset($data['MonthlyExpenditureBudget']) ? (float) $data['MonthlyExpenditureBudget'] : null,
            archived: (bool) ($data['Archived'] ?? false),
            isMembershipCostCenter: (bool) ($data['IsMembershipCostCenter'] ?? false),
            rates: ! empty($data['Rates']) ? CostCenterRates::fromArray($data['Rates']) : null,
        );
    }
}
