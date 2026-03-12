<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Tasks;

final readonly class TaskAssociatedCustomer
{
    public function __construct(
        public int $id,
        public ?string $companyName = null,
        public ?string $givenName = null,
        public ?string $familyName = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) $data['ID'],
            companyName: $data['CompanyName'] ?? null,
            givenName: $data['GivenName'] ?? null,
            familyName: $data['FamilyName'] ?? null,
        );
    }
}
