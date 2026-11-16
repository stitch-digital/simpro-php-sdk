<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\OneOffs;

use Simpro\PhpSdk\Simpro\Data\Common\BillableStatus;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\ItemSellPrice;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\ItemTotal;

/**
 * A one-off line as returned by the collection endpoint.
 */
final readonly class OneOffListItem
{
    public function __construct(
        public int $id,
        public ?OneOffType $type,
        public ?BillableStatus $billableStatus,
        public ?string $description,
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
            type: isset($data['Type']) ? OneOffType::tryFrom($data['Type']) : null,
            billableStatus: isset($data['BillableStatus']) ? BillableStatus::tryFrom($data['BillableStatus']) : null,
            description: $data['Description'] ?? null,
            discount: isset($data['Discount']) ? (float) $data['Discount'] : null,
            sellPrice: ! empty($data['SellPrice']) && is_array($data['SellPrice']) ? ItemSellPrice::fromArray($data['SellPrice']) : null,
            total: ! empty($data['Total']) && is_array($data['Total']) ? ItemTotal::fromArray($data['Total']) : null,
        );
    }
}
