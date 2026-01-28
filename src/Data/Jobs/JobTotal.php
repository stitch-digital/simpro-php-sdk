<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs;

final readonly class JobTotal
{
    public function __construct(
        public float $exTax,
        public float $tax,
        public float $incTax,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            exTax: (float) ($data['ExTax'] ?? 0),
            tax: (float) ($data['Tax'] ?? 0),
            incTax: (float) ($data['IncTax'] ?? 0),
        );
    }
}
