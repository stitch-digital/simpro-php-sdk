<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup\Defaults;

final readonly class DefaultsInvoicing
{
    public function __construct(
        public string $showSellCostPrices,
        public string $financeChargeLabel,
        public string $tracking,
        public string $retainageHold,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            showSellCostPrices: $data['ShowSellCostPrices'] ?? '',
            financeChargeLabel: $data['FinanceChargeLabel'] ?? '',
            tracking: $data['Tracking'] ?? '',
            retainageHold: $data['RetainageHold'] ?? '',
        );
    }
}
