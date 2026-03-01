<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Notes;

final readonly class CustomerNoteCustomer
{
    public function __construct(
        public int $id,
        public ?string $companyName = null,
        public ?string $givenName = null,
        public ?string $familyName = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) ($data['ID'] ?? 0),
            companyName: $data['CompanyName'] ?? null,
            givenName: $data['GivenName'] ?? null,
            familyName: $data['FamilyName'] ?? null,
        );
    }
}
