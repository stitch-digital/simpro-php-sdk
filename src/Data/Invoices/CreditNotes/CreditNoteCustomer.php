<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Invoices\CreditNotes;

final readonly class CreditNoteCustomer
{
    public function __construct(
        public int $id,
        public ?string $companyName = null,
        public ?string $givenName = null,
        public ?string $familyName = null,
        public ?string $phone = null,
        public ?string $address = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            companyName: $data['CompanyName'] ?? null,
            givenName: $data['GivenName'] ?? null,
            familyName: $data['FamilyName'] ?? null,
            phone: $data['Phone'] ?? null,
            address: $data['Address'] ?? null,
        );
    }
}
