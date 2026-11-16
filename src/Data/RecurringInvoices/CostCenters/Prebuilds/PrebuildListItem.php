<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\Prebuilds;

use Simpro\PhpSdk\Simpro\Data\Common\BillableStatus;
use Simpro\PhpSdk\Simpro\Data\Common\PartReference;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\ItemSellPrice;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\ItemTotal;

/**
 * A prebuild line as returned by the collection endpoint.
 */
final readonly class PrebuildListItem
{
    public function __construct(
        public int $id,
        public ?PartReference $prebuild,
        public ?BillableStatus $billableStatus,
        public ?float $discount,
        public ?ItemSellPrice $sellPrice,
        public ?ItemTotal $total,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            prebuild: ! empty($data['Prebuild']) && is_array($data['Prebuild']) ? PartReference::fromArray($data['Prebuild']) : null,
            billableStatus: isset($data['BillableStatus']) ? BillableStatus::tryFrom($data['BillableStatus']) : null,
            discount: isset($data['Discount']) ? (float) $data['Discount'] : null,
            sellPrice: ! empty($data['SellPrice']) && is_array($data['SellPrice']) ? ItemSellPrice::fromArray($data['SellPrice']) : null,
            total: ! empty($data['Total']) && is_array($data['Total']) ? ItemTotal::fromArray($data['Total']) : null,
        );
    }
}
