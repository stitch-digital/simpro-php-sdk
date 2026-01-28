<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Employees;

final readonly class EmployeeListItem
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $email,
        public ?string $phone,
        public ?bool $isArchived,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            name: $data['Name'] ?? '',
            email: $data['Email'] ?? null,
            phone: $data['Phone'] ?? null,
            isArchived: $data['IsArchived'] ?? null,
        );
    }
}
