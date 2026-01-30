<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers;

/**
 * DTO for individual customer list items.
 *
 * Based on swagger: GET /api/v1.0/companies/{companyID}/customers/individuals/
 */
final readonly class CustomerIndividualListItem
{
    public function __construct(
        public int $id,
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
            givenName: $data['GivenName'] ?? '',
            familyName: $data['FamilyName'] ?? '',
        );
    }
}
