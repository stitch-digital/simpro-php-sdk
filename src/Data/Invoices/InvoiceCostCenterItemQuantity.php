<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Invoices;

final readonly class InvoiceCostCenterItemQuantity
{
    public function __construct(
        public ?float $total = null,
        public ?float $remaining = null,
        public ?float $claimed = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            total: isset($data['Total']) ? (float) $data['Total'] : null,
            remaining: isset($data['Remaining']) ? (float) $data['Remaining'] : null,
            claimed: isset($data['Claimed']) ? (float) $data['Claimed'] : null,
        );
    }
}
