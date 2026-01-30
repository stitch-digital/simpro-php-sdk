<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers;

/**
 * Customer contact reference.
 */
final readonly class CustomerContact
{
    public function __construct(
        public int $id,
        public ?string $givenName = null,
        public ?string $familyName = null,
        public ?string $email = null,
        public ?string $phone = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            givenName: $data['GivenName'] ?? null,
            familyName: $data['FamilyName'] ?? null,
            email: $data['Email'] ?? null,
            phone: $data['Phone'] ?? null,
        );
    }

    /**
     * Get the full name of the contact.
     */
    public function fullName(): string
    {
        return trim(($this->givenName ?? '').' '.($this->familyName ?? ''));
    }
}
