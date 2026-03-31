<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\ContractorJobs;

final readonly class ContractorJobTaxCode
{
    public function __construct(
        public int $id,
        public string $code,
        public string $type,
        public float $rate,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            code: $data['Code'] ?? '',
            type: $data['Type'] ?? '',
            rate: (float) ($data['Rate'] ?? 0),
        );
    }
}
