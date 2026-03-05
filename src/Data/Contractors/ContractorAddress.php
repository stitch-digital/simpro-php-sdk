<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Contractors;

final readonly class ContractorAddress
{
    public function __construct(
        public string $address,
        public string $city,
        public string $state,
        public string $postalCode,
        public string $country,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            address: $data['Address'] ?? '',
            city: $data['City'] ?? '',
            state: $data['State'] ?? '',
            postalCode: $data['PostalCode'] ?? '',
            country: $data['Country'] ?? '',
        );
    }
}
