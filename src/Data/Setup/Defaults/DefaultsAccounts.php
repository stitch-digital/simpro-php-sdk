<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup\Defaults;

final readonly class DefaultsAccounts
{
    public function __construct(
        public string $incomeAccount,
        public string $depositAccount,
        public string $expenseAccount,
        public string $contractorInvoiceAccount,
        public string $retainageAssetAccount,
        public string $retainageLiabilityAccount,
        public string $financeChargeAccount,
        public string $freightAccount,
        public string $restockingFeeAccount,
        public string $taxAccount,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            incomeAccount: $data['IncomeAccount'] ?? '',
            depositAccount: $data['DepositAccount'] ?? '',
            expenseAccount: $data['ExpenseAccount'] ?? '',
            contractorInvoiceAccount: $data['ContractorInvoiceAccount'] ?? '',
            retainageAssetAccount: $data['RetainageAssetAccount'] ?? '',
            retainageLiabilityAccount: $data['RetainageLiabilityAccount'] ?? '',
            financeChargeAccount: $data['FinanceChargeAccount'] ?? '',
            freightAccount: $data['FreightAccount'] ?? '',
            restockingFeeAccount: $data['RestockingFeeAccount'] ?? '',
            taxAccount: $data['TaxAccount'] ?? '',
        );
    }
}
