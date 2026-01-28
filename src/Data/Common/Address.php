<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Common;

/**
 * Common DTO for address information.
 *
 * Used by customers, sites, employees, and other entities.
 */
final readonly class Address
{
    public function __construct(
        public ?string $address = null,
        public ?string $city = null,
        public ?string $state = null,
        public ?string $postalCode = null,
        public ?string $country = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            address: $data['Address'] ?? null,
            city: $data['City'] ?? null,
            state: $data['State'] ?? null,
            postalCode: $data['PostalCode'] ?? null,
            country: $data['Country'] ?? null,
        );
    }

    /**
     * Get the full address as a formatted string.
     */
    public function format(string $separator = ', '): string
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->postalCode,
            $this->country,
        ]);

        return implode($separator, $parts);
    }

    /**
     * Check if the address has any data.
     */
    public function isEmpty(): bool
    {
        return $this->address === null
            && $this->city === null
            && $this->state === null
            && $this->postalCode === null
            && $this->country === null;
    }
}
