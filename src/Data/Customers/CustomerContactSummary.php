<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers;

/**
 * DTO for customer contact summary (embedded in Customer response).
 *
 * Based on swagger: Contacts array in GET /api/v1.0/companies/{companyID}/customers/companies/{customerID}
 */
final readonly class CustomerContactSummary
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
            id: (int) $data['ID'],
            givenName: $data['GivenName'] ?? '',
            familyName: $data['FamilyName'] ?? '',
        );
    }
}
