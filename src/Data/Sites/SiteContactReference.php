<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Sites;

final readonly class SiteContactReference
{
    public function __construct(
        public int $id,
        public ?string $givenName = null,
        public ?string $familyName = null,
        public ?string $email = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) ($data['ID'] ?? 0),
            givenName: $data['GivenName'] ?? null,
            familyName: $data['FamilyName'] ?? null,
            email: $data['Email'] ?? null,
        );
    }
}
