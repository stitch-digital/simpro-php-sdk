<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

/**
 * Advanced commission components DTO.
 */
final readonly class AdvancedCommissionComponents
{
    public function __construct(
        public float $catalog = 0.0,
        public float $prebuild = 0.0,
        public float $oneOffs = 0.0,
        public float $labor = 0.0,
        public float $serviceFee = 0.0,
        public float $adjustment = 0.0,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            catalog: (float) ($data['Catalog'] ?? 0.0),
            prebuild: (float) ($data['Prebuild'] ?? 0.0),
            oneOffs: (float) ($data['OneOffs'] ?? 0.0),
            labor: (float) ($data['Labor'] ?? 0.0),
            serviceFee: (float) ($data['ServiceFee'] ?? 0.0),
            adjustment: (float) ($data['Adjustment'] ?? 0.0),
        );
    }
}
