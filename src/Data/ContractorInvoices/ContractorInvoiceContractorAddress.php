<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\ContractorInvoices;

final readonly class ContractorInvoiceContractorAddress
{
    public function __construct(
        public ?string $address = null,
        public ?string $city = null,
        public ?string $state = null,
        public ?string $postalCode = null,
        public ?string $country = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
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
}
