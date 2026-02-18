<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Quotes;

use Simpro\PhpSdk\Simpro\Data\Jobs\JobCostBreakdown;
use Simpro\PhpSdk\Simpro\Data\Jobs\JobResourcesCost;
use Simpro\PhpSdk\Simpro\Data\Jobs\JobResourcesMarkup;

final readonly class QuoteTotals
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
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            materialsCost: isset($data['MaterialsCost']) ? JobCostBreakdown::fromArray($data['MaterialsCost']) : null,
            resourcesCost: isset($data['ResourcesCost']) ? JobResourcesCost::fromArray($data['ResourcesCost']) : null,
            materialsMarkup: isset($data['MaterialsMarkup']) ? JobCostBreakdown::fromArray($data['MaterialsMarkup']) : null,
            resourcesMarkup: isset($data['ResourcesMarkup']) ? JobResourcesMarkup::fromArray($data['ResourcesMarkup']) : null,
            adjusted: isset($data['Adjusted']) ? JobCostBreakdown::fromArray($data['Adjusted']) : null,
            membershipDiscount: isset($data['MembershipDiscount']) ? (float) $data['MembershipDiscount'] : null,
            discount: isset($data['Discount']) ? (float) $data['Discount'] : null,
            stcs: isset($data['STCs']) ? (float) $data['STCs'] : null,
            veecs: isset($data['VEECs']) ? (float) $data['VEECs'] : null,
            grossProfitLoss: isset($data['GrossProfitLoss']) ? JobCostBreakdown::fromArray($data['GrossProfitLoss']) : null,
            grossMargin: isset($data['GrossMargin']) ? JobCostBreakdown::fromArray($data['GrossMargin']) : null,
            nettProfitLoss: isset($data['NettProfitLoss']) ? JobCostBreakdown::fromArray($data['NettProfitLoss']) : null,
            nettMargin: isset($data['NettMargin']) ? JobCostBreakdown::fromArray($data['NettMargin']) : null,
        );
    }
}
