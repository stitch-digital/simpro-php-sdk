<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Invoices;

final readonly class InvoiceCostCenterItem
{
    public function __construct(
        public int $id,
        public ?InvoiceCostCenterItemDetail $item = null,
        public ?InvoiceCostCenterItemQuantity $quantity = null,
        public ?InvoiceCostCenterTotal $unitPrice = null,
        public ?InvoiceCostCenterTotal $total = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            item: ! empty($data['Item']) ? InvoiceCostCenterItemDetail::fromArray($data['Item']) : null,
            quantity: ! empty($data['Quantity']) ? InvoiceCostCenterItemQuantity::fromArray($data['Quantity']) : null,
            unitPrice: ! empty($data['UnitPrice']) ? InvoiceCostCenterTotal::fromArray($data['UnitPrice']) : null,
            total: ! empty($data['Total']) ? InvoiceCostCenterTotal::fromArray($data['Total']) : null,
        );
    }
}
