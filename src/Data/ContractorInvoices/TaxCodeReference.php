<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\ContractorInvoices;

final readonly class TaxCodeReference
{
    public function __construct(
        public int $id,
        public ?string $code = null,
        public ?float $rate = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) ($data['ID'] ?? 0),
            code: $data['Code'] ?? null,
            rate: isset($data['Rate']) ? (float) $data['Rate'] : null,
        );
    }
}
