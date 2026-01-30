<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers;

/**
 * DTO for customer payment terms.
 *
 * Based on swagger customer endpoints.
 */
final readonly class CustomerPaymentTerms
{
    public function __construct(
        public int $days,
        public string $type,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            days: $data['Days'] ?? 0,
            type: $data['Type'] ?? 'Invoice',
        );
    }
}
