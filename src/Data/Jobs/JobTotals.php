<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs;

final readonly class JobTotals
{
    public function __construct(
        public ?JobCostBreakdown $materialsCost,
        public ?JobResourcesCost $resourcesCost,
        public ?JobCostBreakdown $materialsMarkup,
        public ?JobResourcesMarkup $resourcesMarkup,
        public ?JobCostBreakdown $adjusted,
        public ?float $membershipDiscount,
        public ?float $discount,
        public ?float $stcs,
        public ?float $veecs,
        public ?JobCostBreakdown $grossProfitLoss,
        public ?JobCostBreakdown $grossMargin,
        public ?JobCostBreakdown $nettProfitLoss,
        public ?JobCostBreakdown $nettMargin,
        public ?float $invoicedValue,
        public ?float $invoicePercentage,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            materialsCost: ! empty($data['MaterialsCost']) ? JobCostBreakdown::fromArray($data['MaterialsCost']) : null,
            resourcesCost: ! empty($data['ResourcesCost']) ? JobResourcesCost::fromArray($data['ResourcesCost']) : null,
            materialsMarkup: ! empty($data['MaterialsMarkup']) ? JobCostBreakdown::fromArray($data['MaterialsMarkup']) : null,
            resourcesMarkup: ! empty($data['ResourcesMarkup']) ? JobResourcesMarkup::fromArray($data['ResourcesMarkup']) : null,
            adjusted: ! empty($data['Adjusted']) ? JobCostBreakdown::fromArray($data['Adjusted']) : null,
            membershipDiscount: isset($data['MembershipDiscount']) ? (float) $data['MembershipDiscount'] : null,
            discount: isset($data['Discount']) ? (float) $data['Discount'] : null,
            stcs: isset($data['STCs']) ? (float) $data['STCs'] : null,
            veecs: isset($data['VEECs']) ? (float) $data['VEECs'] : null,
            grossProfitLoss: ! empty($data['GrossProfitLoss']) ? JobCostBreakdown::fromArray($data['GrossProfitLoss']) : null,
            grossMargin: ! empty($data['GrossMargin']) ? JobCostBreakdown::fromArray($data['GrossMargin']) : null,
            nettProfitLoss: ! empty($data['NettProfitLoss']) ? JobCostBreakdown::fromArray($data['NettProfitLoss']) : null,
            nettMargin: ! empty($data['NettMargin']) ? JobCostBreakdown::fromArray($data['NettMargin']) : null,
            invoicedValue: isset($data['InvoicedValue']) ? (float) $data['InvoicedValue'] : null,
            invoicePercentage: isset($data['InvoicePercentage']) ? (float) $data['InvoicePercentage'] : null,
        );
    }
}
