<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Invoices;

final readonly class InvoiceTotals
{
    public function __construct(
        public float $totalExTax,
        public float $totalIncTax,
        public float $totalTax,
        public float $amountDue,
        public float $amountPaid,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            totalExTax: (float) ($data['TotalExTax'] ?? 0),
            totalIncTax: (float) ($data['TotalIncTax'] ?? 0),
            totalTax: (float) ($data['TotalTax'] ?? 0),
            amountDue: (float) ($data['AmountDue'] ?? 0),
            amountPaid: (float) ($data['AmountPaid'] ?? 0),
        );
    }
}
