<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers;

final readonly class CustomerListItem
{
    public function __construct(
        public int $id,
        public string $companyName,
        public string $givenName,
        public string $familyName,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            companyName: $data['CompanyName'] ?? '',
            givenName: $data['GivenName'] ?? '',
            familyName: $data['FamilyName'] ?? '',
        );
    }
}
