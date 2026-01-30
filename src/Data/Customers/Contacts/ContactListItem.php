<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers\Contacts;

final readonly class ContactListItem
{
    public function __construct(
        public int $id,
        public ?string $givenName,
        public ?string $familyName,
        public ?string $email,
        public ?string $phone,
        public ?string $position,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            givenName: $data['GivenName'] ?? null,
            familyName: $data['FamilyName'] ?? null,
            email: $data['Email'] ?? null,
            phone: $data['Phone'] ?? null,
            position: $data['Position'] ?? null,
        );
    }
}
