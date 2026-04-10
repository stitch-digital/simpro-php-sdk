<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Contractors;

final readonly class ContractorPaymentTerms
{
    public function __construct(
        public ?int $days,
        public ?string $type,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            days: isset($data['Days']) ? (int) $data['Days'] : null,
            type: $data['Type'] ?? null,
        );
    }
}
