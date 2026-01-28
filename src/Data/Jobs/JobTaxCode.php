<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs;

final readonly class JobTaxCode
{
    public function __construct(
        public int $id,
        public string $code,
        public ?string $type,
        public ?float $rate,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            code: $data['Code'] ?? '',
            type: $data['Type'] ?? null,
            rate: isset($data['Rate']) ? (float) $data['Rate'] : null,
        );
    }
}
