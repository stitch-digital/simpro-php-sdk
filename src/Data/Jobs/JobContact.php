<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs;

final readonly class JobContact
{
    public function __construct(
        public int $id,
        public ?string $name,
        public ?string $givenName,
        public ?string $familyName,
        public ?string $email,
        public ?string $phone,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            name: $data['Name'] ?? null,
            givenName: $data['GivenName'] ?? null,
            familyName: $data['FamilyName'] ?? null,
            email: $data['Email'] ?? null,
            phone: $data['Phone'] ?? null,
        );
    }
}
