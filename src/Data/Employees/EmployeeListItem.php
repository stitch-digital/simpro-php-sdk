<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Data\Employees;

final readonly class EmployeeListItem
{
    public function __construct(
        public int $id,
        public string $name,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['ID'],
            name: $data['Name'] ?? '',
        );
    }
}
