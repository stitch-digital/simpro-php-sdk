<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers;

/**
 * DTO for customer currency information.
 *
 * Based on swagger customer endpoints.
 */
final readonly class CustomerCurrency
{
    public function __construct(
        public string $id,
        public string $name,
        public bool $visible,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'] ?? '',
            name: $data['Name'] ?? '',
            visible: $data['Visible'] ?? false,
        );
    }
}
