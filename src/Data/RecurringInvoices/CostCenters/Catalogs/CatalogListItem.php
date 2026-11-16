<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\Catalogs;

use Simpro\PhpSdk\Simpro\Data\Common\BillableStatus;
use Simpro\PhpSdk\Simpro\Data\Common\PartReference;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\ItemSellPrice;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\ItemTotal;

/**
 * A catalog line as returned by the collection endpoint.
 */
final readonly class CatalogListItem
{
    public function __construct(
        public int $id,
        public ?PartReference $catalog,
        public ?BillableStatus $billableStatus,
        public ?float $basePrice,
        public ?float $markup,
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
            catalog: ! empty($data['Catalog']) && is_array($data['Catalog']) ? PartReference::fromArray($data['Catalog']) : null,
            billableStatus: isset($data['BillableStatus']) ? BillableStatus::tryFrom($data['BillableStatus']) : null,
            basePrice: isset($data['BasePrice']) ? (float) $data['BasePrice'] : null,
            markup: isset($data['Markup']) ? (float) $data['Markup'] : null,
            discount: isset($data['Discount']) ? (float) $data['Discount'] : null,
            sellPrice: ! empty($data['SellPrice']) && is_array($data['SellPrice']) ? ItemSellPrice::fromArray($data['SellPrice']) : null,
            total: ! empty($data['Total']) && is_array($data['Total']) ? ItemTotal::fromArray($data['Total']) : null,
        );
    }
}
