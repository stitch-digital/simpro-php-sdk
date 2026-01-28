<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Customers;

final readonly class CustomerListItem
{
    public function __construct(
        public int $id,
        public string $companyName,
        public string $type,
        public ?string $email,
        public ?string $phone,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            companyName: $data['CompanyName'] ?? '',
            type: $data['Type'] ?? '',
            email: $data['Email'] ?? null,
            phone: $data['Phone'] ?? null,
        );
    }
}
