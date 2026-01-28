<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Jobs;

final readonly class JobCustomer
{
    public function __construct(
        public int $id,
        public string $companyName,
        public ?string $givenName,
        public ?string $familyName,
        public ?string $type,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            companyName: $data['CompanyName'] ?? '',
            givenName: $data['GivenName'] ?? null,
            familyName: $data['FamilyName'] ?? null,
            type: $data['Type'] ?? null,
        );
    }
}
