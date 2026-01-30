<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers;

use Simpro\PhpSdk\Simpro\Data\Customers\Contracts\PricingTier;

/**
 * DTO for customer material pricing information.
 *
 * Based on swagger customer endpoints.
 */
final readonly class CustomerMaterial
{
    public function __construct(
        public PricingTier $pricingTier,
        public float $markup,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            pricingTier: PricingTier::fromArray($data['PricingTier']),
            markup: isset($data['Markup']) ? (float) $data['Markup'] : 0.0,
        );
    }
}
