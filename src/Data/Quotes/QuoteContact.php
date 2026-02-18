<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Quotes;

final readonly class QuoteContact
{
    public function __construct(
        public int $id,
        public ?string $givenName,
        public ?string $familyName,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) ($data['ID'] ?? 0),
            givenName: $data['GivenName'] ?? null,
            familyName: $data['FamilyName'] ?? null,
        );
    }
}
