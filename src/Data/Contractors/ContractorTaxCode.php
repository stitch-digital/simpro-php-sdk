<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Contractors;

final readonly class ContractorTaxCode
{
    public function __construct(
        public ?int $id,
        public ?string $code,
        public ?string $type,
        public ?float $rate,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['ID']) ? (int) $data['ID'] : null,
            code: $data['Code'] ?? null,
            type: $data['Type'] ?? null,
            rate: isset($data['Rate']) ? (float) $data['Rate'] : null,
        );
    }
}
