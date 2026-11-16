<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters;

/**
 * A line item's quantity and the amount it adds to the cost center.
 */
final readonly class ItemTotal
{
    public function __construct(
        public float $qty,
        public ?ItemAmount $amount,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            qty: (float) ($data['Qty'] ?? 0),
            amount: ! empty($data['Amount']) && is_array($data['Amount']) ? ItemAmount::fromArray($data['Amount']) : null,
        );
    }
}
