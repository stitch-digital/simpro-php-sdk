<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers\Contracts;

/**
 * DTO for pricing tier information.
 *
 * Based on swagger: GET /api/v1.0/companies/{companyID}/customers/{customerID}/contracts/{contractID}
 */
final readonly class PricingTier
{
    public function __construct(
        public int $id,
        public string $name,
        public float $defaultMarkup,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            name: $data['Name'] ?? '',
            defaultMarkup: isset($data['DefaultMarkup']) ? (float) $data['DefaultMarkup'] : 0.0,
        );
    }
}
