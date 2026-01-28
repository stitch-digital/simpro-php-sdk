<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Quotes;

final readonly class QuoteTotals
{
    public function __construct(
        public float $totalExTax,
        public float $totalIncTax,
        public float $totalTax,
        public float $totalCost,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            totalExTax: (float) ($data['TotalExTax'] ?? 0),
            totalIncTax: (float) ($data['TotalIncTax'] ?? 0),
            totalTax: (float) ($data['TotalTax'] ?? 0),
            totalCost: (float) ($data['TotalCost'] ?? 0),
        );
    }
}
