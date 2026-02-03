<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Setup;

/**
 * ScaledTierPricing DTO for pricing tier scaled pricing entries.
 */
final readonly class ScaledTierPricing
{
    public function __construct(
        public float $scaledPrice,
        public float $markup,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            scaledPrice: (float) ($data['ScaledPrice'] ?? 0.0),
            markup: (float) ($data['Markup'] ?? 0.0),
        );
    }
}
