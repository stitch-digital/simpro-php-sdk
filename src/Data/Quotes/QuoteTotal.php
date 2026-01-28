<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Quotes;

final readonly class QuoteTotal
{
    public function __construct(
        public float $exTax,
        public float $tax,
        public float $incTax,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            exTax: (float) ($data['ExTax'] ?? 0),
            tax: (float) ($data['Tax'] ?? 0),
            incTax: (float) ($data['IncTax'] ?? 0),
        );
    }
}
